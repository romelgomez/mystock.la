

$(document).ready(function(){


    (function( products, $, undefined) {

        //Private Property
        var lastResponseInfo = {};

        //Private Method
        // set many properties that will be used for many private methods
        var setLastResponseInfo = function(response){
            lastResponseInfo['page']        = response['info']['page'];
            lastResponseInfo['current']     = parseInt(response['info']['current']);
            lastResponseInfo['count']       = parseInt(response['info']['count']);   // Cantidad de publicaciones o registros
            lastResponseInfo['prevPage']    = response['info']['prevPage'];
            lastResponseInfo['nextPage']    = response['info']['nextPage'];
            lastResponseInfo['pageCount']   = response['info']['pageCount'];
            lastResponseInfo['data']        = response['data'];
        };

        //Private Method
        var parseUrl = function () {
            /*
             * Type: function
             * Descripción: destinada a procesar la url
             * retorna un objeto.
             *******************************************/

            // Posibles urls
            // /stock/3
            // /publicados#pagina-1
            // /publicados#mayor-precio/pagina-1
            // /publicados#buscar-las_mejores/mayor-precio/pagina-1

            var pathname = $(location).attr('href');
            var url = $.url(pathname);
            var segments = url.attr('fragment');
            var userId   =  url.segment(2);

            var url_obj         	= {};
            url_obj.search      	= '';
            url_obj.page        	= '';
            url_obj['order-by']    	= '';
            url_obj.user_id     	= userId;


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

                        var orderBy = $('#order-by-text');

                        if(parameter == 'highest-price'){
                            url_obj['order-by'] = "highest-price";
                            orderBy.text('Highest price');
                        }
                        if(parameter == 'lowest-price'){
                            url_obj['order-by'] = "lowest-price";
                            orderBy.text('Lowest price');
                        }
                        if(parameter == 'latest'){
                            url_obj['order-by'] = "latest";
                            orderBy.text('Latest');
                        }
                        if(parameter == 'oldest'){
                            url_obj['order-by'] = 'oldest';
                            orderBy.text('Oldest');
                        }
                        if(parameter == 'higher-availability'){
                            url_obj['order-by'] = 'higher-availability';
                            orderBy.text('Higher availability');
                        }
                        if(parameter == 'lower-availability'){
                            url_obj['order-by'] = 'lower-availability';
                            orderBy.text('Lower availability');
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
            var price       = obj['Product']['price'];

            var title = obj['Product']['title'].trim();
            if(title.length > 32){
                title = title.slice(0, 30)+' ...';
            }
			title = utility.capitaliseFirstLetter(title);

            var date        = new Date(obj['Product']['created']);
            var created     = date.getDay()+'/'+date.getMonth()+'/'+date.getFullYear()+' - '+date.getHours()+':'+date.getMinutes();

            var slug = obj['Product']['title'].trim().toLowerCase();
            slug = utility.stringReplace(slug,'®','');
            slug = utility.stringReplace(slug,':','');
            slug = utility.stringReplace(slug,'/','');
            slug = utility.stringReplace(slug,'|','');
            slug =  slug.replace(/\s+/g, ' ');
            slug = utility.stringReplace(slug,' ','-');

            var link        =   '/product/'+id+'/'+slug+'.html';

            var image       = '/resources/app/img/products/'+obj['Image'][0]['name'];

			return '<div class="col-md-4">'+
				'<div class="thumbnail">'+
					'<a href="'+link+'"><img src="'+image+'" alt="..."></a>'+
					'<div class="caption">'+
						'<h3><a href="'+link+'">'+title+'</a></h3>'+
						'<h4>Price: $'+price+'</h4>'+
					'</div>'+
				'</div>'+
			'</div>';

        };

        /* Private Method
         * Descripción: función destinada a ordenar la publicaciones, según la preferencia del usuario.
         **********************************************************************************************/
        var orderBy =  function(){

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/stock-products",
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

							if(response['data'].length > 0){
								setLastResponseInfo(response);
								preparePublications(); // New

								$("#no-products").hide();
								$("#yes-products").show();
							}else{
								if(response['total-products'] > 0){
									$("#yes-products").hide();
									$("#no-products").hide();
									$("#no-products-for-this-search").show();
								}else{
									$("#yes-products").hide();
									$("#no-products-for-this-search").hide();
									$("#no-products").show();
								}
							}

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

            if(lastResponseInfo['count'] > 1){

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
                        request_this.user_id    =  currentUrl.segment(2);

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
                "url":"/stock-products",
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

							if(response['data'].length > 0){
								setLastResponseInfo(response);
								preparePublications(); // New

								$("#no-products").hide();
								$("#yes-products").show();
							}else{
								if(response['total-products'] > 0){
									$("#yes-products").hide();
									$("#no-products").hide();
									$("#no-products-for-this-search").show();
								}else{
									$("#yes-products").hide();
									$("#no-products-for-this-search").hide();
									$("#no-products").show();
								}
							}

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

            if(lastResponseInfo['pageCount'] > 1){

                // si existe una pagina anterior y si la página anterior no es la 0
                if(lastResponseInfo['prevPage'] && (lastResponseInfo['page']-1) != 0){

                    var prevPage = $('#prev-page');

                    prevPage.attr({"disabled":false}).removeClass('disabled');
                    prevPage.off('click');
                    prevPage.on('click', function(){

                        var url_obj         =  parseUrl();
                        var prev_page       = lastResponseInfo['page']-1; // también puede tomar el valor de: url_obj.page
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
                        request_this.user_id    =  currentUrl.segment(2);


                        request_parameters.data =    request_this;
                        ajax.request(request_parameters);

                    });

                }else{
                    $("#prev-page").attr({"disabled":true}).addClass('disabled');
                }

                // si existe una siguiente pagina
                if(lastResponseInfo['nextPage']){

                    var nextPage = $("#next-page");

                    nextPage.attr({"disabled":false}).removeClass('disabled');
                    nextPage.off('click');
                    nextPage.on('click', function(){

                        var url_obj =  parseUrl();

                        var next_page = lastResponseInfo['page']+1; // también puede tomar el valor de: url_obj.page
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
                        request_this.user_id    =  currentUrl.segment(2);

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
                "url":"/stock-products",
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

							// se establece la url
							var url = utility.stringReplace(response['search'],' ','-');
							window.location = "#search-"+url;
							// is show the search text
							$("#this-search").text(response['search']);

							if(response['data'].length > 0){
								setLastResponseInfo(response);
								preparePublications(); // New

								$("#no-products").hide();
								$("#yes-products").show();
							}else{
								if(response['total-products'] > 0){
									$("#yes-products").hide();
									$("#no-products").hide();
									$("#no-products-for-this-search").show();
								}else{
									$("#yes-products").hide();
									$("#no-products-for-this-search").hide();
									$("#no-products").show();
								}
							}

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
                    request_this.search     = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');

                    var pathname            = $(location).attr('href');
                    var currentUrl          = $.url(pathname);
                    request_this.user_id    =  currentUrl.segment(2);

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
         * Private Method
         * Descripción:   se establece la información de la cantidad de registros existentes
         * Parámetros:
         *************************************************************************************************************************************************************/
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

            if(lastResponseInfo['count'] > 0){
                if(lastResponseInfo['count'] == 1){
                    info = '1 publication';
                }else{

                    var de = '';
                    var hasta = '';

                    if(lastResponseInfo['page'] == lastResponseInfo['pageCount']){
                        de 		= lastResponseInfo['count']-lastResponseInfo['current']+1;
                        hasta	= lastResponseInfo['count'];
                    }

                    if(lastResponseInfo['page'] < lastResponseInfo['pageCount']){
                        de 		= (lastResponseInfo['page']*lastResponseInfo['current'])-10+1;
                        hasta	= lastResponseInfo['page']*lastResponseInfo['current'];
                    }

                    var info = '<b>'+de+'</b> - <b>'+hasta+'</b> de <b>'+lastResponseInfo['count']+'</b>';

                }
            }else{
                info = '0 publications';
            }

            // se establece la información de la cantidad de registros existentes
            $("#pagination-info").find("span").html(info);

        };






        //Private Method
        var preparePublications = function(){

            /*
             registros a mostrar = 10; según esta cantidad cierto comportamiento es observado.

             # Solo 1 registro
             - paginación                inhabilitada
             - ordenar por precio        inhabilitada
             - búsqueda                  inhabilitada

             # Entre 1 y 10 registros
             - paginación                inhabilitada - Según la cantidad de registros que se muestra en una primera vez.
             - ordenar por precio        habilitado
             - búsqueda                  inhabilitada

             # Más de 10 registros
             - paginación                habilitado - Según la cantidad de registros que se muestra en una primera vez.
             - ordenar por precio        habilitado
             - búsqueda                  habilitado

             */

            if(lastResponseInfo['data'].length > 0){

                // se establece la variable que almacenara las publicaciones
                var products    = '';

                $.each(lastResponseInfo['data'],function(index,value){

                    // se prepara las publicaciones
                    products += prepareProduct(value);

                    /* START  ha finalizado el bucle - este código se ejecuta una sola vez
                     *************************************************************************/
                    if(lastResponseInfo['current']==(index+1)){

                        orderBy();
                        pagination();
                        search();
                        info();

                        //se muestra el contenedor de los filtros
                        $("#information-panel").css({"display":""});

                        // se establece las publicaciones en el DOM
                        $('#products').html(products);


                    }
                    // END

                });

            }

        };


        var notification;

        //Public Method
        products.get = function(){
            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/stock-products",
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

							if(response['data'].length > 0){
								setLastResponseInfo(response);
								preparePublications(); // New

								$("#no-products").hide();
								$("#yes-products").show();
							}else{
								if(response['total-products'] > 0){

									if(response['search'] != undefined){
										$("#this-search").text(response['search']);
									}

									$("#yes-products").hide();
									$("#no-products").hide();
									$("#no-products-for-this-search").show();
								}else{
									$("#yes-products").hide();
									$("#no-products-for-this-search").hide();
									$("#no-products").show();
								}
							}

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

    }( window.products = window.products || {}, jQuery ));



    products.get();

});
