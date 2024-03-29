<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <title>MyStock.LA</title>

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
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/resources/theme-vendor/bootstack/assets/js/modernizr.custom.js"></script>
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
        <a class="navbar-brand" href="/">MyStock.LA</a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">

            <?php if(isset($userLogged)){ ?>
                <li class=""><a href="/publish"><span class="glyphicon glyphicon-globe"></span> Publish</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-user"></span> Account <?php if(isset($userLogged)){ echo '( '.$userLogged['User']['name'].' )'; } ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation" class="dropdown-header">PRODUCTS</li>
                        <li><a href="/published"><span class="glyphicon glyphicon-bullhorn"></span> Published</a></li>
                        <li><a href="/drafts"><span class="glyphicon glyphicon-pencil"></span> Drafts</a></li>
                        <li><a href="/stock/<?php echo $userLogged['User']['id']; ?>"><span class="glyphicon glyphicon-th"></span> Stock</a></li>
                        <!--                                <li class="divider"></li>-->
                        <!--                                <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> <del>Configuración</del></a></li>-->
                    </ul>
                </li>
            <?php } ?>
            <?php
            if(isset($userLogged)){
                echo '<li class=""><a href="/logout"><span class="glyphicon glyphicon-off"></span>  Sign out</a></li>';
            }else{
                echo '<li class=""><a href="/login"><span class="glyphicon glyphicon-off"></span> Log in</a></li>';
            }
            ?>
        </ul>
    </div><!--/.nav-collapse -->
</div>




<!-- Header Wrap -->
<section id="home">
    <div class="headerwrap">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h1>Welcome to <b>MyStock.LA</b></h1>
                    <h3>Showcase your products or services in social networks</h3>
                    <br>
                </div>

                <div class="col-lg-2 hidden-xs hidden-sm hidden-md">
                    <h5>Start your business</h5>
                    <p>The best way to start your business is to start simple, and online.</p>
                    <img src="/resources/theme-vendor/bootstack/assets/img/arrow-left.png" alt="">
                </div>
                <div class="col-lg-8">
                    <img class="img-responsive" src="/resources/theme-vendor/bootstack/assets/img/app-bg.png" alt="">
                </div>
                <div class="col-lg-2 hidden-xs hidden-sm hidden-md">
                    <br>
                    <img class="pad-top" src="/resources/theme-vendor/bootstack/assets/img/arrow-right.png" alt="">
                    <h5>Easy to use and share</h5>
                </div>
            </div>
        </div> <!-- /container -->
    </div><!-- /headerwrap -->


    <!-- Intro Wrap -->
    <div class="intro">
        <div class="container">
            <div class="row text-center">
                <h2>Free Unlimited Publications</h2>
                <hr>
                <h3>No fees at all. You can define or implement any payment option that you know!</h3>
            </div>
            <br>
        </div> <!-- /container -->
    </div><!-- /introwrap -->
</section>


<!-- Divider 1 Section -->
<div class="divider01">
    <div class="container text-center">
        <h3 style="margin-bottom: 20px;">Start publishing today!</h3>
        <a href="/publish" type="button" class="btn btn-lg btn-theme2" style="font-family: 'Lato', sans-serif">Go!</a>
    </div><!-- /container -->
</div><!-- /divider01 -->




<!-- Footer Wrap -->
<section id="contact">
    <div class="footerwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h3>Contact us</h3>
                    <span class="icon-envelope"></span> <a href="mailto:soporte@santomercado.com">support@mystock.la</a>
                </div>

                <div class="col-lg-4">
<!--                    <h3>Encuentranos en</h3>-->
<!--                    <div id="social">-->
<!--                        <a href="#" rel="tooltip" title="Facebook" class="facebook">Facebook</a>-->
<!--                        <a href="#" rel="tooltip" title="Twitter" class="twitter">Twitter</a>-->
<!--                    </div>-->
                </div>
                <div class="col-lg-4">
<!--                    <h3>Boletín informativo</h3>-->
<!--                    <br>-->
<!--                    <p>Suscríbete a nuestro boletín informativo y sea el primero en conocer nuestras últimas actualizaciones.</p>-->
<!--                    <div class="form-inline">-->
<!--                        <input type="text" class="form-control" placeholder="Su correo electrónico">-->
<!--                        <button class="btn btn-theme" type="button">Suscribirse</button>-->
<!--                    </div>-->
                </div>Default
            </div><!-- /row -->
        </div><!-- /container -->
    </div><!-- /footerwrap -->
</section>

<!-- Copyright Wrap -->
<div class="copywrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy;2014 MyStock.LA - All rights reserved. <a href="/terms-of-service" target="_blank">Terms of Service</a> & <a href="/privacy-policy" target="_blank">Privacy Policy</a></p>
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
