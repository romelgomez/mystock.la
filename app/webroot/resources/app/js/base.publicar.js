$(document).ready(function(){

    (function( product, $, undefined) {





        var initRedactor = function(){
            //$('#ProductBody')['redactor']({
            //    lang: 'es'
            //});
			$('#ProductBody')['redactor']();
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
                request_parameters['data']['title']         = $("#ProductTitle").val().trim();
                request_parameters['data']['body']          = $("#ProductBody").val();
                request_parameters['data']['price']         = $("#ProductPrice").val();
                request_parameters['data']['quantity']      = $("#ProductQuantity").val();

                ajax.request(request_parameters);

            }else{
                $('#save-now').click(function(){

                    request_parameters['data']['id']            = $("#ProductId").val();
                    request_parameters['data']['title']         = $("#ProductTitle").val().trim();
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
                "requestType":"custom",
                "type":"post",
                "url":"/add_new",
                "data":{},
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
                            var slug = utility.stringReplace((response['product_title'].toLowerCase().trim()),' ','-');
                            slug = utility.stringReplace(slug,'/','-');
                            window.location = '/product/'+response['product_id']+'/'+slug+'.html';
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
                        var start_upload = $("#previews");

                        start_upload.css({
                            "background-color":"#FFD1D1",
                            "border":"1px solid red"
                        });

                        $("#first-files").one("click",function(){
                            $("#previews").css({
                                "background-color":"#f5f5f5",
                                "border":"1px solid #CCC"
                            });
                        });

                        $("#upload-all").one("click",function(){
                            $("#previews").css({
                                "background-color":"#f5f5f5",
                                "border":"1px solid #CCC"
                            });
                        });
                    };

                    if($('#ProductId').val()){
                        if($("#previews").find(".dz-success").length > 0){
                            /* luz verde para realizar solicitud ajax
                             ********************************/

                            // Antes primero se verifica que hay contenido. La validación corriente no funciona con el WYSIWYG.
                            var productBody = $('#ProductBody');
                            if(productBody.val() == ''){
                                var productBodyHelpBlock = productBody.parents('div.form-group').find('span.help-block')[0];
                                $(productBodyHelpBlock).show();
                            }else{

                                request_parameters['data']['id']        = $("#ProductId").val();
                                request_parameters['data']['title']     = $("#ProductTitle").val().trim();
                                request_parameters['data']['body']      = $("#ProductBody").val();
                                request_parameters['data']['price']     = $("#ProductPrice").val();
                                request_parameters['data']['quantity']  = $("#ProductQuantity").val();

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
                        "maxlength":10,
                        "min":0
                    },
                    "ProductQuantity":{
                        "required":true,
                        "digits": true,
                        "maxlength":10,
                        "min":1
                    }
                },
                "messages":{
                    "ProductTitle":{
                        "required":"Title is required.",
                        "maxlength":"The title should not be longer than 200 characters."
                    },
                    "ProductBody":{
                        "required":"Description is required."
                    },
                    "ProductPrice":{
                        "required":"Price is required.",
                        "number":"Only a number, integer or rational separated by a dot.",
                        "maxlength":"The price should not have more than 10 digits",
                        "min":"The price must be equal or greater than 0."
                    },
                    "ProductQuantity":{
                        "required":"Quantity available is required.",
                        "digits":"Only positive integers.",
                        "min":"The quantity must be equal to or greater than 1.",
                        "number":"Please enter a valid number.",
                        "maxlength":"Available quantity expressed should not be longer than 10 digits"
                    }
                }
            };

            validate.form("ProductAddForm",newProductValidateObj);
        };


        /*
         Private Method
         Descripción: para visualizar en mejor resolución una miniatura habilitada del producto.
         */
        //var betterVisualizing = function(){
        //    $("#product_thumbnails").find(".view-this-product-thumbnail").each(function(){
        //        $(this).off("click");
        //        $(this).click(function(){
        //
        //            var pure_json_obj   = $(this).parent().children().last().html();
        //            var obj             = $.parseJSON(pure_json_obj);
        //
        //            // Proceso para visualizar la imagen
        //            var image_url = '/resources/app/img/products/'+obj['thumbnails']['median']['name'];
        //
        //            var imageProduct =  $("#image-product");
        //
        //            imageProduct.attr({"href":image_url});
        //            imageProduct.ekkoLightbox();
        //
        //
        //        });
        //    });
        //};


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


                var removeButton = Dropzone.createElement('<a class="dz-delete" style="cursor:pointer" >Delete</a>');
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


					var productId = $('#ProductId');

                    // If you want to the delete the file on the server as well,
                    // you can do the AJAX request here.
                    if(file['xhr'] !== undefined){
//                        console.log('Es una imagen recién cargada al servidor que se quiere eliminar');

                        var obj = JSON.parse(file['xhr']['response']);

                        request_parameters['data']['image_id']      = obj['small']['id'];
                        request_parameters['data']['product_id']    = productId.val();

                        ajax.request(request_parameters);

                    }else{
                        if(file['id']  !== undefined){
//                            console.log('Es una imagen que esta en servidor');

                            request_parameters['data']['image_id']      = file['id'];
                            request_parameters['data']['product_id']    = productId.val();

                            ajax.request(request_parameters);

                        }else{
//                            console.log('Es una imagen en cola que fue eliminada')
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
                            $("#upload-all").hide();
                        };

                        saveDraft(true,ifSuccess);

                    });

                    // Para incluir las imágenes que ya estén cargadas.
                    var pathname = $(location).attr('href');
                    var url = $.url(pathname);
                    var split_segments = url.attr('directory').split('/');
                    if(split_segments[1] == 'edit' || split_segments[1] == 'edit-draft' ){
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
//                        console.log("Added file.");

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
