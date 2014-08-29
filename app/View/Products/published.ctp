<!-- Content
===================== -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <!-- start content-->

            <div class="row" id="no-products" style="display: none;">
                <div class="col-xs-12">
                    <div  class="alert alert-warning" role="alert" >
                        <strong>!Alerta!</strong> no hay productos publicados aun. <a href="/publicar" class="alert-link" >Añade un nuevo producto!</a>
                    </div>
                </div>
            </div>
            <div class="row" id="yes-products" style="display: none;">
                <div class="col-xs-12">

                    <div class="row">
                        <div class="col-xs-12">
                            <h1 id="type" class="page-header" style="margin-top: 0;">Publicados</h1>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-6 col-sm-4">
                            <!-- búsqueda.
                            ------------------------------------------------------------------------------------------>
                            <form role="form" id="SearchPublicationsForm">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" placeholder="Eje: Laptops">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">Buscar</button>
                                </span>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="col-xs-6 col-sm-4"></div>
                        <div class="col-xs-6 col-sm-4">

                            <div class="pull-right">

                                <!-- Información.
                               -------------------------------------------------------------------------------------->
                                <div id="pagination-info" style="overflow: hidden; float: left; margin-right: 10px; line-height: 30px; ">
                                    <span></span>
                                </div>

                                <!-- Paginación.
                                -------------------------------------------------------------------------------------->
                                <div id="pagination" style="display:none; overflow: hidden;  float: left;"   >
                                    <div style=" float: left; margin-right: 10px; ">
                                        <div class="btn-group" >
                                            <button id="prev-page" class="btn btn-default disabled" disabled><i class="icon-chevron-left"></i> Anterior</button>
                                            <button id="next-page" class="btn btn-default disabled" disabled><i class="icon-chevron-right"></i> Próximo</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ordenar por
                                ---------------------------------------------------------------------------------------->
                                <div id="order-by" style="display:none; float: left; margin-right: 10px; ">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Ordenar por <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                            <li><a id="recent" href="#"><span class="glyphicon glyphicon-time"></span> Recientes</a></li>
                                            <li><a id="oldest" href="#"><span class="glyphicon glyphicon-calendar"></span> Antiguos</a></li>

                                            <li class="divider"></li>

                                            <li><a id="higher-price" href="#"><span class="glyphicon glyphicon-tags"></span> Mayor precio</a></li>
                                            <li><a id="lower-price" href="#"><span class="glyphicon glyphicon-tag"></span> Menor precio</a></li>

                                            <li class="divider"></li>

                                            <li><a id="higher-availability" href="#"><span class="glyphicon glyphicon-th"></span> Mayor disponibilidad</a></li>
                                            <li><a id="lower-availability" href="#"><span class="glyphicon glyphicon-th-large"></span> Menor disponibilidad</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <hr>

                    <div id="products" ></div>

                    <hr style="margin-bottom: 0">

                </div>
            </div>






            <!-- end content-->
        </div>
    </div>
</div>


<!-- Modal para borrar la publicación -->
<div id="delete_product_modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">¿Realmente quieres borrar esta publicación?</h4>
            </div>
            <div class="modal-body">
                Un alternativa mejor es pausar la publicación y activarla cuando se normalice el inventario o evento sobrevenido, pausar la publicación permitirá que la publicidad y contactos estén disponible a los clientes, teniendo claro que la publicación quedará inhabilitada para ser ofertada a través del sistema.
            </div>
            <div class="modal-footer">
                <button id="delete_product" type="button" class="btn btn-danger">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->script('base.publicados',false); ?>
