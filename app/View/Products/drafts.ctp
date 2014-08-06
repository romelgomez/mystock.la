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
								
								<div class="alert alert-info">
									borradores
									<a href="#" data-dismiss="alert" class="close">×</a>
								</div>
										
										
													
							<!-- end content-->		
							</div>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>


<?php echo $this->Html->script('base.borradores',false); ?>
