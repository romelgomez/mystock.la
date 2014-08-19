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

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <!-- Bootstrap-Modal - http://jschr.github.io/bootstrap-modal/ -->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.5/css/bootstrap-modal-bs3patch.css">-->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.5/css/bootstrap-modal.css">-->



    <?php
        echo $this->Html->css(array('refactored/base'));
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
                    <li class=""><a href="/publicar"><span class="glyphicon glyphicon-globe"></span> Publicar</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-user"></span> Cuenta <?php if(isset($userLogged)){ echo '( '.$userLogged['User']['name'].' )'; } ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation" class="dropdown-header">PRODUCTOS</li>
                            <li><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span> <del>Comprados</del></a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-usd"></span> <del>Vendidos</del></a></li>
                            <li><a href="/publicados"><span class="glyphicon glyphicon-bullhorn"></span> Publicados</a></li>
                            <li><a href="/borradores"><span class="glyphicon glyphicon-pencil"></span> <del>Borradores</del></a></li>
                            <li class="divider"></li>
                            <li><a href="#"><span class="glyphicon glyphicon-thumbs-up"></span> <del>Reputación</del></a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> <del>Configuración</del></a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administración <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><del> Usuarios</del></a></li>
                            <li><a href="/categorias">Categorías</a></li>
                        </ul>
                    </li>
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

        <div style="text-align: center;">
            En SantoMercado.com solo publican empresas certificadas. Exige tu factura.
            <br>
            Copyright © 2012 Santo Mercado Venezuela S.A J-777777777-G
        </div>

    </div>


    <!-- Debug
    ===================== -->
    <div style="padding: 10px;margin: 10px;border: 1px solid black;border-radius: 4px;">
        <h2 style="margin-top: 0;" >Debug:</h2>

        <h5>Ajax Request responseText:</h5>
        <div id="debug"></div>

        <?php
        echo '<h5>Sql Dump:</h5>';
        echo $this->element('sql_dump');

        echo '<h5>Ubicación:</h5>';

        if(isset($controller) && isset($action)){
            echo 'Controller: '.$controller.'Controller.php'.'<br />';
            echo 'Action: '.$action;
        }

        ?>
    </div>





    <!-- jQuery - https://github.com/jquery/jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <link rel="stylesheet" href="/resources/library-vendor/redactor/redactor.css">
    <script type="text/javascript" src="/resources/library-vendor/redactor/redactor.min.js" ></script>
    <script type="text/javascript" src="/resources/library-vendor/redactor/langs/es.js" ></script>


    <!-- Bootstrap - https://github.com/twbs/bootstrap -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- bootstrap-modal - https://github.com/jschr/bootstrap-modal -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.5/js/bootstrap-modalmanager.min.js"-->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.5/js/bootstrap-modal.min.js"></script>-->

    <!-- jQuery Validation Plugin - https://github.com/jzaefferer/jquery-validation -->
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>

    <!-- Purl - https://github.com/allmarkedup/purl -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/purl/2.3.1/purl.min.js"></script>




    <?php
        echo $this->Html->script(array('base'));
    ?>

    <?php echo $this->fetch('script'); ?>

</body>
</html>