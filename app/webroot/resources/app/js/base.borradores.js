

$(document).ready(function(){


    (function( productsPublished, $, undefined) {

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
            // /publicados
            // /publicados#pagina-1
            // /publicados#mayor-precio/pagina-1
            // /publicados#buscar-las_mejores/mayor-precio/pagina-1

            var pathname = $(location).attr('href');
            var url = $.url(pathname);
            var segments = url.attr('fragment');

            var url_obj         = {};
            url_obj.search      = "";
            url_obj.page        = "";
            url_obj.order_by    = "";

            if(segments != ""){
                var split_segments = url.attr('fragment').split('/');
                if(split_segments.length){
                    $(split_segments).each(function(index,parameter){

                        if(parameter.indexOf("buscar-") !== -1){
                            var search_string = utility.stringReplace(parameter,'buscar-','');

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
                        if(parameter.indexOf("pagina-") !== -1){
                            url_obj.page = parseInt(utility.stringReplace(parameter,'pagina-',''));
                        }

                        var orderBy = $('#order-by-text');

                        if(parameter == "mayor-precio"){
                            url_obj.order_by = "mayor-precio";
                            orderBy.text('Mayor precio');
                        }
                        if(parameter == "menor-precio"){
                            url_obj.order_by = "menor-precio";
                            orderBy.text('Menor precio');
                        }
                        if(parameter == "recientes"){
                            url_obj.order_by = "recientes";
                            orderBy.text('Recientes');
                        }
                        if(parameter == "antiguos"){
                            url_obj.order_by = "antiguos";
                            orderBy.text('Antiguos');
                        }
                        if(parameter == "mayor-disponibilidad"){
                            url_obj.order_by = "mayor-disponibilidad";
                            orderBy.text('Mayor disponibilidad');
                        }
                        if(parameter == "menor-disponibilidad"){
                            url_obj.order_by = "menor-disponibilidad";
                            orderBy.text('Menor disponibilidad');
                        }


                    });
                }
            }

            return url_obj;
        };

        // Private Method
        // give html format to the publication
        var prepareProduct = function(obj){

            var id          = obj['product']['id'];
            var title       = obj['product']['title'];
            var price       = obj['product']['price'];

            var date   = new Date(obj['product']['created']);
            var created = date.getDay()+'/'+date.getMonth()+'/'+date.getFullYear()+' - '+date.getHours()+':'+date.getMinutes();

            var image = '';

            if(obj['imagen'] == undefined){
                image = '/resources/app/img/foto-no-disponible.jpg';
            }else{
                image = '/resources/app/img/products/'+obj['imagen']['thumbnails']['small']['name'];
            }

            var  link = '/editar_borrador/'+obj['product']['id'];


            if(title == '') {
                title = '<mark>Sin título disponible</mark>';
            }


            // se arma una publicación
            return  '<div id="product-'+id+'"  class="media bg-info" style="padding: 10px;border-radius: 4px;" >'+
                '<a class="pull-left" href="'+link+'">'+
                '<img src="'+image+'" class="img-thumbnail" style="width: 200px; ">'+
                '</a>'+
                '<div class="media-body">'+
                '<h4 class="media-heading" style="margin-bottom: 10px; border-bottom: 1px solid #B6B6B6; padding-bottom: 9px;" ><a href="'+link+'" >'+title+'</a></h4>'+

                '<div style="margin-bottom: 10px;">'+
                '<div class="btn-group">'+
                '<button class="btn btn-default edit"><i class="icon-edit"></i> Editar</button>'+
                '</div>'+
                '</div>'+
                '<div>'+
                '<span class="glyphicon glyphicon-calendar"></span> Creado: '+created+
                '</div>'+
                '</div>'+
                '<div style="display:none;"><!--'+JSON.stringify(obj)+'--></div>'+
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
                "url":"/drafts",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        if(response['result'] == true || response['result'] == undefined){
                            if(response['data'].length > 0){
                                // hay publicaciones

                                setLastResponseInfo(response);
                                preparePublications();

                            }else{
                                // no hay publicaciones.
                                $("#no-products").css({"display":"inherit"});
                            }
                        }else{
                            // hay un error en la solicitud.
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
                    'id':'higher-price',
                    'url':'mayor-precio',
                    'text':'Mayor precio'
                },                {
                    'id':'lower-price',
                    'url':'menor-precio',
                    'text':'Menor precio'
                },                {
                    'id':'recent',
                    'url':'recientes',
                    'text':'Recientes'
                },                {
                    'id':'oldest',
                    'url':'antiguos',
                    'text':'Antiguos'
                },                {
                    'id':'higher-availability',
                    'url':'mayor-disponibilidad',
                    'text':'Mayor disponibilidad'
                },                {
                    'id':'lower-availability',
                    'url':'menor-disponibilidad',
                    'text':'Menor disponibilidad'
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
                            window.location = "#buscar-"+url+"/"+orderBy['url'];

                        }else{
                            window.location = "#"+orderBy['url'];
                        }

                        request_this.order_by   = orderBy['url'];
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
                "url":"/drafts",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        if(response['result'] == true || response['result'] == undefined){
                            if(response['data'].length > 0){
                                // hay publicaciones
                                setLastResponseInfo(response);
                                preparePublications();
                            }else{
                                // no hay publicaciones.
                                $("#no-products").css({"display":"inherit"});
                            }
                        }else{
                            // hay un error en la solicitud.
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

                    var prevPage = $("#prev-page");

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

                        if(url_obj.order_by != ""){
                            if(url_obj.search != ""){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#buscar-"+url+"/"+url_obj.order_by+"/pagina-"+prev_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#"+url_obj.order_by+"/pagina-"+prev_page;
                            }
                            // ORDER
                            request_this.order_by = url_obj.order_by;
                        }else{
                            if(url_obj.search != ""){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#buscar-"+url+"/pagina-"+prev_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#pagina-"+prev_page;
                            }
                        }

                        window.location = new_url;

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

                        if(url_obj.order_by != ""){
                            if(url_obj.search != ""){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#buscar-"+url+"/"+url_obj.order_by+"/pagina-"+next_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#"+url_obj.order_by+"/pagina-"+next_page;
                            }
                            // ORDER
                            request_this.order_by = url_obj.order_by;
                        }else{
                            if(url_obj.search != ""){
                                url = utility.stringReplace(url_obj.search,' ','-');
                                new_url = "#buscar-"+url+"/pagina-"+next_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#pagina-"+next_page;
                            }
                        }

                        window.location = new_url;

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
                "url":"/drafts",
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

                            // se establece la url
                            var url = utility.stringReplace(response['search'],' ','-');
                            window.location = "#buscar-"+url;

                            setLastResponseInfo(response);
                            preparePublications();

                            orderBy();
                            pagination();
                            info();

                            var searchInfo = $("#search-info");

                            // si hay productos publicados.
                            if(response['data'].length > 0){
                                searchInfo.hide();

                                // se muestran las publicaciones
                                $("#products").show();
                            }else{
                                // se muestra el mensaje que indica que no hay publicaciones
                                searchInfo.text('No hay resultados para: '+response['search']);
                                searchInfo.show();

                                // se oculta las publicaciones
                                $("#products").hide();
                            }

                        }else{
                            // hay un error en la solicitud.
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
                        "required":"Es preciso definir el campo para proceder con la búsqueda.",
                        "maxlength":"Hay un límite de 100 caracteres.",
                        "noSpecialChars":"No esta permitido usar caracteres especiales."
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
                    info = '1 publicación';
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
                info = '0 publicaciones';
            }

            // se establece la información de la cantidad de registros existentes
            $("#pagination-info").find("span").html(info);

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

                        var pure_json_obj   = $(this).parents("div.media").children().last().html();
                        var obj             = $.parseJSON(utility.removeCommentTag(pure_json_obj));

                        window.location = '/editar_borrador/'+obj['product']['id'];

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

                        //  delete_status
                        if(response['result']){

                            // Éxito al eliminar la publicación
                            $("#successful-elimination").fadeIn();
                            setTimeout(function(){ $("#successful-elimination").fadeOut(); }, 7000);

                            // Ocultamos lentamente la publicación y luego removemos el elemento del dom.
                            $("#product-"+response['id']).fadeOut('slow',function(){
                                $(this).remove();

                                if(response['data'].length > 0){
                                    // hay publicaciones

                                    var url_obj =  parseUrl();

                                    // START Esta lógica permite resolver la url, ya que puede sufrir cambios debido a la eliminación de publicaciones. un caso es cuando se borran todas las publicaciones de la página 2, el sistema automáticamente muestra las publicaciones de la página 1, hay es cuando esta lógica entra en acción, resolviendo la como debe ser según la data mostrada.
                                    // esta definido  order_by
                                    if(url_obj['order_by'] != ''){
                                        if(url_obj['page'] != ''){
                                            if(response['info']['pageCount'] == 1){
                                                window.location = "#"+response['win_order_by'];
                                            }else{
                                                window.location = "#"+response['win_order_by']+'/pagina_'+response['info']['page'];
                                            }
                                        }else{
                                            window.location = "#"+response['win_order_by'];
                                        }
                                    }else{
                                        if(url_obj['page'] != ''){
                                            if(response['info']['pageCount'] == 1){
                                                window.location = "#";
                                            }else{
                                                window.location = "#pagina_"+response['info']['page'];
                                            }
                                        }
                                    }
                                    // END

                                    if(response['info']['pageCount'] == 1){
                                        $("#prev-page").off('click');
                                        $("#next-page").off('click');
                                        $("#pagination").css({"display":"none"});
                                    }

                                    setLastResponseInfo(response);
                                    preparePublications();

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
                        var pure_json_obj = $(this).parents("div.media").children().last().html();
                        var obj 			= $.parseJSON(utility.stringReplace(pure_json_obj));
                        $("#delete_product").attr({"product_id":obj['product']['id']});

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

                        /* se llama a los observadores de eventos para procesar solicitudes relacionadas.
                         *********************************************************************************/
                        edit();
                        deleteProduct();

                    }
                    // END

                });

            }

        };


        var notification;

        //Public Method
        productsPublished.get = function(){
            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/drafts",
                "data":parseUrl(),
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        // Si la sesión ha expirado
                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        if(response['result'] == true || response['result'] == undefined){

                            // No hay productos publicados.
                            if(response['total_products'] == 0){
                                $("#no-products").css({"display":"inherit"});
                            }else{
                                $("#yes-products").css({"display":"inherit"});
                            }


                            if(response['data'].length > 0){
                                setLastResponseInfo(response);
                                preparePublications(); // New
                            }

                            // Al copiar la url "/publicados#buscar_algo" en la barra de navegación, donde "algo" no existe. Y existan productos publicados.
                            if(response['data'].length == 0 && response['total_products'] > 0){
                                window.location = "/publicados";
                            }

                        }else{
                            // hay un error en la solicitud.
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

    }( window.productsPublished = window.productsPublished || {}, jQuery ));



    productsPublished.get();

});