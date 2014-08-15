<!-- Content
===================== -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <!-- start content-->

            <div id="no-products" style="display:none;  margin-bottom: 10px;">
                <div class="alert alert-warning" role="alert">
                    <strong>Alerta!</strong> no hay productos publicados aún. <a href="/publicar">Añade un nuevo producto!</a>
                </div>
            </div>


            <!--                    <div id="searching" style="display:none; overflow: hidden; float: left; margin-right: 10px;" >-->
            <!--                        <form action="#" id="SearchPublicationsForm" method="post" accept-charset="utf-8">-->
            <!--                            <div class="control-group" style="margin-bottom: 0px;">-->
            <!--                                <div class="controls" style=" display: table-row; ">-->
            <!--                                    <div class="input-append" style="overflow: hidden;margin-bottom: 0;float: left;">-->
            <!--                                        <input id="search" name="search" type="text" placeholder="Eje: Laptops" title="Buscar"><button class="btn" type="submit"><i class="icon-search"></i></button>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </form>-->
            <!--                    </div>-->

<!--            <div class="form-group has-success">-->
<!--                <label class="control-label" for="inputSuccess1">Input with success</label>-->
<!--                <input type="text" class="form-control" id="inputSuccess1">-->
<!--            </div>-->

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

            <div id="published" ></div>

            <hr style="margin-bottom: 0">

            <!-- end content-->
        </div>
    </div>
</div>


<!-- Modal para borrar la publicación
---------------------------------------------------------------------------------------------------------------->
<!--<div class="modal hide fade" id="delete_product_modal"  style="position: fixed;"  >-->
<!--    <div class="alert alert-block alert-error"  style="margin-bottom: 0px; border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">-->
<!--        <h4 class="alert-heading">¿Realmente quieres borrar esta publicación?</h4>-->
<!--        <h5 class="muted" style=" margin-bottom: 0px; " >Un alternativa mejor es pausar la publicacion y activarla cuando se normalice el inventario o evento sobrevenido,-->
<!--            pausar la publicacion permitira que la publicidad y contactos esten disponible al los clientes, teniendo claro que la publicación quedara inavilitada para ser ofertada a traves del sistema.</h5>-->
<!--    </div>-->
    <!-- Form footer
    ------------------------------------>
<!--    <div class="modal-footer">-->
<!--        <button  id="delete_product" class="btn btn-danger" >Confirmar</button>-->
<!--        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>-->
<!--    </div>-->
<!--</div>-->






<?php echo $this->Html->script('purl',false); ?>
<?php echo $this->Html->script('base.publicados',false); ?>
