$(document).ready(function() {

    (function( banners, $, undefined ) {

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
//                        console.log('Es una imagen recién cargada al servidor que se quiere eliminar');

                        var obj = JSON.parse(file['xhr']['response']);

                        request_parameters['data']['image_id']      = obj['small']['id'];
                        request_parameters['data']['product_id']    = $('#ProductId').val();

                        ajax.request(request_parameters);

                    }else{
                        if(file['id']  !== undefined){
//                            console.log('Es una imagen que esta en servidor');

                            request_parameters['data']['image_id']      = file['id'];
                            request_parameters['data']['product_id']    = $('#ProductId').val();

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


        //Private Method
        function changeBanner() {

            $("#change-banner").on('click',function(event){
                event.preventDefault();
                $('#change-banner-modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
//                    validate.removeValidationStates('UserForm');
                });
            });

        }

        //Public Method
        banners.main = function() {
            changeBanner();


        };

    }( window.banners = window.banners || {}, jQuery ));


    banners.main();

});