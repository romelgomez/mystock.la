<?php echo $this->Html->css('jqtree',false); ?>

<div id="total-categories" style="display:none;"><?php if(isset($categories)){ echo $categories['total']; } ?></div>


<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <!-- Tree -->
            <div id="tree" style="display:none;" >
                <div class="row-fluid">
                    <div class="span12">

                        <div class="btn-toolbar" style="margin-top: 0;">
                            <button type="button" class="new_category btn btn-primary">Añade una nueva categoría!</button>
                            <div id="admin_category" class="btn-group">
                                <a id="edit_category_name"	class="btn disabled" ><i class="icon-edit"></i> Editar</a>
                                <a id="delect_category" 	class="btn disabled" ><i class="icon-remove-sign"></i> Borrar</a>
                            </div>
                        </div>

                        <div class="alert alert-info" style="margin-bottom: 10px;">
                            <strong>Para modificar la posición: </strong> Arrastra y suelta la categoría.<br>
                            <strong>Para editar o borrar:</strong> Selecciona la categoría y luego selecciona la acción.
                            <br>
                            <b>Convenciones o costumbres:</b>
                            <ul>
                                <li>Departamentos.
                                    <ul>
                                        <li>Categorías Generales.
                                            <ul>
                                                <li>Categorías Especificas.</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div id="json_tree" style="display:none;"><?php if(isset($categories['categories'])){ echo $categories['categories']; } ?></div>

                <div style="overflow: hidden;border: 1px solid black;background-color: whiteSmoke; border: 1px solid #E3E3E3; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05); -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);padding: 10px 10px 10px 0;" >
                    <div id="display_tree"></div>
                </div>
            </div>
            <!-- no Tree -->
            <div id="no-tree" style="display:none;">
                <div class="alert">
                    <a href="#" data-dismiss="alert" class="close">×</a>
                    <strong>Alerta!</strong> no hay categorías cargadas aún. <a class="new_category" href="#">Añade una categoría!</a>
                </div>
            </div>

        </div>
    </div>
</div>






<!-- Modal Nueva Categoría
===================== -->
<div class="modal hide fade" id="new_category_modal"  >
    <form id="CategoryAddForm" action="#" method="post" accept-charset="utf-8">

        <!-- Form header
        ------------------------------------>
        <div class="modal-header">
            <a href="#" class="close" data-dismiss="modal" style="border: none;float: right;"><img src="./img/x2.png" title="Cancelar" style="width: 24px;"></a>
            <h3>Nueva categoría</h3>
        </div>

        <!-- Form body
        ------------------------------------>
        <div class="modal-body">

            <!-- Mensajes post ajax request
            ------------------------------------>
            <div class="alert alert-success" 		style="display:none">
                <button type="button" class="close" data-dismiss="alert">x</button>
                ¡Se ha registrado la categoría!
            </div>
            <div class="alert alert-error fade in"	style="display:none">
                <button type="button" class="close" data-dismiss="alert">x</button>
                ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
            </div>

            <!-- Form inputs
            ------------------------------------>
            <div class="control-group">
                <label class="control-label" for="CategoryName">Nombre</label>
                <div class="controls">
                    <div class="input-prepend" style="display: inline;">
                        <span class="add-on"><i class="icon-user"></i></span><input type="text" id="CategoryName" name="CategoryName" placeholder="Eje: Laptops">
                    </div>
                    <span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
                </div>
            </div>

        </div>

        <!-- Form footer
        ------------------------------------>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" >Enviar</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        </div>

    </form>
</div>

<!-- Modal para editar nombre de la categoría
===================== -->
<div class="modal hide fade" id="edit_category_name_modal"  >
    <form id="CategoryEditForm" action="#" method="post" accept-charset="utf-8">

        <!-- Form header
        ------------------------------------>
        <div class="modal-header">
            <a href="#" class="close" data-dismiss="modal" style="border: none;float: right;"><img src="./img/x2.png" title="Cancelar" style="width: 24px;"></a>
            <h3>Editar el nombre de la categoría</h3>
        </div>

        <!-- Form body
        ------------------------------------>
        <div class="modal-body">

            <!-- Mensajes post ajax request
            ------------------------------------>
            <div class="alert alert-success" 		style="display:none">
                <button type="button" class="close" data-dismiss="alert">x</button>
                ¡Se ha editado la categoría!
            </div>
            <div class="alert alert-error fade in"	style="display:none">
                <button type="button" class="close" data-dismiss="alert">x</button>
                ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
            </div>

            <!-- Form inputs
            ------------------------------------>
            <input type="text" id="EditCategoryId" name="EditCategoryId" style="display:none;">

            <div class="control-group">
                <label class="control-label" for="EditCategoryName">Nombre</label>
                <div class="controls">
                    <div class="input-prepend" style="display: inline;">
                        <span class="add-on"><i class="icon-user"></i></span><input type="text" id="EditCategoryName" name="EditCategoryName" placeholder="Eje: Laptops">
                    </div>
                    <span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
                </div>
            </div>

        </div>

        <!-- Form footer
        ------------------------------------>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" >Actualizar</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        </div>

    </form>
</div>


<!-- Modal para editar borrar la categoría
===================== -->
<div class="modal hide fade" id="delect_category_modal"  >
    <form id="CategoryDelectForm" action="#" method="post" accept-charset="utf-8">

        <!-- Form inputs
        ------------------------------------>
        <input type="text" id="DelectCategoryId" name="DelectCategoryId" style="display:none;">

        <div class="alert alert-block alert-error"  style="margin-bottom: 0; border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
            <h4 class="alert-heading">¿Realmente quieres borrar esta categoría?</h4>

            <div style="margin: 10px 0 0 0;" ><i class="icon-play  icon-white"></i> <strong id="delect-category-name"></strong></div>

        </div>

        <!-- Form footer
        ------------------------------------>
        <div class="modal-footer">
            <button id="DelectCategoryBranch" type="button" class="btn btn-danger" data-toggle="button" style="display:none;" >Y categorías hijas también</button>
            <button type="submit" class="btn btn-danger" >Confirmar</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        </div>

    </form>
</div>



<?php echo $this->Html->script('tree.jquery',false); ?>
<?php echo $this->Html->script('jquery.cookie',false); ?>
<?php echo $this->Html->script('base.categorias',false); ?>
