<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">

					<?php
					if($data['status'] === 'success'){
						?>

						<div class="row">
							<div class="col-xs-6 col-md-4">

								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Set new password</h3>
									</div>
									<div class="panel-body">

										<form role="form" id="new-password-form" action="#" method="post" accept-charset="utf-8">

											<div class="message"></div>

											<input type="hidden" id="id" name="id" value="<?php echo $data['id']; ?>">
											<input type="hidden" id="key" name="key" value="<?php echo $data['key']; ?>">

											<div class="form-group">
												<label for="password"><span class="glyphicon glyphicon-lock"></span> Password</label>
												<input type="password" class="form-control" id="password" name="password">
												<span class="help-block" style="display: none;">Required</span>
											</div>

											<div class="form-group">
												<label for="password-again"><span class="glyphicon glyphicon-lock"></span> Confirm Password</label>
												<input type="password" class="form-control" id="password-again" name="password-again">
												<span class="help-block" style="display: none;">Required</span>
											</div>

											<button type="submit" class="btn btn-primary">Save</button>

										</form>

									</div>
								</div>

							</div>
							<div class="col-xs-12 col-sm-6 col-md-8">
							</div>
						</div>

					<?php
					}else{
						switch ($data['message']) {
							case "user-not-exist":
								echo '<div class="alert alert-danger" role="alert">This request is invalid.</div>';
								break;
							case "this-link-is-invalid":
								echo '<div class="alert alert-danger" role="alert">This link is expired or is invalid.</div>';
								break;
							default:
								echo '<div class="alert alert-danger" role="alert">An unexpected error occurred</div>';
						}
					}
					?>



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

array_push($scripts,'/resources/app/js/base.password.js');

echo $this->Html->script($scripts,array('inline' => false));

?>
