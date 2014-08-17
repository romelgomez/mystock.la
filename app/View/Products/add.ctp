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

                echo $this->Form->create('Product',  array('url' => "/newProduct", 'type' => 'file','role'=>'form'));
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
                                <span class="help-block">El campo título es obligatorio.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="ProductTitle"><span class="glyphicon glyphicon-book"></span> Descripción</label>
                                <?php echo $this->Form->textarea('Product.body',array('label'=>false,'div'=>false,'rows'=>7,'class'=>'form-control','name'=>'ProductBody','placeholder'=>'Eje: EVGA X79 es una tarjeta madre que ...')); ?>
                                <span class="help-block">El campo descipción es obligatorio</span>
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


        </div>
    </div>
</div>


<?php
//    debug($this->request);
//    debug($this->request->data['current-menu']);
?>

<?php echo $this->Html->script('base.publicar',false); ?>
