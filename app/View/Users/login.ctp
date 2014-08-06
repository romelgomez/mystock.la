<!-- Content
===================== -->	
<div id="content" class="container-fluid ">
	<div class="row-fluid">
		<div class="span12" style="padding: 10px; border: 1px solid #E5E5E5; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">

			
			<!-- Mensages post ajax request
			------------------------------------>
			<div id="" class="alert alert-warning" style="display:none;">
				<button type="button" class="close" data-dismiss="alert">x</button>
				¡Los datos son invalidos!
			</div>
			<div class="alert alert-error fade in" style="display:none;">
				<button type="button" class="close" data-dismiss="alert">x</button>
				¡Ha ocurrido algun error, intente nuevamente o también puede probar recargar la página si persiste el error!
			</div>		
			
			<!-- Login form
			===================== -->
			<form id="LoginForm" class="form-vertical" action="cuenta.html">
				<p>Ingrese el correo y la contraceña para continuar.</p>
                <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend" style="display: inline;">
                            <span class="add-on"><i class="icon-envelope"></i></span><input id="LoginEmail" name="LoginEmail" value="" type="email" maxlength="128" placeholder="Correo" tabindex="1" autocorrect="off" autocapitalize="off">
                        </div>
                        <span style="display:none; margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend" style="display: inline;">
                            <span class="add-on"><i class="icon-lock"></i></span><input id="LoginPassword" name="LoginPassword" type="password" placeholder="Contraseña">
                        </div>
						<span style="display:none; margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
                    </div>
                </div>
				<span><input type="submit" class="btn btn-inverse" value="Entrar"></span>
                
                
                <div id="login-error" class="alert alert-block" style="margin-top: 20px; display:none;">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<h4>Alerta!</h4>
					El correo electrónico o contraceña, <b>no son correctos</b>.
				</div>
                
                
                <div class="form-actions" style="background-color: white; margin-bottom: 0px; padding: 10px 0px 0px 0px;">
                    <ul class="nav nav-pills" style="margin: 0;">
						<li><a href="#" class="flip-link" id="recover">¿Olvido la contraseña?</a></li>
						<li><a href="#" class="flip-link" id="new_user">¿Nuevo usuario?</a></li>
                    </ul>
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
			<h3>Recupere la contraceña</h3>
		</div>
		
		<!-- Form body
		------------------------------------>
		<div class="modal-body">
		
			<!-- Mensages post ajax request
			------------------------------------>
			<div class="alert alert-success" 	style="display:none">
				<button type="button" class="close" data-dismiss="alert">x</button>
				¡Se ha enviado un mensaje a su correo para confirmar y recuperar su información!
			</div>
			<div class="alert alert-error fade in"	style="display:none">
				<button type="button" class="close" data-dismiss="alert">x</button>
				¡Ha ocurrido algun error, intente nuevamente o también puede probar recargar la página si persiste el error!
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
			<button type="submit " class="btn btn-primary" >Enviar</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
		</div>
		
	</form>
</div>



<!-- Modal Nuevo usuario
===================== -->	
<div class="modal hide fade" id="new_user_modal"  >
	<form id="UserAddForm" action="#" method="post" accept-charset="utf-8">
		
		<!-- Form header 
		------------------------------------>
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal" style="border: none;float: right;"><img src="./img/x2.png" title="Cancelar" style="width: 24px;"></a>
			<h3>Nuevo usuario</h3>
		</div>
		
		<!-- Form body  
		------------------------------------>
		<div class="modal-body">	
		
			<!-- Mensages post ajax request  
			------------------------------------>
			<div class="alert alert-success" 		style="display:none">
				<button type="button" class="close" data-dismiss="alert">x</button>
				¡Se ha enviado un mensaje a su correo para confirmar su información!
			</div>
			<div class="alert alert-error fade in"	style="display:none">
				<button type="button" class="close" data-dismiss="alert">x</button>
				¡Ha ocurrido algun error, intente nuevamente o también puede probar recargar la página si persiste el error!
			</div>	  
		  
			<!-- Form inputs  
			------------------------------------>
			<div class="control-group">
				<label class="control-label" for="UserName">Primer Nombre</label>
				<div class="controls">
					<div class="input-prepend" style="display: inline;">
                        <span class="add-on"><i class="icon-user"></i></span><input type="text" id="UserName" name="UserName" placeholder="Eje: Maria">
                    </div>
					<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="UserEmail">Correo</label>
				<div class="controls">
					<div class="input-prepend" style="display: inline;">
                        <span class="add-on"><i class="icon-envelope"></i></span><input type="text" id="UserEmail" name="UserEmail" placeholder="Eje: maria@gmail.com">
                    </div>
					<span style="display:none; margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="UserPassword">Contraceña</label>
				<div class="controls">
					<div class="input-prepend" style="display: inline;">
						<span class="add-on"><i class="icon-lock"></i></span><input id="UserPassword" name="UserPassword" type="password" placeholder="Contraseña">
					</div>
					<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
				</div>
			</div>
		
		</div>
		
		<!-- Form footer 
		------------------------------------>
		<div class="modal-footer">
			<button type="submit " class="btn btn-primary" >Enviar</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
		</div>
		
	</form>
</div>


<?php echo $this->Html->script('base.entrar',false); ?>
<?php echo $this->Html->script('base.entrar-jquery.validate',false); ?>
