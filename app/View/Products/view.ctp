<?php
//    debug($this->{'request'});
//    debug($this->{'request'});
    $data = $this->{'request'}->{'data'};
?>

<!-- Content
===================== -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <!-- start content-->

            <h1 id="type" class="page-header" style="margin-top: 0; background: url('/resources/app/img/benjaminFranklin.jpg') no-repeat center;padding-top: 300px;padding-left: 20px;f: white;-webkit-background-size: cover;   -moz-background-size: cover;   -o-background-size: cover;   background-size: cover;border: 1px black double;">
                <a href="<?php echo '/stock/'.$data['User']['id']; ?>" style="text-shadow: 0 0 3px rgba(0,0,0,.8); color: #fff;"><?php echo ucfirst($data['User']['name']);  ?> Stock</a>
                <?php
                    if(isset($userLogged)){
                        echo '- <a id="change-banner" href="#" style="text-shadow: 0 0 3px rgba(0,0,0,.8); color: #fff;" class="rotate"><i class="fa fa-camera"></i></a>';
                    }
                ?>
            </h1>


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
                        <hr style="margin-top: 10px; margin-bottom: 15px;">
                        <?php
                        // lazy solution
                        $foo    = trim($data['Product']['title']);
                        $foo    = strtolower($foo);
                        $foo    = str_replace('/', '',$foo);
                        $foo    = preg_replace( '/\s+/', ' ', $foo);
                        $title  = str_replace(' ', '-',$foo);

                        echo '<div class="fb-comments" data-href="http://www.santomercado.com/producto/'.$data['Product']['id'].'/'.$title.'.html" data-numposts="5" data-colorscheme="light" data-width="100%"></div>';

                        ?>




                    </li>
                </ul>
            </div>

       </div>
    </div>
</div>

<?php

    // CSS
    $css = array();

    if(isset($userLogged)){
        array_push($css,'/resources/library-vendor/hover/hover-min.css');
    }

    //  lightBox https://github.com/ashleydw/lightbox
    array_push($css,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.css');

    array_push($css,'/resources/app/css/base.css');

    $this->Html->css($css, null, array('inline' => false));

    // JS
    $scripts = array();

    //  lightbox  - https://github.com/ashleydw/lightbox
    array_push($scripts,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.js');

    if(isset($userLogged)){
        array_push($scripts,'/resources/app/js/base.banners.js');
    }

    echo $this->Html->script($scripts,array('inline' => false));

?>