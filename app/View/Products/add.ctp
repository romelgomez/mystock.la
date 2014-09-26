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

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <?php

                if(isset($url_action)){
                    if($url_action == 'editar' || $url_action == 'editar_borrador'){
                        echo '<div id="current-menu" style="display:none;">'.$this->request->data['current-menu'].'</div>';
                    }
                }

                echo $this->Form->create('Product',  array('url' => "#", 'type' => 'file','role'=>'form'));
                echo $this->Form->hidden('Product.id');
                echo $this->Form->hidden('Product.category_id');


            ?>

            <div class="row" id="category-selector" style="display: none;">
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

            <div class="row" id="add-product" style="display: none;">
                <div class="col-xs-12">

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

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">
                            <ul id="path2" class="breadcrumb" style=" border: 1px solid #CCC; padding: 6px 15px; margin-bottom: 0;">
                                <li class="active" >Publicar</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12">
                            <button id="edit-category" type="button" class="btn btn-default" >Editar Categoría</button>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="ProductTitle"><span class="glyphicon glyphicon-bookmark"></span> Titulo</label>
                                <?php echo $this->Form->input('Product.title',array('label'=>false,'div'=>false,'class'=>'form-control','name'=>'ProductTitle','placeholder'=>'Eje: EVGA X79 Classified Intel Socket 2011 Quad Channel DDR3 32GB of DDR3 2133MHz+ 151-SE-E779-KR')); ?>
                                <span class="help-block" style="display: none;">El campo título es obligatorio.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="ProductBody"><span class="glyphicon glyphicon-book"></span> Descripción</label>
                                <?php echo $this->Form->textarea('Product.body',array('label'=>false,'div'=>false,'rows'=>7,'class'=>'form-control','name'=>'ProductBody')); ?>
                                <span class="help-block" style="display: none;"><p class="text-danger">El campo descripción es obligatorio</p></span>
                            </div>
                        </div>
                    </div>

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

                    <div class="row">
                        <div class="col-xs-12">

                            <?php
                                if(isset($this->request->data['Image']) && count($this->request->data['Image']) > 0){
                                    // hay imágenes cargadas.
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> Imágenes: <a id="continue-upload" href="#"  >Añadir mas</a>
                                                </div>
                                                <div class="panel-body" style="cursor: pointer;">

                                                    <div id="start-upload" style="height: 212px;  overflow: hidden;  display:none;">
                                                        <div style="overflow: hidden; padding-top: 25px; text-align: center;">
                                                            <img src="/resources/app/img/sube_imagenes.png">
                                                        </div>
                                                    </div>
                                                    <div id="product_thumbnails">
                                                        <?php
                                                        foreach($this->request->data['Image'] as $index => $imagen){
                                                            ?>

                                                            <a id="thumbnail-id-<?php echo $imagen['original']['id']; ?>" style="overflow: hidden; width: 200px; height: 200px; float: left; margin: 5px;">
                                                                <div style="overflow: hidden; width: 200px; height: 200px; z-index: 0; position: relative;">
                                                                        <img src="/resources/app/img/products/<?php echo $imagen['thumbnails']['small']['name']; ?>" class="img-thumbnail">
                                                                </div>
                                                                <div class="disable-this-product-thumbnail" style="overflow: hidden; z-index: 1; margin-top:-200px; position: relative; float: right; cursor: pointer;">
                                                                    <img style="width: 24px;" src="/resources/app/img/x.png">
                                                                </div>
                                                                <div class="view-this-product-thumbnail" style="overflow: hidden; z-index: 1; margin-top:-120px; margin-left: 80px; position: relative;  padding-right: 2px; padding-top: 2px; width: 32px; height: 32px; cursor: pointer;">
                                                                    <img src="/resources/app/img/view.png">
                                                                </div>
                                                                <div style="display:none;"><?php echo json_encode($imagen); ?></div>
                                                            </a>

                                                        <?php
                                                        }
                                                        ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }else{
                                    // No hay imágenes cargadas
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> Imágenes: <a id="continue-upload" style="display:none" href="#"  >Añadir mas</a>
                                                </div>
                                                <div class="panel-body" style="cursor: pointer;">

                                                    <div id="start-upload" style="height: 212px;  overflow: hidden;">
                                                        <div style="overflow: hidden; padding-top: 25px; text-align: center;">
                                                            <img src="/resources/app/img/sube_imagenes.png">
                                                        </div>
                                                    </div>
                                                    <div id="product_thumbnails" style="display:none;"></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning" role="alert">
                                <ul>
                                    <li>Al escribir el titulo por favor sigue esta convención: Marca - Nombre - Características relevantes - Numero de parte o Modelo.</li>
                                    <li><strong>Todos</strong> los campos excepto <b>subtítulo</b> son requeridos para publicar. Pero no para guardar un borrador.</li>
                                    <li>Las imágenes son de carácter obligatorio. De no tener al menos una imagen cargada, el sistema no mostrará la publicación a los clientes.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">


                        </div>
                    </div>


                </div>
            </div>

            <?php
                echo $this->Form->end();
            ?>



<!--            <button class="demo btn btn-primary btn-large" data-toggle="modal" href="#uploading-pictures">View Demo</button>-->



        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="uploading-pictures" tabindex="-1" role="dialog" aria-labelledby="uploadingPicturesLabel" aria-hidden="true">
    <form id="UserAddForm" action="#" method="post" accept-charset="utf-8">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Form header-->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Carga imágenes del producto o servicio</h4>
                </div>
                <!-- Form body-->
                <div class="modal-body">
                    <div id="drop-files" class="drop-element" style="overflow: auto;padding: 10px; height: 600px; border: 2px dashed #DCDCDC; border-radius: 5px 5px;">

                        <div id="optional-selection-container" style=" overflow: hidden; margin-top: 200px;">
                            <div class="row-fluid">
                                <div class="span4"></div>
                                <div class="span4">
                                    <div style="text-align: center;">
                                        <div style="opacity: 0.2;overflow: hidden;padding: 10px;" ><h3 style="margin: 0;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-weight: normal;" >Suelta las imágenes aquí</h3></div>
                                        <div style="height: 27px;" ><span style="opacity: 0.2;">O si prefieres...</span></div>
                                        <div><button type="submit" class="btn btn-primary" style="position: relative; overflow: hidden; direction: ltr; ">Selecciona las imágenes desde la computadora<input id="first-files" multiple="multiple" type="file" name="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0; "></button></div>
                                    </div>
                                </div>
                                <div class="span4"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Form footer-->
                <div class="modal-footer">
                    <div class="pull-left">
                        <button id="second-files-button" type="submit" class="btn btn-primary" style="position: relative; overflow: hidden; direction: ltr; display:none;">Añadir mas<input id="second-files" multiple="multiple" type="file" name="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0; "></button>
                    </div>
                    <div class="pull-right">
                        <button id="save-this" class="btn btn-primary" disabled="disabled"  >Confirmar la imágenes</button>
                        <button id="cancel-this" class="btn"  data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- light box para visualizar mejor la imagen cargada de la publicación o borrador.
--------------------------------------------------------------------------------------------------------------->
<a href="" id="image-product"  style="display: none;"></a>


<?php

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

    array_push($scripts,'/resources/app/js/base.publicar.js');

    echo $this->Html->script($scripts,false);

?>