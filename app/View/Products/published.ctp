<style type="text/css">

    /* css temporal */
    /*.product-universal-container{*/
        /*border: 2px solid #333;*/
        /*overflow: hidden;*/
        /*text-decoration: none;*/
        /*height:120px;*/
        /*border-bottom-left-radius: 6px 6px ;*/
        /*border-bottom-right-radius: 6px 6px ;*/
        /*border-top-left-radius: 6px 6px ;*/
        /*border-top-right-radius: 6px 6px;*/
    /*}*/


    /*.product-universal-image{*/
        /*border-right: 1px solid black;*/
    /*}*/
    /*.product-universal-name{*/
        /*background: black;*/
        /*color: white;*/
        /*font-size: 17px;*/
        /*font-weight: bold;*/
        /*height: 22px;*/
        /*opacity: 0.65;*/
        /*border-top-right-radius:3px;*/
        /*padding-left: 5px;*/
        /*font-family:"Times New Roman",Georgia,Serif;*/
        /*overflow: hidden;*/
    /*}*/

    /*.product-universal-options{*/
        /*font-size: 15px;*/
        /*font-family:"Times New Roman",Georgia,Serif;*/
        /*padding:5px;*/
        /*overflow: hidden;*/
    /*}*/

    /*.product-universal-name:hover{*/
        /*background: #65b9bf;*/
    /*}*/

    /*#published div.media{*/
        /*border: 1px solid gray;*/
        /*padding: 10px;*/
        /*border-radius: 3px;*/
    /*}*/
    /*#published div.media:hover{*/
        /*border-color: #08C;*/
        /*-webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);*/
        /*-moz-box-shadow: 0 1px 4px rgba(0,105,214,0.25);*/
        /*box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);*/
    /*}*/


</style>

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

            <div class="row">
                <div class="col-xs-6 col-sm-4">
                    <!-- búsqueda.
                    ------------------------------------------------------------------------------------------>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Buscar</button>
                        </span>
                    </div><!-- /input-group -->
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
