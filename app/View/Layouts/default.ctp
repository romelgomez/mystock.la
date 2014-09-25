<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">

    <title>SantoMercado.com</title>

    <?php
        // layout -> default
        $css = array(
            '/resources/library-vendor/bootstrap/css/bootstrap.css',
            '/resources/library-vendor/font-awesome/css/font-awesome.min.css',
            '/resources/library-vendor/pnotify/pnotify.custom.min.css',
            '/resources/library-vendor/redactor/redactor.css',
            '/resources/library-vendor/jqtree/jqtree.css',
            '/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.css',
            '/resources/app/css/base.css'
        );

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
            Solo empresas publican en SantoMercado.com
            <br>
            Copyright © 2012 Santo Mercado Venezuela S.A J-777777777-G
        </div>
    </div>

    <?php

        $scripts = array(
            '/resources/library-vendor/jquery/jquery-1.11.1.js',
            '/resources/library-vendor/bootstrap/js/bootstrap.js',
            '/resources/library-vendor/pnotify/pnotify.custom.min.js',
            '/resources/app/js/base.js'
        );

        echo $this->Html->script($scripts);
    ?>

    <?php echo $this->fetch('script'); ?>

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