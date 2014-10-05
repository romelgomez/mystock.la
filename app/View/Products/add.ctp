<!-- css temporal -->
<style type="text/css">
    /* Categorías */

    .error {
        border: 1px solid red;
    }

    .ulMenu {
        float: left;
        overflow: auto;
        height: 250px; /* 170 */
        width: 250px;
        border-right: 1px solid;
        background-color: aliceblue;
    }

    .li-selected{
        background: #ACEAFF;
    }

    .ul-parent-selected{
        background: #9DE7FF;
    }

    .li-parent-selected{
        background: #6FDBFF;
    }

    .liMenu{
        padding: 5px;
        padding-left: 10px;
        cursor: pointer;
    }

    .class-all-menu-container{
        overflow: hidden;
        border: 1px solid black;
        padding: 10px;
        background-color: whitesmoke;
        border-top-left-radius: 6px 6px;
        border-top-right-radius: 6px 6px;
        border-bottom-left-radius: 6px 6px;
        border-bottom-right-radius: 6px 6px;
    }

    .menu-container{
        overflow: hidden;
        border: 1px solid black;
    }

    .category-selected{
        width: 300px;
        float: left;
        height: 250px;
        border-right: 1px solid;
        background-color: aliceBlue;

    }

    .category-selected-text{
        background-color: aliceBlue;
        color: #E32;
        font-family: 'Gill Sans','lucida grande', helvetica, arial, sans-serif;
        font-size: 190%;
    }


    #path span{
        cursor: pointer;
    }


    form .required {
        font-weight: bold;
    }
    form .required label:after {
        color: #e32;
        content: '*';
        display:inline;
    }

    .required h3:after {
        color: #e32;
        content: '*';
        display:inline;
    }

    .requiredInput{
        background-color: #FFD1D1;
        border: 1px solid red;
    }
</style>

<?php //debug($this->request);  ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <?php
                if(isset($url_action)){
                    if($url_action == 'editar' || $url_action == 'editar_borrador'){
                        echo '<div id="current-menu" style="display:none;">'.$this->request->data['current-menu'].'</div>';
                    }
                }
            ?>

            <section id="category-selector" style="display: none;" >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- Category Selector -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row" id="category-selector">
                                    <div class="col-xs-12">
                                        <h1 class="page-header" style="margin-top: 0;">Seleccione una categoría <small>la que mejor se adapte al producto que desea publicar.</small></h1>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <ul id="path" class="breadcrumb" style=" border: 1px solid #CCC; padding: 6px 15px; ">
                                            <li class="active" >Publicar</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="well well-sm">
                                            <div id="menu-box"  style=" border: 1px solid #B9B9B9; height: 265px; border-top-left-radius: 6px 6px; border-top-right-radius: 6px 6px;border-bottom-left-radius: 6px 6px;border-bottom-right-radius: 6px 6px;overflow-x: scroll;overflow-y: hidden;" >
                                                <div id="menu" style="overflow: hidden;">
                                                    <?php
                                                    if(isset($base_menu)){
                                                        echo  '<div class="ulMenu" id="default-options">';
                                                        foreach($base_menu as $k => $v){
                                                            echo '<div class="liMenu" id="category-id-'.$v['Category']['id'].'"> '.$v['Category']['name'].'</div>';
                                                        }
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button id="add-content" type="button" class="btn btn-primary disabled" disabled="disabled">Confirmar y continuar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>




            <section id="add-product" style="display: none;">
                <div  class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Nueva Publicación</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-xs-12">

                                <?php
                                echo $this->Form->create('Product',  array('url' => "#",'role'=>'form'));
                                echo $this->Form->hidden('Product.id');
                                echo $this->Form->hidden('Product.category_id');
                                ?>

                                <!-- Actions -->
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-xs-12">
                                        <?php
                                        /*
                                            when URL IS:

                                            /publicar           -> $url_action = false
                                            /editar             -> $url_action = 'editar'
                                            /editar_borrador    -> $url_action = 'editar_borrador'

                                        */
                                        if(isset($url_action)){
                                            // edit
                                            if($url_action =='editar'){
                                                $status = $this->request->data['Product']['status'];
                                                ?>
                                                <button id="update" type="submit" class="btn btn-primary">Actualizar</button>
                                                <?php if($status){  // esta publicado,  por lo tanto el elemento activate_container debe esta oculto. ?>
                                                    <button id="pause" type="button" class="btn btn-default">Pausar</button>
                                                    <button id="activate" type="button" class="btn btn-default" style="display:none;">Activar</button>
                                                <?php }else{        // esta pausado,    por lo tanto el elemento pause_container debe esta oculto. ?>
                                                    <button id="pause" type="button" class="btn btn-default" style=" display:none;">Pausar</button>
                                                    <button id="activate" type="button" class="btn btn-default">Activar</button>
                                                <?php } ?>
                                                <button id="delete" class="btn btn-danger" type="button">Borrar</button>
                                                <div id="debugTime" style="padding-top: 10px; display:none;">La publicación se ha actualizado a las <span id="lastTimeSave"></span> (Hace <span id="minutesElapsed">0</span> minutos)</div>
                                            <?php
                                            }
                                            // newProduct, editDraft
                                            if($url_action =='editar_borrador' || $url_action == false){
                                                ?>
                                                <button id="publish"	class="btn btn-primary"	type="submit"   >Publicar</button>
                                                <button id="save-now" 	class="btn btn-success"	type="button" style="margin-left: 4px;"  >Guardar Ahora</button>
                                                <button id="discard"	class="btn btn-warning"	type="button" style="margin-left: 4px;"  >Descartar</button>
                                                <div id="debugTime" style="padding-top: 10px; display:none;">El borrador se ha guardado a las <span id="lastTimeSave"></span> (Hace <span id="minutesElapsed">0</span> minutos)</div>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- Breadcrumb -->
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-xs-12">
                                        <ul id="path2" class="breadcrumb" style=" border: 1px solid #CCC; padding: 6px 15px; margin-bottom: 0;">
                                            <li class="active" ></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Edit category -->
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-xs-12">
                                        <button id="edit-category" type="button" class="btn btn-default" >Editar Categoría</button>
                                    </div>
                                </div>

                                <hr>

                                <!-- Form ProductTitle -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="ProductTitle"><span class="glyphicon glyphicon-bookmark"></span> Titulo</label>
                                            <?php echo $this->Form->input('Product.title',array('label'=>false,'div'=>false,'class'=>'form-control','name'=>'ProductTitle','placeholder'=>'Eje: EVGA X79 Classified Intel Socket 2011 Quad Channel DDR3 32GB of DDR3 2133MHz+ 151-SE-E779-KR')); ?>
                                            <span class="help-block" style="display: none;">El campo título es obligatorio.</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form ProductBody -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="ProductBody"><span class="glyphicon glyphicon-book"></span> Descripción</label>
                                            <?php echo $this->Form->textarea('Product.body',array('label'=>false,'div'=>false,'rows'=>7,'class'=>'form-control','name'=>'ProductBody')); ?>
                                            <span class="help-block" style="display: none;"><p class="text-danger">El campo descripción es obligatorio</p></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form ProductPrice -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="ProductPrice"><span class="glyphicon glyphicon-tag"></span> Precio</label>
                                            <div class="input-group col-xs-4">
                                                <div class="input-group-addon">BsF</div>
                                                <?php echo $this->Form->input('Product.price',array('label'=>false,'div'=>false,'type'=>'number','class'=>'form-control','name'=>'ProductPrice','placeholder'=>'Eje: 1000')); ?>
                                            </div>
                                            <span class="help-block" style="display: none;">El campo precio es obligatorio</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form ProductQuantity -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="ProductQuantity"><span class="glyphicon glyphicon-th"></span> Cantidad disponible</label>
                                            <div class="input-group col-xs-4">
                                                <div class="input-group-addon">Unidades</div>
                                                <?php echo $this->Form->input('Product.quantity',array('label'=>false,'div'=>false,'type'=>'number','class'=>'form-control','name'=>'ProductQuantity','placeholder'=>'Eje: 100')); ?>
                                            </div>
                                            <span class="help-block" style="display: none;">El campo cantidad disponible es obligatorio</span>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                echo $this->Form->end();
                                ?>


                            </div>
                        </div><!-- End .row -->
                    </div><!-- End .panel-body -->
                    <!-- Images -->
                    <ul class="list-group">
                        <li class="list-group-item" >
                            <h3 id="panels" class="page-header" style="margin-top: 10px;">Imágenes  <small><button id="continue-upload" type="button" class="btn btn-link clickable" style="display: none;">¡Añadir mas imágenes!</button> <button id="upload-all" type="button" class="btn btn-link" style="display: none;">¡Subir las imágenes!</button></small></h3>
                            <div id="previews" class="well dropzone-previews" style="margin-bottom: 10px;">
                                <button id="first-files" class="btn btn-primary clickable">Selecciona las imágenes desde la computadora</button>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="alert alert-warning" role="alert">
                    <ul>
                        <li>Al escribir el titulo por favor sigue esta convención: Marca - Nombre - Características relevantes - Numero de parte o Modelo.</li>
                        <li>Las imágenes son de carácter obligatorio. De no tener al menos una imagen cargada, el sistema no mostrará la publicación a los clientes.</li>
                    </ul>
                </div>
            </section><!-- End #add-product -->


        </div>
    </div>
</div>


<!-- light box para visualizar mejor la imagen cargada de la publicación o borrador.
--------------------------------------------------------------------------------------------------------------->
<a href="" id="image-product"  style="display: none;"></a>


<?php

    // CSS
    $css = array();

    //  Redactor http://imperavi.com/redactor/
    array_push($css,'/resources/library-vendor/redactor/redactor.css');

    //  lightbox https://github.com/ashleydw/lightbox
    array_push($css,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.css');

    // dropzone - https://github.com/enyo/dropzone
    array_push($css,'/resources/library-vendor/dropzone/css/dropzone.css');

    array_push($css,'/resources/app/css/base.css');

    $this->Html->css($css, null, array('inline' => false));

    // JS
    $scripts = array();

    //  Redactor - http://imperavi.com/redactor/
    array_push($scripts,'/resources/library-vendor/redactor/redactor.min.js');
    array_push($scripts,'/resources/library-vendor/redactor/langs/es.js');

    //  Ekko Lightbox  - https://github.com/ashleydw/lightbox
    array_push($scripts,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.js');

    //  jQuery Validation Plugin - https://github.com/jzaefferer/jquery-validation
    //  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js');
    //  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js');
    array_push($scripts,'/resources/library-vendor/jquery-validate/jquery.validate.js');
    array_push($scripts,'/resources/library-vendor/jquery-validate/additional-methods.js');

    //  Purl - https://github.com/allmarkedup/purl
    //  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/purl/2.3.1/purl.min.js');
    array_push($scripts,'/resources/library-vendor/purl/purl.js');

    //  dropzone  - https://github.com/sciactive/pnotify
    array_push($scripts,'/resources/library-vendor/dropzone/dropzone.js');

    array_push($scripts,'/resources/app/js/base.publicar.js');

    echo $this->Html->script($scripts,array('inline' => false));

?>