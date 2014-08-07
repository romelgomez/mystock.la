<?php

	/*
	- Todas la urls estaran en minusculas. Ejemplo:
		/familias
		/familias_sustancias
	- Las url que estan en ingles, son aquellas que no son visibles.	
	*/
	
	/* Ordenadas alfaveticamente por el nombre del controlador. Que es lo que se repite. */




//	A
//	B
Router::connect('/cuenta',									array('controller' => 'backEnds', 	'action' => 'index'));							// Acción Get - Interfaz de administración.
//	C
Router::connect('/categorias',								array('controller' => 'categories', 'action' => 'index'));							// Acción Get - Interfaz administrar las categorias de productos y servicios
Router::connect('/new_category',							array('controller' => 'categories', 'action' => 'new_category'));					// Acción Ajax - Para registrar una categoria
Router::connect('/delect_category',							array('controller' => 'categories', 'action' => 'delect_category'));				// Acción Ajax - Para borrar una categoria
Router::connect('/edit_category_name',						array('controller' => 'categories', 'action' => 'edit_category_name'));				// Acción Ajax - Para editar el nombre una categoria
Router::connect('/edit_category_position',					array('controller' => 'categories', 'action' => 'edit_category_position'));			// Acción Ajax - Para editar la posicion de una categoria

/* para la selecion de la categoria de un producto o servicio.
 ************************************/
Router::connect('/get_category_child_elements', 			array('controller' => 'categories', 'action' => 'get_category_child_elements'));	// Acción Ajax - Para optener hijos que existen de una categoria padre    

//	D
//	E
//	F
Router::connect('/',										array('controller' => 'frontEnds', 	'action' => 'index'));							// Acción Get - Interfaz principal.
//	G
//	H
//	I
Router::connect('/image_add',								array('controller' => 'images', 'action' => 'add'));
Router::connect('/enables_this_images',	 					array('controller' => 'images', 'action' => 'enables_this_images'));
Router::connect('/disable_this_imagen',	 					array('controller' => 'images', 'action' => 'disable_this_imagen'));

//	J
//	K
//	L
//	M
//	N
//	O
//	P

/* /publicar, /editar_borrador, /editar usan la misma vista add para los procesos referentes, todos los proceso son compatibles en 98% por lo tanto comparten vistas , la accion es identificada por la url, 
 * solo cambia una pequeña parte de dom, los botones unicamente.  
***********************************************************************************************************************/
Router::connect('/publicar',								array('controller' => 'products', 	'action' => 'add'));							// Acción Get  - Interfaz para cargar nuevos productos. 
	
Router::connect('/editar_borrador/:id', 					array('controller' => 'products', 	'action' => 'add'),  				  			// Accion Get  - para editar un borrador, es un producto que no a sido publicado.
	array(
		'pass' => array('id'),
		'id' => '[0-9]+'
	));

Router::connect('/editar/:id', 								array('controller' => 'products', 	'action' => 'add'),  				  			// Accion Get  - para editar un producto publicado.
	array(
		'pass' => array('id'),
		'id' => '[0-9]+'
	));


Router::connect('/search_publications', 					array('controller' => 'products', 	'action' => 'search_publications')); 			// Acción Ajax - para pausar buscar publicaciones. 


Router::connect('/pause', 									array('controller' => 'products',	 'action' => 'pause'));   							// Acción Ajax - para pausar una publicación activa. 
Router::connect('/activate', 								array('controller' => 'products',	 'action' => 'activate'));   						// Acción Ajax - para activar una publicación pausada. 
Router::connect('/delete', 									array('controller' => 'products',	 'action' => 'delete'));							// Acción Ajax - borrar un producto publicado



Router::connect('/add_new',									array('controller' => 'products', 	'action' => 'add_new'));						// Acción Ajax - para guadar una nueva publicación. 
Router::connect('/save_draft',	 							array('controller' => 'products',	'action' => 'saveDraft'));	   					// Accion Ajax - para guadar un borrador

Router::connect('/publicados', 								array('controller' => 'products',	'action' => 'published'));	   					// Accion Get  - Interfaz para acceder a los productos publicados 
Router::connect('/products_published', 						array('controller' => 'products',	'action' => 'products_published'));				// Accion ajax - para obtener los productos publicados 


Router::connect('/borradores',	 							array('controller' => 'products',	'action' => 'drafts'));	   						// Accion Get  - Interfaz para acceder a los borradores
Router::connect('/discard',	 								array('controller' => 'products',	'action' => 'discard'));	   					// Accion Ajax - para descartar un borrador registrado

Router::connect('/producto/:id/:slug', 						array('controller' => 'products', 	'action' => 'view'),  				  			// Accion Get 	- Interfaz para visualizar un producto	
	array(
		'pass' => array('id','slug'),
		'id' => '[0-9]+',
		'ext' => 'html'
	));

//	Q
//	R
//	S
//	T
//	U
Router::connect('/salir',									array('controller' => 'users', 	'action' => 'logout'));								// Accion Get  - Para salir de la aplicación
Router::connect('/entrar',									array('controller' => 'users', 	'action' => 'login'));								// Accion Get  - Interfaz de entrada a la aplicación o registro de un nuevo usuario.						
Router::connect('/login',									array('controller' => 'users', 	'action' => 'in'));									// Accion Ajax - Para entrar a la aplicación.						
Router::connect('/new_user',								array('controller' => 'users', 	'action' => 'add'));								// Acción Ajax - Para registrar un nuevo usurio.
Router::connect('/check_email',								array('controller' => 'users', 	'action' => 'check_email')); 						// Acción Ajax - Para determinar si el correo ya se ha registrado.  
Router::connect('/recover_account',							array('controller' => 'users', 	'action' => 'recover_account')); 					// Acción Ajax - Para recuperar una cuenta.  
//	V
//	W
//	X
//	Y
//	Z


/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	// CakePlugin::routes();
