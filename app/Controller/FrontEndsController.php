<?php class FrontEndsController extends AppController {

	public function beforeFilter(){
			
		$this->Auth->allow('index');
			
		parent::beforeFilter();				
	}
	
	public function index(){
		
		$this->loadModel('Category');
		$categories = $this->Category->find('threaded', array(
				'order' => 'Category.lft',
				'contain' => false
		));
		$this->set('categories',$categories);
		
	}
	
} ?>
