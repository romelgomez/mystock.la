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
    <link href="/resources/theme-verdor/bootstack/assets/library-vendor/socicon/socicon.css" rel="stylesheet">
    <link href="/resources/theme-verdor/bootstack/assets/library-vendor/icomoon/style.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/resources/theme-verdor/bootstack/assets/css/style.css" rel="stylesheet">
    <!-- Web Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/resources/theme-verdor/bootstack/assets/js/modernizr.custom.js"></script>
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
                            <li><a href="/stock/<?php echo $userLogged['User']['id']; ?>">Stock</a></li>
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
                    <h5>Automate business processes</h5>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <img src="/resources/theme-verdor/bootstack/assets/img/arrow-left.png" alt="">
                </div>
                <div class="col-lg-8">
                    <img class="img-responsive" src="/resources/theme-verdor/bootstack/assets/img/app-bg.png" alt="">
                    <!--<h4>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</h4>
                    <a href="#features" type="submit" class="btn btn-lg btn-theme smoothScroll">LEARN MORE</a>-->
                </div>
                <div class="col-lg-2 hidden-xs hidden-sm hidden-md">
                    <br>
                    <img class="pad-top" src="/resources/theme-verdor/bootstack/assets/img/arrow-right.png" alt="">
                    <h5>Collaborate in the cloud</h5>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
            </div>
        </div> <!-- /container -->
    </div><!-- /headerwrap -->


    <!-- Intro Wrap -->
    <div class="intro">
        <div class="container">
            <div class="row text-center">
                <h2>Proven Success</h2>
                <hr>
                <br>
                <br>
                <div class="col-lg-3">
                    <span class="icon-users"></span>
                    <h3>Professional Services</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
                <div class="col-lg-3">
                    <span class="icon-stats"></span>
                    <h3>Cost Effective</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
                <div class="col-lg-3">
                    <span class="icon-stack"></span>
                    <h3>Scalable and Adaptable</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
                <div class="col-lg-3">
                    <span class="icon-thumbs-up"></span>
                    <h3>Easy to Use</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
            </div>
            <br>
        </div> <!-- /container -->
    </div><!-- /introwrap -->
</section>


<!-- Features Wrap -->
<section id="features">
    <div class="featureswrap">
        <div class="container">
            <div class="row">
                <h2 class="text-center">Features</h2>
                <hr>
                <br>
                <br>
                <div class="col-lg-6">
                    <img class="img-responsive left" src="/resources/theme-verdor/bootstack/assets/img/mobile.png" alt="">
                </div>

                <div class="col-lg-6">
                    <br>
                    <!-- Accordion -->
                    <div class="accordion ac" id="accordion2">
                        <div class="accordion-group active">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                    <span class="icon-cloud"></span> Collaborate in the cloud
                                </a>
                            </div><!-- /accordion-heading -->
                            <div id="collapseOne" class="accordion-body collapse in">
                                <div class="accordion-inner">
                                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                </div><!-- /accordion-inner -->
                            </div><!-- /collapse -->
                        </div><!-- /accordion-group -->
                        <br>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                    <span class="icon-checkmark"></span> Get started fast
                                </a>
                            </div>
                            <div id="collapseTwo" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                </div><!-- /accordion-inner -->
                            </div><!-- /collapse -->
                        </div><!-- /accordion-group -->
                        <br>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                                    <span class="icon-bars"></span> Deliver real time insights
                                </a>
                            </div>
                            <div id="collapseThree" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                </div><!-- /accordion-inner -->
                            </div><!-- /collapse -->
                        </div><!-- /accordion-group -->
                        <br>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                                    <span class="icon-list"></span> Automate business processes
                                </a>
                            </div>
                            <div id="collapseFour" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                </div><!-- /accordion-inner -->
                            </div><!-- /collapse -->
                        </div><!-- /accordion-group -->
                        <br>
                    </div><!-- /accordion -->
                </div>
            </div><!-- /row -->
            <br><br><br>
            <div class="row">
                <h2 class="text-center">Multi Useful Components</h2>
                <hr><br><br>
                <div class="col-lg-6">
                    <br><br>
                    <h4><span class="icon-cogs"></span> Create customized business apps</h4>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                    <br><br>
                </div>

                <div class="col-lg-6">
                    <img class="img-responsive right" src="/resources/theme-verdor/bootstack/assets/img/tablet.png" alt="">
                </div>
            </div>
            <br>
            <br>
            <br>
        </div><!-- /container -->
    </div><!-- /featureswrap -->
</section>


<!-- Divider 1 Section -->
<div class="divider01">
    <div class="container">
        <div class="row text-center">
            <div id="carousel-example-generic" class="carousel slide">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <h3>"Working with Bootstack was amazing. Their team is incredible."</h3>
                        <h4>John Doe, <em>A Company</em></h4>
                    </div>
                    <div class="item">
                        <h3>"Bootstack has helped my team build amazing products. Amazing team."</h3>
                        <h4>Jane Doe, <em>A Company</em></h4>
                    </div>
                </div><!-- /carousel-inner -->
                <br>
                <br>
                <br>
                <!-- Indicators - Uncomment for indicators to show
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                </ol> -->
            </div><!-- /carousel-example -->
        </div><!-- /row -->
    </div><!-- /container -->
</div><!-- /divider01 -->


<!-- Pricing Section -->
<section id="pricing">
    <div class="pricingwrap">
        <div class="container text-center">
            <br>
            <h2>Our Pricing Options</h2>
            <hr><br><br>
            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <h3 class="panel-title">Starter</h3>
                        </div>
                        <div class="panel-body">
                            <div class="the-price">
                                <h2>$9<span class="subscript">/mo</span></h2>
                                <small>1 month FREE Trial</small>
                            </div>
                            <table class="table">
                                <tr>
                                    <td>1 Account</td>
                                </tr>
                                <tr>
                                    <td>1 Project</td>
                                </tr>
                                <tr>
                                    <td>50K API Access</td>
                                </tr>
                                <tr>
                                    <td>100MB Storage</td>
                                </tr>
                                <tr>
                                    <td>Custom Cloud Services</td>
                                </tr>
                                <tr>
                                    <td>Weekly Reports</td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a href="#" class="btn btn-theme" role="button">Sign Up</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <h3 class="panel-title">Standard</h3>
                        </div>
                        <div class="panel-body">
                            <div class="the-price">
                                <h2>$19<span class="subscript">/mo</span></h2>
                                <small>1 month FREE Trial</small>
                            </div>
                            <table class="table">
                                <tr>
                                    <td>2 Accounts</td>
                                </tr>
                                <tr>
                                    <td>5 Projects</td>
                                </tr>
                                <tr>
                                    <td>100K API Access</td>
                                </tr>
                                <tr>
                                    <td>200MB Storage</td>
                                </tr>
                                <tr>
                                    <td>Custom Cloud Services</td>
                                </tr>
                                <tr>
                                    <td>Weekly Reports</td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a href="#" class="btn btn-theme" role="button">Sign Up</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <h3 class="panel-title">Premium</h3>
                        </div>
                        <div class="panel-body">
                            <div class="the-price">
                                <h2>$39<span class="subscript">/mo</span></h2>
                                <small>1 month FREE Trial</small>
                            </div>
                            <table class="table">
                                <tr>
                                    <td>5 Accounts</td>
                                </tr>
                                <tr>
                                    <td>20 Projects</td>
                                </tr>
                                <tr>
                                    <td>300K API Access</td>
                                </tr>
                                <tr>
                                    <td>500MB Storage</td>
                                </tr>
                                <tr>
                                    <td>Custom Cloud Services</td>
                                </tr>
                                <tr>
                                    <td>Weekly Reports</td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a href="#" class="btn btn-theme" role="button">Sign Up</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <h3 class="panel-title">Enterprise</h3>
                        </div>
                        <div class="panel-body">
                            <div class="the-price">
                                <h2>$59<span class="subscript">/mo</span></h2>
                                <small>1 month FREE Trial</small>
                            </div>
                            <table class="table">
                                <tr>
                                    <td>10 Accounts</td>
                                </tr>
                                <tr>
                                    <td>50 Projects</td>
                                </tr>
                                <tr>
                                    <td>500K API Access</td>
                                </tr>
                                <tr>
                                    <td>1GB Storage</td>
                                </tr>
                                <tr>
                                    <td>Custom Cloud Services</td>
                                </tr>
                                <tr>
                                    <td>Weekly Reports</td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a href="#" class="btn btn-theme" role="button">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div><!-- /row -->
        </div><!-- /container -->
    </div><!-- /pricingwrap -->
</section>


<!-- Divider 2 Section -->
<div class="divider02">
    <div class="container text-center">
        <h3>Start your 30-day free trial today.</h3>
        <a href="#" type="button" class="btn btn-lg btn-theme2">Get Started</a>
    </div>
</div><!-- /divider02 -->


<!-- Footer Wrap -->
<section id="contact">
    <div class="footerwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h3>Contact Us</h3>
                    <br>
                    <p><span class="icon-location"></span> Some Address 373, Palo Alto, CA <br/>
                        <span class="icon-phone"></span> (800) 373-7733 <br/>
                        <span class="icon-envelope"></span> <a href="mailto:support@bootstacktheme.com">support@bootstacktheme.com</a>
                    </p>
                </div>

                <div class="col-lg-4">
                    <h3>We Are Social</h3>
                    <br>
                    <div id="social">
                        <a href="#" rel="tooltip" title="Facebook" class="facebook">Facebook</a>
                        <a href="#" rel="tooltip" title="Twitter" class="twitter">Twitter</a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h3>Newsletter</h3>
                    <br>
                    <p>Subscribe to our newsletter and be the first to know our latest updates.</p>
                    <div class="form-inline">
                        <input type="text" class="form-control" placeholder="Your email">
                        <button class="btn btn-theme" type="button">Subscribe</button>
                    </div>
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
                <p>Copyright &copy;2014 SantoMercado.com Todos los derechos reservados.</p>
            </div>
        </div><!-- /row -->
    </div><!-- /container -->
</div><!-- /copywrap (copyright) -->


<script src="/resources/library-vendor/jquery/jquery-1.11.1.js"></script>
<script src="/resources/library-vendor/bootstrap/js/bootstrap.min.js"></script>
<!--<script src="/resources/theme-vendor/bootstack/assets/js/jquery.easing.1.3.js"></script>-->
<script src="/resources/theme-verdor/bootstack/assets/js/detectmobilebrowser.js"></script>
<script src="/resources/theme-verdor/bootstack/assets/js/smoothscroll.js"></script>
<script src="/resources/theme-verdor/bootstack/assets/js/waypoints.js"></script>
<script src="/resources/theme-verdor/bootstack/assets/js/main.js"></script>
<script>
    $('.carousel').carousel({
        interval: 3500,
        pause: "none"
    })
</script>
</body>
</html>