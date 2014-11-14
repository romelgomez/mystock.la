<?php class UsersController extends AppController{

    public function beforeFilter(){
        $this->{'Auth'}->allow(array('add','check_email','in','recover_account','terms_of_service','privacy_policy'));
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

        $options = array(
            'conditions' => array(
                'User.email' 		=> $request['email'],
                'User.password'		=> $this->{'Auth'}->password($request['password'])
            )
        );

        $user = $this->{'User'}->find('first',$options);

        if($user == true){
            if($this->{'Auth'}->login($user)){
                $return['login'] = true;
            }else{
                $return['login'] = false;
            }
        }else{
            $return['login'] = false;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    public function add(){
        $request = $this->{'request'}->input('json_decode',true);


        $this->{'User'}->set($request);
        if($this->{'User'}->validates()){

            $user =	array(
                'User'=>Array
                (
                    'name'				=>	$request['name'],
                    'email'				=>	$request['email'],
                    'password'			=>	$this->{'Auth'}->password($request['password']),
                    'activation_key'	=>	String::uuid(),
                )
            );

            if($this->{'User'}->save($user)){
                $return = $this->{'User'}->read();
                // TODO se envía el correo.
                $return['Add'] = true;
            }else{
                $return['Add'] = false;
            }

        }else{
            $return['Add'] = false;
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

    public function recover_account(){
        $request = $this->{'request'}->input('json_decode',true);

        if($this->email($request['email'])){
            // ++++++ se envía el correo  ++++++
            $return['Send'] = true;
        }else{
            $return['Send'] = false;
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

}
