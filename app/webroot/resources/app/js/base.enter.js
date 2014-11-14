/*
    login Formulario

     form_id    #LoginForm
     url        /login

     inputs
        LoginEmail
        LoginPassword
 */

$(document).ready(function(){
    (function( user, $) {

        /*
         Private Method
         Descripción:  Recuperar una cuenta
         */
        var recoverAcount = function(){

            $("#recover").on('click',function(event){
                event.preventDefault();
                $('#recoverModal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                    validate.removeValidationStates('UserForm');
                });
            });

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/recover_account",
                "data":{},
                "form":{
                    "id":"UserForm",
                    "inputs":[
                        {'id':'Email', 'name':'email'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        var userForm = $("#UserForm");

                        if(response['Send']){
                            ajax.notification("success",notification);

                            userForm.find(".alert-success").fadeIn();
                            validate.removeValidationStates('UserForm');

                            setTimeout(function(){
                                $("#UserForm").find(".alert-success").fadeOut();
                            },2000);
                        }else{
                            ajax.notification("error",notification);

                            userForm.find(".alert-danger").fadeIn();
                            userForm.find(".modal-body").find(".form-group").hide();
                            userForm.find(".modal-footer").hide();

                            setTimeout(function(){
                                $('#recoverModal').modal('hide');
                                validate.removeValidationStates('UserForm');

                                var userForm = $("#UserForm");
                                userForm.find(".alert-danger").fadeOut();
                                userForm.find(".modal-body").find(".form-group").show();
                                userForm.find(".modal-footer").show();

                            },3000);
                        }


                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(response){}
                }
            };

            // validación:
            var validateObj = {
                "submitHandler": function(){
                    ajax.request(request_parameters);
                },
                "rules":{
                    "Email":{
                        "required":true,
                        "email": true,
                        "remote": {
                            "url": "/check_email",
                            "type": "post",
                            "data": {
                                "UserEmail": function() {
                                    return $("#Email").val();
                                }
                            }
                        },
                        "maxlength":30
                    }
                },
                "messages":{
                    "Email":{
                        "required":"El campo email es obligatorio.",
                        "email":"Debe proporcionar un correo valido.",
                        "remote":"No existe una cuenta asociada a tal dirección de correo electrónico. Por favor, verifique e inténtelo de nuevo.",
                        "maxlength":"El correo no debe tener mas de 30 caracteres."
                    }
                }
            };

            validate.form("UserForm",validateObj);

        };

        /*
         Private Method
         Descripción:  Nuevo Usuario
         */
        var newUser = function(){

            $("#newUser").on('click',function(event){
                event.preventDefault();
                $('#newUserModal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                    validate.removeValidationStates('UserAddForm');
                });
            });

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/new_user",
                "data":{},
                "form":{
                    "id":"UserAddForm",
                    "inputs":[
                        {'id':'UserName',          'name':'name'},
                        {'id':'UserEmail',       'name':'email'},
                        {'id':'UserPassword',       'name':'password'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        var userAddForm = $("#UserAddForm");

                        if(response['Add']){
                            ajax.notification("success",notification);

                            userAddForm.find(".alert-success").fadeIn();
                            validate.removeValidationStates('UserAddForm');

                            setTimeout(function(){
                                $("#UserAddForm").find(".alert-success").fadeOut();
                            },2000);
                        }else{
                            ajax.notification("error",notification);

                            userAddForm.find(".alert-danger").fadeIn();
                            userAddForm.find(".modal-body").find(".form-group").hide();
                            userAddForm.find(".modal-footer").hide();

                            setTimeout(function(){
                                $('#newUserModal').modal('hide');
                                validate.removeValidationStates('UserAddForm');

                                var userAddForm = $("#UserAddForm");
                                userAddForm.find(".alert-danger").fadeOut();
                                userAddForm.find(".modal-body").find(".form-group").show();
                                userAddForm.find(".modal-footer").show();

                            },3000);
                        }

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(response){}
                }
            };

            // validación:
            var validateObj = {
                "submitHandler": function(){
                    ajax.request(request_parameters);
                },
                "rules":{
                    "UserName":{
                        "required":true,
                        "lettersonly":true,
                        "minlength": 3,
                        "maxlength":20
                    },
                    "UserEmail":{
                        "required":true,
                        "email": true,
                        "remote": {
                            "url": "/check_email",
                            "type": "post",
                            "data": {
                                "inverse_result":true,
                                "UserEmail":function(){
                                    return $("#UserEmail").val();
                                }
                            }
                        },
                        "maxlength":30
                    },
                    "UserPassword":{
                        "required":true,
                        "rangelength": [7, 21],
                        "notEqualToName":"UserName",
                        "notEqualToEmail":"UserEmail"
                    },
                    "UserPasswordAgain":{
                        "required":true,
                        "rangelength": [7, 21],
                        "notEqualToName":"UserName",
                        "notEqualToEmail":"UserEmail",
                        "equalTo":"#UserPassword"
                    },
                    "terms-of-service":{
                        "required":true
                    }
                },
                "messages":{
                    "UserName":{
                        "required":"The name is required.",
                        "lettersonly":"The name must have only alphabetic characters.",
                        "minlength": "The name must have at least 3 characters.",
                        "maxlength":"The name must not be longer than 20 characters."
                    },
                    "UserEmail":{
                        "required":"The email is required.",
                        "email":"You must provide a valid email.",
                        "remote":"Already registered. Try to recover the account.",
                        "maxlength":"The email must not have more than 30 characters."
                    },
                    "UserPassword":{
                        "required":"The password is required.",
                        "rangelength":"You must provide a password that is between 7 and 21 characters.",
                        "notEqualToName":"The key does not match the name.",
                        "notEqualToEmail":"The key must not be equal to email."
                    },
                    "UserPasswordAgain":{
                        "required":"The password is required.",
                        "rangelength":"You must provide a password that is between 7 and 21 characters.",
                        "notEqualToName":"The key does not match the name.",
                        "notEqualToEmail":"The key must not be equal to email.",
                        "equalTo":"Both password must be identical."
                    },
					"terms-of-service":{
                        "required":"Accept terms-of-service is required."
                    }
                }
            };

            validate.form("UserAddForm",validateObj);

        };

        /*
         Private Method
         Descripción:  inicio de sesión
         */
        var login = function(){

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/in",
                "data":{},
                "form":{
                    "id":"LoginForm",
                    "inputs":[
                        {'id':'LoginEmail',          'name':'email'},
                        {'id':'LoginPassword',       'name':'password'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        if(response['login']){
                            window.location = "/";
                        }else{
                            $("#login-error").fadeIn();
                            setTimeout(function(){ $("#login-error").fadeOut(); }, 7000);
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
            var loginUserValidateObj = {
                "submitHandler": function(){
                    ajax.request(request_parameters);
                },
                "rules":{
                    "LoginEmail":{
                        "required":true,
                        "email": true,
                        "maxlength":30
                    },
                    "LoginPassword":{
                        "required":true,
                        "rangelength": [7, 21]
                    }
                },
                "messages":{
                    "LoginEmail":{
                        "required":"The email is required.",
                        "email":"You must provide a valid email.",
                        "maxlength":"The email must not have more than 30 characters."
                    },
                    "LoginPassword":{
                        "required":"The password is required.",
                        "rangelength":"You must provide a password that is between 7 and 21 characters."
                    }
                }
            };

            validate.form("LoginForm",loginUserValidateObj);
        };

        //Public Method
        user.init = function(){
            login();
            newUser();
            recoverAcount();
        };


    }( window.user = window.user || {}, jQuery ));


    user.init();
});
