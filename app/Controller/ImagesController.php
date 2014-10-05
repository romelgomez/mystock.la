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


            $this->{'Upload'}->upload($file, $destination,null, array('type' => 'resizecrop', 'size' => array('900', '900'), 'output' => 'jpg'));
            $thumbnails["median"]['name']		= $this->{'Upload'}->result;


            $this->{'Upload'}->upload($file, $destination,null, array('type' => 'resizecrop', 'size' => array('400', '400'), 'output' => 'jpg'));
            $thumbnails["small"]['name']		= $this->{'Upload'}->result;

            $images = $thumbnails;
        }


        if(isset($thumbnails)){

            $parentId = '';

            foreach($thumbnails as $size=>$object){

                $imagenTruncada = array(
                    'Image' => Array
                    (
                        'product_id'	=> $request['product_id'],
                        'size' 			=> $size,
                        'name'			=> $object['name'],
                        'status' 		=> 1
                    )
                );

                if($size == 'large'){
                    $imagenTruncada['Image']['parent_id'] = null;
                    $this->{'Image'}->save($imagenTruncada);
                    $parentId = $this->{'Image'}->id;
                }else{
                    $imagenTruncada['Image']['parent_id'] = $parentId;
                    $this->{'Image'}->save($imagenTruncada);
                }

                $images[$size]['id'] = $this->{'Image'}->id;
                $this->{'Image'}->create();

            }

        }

/*
    {
       "large":{
          "name":"4397c285-5cb7-4431-ae09-ebb3e65ef554.jpg",
          "id":"543098b0-fbd4-4f1a-8eb2-04f27f000007"
       },
       "median":{
          "name":"fe57096c-937a-437b-a59a-8a04230b5755.jpg",
          "id":"543098b0-5bc8-41ce-9b54-04f27f000007"
       },
       "small":{
          "name":"27e1dc0b-15f4-4794-bbeb-f81f2a510804.jpg",
          "id":"543098b0-1830-4529-a119-04f27f000007"
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
