<?php class UsersController extends AppController{
	
	public function beforeFilter(){
		$this->Auth->allow(array('add','check_email','in','recover_account'));
		parent::beforeFilter();				
	}

	public function login(){
	}
	
	public function logout(){		
		$this->Auth->logout();
		$this->redirect('/');
	}
		
	public function in(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		$options = array(
			'conditions' => array(
								'User.email' 		=> $request['email'],
								'User.password'		=> $this->Auth->password($request['password'])
						)
		);
		
		$user = $this->User->find('first',$options);	
		
		if($user == true){
			if($this->Auth->login($user)){
				$return['login'] = true;
			}else{
				$return['login'] = false;
			}	
		}else{
			$return['login'] = false;
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}
	
	public function add(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
	
		$this->User->set($request);
		if($this->User->validates()){
			
			$user =	array(
				'User'=>Array
					(
						'name'				=>	$request['name'],
						'email'				=>	$request['email'],
						'password'			=>	$this->Auth->password($request['password']),
						'activation_key'	=>	$this->Auth->password($request['email']),
					)
			);
			
			//	$return['_info_to_save'] = $user;
			if($this->User->save($user)){
				$return = $this->User->read();
				// se envia el correo.
				$return['save'] = true;
			}else{
				$return['save'] = false; // ++++++++++++++ ha ocurrido un error +++++++++++++++
			}
			$return['validates'] = true;
			
		}else{
			$return['validates'] = false;
			// se envia los campos faltantes o algun error que pudicece haber ocurrido.
			$errors = $this->User->validationErrors;
			foreach($errors as $k=>$v){
				$tmp = 'user_'.$k;
				$return['fields'][Inflector::camelize($tmp)] = $v[0];
			}
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}

	private function email($email){
		$userEmailConditions = array(
			'conditions' => array('User.email' => $email),
		);
		return $this->User->find('first',$userEmailConditions);
	}
	
	public function check_email(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
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
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}
	
	public function recover_account(){
		if($this->request->is('post')){
			$request = $this->request->data;
		}else{
			$request = $this->request->query; 
		}
		
		if($this->email($request['email'])){
			// ++++++ se envia el correo  ++++++
			$return['send'] = true;
		}else{
			$return['send'] = false;
		}
		
		$this->set('return',$return);
		$this->render('ajax_view','ajax');
	}
	
}?>
