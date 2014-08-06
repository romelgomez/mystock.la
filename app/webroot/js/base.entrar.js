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


/*	Nuevo Usuario Formulario
 ****************************/
/* 
	link 		#new_user
	modal 		#new_user_modal
	form_id		#UserAddForm
	url			/new_user
	
	inputs
	* UserName
	* UserEmail
	* 	"url": "/check_email"
	* UserPassword
*/

$("#new_user").on('click',function(event){
	event.preventDefault();
	$('#new_user_modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
		reset_form('UserAddForm');
		new_user._validate.resetForm();
	});
});

var new_user_obj = {
	"type":"post",
	"url":"/new_user", 
	"data":{
		"form":{
			"id":"UserAddForm",
			"inputs":{
				"name":{
					"id":"UserName"
				},
				"email":{
					"id":"UserEmail"
				},
				"password":{
					"id":"UserPassword"
				}
			}
		}
	},
	"console_log":true,
	"callbacks":{
		"complete":function(response){
			var a = response.responseText;
			var obj = $.parseJSON(a);
			//console.log(obj);
			var status = display_status_request("UserAddForm","new_user_modal",obj);	
			if(status){
				new_user._validate.resetForm();
				reset_form("UserAddForm");
			}
		}
	}
}

// validación:
var new_user_validate_obj = {
	"submitHandler": function(form) {
		new Request(new_user_obj);
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
			"lettersonly":"El nombre debe tener solo caracteres alfabeticos.",
			"minlength": "El nombre debe tener almenos 3 caracteres.",
			"maxlength":"El nombre no debe tener mas de 20 caracteres."
		},
		"UserEmail":{
			"required":"El campo correo es obligatorio.",
			"email":"Debe proporcionar un correo valido.",
			"remote":"Ya esta registrado. Intente recuperar la cuenta.",
			"maxlength":"El correo no debe tener mas de 30 caracteres.",
		},
		"UserPassword":{
			"required":"El campo contraceña es obligatorio.",
			"rangelength":"Debe proporcionar una clave que contenga entre 7 y 21 caracteres.",
			"notEqualToName":"La clave no debe ser igual al <b>nombre.</b>", 
			"notEqualToEmail":"La clave no debe ser igual al <b>correo.</b>"
		}
	}
}

var new_user = new validate_this_form("UserAddForm",new_user_validate_obj);

/*	login Formulario
 ****************************
	form_id		#LoginForm
	url			/login 
	
	inputs
	* LoginEmail
	* LoginPassword
	
*/

var login_user_obj = {
	"type":"post",
	"url":"/login", 
	"data":{
		"form":{
			"id":"LoginForm",
			"inputs":{
				"email":{
					"id":"LoginEmail"
				},
				"password":{
					"id":"LoginPassword"
				}
			}
		}
	},
	"console_log":false,
	"callbacks":{
		"complete":function(response){
				var a 	= response.responseText;
							
				var obj = $.parseJSON(a);
				
				if(obj.login){
					window.location = "/cuenta";
				}else{
					$("#login-error").fadeIn();
					setTimeout(function(){ $("#login-error").fadeOut(); }, 7000);
				}
				
		}
	}
}	

// validación:
var login_user_validate_obj = {
	"submitHandler": function(form) {
		new Request(login_user_obj);
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
			"maxlength":"El correo no debe tener mas de 128 caracteres.",
		},
		"LoginPassword":{
			"required":"El campo contraceña es obligatorio.",
			"rangelength":"Debe ser una clave que contenga entre 7 y 21 caracteres.",
		}
	}
}	

var login_user = new validate_this_form("LoginForm",login_user_validate_obj);
