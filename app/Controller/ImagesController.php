<?php

class ImagesController extends AppController{

    /*
    $return['all_images'] = $image = $this->Image->find('threaded', array(
            'conditions' => array('Image.product_id'=>$request['product_id']),
            'contain' => false
    ));
    */

    /*
    1) Escenario donde existan los campos status y delete
    -status 0 y delete 0  se interpreta: la imagen fue cargada y descartada antes de ser aprobada.
    -status 1 y delete 0  se interpreta: la imagen cargada fue aprobada
    -status 1 y delete 1  se interpreta: la imagen cargada aprobada fue eliminada
    -status 0 y delete 1  se interpreta: esta interpretación no existe.

    2) Escenario donde solo exista status:
    -status 0	se interpreta: la imagen fue cargada, todavía no esta aprobada, puede considerarse eliminada
    -status 1	se interpreta: la imagen cargada esta aprobada.

    3) Escenario donde solo exista delete:
    -delete 0	se interpreta: la imagen cargada esta aprobada
    -delete 1	se interpreta: la imagen cargada se considera eliminada.

    ******************* Se implementa el 2 escenario	*****************************
    */

    public $components = array('Upload');

    /* Descripción: 		Función guardar y redimensionar las imágenes del producto.
     * tipo de solicitud: 	ajax-get,ajax-post
     * tipo de acceso: 		vendedor
     * Recibe: 				array.
     * Retorna: 			un array. el cual sera transformado en un objeto JSON en la vista ajax_view.
     *******************/
    public function add(){
        $request = $this->{'request'}->data;

        $destination = WWW_ROOT."resources/app/img/products/";
        $file = $this->{'request'}->params['form']['image'];

        $images = array();

        if($file['name']){
            $this->{'Upload'}->upload($file, $destination,null, array('type' => 'resizecrop', 'size' => array('1920', '1080'), 'output' => 'jpg'));
            $thumbnails["large"]['name']		= $this->{'Upload'}->result;
            $thumbnails["large"]['size']		= "1920x1080";

            $this->{'Upload'}->upload($file, $destination,null, array('type' => 'resizecrop', 'size' => array('900', '900'), 'output' => 'jpg'));
            $thumbnails["median"]['name']		= $this->{'Upload'}->result;
            $thumbnails["median"]['size']		= "900x900";

            $this->{'Upload'}->upload($file, $destination,null, array('type' => 'resizecrop', 'size' => array('400', '400'), 'output' => 'jpg'));
            $thumbnails["small"]['name']		= $this->{'Upload'}->result;
            $thumbnails["small"]['size']		= "400x400px";

            $this->{'Upload'}->upload($file, $destination); // al parecer el orden de subida influye, esta linea si va de primera las demás subidas no se ejecutan.
            $images["original"]['name'] 	= $this->{'Upload'}->result;

            $images['thumbnails'] 		= $thumbnails;
        }

        if(isset($images)){
            $imagenOriginal = array(
                'Image' => Array
                (
                    'parent_id' 	=> NULL,
                    'product_id' 	=> $request['product_id'],
                    'size' 			=> 'original',
                    'name' 			=> $images['original']['name'],
                    'status' 		=> 0
                )
            );

            $this->{'Image'}->save($imagenOriginal);
            $images['original']['id'] 	= $this->{'Image'}->id;
            $this->{'Image'}->create();

            foreach($images['thumbnails'] as $key=>$object){
                $imagenTruncada = array(
                    'Image' => Array
                    (
                        'parent_id' 	=> $images['original']['id'],
                        'product_id'	=> $request['product_id'],
                        'size' 			=> $object['size'],
                        'name'			=> $object['name'],
                        'status' 		=> 0
                    )
                );
                $this->{'Image'}->save($imagenTruncada);
                $images['thumbnails'][$key]['id'] = $this->{'Image'}->id;
                $this->{'Image'}->create();
            }
        }

        /*
                        {
                           "original":{
                              "name":"Capturadepantallade2013-01-0619203433.png",
                              "id":"78"
                           },
                           "thumbnails":{
                              "large":{
                                 "name":"Capturadepantallade2013-01-0619203430.png",
                                 "size":"1920x1080",
                                 "id":"79"
                              },
                              "median":{
                                 "name":"Capturadepantallade2013-01-0619203431.png",
                                 "size":"900x900",
                                 "id":"80"
                              },
                              "small":{
                                 "name":"Capturadepantallade2013-01-0619203432.png",
                                 "size":"400x400px",
                                 "id":"81"
                              }
                           }
                        }
        */

        $return = $images;

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }

    /* Descripción: 		Función para habilitar la selección de imágenes cargadas por el vendedor.
     * tipo de solicitud: 	ajax-get,ajax-post
     * tipo de acceso: 		vendedor
     * Recibe: 				array.
     * Retorna: 			un array. el cual sera transformado en un objeto JSON en la vista ajax_view.
     *******************/
    public function enables_this_images(){
        $request = $this->{'request'}->input('json_decode',true);


        /*
        print_r($request);

        Array
        (
            [images_ids] => Array
                (
                    [0] => 1154
                    [1] => 1146
                    [2] => 1142
                    [3] => 1150
                    [4] => 1138
                )

            [product_id] => 212
        )
        */

        $return = array();

        // verificar y actualizar los registros.
        foreach($request['images_ids'] as $image_id){
            $image = $this->{'Image'}->find('first', array(
                'conditions' => array('Image.id' => $image_id,'Image.product_id'=>$request['product_id']),
                'contain' => false
            ));
            if($image){
                $update = array(
                    'Image'=>array(
                        'id'=>	$image['Image']['id'],
                        'status'=> 1,
                    )
                );

                if ($this->{'Image'}->save($update)) {
                    $return['status'] = true;
                }else{
                    $return['status'] = false;
                }
            }
            $this->{'Image'}->create();
        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


    public function disable_this_imagen(){
        $request = $this->{'request'}->input('json_decode',true);


        $user_logged = $this->{'Auth'}->User();

        /*
        print_r($request);
        Array
        (
            [image_id] => 1390
            [product_id] => 230
        )
        */

        $image = $this->{'Image'}->find('first', array(
            'conditions' => array('Image.id' => $request['image_id'],'Image.product_id'=>$request['product_id']),
            'contain' => array(
                'Product'=>array(
                    'User'=>array(
                        'conditions' =>array('User.id'=>$user_logged['User']['id'])
                    )
                )
            )
        ));

        $return = array();

        if($image){
            $update = array(
                'Image'=>array(
                    'id'=>	$image['Image']['id'],
                    'status'=> 0
                )
            );
            if($this->{'Image'}->save($update)) {
                $return['image_id'] = $request['image_id'];
                $return['status'] 	= true;
            }else{
                $return['status'] = false;
            }

        }

        $this->{'set'}('return',$return);
        $this->{'render'}('ajax_view','ajax');
    }


}
