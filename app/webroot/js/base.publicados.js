

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
            // /publicados
            // /publicados#pagina_1
            // /publicados#mayor_precio/pagina_1
            // /publicados#buscar_las_mejores/mayor_precio/pagina_1

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

                        if(parameter.indexOf("buscar_") !== -1){
                            var search_string = str_replace(parameter,'buscar_','');

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
        };

        // Private Method
        // give html format to the publication
        var prepareProduct = function(obj){

            var id          = obj['product']['id'];
            var title       = obj['product']['title'];
            var price       = obj['product']['price'];
            var published   = obj['product']['created'];

            var slug        =   str_replace(title.toLowerCase().trim(),' ','_');
            var link        =   '/producto/'+id+'/'+slug+'.html';

            var image       = 'img/products/'+obj['imagen']['thumbnails']['small']['name'];

            var status = '';
            var status_button = '';

            if(obj['product']['status']){
                status = '<span class="label label-success active-status">publicado</span>';
                status_button = '<button class="btn btn-default pause"><span class="glyphicon glyphicon-stop"></span> Pausar</button>'+'<button class="btn btn-default activate" style="display:none;"><span class="glyphicon glyphicon-play"></span> Activar</button>';
            }else{
                status = '<span class="label label-warning paused-status">pausado</span>';
                status_button = '<button class="btn btn-default pause" style="display:none;"><span class="glyphicon glyphicon-stop"></span> Pausar</button>'+'<button class="btn btn-default activate"><span class="glyphicon glyphicon-play"></span> Activar</button>';
            }

            var quantity = obj['product']['quantity'];
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
            return  '<div id="product-'+id+'"  class="media bg-info" style="padding: 10px;border-radius: 4px;" >'+
                        '<a class="pull-left" href="'+link+'">'+
                            '<img src="'+image+'" class="img-thumbnail" style="width: 200px; ">'+
                        '</a>'+
                        '<div class="media-body">'+
                            '<h4 class="media-heading" style="margin-bottom: 10px; border-bottom: 1px solid #B6B6B6; padding-bottom: 9px;" ><a href="'+link+'" >'+title+'</a></h4>'+

                            '<div style="margin-bottom: 10px;">'+
                                '<div class="btn-group">'+
                                    '<button class="btn btn-default edit"><i class="icon-edit"></i> Editar</button>'+
                                    status_button+
                                    '<button class="btn btn-danger delete" ><i class="icon-remove-sign"></i> Eliminar</button>'+
                                '</div>'+
                            '</div>'+
                            '<div>'+
                                '<span class="glyphicon glyphicon-tag"></span> Precio: '+price+' BsF.<br>'+
                                '<span class="glyphicon glyphicon-off"></span> Estatus: '+status+'<br>'+
                                '<span class="glyphicon glyphicon-th"></span> Cantidad disponible: '+_quantity+'<br>'+
                                '<span class="glyphicon glyphicon-calendar"></span> Publicado: '+published+
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
                "url":"/published",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = base.ajaxRequestNotification("beforeSend");
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
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
                    }
                }
            };


            var order_by = {
                "higher-price":"mayor_precio",
                "lower-price":"menor_precio",
                "recent":"recientes",
                "oldest":"antiguos",
                "higher-availability":"mayor_disponibilidad",
                "lower-availability":"menor_disponibilidad"
            };

            if(lastResponseInfo['count'] > 1){

                $.each(order_by,function(id,order_by){

                    var element = $("#"+id);

                    element.off('click');
                    element.on('click',function(event){
                        event.preventDefault();

                        var url_obj =  parseUrl();

                        var request_this = {};

                        if(url_obj.search != ''){
                            // se solicita buscar algo.
                            request_this.search	= url_obj.search;

                            var url = str_replace(url_obj.search,' ','_');
                            window.location = "#buscar_"+url+"/"+order_by;

                        }else{
                            window.location = "#"+order_by;
                        }

                        request_this.order_by   = order_by;
                        request_parameters.data = request_this;
                        ajax.request(request_parameters);


                    });

                });

                $("#order-by").css({"display":""});

            }else{
                $("#order-by").css({"display":"none"});
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
                "url":"/published",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = base.ajaxRequestNotification("beforeSend");
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
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
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
                                url = str_replace(url_obj.search,' ','_');
                                new_url = "#buscar_"+url+"/"+url_obj.order_by+"/pagina_"+prev_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#"+url_obj.order_by+"/pagina_"+prev_page;
                            }
                            // ORDER
                            request_this.order_by = url_obj.order_by;
                        }else{
                            if(url_obj.search != ""){
                                url = str_replace(url_obj.search,' ','_');
                                new_url = "#buscar_"+url+"/pagina_"+prev_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#pagina_"+prev_page;
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
                                url = str_replace(url_obj.search,' ','_');
                                new_url = "#buscar_"+url+"/"+url_obj.order_by+"/pagina_"+next_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#"+url_obj.order_by+"/pagina_"+next_page;
                            }
                            // ORDER
                            request_this.order_by = url_obj.order_by;
                        }else{
                            if(url_obj.search != ""){
                                url = str_replace(url_obj.search,' ','_');
                                new_url = "#buscar_"+url+"/pagina_"+next_page;
                                //SEARCH
                                request_this.search = url_obj.search;
                            }else{
                                new_url = "#pagina_"+next_page;
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
                "url":"/published",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = base.ajaxRequestNotification("beforeSend");
                    },
                    "success":function(response){

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        if(response['result']){

                            // se establece la url
                            var url = str_replace(response['search'],' ','_');
                            window.location = "#buscar_"+url;




                            // si hay productos publicados.
                            if(response['data'].length > 0){

                                setLastResponseInfo(response);
                                preparePublications();

                                orderBy();
                                pagination();
                                info();

                                // se oculta el mensaje que informa la no existencias de publicaciones
                                $("#no-products-for-this-search").css({"display":"none"});

                                // se establece la información de la cantidad de registros encontrados.
                                var count = '';
                                if(response['info']['count'] > 1){
                                    count = response['info']['count']+' registros encontrados'
                                }else{
                                    count = response['info']['count']+' registro encontrado'
                                }

                                $("#product-quantity-for-this-search").html(count);

                                // se establece la información de lo que se busca
                                $("#for-this-search").html(response['search']);

                                // se muestra la información acerca de la búsqueda
                                $("#products-for-this-search").css({"display":"inherit"});

                                // se muestran las publicaciones
                                $("#products").css({"display":"inherit"});

                            }else{

                                // se oculta el mensaje que informa que hay publicaciones
                                $("#products-for-this-search").css({"display":"none"});

                                // se muestra el mensaje que indica que no hay publicaciones
                                $("#no-for-this-search").html(response['search']);
                                $("#no-products-for-this-search").css({"display":"inherit"});

                                // se oculta las publicaciones
                                $("#products").css({"display":"none"});

                            }

                        }else{
                            // hay un error en la solicitud.
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
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
                info = '0 publicaciónes';
            }

            // se establece la informacion de la cantidad de registros existentes
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
                        notification = base.ajaxRequestNotification("beforeSend");
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

                            var status = '<span class="label label-warning paused-status">pausado</span>';
                            $("#product-"+response['id']+' .active-status').replaceWith(status);

                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
                    }
                }
            };

            var elements = $("#products").find(".pause");

            if(elements.length){
                $(elements).each(function(){
                    $(this).off('click');
                    $(this).on('click',function(){
                        var pure_json_obj   = $(this).parents("div.media").children().last().html();
                        var obj             = $.parseJSON(clean_obj(pure_json_obj));
                        var request_this = {};
                        request_this.id  = obj.product.id;

                        request_parameters.data =    request_this;
                        ajax.request(request_parameters);
                    });

                });
            }

        };

        /*
         * Private Method
         * Descripción: función destinada a activar una publicación pausada
         * Parametros:  null
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
                        notification = base.ajaxRequestNotification("beforeSend");
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
                            var status = '<span class="label label-success active-status">publicado</span>';
                            $("#product-"+response['id']+' .paused-status').replaceWith(status);
                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
                    }
                }
            };

            var elements = $("#products").find(".activate");

            if(elements.length){
                $(elements).each(function(){
                    $(this).off('click');
                    $(this).on('click',function(){
                        var pure_json_obj   = $(this).parents("div.media").children().last().html();
                        var obj             = $.parseJSON(clean_obj(pure_json_obj));
                        var request_this = {};
                        request_this.id  = obj.product.id;
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

                        var pure_json_obj   = $(this).parents("div.media").children().last().html();
                        var obj             = $.parseJSON(clean_obj(pure_json_obj));

                        // edit link
                        window.location = '/editar/'+obj.product.id;

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
                        notification = base.ajaxRequestNotification("beforeSend");
                    },
                    "success":function(response){

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        //  delete_status
                        if(response['result']){

                            // Exito al eliminar la publicación
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
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
                    }
                }
            };


            var elements = $("#products").find(".delete");

            if(elements.length){
                $(elements).each(function(){

                    $(this).off('click');
                    $(this).on('click',function(){
                        var pure_json_obj = $(this).parents("div.media").children().last().html();
                        var obj 			= $.parseJSON(clean_obj(pure_json_obj));
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

                        request_this.id  		= parseInt($(this).attr("product_id"));
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
                        pause();
                        activate();
                        edit();
                        deleteProduct();

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
                "url":"/published",
                "data":parseUrl(),
                "callbacks":{
                    "beforeSend":function(){
                        notification = base.ajaxRequestNotification("beforeSend");
                    },
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

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
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){
                        base.ajaxRequestNotification("complete",notification);
                    }
                }
            };

            ajax.request(request_parameters);
        };

    }( window.products = window.products || {}, jQuery ));



    products.get();

});