<?php
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController{

	/*
	 @Name              -> beforeFilter
	 @Description       -> This is CakePHP function. Check docs for more details.
	 */
	public function beforeFilter(){
        $this->{'Auth'}->allow(array('add','check_email','in','recover_account','terms_of_service','privacy_policy','new_password_request','verify_email_address','send_email_again_to_verify_email_address'));
        parent::beforeFilter();
    }

	/*
	 @Name              -> privacy_policy
	 @Description       -> The privacy policy.
	 @RequestType	    -> GET
	 @Parameters        -> NULL
	 @Receives       	-> NULL
	 @Returns           -> NULL
	 */
	public  function terms_of_service(){
	}

	/*
	 @Name              -> privacy_policy
	 @Description       -> The privacy policy.
	 @RequestType	    -> GET
	 @Parameters        -> NULL
	 @Receives       	-> NULL
	 @Returns           -> NULL
	 */
	public  function privacy_policy(){
	}

	/*
	 @Name              -> login
	 @Description       -> The method to represent Sign in forms. The (in) Ajax method is login real action.
	 @RequestType	    -> GET
	 @Parameters        -> NULL
	 @Receives       	-> NULL
	 @Returns           -> NULL
	 */
    public function login(){
		if($this->{'Auth'}->login()){
			$this->{'redirect'}('/');
		}
		$this->response->disableCache();
    }

	/*
	 @Name              -> logout
	 @Description       -> sign out.
	 @RequestType	    -> GET
	 @Parameters        -> NULL
	 @Receives       	-> NULL
	 @Returns           -> NULL
	 */
    public function logout(){
        $this->{'Auth'}->logout();
        $this->{'redirect'}('/');
    }

	/*
	 @Name              -> in
	 @Description       -> Login real action. Sign in method.
	 @RequestType	    -> AJAX-POST
	 @Parameters        -> NULL
	 @Receives       	-> JSON object
	 @Returns           -> Array, which is presented as json string with the json_encode() function in "ajax_view" view, in the ajax layout.
	 */
	public function in(){
        $request = $this->{'request'}->input('json_decode',true);

		$options = array(
            'conditions' => array(
                'User.email' 		=> $request['email'],
            )
        );

		$user = $this->{'User'}->find('first',$options);

		$passwordHash = Security::hash($request['password'], 'blowfish', $user['User']['password']);

		// checks that the user password match
		if($passwordHash === $user['User']['password']){
			// checks that the user is not banned
			if(!$user['User']['banned']){
				// checks that the user has email already verified
				if($user['User']['email_verified']){
					if($this->{'Auth'}->login($user)){
						$return['status'] = 'success';
					}else{
						$return['status'] = 'error';
						$return['message'] = 'no-login';
					}
				}else{
					$return['status'] = 'error';
					$return['message'] = 'email-not-verified';
				}
			}else{
				$return['status'] = 'error';
				$return['message'] = 'banned';
			}
        }else{
			$return['status'] = 'error';
			$return['message'] = 'password-does-not-match';
		}

		$this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

	public function verify_email_address(){
		$request = $this->{'request'}->params;

		$options = array(
				'conditions' => array(
						'User.id' 		=> $request['id'],
				)
		);

		$user = $this->{'User'}->find('first',$options);

		// checks that the user exist
		if($user){
			// checks that the email is not verified
			if(!$user['User']['email_verified']){

				$tempPasswordHash = Security::hash($request['key'], 'blowfish', $user['user']['temp_password']);

				if($tempPasswordHash===$user['user']['temp_password']){
					$user =	array(
						'User'=>Array
						(
							'email_verified' =>	1,
						)
					);

					if($this->{'User'}->save($user)){
						$return['status'] = 'success';
					}else{
						$return['status'] = 'error';
						$return['message'] = 'cannot-set-new-parameter';
					}

				}else{
					$return['status'] = 'error';
					$return['message'] = 'this-link-is-invalid';
				}

			}else{
				$return['status'] = 'error';
				$return['message'] = 'already-verified';
			}
		}else{
			$return['status'] = 'error';
			$return['message'] = 'user-not-exist';
		}

		$this->{'set'}('data',$return);
	}


	/*
	 @Name              -> send_email_again_to_verify_email_address
	 @Description       -> send email again to verify email address
	 @RequestType	    -> AJAX-POST
	 @Parameters        -> NULL
	 @Receives       	-> JSON object
	 @Returns           -> Array, which is presented as json string with the json_encode() function in "ajax_view" view, in the ajax layout.
	 */
	public function send_email_again_to_verify_email_address(){
		$request = $this->{'request'}->input('json_decode',true);
		$return = array();

		$options = array(
				'conditions' => array(
						'User.email' 		=> $request['email'],
				)
		);

		$user = $this->{'User'}->find('first',$options);

		// checks that the user exist
		if($user){

			// checks that the email is not verified
			if(!$user['User']['email_verified']){
				$publicKey	 	= String::uuid();
				$privateKeyHash = Security::hash($publicKey);

				$user =	array(
						'User'=>Array
						(
								'temp_password'			=>	$privateKeyHash,
						)
				);

				if($this->{'User'}->save($user)){
					$userData = $this->{'User'}->read();

					$Email = new CakeEmail('default');
					$Email->template('verifyEmail', 'verifyEmail');
					$Email->viewVars(array('userId' => $userData['User']['id'],'publicKey'=>$publicKey));
					$Email->emailFormat('both');
					$Email->from(array('support@mystock.la' => 'MyStock.LA'));
					$Email->to($userData['User']['email']);
					$Email->subject('MyStock.LA - Verify your email address');

					if ($Email->send()) {
						$return['status'] = 'success';
					}else{
						$return['status'] = 'error';
						$return['message'] = 'email-not-send';
					}
				}else{
					$return['status'] = 'error';
					$return['message'] = 'cannot-set-new-parameters';
				}
			}else{
				$return['status'] = 'error';
				$return['message'] = 'already-verified';
			}
		}else{
			$return['status'] = 'error';
			$return['message'] = 'user-not-exist';
		}

	}

	/*
	 @Name              -> add
	 @Description       -> add new user
	 @RequestType	    -> AJAX-POST
	 @Parameters        -> NULL
	 @Receives       	-> JSON object
	 @Returns           -> Array, which is presented as json string with the json_encode() function in "ajax_view" view, in the ajax layout.
	 */
    public function add(){
        $request = $this->{'request'}->input('json_decode',true);
		$return = array();

		$this->{'User'}->set($request);
        if($this->{'User'}->validates()){

			$options = array(
					'conditions' => array(
							'User.email' 		=> $request['email'],
					)
			);

			$user = $this->{'User'}->find('first',$options);

			// checks that the user does not exist
			if(!$user){
				Security::setHash('blowfish');
				$passwordHash = Security::hash($request['password']);

				$publicKey	 	= String::uuid();
				$privateKeyHash = Security::hash($publicKey);

				$user =	array(
						'User'=>Array
						(
								'name'					=>	$request['name'],
								'email'					=>	$request['email'],
								'email_verified'		=>	0,
								'password'				=>	$passwordHash,
								'temp_password'			=>	$privateKeyHash,
								'banned'				=>	0,
						)
				);

				if($this->{'User'}->save($user)){
					$userData = $this->{'User'}->read();

					$Email = new CakeEmail('default');
					$Email->template('verifyEmail', 'verifyEmail');
					$Email->viewVars(array('userId' => $userData['User']['id'],'publicKey'=>$publicKey));
					$Email->emailFormat('both');
					$Email->from(array('support@mystock.la' => 'MyStock.LA'));
					$Email->to($userData['User']['email']);
					$Email->subject('MyStock.LA - Verify your email address');

					if ($Email->send()) {
						$return['status'] = 'success';
					}else{
						$return['status'] = 'error';
						$return['message'] = 'email-not-send';
					}
				}else{
					$return['status'] = 'error';
					$return['message'] = 'cannot-save-new-user';
				}
			}else{
				$return['status'] = 'error';
				$return['message'] = 'user-already-exist';
            }
        }else{
			$return['status'] = 'error';
			$return['message'] = 'invalid-data';
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


	/*
	 @Name              -> check_email
	 @Description       -> to seed if email is already in database
	 @RequestType	    -> AJAX-POST
	 @Parameters        -> NULL
	 @Receives       	-> JSON object
	 @Returns           -> Array, which is presented as json string with the json_encode() function in "ajax_view" view, in the ajax layout.
	 */
    public function check_email(){
		$request = $this->{'request'}->data;

		$userEmailConditions = array(
				'conditions' => array('User.email' => $request['UserEmail']),
		);

        $email = $this->{'User'}->find('first',$userEmailConditions);

        if($email){
            $return = true;
        }else{
            $return = false;
        }

        if(isset($request['inverse_result'])){
            $inverse_result =  $request['inverse_result'];
            if($inverse_result){
                if(!$email){
                    $return = true;
                }else{
                    $return = false;
                }
            }
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

	public function set_new_password(){
		$request = $this->{'request'}->input('json_decode',true);
		$return = array();

		$options = array(
				'conditions' => array(
						'User.id' 		=> $request['id'],
				)
		);

		$user = $this->{'User'}->find('first',$options);

		// checks that the user exist
		if($user){
			$tempPasswordHash = Security::hash($request['key'], 'blowfish', $user['user']['temp_password']);

			if($tempPasswordHash===$user['user']['temp_password']){
				Security::setHash('blowfish');
				$passwordHash = Security::hash($request['password']);

				$publicKey	 	= String::uuid();
				$privateKeyHash = Security::hash($publicKey);

				$user =	array(
						'User'=>Array
						(
								'password'				=>	$passwordHash,
								'temp_password'			=>	$privateKeyHash,
						)
				);

				if($this->{'User'}->save($user)){
					$userData = $this->{'User'}->read();

					// Send email to notify what the password has been changed

					$Email = new CakeEmail('default');
					$Email->template('passwordHasBeenChanged', 'passwordHasBeenChanged');
//					$Email->viewVars(array('userId' => $userData['User']['id'],'publicKey'=>$publicKey));
					$Email->emailFormat('both');
					$Email->from(array('support@mystock.la' => 'MyStock.LA'));
					$Email->to($userData['User']['email']);
					$Email->subject('MyStock.LA - You password has been changed');
//
					if ($Email->send()) {
						$return['status'] = 'success';
					}else{
						$return['status'] = 'error';
						$return['message'] = 'email-not-send';
					}

				}else{
					$return['status'] = 'error';
					$return['message'] = 'cannot-set-new-password';
				}



			}else{
				$return['status'] 	= 'error';
				$return['message']	= 'the-key-is-invalid';
			}
		}else{
			$return['status'] = 'error';
			$return['message'] = 'user-not-exist';
		}


	}

	/*
	 @Name              -> new_password_request
	 @Description       -> To accept or not, set new password
	 @RequestType	    -> GET;
	 @Parameters        -> NULL
	 @Receives       	-> 2 Parameters, id=> is uuid UserId; key=> is uuid temp password which is processed for Security::hash blowfish pipeline.
	 @Returns           -> Array
	 */
	public function  new_password_request(){
		$request = $this->{'request'}->params;

		$options = array(
				'conditions' => array(
						'User.id' 		=> $request['id'],
				)
		);

		$user = $this->{'User'}->find('first',$options);

		// checks that the user exist
		if($user){
			$tempPasswordHash = Security::hash($request['key'], 'blowfish', $user['user']['temp_password']);

			if($tempPasswordHash===$user['user']['temp_password']){
				$return['status'] 	= 'success';
				$return['message']	= 'request-accepted';
			}else{
				$return['status'] 	= 'error';
				$return['message']	= 'this-link-is-invalid';
			}
		}else{
			$return['status'] = 'error';
			$return['message'] = 'user-not-exist';
		}

		$this->{'set'}('data',$return);
	}

	/*
	 @Name              -> recover_account
	 @Description       -> for recover account
	 @RequestType	    -> AJAX-POST;
	 @Parameters        -> NULL
	 @Receives       	-> JSON object;
	 @Returns           -> Array, which is presented as json string with the json_encode() function in "ajax_view" view, in the ajax layout.
	 */
    public function recover_account(){
        $request = $this->{'request'}->input('json_decode',true);
		$return  = array();

		$options = array(
			'conditions' => array(
				'User.email' 	=> $request['email'],
			)
		);

		$user = $this->{'User'}->find('first',$options);

		// checks that the user exist
		if($user){

			// checks that user is not banned
			if(!$user['User']['banned']){
				Security::setHash('blowfish');

				$publicKey	 = String::uuid();
				$privateKey  = Security::hash($publicKey);

				$newTempPassword =	array(
						'User'=>Array
						(
							'id'				=>$user['User']['id'],
							'email_verified' 	=> 1,
							'temp_password'		=>$privateKey,
						)
				);

				if($this->{'User'}->save($newTempPassword)){

					$Email = new CakeEmail('default');
					$Email->template('newPasswordRequest', 'newPasswordRequest');
					$Email->viewVars(array('userId' => $user['User']['id'],'publicKey'=>$publicKey));
					$Email->emailFormat('both');
					$Email->from(array('support@mystock.la' => 'MyStock.LA'));
					$Email->to($user['User']['email']);
					$Email->subject('MyStock.LA - Set new password');

					if ($Email->send()) {
						$return['status'] = 'success';
					}else{
						$return['status'] = 'error';
						$return['message'] = 'email-not-send';
					}
				}else{
					$return['status'] = 'error';
					$return['message'] = 'cannot-set-new-parameters';
				}
			}else{
				$return['status'] = 'error';
				$return['message'] = 'banned';
			}
		}else{
			$return['status'] = 'error';
			$return['message'] = 'user-not-exist';
		}

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

}
