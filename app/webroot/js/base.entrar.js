/*
    login Formulario

     form_id    #LoginForm
     url        /login

     inputs
        LoginEmail
        LoginPassword
 */

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
                "beforeSend":function(){},
                "success":function(response){
                    $('#debug').text(JSON.stringify(response));

                    var userForm = $("#UserForm");

                    if(response['Send']){
                        userForm.find(".alert-success").fadeIn();
                        validate.removeValidationStates('UserForm');

                        setTimeout(function(){
                            $("#UserForm").find(".alert-success").fadeOut();
                        },2000);
                    }else{
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
                "error":function(){},
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
                "beforeSend":function(){},
                "success":function(response){
                    $('#debug').text(JSON.stringify(response));

                    var userAddForm = $("#UserAddForm");

                    if(response['Add']){
                        userAddForm.find(".alert-success").fadeIn();
                        validate.removeValidationStates('UserAddForm');

                        setTimeout(function(){
                            $("#UserAddForm").find(".alert-success").fadeOut();
                        },2000);
                    }else{
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
                "error":function(){},
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
                }
            },
            "messages":{
                "UserName":{
                    "required":"El campo nombre es obligatorio.",
                    "lettersonly":"El nombre debe tener solo caracteres alfabéticos.",
                    "minlength": "El nombre debe tener al menos 3 caracteres.",
                    "maxlength":"El nombre no debe tener mas de 20 caracteres."
                },
                "UserEmail":{
                    "required":"El campo correo es obligatorio.",
                    "email":"Debe proporcionar un correo valido.",
                    "remote":"Ya esta registrado. Intente recuperar la cuenta.",
                    "maxlength":"El correo no debe tener mas de 30 caracteres."
                },
                "UserPassword":{
                    "required":"El campo contraseña es obligatorio.",
                    "rangelength":"Debe proporcionar una clave que contenga entre 7 y 21 caracteres.",
                    "notEqualToName":"La clave no debe ser igual al nombre.",
                    "notEqualToEmail":"La clave no debe ser igual al correo."
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

        var request_parameters = {
            "requestType":"form",
            "type":"post",
            "url":"/login",
            "data":{},
            "form":{
                "id":"LoginForm",
                "inputs":[
                    {'id':'LoginEmail',          'name':'email'},
                    {'id':'LoginPassword',       'name':'password'}
                ]
            },
            "callbacks":{
                "beforeSend":function(){},
                "success":function(response){
                    $('#debug').text(JSON.stringify(response));

                    if(response['login']){
                        window.location = "/";
                    }else{
                        $("#login-error").fadeIn();
                        setTimeout(function(){ $("#login-error").fadeOut(); }, 7000);
                    }

                },
                "error":function(){},
                "complete":function(response){}
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
                    "maxlength":128
                },
                "LoginPassword":{
                    "required":true,
                    "rangelength": [7, 21]
                }
            },
            "messages":{
                "LoginEmail":{
                    "required":"El campo correo es obligatorio.",
                    "email":"Debe proporcionar un correo valido.",
                    "maxlength":"El correo no debe tener mas de 128 caracteres."
                },
                "LoginPassword":{
                    "required":"El campo contraseña es obligatorio.",
                    "rangelength":"Debe ser una clave que contenga entre 7 y 21 caracteres."
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