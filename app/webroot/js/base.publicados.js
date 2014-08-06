/*

	registros a mostrar = 10; segun esta cantidad cierto comportamiento es observado. 

	solo 1 registro
	* paginacion 				inavilitada
	* ordenar por precio		inavilitada
	* busqueda					inavilitada
	
	entre 1 y 10 registros
	* paginacion 				inavilitada - Segun la cantidad de registros que se muestra en una primera vez.
	* ordenar por precio		avilitado
	* busqueda					inavilitada

	mas de 10 registros 			
	* paginacion 				avilitado - Segun la cantidad de registros que se muestra en una primera vez.
	* ordenar por precio		avilitado
	* busqueda					avilitado
	
*/

/*
 * Type: función
 * Descrición: funcion destinada a ordenar la publicaciones, segun la preferencia del vendedor.
 * Parametros: null
 *************************************************************************************************************************************************************/
var order_by =  function(){
	
	var order_by_obj = {
		"type":"post",
		"url":"/products_published",
		"data":{
			"custon":{}
		},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
					
				var a	= response.responseText;
				var obj = $.parseJSON(a);
				if(obj.expired_session){
					window.location = "/entrar";
				}
					
				if(obj.result){
					if(obj.data){
						// hay publicaciones 
						_var = {};
						_var = new set_vars(obj);
						prepare_publications('after');
					}else{
						// no hay publicaciones.
						$("#no-products").css({"display":"inherit"});
					}
				}else{
					// hay un error en la solicitud.
					window.location = "/cuenta";
				}
					
					
			}
		}
	}	

	
	var order_by = {
		"higher-price":"mayor_precio",
		"lower-price":"menor_precio",
		"recent":"recientes",
		"oldest":"antiguos",
		"higher-availability":"mayor_disponibilidad",
		"lower-availability":"menor_disponibilidad"
	}
	
	if(_var.count > 1){
		
		$.each(order_by,function(id,order_by){
			
			$("#"+id).off('click');	
			$("#"+id).on('click',function(event){
				event.preventDefault();
				
				var url_obj =  url_parser();
				
				//console.log(url_obj)
				var request_this 				= {};
				
				if(url_obj.search != ''){
					// se solicita buscar algo.
					request_this.search	= url_obj.search;
					
					var url = str_replace(obj.search,' ','_');
					window.location = "#buscar_"+url+"/"+order_by;
					
				}else{
					window.location = "#"+order_by;
				}
				
				request_this.order_by			= order_by;
				order_by_obj.data.custon		= request_this;				
				new Request(order_by_obj);
				
			});
					
		});		
	
		$("#order-by").css({"display":""});
	
	}else{
		$("#order-by").css({"display":"none"});
	}

}


/*
 * Type: función
 * Descrición:   se establece la informacion de la cantidad de registros existentes
 * Parametros: 
 *************************************************************************************************************************************************************/
var info = function(){
	
	console.log(_var);
	
	/*
	
	Algorismo para obtener el resultado esperado con la data disponible:
	Calculo Imaginario

	count 	:	el total de registros 
	current :	los que actualmente son observados.
	
	1  - 10 de 35	----------- current: 10		1	2	3	4	6	5	7	9	8	10
	11 - 20 de 35   ----------- current: 10		11	12	13	14	15	16	17	18	19	20
	21 - 30 de 35	----------- current: 10		21	22	23	24	25	26	27	28	29	30
	31 - 35 de 35	----------- current: 5		31	32	33	34	35
	
	
	1 - 10 de 35 					  
		
		count: 35
		current: 10
		data: Array[10]
		nextPage: true
		page: 1
		pageCount: 4
		prevPage: false
		
		if page < pageCount 
		   
		De 		=	page*current-10+1 	= 1*10-10+1 = 1 
		Hasta	=	page*current					= 10
		 
		 
		 	
	11 - 20 de 35
	
		count: 35
		current: 10
		data: Array[10]
		nextPage: true
		page: 2
		pageCount: 4
		prevPage: true

		
		if page < pageCount 
		
		De 		=	page*current-10+1 =	2*10-10+1 	= 11 
		Hasta	=	page*current					=	20


	21 - 30 de 35			
	
		count: 35
		current: 10
		data: Array[10]
		nextPage: true
		page: 3
		pageCount: 4
		prevPage: true

		if page < pageCount

		De 		=	page*current-10+1 = 3*10-10+1	= 21
		Hasta	=	page*current					= 30


	31 - 35 de 35
	
		count: 35
		current: 5
		data: Array[5]
		nextPage: false
		page: 4
		pageCount: 4
		prevPage: true
	
		if page ==  pageCount
		
		De 		=	count-current+1	= 31
		Hasta	=	count			= 35
	
	********************************************************************

	1 - 5 de 5
	
		count: 5
		current: 5
		data: Array[5]
		nextPage: false
		page: 1
		pageCount: 1
		prevPage: false
	
		if page ==  pageCount
		
		De 		=	count-current+1	= 1
		Hasta	=	count			= 5

	*/

	if(_var.count > 0){
		if(_var.count == 1){
			 info = '1 publicación';
		}else{
		
			if(_var.page == _var.pageCount){
				var de 		= _var.count-_var.current+1;
				var hasta	= _var.count;
			} 
	
			if(_var.page < _var.pageCount){
				var de 		= (_var.page*_var.current)-10+1
				var hasta	= _var.page*_var.current;	 
			}

			var info = '<b>'+de+'</b> - <b>'+hasta+'</b> de <b>'+_var.count+'</b>';
		
		}
	}else{
		info = '0 publicaciónes';
	}
				
	// se establece la informacion de la cantidad de registros existentes
	$("#pagination-info span").html(info);
	
}



/*
 * Type: función
 * Descrición: encargada de administrar la paginacion de los resultados.
 * Parametros: null
 *************************************************************************************************************************************************************/
var pagination = function(){
	
	var pagination_obj = {
		"type":"post",
		"url":"/products_published",
		"data":{
			"custon":{}
		},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
				
				var a = response.responseText;
				var obj = $.parseJSON(a);
				if(obj.expired_session){
					window.location = "/entrar";
				}
				
				if(obj.result){
					if(obj.data){
						// hay publicaciones 
						_var = {};
						_var = new set_vars(obj);
						prepare_publications('after');
					}else{
						// no hay publicaciones.
						$("#no-products").css({"display":"inherit"});
					}
				}else{
					// hay un error en la solicitud.
					window.location = "/cuenta";
				}
				
				
			}
		}
	}
	
	//console.log(_var);

	if(_var.pageCount > 1){
	
		// si exite una pagina anterior y si la pagina anterior no es la 0
		if(_var.prevPage && (_var.page-1) != 0){
			
			$("#prev-page").attr({"disabled":false}).removeClass('disabled');
			$("#prev-page").off('click');	
			$("#prev-page").on('click', function(){
				
				var url_obj =  url_parser();
				var prev_page = _var.page-1; // tambien puede tomar el valor de: url_obj.page
				var request_this = {};
				
				// PAGE
				request_this.page = prev_page;
				
				if(url_obj.order_by != ""){
					if(url_obj.search != ""){
						var url = str_replace(obj.search,' ','_');
						var new_url = "#buscar_"+url+"/"+url_obj.order_by+"/pagina_"+prev_page;
						//SEARCH
						request_this.search = url_obj.search;
					}else{
						var new_url = "#"+url_obj.order_by+"/pagina_"+prev_page;
					}   
					// ORDER
					request_this.order_by = url_obj.order_by;
				}else{
					if(url_obj.search != ""){
						var url = str_replace(obj.search,' ','_');
						var new_url = "#buscar_"+url+"/pagina_"+prev_page;
						//SEARCH
						request_this.search = url_obj.search;
					}else{
						var new_url = "#pagina_"+prev_page;
					}
				}
				
				/*		
				// PAGE
				request_this.page = prev_page;		
				if(url_obj.order_by != ""){
					
					var new_url = "#"+url_obj.order_by+"/pagina_"+prev_page;
					// ORDER
					request_this.order_by = url_obj.order_by;
				
				}else{
					var new_url = "#pagina_"+prev_page;
				}
				*/
				
				
				/*
				if(url_obj.search != ''){
					// se solicita buscar algo.
					request_this.search	= url_obj.search;
					
					var url = str_replace(obj.search,' ','_');
					window.location = "#buscar_"+url+"/"+order_by;
					
				}else{
					window.location = "#"+order_by;
				}
				*/
				
				
				
				window.location = new_url;
				pagination_obj.data.custon = request_this;
				new Request(pagination_obj);
				
				// al establecer la solisitud ajax un aviso de cargando deber ser colocado. 
				
				
			});
			
		}else{
			$("#prev-page").attr({"disabled":true}).addClass('disabled');
		}
		// si existe una siguiente pagina
		if(_var.nextPage){
			
			$("#next-page").attr({"disabled":false}).removeClass('disabled');
			$("#next-page").off('click');		
			$("#next-page").on('click', function(){
				
				var url_obj =  url_parser();
				
				var next_page = _var.page+1; // tambien puede tomar el valor de: url_obj.page
				var request_this = {};
				
				// PAGE
				request_this.page = next_page;
				
				if(url_obj.order_by != ""){
					if(url_obj.search != ""){
						var url = str_replace(obj.search,' ','_');
						var new_url = "#buscar_"+url+"/"+url_obj.order_by+"/pagina_"+next_page;
						//SEARCH
						request_this.search = url_obj.search;
					}else{
						var new_url = "#"+url_obj.order_by+"/pagina_"+next_page;
					}   
					// ORDER
					request_this.order_by = url_obj.order_by;
				}else{
					if(url_obj.search != ""){
						var url = str_replace(obj.search,' ','_');
						var new_url = "#buscar_"+url+"/pagina_"+next_page;
						//SEARCH
						request_this.search = url_obj.search;
					}else{
						var new_url = "#pagina_"+next_page;
					}
				}
				
				
				/*
				// PAGE
				request_this.page = next_page;
				if(url_obj.order_by != ""){
					var new_url = "#"+url_obj.order_by+"/pagina_"+next_page;
					// ORDER
					request_this.order_by = url_obj.order_by;
				}else{
					var new_url = "#pagina_"+next_page;
				}
				*/
				
				
				window.location = new_url;
				pagination_obj.data.custon = request_this;
				new Request(pagination_obj);
							
			});
		}else{
			$("#next-page").attr({"disabled":true}).addClass('disabled');
		}
	
		$("#pagination").css({"display":""});		
	}else{
		$("#pagination").css({"display":"none"});
	}
	
}

/*
 * Type: función
 * Descrición: funcion destinada a modifcar el estado de la barra de progreso.
 * Parametros:
 * 	percentage: string
 *************************************************************************************************************************************************************/
var progress_bar = function(percentage){
	$("div.bar").css({"width":percentage});
}

/*
 * Type: función
 * Descrición: funcion destinada a pausar una publicación activa 
 * Parametros:  null  
 *************************************************************************************************************************************************************/
var pause = function(){
	var pause_obj = {
		"type":"post",
		"url":"/pause",
		"data":{
			"custon":{}
		},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
				
				var a = response.responseText;
				console.log(a);
				
				var obj = $.parseJSON(a);
				if(obj.expired_session){
					window.location = "/entrar";
				}
				
				if(obj.result){
					$("#product-"+obj.id+' .pause').css({
					  "display": 'none'
					});
					$("#product-"+obj.id+' .activate').css({
					  "display": 'inline'
					});
					
					var status = '<span class="label label-warning paused-status">pausado</span>';
					$("#product-"+obj.id+' .active-status').replaceWith(status);
					
				}else{
					window.location = "/";
				}
				
			}
		}
	}
	
	if($("#published .pause").length){
		$("#published .pause").each(function(){
			$(this).off('click');
			$(this).on('click',function(){
				var pure_json_obj = $(this).parents("div.media").children().last().html(); 
				var obj 			= $.parseJSON(clean_obj(pure_json_obj));
				var request_this = {};
				request_this.id  = obj.product.id;
				pause_obj.data.custon = request_this;
				new Request(pause_obj);
			});
			
		});
	}
	
}

/*
 * Type: función
 * Descrición: funcion destinada a actiar una publicación activa 
 * Parametros:  null  
 *************************************************************************************************************************************************************/
var activate = function(){
	var pause_obj = {
		"type":"post",
		"url":"/activate",
		"data":{
			"custon":{}
		},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
				
				var a = response.responseText;
				var obj = $.parseJSON(a);
				if(obj.expired_session){
					window.location = "/entrar";
				}
				
				if(obj.result){
					$("#product-"+obj.id+' .pause').css({
					  "display": "inline"
					}); 
					$("#product-"+obj.id+' .activate').css({
					  "display": "none"
					});
					var status = '<span class="label label-success active-status">publicado</span>';
					$("#product-"+obj.id+' .paused-status').replaceWith(status);
				}else{
					window.location = "/";
				}
				
			}
		}
	}
	
	if($("#published .activate").length){
		$("#published .activate").each(function(){
			$(this).off('click');
			$(this).on('click',function(){
				
				pure_json_obj = $(this).parents("div.media").children().last().html(); 
				obj 			= $.parseJSON(clean_obj(pure_json_obj));
				var request_this = {};
				request_this.id  = obj.product.id;
				pause_obj.data.custon = request_this;
				new Request(pause_obj);
				
			});
		});
	}
	
}

/*
 * Type: función
 * Descrición: funcion que procesa la solicitud de editar una publicación,  la razon de crear una funcion y no un simple link que es mas simple, es por la maquetacion o bootstrap, como es un grupo de botones pegados, al colocar un link <a></a> se descuadra. por lo tanto es requerido usar una funcion.    
 * Parametros:  null
 *************************************************************************************************************************************************************/
var edit = function(){
	if($("#published .edit").length){
		$("#published .edit").each(function(){
			$(this).off('click');
			$(this).on('click',function(){
				
				pure_json_obj = $(this).parents("div.media").children().last().html(); 
				obj 			= $.parseJSON(clean_obj(pure_json_obj));
				
				var edit_link	= 	'/editar/'+obj.product.id;
				window.location = edit_link;
								
			});
		});
	}
}

/*
 * Type: función
 * Descrición: funcion que procesa la solicitud de borrar una publicación    
 * Parametros: null
 *************************************************************************************************************************************************************/
var _delete =  function(){

	var delete_obj = {
		"type":"post",
		"url":"/delete",
		"data":{
			"custon":{}
		},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
				
				var a = response.responseText;
				var obj = $.parseJSON(a);
				
				console.log(obj);
				
				if(obj.expired_session){
					window.location = "/entrar";
				}
				
				//  delete_status
				if(obj.result){
					
					// Exito al eliminar la publicación
					$("#successful-elimination").fadeIn();
					setTimeout(function(){ $("#successful-elimination").fadeOut(); }, 7000);
					
					// Ocultamos lentamente la publicación y luego removemos el elemento del dom.	
					$("#product-"+obj.id).fadeOut('slow',function(){
						$(this).remove();
						
						if(obj.data){
							// hay publicaciones 
							
							var url_obj =  url_parser();
							
							/* START Esta logica permita resolver la url, ya que puede sufrir cambios debido a la eliminacion de publicaciones. un caso es cuando se borran todas las publicaciones de la pagina 2,
							 * el sistema automaticamente muestra las publicaciones de la pagina 1, hay es cuando esta logica entra en accion, resolviendola a como debe ser segun la data mostrada.
							 ***********************************************************************************************************************************************************************************/  
							
							// esta definido  order_by 
							if(url_obj.order_by != ''){
								if(url_obj.page != ''){
									if(obj.info.pageCount == 1){
										window.location = "#"+obj.win_order_by;
									}else{
										window.location = "#"+obj.win_order_by+'/pagina_'+obj.info.page;
									}
								}else{
									window.location = "#"+obj.win_order_by;
								}
							}else{
								if(url_obj.page != ''){
									if(obj.info.pageCount == 1){
										window.location = "#";
									}else{
										window.location = "#pagina_"+obj.info.page;
									}
								}
							}
							
							// END
							
							
							if(obj.info.pageCount == 1){
								$("#prev-page").off('click');
								$("#next-page").off('click');	
								$("#pagination").css({"display":"none"});
							}
							
							_var = {};
							_var = new set_vars(obj);
							prepare_publications('after');
						}else{
							window.location = "#";
							
							//se oculta el contenedor de los filtros
							$("#information-panel").css({"display":"none"});  
								
							// no hay publicaciones.
							$("#no-products").css({"display":"inherit"});
						}
						
					});
					

					
				}else{
					window.location = "/";
				}
				
			}
		}
	}
	
	if($("#published .delete").length){
		$("#published .delete").each(function(){

			$(this).off('click');
			$(this).on('click',function(){
				pure_json_obj = $(this).parents("div.media").children().last().html(); 
				obj 			= $.parseJSON(clean_obj(pure_json_obj));
				$("#delete_product").attr({"product_id":obj.product.id});
				
				$('#delete_product_modal').modal({"backdrop":true,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
				});
			});
			
			$("#delete_product").off('click');
			$("#delete_product").on('click',function(){
				$('#delete_product_modal').modal('hide');
				
				var request_this = {};
				
				var url_obj =  url_parser();	
				if(url_obj.page != ''){
					request_this.page	= url_obj.page;
				}
				if(url_obj.order_by != ''){
					request_this.order_by	= url_obj.order_by;
				}
				
				request_this.id  		= parseInt($(this).attr("product_id"));
				request_this.session 	= false;
				request_this.paginate 	= true;
				delete_obj.data.custon = request_this;
				new Request(delete_obj);
				
			});
			
		});
	}
	
}

/*
 * Type: función
 * Descrición: funcion que se encarga de dar formato html a las publicaciones registradas.    
 * Parametros: 
 * 	obj: objeto
 * Retorna: una cadena.
 *************************************************************************************************************************************************************/
var prepare_product = function(obj){
	
	var id 			= obj.product.id;
	var title 		= obj.product.title;
	var subtitle 	= obj.product.subtitle;
	var price 		= obj.product.price;
	var published 	= obj.product.created;
	
	var slug 		= 	str_replace(title.toLowerCase().trim(),' ','_');
	var link 		= 	'/producto/'+id+'/'+slug+'.html';
	
	var image		= 'img/products/'+obj.imagen.thumbnails.small.name;  
	
	if(obj.product.status){
		var status = '<span class="label label-success active-status">publicado</span>';
		var status_button = '<button class="btn pause"><i class="icon-stop"></i> Pausar</button>'+
							'<button class="btn activate" style="display:none;"><i class="icon-stop"></i> Activar</button>';
	}else{
		var status = '<span class="label label-warning paused-status">pausado</span>';
		var status_button = '<button class="btn pause" style="display:none;><i class="icon-stop"></i> Pausar</button>'+
							'<button class="btn activate" "><i class="icon-stop"></i> Activar</button>';
	}
		
		var quantity = obj.product.quantity
		
		if(quantity == 0){
			var _quantity = '<span class="badge">0</span>';
		}
		else if(quantity>= 1 && quantity<=5){
			var _quantity = '<span class="badge badge-important">'+quantity+'</span>';
		}
		else if(quantity>=6 && quantity<=10){
			var _quantity = '<span class="badge badge-warning">'+quantity+'</span>';
		}
		else if(quantity>10){
			var _quantity = '<span class="badge badge-success">'+quantity+'</span>';
		}
		// END
		
		
		
		
		// se arma una publicación
		var product = 	'<div id="product-'+id+'"  class="media" >'+
			'<a class="pull-left thumbnail" href="'+link+'">'+
				'<img src="'+image+'"  style="width: 200px; ">'+
			'</a>'+
			'<div class="media-body">'+
				'<h4 class="media-heading"><input type="checkbox" style="margin-top: 0px;"> <a href="'+link+'" >'+title+'</a></h4>'+
				'<h5 class="muted" >'+subtitle+'</h5>'+

				'<div style="margin-bottom: 10px;">'+
					'<div class="btn-group">'+
						'<button class="btn edit"><i class="icon-edit"></i> Editar</button>'+
						status_button+
						'<button class="btn btn-danger delete" ><i class="icon-remove-sign"></i> Eliminar</button>'+
					'</div>'+
				'</div>'+
				'<div>'+
					'<i class="icon-tag"></i> Precio: '+price+' BsF.<br>'+
					'<i class="icon-off "></i> Estatus: '+status+'<br>'+
					'<i class="icon-th"></i> Cantidad displonible: '+_quantity+'<br>'+
					'<i class="icon-calendar"></i> Publicado: '+published+
				'</div>'+			
			'</div>'+
			'<div style="display:none;"><!--'+JSON.stringify(obj)+'--></div>'+
		'</div>';
		
	
	return product;
}


/*
 * Type: función
 * Descrición: 
 * Parametros: 
 *************************************************************************************************************************************************************/
var information_panel = function(){
	
	order_by();
	pagination();
	search();
	info();
				
	//se muestra el contenedor de los filtros
	$("#information-panel").css({"display":""});  
	
}




var prepare_publications = function(type){

	if(_var.data.length > 0){
		
		// se establece la variable que almacenara las publicaciones
		var products	= '';
	
		if(type == 'initial'){
			
			$.each(_var.data,function(index){
	
				// se prepara las publicaciones
				products += prepare_product(this);	
			
				// se calcula el porcentje avansado y se actuliza la barra de progreso  
				var p = ((1*100)/((_var.current+1)-(index+1)));
				progress_bar(p+'%');
				
				/* START  ha finalizado el bucle - este codigo se ejecuta una sola vez
				 *************************************************************************/
				if(_var.current==(index+1)){
					// se oculta barra de progreso.
					$("#progress-bar").fadeOut('slow',function(){
						
						information_panel();
						
						// se establece las publicaciones en el dom
						$('#published').html(products);
									
						/* se llama a los observadores de eventos para procesar solicitudes relacionadas.
						*************************************************************************************/
						pause();
						activate();
						edit();
						_delete();
								
					});
				}
				// END
					
			});
			
		}
		if(type == 'after'){
			
			$.each(_var.data,function(index){
	
				// se prepara las publicaciones
				products += prepare_product(this);	
				
				/* START  ha finalizado el bucle - este codigo se ejecuta una sola vez
				 *************************************************************************/
				if(_var.current==(index+1)){
					
					information_panel();
					
					// se establece las publicaciones en el dom
					$('#published').html(products);
									
					/* se llama a los observadores de eventos para procesar solicitudes relacionadas.
						*************************************************************************************/
					pause();
					activate();
					edit();
					_delete();
				
				
				
				}
				// END
				
			});
			
			
			
		}
		
	}else{
		
		// se oculta barra de progreso. cuando se llama a /publicados se despliega la barra automaticamente. 
		$("#progress-bar").css({"display":"none"});
		
	}
	
}


	
/*
 * Type: clase.
 * Descrición: destinada a hacer disponible a todas las funciones variables informacion que contienen la data basica. 
 * Parametros: obj
 *************************************************************************************************************************************************************/
var set_vars = function(obj){
	this.page			= obj.info.page;
	this.current 		= parseInt(obj.info.current);
	this.count 			= parseInt(obj.info.count);   // cantidad de publicaciones o registros
	this.prevPage 		= obj.info.prevPage;
	this.nextPage 		= obj.info.nextPage;
	this.pageCount 		= obj.info.pageCount;
	this.data			= obj.data;
}

/*
 * Type: clase.
 * Descrición: destinada a procesar la url 
 * reorna un objeto.
 *************************************************************************************************************************************************************/
var url_parser = function(){
	
	var pathname = $(location).attr('href');
	var url = $.url(pathname);
	var segments = url.attr('fragment');
	
	var url_obj			= {}; 
	url_obj.search 		= "";
	url_obj.page 		= "";
	url_obj.order_by	= "";
			
	if(segments != ""){
		var split_segments = url.attr('fragment').split('/');
		if(split_segments.length){
			$(split_segments).each(function(index,parameter){
			
				if(parameter.indexOf("buscar_") !== -1){
					var search_string = str_replace(parameter,'buscar_','');
					
					/* La cadena search_string se manipula en el siguiente orden.
					* 
					* 1) se replasa los caracteres especiales
					* 2) se elimina los espacios en blancos ante y despues de la cadena
					* 3) se replasa los espacios en blancos largos por uno solo. 
					* 
					********************************************************************/ 
					url_obj.search = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');
					
					//console.log(url_obj.search);
					
				}
				if(parameter.indexOf("pagina_") !== -1){
					url_obj.page = parseInt(str_replace(parameter,'pagina_',''));
				}
				if(parameter == "mayor_precio"){
					url_obj.order_by = "mayor_precio";
				}
				if(parameter == "menor_precio"){
					url_obj.order_by = "menor_precio";	
				}
				if(parameter == "recientes"){
					url_obj.order_by = "recientes";	
				}
				if(parameter == "antiguos"){
					url_obj.order_by = "antiguos";	
				}
				if(parameter == "mayor_disponibilidad"){
					url_obj.order_by = "mayor_disponibilidad";	
				}
				if(parameter == "menor_disponibilidad"){
					url_obj.order_by = "menor_disponibilidad";	
				}

				
			});
		}
	}	
	
	return url_obj;
}


var search_info = function(obj){
	
	
	
}


/*
 * Type: funcion.
 * Descrición: destinada a procesar una buscqueda sobre los registros o publicaciones.
 *************************************************************************************************************************************************************/
var search = function(){

	var new_search_obj = {
		"type":"post",
			"url":"/products_published",
			"data":{
				"custon":{}
			},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
				
				var a	= response.responseText;
				var obj = $.parseJSON(a);
				console.log(obj);
				
				if(obj.expired_session){
					window.location = "/entrar";
				}
					
				if(obj.result){
					
					/*
					if(obj.total_products.length == 0){
						// no hay publicaciones.
						$("#no-products").css({"display":""});
					}
					*/

						// se establece la url
						var url = str_replace(obj.search,' ','_');
						window.location = "#buscar_"+url;
						
						_var = {};
						_var = new set_vars(obj);
					
						order_by();
						pagination();
						info();
					
					// si hay productos publicados.
					if(obj.data.length > 0){

						prepare_publications('after');
					
						// se oculta el mensaje que informa la no existencias de publicaciones
						$("#no-products-for-this-search").css({"display":"none"});
						
						// se establece la informacion de la cantidad de registros encontrados. 
						if(obj.info.count > 1){
							var count = obj.info.count+' registros encontrados'	
						}else{
							var count = obj.info.count+' registro encontrado'
						}
						$("#product-quantity-for-this-search").html(count);
						
						// se establece la información de lo que se busca
						$("#for-this-search").html(obj.search);
						
						// se muestra la informacion acerca de la busqueda
						$("#products-for-this-search").css({"display":"inherit"});
						
						// se muestra las publicaciones
						$("#published").css({"display":"inherit"});
					
					}else{
						
						// se oculta el mensaje que informa que hay publicaiones
						$("#products-for-this-search").css({"display":"none"});
					
						// se muestra el mensaje que indica que no hay publicaciones
						$("#no-for-this-search").html(obj.search);
						$("#no-products-for-this-search").css({"display":"inherit"});
						
						// se oculta las publicaciones
						$("#published").css({"display":"none"});

						// se oculta barra de progreso.
						$("#progress-bar").css({"display":"none"});
				
					}
					
					
				}else{
					// hay un error en la solicitud.
					window.location = "/cuenta";
				}
				
			}
		}
	}

	// validación:
	var new_search_validate_obj = {
		"submitHandler": function(form){
			
			var request_this 				= {};
			
			search_string 					= $("#search").val();
			request_this.search				= search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');
			new_search_obj.data.custon		= request_this;
			
			new Request(new_search_obj);
		
		},
		"rules":{
			"search":{
				"required":true,
				"maxlength":100,
				"noSpecialChars":true
			}
		},
		"messages":{
			"search":{
				"required":"Es preciso definir el campo para proceder con la busqueda.",
				"maxlength":"Hay un limite de 100 caracteres.",
				"noSpecialChars":"No esta perimitido usar caracteres especiales."
			}
		}
	}

	var new_search = new validate_this_form("SearchPublicationsForm",new_search_validate_obj);	
	$("#searching").css({"display":""});

	
}


$(document).ready(function(){

	var url_obj =  url_parser();
	
	// Posibles urls
	// http://www.iska.com:8080/publicados
	// http://www.iska.com:8080/publicados#pagina_1
	// http://www.iska.com:8080/publicados#mayor_precio/pagina_1
	// http://www.iska.com:8080/publicados#buscar_las_mejores/mayor_precio/pagina_1

	var products_published_obj = {
		"type":"post",
		"url":"/products_published",
		"data":{
			"custon":{}
		},
		"console_log":false,
		"callbacks":{
			"complete":function(response){
				
				var a	= response.responseText;
				var obj = $.parseJSON(a);
				console.log(obj);
					
				if(obj.expired_session){
					window.location = "/entrar";
				}
					
				if(obj.result){
					 
					if(obj.total_products.length == 0){
						// no hay publicaciones.
						$("#no-products").css({"display":""});
					}
					
					_var = {};
					_var = new set_vars(obj);
					
					if(obj.data.length > 0){
						prepare_publications('initial');
					}
					
					if(obj.data.length == 0 && obj.total_products > 0){
						window.location = "/publicados";
					}
					
					
				}else{
					// hay un error en la solicitud.
					window.location = "/cuenta";
				}
					
			}
		}
	}
		
	// se muestra la barra de progreso
	$("#progress-bar").css({"display":"inherit"});
		
	var request_this 	= {};
		
	if(url_obj.search != ''){
		// se solicita buscar algo.
		request_this.search	= url_obj.search;		
	}

	if(url_obj.page != ''){
		// la pagina esta definida
		request_this.page	= url_obj.page;
	}

	if(url_obj.order_by != ''){
		// esta definido odernar por
		request_this.order_by	= url_obj.order_by;
	}	
		
	products_published_obj.data.custon		= request_this;			
	new Request(products_published_obj);

});


/*
prototype	 	equivalente		jquery
childElements					.children()
nextSiblings					.next(),.nextAll(),.nextUntil(),.siblings()
parentElement					.parent(),:parent Selector,.parents(),.parentsUntil()  -- parents(".class") encuentra el parent element en cualquier nivel que se requiera.
hasClassName					.hasClass()
removeClassName					.removeClass()
addClassName					.addClass()
remove							.remove()
*/
