<?php
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController{

    public function beforeFilter(){
        $this->{'Auth'}->allow(array('add','check_email','in','recover_account','terms_of_service','privacy_policy','verify_email_address','send_email_again'));
        parent::beforeFilter();
    }

	public  function terms_of_service(){
	}

	public  function privacy_policy(){
	}

    public function login(){
		if($this->{'Auth'}->login()){
			$this->{'redirect'}('/');
		}
		$this->response->disableCache();
    }

    public function logout(){
        $this->{'Auth'}->logout();
        $this->{'redirect'}('/');
    }

    public function in(){
        $request = $this->{'request'}->input('json_decode',true);

		// Before login, first we need verify your email address, we already send to you one email to do that.
		$options = array(
            'conditions' => array(
                'User.email' 		=> $request['email'],
            )
        );

		$user = $this->{'User'}->find('first',$options);

		$inHash = Security::hash($request['password'], 'blowfish', $user['User']['password']);

        if($inHash === $user['User']['password']){

			if(!$user['User']['banned']){
				if($user['User']['email_verified']){
					if($this->{'Auth'}->login($user)){
						$return['login'] = true;
					}else{
						$return['login'] = false;
					}
				}else{
					$return['email_verified'] 	= false;
				}
			}else{
				$return['banned'] 	= true;
			}
        }else{
            $return['login'] = false;
        }

		$this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

	public function verify_email_address(){
		debug($this->{'request'});

		//			$newHash = Security::hash($newPassword, 'blowfish', $storedPassword);

	}

	public function send_email_again(){
		debug($this->{'request'});

//		$options = array(
//				'conditions' => array(
//						'User.email' 		=> $this->{'request'}['data']['email'],
//				)
//		);
//
//		$user = $this->{'User'}->find('first',$options);
//
//		if($user){
//
//			$publicKey	 	= String::uuid();
//			$privateKeyHash = Security::hash($publicKey);
//
//			$user =	array(
//					'User'=>Array
//					(
//							'temp_password'			=>	$privateKeyHash,
//					)
//			);
//
//			if($this->{'User'}->save($user)){
//				$return = $this->{'User'}->read();
//
//				$message = '<h3>MyStock.LA - Verify your email address</h3>'.
//						'<p>Click in the next link to verify your email address:</p>'.
//						'<p><a href="http://www.mystock.la/ve/'.(string)$return['User']['id'].'/'.(string)$publicKey.'" target="_blank">Verify my email address!</a></p>'.
//						'<p>NOTE: After successfully verify your email address, please delete this email.<p>';
//
//				$Email = new CakeEmail();
//				$Email->from(array('support@mystock.la' => 'MyStock.LA'));
//				$Email->to($user['User']['email']);
//				$Email->subject('MyStock.LA - Verify your email address');
//
//				if ($Email->send($message)) {
//					$return['send'] = true;
//				} else {
//					$return['send'] = false;
//				}
//
//			}
//		}

	}

	public function send_email($config,$from,$to,$subject,$message){
		$Email = new CakeEmail($config);
		$Email->from($from);
		$Email->to($to);
		$Email->subject($subject);

		return $Email->send($message);
	}


    public function add(){
        $request = $this->{'request'}->input('json_decode',true);

		$this->{'User'}->set($request);
        if($this->{'User'}->validates()){

			// Before login, first we need verify your email address, we already send to you one email to do that.
			$options = array(
					'conditions' => array(
							'User.email' 		=> $request['email'],
					)
			);

			$user = $this->{'User'}->find('first',$options);

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
					$return = $this->{'User'}->read();

					// Send Email
					$message = '<h3>MyStock.LA - Verify your email address</h3>'.
							'<p>Click in the next link to verify your email address:</p>'.
							'<p><a href="http://www.mystock.la/ve/'.(string)$return['User']['id'].'/'.(string)$publicKey.'" target="_blank">Verify my email address!</a></p>'.
							'<p>NOTE: After successfully verify your email address, please delete this email.<p>';
					$from 		= array('support@mystock.la' => 'MyStock.LA');
					$to 		= $return['User']['email'];
					$subject 	= 'MyStock.LA - Verify your email address';

					if ($this->send_email('default',$from,$to,$subject,$message)) {
						$return['send-email'] = true;
					}

				}else{
					$return['saved-user'] = false;
				}

			}else{
                $return['already-exist'] = true;
            }
        }else{
            $return['invalid-data'] = true;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    private function email($email){
        $userEmailConditions = array(
            'conditions' => array('User.email' => $email),
        );
        return $this->{'User'}->find('first',$userEmailConditions);
    }

    public function check_email(){
        if($this->{'request'}->is('post')){
            $request = $this->{'request'}->data;
        }else{
            $request = $this->{'request'}->query;
        }

        $email = $this->email($request['UserEmail']);

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

	public function  new_password(){
		debug($this->{'request'});

		//			$newHash = Security::hash($newPassword, 'blowfish', $storedPassword);
	}

    public function recover_account(){
        $request = $this->{'request'}->input('json_decode',true);

		$options = array(
				'conditions' => array(
						'User.email' 	=> $request['email'],
				)
		);

		$user = $this->{'User'}->find('first',$options);

		if($user == true){

			if(!$user['User']['banned']){
				Security::setHash('blowfish');

				$publicKey	 = String::uuid();
				$privateKey  = Security::hash($publicKey);

				$newTempPassword =	array(
						'User'=>Array
						(
								'id'				=>$user['User']['id'],
								'email_verified' 	=> 1,
								'temp_password'		=>	$privateKey,
						)
				);

				if($this->{'User'}->save($newTempPassword)){

					// Send Email
					$message = '<h3>MyStock.LA - Set new password</h3>'.
							'<p>Click in the next link to set new password:</p>'.
							'<p><a href="http://www.mystock.la/np/'.(string)$user['User']['id'].'/'.(string)$publicKey.'" target="_blank">Set new password</a></p>'.
							'<p>NOTE: After set new password, please delete this email.<p>';
					$from 		= array('support@mystock.la' => 'MyStock.LA');
					$to 		= $user['User']['email'];
					$subject 	= 'Recover password';

					if ($this->send_email('default',$from,$to,$subject,$message)) {
						$return['send-email'] = true;
					}

				}else{
					$return['send'] = false;
				}

			}else{
				$return['banned'] 	= true;
			}

		}else{
			$return['send'] = false;
		}

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

}
