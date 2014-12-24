<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />

	<?php

	$data = $this->{'request'}->{'data'};

	// facebook
	if(isset($url_action)){
		if($url_action == 'product'){

			// Title URL -  lazy solution
			$foo    = trim($data['Product']['title']);
			$foo    = strtolower($foo);
			$foo    = str_replace('/', '',$foo);
			$foo    = preg_replace( '/\s+/', ' ', $foo);
			$title  = str_replace(' ', '-',$foo);

			// Description
			$text =  $data['Product']['body']; // "The Obsidian Series: Designed by builders, for builders. Obsidian Series 900D is for serious builders with big projects. Do you want to design your ultimate dream PC? Add state-of-the-art liquid cooling or air cooling? Build a quadruple GPU, dual CPU graphics powerhouse? Or, just create a monster file server?If these are the kinds of questions you’re asking yourself, you need a 900D.Serious Expansion Flexibility for Serious BuildersWe don’t need to tell you that there’s no such thing as too much room. We’ll just let the numbers speak for themselves:Ten expansion slotsFits up to nine hard drives or SSDs, with three hot-swap mountsFour 5.25” optical drive baysRoom for dual power suppliesAnd if nine hard drives or SSDs aren’t enough, you can expand to 15 drives by adding additional drive cages (available separately).Incredible Cooling Performance and PotentialA cooling-optimized case design requires three things: good passive ventilation, flexible fan mounting options for good airflow, and the room to add sophisticated active cooling systems. The Obsidian Series 900D starts with a clear airflow path from the intake fans to the case interior, with no drives in the way. But, that’s just the start.Includes three AF120L 120mm front intake fans and one AF140L 140mm rear exhaust fan15 total fan mount locationsFive radiator mounting pointsFour removable dust filters Easy to Build With. Easy to Live With.System builds should be straightforward and easy, without your PC case getting in the way. And, reconfigurations and upgrades should be just as simple. An enjoyable build process with great-looking results is a huge part of the pride of ownership that comes with building high-performance PCs.The 900D can help make it all happen with tool-free side panel access, hard drive installation, and optical drive installation. Inside, you’ll find a CPU backplate cutout and rubber grommets on the cable routing holes. There’s generous cable routing space behind the motherboard tray, and snap-down cable latches help you create clean, easy-to-modify cabling that doesn’t rely on dozens of zip ties.You also get modular hard drive cages, and the dust filters and fan covers are easily removable for cleaning and maintenance.Understated OverkillLet the beginners use cases that look like they’re going to turn into a giant robot. Obsidian Series 900D is built on a steel and cast aluminum frame, with solid steel panels and a fully painted interior. The brushed aluminum fascia resists scratches and fingerprints, and it’s just plain fun to put your hands on it.Contents and SpecificationsPackage contentsObsidian Series 900D Super Tower PC CaseQuick Start GuideCompatibilityATX, Micro ATX, E-ATX, HPTX, and Mini ITX compatibleTechnical specificationsIncludes three 120mm AF120L and one 140mm AF140L exhaust fanBrushed aluminum front fascia with full cast aluminum surround structure front and rearFive radiator mounting points:Front: up to 360mmTop: up to 480mm (4 x 120) or 420mm (3 x 140)Bottom side one: up to 480mm (4 x 120) or 420mm (3 x 140)Bottom side two (with PSU installed): up to 280mm (2 x 140) or 240mm (2 x 120)Rear: 140mm or 120mmUp to fifteen total fan mount locationsNine tool free 3.5” and screw-in 2.5” combo hard drive bays for maximum storage, upgradable to fifteen total (requires purchasing two additional cages)Four tool-free 5.25” drive bays Dual USB 3.0, quad USB 2.0 front panel I/OTool free side panel access to top panels.Magnetic latch bottom HDD/Radiator chamber access with swing-out doors.Full side panel windowRemovable lower rad covers allow you to customize between cooling and clean, refined appearanceThree hot-swap bays integrated into one of three included modular hard drive cagesTen expansion slots for multi-GPU dream systemsDual PSU baysCPU backplate cutout and rubber grommeted cable routing holesEasily removable dust filters and fan coversSnap-down cable routing latches and extra routing space behind the motherboard trayPSU – ATX (not included)";

			$description = '';
			$_description        =  strip_tags($text);     // remove html entities
			$_description        =  trim($_description);   // remove whitespaces
			$_descriptionLength  =  strlen($_description); // Get string length

			if($_descriptionLength > 200){
				$_description = substr($_description, 0, 140);      // Return part of a string
				$_description =  explode(" ",$_description);        // returns an array containing all the words found inside the string
				for($i = 0; $i < sizeof($_description)-1; $i++){
					$description .= ' '.(string)$_description[$i];
				}
				$description = ucfirst(trim($description)).' ...';
			}else{
				$description = ucfirst($_description);
			}


			echo '<title>MyStock.LA - '.$data['Product']['title'].' - '.$data['User']['name'].' Stock</title>';

			echo '<meta property="og:title" content="'.$data['Product']['title'].'" />';
			echo '<meta property="og:url" content="http://www.mystock.la/producto/'.$data['Product']['id'].'/'.$title.'.html" />';
			echo '<meta property="og:type" content="website" />';
			echo '<meta property="og:site_name" content="MyStock.LA" />';
			echo '<meta property="og:description" content="'.$description.'" />';
			echo '<meta property="og:image" content="http://www.mystock.la/resources/app/img/products/'.$data['Image'][0]['facebook'].'" />';
			echo '<meta property="fb:app_id" content="338515926310582" />';


			echo '<meta name="twitter:card" content="summary_large_image" />';
			echo '<meta name="twitter:site" content="@mystockla" />';
			echo '<meta name="twitter:title" content="'.$data['Product']['title'].'" />';
			echo '<meta name="twitter:description" content="'.$description.'">';
			echo '<meta name="twitter:image:src" content="http://www.mystock.la/resources/app/img/products/'.$data['Image'][0]['facebook'].'" />';
			echo '<meta name="twitter:url" content="http://www.mystock.la/stock/'.$data['Product']['user_id'].'" />';

		}

		if($url_action == 'stock'){

			if($data['User']['banner'] !== NULL){
				$banner = 'resources/app/img/banners/'.$data['User']['banner'];
			}else{
				$banner = 'resources/app/img/benjaminFranklin.jpg';
			}

			echo '<title>MyStock.LA  - '.$data['User']['name'].' Stock</title>';

			echo '<meta property="og:title" content="'.$data['User']['name'].' Stock" />';
			echo '<meta property="og:url" content="http://www.mystock.la/stock/'.$data['User']['id'].'" />';
			echo '<meta property="og:type" content="website" />';
			echo '<meta property="og:site_name" content="MyStock.LA" />';
			echo '<meta property="og:description" content="Visita el stock de producto y/o servicios que tengo para ti" />';
			echo '<meta property="og:image" content="http://www.mystock.la/'.$banner.'" />';
			echo '<meta property="fb:app_id" content="338515926310582" />';

			echo '<meta name="twitter:card" content="summary_large_image" />';
			echo '<meta name="twitter:site" content="@mystockla" />';
			echo '<meta name="twitter:title" content="'.$data['User']['name'].' Stock" />';
			echo '<meta name="twitter:description" content="Visita el stock de producto y/o servicios que tengo para ti">';
			echo '<meta name="twitter:image:src" content="http://www.mystock.la/'.$banner.'" />';
			echo '<meta name="twitter:url" content="http://www.mystock.la/stock/'.$data['User']['id'].'" />';
		}
	}else{
		echo '<title>MyStock.LA</title>';
	}
	?>

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">

	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>

	<?php
	$css = array();

	//  Bootstrap
	//  array_push($scripts,'//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css');
	array_push($css,'/resources/library-vendor/bootstrap/css/bootstrap.css');

	//  font-awesome
	//  array_push($scripts,'//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
	array_push($css,'/resources/library-vendor/font-awesome/css/font-awesome.min.css');

	//  Pnotify https://github.com/sciactive/pnotify
	//  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.css');
	array_push($css,'/resources/library-vendor/pnotify/pnotify.custom.min.css');

	//  Redactor http://imperavi.com/redactor/
	//        array_push($css,'/resources/library-vendor/redactor/redactor.css');

	//  jqTree http://mbraak.github.io/jqTree/
	//        array_push($css,'/resources/library-vendor/jqtree/jqtree.css');

	//  lightbox https://github.com/ashleydw/lightbox
	//        array_push($css,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.css');

	//        array_push($css,'/resources/app/css/base.css');

	echo $this->Html->css($css);

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
<!--<div id="fb-root"></div>-->
<!--<script>-->
<!--	window.fbAsyncInit = function() {-->
<!--		FB.init({-->
<!--			appId      : '338515926310582',-->
<!--			xfbml      : true,-->
<!--			version    : 'v2.1'-->
<!--		});-->
<!--	};-->
<!---->
<!--	(function(d, s, id){-->
<!--		var js, fjs = d.getElementsByTagName(s)[0];-->
<!--		if (d.getElementById(id)) {return;}-->
<!--		js = d.createElement(s); js.id = id;-->
<!--		js.src = "//connect.facebook.net/en_US/sdk.js";-->
<!--		fjs.parentNode.insertBefore(js, fjs);-->
<!--	}(document, 'script', 'facebook-jssdk'));-->
<!--</script>-->


<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background: url(/resources/app/img/escheresque_ste.png); border-bottom: 1px solid black;">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand a-gold" href="/" >MyStock.LA</a>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">

			<?php if(isset($userLogged)){ ?>
				<li class=""><a href="/publish"><span class="glyphicon glyphicon-globe"></span> Publish</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-user"></span> Account <?php if(isset($userLogged)){ echo '( '.$userLogged['User']['name'].' )'; } ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation" class="dropdown-header">PRODUCTS</li>
						<li><a href="/published"><span class="glyphicon glyphicon-bullhorn"></span> Published</a></li>
						<li><a href="/drafts"><span class="glyphicon glyphicon-pencil"></span> Drafts</a></li>
						<li><a href="/stock/<?php echo $userLogged['User']['id']; ?>"><span class="glyphicon glyphicon-th"></span> Stock</a></li>
						<!--                                <li class="divider"></li>-->
						<!--                                <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> <del>Configuración</del></a></li>-->
					</ul>
				</li>
			<?php } ?>
			<?php
			if(isset($userLogged)){
				echo '<li class=""><a href="/logout" class="menu" ><span class="glyphicon glyphicon-off"></span>  Sign out</a></li>';
			}else{
				echo '<li class=""><a href="/login" class="menu" ><span class="glyphicon glyphicon-off"></span> Log in</a></li>';
			}
			?>
		</ul>
	</div><!--/.nav-collapse -->
</div>

<div class="main-container">
	<?php echo $this->fetch('content'); ?>
</div>



<!-- footer -->
<div class="footer" style="background: #222; background: url(/resources/app/img/escheresque_ste.png); border-top: 1px solid black;">
	<div style="text-align: center; margin-top: 20px; color: #ffffff;">
		Copyright &copy;2014 MyStock.LA - All rights reserved.
		<a href="/terms-of-service" target="_blank" class="a-gold">Terms of Service</a> &
		<a href="/privacy-policy" target="_blank" class="a-gold">Privacy Policy</a>.
	</div>
</div>

<?php

$scripts = array();

//  jQuery - https://github.com/jquery/jquery
//  array_push($scripts,'https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
array_push($scripts,'/resources/library-vendor/jquery/jquery-1.11.1.js');

//  jQueryCookie - https://github.com/carhartl/jquery-cookie
//    array_push($scripts,'/resources/library-vendor/jquery-cookie/jquery.cookie.js');

//  jqTree - http://mbraak.github.io/jqTree/
//    array_push($scripts,'/resources/library-vendor/jqtree/tree.jquery.js');

//  Redactor - http://imperavi.com/redactor/
//    array_push($scripts,'/resources/library-vendor/redactor/redactor.min.js');
//    array_push($scripts,'/resources/library-vendor/redactor/langs/es.js');

//  Bootstrap - https://github.com/twbs/bootstrap
//  array_push($scripts,'//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');
array_push($scripts,'/resources/library-vendor/bootstrap/js/bootstrap.js');

//  pnotify  - https://github.com/sciactive/pnotify
//  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.js');
array_push($scripts,'/resources/library-vendor/pnotify/pnotify.custom.min.js');

//  Ekko Lightbox  - https://github.com/ashleydw/lightbox
//    array_push($scripts,'/resources/library-vendor/ekko-lightbox/ekko-lightbox.min.js');

//  jQuery Validation Plugin - https://github.com/jzaefferer/jquery-validation
//  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js');
//  array_push($scripts,'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js');
//    array_push($scripts,'/resources/library-vendor/jquery-validate/jquery.validate.js');
//    array_push($scripts,'/resources/library-vendor/jquery-validate/additional-methods.js');

//  Purl - https://github.com/allmarkedup/purl
//  array_push($scripts,'https://cdnjs.cloudflare.com/ajax/libs/purl/2.3.1/purl.min.js');
//    array_push($scripts,'/resources/library-vendor/purl/purl.js');

// App
array_push($scripts,'/resources/app/js/base.js');

echo $this->Html->script($scripts);

echo $this->fetch('script');

?>

<script type="text/javascript">
	$(document).ready(function ($) {
		// delegate calls to data-toggle="lightbox"
		$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
			event.preventDefault();
			return $(this).ekkoLightbox();
		});
	});
</script>

</body>
</html>
