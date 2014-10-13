<?php class ProductsController extends AppController {

    public function beforeFilter(){

        $this->{'Auth'}->allow(array('view','stock','products'));

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

        $url_action = strstr($this->{'request'}->url, '/', true); // Desde PHP 5.3.0
        $this->{'set'}('url_action',$url_action);


        if(isset($this->{'params'}->id)){
            $id = $this->{'params'}->id;
            // para editar principalmente.
            $product_data = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $id,'Product.deleted'=>0)
            ));


            if($product_data){

                // Start

//                [
//                  {'small':'f155d610-a8c7-4aee-b4f1-98f0b3442012.jpg','large':'f155d610-a8c7-4aee-b4f1-98f0b34420ss.jpg'},
//                  {'small':'0c05ec97-aa2d-4cf7-a80f-20d8e1c38887.jpg','large':'0c05ec97-aa2d-4cf7-a80f-20d8e1c38855.jpg'}
//                ]

//                array(
//                    (int) 0 => array(
//                        'Image' => array(
//                            'id' => '54355e00-1ed4-4d58-a9bc-188a7f00000b',
//                            'parent_id' => '54355dff-0ec4-4439-b942-188a7f00000b',
//                            'product_id' => '54355dff-048c-419f-94f2-188a7f00000b',
//                            'size' => 'large',
//                            'name' => 'a99bf8f9-6eee-4541-b6ff-7472b2927d63.jpg',
//                            'status' => true,
//                            'created' => '2014-10-08 11:23:36',
//                            'modified' => '2014-10-08 11:23:36'
//                        )
//                    ),
//                    (int) 1 => array(
//                        'Image' => array(
//                            'id' => '54355e00-f9fc-43b3-bc87-188a7f00000b',
//                            'parent_id' => '54355dff-0ec4-4439-b942-188a7f00000b',
//                            'product_id' => '54355dff-048c-419f-94f2-188a7f00000b',
//                            'size' => 'facebook',
//                            'name' => 'ecf03890-f6d7-4f12-894f-439b75cb92de.jpg',
//                            'status' => true,
//                            'created' => '2014-10-08 11:23:36',
//                            'modified' => '2014-10-08 11:23:36'
//                        )
//                    )
//                )

                if($product_data['Image']){
                    $this->{'loadModel'}('Image');

                    $data = array();

                    foreach($product_data['Image'] as $index_0 => $smallImagen){

                        //debug($original_imagen);
                        $data[$index_0]['small'] 		= $smallImagen['name'];

                        $images	=  $this->{'Image'}->find('all',array(
                                'conditions' => array('Image.parent_id' => $smallImagen['id']),
                                'contain' => false
                            )
                        );

                        foreach($images as $index_1 => $image){
                            $data[$index_0][$image['Image']['size']] = $image['Image']['name'];
                        }

                    }

                    $product_data['Image'] = array();
                    $product_data['Image'] = $data;
                }
                // End

//                $product_data['Path'] = $path;
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

        $url_action = strstr($this->{'request'}->url, '/', true); // Desde PHP 5.3.0
        $this->{'set'}('url_action',$url_action);


        if(isset($this->{'params'}->id)){
            $id = $this->{'params'}->id;
            // para editar principalmente.
            $product_data = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $id,'Product.user_id'=>$user_logged['User']['id'],'Product.deleted'=>0)
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

                $this->{'request'}->data = $product_data;

            }else{
                $this->{'redirect'}('/');
            }
        }


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
           "title":"hola",
           "body":"hola",
           "price":"21",
           "quantity":"32"
        }
        */

        if($request['id']){
            $this->{'loadModel'}('Image');
            if($this->{'Image'}->find('first',array('conditions' => array('Image.product_id' => $request['id'],'Image.status' => 1)))){
                // si hay imágenes subidas y aprobadas
                $this->{'Product'}->set($request);
                if($this->{'Product'}->validates()){

                    // se verifica que el usuario esté trabajando en un post suyo o de otro vendedor de misma compañía
                    if($request['id']){
                        $isOk = $this->{'Product'}->find('first', array(
                            'conditions' => array('Product.id' => $request['id'],'Product.user_id'=>$user_logged['User']['id'])
                        ));

                        if($isOk){

                            $producto =	array(
                                'Product'=>Array
                                (
                                    'id'            =>  $request['id'],
                                    'user_id'       =>  $user_logged['User']['id'],
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

        // Esta lógica es cuando se guardó un borrador, por lo tanto existe un id ya definido. Se verifica que el usuario esté trabajando en un post suyo, de no cumplir o intentar modificar el dom, el script creará otro borrador.
        if($request['id']){
            $isOk = $this->{'Product'}->find('first', array(
                'conditions' => array('Product.id' => $request['id'],'Product.user_id'=>$user_logged['User']['id'])
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

    public function search(){
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

        $url_action = strstr($this->{'request'}->url, '/', true); // Desde PHP 5.3.0
        $this->{'set'}('url_action',$url_action);

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

        if($url == 'published' || $url == 'drafts' || $url == 'stock-products'){
//            if($url == 'search-products'){
//
//                // search - conditions
//                if(!isset($request['search']) || $request['search'] == ''){
//                    $conditions = array('Product.deleted'=>0,'Product.published'=>1);
//                }else{
//                    $search = $this->cleanString($request["search"]);
//                    $return["search"] = $search;
//                    $conditions = array(
//                        'Product.deleted'=>0,
//                        'Product.published'=>1,
//                        'or'=>array(
//                            'Product.title LIKE'=> '%'.$search.'%',
//                            'Product.body LIKE'=> '%'.$search.'%'
//                        )
//                    );
//                }
//
//                // total_products es la cantidad total de productos publicados, este resultado es indiferente a los filtros aplicados por el usuario.
//                $return['total_products'] = $this->{'Product'}->find('count', array('conditions'=> array('Product.deleted'=>0,'Product.published'=>1)));
//
//            }
            if($url == 'stock-products'){

                // search - conditions
                if(!isset($request['search']) || $request['search'] == ''){
                    $conditions = array('Product.user_id' => $request['user_id'],'Product.deleted'=>0,'Product.published'=>1,'Product.status'=>1);
                }else{
                    $search = $this->cleanString($request["search"]);
                    $return["search"] = $search;
                    $conditions = array(
                        'Product.user_id' => $request['user_id'],
                        'Product.deleted'=>0,
                        'Product.published'=>1,
                        'Product.status'=>1,
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
                    $conditions = array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>1);
                }else{
                    $search = $this->cleanString($request["search"]);
                    $return["search"] = $search;
                    $conditions = array(
                        'Product.user_id' => $user_logged['User']['id'],
                        'Product.deleted'=>0,
                        'Product.published'=>1,
                        'or'=>array(
                            'Product.title LIKE'=> '%'.$search.'%',
                            'Product.body LIKE'=> '%'.$search.'%'
                        )
                    );
                }

                // total_products es la cantidad total de productos publicados, este resultado es indiferente a los filtros aplicados por el usuario.
                $return['total_products'] = $this->{'Product'}->find('count', array('conditions'=> array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>1)));

            }
            if($url == 'drafts'){
                // search - conditions
                if(!isset($request['search']) || $request['search'] == ''){
                    $conditions = array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>0);
                }else{
                    $search = $this->cleanString($request["search"]);
                    $return["search"] = $search;
                    $conditions = array(
                        'Product.user_id' => $user_logged['User']['id'],
                        'Product.deleted'=>0,
                        'Product.published'=>0,
                        'or'=>array(
                            'Product.title LIKE'=> '%'.$search.'%',
                            'Product.body LIKE'=> '%'.$search.'%'
                        )
                    );
                }

                // total_products es la cantidad total de productos publicados, este resultado es indiferente a los filtros aplicados por el usuario.
                $return['total_products'] = $this->{'Product'}->find('count', array('conditions'=> array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>0)));
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

                if($request['order_by'] == "mayor-precio"){

                    $order = array(
                        'Product.price' => 'desc'
                    );
                }
                if($request['order_by'] == "menor-precio"){

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

                if($request['order_by'] == "mayor-disponibilidad"){

                    $order = array(
                        'Product.quantity' => 'desc'
                    );
                }
                if($request['order_by'] == "menor-disponibilidad"){

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
                    $return['data']	= $products;
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
                'conditions' => array('Product.id' => $request['id'],'Product.user_id'=>$user_logged['User']['id'])
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
            'conditions' => array('Product.id' => $request['id'],'Product.user_id' => $user_logged['User']['id'])
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
            'conditions' => array('Product.id' => $request['id'],'Product.user_id' => $user_logged['User']['id'])
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
            'conditions' => array('Product.id' => $request['id'],'Product.user_id' => $user_logged['User']['id'])
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

                    // se establecen variables.
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
                            case "mayor-precio":

                                $order = array(
                                    'Product.price' => 'desc'
                                );
                                $win_order_by = "mayor-precio";

                                break;
                            case "menor_precio":

                                $order = array(
                                    'Product.price' => 'asc'
                                );
                                $win_order_by = "menor-precio";

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
                            case "mayor-disponibilidad":

                                $order = array(
                                    'Product.created' => 'asc'
                                );
                                $win_order_by = "mayor-disponibilidad";

                                break;
                            case "menor-disponibilidad":

                                $order = array(
                                    'Product.quantity' => 'asc'
                                );
                                $win_order_by = "menor-disponibilidad";

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
                            'conditions' =>  array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>1),
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
                            $return['data'] 	= $products;
                        }

                    } catch (Exception $e) {
                        try {
                            /* Segundo intento:  pagina anterior
                            *******************************************************/
                            $previous_page = $page-1;

                            $this->{'paginate '}= array(
                                'conditions' =>  array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>1),
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
                                $return['data'] = $products;
                            }

                        }catch (Exception $e){
                            try {
                                /* Tercer intento:  ultima pagina disponible
                                *******************************************************/
                                $last_page = $this->{'request'}->params['paging']['Product']['pageCount'];

                                $this->{'paginate'}= array(
                                    'conditions' =>  array('Product.user_id' => $user_logged['User']['id'],'Product.deleted'=>0,'Product.published'=>1),
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
                                    $return['data'] = $products;
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






}