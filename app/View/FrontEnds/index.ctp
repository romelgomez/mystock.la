<!-- container
===================== -->
<div id="content" class="container-fluid">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<!-- td Sidebar 
				-------------------------> 
				<td style="width:250px" >
				
					<div id="sidebar" style="width: 250px;">
	
						<div id="search-container" style="overflow: hidden; border-right: 1px solid #636363;   padding:4px;border: 1px solid #D4D4D4;border-bottom: none;border-top-left-radius: 4px;border-top-right-radius: 4px;">		
							<div class="input-append">
								<input id="search" style="width: 183px;" type="text" placeholder="Eje: Laptops" title="Buscar"><button class="btn" type="button"><i class="icon-search"></i></button>
							</div>
						</div>
					
						<div id="sidebar-menu" style="font-size: 13px; border: 1px solid rgb(212, 212, 212); overflow-x: auto; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; min-height: 500px; ">
							
							<nav id="search_results" style="display: none; ">
								<ul class="menu-items level-1" style="list-style: none; margin: 7px 0px;">
								</ul>
							</nav>
							
							<!-- $("#accordion-menu a.link")  master-link -->
							<?php if($categories){ ?>
								
							<div id="accordion-menu" style="display: inherit; ">
								<div class="accordion" id="accordion">
									
									<?php foreach($categories as $department){ ?>
										<div id="" class="accordion-group">
											<div class="accordion-heading master-link" accordion-id="department-<?php echo $department['Category']['id']; ?>" >
												<div style="padding: 8px;">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#department-<?php echo $department['Category']['id']; ?>"><i class="icon-list-alt" title=""></i></a>
													<a href="#" class="link" title="<?php echo $department['Category']['name']; ?>"><?php echo $department['Category']['name']; ?></a>
												</div>
											</div>
											<?php if($department['children']){ ?> 
												<div id="department-<?php echo $department['Category']['id']; ?>" class="accordion-body collapse">
													<?php foreach($department['children'] as $category_general){ ?>
														<div class="accordion-inner"><a href="#" class="link" title="<?php echo $category_general['Category']['name']; ?>"><?php echo $category_general['Category']['name']; ?></a></div>
													<?php } ?>
												</div>
											<?php } ?>							
										</div>
									<?php } ?>						
									
									<!-- Test html 
									<div id="" class="accordion-group">
										<div class="accordion-heading master-link" accordion-id="categoria-3">
											<div style="padding: 8px;">
												<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#categoria-3"><i class="icon-list-alt" title=""></i></a>
												<a href="#" class="link" title="Computación">Computación 5</a>
											</div>
										</div>
										<div id="categoria-3" class="accordion-body collapse">

											<div class="accordion-inner"><a href="#" class="link" title="Laptops, Tablets y Netbooks">Laptops, Tablets y Netbooks</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Desktops y Servidores">Desktops y Servidores</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Accesorios de Ordenador y periféricos">Accesorios de Ordenador y periféricos</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Discos duros externos, ratones, redes y mucho más">Discos duros externos, ratones, redes y mucho más</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Partes de Computadoras y Componentes">Partes de Computadoras y Componentes</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Software">Software</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Juegos para PC">Juegos para PC</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Impresoras y Cartuchos">Impresoras y Cartuchos</a></div>
											<div class="accordion-inner"><a href="#" class="link" title="Oficina y Suministros para la Escuela">Oficina y Suministros para la Escuela</a></div>

										</div>
									</div>
									-->
								
								</div>						
							</div>
							
							<?php } ?>
							
						</div>	
	
					</div>
				
				</td>
				<!-- td Content
				------------------------->
				<td>
					
					<div class="container-fluid" style="padding-right: 0px;">
						<div class="row-fluid">
							<div class="span9">
							<!-- Center -->
								<ul class="thumbnails">
								  <li class="span12" style="margin-bottom: 0px;">
									<div class="thumbnail">
									  <img src="/img/site_index/ax1200iHPB.png" alt="">
									</div>
								  </li>
								</ul>
						
								<h4>Destacados</h4>
								<ul class="thumbnails">
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
								</ul>
								<ul class="thumbnails">
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
									<li class="span3"><a href="#" class="thumbnail"><img src="./img/270x270.gif" alt=""></a></li>
								</ul>
								
							</div>
							<div class="span3" style="width: 25.1%; margin-left: 0.5%;">
					
							<!-- Sidebar -->
								<ul class="thumbnails">
								  <li class="span12" style="margin-bottom: 0px;">
									<div class="thumbnail">
									  <img src="/img/site_index/dominator-platinum-landing-C.png" alt="">
									</div>
								  </li>
								</ul>
								<ul class="thumbnails">
								  <li class="span12" style="margin-bottom: 0px;">
									<div class="thumbnail">
									  <img src="/img/site_index/quiksilver-logo.jpg" alt="">
									</div>
								  </li>
								</ul>
							
							</div>
						</div>
					</div>
					
				
				</td>
			</tr>
		</tbody>
	</table>
</div>

<?php echo $this->Html->css('base.sidebar_menu',false); ?>
<?php echo $this->Html->script('base.index',false); ?>
