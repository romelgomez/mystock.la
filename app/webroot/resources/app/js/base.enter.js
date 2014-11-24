$(document).ready(function(){
    (function( user, $) {

		/*
		 Private Method
		 Descripción:  Recuperar una cuenta
		 */
		var verifyEmail = function(){

			$("#send-email-again").on('click',function(event){
				event.preventDefault();
				$('#verify-email-modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
					validate.removeValidationStates('verify-email-form');
				});
			});

			var notification;

			var request_parameters = {
				"requestType":"form",
				"type":"post",
				"url":"/sea",
				"data":{},
				"form":{
					"id":"verify-email-form",
					"inputs":[
						{'id':'verify-email', 'name':'email'}
					]
				},
				"callbacks":{
					"beforeSend":function(){
						notification = ajax.notification("beforeSend");
					},
					"success":function(response){

						var form = $("#verify-email-form");

						var message = '';

						if(response['status'] === 'success'){
							ajax.notification("success",notification);

							message = 'We already send one email to verify your account.';

							form.find('.alert').html(utility.alert(message,'success'));
						}else{
							ajax.notification("complete",notification);

							switch (response['message']) {
								case 'user-not-exist':
									message = 'This email does not exist in our database.';
									break;
								case 'already-verified':
									message = 'This account is already verified';
									break;
								case 'cannot-set-new-parameters':
									message = 'An unexpected error occurred.';
									break;
								case 'email-not-send':
									message = 'An unexpected error occurred.';
									break;
								default:
									message = 'An unexpected error occurred.';
							}

							form.find('.alert').html(utility.alert(message,'danger'));
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
					"verify-email":{
						"required":true,
						"email": true,
						"remote": {
							"url": "/check_email",
							"type": "post",
							"data": {
								"UserEmail": function() {
									return $("#verify-email").val();
								}
							}
						},
						"maxlength":30
					}
				},
				"messages":{
					"Email":{
						"required":"The email is required.",
						"email":"You must provide a valid email.",
						"remote":"There is no one associated account with such email. Please check and try again.",
						"maxlength":"The email must not have more than 30 characters."
					}
				}
			};

			validate.form("verify-email-form",validateObj);

		};


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

                        var form = $("#recoverUserForm");

						var message = '';

						if(response['status'] === 'success'){
							ajax.notification("success",notification);

							message = 'We already send one email to recovery your account.';

							form.find('.alert').html(utility.alert(message,'success'));
						}else{
							ajax.notification("complete",notification);

							switch (response['message']) {
								case 'user-not-exist':
									message = 'This email does not exist in our database.';
									break;
								case 'banned':
									message = 'This account was banned. Please contact us at support@mystock.la if you believe that there was a misunderstanding.';
									break;
								case 'suspended':
									message = 'This account was suspended. Please contact us at support@mystock.la if you believe that there was a misunderstanding.';
									break;
								case 'cannot-set-new-parameters':
									message = 'An unexpected error occurred.';
									break;
								case 'email-not-send':
									message = 'An unexpected error occurred.';
									break;
								default:
									message = 'An unexpected error occurred.';
							}

							form.find('.alert').html(utility.alert(message,'danger'));
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
                        "required":"The email is required.",
                        "email":"You must provide a valid email.",
                        "remote":"There is no one associated account with such email. Please check and try again.",
                        "maxlength":"The email must not have more than 30 characters."
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

						var form = $("#UserAddForm");

						var message = '';

						if(response['status'] === 'success'){
							ajax.notification("success",notification);

							message = 'Almost ready, now we need verify your account, we already send one email to do that.';

							form.find('.alert').html(utility.alert(message,'success'));
						}else{
							ajax.notification("complete",notification);

							switch (response['message']) {
								case 'invalid-data':
									message = 'Invalid request.';
									break;
								case 'user-already-exist':
									message = 'The user already exist';
									break;
								case 'cannot-save-new-user':
									message = 'An unexpected error occurred.';
									break;
								case 'email-not-send':
									message = 'An unexpected error occurred.';
									break;
								default:
									message = 'An unexpected error occurred.';
							}

							form.find('.alert').html(utility.alert(message,'danger'));
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
                        "minlength": 3,
                        "maxlength":30
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
                        "minlength": "The name must have at least 3 characters.",
                        "maxlength":"The name must not be longer than 30 characters."
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

						var message = '';

						if(response['status'] === 'success'){
							window.location = "/";
						}else{
							ajax.notification("complete",notification);

							switch (response['message']) {
								case 'user-not-exist':
									message = 'This email does not exist in our database.';
									break;
								case 'password-does-not-match':
									message = 'The password does not match.';
									break;
								case 'banned':
									message = 'This account was banned. Please contact us at support@mystock.la if you believe that there was a misunderstanding.';
									break;
								case 'suspended':
									message = 'This account was suspended. Please contact us at support@mystock.la if you believe that there was a misunderstanding.';
									break;
								case 'email-not-verified':
									message = 'The email is not verified. <button id="send-email-again" type="button" class="btn btn-link">Send me the email again.</button>';
									break;
								case 'no-login':
									message = 'An unexpected error occurred.';
									break;
								default:
									message = 'An unexpected error occurred.';
							}

							$("#LoginForm").find('.alert').html(utility.alert(message,'danger'));
						}

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){
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
			verifyEmail();
        };


    }( window.user = window.user || {}, jQuery ));


    user.init();
});
