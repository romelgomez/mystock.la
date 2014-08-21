<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title">Ingrese el correo y la contraseña para continuar.</h2>
                </div>
                <div class="panel-body">
                    <form role="form" id="LoginForm">
                        <div class="form-group">
                            <label for="LoginEmail"><span class="glyphicon glyphicon-envelope"></span> Email address</label>
                            <input id="LoginEmail" name="LoginEmail" value="" class="form-control" type="email" maxlength="128" placeholder="Correo" tabindex="1" autocorrect="off" autocapitalize="off">
                            <span class="help-block" style="display: none;">El campo título es obligatorio.</span>
                        </div>
                        <div class="form-group">
                            <label for="LoginPassword"><span class="glyphicon glyphicon-lock"></span> Contraseña</label>
                            <input type="password" class="form-control" id="LoginPassword" name="LoginPassword" placeholder="Contraseña" tabindex="2">
                            <span class="help-block" style="display: none;">El campo contraseña es obligatorio.</span>
                        </div>
                        <button type="submit" class="btn btn-primary" tabindex="3">Entrar</button>
                    </form>
                </div>
                <div class="panel-footer">
                    <button id="recover" type="button" class="btn btn-link">¿Olvido la contraseña?</button>
                    <button id="newUser" type="button" class="btn btn-link">¿Nuevo usuario?</button>
                </div>
            </div>

            <div id="login-error"  class="alert alert-danger alert-dismissible" style="display: none;" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <strong>Alerta!</strong>  El correo electrónico o la contraseña, <b>no son correctos</b>.
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
                    <h4 class="modal-title">Nuevo usuario</h4>
                </div>

                <div class="modal-body">

                    <!-- Mensajes post ajax request -->
<!--                    <div class="alert alert-success" 		style="display:none">-->
<!--                        <button type="button" class="close" data-dismiss="alert">x</button>-->
<!--                        ¡Se ha enviado un mensaje a su correo para confirmar su información!-->
<!--                    </div>-->
<!--                    <div class="alert alert-error fade in"	style="display:none">-->
<!--                        <button type="button" class="close" data-dismiss="alert">x</button>-->
<!--                        ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!-->
<!--                    </div>-->

                    <div class="form-group">
                        <label for="UserName"><span class="glyphicon glyphicon-user"></span> Primer Nombre</label>
                        <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Eje: Maria">
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

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>

            </form>
        </div>
    </div>
</div>




<!-- Modal Olvido de la contraseña
===================== -->
<div class="modal hide fade" id="recover_modal">
    <form id="UserForm" action="#" method="post" accept-charset="utf-8">

        <!-- Form header
        ------------------------------------>
        <div class="modal-header">
            <a href="#" class="close" data-dismiss="modal" style="border: none;float: right;"><img src="./img/x2.png" title="Cancelar" style="width: 24px;"></a>
            <h3>Recupere la contraseña</h3>
        </div>

        <!-- Form body
        ------------------------------------>
        <div class="modal-body">

            <!-- Mensajes post ajax request
            ------------------------------------>
            <div class="alert alert-success" 	style="display:none">
                <button type="button" class="close" data-dismiss="alert">x</button>
                ¡Se ha enviado un mensaje a su correo para confirmar y recuperar su información!
            </div>
            <div class="alert alert-error fade in"	style="display:none">
                <button type="button" class="close" data-dismiss="alert">x</button>
                ¡Ha ocurrido algún error, intente nuevamente o también puede probar recargar la página si persiste el error!
            </div>

            <!-- Form inputs
            ------------------------------------>
            <div class="control-group">
                <label class="control-label" for="Email">Correo</label>
                <div class="controls">
                    <div class="input-prepend" style="display: inline;">
                        <span class="add-on"><i class="icon-envelope"></i></span><input type="text" id="Email" name="Email" placeholder="Eje: maria@gmail.com">
                    </div>
                    <span style="display:none; margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
                </div>
            </div>

        </div>

        <!-- Form footer
        ------------------------------------>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" >Enviar</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        </div>

    </form>
</div>


<?php echo $this->Html->script('base.entrar',false); ?>
<?php echo $this->Html->script('base.entrar-jquery.validate',false); ?>
