<?php class ProductsController extends AppController {

	
	public function index(){
	}
	
	/* Descripción: 		Función para ver un producto, ofertar y preguntar por el.
	 * tipo de solicitud: 	get (no-ajax)
	 * tipo de acceso: 		publico
	 * Recive: 				array.
	 * Retorna: 			array.
	 *******************/
	public function view(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		debug($request);
		debug($this->request);
	}
	
	/* Descripción: 		Función principal, solisitada por el vendedor para cargar un producto.
	 * tipo de solicitud: 	get (no-ajax)
	 * tipo de acceso: 		vendedor
	 * Recive: 				array.
	 * Retorna: 			array.	
	 *******************/
	public function add(){
		$user_logged 	= $this->Auth->User();
		$this->loadModel('Category');
		
		$url_action = strstr($this->request->url, '/', true); // Desde PHP 5.3.0
		$this->set('url_action',$url_action);
		 
		
		if(isset($this->params->id)){
			$id = $this->params->id;
			// para editar principalmente.
			$product_data = $this->Product->find('first', array(
				'conditions' => array('Product.id' => $id,'Product.company_id'=>$user_logged['User']['company_id'],'Product.deleted'=>0)
			));
			
			if($product_data){

				/* tiene permisos para editar la publicacion o borrador pero esta jugando con la url.
				 ****************************************************************************************/ 
				if($url_action == 'editar'){
					if(!$product_data['Product']['published'] == 1){
						// debug('esta editando un borrador');	
						$this->redirect('/');
					}
				}
				if($url_action == 'editar_borrador'){
					if(!$product_data['Product']['published'] == 0){
						//debug('esta editando un publicado');			
						$this->redirect('/');
					}
				}

				
				
				/* se extre la data realcionda con la categoria selecionda para establecer o recontruir el menu y path tal y como el vendedor lo dejo por ultima vez 
				 *******************************************************************************************************************************************************/
				$category_id = $product_data['Product']['category_id'];
				
				/* menu
				 *******/
				$path = $this->Category->getPath($category_id);
				foreach($path as $index => $category){
					$menu[$index]['id'] 			= $category['Category']['id'];
					$menu[$index]['name']			= $category['Category']['name']; 
					
					$children 						= $this->get_category_child_elements($category['Category']['id']);
					
					if($children['children']){
						$menu[$index]['categories'] 	= $children['categories'];
						$menu[$index]['children'] 		= true;
					}else{
						
						$menu[$index]['category_id_selected'] 	= $children['category_id_selected'];	
						$menu[$index]['children'] 				= false;	
					}
				}
				
				if($product_data['Image']){
					$this->loadModel('Image');
					foreach($product_data['Image'] as $index_0 => $original_imagen){
						
							//debug($original_imagen);
							$data[$index_0]['original'] 		= $original_imagen;
							$products[$index_0]['childrens'] 	=  $this->Image->find('all',array(
																									'conditions' => array('Image.parent_id' => $original_imagen['id']),
																									'contain' => false																	
																								)
							);
							foreach($products[$index_0]['childrens'] as $index_2 => $children ){
							
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
				

				
				
				$this->request->data = $product_data;
				$this->request->data['current-menu'] = json_encode($menu);
				
				
				

				
				
				
			}else{
				$this->redirect('/');
			}
		}
		
		
		//debug($this->request->data);
			
		$this->set_base_menu();
	}
	
	/* Descripción: 		Función para obtener las categorias dependientes o hijas de la categoria proporcionada. 
	 * tipo de solicitud: 	interna
	 * Recive: 				int.
	 * Retorna: 			array.	
	 *******************/
	private function get_category_child_elements($category_id){
		$children = $this->Category->find('all', array(
			'conditions' => array('Category.parent_id'=>$category_id),
			'order' => 'lft ASC'
		));					
		if($children){
			foreach($children as $k => $v){
				$return['categories'][$v['Category']['id']] = $v['Category']['name']; 
			}
			$return['children'] = true;
			
		}else{
			$return['category_id_selected'] = $category_id;
			$return['children'] 			= false;
		}
		return $return;
	}
	
	/* Descripción: 		Función para obtener la base del arbol de categorias en la vista.
	 * tipo de solicitud: 	internar
	 * tipo de acceso: 		vendedor
	 * Recive: 				null.
	 * Retorna: 			un array.
	 *******************/	
	private function set_base_menu(){
		$categories = $this->Category->find('all', array(
			'conditions' => array('Category.parent_id'=>null),
			'order' => 'lft ASC',
			'contain' => false
		));
		$this->set('base_menu',$categories);
	}
	
	/* Descripción: 		Función destinada a añadir una nueva publicación, los datos suministrados deberan estar completos, 
	 * tipo de solicitud: 	ajax-get, ajax-post
	 * tipo de acceso: 		vendedor
	 * Recive: 				array.
	 * Retorna: 			un array, el cual sera transformado en un objeto JSON en la vista ajax_view..	
	 *******************/
	public function add_new(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$user_logged = $this->Auth->User();
		
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
	
		$this->loadModel('Image');
		if($request['id']){
			if($this->Image->find('first',array('conditions' => array('Image.product_id' => $request['id'],'Image.status' => 1)))){
			// si hay imgenes cargadas y aprovadas	
				$this->Product->set($request);
				if($this->Product->validates()){
					
					// se verifica que el usuario este trabajando en un post suyo o de otro vendedor de misma compañia
					if($request['id']){
						$isOk = $this->Product->find('first', array(
							'conditions' => array('Product.id' => $request['id'],'Product.company_id'=>$user_logged['User']['company_id'])
						));
						
						if($isOk){

							$producto =	array(
								'Product'=>Array
								(
									'id'				=>	$request['id'],							
									'company_id'		=>	$user_logged['User']['company_id'],
									'user_id'			=>	$user_logged['User']['id'],
									'category_id'		=>	$request['category_id'],
									'title'				=>	$request['title'],
									'subtitle'			=>	$request['subtitle'],
									'body'				=>	$request['body'],
									'price'				=>	$request['price'],
									'quantity'			=>	$request['quantity'],
									'status'			=>	1,
									'published'			=>	1,
									'banned'			=>	0,
									'deleted'			=>	0
								)
							);
							
							if($this->Product->save($producto)){
								$return['result'] 			= true;
								$product_data 				= $this->Product->read();
								$return['product_id']		= $product_data['Product']['id'];
								$return['product_title']	= $product_data['Product']['title'];
							}
						
						}else{
							// esta intentando modificar un posts de otra compañia.
						}
						
					}
						
				}
			}
		}
		
		if(!isset($return['result'])){
			$return['result'] = false;
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');	
	}
	
	/* Descripción: 		Función para guardar un borrador.
	 * tipo de solicitud: 	get (no-ajax)
	 * tipo de acceso: 		vendedor
	 * Recive: 				array.
	 * Retorna: 			un array, el cual sera transformado en un objeto JSON en la vista ajax_view.	
	 *******************/	
	public function saveDraft(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$user_logged = $this->Auth->User();

		// esta logica es cuando se guardo un borrador, por lo tanto existe un id ya definido.
		// se verifica que el usuario este trabajando en un post suyo o de otro vendedor de misma compañia
		// de no cumplir o intentar modificar el dom, el script creara otro borrador.
		if($request['id']){
			$isOk = $this->Product->find('first', array(
				'conditions' => array('Product.id' => $request['id'],'Product.company_id'=>$user_logged['User']['company_id'])
			));
			if($isOk){
				$id = $request['id'];
			}else{
				$id = null; 
				// esta intentando modificar un posts de otra compañia.
			}
		}else{
			$id = null; 
		}
		
		$producto =	array(
			'Product'=>Array
				(	
					'id'			=>$id,						
					'user_id'		=>$user_logged['User']['id'],
					'category_id'	=>$request['category_id'],
					'company_id'	=>$user_logged['User']['company_id'],
					'title'			=>$request['title'],
					'subtitle'		=>$request['subtitle'],
					'body'			=>$request['body'],
					'price'			=>$request['price'],
					'quantity'		=>$request['quantity'],
				)
		);
		
		if($this->Product->save($producto,false)){
			$productData = $this->Product->read();
				
			$id = $productData['Product']['id'];
			$lastSave = $productData['Product']['modified'];
				
			$return['id'] = $id;
				
			// 2.1
			App::uses('CakeTime', 'Utility');
			$return['time'] = CakeTime::format('H:i',$lastSave);
				
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');	
	}
	
	public function search_publications(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		/*	
		$search 		= $this->cleanString($request["search"]);	

		$publications $this->Product->find('all', array(
			'conditions' => array('Article.status' => 'pending')
		));
		*/
		
		/*
		$this->paginate = array(
			'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
			'contain' => array(
				'Image'=>array(
				)
			),
			'order' => $order,
			'limit' => 10,
			'page'	=>$page
		);
		*/
		
		/*
		$pending = $this->Article->find('all', array(
			'conditions' => array('Article.status' => 'pending')
		));
		
		array(
			'conditions' => array('Model.field' => $thisValue), //array of conditions
			'recursive' => 1, //int
			'fields' => array('Model.field1', 'DISTINCT Model.field2'), //array of field names
			'order' => array('Model.created', 'Model.field3 DESC'), //string or array defining order
			'group' => array('Model.field'), //fields to GROUP BY
			'limit' => n, //int
			'page' => n, //int
			'offset' => n, //int
			'callbacks' => true //other possible values are false, 'before', 'after'
		)
		
		'conditions' => array( 
			'Model.is_active' => 'Y', 
			'or' => array( 
				'Model.column1 LIKE' => '%keyword%', 
				'Model.column2 LIKE' => '%keyword', 
				... 
				... 
		) 
		*/	
		


		$return = FALSE;
		$this->set('return',$return);
		$this->render('ajax_view','ajax');	

	}
	
	
	/* Descripción: 		Función para obtener todos los productos publicados.
	 * tipo de solicitud: 	ajax-post, ajax-get
	 * tipo de acceso: 		vendedor
	 * Recive: 				null.
	 * Retorna: 			un array, el cual sera transformado en un objeto JSON en la vista ajax_view.	
	 *******************/	
	public function products_published(){
		
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
	
		$user_logged = $this->Auth->User();

		if(!isset($request['search'])){
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
		
		if(!isset($request['page'])){
			$page = 1;
		}else{
			$page = (int)$request['page'];	
		}
		
		if(!isset($request['order_by'])){
			
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
		
		$this->paginate = array(
			'conditions' =>  $conditions,
			'contain' => array(
				'Image'=>array(
				)
			),
			'order' => $order,
			'limit' => 10,
			'page'	=>$page
		);
		
		// retornar la cantidad de registros para determinar si realmente no quedan mas registros.
		// $conditions = ;
		
		try {
			
			$products = $this->paginate('Product');
			
			// total_products es la cantidad total de productos publicados, este resultado es indiferente a los fitros aplicados por el usuario. 
			$return['total_products'] = $this->Product->find('count', array('conditions'=> array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1)));
			
			if($products){
				$return['data']	= $this->product_images($products);
			}else{
				$return['data'] = array();
			}
			
			$return['result']	= true;
		
		}catch(NotFoundException $e){
			
			// se redireciona a /cuenta y se establece un mensage que indique que hubo un error al procesar la solicitud
			$return['result'] = false; 
			$this->Session->setFlash('Error 404, lo que busca ha sido movido o eliminado o nunca existió.','error');
			
		}

		$return['info'] = $this->request->params['paging']['Product'];
	
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}	
	
	
	/* Descripción: 		Función para visualizar todos los productos publicados.
	 * tipo de solicitud: 	get (no ajax)
	 * tipo de acceso: 		vendedor
	 *******************/	
	public function published(){
	}
	
	
	/* Descripción: 		Función para visualizar todos los productos en status de borrador.
	 * tipo de solicitud: 	get (no ajax)
	 * tipo de acceso: 		vendedor
	 * Recive: 				null.
	 * Retorna: 			un array.	
	 *******************/	
	public function drafts(){
		$user_logged = $this->Auth->User();
			
		$products = $this->Product->find('all',array(
			'conditions' => array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.status'=>0),
			'order' => array('Product.created DESC'),
		));
		
		
		$this->set('products',$products);
	}
	
	
	/* Descripción: 		Función para descartar borrador registrado o no. es preciso hacer pasar la solisitud ahun cuando no este registrado el borrador con el fin de establecer un mensaje de exito de seccion ya que se cambia de vista.  
	 * tipo de solicitud: 	get-ajax,post-ajax
	 * tipo de acceso: 		vendedor
	 * Recive: 				un array.
	 * Retorna: 			un array. el cual sera transformado en un objeto JSON en la vista ajax_view.		
	 *******************/	
	public function discard(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$user_logged = $this->Auth->User();
	
		if($request['row_exist'] == 'true'){
			
			$isOk = $this->Product->find('first', array(
				'conditions' => array('Product.id' => $request['id'],'Product.company_id'=>$user_logged['User']['company_id'])
			));
				
			if($isOk){
					
				$productData['Product']['id'] 		= $request['id'];
				$productData['Product']['deleted'] 	= 1;
										
				if($this->Product->save($productData)){
					$return['result'] = true;
					$this->Session->setFlash('El borrador ha sido descartado.','success');
				}
					
			}else{
				// esta intentando de borrar un posts de otra compañia.
				
				$return['result'] = false;
				$this->Session->setFlash('Ha ocurrido un error.','error');
			}
				
		}else{
			
			$return['result'] 		= true;
			$this->Session->setFlash('El borrador ha sido descartado','success');
					
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}
	

	/* Descripción: 		Función para pusar una publicacion.  
	 * tipo de solicitud: 	get-ajax,post-ajax
	 * tipo de acceso: 		vendedor
	 * Recive: 				un array.
	 * Retorna: 			un array. el cual sera transformado en un objeto JSON en la vista ajax_view.		
	 *******************/		
	public function pause(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$user_logged = $this->Auth->User();
		
		$product_data = $this->Product->find('first', array(
			'conditions' => array('Product.id' => $request['id'],'Product.company_id' => $user_logged['User']['company_id'])
		));
	
		if($product_data){
			$data['Product']['id'] 		= $product_data['Product']['id'];
			$data['Product']['status']	= 0;
				
			if($this->Product->save($data)){
				$return['result'] 	= true;	
				$return['id'] 		= $request['id'];
			}else{
				$return['result'] = false;
			}
		}else{
			$return['result'] = false;
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}


	/* Descripción: 		Función para activar una publicacion.  
	 * tipo de solicitud: 	get-ajax,post-ajax
	 * tipo de acceso: 		vendedor
	 * Recive: 				un array.
	 * Retorna: 			un array. el cual sera transformado en un objeto JSON en la vista ajax_view.		
	 *******************/	
	public function activate(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$user_logged = $this->Auth->User();
		
		$product_data = $this->Product->find('first', array(
			'conditions' => array('Product.id' => $request['id'],'Product.company_id' => $user_logged['User']['company_id'])
		));
	
		if($product_data){
			$data['Product']['id'] 		= $product_data['Product']['id'];
			$data['Product']['status']	= 1;
				
			if($this->Product->save($data)){
				$return['result'] 	= true;
				$return['id'] 		= $request['id'];	
			}else{
				$return['result'] = false;
			}
		}else{
			$return['result'] = false;
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}
	
	/* Descripción: 		Función para borrar una publicacion.  
	 * tipo de solicitud: 	get-ajax,post-ajax
	 * tipo de acceso: 		vendedor
	 * Recive: 				un array.
	 * Retorna: 			un array. el cual sera transformado en un objeto JSON en la vista ajax_view.		
	 *******************/	
	public function delete(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$user_logged = $this->Auth->User();
		
		$product_data = $this->Product->find('first', array(
			'conditions' => array('Product.id' => $request['id'],'Product.company_id' => $user_logged['User']['company_id'])
		));
	
		if($product_data){
			$product['Product']['id'] 		= $product_data['Product']['id'];
			$product['Product']['deleted']	= 1;
				
			if($this->Product->save($product)){
				$return['result'] 	= true;
				$return['id'] 		= $request['id'];
				
				// establecemos un mensaje luego de borrar la publicación desde la vista de editar.
				if($request['session'] == 'true'){
					$this->Session->setFlash('La publicación ha sido borrada.','success');	
				}
				
				/* START retoramos resultados actualizados - paginados a vista /publicados
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
					
					
					/* START se intenta 3 veces lograr retornar reultados paginados. 
					 *******************************************************************************************************************************************/
					try {
					/* Primer intento:  pagina actual
					 *******************************************************/
						$this->paginate = array(
							'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
							'contain' => array(
								'Image'=>array(
								)
							),
							'order' => $order,
							'limit' => 10,
							'page'	=>$page
						);
					 
						$products = $this->paginate('Product');
					
						if($products){
							$return['data'] 	= $this->product_images($products);
						}
												
					} catch (NotFoundException $e) {
						try {
						/* Segundo intento:  pagina anterior
						*******************************************************/
							$previous_page = $page-1;
							
							$this->paginate = array(
								'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
								'contain' => array(
									'Image'=>array(
									)
								),
								'order' => $order,
								'limit' => 10,
								'page'	=>$previous_page
							);
											
							$products = $this->paginate('Product');
					
							if($products){
								$return['data'] = $this->product_images($products);
							}
							
						}catch (NotFoundException $e){
							try {
							/* Tercer intento:  ultima pagina disponible
							*******************************************************/	
								$last_page = $this->request->params['paging']['Product']['pageCount'];

								$this->paginate = array(
									'conditions' =>  array('Product.company_id' => $user_logged['User']['company_id'],'Product.deleted'=>0,'Product.published'=>1),
									'contain' => array(
										'Image'=>array(
										)
									),
									'order' => $order,
									'limit' => 10,
									'page'	=>$last_page
								);
												
								$products = $this->paginate('Product');
						
								if($products){
									$return['data'] = $this->product_images($products);
								}
						
							}catch (NotFoundException $e){
								// Error inesperado. Redireciona a /
								$return['result'] = false;
							}
						}
					}
					// END
					$return['info'] = $this->request->params['paging']['Product'];
				}
				// END
				
			
			}else{
				// Error al intentar guardar. Redireciona a /
				$return['result'] = false;
			}
		}else{
			// El registro no existe. Redireciona a /
			$return['result'] = false;
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}
	

	/* Descripción: 		Función para obtener las imagenes asociadas a una publicación.
	 * tipo de solicitud: 	interna
	 * Recive: 				array.
	 * Retorna: 			array.	
	 *******************/
	public function product_images($products){
		$this->loadModel('Image');
		foreach($products as $index_0 =>$product){
			$data[$index_0]['product'] = $product['Product'];
									
			foreach($product['Image'] as $index_1 => $original_imagen){
				$data[$index_0]['imagen']['original'] = $original_imagen;
				$products[$index_0]['Image']['childrens'] =  $this->Image->find('all',array(
																							'conditions' => array('Image.parent_id' => $original_imagen['id']),
																							'contain' => false																	
																					)
				);
											
				foreach($products[$index_0]['Image']['childrens'] as $index_2 => $children ){
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


} ?>


	
