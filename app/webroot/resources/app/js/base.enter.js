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

							form.find('.message').html(utility.alert(message,'success'));
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

							form.find('.message').html(utility.alert(message,'danger'));
						}

					},
					"error":function(){
						ajax.notification("error",notification);
					},
					"complete":function(response){}
				}
			};

			// validation:
			var validateObj = {
				"submitHandler": function(){
					ajax.request(request_parameters);
				},
				"rules":{
					"verify-email":{
						"required":true,
						"email": true,
						"remote": {
							"url": "/check-email",
							"type": "post",
							"data": {
								"email": function() {
									return $("#verify-email").val();
								}
							}
						},
						"maxlength":30
					}
				},
				"messages":{
					"verify-email":{
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
                $('#recover-account-modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                    validate.removeValidationStates('recover-account-form');
                });
            });

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/recover-account",
                "data":{},
                "form":{
                    "id":"recover-account-form",
                    "inputs":[
                        {'id':'recover-account-email', 'name':'email'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){

                        var form = $("#recover-account-form");

						var message = '';

						if(response['status'] === 'success'){
							ajax.notification("success",notification);

							message = 'We already send one email to recovery your account.';

							form.find('.message').html(utility.alert(message,'success'));
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
                    "recover-account-email":{
                        "required":true,
                        "email": true,
                        "remote": {
                            "url": "/check-email",
                            "type": "post",
                            "data": {
                                "email": function() {
                                    return $("#recover-account-email").val();
                                }
                            }
                        },
                        "maxlength":30
                    }
                },
                "messages":{
                    "recover-account-email":{
                        "required":"The email is required.",
                        "email":"You must provide a valid email.",
                        "remote":"There is no one associated account with such email. Please check and try again.",
                        "maxlength":"The email must not have more than 30 characters."
                    }
                }
            };

            validate.form("recover-account-form",validateObj);

        };

        /*
         Private Method
         Descripción:  Nuevo Usuario
         */
        var newUser = function(){

            $("#new-user").on('click',function(event){
                event.preventDefault();
                $('#new-user-modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                    validate.removeValidationStates('new-user-form');
                });
            });

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/new-user",
                "data":{},
                "form":{
                    "id":"new-user-form",
                    "inputs":[
                        {'id':'new-user-name',          'name':'name'},
                        {'id':'new-user-email',       	'name':'email'},
                        {'id':'new-user-password',      'name':'password'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){
                        notification = ajax.notification("beforeSend");
                    },
                    "success":function(response){

						var form = $("#new-user-form");

						var message = '';

						if(response['status'] === 'success'){
							ajax.notification("success",notification);

							message = 'Almost ready, now we need verify your account, we already send one email to do that.';

							form.find('.message').html(utility.alert(message,'success'));
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
                    "new-user-name":{
                        "required":true,
                        "minlength": 3,
                        "maxlength":30
                    },
                    "new-user-email":{
                        "required":true,
                        "email": true,
                        "remote": {
                            "url": "/check-email",
                            "type": "post",
                            "data": {
                                "inverse_result":true,
                                "email":function(){
                                    return $("#new-user-email").val();
                                }
                            }
                        },
                        "maxlength":30
                    },
                    "new-user-password":{
                        "required":true,
                        "rangelength": [7, 21],
                        "notEqualToName":"new-user-name",
                        "notEqualToEmail":"new-user-email"
                    },
                    "new-user-password-again":{
                        "required":true,
                        "rangelength": [7, 21],
                        "notEqualToName":"new-user-name",
                        "notEqualToEmail":"new-user-email",
                        "equalTo":"#new-user-password"
                    },
                    "terms-of-service":{
                        "required":true
                    }
                },
                "messages":{
                    "new-user-name":{
                        "required":"The name is required.",
                        "minlength": "The name must have at least 3 characters.",
                        "maxlength":"The name must not be longer than 30 characters."
                    },
                    "new-user-email":{
                        "required":"The email is required.",
                        "email":"You must provide a valid email.",
                        "remote":"Already registered. Try to recover the account.",
                        "maxlength":"The email must not have more than 30 characters."
                    },
                    "new-user-password":{
                        "required":"The password is required.",
                        "rangelength":"You must provide a password that is between 7 and 21 characters.",
                        "notEqualToName":"The key does not match the name.",
                        "notEqualToEmail":"The key must not be equal to email."
                    },
                    "new-user-password-again":{
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

            validate.form("new-user-form",validateObj);

        };

        /*
         Private Method
         Description:  log in
         */
        var login = function(){

            var notification;

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/in",
                "data":{},
                "form":{
                    "id":"login-form",
                    "inputs":[
                        {'id':'login-email',          'name':'email'},
                        {'id':'login-password',       'name':'password'}
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

							$("#login-form").find('.message').html(utility.alert(message,'danger'));
						}

                    },
                    "error":function(){
                        ajax.notification("error",notification);
                    },
                    "complete":function(){
                    }
                }
            };

            // Validation:
            var loginUserValidateObj = {
                "submitHandler": function(){
                    ajax.request(request_parameters);
                },
                "rules":{
                    "login-email":{
                        "required":true,
                        "email": true,
                        "maxlength":30
                    },
                    "login-password":{
                        "required":true,
                        "rangelength": [7, 21]
                    }
                },
                "messages":{
                    "login-email":{
                        "required":"The email is required.",
                        "email":"You must provide a valid email.",
                        "maxlength":"The email must not have more than 30 characters."
                    },
                    "login-password":{
                        "required":"The password is required.",
                        "rangelength":"You must provide a password that is between 7 and 21 characters."
                    }
                }
            };

            validate.form("login-form",loginUserValidateObj);
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
