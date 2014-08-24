<?php class CategoriesController extends AppController{

    /* Descripción: 		Función recursiva interna, Para personalizar el árbol.
     * tipo de solicitud: 	interna de la clase CategoriesController
     * tipo de acceso: 		administrativo
     * Recibe: 				un array anidado de categorías.
     * Retorna: 			un JSON  anidado de categorías, compatible con la librería tree.jquery.js
     *******************/
    private function recursive_tree($tree){
        $data = array();
        foreach($tree as $key => $val){

            // para efecto de corregir errores
            // $data[$key]['label'] 	= 'name: '.$val['Category']['name'].', id: '. $val['Category']['id'].', lft: '.$val['Category']['lft'].', rght: '.$val['Category']['rght'];

            $data[$key]['label'] 		= $val['Category']['name'];

            $data[$key]['id'] 			= $val['Category']['id'];
            $data[$key]['parent_id']	= $val['Category']['parent_id'];
            $data[$key]['lft']			= $val['Category']['lft'];
            $data[$key]['rght']			= $val['Category']['rght'];

            if($val['children']){
                $data[$key]['children'] 	=  $this->recursive_tree($val['children']);
            }else{
                $data[$key]['children'] 	= array();
            }
        }
        return $data;
    }

    /* Descripción: Función permite obtener el árbol listo para enviar a la vista.
     * tipo de solicitud: 	internar
     * tipo de acceso: 		administrativo
     * Recibe: 				null
     * Retorna: 			un array
     *******************/
    private function categories(){
        $return['countCategories'] = $this->{'Category'}->find('count');
        if($return['countCategories']){
            $categories = $this->{'Category'}->find('threaded', array(
                'order' => 'Category.lft',
                'contain' => false
            ));
            $return['categories'] = $this->recursive_tree($categories);
        }
        return $return;
    }


    /* Descripción: Función principal , permite visualizar las categorías, entre otras acciones administrativas realacionadas.
     * tipo de solicitud: 	get (no-ajax)
     * tipo de acceso: 		administrativo
     * Recibe: 				null
     * Retorna: 			un array
     *******************/
    public function index(){

        $return['total'] = $this->{'Category'}->find('count');

        if($return['total'] > 0){
            $categories = $this->{'Category'}->find('threaded', array(
                'order' => 'Category.lft',
                'contain' => false
            ));
            $return['categories'] = json_encode($this->recursive_tree($categories));
        }

        $this->{'set'}('categories',$return);

    }

    /* Descripción: Función para crear nuevas categorías.
     * tipo: 				ajax-post,ajax-get
     * tipo de acceso: 		administrativo
     * Recibe:			 	array
     * Retorna: 			un array, el cual sera transformado en un objeto JSON en la vista ajax_view.
     *******************/
    public function new_category(){
        $request = $this->{'request'}->input('json_decode',true);

        $category =	array(
            'Category'=>Array
            (
                'name'	=>	$request['name']
            )
        );

        $this->{'Category'}->set($category);
        if($this->{'Category'}->validates()){
            if($this->{'Category'}->save($category)){
                $return['save'] = true;
            }else{
                $return['save'] = false; // ++++++++++++++ ha ocurrido un error +++++++++++++++
            }
            $return['validates'] = true;
        }else{
            $return['validates'] = false;
            // se envía los campos faltantes o algún error que pudiese haber ocurrido.
            $errors = $this->{'Category'}->validationErrors;
            foreach($errors as $k=>$v){
                $tmp = 'category_'.$k;
                $return['fields'][Inflector::camelize($tmp)] = $v[0];
            }
        }

        $return += $this->categories();

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    /* Descripción: Función para editar el nombre de la categoría.
     * tipo: 				ajax-post, ajax-get
     * tipo de acceso: 		administrativo
     * Recibe:			 	array
     * Retorna: 			un array, el cual sera transformado en un objeto JSON en la vista ajax_view.
     *******************/
    public function edit_category_name(){
        $request = $this->{'request'}->input('json_decode',true);

        $category = array(
            'Category'=>array(
                'id'	=>$request['id'],
                'name'	=>$request['name']
            )
        );

        $this->{'Category'}->set($category);
        if($this->{'Category'}->validates()){
            if($this->{'Category'}->save($category)){
                $return = $this->{'Category'}->read();
                $return['save'] = true;
            }else{
                $return['save'] = false; // ++++++++++++++ ha ocurrido un error +++++++++++++++
            }
            $return['validates'] = true;
        }else{
            $return['validates'] = false;
            // se envía los campos faltantes o algún error que pudiese haber ocurrido.
            $errors = $this->{'Category'}->validationErrors;
            foreach($errors as $k=>$v){
                $tmp = 'category_'.$k;
                $return['fields'][Inflector::camelize($tmp)] = $v[0];
            }
        }

        $return += $this->categories();

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    /* Descripción: Función para borra una categoría y sus categorías hijas si es requerido. Actualiza todo el árbol cada vez que es llamada.
     * tipo: 				ajax-post, ajax-get
     * tipo de acceso: 		administrativo
     * Recibe:			 	array
     * Retorna: 			un array, el cual sera transformado en un objeto JSON en la vista ajax_view.
    *******************/
    public function delect_category(){
        $request = $this->{'request'}->input('json_decode',true);

        $theWholeBranch 	= $request['theWholeBranch'];
        $id					= $request['id'];

        $category = $this->{'Category'}->find('first', array(
            'conditions' => array('Category.id' => $id),
            'contain' => false
        ));

        $return = array();

        if($category){
            if($theWholeBranch == 1){
                $return['status'] = $this->{'Category'}->delete();
            }else{
                $return['status'] = $this->{'Category'}->removeFromTree($id, true);
            }
        }

        $return += $this->categories();

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    /* Descripción: Función para cambiar la posición de la categoría en el árbol, principalmente tiene dos secciones 1) solo mover de arriba a abajo y viceversa 2) primero establecer el parent_id y luego mover de arriba a abajo y viceversa
     * tipo:                ajax-post, ajax-get
     * tipo de acceso:      administrativo
     * Recibe:              array
     * Retorna:             un array, el cual sera transformado en un objeto JSON en la vista ajax_view.
    *******************/

    public function edit_category_position(){
        $request = $this->{'request'}->input('json_decode',true);


        //print_r($request);

        if($request['type'] == 'only_move'){
            $id 			= (int)$request['id'];
            $category = $this->{'Category'}->find('first', array(
                'conditions' => array('Category.id' => $id),
                'contain' => false
            ));
            if($category){
                $positions = $this->{'Category'}->find('count', array(
                    'conditions' => array('Category.lft >=' => $request['min'],'Category.rght <=' => $request['max'],'Category.parent_id' => $request['parent_id'])
                ));
                if($positions > 0){
                    if($request['move_to'] == 'moveDown'){
                        $return['result'] = $this->{'Category'}->moveDown($id, $positions);
                    }
                    if($request['move_to'] == 'moveUp'){
                        $return['result'] = $this->{'Category'}->moveUp($id, $positions);
                    }
                }
            }
        }
        if($request['type'] == 'set_parent_and_move'){

            $new_parent_id 			= 	(int)$request['new_parent_id'];
            $moved_node_id			=	(int)$request['moved_node_id'];
            $target_node_id			=	(int)$request['target_node_id'];
            $position				=	$request['position'];

            $category = array(
                'Category'=>array(
                    'id'		=>$moved_node_id,
                    'parent_id'	=>$new_parent_id
                )
            );

            if($position == 'inside'){
                /*  Descripción:
                 *  antes de insertar la categoría observamos cuantos hijos tiene la categoría que sera populada, es decir cuantos hijos tiene target_node_id,
                 *  si no tiene ninguno la categoría simplemente se insert,  si tiene hijos el valor que arroje sera el numero de posiciones que la categoría tendrá que subir para estar de primera.
                 *  es importante recordar que la categoría al ser insertada o a establecerle un nuevo parent_id es ordenada de ultima.
                */


                $position_length = $this->{'Category'}->childCount($target_node_id, true);

                if($this->{'Category'}->save($category)){
                    $return['save_new_parent_id'] 	= true;
                }

                if($position_length == 0){
                    $return['status'] 				=  'se mantiene';
                }elseif($position_length > 0){
                    $this->{'Category'}->moveUp($moved_node_id, $position_length);
                    $return['status'] 				=  'subió '.$position_length.' posiciones.';
                }
            }

            if($position == 'before'){
                /* Descripción:
                 * Antes de establecer el parent_id se cuenta cuantas categorías existen con parent_id == null tal valor
                 * representa el numero de posiciones que la categoría sera subida para posicionarse de primera.
                 * es importante recordar que posición es before solo cuando la categoría es posicionada de primera sin padres es decir es un caso unico.
                */

                $position_length = $this->{'Category'}->find('count',array(
                    'conditions' => array('Category.parent_id' => null)
                ));

                if($this->{'Category'}->save($category)){
                    $return['save_new_parent_id'] 	= true;
                }

                $this->{'Category'}->moveUp($moved_node_id, $position_length);
                $return['status'] 	=  'subió '.$position_length.' posiciones.';
            }

            if($position == 'after'){
                /* Descripción:
                 * La categoría tiene dos opciones mantenerse o subir, si la categoría es sucesiva se ha de suponer que el admin la coloco de ultima por lo tanto no es necesario subir la categoría
                 * se calcula el mínimo y maximo junto con el parent_id de la categoría movida permitirá consulta cuantas categoría directas (directChildren) existen entre la categoría movida y la sucesiva o target.
                 */

                if($this->{'Category'}->save($category)){
                    $return['save_new_parent_id'] 	= true;
                }

                $moved_node = $this->{'Category'}->find('first', array(
                    'conditions' => array('Category.id' => (int)$moved_node_id),
                    'contain' => false
                ));

                $target_node = $this->{'Category'}->find('first', array(
                    'conditions' => array('Category.id' => (int)$target_node_id),
                    'contain' => false
                ));

                $max		= $moved_node['Category']['lft']-1;
                $min 		= $target_node['Category']['rght']+1;
                $parent_id	= $moved_node['Category']['parent_id'];

                $position_length = $this->{'Category'}->find('count', array(
                    'conditions' => array('Category.lft >=' => $min,'Category.rght <=' => $max,'Category.parent_id' => $parent_id)
                ));

                if($position_length > 0){
                    $this->{'Category'}->moveUp($moved_node_id, $position_length);
                    $return['status'] = 'subió '.$position_length.' posiciones.';
                }else{
                    // son sucesivos
                    $return['status'] =  'se mantiene';
                }

            }

        }

        if(isset($return)){
            $return += $this->categories();
        }else{
            $return = null;
        }

        $this->set('return',$return);
        $this->render('ajax_view','ajax');
    }

    public function edit_category_position2(){
        $request = $this->{'request'}->input('json_decode',true);

        //print_r($request);

        if($request['type'] == 'only_move'){
            $id 			= (int)$request['id'];
            $category = $this->{'Category'}->find('first', array(
                'conditions' => array('Category.id' => $id),
                'contain' => false
            ));
            if($category){
                $positions = $this->{'Category'}->find('count', array(
                    'conditions' => array('Category.lft >=' => (int)$request['min'],'Category.rght <=' => (int)$request['max'],'Category.parent_id' => (int)$request['parent_id'])
                ));

                debug($positions,false);

                if($positions > 0){
                    if($request['move_to'] == 'moveDown'){
                        $return['result'] = $this->{'Category'}->moveDown($id, $positions);
                    }
                    if($request['move_to'] == 'moveUp'){
                        $return['result'] = $this->{'Category'}->moveUp($id, $positions);
                    }
                }
            }
        }
        if($request['type'] == 'set_parent_and_move'){

            $new_parent_id          =   (int)$request['new_parent_id'];
            $moved_node_id          =   (int)$request['moved_node_id'];
            $target_node_id         =   (int)$request['target_node_id'];
            $position               =   $request['position'];

            $category = array(
                'Category'=>array(
                    'id'        =>$moved_node_id,
                    'parent_id' =>$new_parent_id
                )
            );

            if($position == 'inside'){
                /*  Descripción:
                 *  antes de insertar la categoría observamos cuantos hijos tiene la categoría que sera populada, es decir cuantos hijos tiene target_node_id,
                 *  si no tiene ninguno la categoría simplemente se insert,  si tiene hijos el valor que arroje sera el numero de posiciones que la categoría tendrá que subir para estar de primera.
                 *  es importante recordar que la categoría al ser insertada o a establecerle un nuevo parent_id es ordenada de ultima.
                */

                $position_length = $this->{'Category'}->childCount($target_node_id, true);

                if($this->{'Category'}->save($category)){
                    $return['save_new_parent_id'] 	= true;
                }

                if($position_length == 0){
                    $return['status'] 				=  'se mantiene';
                }elseif($position_length > 0){
                    $this->{'Category'}->moveUp($moved_node_id, $position_length);
                    $return['status'] 				=  'subió '.$position_length.' posiciones.';
                }
            }

            if($position == 'before'){
                /* Descripción:
                 * Antes de establecer el parent_id se cuenta cuantas categorías existen con parent_id == null tal valor
                 * representa el numero de posiciones que la categoría sera subida para posicionarse de primera.
                 * es importante recordar que posición es before solo cuando la categoría es posicionada de primera sin padres es decir es un caso unico.
                */

                $position_length = $this->{'Category'}->find('count',array(
                    'conditions' => array('Category.parent_id' => null)
                ));

                if($this->{'Category'}->save($category)){
                    $return['save_new_parent_id'] 	= true;
                }

                $this->{'Category'}->moveUp($moved_node_id, $position_length);
                $return['status'] 	=  'subió '.$position_length.' posiciones.';
            }

            if($position == 'after'){
                /* Descripción:
                 * La categoría tiene dos opciones mantenerse o subir, si la categoría es sucesiva se ha de suponer que el admin la coloco de ultima por lo tanto no es necesario subir la categoría
                 * se calcula el mínimo y maximo junto con el parent_id de la categoría movida permitirá consulta cuantas categoría directas (directChildren) existen entre la categoría movida y la sucesiva o target.
                 */

                if($this->{'Category'}->save($category)){
                    $return['save_new_parent_id'] 	= true;
                }

                $moved_node = $this->{'Category'}->find('first', array(
                    'conditions' => array('Category.id' => (int)$moved_node_id),
                    'contain' => false
                ));

                $target_node = $this->{'Category'}->find('first', array(
                    'conditions' => array('Category.id' => (int)$target_node_id),
                    'contain' => false
                ));

                $max		= $moved_node['Category']['lft']-1;
                $min 		= $target_node['Category']['rght']+1;
                $parent_id	= $moved_node['Category']['parent_id'];

                $position_length = $this->{'Category'}->find('count', array(
                    'conditions' => array('Category.lft >=' => $min,'Category.rght <=' => $max,'Category.parent_id' => $parent_id)
                ));

                if($position_length > 0){
                    $this->{'Category'}->moveUp($moved_node_id, $position_length);
                    $return['status'] = 'subió '.$position_length.' posiciones.';
                }else{
                    // son sucesivos
                    $return['status'] =  'se mantiene';
                }

            }

        }

        if(isset($return)){
            $return += $this->categories();
        }else{
            $return = null;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }



    /*
        Descripción: Función para obtener los registros dependientes de una categoría, obtener los registros donde paren_id es el id de la categoría suministrada. Además se actualiza el path: electrónicos>laptops>etc

        Tipo:               Ajax
        Tipo de acceso:     vendedor
        Recibe:             Array
        Retorna:            Un array, el cual será transformado en un objeto JSON en la vista ajax_view.
    */

    public function get_category_child_elements(){
        $request = $this->{'request'}->input('json_decode',true);


        $category_id = $request['category_id'];

        $children = $this->{'Category'}->find('all', array(
            'conditions' => array('Category.parent_id'=>$category_id),
            'order' => 'lft ASC',
            'contain' => false
        ));

        if($children){
            foreach($children as $k => $v){
                $return['categories'][$v['Category']['id']] = $v['Category']['name'];
            }
            $return['children'] = true;
        }else{
            $return['category_id_selected'] = $request['category_id'];
            $return['children'] 			= false;
        }

        $path = $this->{'Category'}->getPath($category_id);
        $_path = array();
        foreach($path as $k => $v){
            $_path[$k]['id'] 	= $v['Category']['id'];
            $_path[$k]['name']	= $v['Category']['name'];

        }

        $return['path']                 = $_path;
        $return['id']                   = $category_id;

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


}