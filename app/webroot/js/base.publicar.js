/*
    Descripción: núcleo del menú principal, determina si la categoría suministrada tiene descendientes, si los tiene construye un nuevo bloque, si no tiene se supone que es la categoría seleccionada.
    Parámetros:
    category_id: int, el id de la categoría
*/
var get_child_elements = function(category_id){

    var get_child_elements_obj = {
        "type":"post",
        "url":"/get_category_child_elements",
        "data":{
            "custon":{}
        },
        "console_log":true,
        "callbacks":{
            "complete":function(response){

//                console.log(response);
//
//                var a = response.responseText;
//
//                var obj = $.parseJSON(a);
//                if(obj.expired_session){
//                    window.location = "/entrar";
//                }
//
//                set_menu(obj);
//                set_path(obj.path);

            }
        }
    };


    var category_element = $('#category-id-'+category_id);

    // remueve los vecinos a la derecha, acomodando el espacio para recibir e insertar la respuesta del servidor acerca de si hay o no mas categorías dependientes.
    var category_id_container	= $(category_element).parent().attr('id');
    $('#'+category_id_container).nextAll().each(function(){ $(this).remove() });

    var request_this = {};
    request_this.category_id  = category_id;

    get_child_elements_obj.data.custon = request_this;

    console.log('get_child_elements_obj: ',get_child_elements_obj);


//    new Request(get_child_elements_obj);

};


$(document).ready(function(){

    (function( product, $) {

        //Private Method
        var parseUrl = function(){
            /*
             * Descripción: destinada a procesar la url
             * retorna un objeto.
             *******************************************/

            // Posibles urls
            // /publicar
            // /editar/18
            // /borrador/18

            var url = $.url();
            var segments = url.segment();

            var url_obj = {};
            url_obj.action = segments[0];
            url_obj.id     = segments[1] || false;

            return url_obj;

        };


        //Private Method
        var clear = function(){

        };

        var getChildCategories = function(category_id){

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/get_category_child_elements",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));


                        //                console.log(response);
//
//                var a = response.responseText;
//
//                var obj = $.parseJSON(a);
//                if(obj.expired_session){
//                    window.location = "/entrar";
//                }
//
//                set_menu(obj);
//                set_path(obj.path);


//                        // Si la sesión ha expirado
//                        if(response['expired_session']){
//                            window.location = "/entrar";
//                        }
//
//                        if(response['result']){
//
//
//
//                        }else{
//                            // hay un error en la solicitud.
//                            window.location = "/";
//                        }

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            request_parameters['data']['category_id'] = category_id;

            ajax.request(request_parameters);


        };

        //Private Method
        var observeTheCategories = function(){

            // Id del nodo que contiene las categorías
            $("#default-options").children().each(function(){
                $(this).click(function(event){
                    event.preventDefault();

                    var element_id  = $(this).attr('id');                           // category-id-142
                    var category_id = str_replace(element_id,'category-id-','');    // 142

                    // remueve los vecinos a la derecha, acomodando el espacio para recibir e insertar la respuesta del servidor acerca de si hay o no mas categorías dependientes.
                    var category_id_container	= $(this).parent().attr('id');
                    $('#'+category_id_container).nextAll().each(function(){ $(this).remove() });

                    getChildCategories(category_id);

                });
            });
        };


        //Public Method
        product.init = function(){
            observeTheCategories();
        };


    }( window.product = window.product || {}, jQuery ));


    product.init();


//    if($('#default-options')){
//        observer_category_container();
//        transition();
//    }
//
//    if($('#product_thumbnails').find("a").length){
//        /* inhabilitar miniaturas del producto
//         *****************************************/
//        disable_thumbnails();
//
//        /* Visualizar en mejor resolución una miniatura habilitada del producto
//         ************************************************************************/
//        better_visualizing();
//    }
//
//
//    discard();
//
//    save_draft(false);
//
//    validate.form ("ProductAddForm",new_product_validate_obj);
//
//
//    activate();
//
//    pause();
//
//    _delete();
//
//    $('#ProductBody').redactor();


});

