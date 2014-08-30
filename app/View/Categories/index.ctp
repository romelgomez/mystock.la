<div id="json_tree" style="display:none;"><?php if(isset($categories['categories'])){ echo $categories['categories']; } ?></div>


<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

<!--            <hr style="margin-bottom: 0">-->

            <!-- Tree -->
            <div id="tree" style="display:none;" >

                <!-- controles -->
                <div class="row">
                    <div class="col-xs-12">


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Categorías de productos y servicios:</h3>
                            </div>
                            <div class="panel-body">

                                <div class="btn-toolbar" role="toolbar">
                                    <div id="admin_category" class="btn-group">
                                        <button id="edit_category_name" type="button" class="btn btn-default disabled">Editar</button>
                                        <button id="delect_category"  type="button" class="btn btn-default disabled">Borrar</button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary new_category">Añade una nueva categoría!</button>
                                    </div>
                                </div>

                                <h4 id="panels" class="page-header">Instrucciones:</h4>


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

                                <h4 id="panels" class="page-header">Categorías:</h4>

                                <div id="display_tree"></div>

                            </div>
                        </div>

                    </div>
                </div>



            </div>
            <!-- no Tree -->
            <div id="no-tree" style="display:none;">
                <div class="alert alert-info" role="alert">
                    <strong>Alerta!</strong> no hay categorías cargadas aún. <a class="new_category" href="#">Añade una categoría!</a>
                </div>
            </div>


        </div>
    </div>
</div>


<!-- Modal Nueva Categoría -->
<div class="modal fade" id="new_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="CategoryAddForm" action="#" method="post" accept-charset="utf-8">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title">Nueva categoría</h4>
                </div>

                <div class="modal-body">

                    <!-- Mensajes post ajax request -->
                    <div class="alert alert-success alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Se ha registrado la categoría!
                    </div>
                    <div class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
                    </div>

                    <div class="form-group">
                        <label for="CategoryName"><span class="glyphicon glyphicon-folder-close"></span> Nombre</label>
                        <input type="text" class="form-control" id="CategoryName" name="CategoryName" placeholder="Eje: Laptops">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>

            </form>
        </div>
    </div>
</div>



<!-- Modal para editar nombre de la categoría -->
<div class="modal fade" id="edit_category_name_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="CategoryEditForm" action="#" method="post" accept-charset="utf-8">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title">Editar el nombre de la categoría</h4>
                </div>

                <div class="modal-body">

                    <!-- Mensajes post ajax request -->
                    <div class="alert alert-success alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Se ha editado la categoría!
                    </div>
                    <div class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
                    </div>

                    <input type="hidden" id="EditCategoryId" name="EditCategoryId">

                    <div class="form-group">
                        <label for="EditCategoryName"><span class="glyphicon glyphicon-folder-close"></span> Nombre</label>
                        <input type="text" class="form-control" id="EditCategoryName" name="EditCategoryName" placeholder="Eje: Laptops">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>

            </form>
        </div>
    </div>
</div>



<!-- Modal para borrar la categoría -->
<div class="modal fade" id="delect_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="CategoryDelectForm" action="#" method="post" accept-charset="utf-8">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title">¿Realmente quieres borrar esta categoría?</h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="DelectCategoryId" name="DelectCategoryId">

                    <h3 class="text-danger" id="delect-category-name" style="margin-top: 0;"></h3>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="DelectCategoryBranch" type="checkbox"> Y categorías hijas también
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php echo $this->Html->script('base.categorias',false); ?>
