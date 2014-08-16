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

            <div class="row" id="category-selector">
                <div class="col-xs-12">
                    <div class="row" id="category-selector">
                        <div class="col-xs-12">
                            <h1 class="page-header">Seleccione una categoría <small>la que mejor se adapte al producto que desea publicar.</small></h1>
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
</div>


<?php echo $this->Html->script('base.publicar',false); ?>
