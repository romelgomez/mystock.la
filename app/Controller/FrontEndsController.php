<?php class FrontEndsController extends AppController {

    public function beforeFilter(){

        $this->{'Auth'}->allow('index');

        parent::beforeFilter();
    }

    public function index(){

        $this->layout = 'landingPage';
    }

}
