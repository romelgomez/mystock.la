$(document).ready(function(){
    (function( publications, $, undefined ) {

        /*
         @Name              -> lastResponseData
         @visibility        -> Private
         @Type              -> Method
         @Description       -> maintains the latest data received by the server
         @parameters        -> NULL
         @returns           -> Object
         */
        var lastResponseData = {};

        /*
         @Name              -> currentAction
         @visibility        -> Private
         @Type              -> Method
         @Description       -> return the current action, exp: www.domain.com/action
         @parameters        -> NULL
         @returns           -> String
         @implemented by    ->
         */
        var currentAction = function(){
            var href 	= $(location).attr('href');
            var url 	= $.url(href);
            return url.segment(1);
        };


        /*
         @Name              -> parseUrl
         @visibility        -> Private
         @Type              -> Method
         @Description       -> parse the URL
         @parameters        -> NULL
         @returns           -> Object
         @implemented by    ->
         */
        var parseUrl = function () {

            var pathname 	= $(location).attr('href');
            var url 		= $.url(pathname);
            var segments	= url.attr('fragment');
            var action   	= url.segment(1);
            var userId   	= url.segment(2);

            var url_obj         	= {};
            url_obj['action']     	= action;
            url_obj['user-id']     	= userId;
            url_obj['search']      	= '';
            url_obj['page']        	= '';
            url_obj['order-by']    	= '';


            if(segments != ''){
                var split_segments = url.attr('fragment').split('/');
                if(split_segments.length){
                    $(split_segments).each(function(index,parameter){

                        if(parameter.indexOf("search-") !== -1){
                            var search_string = utility.stringReplace(parameter,'search-','');

                            /* La cadena search_string se manipula en el siguiente orden.
                             *
                             * 1) se reemplaza los caracteres especiales
                             * 2) se elimina los espacios en blancos ante y después de la cadena
                             * 3) se reemplaza los espacios en blancos largos por uno solo.
                             *
                             ********************************************************************/
                            url_obj.search = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');

                            //console.log(url_obj.search);

                        }
                        if(parameter.indexOf("page-") !== -1){
                            url_obj.page = parseInt(utility.stringReplace(parameter,'page-',''));
                        }


                        if(parameter == 'highest-price'){
                            url_obj['order-by'] = "highest-price";
                        }
                        if(parameter == 'lowest-price'){
                            url_obj['order-by'] = "lowest-price";
                        }
                        if(parameter == 'latest'){
                            url_obj['order-by'] = "latest";
                        }
                        if(parameter == 'oldest'){
                            url_obj['order-by'] = 'oldest';
                        }
                        if(parameter == 'higher-availability'){
                            url_obj['order-by'] = 'higher-availability';
                        }
                        if(parameter == 'lower-availability'){
                            url_obj['order-by'] = 'lower-availability';
                        }


                    });
                }
            }

            return url_obj;
        };

        // Private Method
        // give html format to the publication
        var prepareProduct = function(obj){

			var id          = obj['Product']['id'];
			var title       = obj['Product']['title'].trim();
			var price       = obj['Product']['price'];
			var publication = '';

			var date        = new Date(obj['Product']['created']);
			var created     = date.getDay()+'/'+date.getMonth()+'/'+date.getFullYear()+' - '+date.getHours()+':'+date.getMinutes();

			var slug = obj['Product']['title'].trim().toLowerCase();
			slug = utility.stringReplace(slug,'®','');
			slug = utility.stringReplace(slug,':','');
			slug = utility.stringReplace(slug,'/','');
			slug = utility.stringReplace(slug,'|','');
			slug =  slug.replace(/\s+/g, ' ');
			slug = utility.stringReplace(slug,' ','-');

			var publicationLink = '/product/'+id+'/'+slug+'.html';

			var image = '';

			if(obj['Image'] == undefined || obj['Image'].length == 0){
				image = '/resources/app/img/no-image-available.png';
			}else{
				image = '/resources/app/img/products/'+obj['Image'][0]['name'];
			}

			switch (currentAction()) {
				case 'stock':

					if(title.length > 32){
						title = title.slice(0, 30)+' ...';
					}
					title = utility.capitaliseFirstLetter(title);

					publication = '<div class="col-md-4">'+
					'<div class="thumbnail" style="border: 1px solid black; color: #ffffff; background: url(/resources/app/img/escheresque_ste.png); " >'+
					'<a href="'+publicationLink+'"><img src="'+image+'" alt="..."></a>'+
					'<div class="caption" style="border-top: 1px solid gold;">'+
					'<h3><a href="'+publicationLink+'" style="color: white;" >'+title+'</a></h3>'+
					'<h4 style="color: gold;">Price: $'+price+'</h4>'+
					'</div>'+
					'</div>'+
					'</div>';

					break;
				case 'drafts':

					var  draftLink = '/edit-draft/'+obj['Product']['id'];

					if(title == '') {
						title = '<mark>Untitled</mark>';
					}

					// se arma una publicación
					publication = '<div id="product-'+id+'"  class="media bg-info product" style="padding: 10px;border-radius: 4px; color:white; background-color: #222; background: url(/resources/app/img/escheresque_ste.png); " >'+
							'<a class="pull-left" href="'+draftLink+'">'+
							'<img src="'+image+'" class="img-thumbnail" style="width: 200px; ">'+
							'</a>'+
							'<div class="media-body">'+
							'<h4 class="media-heading" style="margin-bottom: 10px; border-bottom: 1px solid gold; padding-bottom: 9px;" ><a href="'+draftLink+'">'+utility.capitaliseFirstLetter(title)+'</a></h4>'+

							'<div style="margin-bottom: 10px;">'+
							'<div class="btn-group">'+
							'<button class="btn btn-default edit"><i class="icon-edit"></i> Edit</button>'+
							'</div>'+
							'</div>'+
							'<div>'+
							'<span class="glyphicon glyphicon-calendar"></span> Created: '+created+
							'</div>'+
							'</div>'+
							'</div>';


					break;
				case 'published':

	 				var status = '';
					var status_button = '';

					if(obj['Product']['status']){
						status = '<span class="label label-success active-status">published</span>';
						status_button = '<button class="btn btn-default pause"><span class="glyphicon glyphicon-stop"></span> Pause</button>'+'<button class="btn btn-default activate" style="display:none;"><span class="glyphicon glyphicon-play"></span> Enable</button>';
					}else{
						status = '<span class="label label-warning paused-status">paused</span>';
						status_button = '<button class="btn btn-default pause" style="display:none;"><span class="glyphicon glyphicon-stop"></span> Pause</button>'+'<button class="btn btn-default activate"><span class="glyphicon glyphicon-play"></span> Enable</button>';
					}

					var quantity = obj['Product']['quantity'];
					var _quantity = '';

					if(quantity == 0){
						_quantity = '<span class="badge">0</span>';
					}
					else if(quantity>= 1 && quantity<=5){
						_quantity = '<span class="badge badge-important">'+quantity+'</span>';
					}
					else if(quantity>=6 && quantity<=10){
						_quantity = '<span class="badge badge-warning">'+quantity+'</span>';
					}
					else if(quantity>10){
						_quantity = '<span class="badge badge-success">'+quantity+'</span>';
					}
					// END

					// se arma una publicación
					publication  = '<div id="product-'+id+'"  class="media bg-info product" style="padding: 10px;border-radius: 4px; color:white; background-color: #222; background: url(/resources/app/img/escheresque_ste.png); " >'+
							'<a class="pull-left" href="'+publicationLink+'">'+
							'<img src="'+image+'" class="img-thumbnail" style="width: 200px; ">'+
							'</a>'+
							'<div class="media-body">'+
							'<h4 class="media-heading" style="margin-bottom: 10px; border-bottom: 1px solid gold; padding-bottom: 9px;" ><a href="'+publicationLink+'">'+utility.capitaliseFirstLetter(title)+'</a></h4>'+

							'<div style="margin-bottom: 10px;">'+
							'<div class="btn-group">'+
							'<button class="btn btn-default edit"><i class="icon-edit"></i> Edit</button>'+
							status_button+
							'<button class="btn btn-danger delete" ><i class="icon-remove-sign"></i> Remove</button>'+
							'</div>'+
							'</div>'+
							'<div>'+
							'<span class="glyphicon glyphicon-tag"></span> Price: $'+price+'<br>'+
							'<span class="glyphicon glyphicon-off"></span> Status: '+status+'<br>'+
							'<span class="glyphicon glyphicon-th"></span> Quantity in stock: '+_quantity+'<br>'+
							'<span class="glyphicon glyphicon-calendar"></span> Created: '+created+
							'</div>'+
							'</div>'+
							'</div>';

					break;
			}

            return publication;

        };

        var currentOrder = function(order){

            var orderBy = $('#order-by-text');

            switch (order) {
                case 'highest-price':
                    orderBy.text('Highest price');
                    break;
                case 'lowest-price':
                    orderBy.text('Lowest price');
                    break;
                case 'latest':
                    orderBy.text('Latest');
                    break;
                case 'oldest':
                    orderBy.text('Oldest');
                    break;
                case 'higher-availability':
                    orderBy.text('Higher availability');
                    break;
                case 'lower-availability':
                    orderBy.text('Lower availability');
                    break;
                default:
                    orderBy.text('Latest');
            }

        };


        /* Private Method
         * Descripción: función destinada a ordenar la publicaciones, según la preferencia del usuario.
         **********************************************************************************************/
        var orderBy =  function(){

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/products",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        console.log(response);

                        if(response['expired_session']){
                            window.location = "/login";
                        }

                        if(response['status'] === 'success'){
                            lastResponseData = response['data'];
                            process();
                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){
                        ajax.notification("complete",notification);
                    }
                }
            };

            var orderBy = [
                {
                    'id':'highest-price',
                    'url':'highest-price',
                    'text':'Highest price'
                },                {
                    'id':'lowest-price',
                    'url':'lowest-price',
                    'text':'Lowest price'
                },                {
                    'id':'latest',
                    'url':'latest',
                    'text':'Latest'
                },                {
                    'id':'oldest',
                    'url':'oldest',
                    'text':'Oldest'
                },                {
                    'id':'higher-availability',
                    'url':'higher-availability',
                    'text':'Higher availability'
                },
                {
                    'id':'lower-availability',
                    'url':'lower-availability',
                    'text':'Lower availability'
                }
            ];

            if(lastResponseData['paging-info']['count'] > 1){

                $.each(orderBy,function(index,orderBy){

                    var element = $("#"+orderBy['id']);

                    element.off('click');
                    element.on('click',function(event){
                        event.preventDefault();

                        var url_obj =  parseUrl();
                        var request_this = {};

                        $('#order-by-text').text(orderBy['text']);

                        // Se organiza en función a una búsqueda
                        if(url_obj.search != ''){
                            // se solicita buscar algo.
                            request_this.search	= url_obj.search;

                            var url = utility.stringReplace(url_obj.search,' ','-');
                            window.location = "#search-"+url+"/"+orderBy['url'];

                        }else{
                            window.location = "#"+orderBy['url'];
                        }

                        request_this['order-by']   = orderBy['url'];

                        var pathname            = $(location).attr('href');
                        var currentUrl          = $.url(pathname);
                        request_this['action']  =  currentUrl.segment(1);
                        request_this['user-id'] =  currentUrl.segment(2);

                        request_parameters.data = request_this;
                        ajax.request(request_parameters);


                    });

                });

                $("#order-by").show();
            }else{
                $("#order-by").hide();
            }

        };

        /*
         * Private Method
         * Descripción: encargada de administrar la paginación de los resultados.
         *************************************************************************************************************************************************************/
        var pagination = function(){

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/products",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){

                        if(response['expired_session']){
                            window.location = "/login";
                        }

                        if(response['status'] === 'success'){
                            lastResponseData = response['data'];
                            process();
                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){
                        ajax.notification("complete",notification);
                    }
                }
            };

            if(lastResponseData['paging-info']['pageCount'] > 1){

                // si existe una pagina anterior y si la página anterior no es la 0
                if(lastResponseData['paging-info']['prevPage'] && (lastResponseData['paging-info']['page']-1) != 0){

                    var prevPage = $('#prev-page');

                    prevPage.attr({"disabled":false}).removeClass('disabled');
                    prevPage.off('click');
                    prevPage.on('click', function(){

                        var url_obj         =  parseUrl();
                        var prev_page       = lastResponseData['paging-info']['page']-1; // también puede tomar el valor de: url_obj.page
                        var request_this    = {};

                        // PAGE
                        request_this.page = prev_page;

                        var url     = '';
                        var new_url = '';

                        if(url_obj['order-by'] != ''){
                            if(url_obj.search != ''){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = '#search-'+url+'/'+url_obj['order-by']+'/page-'+prev_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = '#'+url_obj['order-by']+'/page-'+prev_page;
                            }
                            // ORDER
                            request_this['order-by'] = url_obj['order-by'];
                        }else{
                            if(url_obj.search != ''){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#search-"+url+"/page-"+prev_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#page-"+prev_page;
                            }
                        }

                        window.location = new_url;

                        var pathname            = $(location).attr('href');
                        var currentUrl          = $.url(pathname);
                        request_this['action']  =  currentUrl.segment(1);
                        request_this['user-id'] =  currentUrl.segment(2);


                        request_parameters.data =    request_this;
                        ajax.request(request_parameters);

                    });

                }else{
                    $("#prev-page").attr({"disabled":true}).addClass('disabled');
                }

                // si existe una siguiente pagina
                if(lastResponseData['paging-info']['nextPage']){

                    var nextPage = $("#next-page");

                    nextPage.attr({"disabled":false}).removeClass('disabled');
                    nextPage.off('click');
                    nextPage.on('click', function(){

                        var url_obj =  parseUrl();

                        var next_page = lastResponseData['paging-info']['page']+1; // también puede tomar el valor de: url_obj.page
                        var request_this = {};

                        // PAGE
                        request_this.page = next_page;

                        var url     = '';
                        var new_url = '';

                        if(url_obj['order-by'] != ''){
                            if(url_obj.search != ''){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#search-"+url+"/"+url_obj['order-by']+"/page-"+next_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#"+url_obj['order-by']+"/page-"+next_page;
                            }
                            // ORDER
                            request_this['order-by'] = url_obj['order-by'];
                        }else{
                            if(url_obj.search != ""){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#search-"+url+"/page-"+next_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#page-"+next_page;
                            }
                        }

                        window.location = new_url;

                        var pathname            = $(location).attr('href');
                        var currentUrl          = $.url(pathname);
                        request_this['action']  =  currentUrl.segment(1);
                        request_this['user-id'] =  currentUrl.segment(2);

                        request_parameters.data =    request_this;
                        ajax.request(request_parameters);

                    });
                }else{
                    $("#next-page").attr({"disabled":true}).addClass('disabled');
                }

                $("#pagination").css({"display":""});
            }else{
                $("#pagination").css({"display":"none"});
            }

        };

        /*
         * Private Method
         * Descripción: destinada a realizar una búsqueda sobre los registros o publicaciones.
         *************************************************************************************************************************************************************/
        var search = function(){

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/products",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        console.log('search',response);


                        if(response['expired_session']){
                            window.location = "/login";
                        }

                        if(response['status'] === 'success'){
                            lastResponseData = response['data'];

                            if(lastResponseData['search'] != ''){
                                // se establece la url
                                var url = utility.stringReplace(lastResponseData['search'],' ','-');
                                window.location = "#search-"+url;
                            }

                            process();
                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){
                        ajax.notification("complete",notification);
                    }
                }
            };

            // validación:
            var new_search_validate_obj = {
                "submitHandler": function(){

                    var request_this        = {};
                    var search_string       = $("#search").val();
                    request_this['search']  = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');

                    var href            	= $(location).attr('href');
                    var currentUrl          = $.url(href);
                    request_this['action']  = currentUrl.segment(1);
                    request_this['user-id'] = currentUrl.segment(2);

                    request_parameters.data =    request_this;
                    ajax.request(request_parameters);

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
                        "required":"It is necessary to define the field to proceed with the search.",
                        "maxlength":"There is a limit of 100 characters.",
                        "noSpecialChars":"Not allowed to use special characters."
                    }
                }
            };

            validate.inlineForm("SearchPublicationsForm",new_search_validate_obj);
            $("#searching").css({"display":""});


        };


        /*
         @Name              -> info
         @visibility        -> Private
         @Type              -> Method
         @Description       -> establece la información de la cantidad de registros existentes
         @parameters        -> NULL
         @returns           -> NULL
         @implemented by    -> process();
         */
        var info = function(){

            /*

             Algoritmo para obtener el resultado esperado con la data disponible:
             Cálculo Imaginario

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

            if(lastResponseData['paging-info']['count'] > 0){
                if(lastResponseData['paging-info']['count'] == 1){
                    info = '1 publication';
                }else{

                    var de = '';
                    var hasta = '';

                    if(lastResponseData['paging-info']['page'] == lastResponseData['paging-info']['pageCount']){
                        de 		= lastResponseData['paging-info']['count']-lastResponseData['paging-info']['current']+1;
                        hasta	= lastResponseData['paging-info']['count'];
                    }

                    if(lastResponseData['paging-info']['page'] < lastResponseData['paging-info']['pageCount']){
                        de 		= (lastResponseData['paging-info']['page']*lastResponseData['paging-info']['current'])-12+1;
                        hasta	= lastResponseData['paging-info']['page']*lastResponseData['paging-info']['current'];
                    }

                    var info = '<b>'+de+'</b> - <b>'+hasta+'</b> de <b>'+lastResponseData['paging-info']['count']+'</b>';

                }
            }else{
                info = '0 publications';
            }

            // se establece la información de la cantidad de registros existentes
            $("#pagination-info").find("span").html(info);

        };

		/*
		 * Private Method
		 * Descripción: función destinada a pausar una publicación activa
		 * Parámetros:  null
		 *************************************************************************************************************************************************************/
		var pause = function(){

			var notification;

			var request_parameters = {
				"requestType":"custom",
				"type":"post",
				"url":"/pause",
				"data":{},
				"callbacks":{
					"beforeSend":function(){
						notification = ajax.notification("beforeSend");
					},
					"success":function(response){

						if(response['expired_session']){
							window.location = "/entrar";
						}

						if(response['result']){
							$("#product-"+response['id']+' .pause').css({
								"display": 'none'
							});
							$("#product-"+response['id']+' .activate').css({
								"display": 'inline'
							});

							var status = '<span class="label label-warning paused-status">paused</span>';
							$("#product-"+response['id']+' .active-status').replaceWith(status);

						}else{
							window.location = "/";
						}

					},
					"error":function(){
						ajax.notification("error",notification);
					},
					"complete":function(){
						ajax.notification("complete",notification);
					}
				}
			};

			var elements = $("#products").find(".pause");

			if(elements.length){
				$(elements).each(function(){
					$(this).off('click');
					$(this).on('click',function(){

						var id = $(this).parents("div.product").attr('id');
						id = utility.stringReplace(id,'product-','');

						var request_this = {};
						request_this.id  = id;

						request_parameters.data =    request_this;
						ajax.request(request_parameters);
					});

				});
			}

		};

		/*
		 * Private Method
		 * Descripción: función destinada a activar una publicación pausada
		 * Parámetros:  null
		 *************************************************************************************************************************************************************/
		var activate = function(){

			var notification;

			var request_parameters = {
				"requestType":"custom",
				"type":"post",
				"url":"/activate",
				"data":{},
				"callbacks":{
					"beforeSend":function(){
						notification = ajax.notification("beforeSend");
					},
					"success":function(response){

						if(response['expired_session']){
							window.location = "/entrar";
						}

						if(response['result']){
							$("#product-"+response['id']+' .pause').css({
								"display": "inline"
							});
							$("#product-"+response['id']+' .activate').css({
								"display": "none"
							});
							var status = '<span class="label label-success active-status">published</span>';
							$("#product-"+response['id']+' .paused-status').replaceWith(status);
						}else{
							window.location = "/";
						}

					},
					"error":function(){
						ajax.notification("error",notification);
					},
					"complete":function(){
						ajax.notification("complete",notification);
					}
				}
			};

			var elements = $("#products").find(".activate");

			if(elements.length){
				$(elements).each(function(){
					$(this).off('click');
					$(this).on('click',function(){

						var id = $(this).parents("div.product").attr('id');
						id = utility.stringReplace(id,'product-','');

						var request_this = {};
						request_this.id  = id;



						request_parameters.data =    request_this;
						ajax.request(request_parameters);

					});
				});
			}

		};


		/*
		 * Private Method
		 * Descripción: función que procesa la solicitud de editar una publicación,  la razón de crear una función y no un simple link que es más simple, es por la maquetación o bootstrap, como es un grupo de botones pegados, al colocar un link <a></a> se descuadra. por lo tanto es requerido usar una función.
		 *************************************************************************************************************************************************************/
		var edit = function(){

			var elements = $("#products").find(".edit");

			if(elements.length){
				$(elements).each(function(){
					$(this).off('click');
					$(this).on('click',function(){

						var id = $(this).parents("div.product").attr('id');
						id = utility.stringReplace(id,'product-','');

						// edit link
						window.location = '/edit/'+id;

					});
				});
			}
		};

		/*
		 * Private Method
		 * Descripción: función que procesa la solicitud de borrar una publicación
		 *************************************************************************************************************************************************************/
		var deleteProduct =  function(){

			var notification;

			var request_parameters = {
				"requestType":"custom",
				"type":"post",
				"url":"/delete",
				"data":{},
				"callbacks":{
					"beforeSend":function(){
						notification = ajax.notification("beforeSend");
					},
					"success":function(response){

						if(response['expired_session']){
							window.location = "/entrar";
						}

						////  delete_status
						//if(response['result']){
						//	ajax.notification("success",notification);
                        //
						//	// Ocultamos lentamente la publicación y luego removemos el elemento del dom.
						//	$("#product-"+response['id']).fadeOut('slow',function(){
						//		$(this).remove();
                        //
						//		if(response['data'] !== undefined){
						//			// hay publicaciones
                        //
						//			var url_obj =  parseUrl();
                        //
						//			// START Esta lógica permite resolver la url, ya que puede sufrir cambios debido a la eliminación de publicaciones. un caso es cuando se borran todas las publicaciones de la página 2, el sistema automáticamente muestra las publicaciones de la página 1, hay es cuando esta lógica entra en acción, resolviendo la como debe ser según la data mostrada.
						//			// esta definido  order_by
						//			if(url_obj['order_by'] != ''){
						//				if(url_obj['page'] != ''){
						//					if(response['info']['pageCount'] == 1){
						//						window.location = "#"+response['win_order_by'];
						//					}else{
						//						window.location = "#"+response['win_order_by']+'/pagina-'+response['info']['page'];
						//					}
						//				}else{
						//					window.location = "#"+response['win_order_by'];
						//				}
						//			}else{
						//				if(url_obj['page'] != ''){
						//					if(response['info']['pageCount'] == 1){
						//						window.location = "#";
						//					}else{
						//						window.location = "#pagina-"+response['info']['page'];
						//					}
						//				}
						//			}
						//			// END
                        //
						//			if(response['info']['pageCount'] == 1){
						//				$("#prev-page").off('click');
						//				$("#next-page").off('click');
						//				$("#pagination").css({"display":"none"});
						//			}
                        //
						//			setLastResponseInfo(response);
						//			preparePublications();
                        //
						//		}else{
						//			window.location = "#";
                        //
						//			//se oculta el contenedor de los filtros
						//			$("#information-panel").hide();
                        //
						//			$("#yes-products").hide();
                        //
						//			// no hay publicaciones.
						//			$("#no-products").show();
                        //
						//		}
                        //
						//	});
                        //
						//}else{
						//	window.location = "/";
						//}

					},
					"error":function(){
						ajax.notification("error",notification);
					},
					"complete":function(){
						ajax.notification("complete",notification);
					}
				}
			};


			var elements = $("#products").find(".delete");

			if(elements.length){
				$(elements).each(function(){

					$(this).off('click');
					$(this).on('click',function(){

						var id = $(this).parents("div.product").attr('id');
						id = utility.stringReplace(id,'product-','');

						$("#delete_product").attr({"product_id":id});

						$('#delete_product_modal').modal({"backdrop":true,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
						});
					});

					var element = $("#delete_product");

					element.off('click');
					element.on('click',function(){
						$('#delete_product_modal').modal('hide');

						var request_this = {};

						var url_obj =  parseUrl();
						if(url_obj['page'] != ''){
							request_this.page   = url_obj['page'];
						}
						if(url_obj['order_by'] != ''){
							request_this.order_by = url_obj['order_by'];
						}

						request_this.id  		= $(this).attr("product_id");
						request_this.session 	= false;
						request_this.paginate 	= true;

						request_parameters.data = request_this;
						ajax.request(request_parameters);

					});

				});
			}

		};


        /*
         @Name              -> process
         @visibility        -> Private
         @Type              -> Method
         @Description       -> after success XHR this function, process the data.
         @parameters        -> NULL
         @returns           -> NULL
         */
        var process = function(){

            /*
             registros a mostrar = 12; según esta cantidad cierto comportamiento es observado.

             # Solo 1 registro
             - paginación                inhabilitada
             - ordenar por precio        inhabilitada
             - búsqueda                  inhabilitada

             # Entre 1 y 12 registros
             - paginación                inhabilitada - Según la cantidad de registros que se muestra en una primera vez.
             - ordenar por precio        habilitado
             - búsqueda                  inhabilitada

             # Más de 12 registros
             - paginación                habilitado - Según la cantidad de registros que se muestra en una primera vez.
             - ordenar por precio        habilitado
             - búsqueda                  habilitado

             */

            if(lastResponseData['products'].length > 0){

                if(lastResponseData['search'] != ''){
                    $("#search").val(lastResponseData['search']);
                }

                $("#search-info").hide();
                $("#no-products").hide();
                $("#products").show();
                $("#info").show();
                $("#yes-products").show();

                // se establece la variable que almacenara las publicaciones
                var products    = '';

                $.each(lastResponseData['products'],function(index,value){

                    // se prepara las publicaciones
                    products += prepareProduct(value);

                    /* START  ha finalizado el bucle - este código se ejecuta una sola vez
                     *************************************************************************/
                    if(lastResponseData['paging-info']['current']==(index+1)){

                        currentOrder(lastResponseData['order-by']);
                        orderBy();
                        pagination();
                        search();
                        info();

                        //se muestra el contenedor de los filtros
                        $("#information-panel").css({"display":""});

                        // se establece las publicaciones en el DOM
                        $('#products').html(products);

						switch(currentAction()) {
							case 'drafts':
								edit();
								deleteProduct();
								break;
							case 'published':
								pause();
								activate();
								edit();
								deleteProduct();
								break;
						}


                    }
                    // END

                });

            }else{
                if(lastResponseData['total-products'] > 0){

                    if(lastResponseData['search'] != undefined){
                        $("#search-info-text").text(lastResponseData['search']);
                    }

                    $("#no-products").hide();
                    $("#products").hide();
                    $("#info").hide();
                    $("#yes-products").show();
                    $("#search-info").show();
                }else{
                    $("#yes-products").hide();
                    $("#no-products").show();
                }
            }

        };

        /*
         @Name              -> get
         @visibility        -> Private
         @Type              -> Method
         @Description       -> get data for display the products
         @parameters        -> type => if it is for products (published, drafts or in stock)
         @returns           -> Object
         @implemented by    -> main;
         */
        var get = function(){

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/products",
                "data":parseUrl(),
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){

                        // Si la sesión ha expirado
                        if(response['expired_session']){
                            window.location = "/login";
                        }

                        if(response['status'] === 'success'){
                            lastResponseData = response['data'];
                            process();
                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){
                        ajax.notification("complete",notification);
                    }
                }
            };

            ajax.request(request_parameters);
        };



        /*
         @Name              -> main
         @visibility        -> Public
         @Type              -> Method
         @Description       -> Where everything begins
         @parameters        -> NULL
         @returns           -> NULL
         */
        publications.main = function() {

            // product to view

            switch (currentAction()) {
                case 'publish':
                    // new publication

                    break;
                case 'view':
                    // view publication

//                    URL: product

                    break;
                case 'published':
                    // publications published

//                    URL: products
                    get();

                    break;
                case 'drafts':
                    // developing publications

//                    URL: products
                    get();

                    break;
                case 'stock':
                    // publications active
                    get();

//                    URL: products

                    break;
                default:
                    window.location = "/";
            }

        };

    }( window.publications = window.publications || {}, jQuery ));

    publications.main();

});

/*

	stock
		 parseUrl
		 currentOrder
		 prepareProduct                     unificar
		 orderBy
            products
		 pagination
            products
 		 search
            products
		 info
		 process
 		 get
            products

	borradores
		 setLastResponseInfo                <- deleted ->
		 parseUrl                           listo
		 prepareProduct                     listo
		 orderBy                            listo
			get-drafts  z->   products
		 pagination                         listo
 			get-drafts  z->   products
		 search                             listo
 			get-drafts  z->   products
		 info                               listo
		 edit
		 deleteProduct                      <**  INTEGRAR **>
 			delete      z->   products
		 preparePublications               <- deleted ->
		 get                                listo
 			get-drafts  z->   products

	publicados
		 setLastResponseInfo                <- deleted ->
		 parseUrl
		 prepareProduct                     unificar
		 orderBy
			get-published  z->   products
		 pagination
			get-published  z->   products
		 search
			get-published  z->   products
		 info
		 pause                              <**  INTEGRAR **>
			pause          z->   products
		 activate                           <**  INTEGRAR **>
			 activate      z->   products
		 edit
		 deleteProduct                      <**  INTEGRAR **>
			 delete        z->   products
		 preparePublications                <- deleted ->
		 get
			 get-published z->   products

	publicar
		 initRedactor						<**  INTEGRAR **>
		 discard							<**  INTEGRAR **>
			 discard
		 elapsedTime						<**  INTEGRAR **>
		 saveDraft							<**  INTEGRAR **>
 			save_draft
		 newProduct							<**  INTEGRAR **>
 			add_new
		 pause								unificar
 			pause
		 activate							unificar
 			activate
		 _delete							unificar
 			delete
		 fileUpload							<**  INTEGRAR **>
			 disable_this_imagen
			 image_add


*/
