<style type="text/css">

/* css temporal */
.product-universal-container{
	border: 2px solid #333;
	overflow: hidden;
	text-decoration: none;
	height:120px;
	border-bottom-left-radius: 6px 6px ;
	border-bottom-right-radius: 6px 6px ;
	border-top-left-radius: 6px 6px ;
	border-top-right-radius: 6px 6px;
}


.product-universal-image{
	border-right: 1px solid black;
}
.product-universal-name{
	background: black;
	color: white;
	font-size: 17px;
	font-weight: bold;
	height: 22px;
	opacity: 0.65;
	border-top-right-radius:3px;
	padding-left: 5px;
	font-family:"Times New Roman",Georgia,Serif;
	overflow: hidden;
}

.product-universal-options{
	font-size: 15px;
	font-family:"Times New Roman",Georgia,Serif;
	padding:5px;
	overflow: hidden;
}

.product-universal-name:hover{
	background: #65b9bf;
}

#published div.media{
	border: 1px solid gray;
	padding: 10px;
	border-radius: 3px;
}
#published div.media:hover{
	border-color: #08C;
	-webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
	-moz-box-shadow: 0 1px 4px rgba(0,105,214,0.25);
	box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
}


</style>


<!-- Content
===================== -->	
<div id="content" class="container-fluid">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<!-- td Sidebar 
				-------------------------> 
				<td style="width:250px" >

					<?php 
						echo $this->element('seller_menu');
						echo $this->element('admin_menu');
					?>

				</td>
				<!-- td Content
				------------------------->
				<td>
					<div class="row-fluid">
						<div class="span12">
							<div style="padding: 0 0 0 10px;" >
							<!-- start content-->
								
								<div id="no-products" style="display:none;  margin-bottom: 10px;"> 
									<div class="alert" style="margin-bottom: 10px;">
										<strong>Alerta!</strong> no hay productos publicados ahun. <a href="/publicar">Añade un nuevo producto!</a>
									</div>
								</div>
								
								<div id="progress-bar" class="progress progress-striped active"  style="display:none;">
									<div class="bar"></div>
								</div>
								
								<div id="information-panel" class="well well-small" style="display:none; height: 30px; margin-bottom: 10px;">
									<div  class="pull-left" >
										<!-- busqueda.
										------------------------------------------------------------------------------------------>
										<div id="searching" style="display:none; overflow: hidden; float: left; margin-right: 10px;" >
											<form action="#" id="SearchPublicationsForm" method="post" accept-charset="utf-8">
												<div class="control-group" style="margin-bottom: 0px;">
													<div class="controls" style=" display: table-row; ">
														<div class="input-append" style="overflow: hidden;margin-bottom: 0;float: left;">
															<input id="search" name="search" type="text" placeholder="Eje: Laptops" title="Buscar"><button class="btn" type="submit"><i class="icon-search"></i></button>
														</div>
													</div>
												</div>
											</form>
										</div>
									
										<!-- Mas filtros.
										----------------------------------------------------------------------------------------->
										<!-- 
										<div id="more-filters" style="float: left; margin-right: 10px; overflow: hidden; ">
											<button type="button" class="btn"><i class="icon-filter"></i> Mas filtros</button>
										</div>
										-->
										
									
									</div>
									<div  class="pull-right" >
										
										<!-- Información. 
										-------------------------------------------------------------------------------------->	
										<div id="pagination-info" style="overflow: hidden; float: left; margin-right: 10px; line-height: 30px; ">
											<span></span>
										</div>	
												
										<!-- Paginación. 
										--------------------------------------------------------------------------------------> 
										<div id="pagination" style="display:none; overflow: hidden;  float: left;"  >
											<div style=" float: left; margin-right: 10px; ">
												<div class="btn-group" >
													<button id="prev-page" class="btn disabled" disabled><i class="icon-chevron-left"></i> Anterior</button>
													<button id="next-page" class="btn disabled" disabled><i class="icon-chevron-right"></i> Próximo</button>
												</div>
											</div>
										</div>
											
										
										<!-- Odenar por 
										---------------------------------------------------------------------------------------->
										<div id="order-by" style="display:none; float: left; margin-right: 10px; ">
											<div class="btn-group">
												<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-tasks"></i> Ordernar por <span class="caret"></span></a>
												<ul class="dropdown-menu">
											
											
													<li><a id="recent" href="#"><i class="icon-time"></i> Recientes</a></li>
													<li><a id="oldest" href="#"><i class="icon-calendar"></i> Antiguos</a></li>
													
													<li class="divider"></li>
													
													<li><a id="higher-price" href="#"><i class="icon-tags"></i> Mayor precio</a></li>
													<li><a id="lower-price" href="#"><i class="icon-tag"></i> Menor precio</a></li>
													
													<li class="divider"></li>
													
													<li><a id="higher-availability" href="#"><i class="icon-th"></i> Mayor disponibilidad</a></li>
													<li><a id="lower-availability" href="#"><i class="icon-th-large"></i> Menor disponibilidad</a></li>
												
												</ul>
											</div>
										</div>
										
										<!-- ver como. 
										--------------------------------------------------------------------------------------> 
										<div style="overflow: hidden; float: left; ">
											<div class="btn-group" data-toggle="buttons-radio">
												<button id="" class="btn"><i class="icon-th-large"></i></button>
												<button id="" class="btn active" ><i class="icon-th-list"></i></button>
												<button id="" class="btn"><i class="icon-align-justify"></i></button>
											</div>
										</div>
										
									</div>
								</div>


								<div id="no-products-for-this-search" style="display:none;  margin-bottom: 10px;">
									<div class="alert">
										<button type="button" class="close" data-dismiss="alert">×</button>
										No hay registros encontrados para: <span id="no-for-this-search" ></span>
									</div>
								</div>

								<div id="products-for-this-search" style="display:none;">
									<div class="alert alert-success" style="margin-bottom: 10px;">
										<button type="button" class="close" data-dismiss="alert">×</button>
										 Hay <span id="product-quantity-for-this-search" ></span> para la siguiente busqueda: <span id="for-this-search" ></span>
									</div>
								</div>
								
								<div id="successful-elimination" style="display:none;">
									<div class="alert alert-success" style=" margin-bottom: 10px; ">
										La publicación ha sido eliminada con exito.
									</div>
								</div>
								
								
								<div id="published" ></div>
								


									
													
							<!-- end content-->		
							</div>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- Modal para borrar la publicacion
---------------------------------------------------------------------------------------------------------------->	
<div class="modal hide fade" id="delete_product_modal"  style="position: fixed;"  >
	<div class="alert alert-block alert-error"  style="margin-bottom: 0px; border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
		<h4 class="alert-heading">¿Realmente quieres borrar esta publicación?</h4>
		<h5 class="muted" style=" margin-bottom: 0px; " >Un alternativa mejor es pausar la publicacion y activarla cuando se normalice el inventario o evento sobrevenido, 
		pausar la publicacion permitira que la publicidad y contactos esten disponible al los clientes, teniendo claro que la publicación quedara inavilitada para ser ofertada a traves del sistema.</h5>
	</div>
	<!-- Form footer 
	------------------------------------>
	<div class="modal-footer">
		<button  id="delete_product" class="btn btn-danger" >Confirmar</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
	</div>
</div>






<?php echo $this->Html->script('purl',false); ?>
<?php echo $this->Html->script('base.publicados',false); ?>
