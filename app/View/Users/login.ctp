<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-6 col-md-4">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2 class="panel-title">Ingrese el correo y la contraseña para continuar.</h2>
                        </div>
                        <div class="panel-body">
                            <form role="form" id="LoginForm">
                                <div class="form-group">
                                    <label for="LoginEmail"><i class="fa fa-envelope-o fa-fw"></i> Email address</label>
                                    <input id="LoginEmail" name="LoginEmail" value="" class="form-control" type="email" maxlength="128" placeholder="Correo" tabindex="1" autocorrect="off" autocapitalize="off">
                                    <span class="help-block" style="display: none;">El campo título es obligatorio.</span>
                                </div>
                                <div class="form-group">
                                    <label for="LoginPassword"><i class="fa fa-key fa-fw"></i> Contraseña</label>
                                    <input type="password" class="form-control" id="LoginPassword" name="LoginPassword" placeholder="Contraseña" tabindex="2">
                                    <span class="help-block" style="display: none;">El campo contraseña es obligatorio.</span>
                                </div>
                                <button type="submit" class="btn btn-primary" tabindex="3">Entrar</button>
                            </form>
                        </div>
                        <div class="panel-footer">
<!--                            <button id="recover" type="button" class="btn btn-link">¿Olvido la contraseña?</button>-->
                            <button id="newUser" type="button" class="btn btn-link">¿Nuevo usuario?</button>
                        </div>
                    </div>

                    <div id="login-error"  class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Alerta!</strong>  El correo electrónico o la contraseña, <b>no son correctos</b>.
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-md-8">

                </div>
            </div>



        </div>
    </div>
</div>


<!-- Modal Nuevo usuario -->
<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="UserAddForm" action="#" method="post" accept-charset="utf-8">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title">Nuevo usuario</h4>
                </div>

                <div class="modal-body">


                    <!-- Mensajes post ajax request -->
                    <div class="alert alert-success alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
<!--                        ¡Se ha enviado un mensaje a su correo para confirmar su información!-->
                        Listo! Ya puede intentar loguearse.
                    </div>
                    <div class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
                    </div>


                    <div class="form-group">
                        <label for="UserName"><span class="glyphicon glyphicon-user"></span> Primer Nombre</label>
                        <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Eje: Maria, MariaSharapova, TennisShop">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                    <div class="form-group">
                        <label for="UserEmail"><span class="glyphicon glyphicon-envelope"></span> Correo</label>
                        <input type="email" class="form-control" id="UserEmail" name="UserEmail" placeholder="Eje: maria@gmail.com">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                    <div class="form-group">
                        <label for="UserPassword"><span class="glyphicon glyphicon-lock"></span> Contraseña</label>
                        <input type="password" class="form-control" id="UserPassword" name="UserPassword">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                    <div class="form-group">
                        <label for="UserPasswordAgain"><span class="glyphicon glyphicon-lock"></span> Contraseña nuevamente</label>
                        <input type="password" class="form-control" id="UserPasswordAgain" name="UserPasswordAgain">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Modal Olvido de la contraseña  -->
<div class="modal fade" id="recoverModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="UserForm" action="#" method="post" accept-charset="utf-8">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title">Recupere la contraseña</h4>
                </div>

                <div class="modal-body">

                    <!-- Mensajes post ajax request -->
                    <div id="recoverySuccess" class="alert alert-success alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Se ha enviado un mensaje a su correo para confirmar y recuperar su información!
                    </div>
                    <div id="recoveryError" class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
                    </div>

                    <div class="form-group">
                        <label for="Email"><span class="glyphicon glyphicon-envelope"></span> Correo</label>
                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Eje: maria@gmail.com">
                        <span class="help-block" style="display: none;">Requerido</span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>

            </form>
        </div>
    </div>
</div>


<?php

    // CSS
    $css = array();

    array_push($css,'/resources/app/css/base.css');

    $this->Html->css($css, null, array('inline' => false));

    // JS
    $scripts = array();

//  jQuery Validation Plugin - https://github.com/jzaefferer/jquery-validation
//  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js');
//  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js');
    array_push($scripts,'/resources/library-vendor/jquery-validate/jquery.validate.js');
    array_push($scripts,'/resources/library-vendor/jquery-validate/additional-methods.js');

    array_push($scripts,'/resources/app/js/base.enter.js');

    echo $this->Html->script($scripts,array('inline' => false));

?>