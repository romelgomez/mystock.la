<?php class ProductsController extends AppController {

    public function beforeFilter(){

        $this->{'Auth'}->allow(['stock','products']);

        parent::beforeFilter();
    }



    /*
        Descripción:        Función para ver un producto, ofertar y preguntar por el.
        Tipo de solicitud:  Get (no-ajax).
        Tipo de acceso:     Público.
        Recibe:             Array.
        Retorna:            Array.
    */
    public function view(){
        $user_logged    = $this->{'Auth'}->User();
        $this->{'loadModel'}('Category');

        if(isset($this->{'params'}->id)){
            $id = $this->{'params'}->id;
            // para editar principalmente.
            $product_data = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $id,'Product.company_id'=>$user_logged['User']['company_id'],'Product.deleted'=>0)
            ));

            if($product_data){

                if($product_data['Image']){
                    $this->{'loadModel'}('Image');

                    $data = array();

                    foreach($product_data['Image'] as $index_0 => $original_imagen){

                        //debug($original_imagen);
                        $data[$index_0]['original'] 		= $original_imagen;
                        $products[$index_0]['children'] 	=  $this->{'Image'}->find('all',array(
                                'conditions' => array('Image.parent_id' => $original_imagen['id']),
                                'contain' => false
                            )
                        );
                        foreach($products[$index_0]['children'] as $children){

                            $namespace = '';

                            switch ($children['Image']['size']) {
                                case '1920x1080':
                                    $namespace = 'large';
                                    break;
                                case '900x900':
                                    $namespace = 'median';
                                    break;
                                case '400x400px':
                                    $namespace = 'small';
                                    break;
                            }

                            $data[$index_0]['thumbnails'][$namespace] = $children['Image'];

                        }
                    }

                    $product_data['Image'] = array();
                    $product_data['Image'] = $data;
                }

                $this->{'request'}->data = $product_data;

            }else{
                $this->{'redirect'}('/');
            }
        }

    }

    /*
        Descripción: Función principal, solicitada por el vendedor para cargar un producto.
        tipo de solicitud: Get
        tipo de acceso: vendedor
        Recibe:         Array.
        Retorna:        Array.
    */
    public function add(){
        $user_logged    = $this->{'Auth'}->User();
        $this->{'loadModel'}('Category');

        $url_action = strstr($this->{'request'}->url, '/', true); // Desde PHP 5.3.0
        $this->{'set'}('url_action',$url_action);


        if(isset($this->{'params'}->id)){
            $id = $this->{'params'}->id;
            // para editar principalmente.
            $product_data = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $id,'Product.company_id'=>$user_logged['User']['company_id'],'Product.deleted'=>0)
            ));

            if($product_data){

                /* tiene permisos para editar la publicación o un borrador pero esta jugando con la url
                 ****************************************************************************************/
                if($url_action == 'editar'){
                    if(!$product_data['Product']['published'] == 1){
                        // debug('esta editando un borrador');
                        $this->{'redirect'}('/');
                    }
                }
                if($url_action == 'editar_borrador'){
                    if(!$product_data['Product']['published'] == 0){
                        //debug('esta editando un publicado');
                        $this->{'redirect'}('/');
                    }
                }

                /* Se extrae la data relacionada con la categoría seleccionada para establecer o reconstruir el menú y path tal y como el vendedor lo dejo por última vez
                 *******************************************************************************************************************************************************/
                $category_id = $product_data['Product']['category_id'];

                /* menu
                 *******/
                $path = $this->{'Category'}->getPath($category_id);

                $menu = array();

                foreach($path as $index => $category){
                    $menu[$index]['id']             = $category['Category']['id'];
                    $menu[$index]['name']           = $category['Category']['name'];

                    $children                       = $this->get_category_child_elements($category['Category']['id']);

                    if($children['children']){
                        $menu[$index]['categories'] 	= $children['categories'];
                        $menu[$index]['children'] 		= true;
                    }else{

                        $menu[$index]['category_id_selected'] 	= $children['category_id_selected'];
                        $menu[$index]['children'] 				= false;
                    }
                }

                if($product_data['Image']){
                    $this->{'loadModel'}('Image');

                    $data = array();

                    foreach($product_data['Image'] as $index_0 => $original_imagen){

                        //debug($original_imagen);
                        $data[$index_0]['original'] 		= $original_imagen;
                        $products[$index_0]['children'] 	=  $this->{'Image'}->find('all',array(
                                'conditions' => array('Image.parent_id' => $original_imagen['id']),
                                'contain' => false
                            )
                        );
                        foreach($products[$index_0]['children'] as $children){

                            $namespace = '';

                            switch ($children['Image']['size']) {
                                case '1920x1080':
                                    $namespace = 'large';
                                    break;
                                case '900x900':
                                    $namespace = 'median';
                                    break;
                                case '400x400px':
                                    $namespace = 'small';
                                    break;
                            }

                            $data[$index_0]['thumbnails'][$namespace] = $children['Image'];

                        }
                    }

                    $product_data['Image'] = array();
                    $product_data['Image'] = $data;
                }

                $this->{'request'}->data = $product_data;
                $this->{'request'}->data['current-menu'] = json_encode($menu);

            }else{
                $this->{'redirect'}('/');
            }
        }

        $categories = $this->{'Category'}->find('all', array(
            'conditions' => array('Category.parent_id'=>null),
            'order' => 'lft ASC',
            'contain' => false
        ));
        // la base del árbol de categorías en la vista.
        $this->{'set'}('base_menu',$categories);
    }

    /*
        Descripción:        Función para obtener las categorías dependientes o hijas de la categoría proporcionada.
        Tipo de solicitud:  Interna.
        Recibe:             Int.
        Retorna:            Array.
    */
    private function get_category_child_elements($category_id){
        $children = $this->{'Category'}->find('all', array(
            'conditions' => array('Category.parent_id'=>$category_id),
            'order' => 'lft ASC'
        ));
        if($children){
            foreach($children as $v){
                $return['categories'][$v['Category']['id']] = $v['Category']['name'];
            }
            $return['children'] = true;

        }else{
            $return['category_id_selected'] = $category_id;
            $return['children'] = false;
        }
        return $return;
    }

    /*
        Descripción: Función destinada a añadir una nueva publicación, los datos suministrados deberán estar completos,
        Tipo de solicitud: Ajax
        Tipo de acceso: Vendedor
        Recibe: Array.
        Retorna: Un array, el cual será transformado en un objeto JSON en la vista ajax_view.
    */
    public function add_new(){

        $request = $this->{'request'}->input('json_decode',true);

        $user_logged = $this->{'Auth'}->User();

        /*
        $request
        {
           "id":"6",
           "category_id":"145",
           "title":"hola",
           "body":"hola",
           "price":"21",
           "quantity":"32"
        }
        */

        $this->{'loadModel'}('Image');
        if($request['id']){
            if($this->{'Image'}->find('first',array('conditions' => array('Image.product_id' => $request['id'],'Image.status' => 1)))){
                // si hay imágenes subidas y aprobadas
                $this->{'Product'}->set($request);
                if($this->{'Product'}->validates()){

                    // se verifica que el usuario esté trabajando en un post suyo o de otro vendedor de misma compañía
                    if($request['id']){
                        $isOk = $this->{'Product'}->find('first', array(
                            'conditions' => array('Product.id' => $request['id'],'Product.company_id'=>$user_logged['User']['company_id'])
                        ));

                        if($isOk){

                            $producto =	array(
                                'Product'=>Array
                                (
                                    'id'            =>  $request['id'],
                                    'company_id'    =>  $user_logged['User']['company_id'],
                                    'user_id'       =>  $user_logged['User']['id'],
                                    'category_id'   =>  $request['category_id'],
                                    'title'         =>  $request['title'],
                                    'body'          =>  $request['body'],
                                    'price'         =>  $request['price'],
                                    'quantity'      =>  $request['quantity'],
                                    'status'        =>  1,
                                    'published'     =>  1,
                                    'banned'        =>  0,
                                    'deleted'       =>  0
                                )
                            );

                            if($this->{'Product'}->save($producto)){
                                $return['result'] 			= true;
                                $product_data 				= $this->{'Product'}->read();
                                $return['product_id']		= $product_data['Product']['id'];
                                $return['product_title']	= $product_data['Product']['title'];
                            }

                        }else{
                            // esta intentando modificar un posts de otra compañía
                        }

                    }

                }
            }
        }

        if(!isset($return['result'])){
            $return['result'] = false;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


    /*

        Descripción:        Función para guardar un borrador.
        tipo de solicitud:  Get (no-ajax)
        tipo de acceso:     Vendedor
        Recibe:             Array.
        Retorna:            Un array, el cual será transformado en un objeto JSON en la vista ajax_view.

    */

    public function saveDraft(){
        $request = $this->{'request'}->input('json_decode',true);

        $user_logged = $this->{'Auth'}->User();

        // Esta lógica es cuando se guardó un borrador, por lo tanto existe un id ya definido. Se verifica que el usuario esté trabajando en un post suyo o de otro vendedor de misma compañía de no cumplir o intentar modificar el dom, el script creará otro borrador.
        if($request['id']){
            $isOk = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $request['id'],'Product.company_id'=>$user_logged['User']['company_id'])
            ));
            if($isOk){
                $id = $request['id'];
            }else{
                $id = null;
                // esta intentando modificar una publicación de otra compañía
            }
        }else{
            $id = null;
        }

        $producto =	array(
            'Product'=>Array
            (
                'id'            =>$id,
                'user_id'       =>$user_logged['User']['id'],
                'category_id'   =>$request['category_id'],
                'company_id'    =>$user_logged['User']['company_id'],
                'title'         =>$request['title'],
                'body'			=>$request['body'],
                'price'			=>$request['price'],
                'quantity'		=>$request['quantity'],
            )
        );

        $return = array();

        if($this->{'Product'}->save($producto,false)){
            $productData = $this->{'Product'}->read();

            $id = $productData['Product']['id'];
            $lastSave = $productData['Product']['modified'];

            $return['id'] = $id;

            // TODO BUSCAR LOGRAR OTRA SOLUCIÓN
            // 2.1
            App::uses('CakeTime', 'Utility');
            $return['time'] = CakeTime::format('H:i',$lastSave);

        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    public function search_publications(){
    }

    /*
        Descripción:        Función para visualizar todos los productos publicados.
        tipo de solicitud:  get (no ajax)
        tipo de acceso:     vendedor
        Recibe:             NULL
        Retorna:            NULL
    */
    public function published(){
    }

    /*
        Descripción:        Función para visualizar todos los productos en estatus de borrador.
        Tipo de solicitud:  Get (no ajax)
        Tipo de acceso:     Vendedor
        Recibe:             NULL
        Retorna:            NULL
    */
    public function drafts(){
    }

    /*
        Descripción:        Función para visualizar todos los productos en estatus de publicados de x usuario.
        Tipo de solicitud:  Get (no ajax)
        Tipo de acceso:     All can access
        Recibe:             NULL
        Retorna:            NULL
    */
    public function stock(){
        $this->{'loadModel'}('User');

        if(isset($this->{'params'}->id)){
            $id = $this->{'params'}->id;
            $user = $this->{'User'}->find('first', array(
                'conditions' => array('User.id' => $id)
            ));

            if($user){
                $this->{'request'}->data = $user;
            }else{
                $this->{'redirect'}('/');
            }
        }else{
            $this->{'redirect'}('/');
        }
    }

    /*
        Descripción:
        tipo de solicitud:  Ajax
        tipo de acceso:     vendedor
        Recibe:             null
        Retorna:            un array, el cual será transformado en un objeto JSON en la vista ajax_view
    */
    public function products(){
        $request = $this->{'request'}->input('json_decode',true);

        $user_logged = $this->{'Auth'}->User();

        $url = $this->{'request'}->url;

        $return = array();

        $conditions = array();

        if($url == 'published' || $url == 'drafts' || $url == 'stock-products' ){
            if($url == 'stock-products'){

                // search - conditions
                if(!isset($request['search']) || $request['search'] == ''){
                    $conditions = array('Product.user_id' => $request['user_id'],'Product.deleted'=>0,'Product.published'=>1);
                }else{
                    $search = $this->cleanString($request["search"]);
                    $return["search"] = $search;
                    $conditions = array(
                        'Product.user_id' => $request['user_id'],
                        'Product.deleted'=>0,
                        'Product.published'=>1,
                        'or'=>array(
                            'Product.title LIKE'=> '%'.$search.'%',
                            'Product.body LIKE'=> '%'.$search.'%'
                        )
                    );
                }

                // total_products es la cantidad total de productos publicados, este resultado es indiferente a los filtros aplicados por el usuario.
                $return['total_products'] = $this->{'Product'}->find('count', array('conditions'=> array('Product.user_id' => $request['user_id'],'Product.deleted'=>0,'Product.published'=>1)));

            }
            if($url == 'published'){
                // search - conditions
                if(!isset($request['search']) || $request['search'] == ''){
                    $conditions = array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1);
                }else{
                    $search = $this->cleanString($request["search"]);
                    $return["search"] = $search;
                    $conditions = array(
                        'Product.company_id' => $user_logged['User']['company_id'],
                        'Product.deleted'=>0,
                        'Product.published'=>1,
                        'or'=>array(
                            'Product.title LIKE'=> '%'.$search.'%',
                            'Product.body LIKE'=> '%'.$search.'%'
                        )
                    );
                }

                // total_products es la cantidad total de productos publicados, este resultado es indiferente a los filtros aplicados por el usuario.
                $return['total_products'] = $this->{'Product'}->find('count', array('conditions'=> array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1)));

            }
            if($url == 'drafts'){
                // search - conditions
                if(!isset($request['search']) || $request['search'] == ''){
                    $conditions = array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>0);
                }else{
                    $search = $this->cleanString($request["search"]);
                    $return["search"] = $search;
                    $conditions = array(
                        'Product.company_id' => $user_logged['User']['company_id'],
                        'Product.deleted'=>0,
                        'Product.published'=>0,
                        'or'=>array(
                            'Product.title LIKE'=> '%'.$search.'%',
                            'Product.body LIKE'=> '%'.$search.'%'
                        )
                    );
                }

                // total_products es la cantidad total de productos publicados, este resultado es indiferente a los filtros aplicados por el usuario.
                $return['total_products'] = $this->{'Product'}->find('count', array('conditions'=> array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>0)));
            }

            // page
            if(!isset($request['page'])  || $request['page'] == ''){
                $page = 1;
            }else{
                $page = (int)$request['page'];
            }

            // order
            $order = array();
            if(!isset($request['order_by']) || $request['order_by'] == ''){

                $order = array(
                    'Product.created' => 'desc'
                );

            }else{

                if($request['order_by'] == "mayor_precio"){

                    $order = array(
                        'Product.price' => 'desc'
                    );
                }
                if($request['order_by'] == "menor_precio"){

                    $order = array(
                        'Product.price' => 'asc'
                    );
                }
                if($request['order_by'] == "recientes"){


                    $order = array(
                        'Product.created' => 'desc'
                    );

                }
                if($request['order_by'] == "antiguos"){

                    $order = array(
                        'Product.created' => 'asc'
                    );

                }

                if($request['order_by'] == "mayor_disponibilidad"){

                    $order = array(
                        'Product.quantity' => 'desc'
                    );
                }
                if($request['order_by'] == "menor_disponibilidad"){

                    $order = array(
                        'Product.quantity' => 'asc'
                    );
                }

            }

            $this->{'paginate'} = array(
                'conditions' =>  $conditions,
                'contain' => array(
                    'Image'=>array(
                    )
                ),
                'order' => $order,
                'limit' => 10,
                'page'	=>$page
            );


            try {
                $products = $this->{'paginate'}('Product');
                if($products){
                    $return['data']	= $this->product_images($products);
                }else{
                    $return['data'] = array();
                }
                $return['result']	= true;
            }catch(Exception $e){
                // se re-direcciona a “/”
                $return['result'] = false;
            }

            $return['info'] = $this->{'request'}->params['paging']['Product'];

        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


    /*
        Descripción: Función para descartar borrador registrado o no. es preciso hacer pasar la solicitud aun cuando no esté registrado el borrador con el fin de establecer un mensaje de éxito de sección ya que se cambia de vista.
        Tipo de solicitud:  Ajax
        Tipo de acceso:     Vendedor
        Recibe:             Un array.
        Retorna:            Un array. el cual será transformado en un objeto JSON en la vista ajax_view.
    */
    public function discard(){

        $request = $this->{'request'}->input('json_decode',true);

        $user_logged = $this->{'Auth'}->User();

        $return = array();

        if($request['row_exist'] == 'true'){

            $isOk = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $request['id'],'Product.company_id'=>$user_logged['User']['company_id'])
            ));

            if($isOk){

                $productData['Product']['id'] 		= $request['id'];
                $productData['Product']['deleted'] 	= 1;

                if($this->{'Product'}->save($productData)){
                    $return['result'] = true;
//                    $this->{'Session'}->setFlash('El borrador ha sido descartado.','success');
                }

            }else{
                // esta intentando de borrar un posts de otra compañía.

                $return['result'] = false;
//                $this->{'Session'}->setFlash('Ha ocurrido un error.','error');
            }

        }else{

            $return['result'] 		= true;
//            $this->{'Session'}->setFlash('El borrador ha sido descartado','success');

        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    /*
        Descripción:  Función para pausar una publicación.
        tipo de solicitud: 	get-ajax,post-ajax
        tipo de acceso:  Vendedor
        Recibe:  Un array.
        Retorna: Un array. el cual será transformado en un objeto JSON en la vista ajax_view.
    */
    public function pause(){

        $request = $this->{'request'}->input('json_decode',true);

        $user_logged = $this->{'Auth'}->User();

        $product_data = $this->{'Product'}->find('first', array(
            'conditions' => array('Product.id' => $request['id'],'Product.company_id' => $user_logged['User']['company_id'])
        ));

        if($product_data){
            $data['Product']['id'] 		= $product_data['Product']['id'];
            $data['Product']['status']	= 0;

            if($this->{'Product'}->save($data)){
                $return['result'] 	= true;
                $return['id'] 		= $request['id'];
            }else{
                $return['result'] = false;
            }
        }else{
            $return['result'] = false;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');

    }

    /*
        Descripción:  Función para pausar una publicación.
        tipo de solicitud: 	get-ajax,post-ajax
        tipo de acceso:  Vendedor
        Recibe:  Un array.
        Retorna: Un array. el cual será transformado en un objeto JSON en la vista ajax_view.
    */
    public function activate(){

        $request = $this->{'request'}->input('json_decode',true);

        $user_logged = $this->{'Auth'}->User();

        $product_data = $this->{'Product'}->find('first', array(
            'conditions' => array('Product.id' => $request['id'],'Product.company_id' => $user_logged['User']['company_id'])
        ));

        if($product_data){
            $data['Product']['id'] 		= $product_data['Product']['id'];
            $data['Product']['status']	= 1;

            if($this->{'Product'}->save($data)){
                $return['result'] 	= true;
                $return['id'] 		= $request['id'];
            }else{
                $return['result'] = false;
            }
        }else{
            $return['result'] = false;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    /*
        Descripción:        Función para borrar una publicación.
        Tipo de solicitud:  get-ajax,post-ajax
        Tipo de acceso:     Vendedor
        Recibe:             Array.
        Retorna:            Array. el cual sera transformado en un objeto JSON en la vista ajax_view.
     */
    public function delete(){

        $request = $this->{'request'}->input('json_decode',true);


        $user_logged = $this->{'Auth'}->User();

        $product_data = $this->{'Product'}->find('first', array(
            'conditions' => array('Product.id' => $request['id'],'Product.company_id' => $user_logged['User']['company_id'])
        ));

        if($product_data){
            $product['Product']['id'] 		= $product_data['Product']['id'];
            $product['Product']['deleted']	= 1;

            if($this->{'Product'}->save($product)){
                $return['result'] 	= true;
                $return['id'] 		= $request['id'];

                // establecemos un mensaje luego de borrar la publicación desde la vista de editar.
                if($request['session'] == 'true'){
                    $this->{'Session'}->setFlash('La publicación ha sido borrada.','success');
                }

                /* START retornamos resultados actualizados - paginados a vista /publicados
                 ***************************************************************************/
                if(isset($request['paginate'])){

                    // se establecen varibles.
                    if(!isset($request['page'])){
                        $page = 1;
                    }else{
                        $page = (int)$request['page'];
                    }

                    if(!isset($request['order_by'])){
                        // recientes
                        $order = array(
                            'Product.created' => 'desc'
                        );
                        $win_order_by = "recientes";
                    }else{
                        switch ($request['order_by']) {
                            case "mayor_precio":

                                $order = array(
                                    'Product.price' => 'desc'
                                );
                                $win_order_by = "mayor_precio";

                                break;
                            case "menor_precio":

                                $order = array(
                                    'Product.price' => 'asc'
                                );
                                $win_order_by = "menor_precio";

                                break;
                            case "recientes":

                                $order = array(
                                    'Product.created' => 'desc'
                                );
                                $win_order_by = "recientes";

                                break;
                            case "antiguos":

                                $order = array(
                                    'Product.created' => 'desc'
                                );
                                $win_order_by = "antiguos";

                                break;
                            case "mayor_disponibilidad":

                                $order = array(
                                    'Product.created' => 'asc'
                                );
                                $win_order_by = "mayor_disponibilidad";

                                break;
                            case "menor_disponibilidad":

                                $order = array(
                                    'Product.quantity' => 'asc'
                                );
                                $win_order_by = "menor_disponibilidad";

                                break;
                            default:

                                // recientes
                                $order = array(
                                    'Product.created' => 'desc'
                                );
                                $win_order_by = "recientes";

                        }
                    }

                    $return['win_order_by'] = $win_order_by;


                    /* START se intenta 3 veces lograr retornar resultados paginados.
                     *******************************************************************************************************************************************/
                    try {
                        /* Primer intento:  pagina actual
                         *******************************************************/
                        $this->{'paginate'}= array(
                            'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
                            'contain' => array(
                                'Image'=>array(
                                )
                            ),
                            'order' => $order,
                            'limit' => 10,
                            'page'	=>$page
                        );

                        $products = $this->{'paginate'}('Product');

                        if($products){
                            $return['data'] 	= $this->product_images($products);
                        }

                    } catch (Exception $e) {
                        try {
                            /* Segundo intento:  pagina anterior
                            *******************************************************/
                            $previous_page = $page-1;

                            $this->{'paginate '}= array(
                                'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
                                'contain' => array(
                                    'Image'=>array(
                                    )
                                ),
                                'order' => $order,
                                'limit' => 10,
                                'page'	=>$previous_page
                            );

                            $products = $this->{'paginate'}('Product');

                            if($products){
                                $return['data'] = $this->product_images($products);
                            }

                        }catch (Exception $e){
                            try {
                                /* Tercer intento:  ultima pagina disponible
                                *******************************************************/
                                $last_page = $this->{'request'}->params['paging']['Product']['pageCount'];

                                $this->{'paginate'}= array(
                                    'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
                                    'contain' => array(
                                        'Image'=>array(
                                        )
                                    ),
                                    'order' => $order,
                                    'limit' => 10,
                                    'page'	=>$last_page
                                );

                                $products = $this->{'paginate'}('Product');

                                if($products){
                                    $return['data'] = $this->product_images($products);
                                }

                            }catch (Exception $e){
                                // Error inesperado. Re-direcciona a /
                                $return['result'] = false;
                            }
                        }
                    }
                    // END
                    $return['info'] = $this->{'request'}->params['paging']['Product'];
                }
                // END


            }else{
                // Error al intentar guardar. Re-direcciona a /
                $return['result'] = false;
            }
        }else{
            // El registro no existe. Re-direcciona a /
            $return['result'] = false;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


    /*
        Descripción:         Función para obtener las imágenes asociadas a una publicación.
        Tipo de solicitud:   Interna
        Recibe:              Array.
        Retorna:             Array.
    */

    public function product_images($products){
        $this->{'loadModel'}('Image');

        $data = array();

        foreach($products as $index_0 =>$product){
            $data[$index_0]['product'] = $product['Product'];

            foreach($product['Image'] as $original_imagen){
                $data[$index_0]['imagen']['original'] = $original_imagen;
                $products[$index_0]['Image']['children'] =  $this->{'Image'}->find('all',array(
                        'conditions' => array('Image.parent_id' => $original_imagen['id']),
                        'contain' => false
                    )
                );

                foreach($products[$index_0]['Image']['children'] as $children ){
                    $namespace = '';

                    switch ($children['Image']['size']) {
                        case '1920x1080':
                            $namespace = 'large';
                            break;
                        case '900x900':
                            $namespace = 'median';
                            break;
                        case '400x400px':
                            $namespace = 'small';
                            break;
                    }
                    $data[$index_0]['imagen']['thumbnails'][$namespace] = $children['Image'];
                }

            }
        }
        return $data;
    }


    /*
     Array deseado:
            $data = array(
                'product'=>
                'imagen'=>array(
                    'original'=>
                    'thumbnails'=>array(
                        'large'=>,
                        'median'=>,
                        'small'=>,
                )
            )
        )
    */


}