<?php echo $this->Html->css('redactor',false); ?>

<!-- css temporal --> 
<style type="text/css">
/* Categorias -- css temporal */

.error {
	border: 1px solid red;
}

.ulMenu {
	float: left;
	overflow: auto;
	height: 250px; /* 170 */
	width: 250px;
	border-right: 1px solid;
	background-color: aliceblue;
}

.li-selected{
	background: #ACEAFF;	
}

.ul-parent-selected{
	background: #9DE7FF;	
}

.li-parent-selected{
	background: #6FDBFF;	
}

.liMenu{
	padding: 5px;
	padding-left: 10px;
	cursor: pointer;	
}
 
.class-all-menu-container{
	overflow: hidden;
	border: 1px solid black;
	padding: 10px;
	background-color: whitesmoke;
	border-top-left-radius: 6px 6px;
	border-top-right-radius: 6px 6px;
	border-bottom-left-radius: 6px 6px;
	border-bottom-right-radius: 6px 6px;	
}

.menu-container{
	overflow: hidden;
	border: 1px solid black;	
}
 
.category-selected{
	width: 300px;
	float: left;
	height: 250px;
	border-right: 1px solid;
	background-color: aliceBlue;	
	
}

.category-selected-text{
	background-color: aliceBlue;	
	color: #E32;
	font-family: 'Gill Sans','lucida grande', helvetica, arial, sans-serif;
	font-size: 190%;
} 


#path span{
	cursor: pointer;
}


form .required {
	font-weight: bold;
}
form .required label:after {
	color: #e32;
	content: '*';
	display:inline;
}

.required h3:after {
	color: #e32;
	content: '*';
	display:inline;
}

.requiredInput{
	background-color: #FFD1D1;
	border: 1px solid red;
}
</style>


<!-- Content
===================== -->	
<div id="content" class="container-fluid">
	<div class="row-fluid">
		<div class="span12">

			<?php 
			
			echo $this->Form->create('Product',  array('url' => "/newProduct", 'type' => 'file')); 
			
					
			if($url_action == 'editar' || $url_action == 'editar_borrador'){
				echo '<div id="current-menu" style="display:none;">'.$this->request->data['current-menu'].'</div>';
			}
			?>
			
				<div id="category-selector"  style="overflow: hidden;">
					
					<div class="page-header">
						<h1>Selecione una categoria <small>la que mejor se adapte al producto que desea publicar.</small></h1>
					</div>
					
					<table class="table table-bordered" style="margin-bottom: 10px;">
						<tbody>
							<tr>
								<td style="width:173px">
									<button id="add-content" type="button" class="btn btn-primary disabled" disabled="disabled" style="padding-top: 6px;padding-bottom: 6px;" >Confirmar y continuar</button>
								</td>
								<td>
									<ul id="path" class="breadcrumb" style=" border: 1px solid #CCC; padding: 6px 15px; ">
										<li class="active" >Publicar</li>
									</ul>
								</td>
							</tr>
						</tbody>		
					</table>	
						
					<?php echo $this->Form->hidden('Product.category_id'); ?>	
					
					
					<div class="well well-small">
						<div id="menu-box"  style=" border: 1px solid #B9B9B9; height: 265px; border-top-left-radius: 6px 6px; border-top-right-radius: 6px 6px;border-bottom-left-radius: 6px 6px;border-bottom-right-radius: 6px 6px;overflow-x: scroll;overflow-y: hidden;" >
							<div id="menu" style="overflow: hidden;">
								<?php 
									if($base_menu){
										echo  '<div class="ulMenu" id="default-options">';		
										foreach($base_menu as $k => $v){
											echo '<div class="liMenu" id="category-id-'.$v['Category']['id'].'"> '.$v['Category']['name'].'</div>';	
										}
										echo '</div>';
									}
								?>
							</div>		
						</div>
					</div>	
					
					
					
					
				</div>
				<!-- inter -->
				<div id="add-product" style="overflow: hidden; display: none;">
					
					<div style="overflow: hidden;">
						
						<?php
						// edit
						if($url_action =='editar'){	$status = $this->request->data['Product']['status']; // status de la publicación
						
								if($status){
									
								}
						
						?>
						
						
							<table class="table table-bordered" style="margin-bottom: 0px;">
								<tbody>
									<tr>
										<td style="width:245px">
											<p>
												<button id="update" type="submit" class="btn btn-primary" style="padding-top: 6px;padding-bottom: 6px; "  >Actualizar</button>
												<?php if($status){ 	// esta publicado,	por lo tanto el elemento activate_container debe esta oculto.  ?>
													<button id="pause" type="button" class="btn " style="padding-top: 6px;padding-bottom: 6px; "  >Pausar</button>
													<button id="activate" type="button" class="btn " style="padding-top: 6px;padding-bottom: 6px; display:none;"  >Activar</button>
												<?php }else{ 		// esta pausado,	por lo tanto el elemento pause_container debe esta oculto. ?>
													<button id="pause" type="button" class="btn " style="padding-top: 6px;padding-bottom: 6px; display:none;"  >Pausar</button>
													<button id="activate" type="button" class="btn " style="padding-top: 6px;padding-bottom: 6px;"  >Activar</button>
												<?php } ?>
												<button id="delete" class="btn btn-danger" type="button" style="padding-top: 6px;padding-bottom: 6px;"  >Borrar</button>
											</p>
										</td>
										<td>
											<div id="debugTime" style="padding-top: 10px; display:none;">La publicación se ha actulizado a las <span id="lastTimeSave"></span> (Hace <span id="minutesElapsed">0</span> minutos)</div>
										</td>
									</tr>
								</tbody>		
							</table>
						
						
						<?php }
						// newProduct, editDraft  
						if($url_action =='editar_borrador' || $url_action == false){
						?>	
							
							<table class="table table-bordered" style="margin-bottom: 0px;">
								<tbody>
									<tr>
										<td style="width:315px">
											<p>
												<button id="publish"	class="btn btn-primary"	type="submit" style="padding-top: 6px;padding-bottom: 6px;"  >Publicar</button>
												<button id="save-now" 	class="btn btn-success"	type="button" style="padding-top: 6px;padding-bottom: 6px;margin-left: 4px;"  >Guardar Ahora</button>
												<button id="discard"	class="btn btn-warning"	type="button" style="padding-top: 6px;padding-bottom: 6px;margin-left: 4px;"  >Descartar</button>
											</p>
										</td>
										<td>
											<div id="debugTime" style="padding-top: 10px; display:none;">El borrador se ha guardado a las <span id="lastTimeSave"></span> (Hace <span id="minutesElapsed">0</span> minutos)</div>
										</td>
									</tr>
								</tbody>		
							</table>
							
							
						<?php }	?>
													
					</div>
					
					<table class="table table-bordered" style="margin-bottom: 0px;">
						<tbody>
							<tr>
								<td style="width:137px">
									<button id="edit-category" type="button" class="btn btn-inverse" style="padding-top: 6px;padding-bottom: 6px;"  >Editar Categoria</button>
								</td>
								<td>
									<ul id="path2" class="breadcrumb" style=" border: 1px solid #CCC; padding: 6px 15px; ">
										<li class="active" >Publicar</li>
									</ul>
								</td>
							</tr>
						</tbody>		
					</table>
					
					<div style="display:none"><?php echo $this->Form->hidden('Product.id'); ?></div>
									
					
					
					
					<div class="control-group">
						<label class="control-label" for="ProductTitle"><i class="icon-bookmark"></i> Titulo</label>
						<div class="controls">
							<?php echo $this->Form->input('Product.title',array('label'=>false,'div'=>false,'class'=>'input-block-level','name'=>'ProductTitle','placeholder'=>'Eje: EVGA X79 Classified Intel Socket 2011 Quad Channel DDR3 32GB of DDR3 2133MHz+ 151-SE-E779-KR')); ?>
							<!-- <input class="input-block-level" type="text" id="ProductTitle" name="ProductTitle" placeholder="Eje: EVGA X79 Classified Intel Socket 2011 Quad Channel DDR3 32GB of DDR3 2133MHz+ 151-SE-E779-KR"> -->
							<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="ProductSubtitle"><i class="icon-info-sign"></i> Subtitulo</label>
						<div class="controls">
							<?php echo $this->Form->input('Product.subtitle',array('label'=>false,'div'=>false,'class'=>'input-block-level','name'=>'ProductSubtitle','placeholder'=>'The EVGA X79 Classified is the ultimate motherboard with a 12+2 Phase PWM Power Design, 100% POSCAP Capacitors, a 300% Increase in CPU Socket Gold Content, Dual 8pin CPU Power Connectors and more')); ?>
							<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
						</div>
					</div>
					

					<div class="control-group">
						<label class="control-label" for="ProductBody"><i class="icon-align-justify"></i> Descipción</label>
						<div class="controls">
							<div class="input-prepend" style="display: inline;">
								<?php echo $this->Form->textarea('Product.body',array('label'=>false,'div'=>false,'rows'=>7,'class'=>'input-block-level','name'=>'ProductBody','placeholder'=>'Eje: EVGA X79 es una tarjeta madre que ...')); ?>
							</div>
							<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
						</div>
					</div>

					<div class="controls controls-row">
						
						<div class="control-group">
							<label class="control-label" for="ProductPrice"><i class="icon-tag"></i> Precio</label>
							<div class="controls">
								<div class="input-append" style="display: inline;">
									
									<?php echo $this->Form->input('Product.price',array('label'=>false,'div'=>false,'class'=>'input-small','name'=>'ProductPrice','placeholder'=>'Eje: 1000')); ?><span class="add-on">BsF</span>
								
								</div>
								<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
							</div>
						</div>
																
						<div class="control-group">
							<label class="control-label" for="ProductQuantity"><i class="icon-th"></i> Cantidad</label>
							<div class="controls">
								<div class="input-append" style="display: inline;">
								
									<?php echo $this->Form->input('Product.quantity',array('label'=>false,'div'=>false,'class'=>'input-small','name'=>'ProductQuantity','placeholder'=>'Eje: 100')); ?><span class="add-on">Unidades</span>
								
								</div>
								<span style="display:none;  margin-top: 5px; margin-left: 7px;" class="help-inline">Requerido</span>
							</div>
						</div>
						
					</div>
				
					<!-- Esquema basico - funcional al intentar guardar un borrador. se puede inavilitar para debug el codigo subsiguiente y avilitar este y probar establecer un borrador  
					<div style="overflow: hidden; margin-bottom: 10px;" >
						<div style="overflow: hidden; padding-bottom: 7px;" class="required" ><span><i class="icon-picture"></i> Imagenes:</span> <a id="continue-upload" style="display:none" href="#" class="lightbox-start-a" >añadir mas</a> </div>
						<div style="padding: 7px; border: 1px solid #CCC; background-color: white; cursor: pointer;  overflow: hidden;  -moz-border-radius: 2px; border-radius: 2px;">
							
							<div id="start-upload" style="height: 212px;  overflow: hidden;">
								<div style="overflow: hidden; padding-top: 25px;"><center><img src="/img/sube_imagenes.png"></center></div>
							</div>
							
							<div id="product_thumbnails" style="display:none;">
							</div>
						
						</div>
					</div>
					-->
					
					<div style="overflow: hidden; margin-bottom: 10px;" >
							<?php
								if($url_action == 'editar' || $url_action == 'editar_borrador'){
									if($this->request->data['Image']){
										// hay imagenes cargadas.
									?>	
						
										<div style="overflow: hidden; padding-bottom: 7px;" class="required" ><span><i class="icon-picture"></i> Imagenes:</span> <a id="continue-upload" style="" href="#" >añadir mas</a> </div>
										<div style="padding: 7px; border: 1px solid #CCC; background-color: white; cursor: pointer;  overflow: hidden;  -moz-border-radius: 2px; border-radius: 2px;">
							
											<div id="start-upload" style="height: 212px;  overflow: hidden; display:none;">
												<div style="overflow: hidden; padding-top: 25px;"><center><img src="/img/sube_imagenes.png"></center></div>
											</div>
											
											<div id="product_thumbnails" >
												<?php 
													foreach($this->request->data['Image'] as $index => $imagen){
													?>
														
														<a class="thumbnail" id="thumbnail-id-<?php echo $imagen['original']['id']; ?>" style="overflow: hidden; width: 200px; height: 200px; float: left; margin: 5px;">
															<div style="overflow: hidden; width: 200px; height: 200px; z-index: 0; position: relative;">
																<center><img src="/img/products/<?php echo $imagen['thumbnails']['small']['name']; ?>" title="santomercado.com"></center>
															</div>
															<div class="disable-this-product-thumbnail" style="overflow: hidden; z-index: 1; margin-top:-200px; position: relative; float: right; padding-right: 2px; padding-top: 2px; width: 24px; height: 24px; cursor: pointer;"><img style="width: 24px;" src="/img/x2.png"></div><div class="view-this-product-thumbnail" style="overflow: hidden; z-index: 1; margin-top:-120px; margin-left: 80px; position: relative;  padding-right: 2px; padding-top: 2px; width: 32px; height: 32px; cursor: pointer;">
																<img src="/img/view.png">
															</div>
															<div style="display:none;"><?php echo json_encode($imagen); ?></div>
														</a>
													
													<?php	
													}
												?>
											</div>
						
										</div>		
										
										
									<?php
									}else{ // El borrador o la  publicación no tienen imagenes cargadas
									?>	
									
										<div style="overflow: hidden; padding-bottom: 7px;" class="required" ><span><i class="icon-picture"></i> Imagenes:</span> <a id="continue-upload" style="display:none" href="#"  >añadir mas</a> </div>
										<div style="padding: 7px; border: 1px solid #CCC; background-color: white; cursor: pointer;  overflow: hidden;  -moz-border-radius: 2px; border-radius: 2px;">
							
											<div id="start-upload" style="height: 212px;  overflow: hidden;">
												<div style="overflow: hidden; padding-top: 25px;"><center><img src="/img/sube_imagenes.png"></center></div>
											</div>
											
											<div id="product_thumbnails" style="display:none;">
											</div>									
										
										</div>
									
									<?php	
									}
								}else{  // Es una publicacion nueva
								?>
						
									<div style="overflow: hidden; padding-bottom: 7px;" class="required" ><span><i class="icon-picture"></i> Imagenes:</span> <a id="continue-upload" style="display:none" href="#" >añadir mas</a> </div>
									<div style="padding: 7px; border: 1px solid #CCC; background-color: white; cursor: pointer;  overflow: hidden;  -moz-border-radius: 2px; border-radius: 2px;">
									
										<div id="start-upload" style="height: 212px;  overflow: hidden;">
											<div style="overflow: hidden; padding-top: 25px;"><center><img src="/img/sube_imagenes.png"></center></div>
										</div>
										
										<div id="product_thumbnails" style="display:none;">
										</div>									
								
									</div>
								
								<?php 
								}
							?>
					</div>











				
					<div class="alert" >
						<ul style="margin-left: 10px; margin-bottom: 0px;">
							<li>Al escribir el titulo por favor sigue esta conveción: Marca - Nombre - Características relevantes - Numero de parte o Modelo.</li>
							<li><strong>Todos</strong> los campos excepto <b>subtitulo</b> son requeridos para publicar. Pero no para guardar un borrador.</li>
							<li>Las imagenes son de caracter obligatorio. De no tener al menos una imagen cargada, el sistema no mostrara la publicación a los clientes.</li>
						</ul>
					</div>
				
				</div>

			<?php echo $this->Form->end(); ?>
			
			
			
		</div><!-- end span12 --> 
	</div>
</div>


<!-- Modal Carga de imagenes 
===================== -->	
<div class="modal hide fade container"   id="uploading-pictures"  >
	<form id="UserAddForm" action="#" method="post" accept-charset="utf-8">
		
		<!-- Form header 
		------------------------------------>
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal" style="border: none;float: right;"><img src="/img/x2.png" title="Cancelar" style="width: 24px;"></a>
			<h3>Carga imagenes del producto o servicio</h3>
		</div>
		
		<!-- Form body  
		------------------------------------>
		<div class="modal-body" style="max-height: 700px;">
			<div id="drop-files" class="drop-element" style="overflow: auto;padding: 10px; height: 600px; border: 2px dashed #DCDCDC; border-radius: 5px 5px;">
				
				<div id="optional-selection-container" style=" overflow: hidden; margin-top: 200px;">
					<div class="row-fluid">
						<div class="span4"></div>
						<div class="span4">
							<center>
								<div style="opacity: 0.2;overflow: hidden;padding: 10px;" ><h3 style="margin: 0;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-weight: normal;" >Suelta las imagenes aqui</h3></div>
								<div style="height: 27px;" ><span style="opacity: 0.2;">O si prefieres...</span></div>
								<div><button type="submit" class="btn btn-primary" style="position: relative; overflow: hidden; direction: ltr; ">Seleciona las imagenes desde la computadora<input id="first-files" multiple="multiple" type="file" name="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0; "></button></div>
							</center>
						</div>
						<div class="span4"></div>
					</div>
				</div>
			
			</div>
		</div>
		
		
		<!-- Form footer 
		------------------------------------>
		<div class="modal-footer">
			<div class="pull-left">
				<button id="second-files-button" type="submit" class="btn btn-primary" style="position: relative; overflow: hidden; direction: ltr; display:none;">Añadir mas<input id="second-files" multiple="multiple" type="file" name="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0; "></button>
			</div>
			<div class="pull-right">
				<button id="save-this" class="btn btn-primary" disabled="disabled"  >Confirmar la imagenes</button>
				<button id="cancel-this" class="btn"  data-dismiss="modal" aria-hidden="true">Cancelar</button>
			</div>
		</div>
		
	</form>
</div>



<!-- light box para visualizar mejor la imagen cargada de la publicación o borrador.
--------------------------------------------------------------------------------------------------------------->
<div id="product-light-box" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
	<div class='lightbox-header'>
		<button type="button" class="close" data-dismiss="lightbox" aria-hidden="true">&times;</button>
	</div>
	<div class='lightbox-content'>
		<img id="image-product" src="">
	</div>
</div>   


<!-- Modal para borrar la publicacion
---------------------------------------------------------------------------------------------------------------->	
<div class="modal hide fade" id="delete_product_modal"  >
	<div class="alert alert-block alert-error"  style="margin-bottom: 0px; border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
		<h4 class="alert-heading">¿Realmente quieres borrar esta publicación?</h4>
		<h5 class="muted" style=" margin-bottom: 0px; " >Un alternativa mejor es pausar la publicacion y activarla cuando se normalice el inventario o evento sobrevenido, 
		pausar la publicacion permitira que la publicidad y contactos esten disponible al los clientes, teniendo claro que la publicación quedara inavilitada para ser ofertada a traves del sistema.</h5>
	</div>
	<!-- Form footer 
	------------------------------------>
	<div class="modal-footer">
		<button  id="delete_product" product_id="" class="btn btn-danger" >Confirmar</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
	</div>
</div>


<?php echo $this->Html->script('redactor.min.js',false); ?>
<?php echo $this->Html->script('base.publicar',false); ?>
