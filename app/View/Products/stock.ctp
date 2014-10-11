<?php
//    debug($this->{'request'});
    $data = $this->{'request'}->{'data'};
?>

<!-- Content
===================== -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <!-- start content-->

            <div class="row" id="no-products" style="display: none;">
                <div class="col-xs-12">
                    <div  class="alert alert-info" role="alert" >
                        <?php echo ucfirst($data['User']['name']);  ?> no tiene productos en existencia
                    </div>
                </div>
            </div>
            <div class="row" id="yes-products" style="display: none;">
                <div class="col-xs-12">

                    <div class="row">
                        <div class="col-xs-12">
                            <h1 id="type" class="page-header" style="margin-top: 0; background: url('/resources/app/img/benjaminFranklin.jpg') no-repeat center;padding-top: 100px;padding-left: 20px;f: white;-webkit-background-size: cover;   -moz-background-size: cover;   -o-background-size: cover;   background-size: cover;border: 1px black double;"><a href="/stock/<?php echo $data['User']['id']; ?>" style="text-shadow: 0 0 3px rgba(0,0,0,.8); color: #fff;"><?php echo ucfirst($data['User']['name']);  ?> Stock</a></h1>

                            <div
                                class="fb-like"
                                data-send="true"
                                data-width="450"
                                data-show-faces="true">
                            </div>
                            <hr>
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

                    <div id="products" style="overflow: hidden;" ></div>

                    <hr>
                    <div class="fb-comments" data-href="http://www.santomercado.com/stock/<?php echo $this->{'request'}->{'data'}['User']['id']; ?>" data-numposts="5" data-colorscheme="light" data-width="100%"></div>

                </div>
            </div>






            <!-- end content-->
        </div>
    </div>
</div>



<?php

    // CSS
    $css = array();

    array_push($css,'/resources/app/css/stock.css');
    array_push($css,'/resources/app/css/base.css');

    $this->Html->css($css, null, array('inline' => false));

    // JS
    $scripts = array();

    //  jQuery Validation Plugin - https://github.com/jzaefferer/jquery-validation
    //  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js');
    //  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js');
    array_push($scripts,'/resources/library-vendor/jquery-validate/jquery.validate.js');
    array_push($scripts,'/resources/library-vendor/jquery-validate/additional-methods.js');

    //  Purl - https://github.com/allmarkedup/purl
    //  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/purl/2.3.1/purl.min.js');
    array_push($scripts,'/resources/library-vendor/purl/purl.js');

    array_push($scripts,'/resources/app/js/base.stock.js');

    echo $this->Html->script($scripts,array('inline' => false));

?>