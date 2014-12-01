<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<h2 style="font-family: 'Lato', sans-serif; font-weight: 300;font-size: 40px;" >Welcome to MyStock.LA </h2>
			<span style="font-family: 'Lobster', cursive; font-size: 27px;">Los Angeles</span>
			<hr>
			<p class="lead">Here you can easily sharing your offer of products or services in the social networks.</p>

			<blockquote>
				<p>The best way to start your business is to start simple, and online.</p>
			</blockquote>

			<p class="lead">Free Unlimited Publications.</p>

			<blockquote>
				<p>Only we will take one small fee if the buyer use PayPal™ payment gateway. But you can define or implement any payment option that you know, we no take fees at all for that.</p>
			</blockquote>
			<hr>

			<p class="text-center"><a href="/publish" type="button" class="btn btn-primary" style="font-size: 15px;">Start publishing today!</a></p>

		</div>
		<div class="col-md-8">			<img src="/resources/app/img/laptop-blank.png" class="img-responsive"></div>
	</div>

</div>


<?php

    // CSS
    $css = array();

    array_push($css,'/resources/app/css/base.css');

    $this->Html->css($css, null, array('inline' => false));

    // JS
    $scripts = array();

    array_push($scripts,'/resources/app/js/base.index.js');

    echo $this->Html->script($scripts,array('inline' => false));

?>
