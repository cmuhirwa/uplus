<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php
	include("userheader.php");
	//including group class
	include_once("../../scripts/class.group.php");
	require_once("../../scripts/class.user.php");
	$users = $User->listAll();
	// var_dump($users);
?>
	<div id="page_content">
		<div id="page_content_inner">

			<h4 class="heading_a uk-margin-bottom">U-Invest Users</h4>
		   
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin>
				<div class="uk-width-large-4-4">
					<div class="md-card uk-margin-medium-bottom">
						<div class="md-card-content">
							<table id="dt_individual_search" class="uk-table" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Image</th>
										<th>userName</th>
										<th>Phone</th>                                        
										<th>Email</th>                                        
										<th>Joined Date</th>
									</tr>
								</thead>

								<tfoot>
									<tr>
										<th>#</th>
										<th>Image</th>
										<th>userName</th>
										<th>Phone</th>                                        
										<th>Email</th>                                        
										<th>Joined Date</th>
									</tr>
								</tfoot>
								<tbody>
									<?php
										$n= 0;
										foreach ($users as $key => $userData)
										{
											$n++;
											$name = $userData['name']??"-";
											$userImage = $userData['userImage'];
											if(!$userImage){
												$userImage = HOSTNAME."frontassets/img/logo_main_3.png";
											}
											$phone = $userData['phone'];
											$email = $userData['email']??"-";
											$createdDate = $userData['createdDate'];
											echo '
												<tr>
													<td>'.$n.'</td>
													<td><img class="md-user-image" style="width:60px;height:60px;" src="'.$userImage.'" /></td>
													<td>'.$name.'</td>
													<td>'.$phone.'</td>
													<td>'.$email.'</td>
													<td>'.$createdDate.'</td>
												</tr>';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- google web fonts -->
	<script>
		WebFontConfig = {
			google: {
				families: [
					'Source+Code+Pro:400,700:latin',
					'Roboto:400,300,500,700,400italic:latin'
				]
			}
		};
		(function() {
			var wf = document.createElement('script');
			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
			'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		})();
	</script>

	<!-- common functions -->
	<script src="assets/js/common.min.js"></script>
	<!-- uikit functions -->
	<script src="assets/js/uikit_custom.min.js"></script>
	<!-- altair common functions/helpers -->
	<script src="assets/js/altair_admin_common.min.js"></script>

	<!-- page specific plugins -->
	<!-- datatables -->
	<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
	<!-- datatables buttons-->
	<script src="bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
	<script src="assets/js/custom/datatables/buttons.uikit.js"></script>
	<script src="bower_components/jszip/dist/jszip.min.js"></script>
	<script src="bower_components/pdfmake/build/pdfmake.min.js"></script>
	<script src="bower_components/pdfmake/build/vfs_fonts.js"></script>
	<script src="bower_components/datatables-buttons/js/buttons.colVis.js"></script>
	<script src="bower_components/datatables-buttons/js/buttons.html5.js"></script>
	<script src="bower_components/datatables-buttons/js/buttons.print.js"></script>
	
	<!-- datatables custom integration -->
	<script src="assets/js/custom/datatables/datatables.uikit.min.js"></script>

	<!--  datatables functions -->
	<script src="assets/js/pages/plugins_datatables.min.js"></script>
	
	 <!-- page specific plugins -->
	<!-- d3 -->
	<script src="bower_components/d3/d3.min.js"></script>
	<!-- c3.js (charts) -->
	<script src="bower_components/c3js-chart/c3.min.js"></script>
	
	<!--  charts functions -->
	<script src="assets/js/pages/plugins_charts.min.js"></script>
	
	<script>
		$(function() {
			if(isHighDensity()) {
				$.getScript( "bower_components/dense/src/dense.js", function() {
					// enable hires images
					altair_helpers.retina_images();
				});
			}
			if(Modernizr.touch) {
				// fastClick (touch devices)
				FastClick.attach(document.body);
			}
		});
		$window.load(function() {
			// ie fixes
			altair_helpers.ie_fix();
		});
	</script>

</body>
</html>
<!-- Localized -->