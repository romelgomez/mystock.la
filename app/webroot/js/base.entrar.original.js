/*	Olvido Formulario
 ****************************/

/*
 link 		#recover
 modal 		#recover_modal
 form_id		#UserForm
 url			/recover_acount

 inputs
 * Email
 */

// modal
$("#recover").on('click',function(event){
    event.preventDefault();
    $('#recover_modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
        reset_form('UserForm');
        recover_account._validate.resetForm();
    });
});

var user_form_obj = {
    "type":"post",
    "url":"/recover_account",
    "data":{
        "form":{
            "id":"UserForm",
            "inputs":{
                "email":{
                    "id":"Email"
                }
            }
        }
    },
    "console_log":false,
    "callbacks":{
        "complete":function(response){
            var a = response.responseText;
            var email = $.parseJSON(a);

            if(email.send){
                $("#recover_modal .alert-success").fadeIn();
                setTimeout(function(){ $("#recover_modal .alert-success").fadeOut(); }, 7000);
                recover_account._validate.resetForm();
                reset_form("UserForm");
            }else{
                $("#recover_modal .alert-error").fadeIn();
                setTimeout(function(){ $("#recover_modal .alert-error").fadeOut(); }, 7000);
            }

        }
    }
}

// validación:
var recover_account_obj = {
    "submitHandler": function(form) {
        new Request(user_form_obj);
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
}

var recover_account = new validate_this_form("UserForm",recover_account_obj);

