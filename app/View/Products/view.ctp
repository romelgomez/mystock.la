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

            <div class="page-header" style="margin-top: 0;">
                <h1 style="margin-top: 0;"><?php  echo ucfirst($data['Product']['title']) ?></h1>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Imágenes:</h3>
                </div>
                <div class="panel-body">
                    <div class="row" style="margin: 0;">

                        <?php foreach($this->request->data['Image'] as $index => $imagen){ ?>

                                <a href="/resources/app/img/products/<?php echo $imagen['large']; ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo ucfirst($data['Product']['title']) ?>" class="col-md-2 thumbnail" style=" margin: 5px;">
                                    <img src="/resources/app/img/products/<?php echo $imagen['small']; ?>" class="img-responsive">
                                </a>

                        <?php } ?>

                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Descripción:</h3>
                </div>
                <div class="panel-body">
                    <?php  echo ucfirst($data['Product']['body']) ?>
                </div>
            </div>

            <div class="panel panel-warning">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h3 class="panel-title">Detalles:</h3>
                </div>

                <!-- List group -->
                <ul class="list-group">
                    <li class="list-group-item bg-primary">
                        <h2 class="text-info">
                            <span class="glyphicon glyphicon-tag"></span>
                            Precio: $<?php echo $data['Product']['price'] ?>
                        </h2>
                    </li>
                    <li class="list-group-item bg-primary">
                        <h4 class="text-info">
                            <a href="<?php echo '/stock/'.$data['User']['id']; ?>"><?php echo ucfirst($data['User']['name']);  ?> Stock</a>
                        </h4>
                    </li>
                </ul>
            </div>

       </div>
    </div>
</div>

<?php

    // CSS
    $css = array();

    //  lightBox https://github.com/ashleydw/lightbox
    array_push($css,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.css');

    array_push($css,'/resources/app/css/base.css');

    $this->Html->css($css, null, array('inline' => false));

    // JS
    $scripts = array();

    //  lightbox  - https://github.com/ashleydw/lightbox
    array_push($scripts,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.js');

    echo $this->Html->script($scripts,array('inline' => false));

?>