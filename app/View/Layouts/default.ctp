<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8" />
	<title>SantoMercado.com</title>
	<?php
		echo $this->Html->css(array('bootstrap.min','bootstrap-responsive.min','bootstrap-modal','bootstrap-lightbox.min','base'));
		echo $this->fetch('css');
	?>
</head>
<body>
	
<!-- header    footer
===================== -->
<div  id="header" class="navbar navbar-inverse navbar-fixed-top"> 
	<div class="navbar-inner">
		<div class="container-fluid">
			
			<button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand" href="/">SantoMercado.com</a>
			<div class="nav-collapse collapse" style="height: 0px; ">
				<ul class="nav">
					<li class=""><a href="/publicar"><i class="icon-globe icon-white"></i> Publicar</a></li>
					<li class="">
						<a href="/cuenta">
							<i class="icon-user icon-white"></i> 
							Cuenta 
							<?php if(isset($userLogged)){
								echo '( '.$userLogged['User']['name'].' )';	
							} ?>
						</a>
					</li>
					<li class=""><a href="#"><i class="icon-shopping-cart icon-white"></i> Carrito de Compras</a></li>
					<li class=""><a href="#"><i class="icon-user icon-white"></i> Foro</a></li>
					<?php 
						if(isset($userLogged)){
							echo '<li class=""><a href="/salir"><i class="icon-off icon-white"></i> Salir</a></li>';
						}else{
							echo '<li class=""><a href="/entrar"><i class="icon-off icon-white"></i> Entrar</a></li>';
						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>


<?php echo $this->fetch('content'); ?>


<!-- footer
===================== -->
<div id="footer">

	<br>
	<center>En santomercado.com solo publican empresas certificadas. Exige tu factura.</center>
	<br>
	<center>Copyright © 2012 Santo Mercado Venezuela S.A J-777777777-G</center>

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
		echo 'Controller: '.$controller.'Controller.php'.'<br />';
		echo 'Action: '.$action;
	?>
</div>			
	



<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-lightbox.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.js"></script>
<script type="text/javascript" src="/js/jquery.validate-additional-methods.min.js"></script>
<script type="text/javascript" src="/js/base.js"></script>
<?php echo $this->fetch('script'); ?>
</body>
</html>
