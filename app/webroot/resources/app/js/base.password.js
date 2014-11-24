$(document).ready(function(){
	(function( user, $) {

		/*
		 Private Method
		 Description:  new password
		 */
		var newPassword = function(){

			var notification;

			var request_parameters = {
				"requestType":"form",
				"type":"post",
				"url":"/snp",
				"data":{},
				"form":{
					"id":"new-password-form",
					"inputs":[
						{'id':'id',        'name':'id'},
						{'id':'key',       'name':'key'},
						{'id':'password',  'name':'password'}
					]
				},
				"callbacks":{
					"beforeSend":function(){
						notification = ajax.notification("beforeSend");
					},
					"success":function(response){

						var form = $("#new-password-form");

						var message = '';

						if(response['status'] === 'success'){
							ajax.notification("success",notification);

							message = 'Done, now try login in to your account.';

							form.find('.form-group').hide();
							form.find('button').hide();

							form.find('.message').html(utility.alert(message,'success'));
						}else{
							ajax.notification("complete",notification);

							switch (response['message']) {
								case 'user-not-exist':
									message = 'Invalid request.';
									break;
								case 'the-key-is-invalid':
									message = 'Invalid request.';
									break;
								case 'cannot-set-new-password':
									message = 'An unexpected error occurred.';
									break;
								case 'email-not-send':
									message = 'Try login in, and unexpected error occurred.';
									break;
								default:
									message = 'An unexpected error occurred.';
							}

							form.find('.message').html(utility.alert(message,'danger'));
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
					"password":{
						"required":true,
						"rangelength": [7, 21]
					},
					"password-again":{
						"required":true,
						"rangelength": [7, 21],
						"equalTo":"#password"
					}
				},
				"messages":{
					"password":{
						"required":"The password is required.",
						"rangelength":"You must provide a password that is between 7 and 21 characters."
					},
					"password-again":{
						"required":"The password is required.",
						"rangelength":"You must provide a password that is between 7 and 21 characters.",
						"equalTo":"Both password must be identical."
					}
				}
			};

			validate.form("new-password-form",validateObj);

		};


		//Public Method
		user.init = function(){
			newPassword();
		};


	}( window.user = window.user || {}, jQuery ));


	user.init();
});
