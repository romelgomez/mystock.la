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

            var selectButton = function(instance,file){

                var notification;

                // proceso para establecer un banner
                var request_parameters = {
                    "requestType":"custom",
                    "type":"post",
                    "url":"/select-banner",
                    "data":{},
                    "callbacks":{
                        "beforeSend":function(){
                            notification = ajax.notification("beforeSend");
                        },
                        "success":function(response){

                            if(response['expired_session']){
                                window['location'] = "/entrar";
                            }

                            if(response['status']){
                                ajax.notification("success",notification);

                                $("#reload-the-page").fadeIn().on("click",function(){

									var url = $(location).attr('href');
									$.post(
											'https://graph.facebook.com',
											{
												id: url,
												scrape: true
											},
											function(response){
												//console.log(response);
											}
									);

                                    window.location.reload(true);
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


                var selectButton = Dropzone.createElement('<a class="dz-select" style="cursor:pointer" >Select</a>');

                selectButton.addEventListener("click", function(e) {
                    // Make sure the button click doesn't submit the form:
                    e.preventDefault();
                    e.stopPropagation();

                    var banner = '';

                    if(file['xhr'] !== undefined){
//                        console.log('Es una imagen recién cargada al servidor que se quiere seleccionar');

                        var obj = JSON.parse(file['xhr']['response']);

                        banner = obj['facebook']['name'];
                        $('#banner').css('background-image', 'url(' + '/resources/app/img/banners/'+ banner + ')');

                        request_parameters['data']      = obj;
                        ajax.request(request_parameters);

                    }else{
                        if(file['id']  !== undefined){
//                            console.log('Es una imagen que esta en servidor');

                            banner = file['obj']['facebook']['name'];
                            $('#banner').css('background-image', 'url(' + '/resources/app/img/banners/'+ banner + ')');

                            request_parameters['data']    = file['obj'];
                            ajax.request(request_parameters);

                        }else{
//                            console.log('Es una imagen en cola que fue eliminada')
                        }
                    }

                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(selectButton);
            };

            var removeButton = function(instance,file){

                var notification;

                // proceso para inhabilitar una imagen
                var request_parameters = {
                    "requestType":"custom",
                    "type":"post",
                    "url":"/delete-banner",
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


                var removeButton = Dropzone.createElement('<a class="dz-delete" style="cursor:pointer;" >Delete</a>');
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

                        ajax.request(request_parameters);

                    }else{
                        if(file['id']  !== undefined){
//                            console.log('Es una imagen que esta en servidor');

                            request_parameters['data']['image_id']      = file['id'];

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
                url: "/add-banner",
                previewsContainer: "#previews",  // Define the container to display the previews
                clickable: ".clickable",         // Define the element that should be used as click trigger to select files.
                paramName: "image",              // The name that will be used to transfer the file
                maxFilesize: 10,                 // MB
                acceptedFiles: 'image/*',
                autoQueue: false,
                previewTemplate: layout,
                init: function() {

                    var myDropzone = this; // closure

                    // Upload all files
                    $("#upload-all").on("click", function() {
//                        console.log("clicked");

                        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED)); // Tell Dropzone to process all queued files.
                        $("#upload-all").fadeOut();

                    });

                    // Banners in database
                    var images = JSON.parse(utility.removeCommentTag($("#banners").html()));

                    /*
                    [
                        {
                            "small":{
                                "id":"54545857-a46c-429f-af96-44fc7f000007",
                                "name":"cbac9717-3859-43a9-b845-f1c578c3213c.jpg"
                            },
                            "facebook":{
                                "id":"54545857-83a0-4e4b-9e28-44fc7f000007",
                                "name":"1c217e5e-6302-4591-bc01-b92142ff6e22.jpg"
                            }
                        },
                        {
                            "small":{
                                "id":"54545e49-ee90-407e-9e44-46d27f000007",
                                "name":"aa36701e-7188-4636-ad6d-7886a085053e.jpg"
                            },
                            "facebook":{
                                "id":"54545e49-929c-4051-b149-46d27f000007",
                                "name":"ee8b1e37-3588-4e86-bf64-6c61b8f1b25b.jpg"
                            }
                        },
                    ]
                    */

                    if(images.length > 0){
                        $("#first-files").hide();
                        $("#continue-upload").show();

//                        console.log(images);

                        $(images).each(function(index,obj){
//                                console.log(obj);
                            // Create the mock file:
                            var mockFile = { id: obj['small']['id'], name: obj['small']['name'], obj:obj};

                            // Call the default addedfile event handler
                            myDropzone.emit("addedfile", mockFile);

                            // And optionally show the thumbnail of the file:
                            myDropzone.emit("thumbnail", mockFile, "/resources/app/img/banners/"+obj['small']['name']);

                            removeButton(myDropzone,mockFile);
                            $(mockFile.previewElement).addClass('dz-success'); // .setAttribute("class",".dz-success");

                            selectButton(myDropzone,mockFile);

                        });
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
                    });

                    // Success
                    myDropzone.on("success", function(file, xhr){
//                        console.log(file);
//                        console.log(xhr);
//                        file.data = xhr;

                        selectButton(myDropzone,file);

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
            fileUpload();

        };

    }( window.banners = window.banners || {}, jQuery ));


    banners.main();

});
