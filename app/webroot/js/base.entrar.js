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
    };


}( window.user = window.user || {}, jQuery ));


user.init();