<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-6 col-md-4">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2 class="panel-title">Enter your email and password to continue</h2>
                        </div>
                        <div class="panel-body">
                            <form role="form" id="LoginForm">
                                <div class="form-group">
                                    <label for="LoginEmail"><i class="fa fa-envelope-o fa-fw"></i> Email address</label>
                                    <input id="LoginEmail" name="LoginEmail" value="" class="form-control" type="email" maxlength="128" tabindex="1" autocorrect="off" autocapitalize="off">
                                    <span class="help-block" style="display: none;"></span>
                                </div>
                                <div class="form-group">
                                    <label for="LoginPassword"><i class="fa fa-key fa-fw"></i> Password</label>
                                    <input type="password" class="form-control" id="LoginPassword" name="LoginPassword" tabindex="2">
                                    <span class="help-block" style="display: none;"></span>
                                </div>
                                <button type="submit" class="btn btn-primary" tabindex="3">Enter</button>
                            </form>
                        </div>
                        <div class="panel-footer">
<!--                            <button id="recover" type="button" class="btn btn-link">¿Olvido la contraseña?</button>-->
                            <button id="newUser" type="button" class="btn btn-link">New User?</button>
                        </div>
                    </div>

                    <div id="login-error"  class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Alert!</strong>  The email or password <b>are not correct</b>.
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
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">New user</h4>
                </div>

                <div class="modal-body">


                    <!-- Mensajes post ajax request -->
                    <div class="alert alert-success alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<!--                        ¡Se ha enviado un mensaje a su correo para confirmar su información!-->
                        Ready! You can now try to login.
                    </div>
                    <div class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        An error has occurred, try again, or you can also try reloading the page if the error persists!
                    </div>


                    <div class="form-group">
                        <label for="UserName"><span class="glyphicon glyphicon-user"></span> First name</label>
                        <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Eje: Maria, MariaSharapova, TennisShop">
                        <span class="help-block" style="display: none;">Required</span>
                    </div>

                    <div class="form-group">
                        <label for="UserEmail"><span class="glyphicon glyphicon-envelope"></span> Email</label>
                        <input type="email" class="form-control" id="UserEmail" name="UserEmail" placeholder="Eje: maria@gmail.com">
                        <span class="help-block" style="display: none;">Required</span>
                    </div>

                    <div class="form-group">
                        <label for="UserPassword"><span class="glyphicon glyphicon-lock"></span> Password</label>
                        <input type="password" class="form-control" id="UserPassword" name="UserPassword">
                        <span class="help-block" style="display: none;">Required</span>
                    </div>

                    <div class="form-group">
                        <label for="UserPasswordAgain"><span class="glyphicon glyphicon-lock"></span> Password once again</label>
                        <input type="password" class="form-control" id="UserPasswordAgain" name="UserPasswordAgain">
                        <span class="help-block" style="display: none;">Required</span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
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
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Recover password</h4>
                </div>

                <div class="modal-body">

                    <!-- Mensajes post ajax request -->
                    <div id="recoverySuccess" class="alert alert-success alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        ¡It sent a message to confirm your email and retrieve your information!
                    </div>
                    <div id="recoveryError" class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        An error has occurred, try again, or you can also try reloading the page if the error persists!
                    </div>

                    <div class="form-group">
                        <label for="Email"><span class="glyphicon glyphicon-envelope"></span> Email</label>
                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Eje: maria@gmail.com">
                        <span class="help-block" style="display: none;">Required</span>
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