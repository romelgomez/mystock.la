<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    // facebook
    if(isset($url_action)){
//        debug($url_action);
        if($url_action == 'producto'){
            echo '<meta property="og:title" content="'.$this->{'request'}->{'data'}['Product']['title'].'" />';
            echo '<meta property="og:type" content="website" />';
            echo '<meta property="og:site_name" content="SantoMercado" />';
            echo '<meta property="og:description" content="A good some product" />';
            echo '<meta property="og:image" content="http://www.santomercado.net/resources/app/img/products/'.$this->request->data['Image'][0]['large'].'" />';
            echo '<meta property="og:app_id" content="599188036854190" />';

        }
    }
    ?>

    <link rel="icon" href="http://getbootstrap.com/favicon.ico">

    <title>SantoMercado.com</title>

    <?php
        $css = array();

        //  Bootstrap
        //  array_push($scripts,'//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css');
        array_push($css,'/resources/library-vendor/bootstrap/css/bootstrap.css');

        //  font-awesome
        //  array_push($scripts,'//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
        array_push($css,'/resources/library-vendor/font-awesome/css/font-awesome.min.css');

        //  Pnotify https://github.com/sciactive/pnotify
        //  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.css');
        array_push($css,'/resources/library-vendor/pnotify/pnotify.custom.min.css');

        //  Redactor http://imperavi.com/redactor/
//        array_push($css,'/resources/library-vendor/redactor/redactor.css');

        //  jqTree http://mbraak.github.io/jqTree/
//        array_push($css,'/resources/library-vendor/jqtree/jqtree.css');

        //  lightbox https://github.com/ashleydw/lightbox
//        array_push($css,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.css');

//        array_push($css,'/resources/app/css/base.css');

        echo $this->Html->css($css);

        echo $this->fetch('css');
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '599188036854190',
            xfbml      : true,
            version    : 'v2.1'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>


    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">SantoMercado.com</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">

                    <?php if(isset($userLogged)){ ?>
                        <li class=""><a href="/publicar"><span class="glyphicon glyphicon-globe"></span> Publicar</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-user"></span> Cuenta <?php if(isset($userLogged)){ echo '( '.$userLogged['User']['name'].' )'; } ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li role="presentation" class="dropdown-header">PRODUCTOS</li>
                                <li><a href="/publicados"><span class="glyphicon glyphicon-bullhorn"></span> Publicados</a></li>
                                <li><a href="/borradores"><span class="glyphicon glyphicon-pencil"></span> Borradores</a></li>
                                <li><a href="/stock/<?php echo $userLogged['User']['id']; ?>"><span class="glyphicon glyphicon-th"></span> Stock</a></li>
<!--                                <li class="divider"></li>-->
<!--                                <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> <del>Configuración</del></a></li>-->
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administración <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
<!--                                <li><a href="#"><del> Usuarios</del></a></li>-->
                                <li><a href="/categorias">Categorías</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php
                        if(isset($userLogged)){
                            echo '<li class=""><a href="/salir"><span class="glyphicon glyphicon-off"></span>  Salir</a></li>';
                        }else{
                            echo '<li class=""><a href="/entrar"><span class="glyphicon glyphicon-off"></span> Entrar</a></li>';
                        }
                    ?>
                </ul>
            </div><!--/.nav-collapse -->
    </div>

    <div class="main-container">
        <?php echo $this->fetch('content'); ?>
    </div>



    <!-- footer -->
    <div id="footer" style="margin-bottom: 20px">
        <hr>
        <div style="text-align: center;">
            Copyright ©2014 SantoMercado.com - Todos los derechos reservados.
        </div>
    </div>

<?php

    $scripts = array();

    //  jQuery - https://github.com/jquery/jquery
    //  array_push($scripts,'https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
    array_push($scripts,'/resources/library-vendor/jquery/jquery-1.11.1.js');

    //  jQueryCookie - https://github.com/carhartl/jquery-cookie
//    array_push($scripts,'/resources/library-vendor/jquery-cookie/jquery.cookie.js');

    //  jqTree - http://mbraak.github.io/jqTree/
//    array_push($scripts,'/resources/library-vendor/jqtree/tree.jquery.js');

    //  Redactor - http://imperavi.com/redactor/
//    array_push($scripts,'/resources/library-vendor/redactor/redactor.min.js');
//    array_push($scripts,'/resources/library-vendor/redactor/langs/es.js');

    //  Bootstrap - https://github.com/twbs/bootstrap
    //  array_push($scripts,'//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');
    array_push($scripts,'/resources/library-vendor/bootstrap/js/bootstrap.js');

    //  pnotify  - https://github.com/sciactive/pnotify
    //  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.js');
    array_push($scripts,'/resources/library-vendor/pnotify/pnotify.custom.min.js');

    //  Ekko Lightbox  - https://github.com/ashleydw/lightbox
//    array_push($scripts,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.js');

    //  jQuery Validation Plugin - https://github.com/jzaefferer/jquery-validation
    //  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js');
    //  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js');
//    array_push($scripts,'/resources/library-vendor/jquery-validate/jquery.validate.js');
//    array_push($scripts,'/resources/library-vendor/jquery-validate/additional-methods.js');

    //  Purl - https://github.com/allmarkedup/purl
    //  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/purl/2.3.1/purl.min.js');
//    array_push($scripts,'/resources/library-vendor/purl/purl.js');

    // App
    array_push($scripts,'/resources/app/js/base.js');

    echo $this->Html->script($scripts);

    echo $this->fetch('script');

?>

    <script type="text/javascript">
        $(document).ready(function ($) {
            // delegate calls to data-toggle="lightbox"
            $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox();
            });
        });
    </script>

</body>
</html>