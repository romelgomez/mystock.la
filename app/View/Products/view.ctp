<?php $data = $this->{'request'}->{'data'}; ?>

<!-- Content
===================== -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <!-- start content-->

            <ol class="breadcrumb">
                <?php
                    $pathLength = sizeof($data['Path']);
                    foreach($data['Path'] as $index => $category){
                        echo '<li><a href="/search#category-'.strtolower($category['Category']['name']).'-'.$category['Category']['id'].'">'.$category['Category']['name'].'</a></li>';
                    }
                ?>
            </ol>
            <a href="<?php echo '/stock/'.$data['User']['id']; ?>">@<?php echo ucfirst($data['User']['name']);  ?> Stock</a>

            <hr>

            <div class="page-header" style="margin-top: 0;">
                <h1><?php  echo ucfirst($data['Product']['title']) ?></h1>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Imágenes:</h3>
                </div>
                <div class="panel-body">
                    <div class="row" style="margin: 0;">

                        <?php foreach($this->request->data['Image'] as $index => $imagen){ ?>

                                <a href="/img/products/<?php echo $imagen['original']['name']; ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo ucfirst($data['Product']['title']) ?>" class="col-md-2 thumbnail" style=" margin: 5px;">
                                    <img src="/img/products/<?php echo $imagen['thumbnails']['small']['name']; ?>" class="img-responsive">
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
                            Precio: <?php echo $data['Product']['price'] ?> BsF.
                        </h2>
                    </li>
                </ul>
            </div>

       </div>
    </div>
</div>