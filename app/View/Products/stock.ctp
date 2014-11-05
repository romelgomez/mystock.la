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
                        <?php echo ucfirst($data['User']['name']);  ?> have no products available
                    </div>
                </div>
            </div>
            <div class="row" id="yes-products" style="display: none;">
                <div class="col-xs-12">

                    <div class="row">
                        <div class="col-xs-12">

                            <?php

                                if($data['User']['banner'] !== NULL){
                                    $banner = '/resources/app/img/banners/'.$data['User']['banner'];
                                }else{
                                    $banner = '/resources/app/img/benjaminFranklin.jpg';
                                }

                            ?>

                            <h1 id="banner" class="page-header" style="margin-top: 0; background: url('<?php echo $banner; ?>') no-repeat center;padding-top: 300px;padding-left: 20px;f: white;-webkit-background-size: cover;   -moz-background-size: cover;   -o-background-size: cover;   background-size: cover;border: 1px black double;">
                                <a href="/stock/<?php echo $data['User']['id']; ?>" style="text-shadow: 0 0 3px rgba(0,0,0,.8); color: #fff;"><?php echo ucfirst($data['User']['name']);  ?> Stock</a>
                                <?php
                                    if(isset($userLogged)){
                                        echo '- <a id="change-banner" href="#" style="text-shadow: 0 0 3px rgba(0,0,0,.8); color: #fff;" class="rotate"><i class="fa fa-camera"></i></a>';
                                    }
                                ?>
                            </h1>

                            <hr style="margin-top: 10px; margin-bottom: 15px;">
                            <div class="pw-widget pw-counter-vertical">
                                <a class="pw-button-facebook pw-look-native"></a>
                                <a class="pw-button-twitter pw-look-native"></a>
                                <a class="pw-button-googleplus pw-look-native"></a>
                                <a class="pw-button-pinterest pw-look-native"></a>
                                <a class="pw-button-linkedin pw-look-native"></a>
                                <a class="pw-button-tumblr pw-look-native"></a>
                                <a class="pw-button-reddit pw-look-native"></a>
                                <a class="pw-button-blogger pw-look-native"></a>
                                <a class="pw-button-post-share"></a>
                                <a class="pw-button-email pw-look-native"></a>
                            </div>
                            <script src="http://i.po.st/static/v3/post-widget.js#publisherKey=4a68gt2qi4hhevvdnlj5&retina=true" type="text/javascript"></script>


                            <hr>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <!-- búsqueda. -->
                            <form role="form" id="SearchPublicationsForm">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" placeholder="Eje: Laptops">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">Search</button>
                                </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
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
                                            <button id="prev-page" class="btn btn-default disabled" disabled><i class="icon-chevron-left"></i> Previous</button>
                                            <button id="next-page" class="btn btn-default disabled" disabled><i class="icon-chevron-right"></i> Next</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ordenar por
                                ---------------------------------------------------------------------------------------->
                                <div id="order-by" style="display:none; float: left; margin-right: 10px; ">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Sort by:  <span id="order-by-text">Recent</span> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                            <li><a id="recent" href="#"><span class="glyphicon glyphicon-time"></span> Recent</a></li>
                                            <li><a id="oldest" href="#"><span class="glyphicon glyphicon-calendar"></span> Antique</a></li>

                                            <li class="divider"></li>

                                            <li><a id="higher-price" href="#"><span class="glyphicon glyphicon-tags"></span> Higher price</a></li>
                                            <li><a id="lower-price" href="#"><span class="glyphicon glyphicon-tag"></span> Lowest price</a></li>

                                            <li class="divider"></li>

                                            <li><a id="higher-availability" href="#"><span class="glyphicon glyphicon-th"></span> Greater availability</a></li>
                                            <li><a id="lower-availability" href="#"><span class="glyphicon glyphicon-th-large"></span> Lower availability</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <hr>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Stock</h3>
                        </div>
                        <div id="products" class="panel-body"></div>
                    </div>

<!--                    <div id="products" style="overflow: hidden;" ></div>-->

                </div>
            </div>






            <!-- end content-->
        </div>
    </div>
</div>

    <!-- seller banners if exist-->
    <div id="banners" style="display: none"><!--<?php
        if(isset($banners)){
            echo json_encode($banners);
        }
        ?>--></div>


<!-- Add banners modal -->
    <div id="change-banner-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Change the banner</h4>
                </div>
                <div class="modal-body">

                    <div id="previews" class="well dropzone-previews" style="margin-bottom: 10px;">
                        <button id="first-files" class="btn btn-primary clickable">Select the images from the computer</button>
                    </div>

                    <div class="alert alert-info" role="alert" style="margin-bottom: 0;">Note: If you delete the banner selected, must select another one if you want change the banner.</div>

                </div>
                <div class="modal-footer">
                    <div class="pull-left">
                        <button id="continue-upload" type="button" class="btn btn-link clickable" style="display: none;" >Add more pictures!</button>
                        <button id="upload-all" type="button" class="btn btn-link" style="display: none;" >Upload images!</button>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="reload-the-page" type="button" class="btn btn-primary" style="display: none;">Reload the page to complete the changes!</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<?php

    // CSS
    $css = array();

    if(isset($userLogged)){
        array_push($css,'/resources/library-vendor/hover/hover-min.css');

        // dropzone - https://github.com/enyo/dropzone
        array_push($css,'/resources/library-vendor/dropzone/css/dropzone.css');
    }

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


    if(isset($userLogged)){
        //  dropzone  - https://github.com/sciactive/pnotify
        array_push($scripts,'/resources/library-vendor/dropzone/dropzone.js');

        array_push($scripts,'/resources/app/js/base.banners.js');
    }


    array_push($scripts,'/resources/app/js/base.stock.js');

    echo $this->Html->script($scripts,array('inline' => false));

?>