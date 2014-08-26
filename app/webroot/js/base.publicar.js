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

        /*
         Private Method
         Descripción: para re-construir y observar eventos del menú.
         Parámetros:
         menu: JSON OBJECT
         */
        var setMenu = function(obj){

            var menu = $('#menu');
            var add_content = $('#add-content');

            var ul = '';

            var newWidth                    = 0;
            var parent_obj_width_dimension  = 0;
            var child_obj_width_dimension   = 0;
            var scroll_value                = 0;

            if(obj['children']){

                // los elementos que estén + 1 - cuento los menús - el por defecto y los que estén desplegados - más el que será próximamente insertado en el dom es decir (+1) y así calcular el nuevo ancho
                newWidth = ((menu.children().length)+1)*251;
                menu.css({"width":newWidth+'px'});

                //var scroll_value = $('menu-box').scrollLeft	- la idea es si la caja interna sobrepasa el tamaño al insertar un nuevo menu dependiente
                // el scroll automáticamente se actualiza pero no es visible el sub-menú por lo tanto hay que auto rodar hasta el final
                parent_obj_width_dimension 	= menu.parent().width();
                child_obj_width_dimension	= menu.width();

                if(child_obj_width_dimension > parent_obj_width_dimension){
                    scroll_value = child_obj_width_dimension - parent_obj_width_dimension;
                    $('#menu-box').scrollLeft(scroll_value);
                }



                var tmp1							= base.randomNumber(1,9999);
                var tmp2 							= base.randomNumber(10000,20000);
                var random_category_id_container	= 'dependent-options-'+base.randomNumber(tmp1,tmp2);


                ul += '<div class="ulMenu" id="'+random_category_id_container+'">';
                $.each(obj['categories'],function(k,v){
                    ul +=  '<div id="category-id-'+k+'" class="liMenu"> '+v+'</div>'
                });
                ul += '</div>';

                menu.append(ul);

                // se observa este nuevo elemento - el nuevo sub-menú para ser procesado
                observeTheCategories(random_category_id_container);

                $('#ProductCategoryId').attr({"value":null});

                add_content.attr({"disabled":"disabled"}); //  se inhabilita el botón para proseguir con la carga del nuevo product
                // add .disabled
                if(!add_content.hasClass("disabled")){
                    add_content.addClass("disabled");
                }

            }else{

                newWidth = ((menu.children().length)*251)+301;
                menu.css({"width":newWidth+'px'});

                // var scroll_value = $('menu-box').scrollLeft - la idea es si la caja interna sobrepasa el tamaño al insertar un nuevo menu dependiente
                // el scroll automáticamente se actualiza pero no es visible el sub-menú por lo tanto hay que auto rodar hasta el final
                parent_obj_width_dimension 	= menu.parent().width();
                child_obj_width_dimension	= menu.width();

                if(child_obj_width_dimension > parent_obj_width_dimension){
                    scroll_value = child_obj_width_dimension - parent_obj_width_dimension;
                    $('#menu-box').scrollLeft(scroll_value);
                }

                ul = '<div id="category-selected" class="category-selected"><div style="margin-top: 50px;" ><div style="text-align: center;"><span class="category-selected-text">Categoría seleccionada!</span> <br><img src="/img/ok.png" alt="Gracias" /></div></div></div>';
                menu.append(ul);

                $('#ProductCategoryId').attr({"value":obj['category_id_selected']});

                add_content.attr({"disabled":false});
                // remove css class .disabled
                if(add_content.hasClass("disabled")){
                    add_content.removeClass("disabled");
                }

            }

            var category_element = $('#category-id-'+obj['id']);

            // establecer estilos - del actual contenedor-menu - se verifica si existe un elemento con la clase css li-selected - si existe se le remueve - se procede a agregarle la clase css  li-selected a la categoría seleccionada
            $(category_element).parent().children().each(function(){
                if($(this).hasClass('li-selected')){
                    $(this).removeClass('li-selected');
                }
            });
            $(category_element).addClass('li-selected');

        };


        /*
         Private Method
         Descripción: remueve los vecinos a la derecha, acomodando el espacio para recibir e insertar la respuesta del servidor acerca de si hay o no mas categorías dependientes.
         Parámetros:
            element_id
         */

        var clear = function(element_id){
            var category_id_container	= $("#"+element_id).parent().attr('id');
            $('#'+category_id_container).nextAll().each(function(){
                $(this).remove();
            });

        };

        /*
         Private Method
         Descripción: para construir y observar el path: electrónicos>laptops>14"  cada sección representa una previa selección en el menú, la idea es regresar a un estado anterior del menú rápidamente sin tener que observar todas las categorías.
         Parámetros:
         path: JSON OBJECT
         */
        var setPath =  function(path){
            var h = '';

            $.each(path,function(k,category){

                if((k+1)==$(path).length){
                    h += '<li id="'+'path-category-id-'+category['id']+'" class="active">'+category['name']+'</li>';
                }else{
                    h += '<li id="'+'path-category-id-'+category['id']+'"><span style="color: #08C;">'+category['name']+'</span></li>';
                }
            });

            var pathElement = $('#path');

            pathElement.html(h);
            $('#path2').html(h);

            pathElement.children().each(function(){
                $(this).click(function(event){
                    event.preventDefault();

                    var path_category_id            = $(this).attr('id'); //  path-category-id-142
                    var category_id                 = str_replace(path_category_id,'path-category-id-','');	// 142

                    var element_id = 'category-id-'+category_id;
                    clear(element_id);// category-id-142

                    getChildCategories(category_id);

                })
            });
        };

        /*
         Private Method
         Descripción: para rearmar el menu de categorías completamente y el path.
        */
        var updateAllMenu = function(){
            var current_menu = $.parseJSON($("#current-menu").html());
            setPath(current_menu);
            $.each(current_menu,function(){
                setMenu(this);
            });
        };

        /*
         Private Method
         Descripción: Administrar la transición, luego de seleccionar la categoría, al editar la categoría.
         */
        var transition = function(){
            // Cuando se va editar hay que acomodar el menú de selección de categorías para que en caso de que el usuario quiera editar la categoría pueda observar el menú tal cual lo dejo.
            if($('#ProductCategoryId').val()){
            updateAllMenu();

                //console.log('se debe empezar a migrar UpdateAllMenu.buildMenu(id) de categories.js');

                $('#category-selector').css({"display":"none"});
                $('#add-product').css({"display":'block'});
            }else{
                $('#category-selector').css({"display": "block"});
                $('add-product').css({"display": "none"});
            }

            $('#add-content').click(function(event){
                event.preventDefault();
                $('#category-selector').css({"display":"none"});
                $('#add-product').css({"display":"inherit"});
            });

            $('#edit-category').click(function(event){
                event.preventDefault();

                $('#category-selector').css({"display":"block"});
                $('#add-product').css({"display": "none"});

                var menu = $('#menu');

                var parent_obj_width_dimension 	= menu.parent().width();
                var child_obj_width_dimension	= menu.width();

                if(child_obj_width_dimension > parent_obj_width_dimension){
                    var scroll_value = child_obj_width_dimension - parent_obj_width_dimension;
                    $('#menu-box').scrollLeft(scroll_value);
                }

            });
        };



        /*
         Private Method
         Descripción: núcleo del menú principal, determina si la categoría suministrada tiene descendientes, si los tiene construye un nuevo bloque, si no tiene se supone que es la categoría seleccionada.
         Parámetros:
         category_id: int, el id de la categoría
        */
        var getChildCategories = function(category_id){

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/get_category_child_elements",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        setMenu(response);
                        setPath(response['path']);

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            request_parameters['data']['category_id'] = category_id;

            ajax.request(request_parameters);


        };

        //Private Method
        var observeTheCategories = function(category_id_container){

            if(!category_id_container){
                category_id_container = 'default-options';
            }

            // Id del nodo que contiene las categorías
            $('#'+category_id_container).children().each(function(){
                $(this).click(function(event){
                    event.preventDefault();

                    var element_id  = $(this).attr('id');                           // category-id-142
                    var category_id = str_replace(element_id,'category-id-','');    // 142

                    clear(element_id);// category-id-142
                    getChildCategories(category_id);

                });
            });
        };

        var initRedactor = function(){
            $('#ProductBody')['redactor']({
                lang: 'es'
            });
        };

        /*
         Private Method
         Descripción: destinada a procesar el descarte de la publicación que se pretende crear o del borrador.
         */
        var discard = function(){

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/discard",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        window.location = "/";

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            $('#discard').click(function(){

                var product_id = $('#ProductId').val();

                if(product_id){
                    request_parameters['data']['row_exist'] = true;
                    request_parameters['data']['id'] = product_id;
                    ajax.request(request_parameters);

                }else{
                    window.location = "/";
                }

            });

        };

        /*
         Private Method
         Descripción: destinada a desplegar en el dom los minutos transcurridos luego de guardar un borrador.
         Parámetros:
         clear: booleano
         */
        var elapsedTime = function(clear){
            if(clear){ clearInterval(this.id) }
            $("#minutesElapsed").html(0);
            this.id = self.setInterval(function(){
                var minutesElapsed = $("#minutesElapsed");
                var tmp =  minutesElapsed.html();
                var elapsed_time = parseInt(tmp)+1;
                minutesElapsed.html(elapsed_time);
            }, 60000);
            return true
        };


        /*
         Private Method
         Descripción: destinada a crear un borrador. Básicamente para definir el id de la publicación.
         Parámetros:
         now: booleano
         1) si es true: se hará la solicitud inmediatamente
         2) si es false o indefinido: se esperara por un evento para realizar la solicitud
         */
        var saveDraft = function(now){

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/save_draft",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        // {"id":"8","time":"22:04"}
                        if(response['id']){
                            $('#ProductId').attr({"value":response['id']});
                            $('#debugTime').css({"display":"block"});
                            $('#lastTimeSave').html(response['time']);

                            var clear = !!this['flag'];
                            this['flag'] = elapsedTime(clear);
                            // se prende
                            // se apaga y luego se prende
                            // se apaga y luego se prende
                        }

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            if(now){

                request_parameters['data']['id']            = $("#ProductId").val();
                request_parameters['data']['category_id']   = $("#ProductCategoryId").val();
                request_parameters['data']['title']         = $("#ProductTitle").val();
                request_parameters['data']['subtitle']      = $("#ProductSubtitle").val();
                request_parameters['data']['body']          = $("#ProductBody").val();
                request_parameters['data']['price']         = $("#ProductPrice").val();
                request_parameters['data']['quantity']      = $("#ProductQuantity").val();

                ajax.request(request_parameters);

            }else{
                $('#save-now').click(function(){

                    request_parameters['data']['id']            = $("#ProductId").val();
                    request_parameters['data']['category_id']   = $("#ProductCategoryId").val();
                    request_parameters['data']['title']         = $("#ProductTitle").val();
                    request_parameters['data']['subtitle']      = $("#ProductSubtitle").val();
                    request_parameters['data']['body']          = $("#ProductBody").val();
                    request_parameters['data']['price']         = $("#ProductPrice").val();
                    request_parameters['data']['quantity']      = $("#ProductQuantity").val();

                    ajax.request(request_parameters);

                });
            }
            return false;
        };

        /*
         Private Method
         Descripción: destinada a crear una nueva publicación, la clase requiere dos objetos para ser procesada.
             1) request_parameters: procesado luego de completar el proceso de validación
             2) newProductValidateObj: requerido para procesar la validación.
         Parámetros:
             a) id del formulario
             b) objeto con los parámetros para validar la data suministrada.
         */
        var newProduct = function(){

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/add_new",
                "data":{},
                "form":{
                    "id":"ProductAddForm",
                    "inputs":[
                        {'id':'ProductId',          'name':'id'},
                        {'id':'ProductCategoryId',  'name':'category_id'},
                        {'id':'ProductTitle',       'name':'title'},
                        {'id':'ProductBody',        'name':'body'},
                        {'id':'ProductPrice',       'name':'price'},
                        {'id':'ProductQuantity',    'name':'quantity'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){
                        notification = base.ajaxRequestNotification("beforeSend");
                    },
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/";
                        }

                        /*
                         Luego de guardar un producto con éxito
                         {"result":true,"product_id":"15","product_title":"sa"}
                         */

                        if(response['result']){
                            var slug = str_replace((response['product_title'].toLowerCase().trim()),' ','_');
                            window.location = '/producto/'+response['product_id']+'/'+slug+'.html';
                        }else{
                            window.location = "/";
                        }

                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(response){}
                }
            };

            // validación:
            var newProductValidateObj = {
                "submitHandler": function(form){

                    /*
                     El id debe ser definido al abrir el modal, porque al cargar multiples imágenes, el código se ejecuta rápidamente, con lo que si son 10 imágenes las 3-4 primera informaran que el id no existe, por lo tanto
                     se creara cuatro product.id., evitamos esto al abrir el modal automáticamente solicitamos crear un borrador para definir el product.id con el cual serán guardadas las multiples imágenes.
                     */

                    /*
                     Descripción: función destinada a establecer un efecto visual de requerido sobre la sección dispuesta para cargar imágenes.
                     */
                    var start_upload = function(){
                        var start_upload = $("#start-upload");

                        start_upload.parent().css({
                            "background-color":"#FFD1D1",
                            "border":"1px solid red"
                        });
                        start_upload.one("click",function(){
                            $("#start-upload").parent().css({
                                "background-color":"white",
                                "border":"1px solid #CCC"
                            });
                        });
                    };

                    if($('#ProductId').val()){
                        if($("#product_thumbnails").find("a").length){
                            /* luz verde para realizar solicitud ajax
                             ********************************/
                            ajax.request(request_parameters);
                        }else{
                            /* Se invita en cargar imágenes
                             ******************************/
                            start_upload();
                        }
                    }else{
                        /* Se invita a cargar imágenes
                         ******************************/
                        start_upload();
                    }

                },
                "rules":{
                    "ProductTitle":{
                        "required":true,
                        "maxlength":200
                    },
                    "ProductBody":{
                        "required":true
                    },
                    "ProductPrice":{
                        "required":true,
                        "number": true,
                        "min":0
                    },
                    "ProductQuantity":{
                        "required":true,
                        "digits": true,
                        "min":1
                    }
                },
                "messages":{
                    "ProductTitle":{
                        "required":"El campo titulo es obligatorio.",
                        "maxlength":"El titulo no debe tener mas de 200 caracteres."
                    },
                    "ProductBody":{
                        "required":"El campo descripción es obligatorio."
                    },
                    "ProductPrice":{
                        "required":"El campo precio es obligatorio.",
                        "number":"Solo un numero, entero o racional separado por un punto.",
                        "min":"El precio debe ser igual o mayor a 0."
                    },
                    "ProductQuantity":{
                        "required":"El campo cantidad es obligatorio.",
                        "digits":"Solo números enteros positivos.",
                        "min":"La cantidad debe ser igual o mayor a 1."
                    }
                }
            };

            validate.form("ProductAddForm",newProductValidateObj);
        };

        /*
         Private Method
         Descripción: destinada inhabilitar miniaturas del producto.
         */
        var disableThumbnails = function(){
            $("#product_thumbnails").find(".disable-this-product-thumbnail").each(function(){
                $(this).off("click");
                $(this).click(function(){
                    var pure_json_obj   = $(this).parent().children().last().html();
                    var obj             = $.parseJSON(pure_json_obj);

                    var notification;

                    // proceso para inhabilitar una imagen
                    var request_parameters = {
                        "requestType":"custom",
                        "type":"post",
                        "url":"/disable_this_imagen",
                        "data":{},
                        "callbacks":{
                            "beforeSend":function(){
                                notification = base.ajaxRequestNotification("beforeSend");
                            },
                            "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                                if(response['expired_session']){
                                    window['location'] = "/entrar";
                                }

                                if(response['status']){
                                    base.ajaxRequestNotification("success",notification);
                                    $("#thumbnail-id-"+response['image_id']).remove();
                                }else{
                                    base.ajaxRequestNotification("error",notification);
                                }

                                // proceso para determinar si aun existen imágenes en la vista del producto.
                                if(!$("#product_thumbnails").find("a").length){
                                    //ocultar el elemento con id product_thumbnails
                                    $('#product_thumbnails').css({
                                        "display": "none"
                                    });
                                    //muestro start-upload
                                    $('#start-upload').css({
                                        "display": "inherit"
                                    });
                                }

                            },
                            "error":function(){
                                base.ajaxRequestNotification("error",notification);
                            },
                            "complete":function(){}
                        }
                    };

                    request_parameters['data']['image_id']      = obj['original']['id'];
                    request_parameters['data']['product_id']    = $('#ProductId').val();

                    ajax.request(request_parameters);

                });
            });
        };

        /*
         Private Method
         Descripción: para visualizar en mejor resolución una miniatura habilitada del producto.
         */
        var betterVisualizing = function(){
            $("#product_thumbnails").find(".view-this-product-thumbnail").each(function(){
                $(this).off("click");
                $(this).click(function(){

                    var pure_json_obj   = $(this).parent().children().last().html();
                    var obj             = $.parseJSON(pure_json_obj);

                    // Proceso para visualizar la imagen
                    var image_url = '/img/products/'+obj['thumbnails']['median']['name'];

                    var imageProduct =  $("#image-product");

                    imageProduct.attr({"href":image_url});
                    imageProduct.ekkoLightbox();


                });
            });
        };


        /*
         Private Method
         Descripción: función destinada a pausar una publicación activa
         Parámetros:
             id: int, el id de la publicación
         */
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
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window['location '] = "/entrar";
                        }

                        if(response['result']){
                            base.ajaxRequestNotification("success",notification);

                            $('#pause').css({
                                "display": 'none'
                            });
                            $('#activate').css({
                                "display": 'inline'
                            });

                        }else{
                            base.ajaxRequestNotification("error",notification);
                        }


                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(response){}
                }
            };

            var pause = $("#pause");

            if(pause.length){
                pause.click(function(){

                    request_parameters['data']['id'] = $("#ProductId").val();
                    ajax.request(request_parameters);

                });
            }

        };

        /*
         Private Method
         Descripción: función destinada a activar una publicación pausada
         Parámetros:
             id: int, el id de la publicación
         */
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
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window['location'] = "/entrar";
                        }

                        if(response['result']){
                            base.ajaxRequestNotification("success",notification);


                            $('#pause').css({
                                "display": "inline"
                            });
                            $('#activate').css({
                                "display": "none"
                            });

                        }else{
                            base.ajaxRequestNotification("error",notification);
                        }

                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(){}
                }
            };

            var activate = $("#activate");

            if(activate.length){
                activate.click(function(){
                    request_parameters['data']['id'] = $("#ProductId").val();
                    ajax.request(request_parameters);
                });
            }

        };



        var _delete =  function(){

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
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        window['location'] = "/";

                    },
                    "error":function(){
                        base.ajaxRequestNotification("error",notification);
                    },
                    "complete":function(response){}
                }
            };

            var deleteButton = $("#delete");

            if(deleteButton.length){
                deleteButton.on('click',function(event){
                    event.preventDefault();
                    // Activamos el modal
                    $('#delete_product_modal').modal({"backdrop":true,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
                    });
                });

                $("#delete_product").click(function(){
                    request_parameters['data']['id']        = $("#ProductId").val();
                    request_parameters['data']['session']   = true;
                    ajax.request(request_parameters);
                });
            }

        };

        /*
         Private Method
         Descripción: Destinado a procesar las imágenes cargadas que quedaron en el modal. Una vez cargadas la imágenes existe la opción de eliminarla, las imágenes que queden en el modal serán procesadas, si son eliminadas todas la imágenes el botón queda inhabilitado, por lo tanto esta lógica deja de ser procesada.
         */
        var saveThis = function(){

            $('#save-this').click(function(event){
                event.preventDefault();

                $('#uploading-pictures').modal('hide');

                var dropFilesThumbnail = $('#drop-files').find("a");

                if(dropFilesThumbnail.length > 0){

                    var images_ids = [];
                    dropFilesThumbnail.each(function(){

                        var image_pure_json_obj 	= $(this).children().last().html();
                        var image_obj				= $.parseJSON(image_pure_json_obj);

                        images_ids.push(image_obj['original']['id']);

                        // Insertar la imagen del producto en la vista
                        var product_thumbnail_element = '<a id="thumbnail-id-'+image_obj['original']['id']+'" style="overflow: hidden; width: 200px; height: 200px; float: left; margin: 5px;">'+
                            '<div style="overflow: hidden; width: 200px; height: 200px; z-index: 0; position: relative;">'+
                                '<img src="/img/products/'+image_obj['thumbnails']['small']['name']+'" class="img-thumbnail" />'+
                            '</div>'+
                            '<div class="disable-this-product-thumbnail" style="overflow: hidden; z-index: 1; margin-top:-200px; position: relative; float: right; cursor: pointer;">'+
                                '<img style="width: 24px;" src="/img/x2.png">'+
                            '</div>'+
                            '<div class="view-this-product-thumbnail" style="overflow: hidden; z-index: 1; margin-top:-120px; margin-left: 80px; position: relative;  padding-right: 2px; padding-top: 2px; width: 32px; height: 32px; cursor: pointer;">'+
                                '<img src="/img/view.png">'+
                            '</div>'+
                            '<div style="display:none;">'+image_pure_json_obj+'</div>'+
                        '</a>';

                        var productThumbnails = $('#product_thumbnails');

                        if(productThumbnails.find("a").length){
                            // console.log('cuando existen lis');

                            productThumbnails.append(product_thumbnail_element);

                        }else{
                            // console.log('cuando no existen lis')

                            //ocultar start-upload
                            $('#start-upload').css({
                                "display": 'none'
                            });

                            //insertar los lis en el carrusel
                            productThumbnails.append(product_thumbnail_element);

                            //mostrar el elemento con id product_thumbnails
                            productThumbnails.css({
                                display: 'inherit'
                            });

                            // mostrar link -continuar cargando-
                            $('#continue-upload').css({
                                "display": "inline"
                            });
                        }

                    });

                    //Habilitar las miniaturas seleccionadas.
                    var request_parameters = {
                        "requestType":"custom",
                        "type":"post",
                        "url":"/enables_this_images",
                        "data":{},
                        "callbacks":{
                            "beforeSend":function(){},
                            "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                                if(response['expired_session']){
                                    window.location = "/entrar";
                                }

                            },
                            "error":function(){},
                            "complete":function(response){}
                        }
                    };

//                    console.log('images_ids',images_ids);
                    request_parameters['data']['images_ids'] = images_ids;
                    request_parameters['data']['product_id'] =  $('#ProductId').val();
                    ajax.request(request_parameters);


                    // remover la miniaturas del modal
                    dropFilesThumbnail.each(function(){
                        $(this).remove();
                    });

                    $('#optional-selection-container').css({
                        "display": "block"
                    });

                    $('#second-files-button').css({
                        "display": "none"
                    });

                    // no permitimos guardar
                    $('#save-this').attr({"disabled":"disabled"});

                    /* inhabilitar miniaturas del producto
                     *****************************************/
                    disableThumbnails();

                    /* Visualizar en mejor resolución una miniatura habilitada del producto.
                     *****************************************/
                    betterVisualizing();

                }

            });

        };

        /*
         Private Method
         Descripción: Destinado a observar el evento de abrir el modal para cargar imágenes del producto o servicio, con el fin de establecer acciones.
         */
        var imagesEvents = function () {
            $("#start-upload").on('click',function(event){
                event.preventDefault();
                $('#uploading-pictures').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false});
            });

            $('#uploading-pictures').on('show.bs.modal', function(){
                saveDraft(true);
            });

            $("#continue-upload").on('click',function(event){
                event.preventDefault();
                $('#uploading-pictures').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false});
            });
        };

        /*
         Private Method
         Descripción:  Subir las imágenes
         */
        var fileUpload = function (){
            /*
             Type: Objeto JSON.
             Descripción: Almacena ordenadamente todas las acciones que ocurren tras los eventos que suceden en una solicitud xhr.
             */
            var file_upload_callbacks = {
                "events":{
                    "dragover":function(element){
                        $('#drop-files').css({
                            "border": '2px dashed #357AE8'
                        });
                        $('#uploading-pictures').css({
                            "border": '1px solid #357AE8'
                        });
                    },
                    "drop":function(element){
                        $('#drop-files').css({
                            "border": '2px dashed #DCDCDC'
                        });
                        $('#uploading-pictures').css({
                            "border": '1px solid rgba(0, 0, 0, 0.3)'
                        });

                    },
                    'progressEvent':{
                        'loadstart':function(){
                            //	Description					|	Times
                            //	Progress has begun. 			Once.

                            var temporary_element = '<a style="overflow: hidden;  width: 200px; height: 200px; float: left; margin: 5px;" >'+
                                '<div style="overflow: hidden; width: 200px; padding-top: 30px;" >'+
                                    '<div style="text-align: center;">'+
                                        '<img src="/img/photocamera.png" class="img-thumbnail" >'+
                                    '</div>'+
                                '</div>'+
                                '<div style="overflow: hidden; width: 200px; margin-top: 5px;" >'+
                                    '<div style="text-align: center;">'+
                                      '<span class="upload-progress"><img src="/img/loading.gif" ></span>'+
                                    '</div>'+
                                '</div>'+
                            '</a>';

                            var dropFiles = $('#drop-files');

                            if($("#optional-selection-container")){
                                $('#optional-selection-container').css({
                                    "display": "none"
                                });
                                dropFiles.append(temporary_element);
                                this.last_element_inserted = dropFiles.children().last();
                            }else{
                                dropFiles.append(temporary_element);
                                this.last_element_inserted =  dropFiles.children().last();
                            }

                            // añadir mas
                            $('#second-files-button').css({"display":"block"});

                            // permitimos guardar
                            $('#save-this').removeClass('disabled');

                        },
                        'progress':function(evt){
                            //	Description					|	Times
                            //	In progress.					Zero or more.
//                            console.log('In progress');


                            // this.last_element_inserted
                            /*
                             if (evt.lengthComputable) {
                             var percentComplete = evt.loaded / evt.total;
                             } else {
                             // console.log('Unable to compute progress information since the total size is unknown');
                             }

                             */

                            /*	 Ejemplo:
                             var progressBar = document.getElementById("p"),
                             client = new XMLHttpRequest()
                             client.open("GET", "magical-unicorns")
                             client.onprogress = function(pe) {
                             if(pe.lengthComputable) {
                             progressBar.max = pe.total
                             progressBar.value = pe.loaded
                             }
                             }
                             client.onloadend = function(pe) {
                             progressBar.value = pe.loaded
                             }
                             client.send()
                             */

                        },
                        'error':function(evt){
                            //	Description					|	Times
                            // 	Progression failed.				Zero or more.
//                            console.log("Progression failed.");

                        },
                        'abort':function(evt){
                            //	Description					|	Times
                            //	Progression is terminated.		Zero or more.
//                            console.log("Progression is terminated.");
                        },
                        'load':function(evt){
                            //	Description					|	Times
                            //  Progression is successful.		Zero or more.
//                            console.log('Progression is successful.');
                        },
                        'loadend':function(evt){
                            //	Description					|	Times
                            // 	Progress has stopped.			Once.
//                            console.log('Progress has stopped.');
                            //	console.log(this.last_element_inserted);


                            var a = this.responseText;

                            var obj = $.parseJSON(a);
                            if(obj['expired_session']){
                                window['location'] = "/entrar";
                            }

                            /*
                             {
                             "original":{
                             "name":"Capturadepantallade2013-01-0619203433.png",
                             "id":"78"
                             },
                             "thumbnails":{
                             "large":{
                             "name":"Capturadepantallade2013-01-0619203430.png",
                             "size":"1920x1080",
                             "id":"79"
                             },
                             "median":{
                             "name":"Capturadepantallade2013-01-0619203431.png",
                             "size":"900x900",
                             "id":"80"
                             },
                             "small":{
                             "name":"Capturadepantallade2013-01-0619203432.png",
                             "size":"400x400px",
                             "id":"81"
                             }
                             }
                             }
                             */

                            var myTemplate = 	'<div style="overflow: hidden; width: 200px; height: 200px; z-index: 0; position: relative;" >'+
                                    '<div style="text-align: center;">'+
                                        '<img src="/img/products/'+obj['thumbnails']['small']['name']+'" class="img-thumbnail" >'+
                                    '</div>'+
                                '</div>'+
                                '<div class="delete-this-image" style="overflow: hidden; z-index: 1; margin-top:-200px; position: relative; float: right; cursor: pointer;">'+
                                    '<img style="width: 24px;" src="/img/x2.png">'+
                                '</div>'+
                                '<div style="display:none">'+a+'</div>';

                            $(this.last_element_inserted).html(myTemplate);

                            $('#save-this').attr({"disabled":false});

                            $(this.last_element_inserted).find('div.delete-this-image').click(function(){
                                $(this).parent().remove();
                                exist_thumbnails();
                            });

                            // ¿siguen existiendo miniaturas luego de borrar una? no, entonces se normaliza la vista.
                            var exist_thumbnails = function(){
                                if(!$('#drop-files').find("a").length){
                                    $('#optional-selection-container').css({
                                        "display": "block"
                                    });
                                    $('#second-files-button').css({
                                        "display": "none"
                                    });
                                    // no permitimos guardar
                                    $('#save-this').attr({"disabled":"disabled"});
                                }
                            }

                        }
                    }
                }
            };

            /*
             Descripción: destinada a crear un objeto FormData el cual permitirá tener acceso a los archivos que el usuario selecciona, para luego hacer una solicitud xhr.
             Parámetros:
             callbacks: objeto json.
             */
            var file_upload =  function(callbacks){

                var file_input_element_ids  = ["first-files","second-files"];
                var drop_element_id			= "drop-files";

                $(file_input_element_ids).each(function(index, value){
                    $('#'+value).change(function(){

                        for(var i=0; i < this.files.length; i++){
                            //console.log(this.files[i]);

                            // start código casi idéntico: este código es en su mayoría el mismo para el evento soltar o drop
                            var form = new FormData();
                            form.append("product_id", $('#ProductId').val());
                            form.append("image", this.files[i]);

                            var xhr = new XMLHttpRequest();

                            // Interface ProgressEvent																	Description							|	Times
                            xhr.addEventListener("loadstart", 	callbacks.events.progressEvent.loadstart,	false);		//	Progress has begun. 				Once.
                            xhr.addEventListener("progress", 	callbacks.events.progressEvent.progress, 	false); 	// 	In progress.						Zero or more.
                            xhr.addEventListener("error", 		callbacks.events.progressEvent.error, 		false);   	// 	Progression failed.					Zero or more.
                            xhr.addEventListener("abort", 		callbacks.events.progressEvent.abort, 		false); 	// 	Progression is terminated.			Zero or more.
                            xhr.addEventListener("load", 		callbacks.events.progressEvent.load, 		false);  	// 	Progression is successful.			Zero or more.
                            xhr.addEventListener("loadend", 	callbacks.events.progressEvent.loadend,		false);  	// 	Progress has stopped.				Once.

                            xhr.open("post", "/image_add", true);
                            xhr.send(form);
                            // end código idéntico.

                        }

                    });
                });

                var dropElementId = $("#"+drop_element_id);

                dropElementId.on('dragover',function(event){
                    event.preventDefault();
                    callbacks.events.dragover();
                });

                dropElementId.on('drop',function(event){
                    event.preventDefault();

                    callbacks.events.dragover();

                    var length = event['originalEvent']['dataTransfer']['files']['length'];

                    for (var i = 0; i < length; i++) {
                        var file = event['dataTransfer']['files'][i];

                        // start código casi idéntico: este código es en su mayoría el mismo para el evento soltar o drop
                        var form = new FormData();
                        form.append("product_id", $('#ProductId').val());
                        form.append("image", file);

                        var xhr = new XMLHttpRequest();

                        // Interface ProgressEvent																	Description							|	Times
                        xhr.addEventListener("loadstart", 	callbacks.events.progressEvent.loadstart,	false);		//	Progress has begun. 				Once.
                        xhr.addEventListener("progress", 	callbacks.events.progressEvent.progress, 	false); 	// 	In progress.						Zero or more.
                        xhr.addEventListener("error", 		callbacks.events.progressEvent.error, 		false);   	// 	Progression failed.					Zero or more.
                        xhr.addEventListener("abort", 		callbacks.events.progressEvent.abort, 		false); 	// 	Progression is terminated.			Zero or more.
                        xhr.addEventListener("load", 		callbacks.events.progressEvent.load, 		false);  	// 	Progression is successful.			Zero or more.
                        xhr.addEventListener("loadend", 	callbacks.events.progressEvent.loadend,		false);  	// 	Progress has stopped.				Once.

                        xhr.open("post", "/image_add", true);
                        xhr.send(form);
                        // end código idéntico.

                    }

                });

            };

            file_upload(file_upload_callbacks);

        };

        //Public Method
        product.init = function(){
            // Se inicializa el formulario
            newProduct();
            // Se inicializa las categorías
            observeTheCategories();
            // La transición entre las categorías y el resto del formulario.
            transition();
            // Para crear el borrador
            saveDraft(false);
            // En caso de que se quiera descartar la publicación
            discard();
            // Se inicializa el WYSIWYG
            initRedactor();

            if($('#product_thumbnails').find("a").length){
                /* inhabilitar miniaturas del producto
                 *****************************************/
                disableThumbnails();

                /* Visualizar en mejor resolución una miniatura habilitada del producto
                 ************************************************************************/
                betterVisualizing();
            }

            activate();

            pause();

            _delete();

            // procesa las imágenes cargadas que quedaron en el modal
            saveThis();

            // observar el evento de abrir el modal para cargar imágenes del producto o servicio
            imagesEvents();

            // Subir las imágenes
            fileUpload();

        };


    }( window.product = window.product || {}, jQuery ));

    product.init();


});