<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {

    public $helpers = array('Session', 'Html','Form','Time');

    public function appError() {
        $this->{'redirect'}('/');
    }

    public $components = array(
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            ),
            'loginAction'=>'/entrar',
            'loginRedirect' => '/publicados',
            'logoutRedirect'=> '/',
            'ajaxLogin'=>'expired_session'
        ),
        'Cookie',
        'Session'
    );

    public function beforeFilter(){

        // Configuración de la Secciones
        Configure::write('Session', array(
            'cookie' => 'santomercado',
            'defaults' => 'database',
            'timeout' => 4320 //3 days
        ));

        /* Para Ubicarse entre las vistas y controladores
         ****************************************************/
        $this->{'set'}('controller',$this->{'request'}->params['controller']);
        $this->{'set'}('action',$this->{'request'}->params['action']);

    }

    public function beforeRender(){
        // Destruye la sección al abrir x links en otra pestaña si se coloca en la función beforeFilter.
        if($this->{'Auth'}->User()){
            $this->{'set'}('userLogged',$this->{'Auth'}->User());
        }

    }

    function cleanString($texto)
    {
        return  trim(preg_replace("/[^\p{L}\p{N}]/u", ' ', $texto));
    }

}