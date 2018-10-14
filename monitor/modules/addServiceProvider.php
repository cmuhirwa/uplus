<div class="container"> 
	<div style="color: #fff; font-size: 20px; background-color: #007569; height: 100px;     box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
	color: #fff;">
		<img style="margin: 15px; " src="/frontassets/img/service-providers.png" height="70">
		Add Service Providers
	</div>
	<br>
	<div class="jumbotron">
		<div class="row">
			<div class="col-xs-12">
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered" style="float: left;">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Location</th>
								<th>Services</th>
								<th>Contact Phone</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php								
								$n = 0;
								foreach ($providers as $key => $provider) {
									$n+=1;
									?>
									<tr>
										<td><?=$n?></td>
										<td><?=$provider['name']?></td>
										<td><?=$provider['location']?></td>
										<td></td>
										<td></td>
										<td><a href="?view=">View</a></td>
									</tr>
									<?php
								}
							?>
										
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>