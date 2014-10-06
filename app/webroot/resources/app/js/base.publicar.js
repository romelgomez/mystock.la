$(document).ready(function(){

    (function( product, $, undefined) {

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



                var tmp1							= utility.randomNumber(1,9999);
                var tmp2 							= utility.randomNumber(10000,20000);
                var random_category_id_container	= 'dependent-options-'+utility.randomNumber(tmp1,tmp2);


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

                ul = '<div id="category-selected" class="category-selected"><div style="margin-top: 20px;" ><div style="text-align: center; padding: 10px;"><span class="category-selected-text">Categoría seleccionada!</span> <br><img src="/resources/app/img/ok.png" alt="Gracias" /></div></div></div>';
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
                    var category_id                 = utility.stringReplace(path_category_id,'path-category-id-','');	// 142

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
                    var category_id = utility.stringReplace(element_id,'category-id-','');    // 142

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

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/discard",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        window.location = "/";

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){}
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
        var saveDraft = function(now,ifSuccess){

            var notification;

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/save_draft",
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

                            if ( ifSuccess !== undefined ) {
                                ifSuccess();
                                ajax.notification("complete",notification);  // cuando sea llamada desde fileUpload()
                            }else{
                                ajax.notification("success",notification);   // cuando sea solicitado por el usuario
                            }

                        }else{
                            ajax.notification("error",notification);
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
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
                        notification = ajax.notification("beforeSend");
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
                            var slug = utility.stringReplace((response['product_title'].toLowerCase().trim()),' ','_');
                            window.location = '/producto/'+response['product_id']+'/'+slug+'.html';
                        }else{
                            window.location = "/";
                        }


                    },
                    "error":function(){
                        ajax.notification("error",notification);
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
                        if($("#product_thumbnails").find("a").length > 0){
                            /* luz verde para realizar solicitud ajax
                             ********************************/

                            // Antes primero se verifica que hay contenido. La validación corriente no funciona con el WYSIWYG.
                            var productBody = $('#ProductBody');
                            if(productBody.val() == ''){
                                var productBodyHelpBlock = productBody.parents('div.form-group').find('span.help-block')[0];
                                $(productBodyHelpBlock).show();
                            }else{
                                ajax.request(request_parameters);
                            }

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
                        "required":"El campo: Titulo, es obligatorio.",
                        "maxlength":"El titulo no debe tener mas de 200 caracteres."
                    },
                    "ProductBody":{
                        "required":"El campo: Descripción, es obligatorio."
                    },
                    "ProductPrice":{
                        "required":"El campo: Precio, es obligatorio.",
                        "number":"Solo un numero, entero o racional separado por un punto.",
                        "min":"El precio debe ser igual o mayor a 0."
                    },
                    "ProductQuantity":{
                        "required":"El campo: Cantidad disponible, es obligatorio.",
                        "digits":"Solo números enteros positivos.",
                        "min":"La cantidad debe ser igual o mayor a 1.",
                        "number":"Por favor, introduzca un número válido."
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
                                notification = ajax.notification("beforeSend");
                            },
                            "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                                if(response['expired_session']){
                                    window['location'] = "/entrar";
                                }

                                if(response['status']){
                                    ajax.notification("success",notification);
                                    $("#thumbnail-id-"+response['image_id']).remove();
                                }else{
                                    ajax.notification("error",notification);
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
                                ajax.notification("error",notification);
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
                    var image_url = '/resources/app/img/products/'+obj['thumbnails']['median']['name'];

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
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window['location '] = "/entrar";
                        }

                        if(response['result']){
                            ajax.notification("success",notification);

                            $('#pause').css({
                                "display": 'none'
                            });
                            $('#activate').css({
                                "display": 'inline'
                            });

                        }else{
                            ajax.notification("error",notification);
                        }


                    },
                    "error":function(){
                        ajax.notification("error",notification);
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
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window['location'] = "/entrar";
                        }

                        if(response['result']){
                            ajax.notification("success",notification);


                            $('#pause').css({
                                "display": "inline"
                            });
                            $('#activate').css({
                                "display": "none"
                            });

                        }else{
                            ajax.notification("error",notification);
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
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
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        window['location'] = "/";

                    },
                    "error":function(){
                        ajax.notification("error",notification);
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



        var  fileUpload = function(){

            var layout = '<div class="dz-preview dz-file-preview">'+
                '<div class="dz-details">'+
                    '<img data-dz-thumbnail />'+
                '</div>'+
                '<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>'+
                '<div class="dz-success-mark"><span>✔</span></div>'+
                '<div class="dz-error-mark"><span>✘</span></div>'+
                '<div class="dz-error-message"><span data-dz-errormessage></span></div>'+
            '</div>';

            var removeButton = function(instance,file){

                var notification;

                // proceso para inhabilitar una imagen
                var request_parameters = {
                    "requestType":"custom",
                    "type":"post",
                    "url":"/disable_this_imagen",
                    "data":{},
                    "callbacks":{
                        "beforeSend":function(){
                            notification = ajax.notification("beforeSend");
                        },
                        "success":function(response){
//                        $('#debug').text(JSON.stringify(response));

                            if(response['expired_session']){
                                window['location'] = "/entrar";
                            }

                            if(response['status']){
                                ajax.notification("success",notification);
                            }else{
                                ajax.notification("error",notification);
                            }

                        },
                        "error":function(){
                            ajax.notification("error",notification);
                        },
                        "complete":function(){}
                    }
                };


                var removeButton = Dropzone.createElement('<a class="dz-remove" style="cursor:pointer" >Eliminar</a>');
                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                    // Make sure the button click doesn't submit the form:
                    e.preventDefault();
                    e.stopPropagation();

                    // Remove the file preview.
                    instance.removeFile(file);

                    // en el contendor #previews determinamos si existen elementos con la clases div.dz-preview, que corresponden a una imagen cargada, si existen, nada pasa, si no existen, se oculta #continue-upload y se muestra #first-files, ambos ID corresponde a botones que inicializan la carga de imágenes.
                    var previews = $("#previews").find("div.dz-preview");
                    if(previews.length == 0 ){
                        $("#first-files").show();
                        $("#continue-upload").hide();
                        $("#upload-all").hide();
                    }

                    // If you want to the delete the file on the server as well,
                    // you can do the AJAX request here.
                    if(file['xhr'] !== undefined){
                        console.log('Es una imagen recién cargada al servidor que se quiere eliminar');

                        var obj = JSON.parse(file['xhr']['response']);

                        request_parameters['data']['image_id']      = obj['small']['id'];
                        request_parameters['data']['product_id']    = $('#ProductId').val();

                        ajax.request(request_parameters);

                    }else{
                        if(file['id']  !== undefined){
                            console.log('Es una imagen que esta en servidor');

                            request_parameters['data']['image_id']      = file['id'];
                            request_parameters['data']['product_id']    = $('#ProductId').val();

                            ajax.request(request_parameters);

                        }else{
                            console.log('Es una imagen en cola que fue eliminada')
                        }
                    }

                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            };


            $(document.body).dropzone({
                url: "/image_add",
                previewsContainer: "#previews",  // Define the container to display the previews
                clickable: ".clickable",         // Define the element that should be used as click trigger to select files.
                paramName: "image",              // The name that will be used to transfer the file
                maxFilesize: 10,                 // MB
                acceptedFiles: 'image/*',
                autoQueue: false,
                previewTemplate: layout,
                init: function() {

                    var myDropzone = this; // closure

                    $("#upload-all").on("click", function() {
//                        console.log("clicked");

                        var ifSuccess = function(){
//                            console.log($('#ProductId').val());
                            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED)); // Tell Dropzone to process all queued files.
                        };

                        saveDraft(true,ifSuccess);

                    });

                    // Para incluir las imágenes que ya estén cargadas.
                    var pathname = $(location).attr('href');
                    var url = $.url(pathname);
                    var split_segments = url.attr('directory').split('/');
                    if(split_segments[1] == 'editar' || split_segments[1] == 'editar_borrador' ){
                        var images = JSON.parse(utility.removeCommentTag($("#images").html()));
                        if(images.length > 0){
                            $("#first-files").hide();
                            $("#continue-upload").show();

                            $(images).each(function(index,obj){
//                                console.log(obj);
                                // Create the mock file:
                                var mockFile = { id: obj['id'], name: obj['name']};

                                // Call the default addedfile event handler
                                myDropzone.emit("addedfile", mockFile);

                                // And optionally show the thumbnail of the file:
                                myDropzone.emit("thumbnail", mockFile, "/resources/app/img/products/"+obj['name']);

                                removeButton(myDropzone,mockFile);

                                $(mockFile.previewElement).addClass('dz-success'); // .setAttribute("class",".dz-success");

                            });
                        }
                    }



                    // Added file
                    myDropzone.on("addedfile", function(file) {
                        console.log("Added file.");

                        $("#first-files").hide();
                        $("#continue-upload").show();
                        $("#upload-all").show();

                        removeButton(myDropzone,file);

                    });

                    // Sending
                    myDropzone.on("sending", function(file, xhr, formData) {
                        formData.append("product_id", $('#ProductId').val());
                    });

                    // Success
                    myDropzone.on("success", function(file, xhr){
//                        console.log(file);
//                        console.log(xhr);
//                        file.data = xhr;
                    });

                    // Error
                    myDropzone.on("error",function(file,errorMessage,xhr){
                        // ...
                    });

                }
            });
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

//            if($('#product_thumbnails').find("a").length){
//                /* inhabilitar miniaturas del producto
//                 *****************************************/
//                disableThumbnails();
//
//                /* Visualizar en mejor resolución una miniatura habilitada del producto
//                 ************************************************************************/
//                betterVisualizing();
//            }

            activate();

            pause();

            _delete();

            // IMAGES
            // procesa las imágenes cargadas que quedaron en el modal
//            saveThis();

            // observar el evento de abrir el modal para cargar imágenes del producto o servicio
//            imagesEvents();

            // Subir las imágenes
            fileUpload();


        };


    }( window.product = window.product || {}, jQuery ));

    product.init();


});