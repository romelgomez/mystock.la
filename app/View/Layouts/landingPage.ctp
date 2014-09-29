<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstack - Bootstrap 3 Landing Page Theme">
    <meta name="keywords" content="">
    <meta name="author" content="Daniely Wright">
    <link href="/resources/theme-vendor/bootstack/assets/img/favicon.png" rel="shortcut icon" >
    <title>SantoMercado.com</title>

    <!-- Bootstrap core CSS -->
    <link href="/resources/library-vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Plugins -->
    <link href="/resources/theme-vendor/bootstack/assets/library-vendor/socicon/socicon.css" rel="stylesheet">
    <link href="/resources/theme-vendor/bootstack/assets/library-vendor/icomoon/style.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/resources/theme-vendor/bootstack/assets/css/style.css" rel="stylesheet">
    <!-- Web Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/resources/theme-vendor/bootstack/assets/js/modernizr.custom.js"></script>
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">

<!-- Fixed navbar -->
<nav id="navigation" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><span class="icon-stack"></span> <b>SantoMercado.com</b></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
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


    </div><!-- /container -->
</nav><!-- /fixed-navbar -->




<!-- Header Wrap -->
<section id="home">
    <div class="headerwrap">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h1>Bienvenido a <b>SantoMercado.com</b></h1>
                    <h3>Muestre sus productos o servicios a quien le conoce</h3>
                    <br>
                </div>

                <div class="col-lg-2 hidden-xs hidden-sm hidden-md">
                    <h5>Su negocio puesto en marcha</h5>
                    <p>La mejor forma de iniciar su empresa, es empezar simple, y en internet.</p>
                    <img src="/resources/theme-vendor/bootstack/assets/img/arrow-left.png" alt="">
                </div>
                <div class="col-lg-8">
                    <img class="img-responsive" src="/resources/theme-vendor/bootstack/assets/img/app-bg.png" alt="">
                </div>
                <div class="col-lg-2 hidden-xs hidden-sm hidden-md">
                    <br>
                    <img class="pad-top" src="/resources/theme-vendor/bootstack/assets/img/arrow-right.png" alt="">
                    <h5>Fácil de usar</h5>
                    <p>Desde una interfaz simple, comparta su oferta de productos y servicios.</p>
                </div>
            </div>
        </div> <!-- /container -->
    </div><!-- /headerwrap -->


    <!-- Intro Wrap -->
    <div class="intro">
        <div class="container">
            <div class="row text-center">
                <h2>Publicaciones Ilimitadas Gratuitas </h2>
                <hr>
                <br>
                <div class="col-lg-6">
                    <span class="glyphicon glyphicon-usd"></span>
                    <h3>Sin Costo</h3>
                    <p>Publicar en SantoMercado.com no representa ningún costo al Vendedor o al Cliente. Alentamos a usar cualquier opción de pago online disponible actualmente, tal como PayPal.</p>
                </div>
                <div class="col-lg-6">
                    <span class="glyphicon glyphicon-thumbs-up"></span>
                    <h3>Somos sociales</h3>
                    <p>Comparta su oferta de productos y servicios, en redes sociales fácilmente.</p>
                </div>
            </div>
            <br>
        </div> <!-- /container -->
    </div><!-- /introwrap -->
</section>


<!-- Divider 1 Section -->
<div class="divider01">
    <div class="container text-center">
        <h3 style="margin-bottom: 20px;">Comience a publicar hoy mismo.</h3>
        <a href="/publicados" type="button" class="btn btn-lg btn-theme2">Empieza ahora</a>
    </div><!-- /container -->
</div><!-- /divider01 -->




<!-- Footer Wrap -->
<section id="contact">
    <div class="footerwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h3>Contáctenos</h3>
                    <span class="icon-envelope"></span> <a href="mailto:soporte@santomercado.com">soporte@santomercado.com</a>
                </div>

                <div class="col-lg-4">
                    <h3>Encuentranos en</h3>
                    <div id="social">
                        <a href="#" rel="tooltip" title="Facebook" class="facebook">Facebook</a>
                        <a href="#" rel="tooltip" title="Twitter" class="twitter">Twitter</a>
                    </div>
                </div>
                <div class="col-lg-4">
<!--                    <h3>Boletín informativo</h3>-->
<!--                    <br>-->
<!--                    <p>Suscríbete a nuestro boletín informativo y sea el primero en conocer nuestras últimas actualizaciones.</p>-->
<!--                    <div class="form-inline">-->
<!--                        <input type="text" class="form-control" placeholder="Su correo electrónico">-->
<!--                        <button class="btn btn-theme" type="button">Suscribirse</button>-->
<!--                    </div>-->
                </div>
            </div><!-- /row -->
        </div><!-- /container -->
    </div><!-- /footerwrap -->
</section>

<!-- Copyright Wrap -->
<div class="copywrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy;2014 SantoMercado.com - Todos los derechos reservados.</p>
            </div>
        </div><!-- /row -->
    </div><!-- /container -->
</div><!-- /copywrap (copyright) -->


<script src="/resources/library-vendor/jquery/jquery-1.11.1.js"></script>
<script src="/resources/library-vendor/bootstrap/js/bootstrap.min.js"></script>
<!--<script src="/resources/theme-vendor/bootstack/assets/js/jquery.easing.1.3.js"></script>-->
<script src="/resources/theme-vendor/bootstack/assets/js/detectmobilebrowser.js"></script>
<script src="/resources/theme-vendor/bootstack/assets/js/smoothscroll.js"></script>
<script src="/resources/theme-vendor/bootstack/assets/js/waypoints.js"></script>
<script src="/resources/theme-vendor/bootstack/assets/js/main.js"></script>
<script>
    $('.carousel').carousel({
        interval: 3500,
        pause: "none"
    })
</script>
</body>
</html>