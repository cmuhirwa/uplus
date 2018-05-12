<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<?php
		$title = "Members";
		//Including common head configuration
		include_once "head.php";
	?>
	 <!-- dropify -->
	<link rel="stylesheet" href="assets/skins/dropify/css/dropify.css">
</head>
<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
	<!-- main header -->
	<?php
		include_once "menu-header.php";
	?>
	<!-- main sidebar -->
	<?php
		include_once "sidebar.php";
	?>

	<div id="page_content">
		<div id="page_content_inner">
			<div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
				<div class="uk-row-first"><h4 class="">Members</h4></div>
			</div>

			<div class=" uk-grid uk-margin-bottom uk-grid-medium" data-uk-grid-margin>   
				<div class="uk-width-large-4-4">
					<div class="md-card">
						<div class="md-card-content">
							<div class=" uk-grid">
								<div class="uk-width-1-6">
									<h4 class="heading_c uk-margin-bottom">Church Growth</h4>
								</div>
								<div class="uk-width-5-6">
									<div class="uk-grid">
										<div class="uk-width-1-6">
											<div class="uk--select"  data-uk-form-select>
												<select id="selectService" class="md-input">
													<option value="service">-- Any Service --</option>
													<?php
													//Getting services
													$church_services = church_services($churchID);
													for ($n=0; $n<count($church_services); $n++) {
														$service = $church_services[$n];
														?>
															<option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="uk-width-1-6">
											<div class="uk--select"  data-uk-form-select>
												<select id="selectGender" class="md-input">
													<option value="">-- Gender --</option>
													<option value="Male">Male</option>
													<option value="Female">Female</option>
												</select>
											</div>
										</div>
										<div class="uk-width-1-6">
											<div class="md-input-wrapper">
												<label>From</label>
												<input type="text" name="date" class="md-input" id="startDateInput" data-uk-datepicker="{format:'DD-MM-YYYY', minDate: '2017-01-01'}>
												<span class="md-input-bar ">
											</div>
										</div>
										<div class="uk-width-2-6">
											<div class="md-input-wrapper">
													<label>To</label>
													<input type="text" name="date" class="md-input" id="endDateInput" data-uk-datepicker="{format:'DD-MM-YYYY', minDate: '2017-01-01'}">
													<span class="md-input-bar "></span>
											</div>                                        
										</div>
										<div class="uk-width-1-6">
											<span class="uk-input-group-addon"><button class="md-btn" id="generateChart">Generate</button></span>
										</div>
									</div>
								</div>                        
							</div>
							
							
							<canvas id="mem_attendance" class="attendance" width="400" height="80"></canvas>
							<div></div>
						</div>
					</div>
					<div class="md-card">
						
						<div class="md-card-content">
							<div id="status"></div>
							<div class="dt_colVis_buttons">
							</div>
							<table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Branch</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Address</th>
										<th>Type</th>
										<th>Date In</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$n=0;
									$sqlGetMembers = $db->query("SELECT m.*, m.name as memberName FROM `members` as m JOIN branches as b ON m.branchid = b.id  WHERE b.church = \"$churchID\" ORDER BY m.id ASC")or die ($db->error);
									while($rowMember = mysqli_fetch_assoc($sqlGetMembers))
										{
											$branchid = $rowMember['branchid'];
											$sqlGetMembersloc = $db->query("SELECT * FROM `branches` WHERE id = '$branchid'")or die ($db->error);
											$branches = mysqli_fetch_array($sqlGetMembersloc);
											$n++;
											echo '<tr>
											<td>'.$n.'</td>
											<td>'.$rowMember['memberName'].'</td>
											<td>'.$branches['name'].'</td>
											<td>'.$rowMember['phone'].'</td>
											<td>'.$rowMember['email'].'</td>
											<td>'.$rowMember['address'].'</td>
											<td>'.$rowMember['type'].'</td>
											<td>'.$rowMember['createdDate'].'</td>
											<td><a href="editmember.php?id='.$rowMember['id'].'"><i class="material-icons">mode_edit</i></a></td>
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
		<div class="modals">            
			<div class="uk-modal" id="add_member_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
				<div class="uk-modal-dialog" style="top: 339.5px;">
					<div class="uk-modal-header uk-tile uk-tile-default">
						<h3 class="d_inline">New Member</h3>
					</div>
					<form id="add_member_form">
						<div class="md-card">
							<div class="md-card-content">
								<div class="md-input-wrapper">
									<label>Full Name</label>
									<input type="text" name="membername" id="name_input" class="md-input" required="required">
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<label>Phone</label>
									<input type="number" name="memberphone" id="phone_input" class="md-input">
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<label>E-mail</label>
									<input type="email" name="memberemail" id="email_input" class="md-input">
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<label>Address</label>
									<input type="text" name="memberaddress" id="address_input" class="md-input">
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<select name="memberlocation" class="md-input" required="required" id="branch_input">
										<option value="">Branch...</option>
										<?php
											//Getting branches
											$branchesQuery = $conn->query(  "SELECT * FROM branches WHERE church = $churchID ") or die("Can't get branches ".$conn->error);
											$branches = array();
											while ($data = $branchesQuery->fetch_assoc()) {
												$branches[] = $data;
												?>
													<option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
												<?php
											}
										?>
									</select>
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<select name="membertype" class="md-input"  required="required" id="type_input">
										<option value="">Type...</option>
										<?php
											//getting types
											$member_types = member_types();
											for($n=0; $n<count($member_types); $n++){
												$mtype = $member_types[$n]['name'];
												?>
													<option value="<?php echo $mtype; ?>"><?php echo ucfirst($mtype); ?></option>
												<?php
											} 
										?>
									</select>
									<span class="md-input-bar "></span>
								</div>
							</div>                            
						</div>
					</form>
					<div id="addStatus" class="card mt-3" style="margin-top:20px"></div>

					<div class="uk-modal-footer uk-text-right act-dialog" data-role='init'>
						<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
						<button class="md-btn md-btn-success pull-right" id="add_member_btn">Save</button>
					</div>

					<div class="uk-modal-footer uk-text-right act-dialog display-none" data-role='done'>
						<button type="button" class="md-btn md-btn-flat uk-modal-close"><img src="assets/img/rot_loader.gif" style="max-height: 50px"> Adddig member...</button>
					</div>

				</div>
			</div>
			<!-- head count modal -->
			<div class="uk-modal" id="head_counts_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
				<div class="uk-modal-dialog" style="top: 339.5px;">
					<div class="uk-modal-header uk-tile uk-tile-default">
						<h3 class="d_inline">Enter head counts</h3>
					</div>
					<form method="POST" id="head_counts_form">
						<div class="md-card">
							<div class="md-card-content">
								<div class="md-input-wrapper">
									<select name="service" class="md-input" required="required" id="service-input">
										<option value="">Service...</option>
										<?php
											//Getting branches
											for ($n=0; $n<count($church_services); $n++) {
												$service = $church_services[$n];
												?>
													<option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?></option>
												<?php
											}
										?>
									</select>
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<select name="service" class="md-input" id="branch-input">
										<option value="">Branch...</option>
										<?php
											//Getting branches
											$branches = church_branches($churchID);
											for ($n=0; $n<count($branches); $n++) {
												$branch = $branches[$n];
												?>
													<option value="<?php echo $branch['id']; ?>"><?php echo $branch['name']; ?></option>
												<?php
											}
										?>
									</select>
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<label>Date</label>
									<input type="text" name="date" class="md-input" id="date-input" data-uk-datepicker="{format:'YYYY-MM-DD', minDate: '2017-01-01'}>
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<label>Number</label>
									<input type="number" name="membername" id="number-input" class="md-input" required="required">
									<span class="md-input-bar "></span>
								</div>
							</div>                            
						</div>
						<div class="uk-modal-footer uk-text-right">
							<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
							<button class="md-btn md-btn-success pull-right">Record</button>
						</div>
					</form>

				</div>
			</div>
			<div class="uk-modal" id="modal_upload_members" aria-hidden="true" style="display: none; overflow-y: auto;">
				<div class="uk-modal-dialog" style="top: 339.5px;">
					<div class="uk-modal-header uk-tile uk-tile-default">
						<h3 class="d_inline">Batch members upload</h3>
					</div>
					<form id="memExport" method="POST" enctype="multipart/form-data">
						<div class="md-card">
							<div class="md-card-content">
								<div class="md-input-wrapper">
									<label>Choose Excel file of members you want to export</label>
									<!-- <div class="dropify-wrapper">
										<div class="dropify-message">
											<span class="file-icon"></span> <p>Drag and drop a file here or click</p>
											<p class="dropify-error">Ooops, something wrong appended.</p>
										</div>
										<div class="dropify-loader"></div>
										<div class="dropify-errors-container">
											<ul></ul>
										</div>
										<input name = "file1" type="file" data-allowed-file-extensions="xls xlsx" id="file1" class="dropify">
										<button type="button" class="dropify-clear">Remove</button>
										<div class="dropify-preview">
											<span class="dropify-render"></span>
											<div class="dropify-infos">
												<div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Drag and drop or click to replace</p>
												</div>
											</div>
										</div>
									</div> -->
									<input type="file" id="file1" class="dropify" data-allowed-file-extensions="xls xlsx"/>
									<span class="md-input-bar "></span>
								</div>                                            
								<input type="hidden" name="action" value="export_members">
								<input type="hidden" name="church" value="<?php echo $churchID; ?>">
								<input type="hidden" name="user" value="<?php echo $userId; ?>">                       
							</div>
						</div>
						<div class="uk-modal-footer uk-text-right">
							<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
							<button type="submit" class="md-btn md-btn-success pull-right">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="md-fab-wrapper md-fab-speed-dial-horizontal">
		<a class="md-fab md-fab-primary" href="javascript:void(0)"><i class="material-icons">add</i><i class="material-icons md-fab-action-close" style="display:none"></i></a>
		<div class="md-fab-wrapper-small">
			<button class="md-fab md-fab-small md-fab-warning d_inline" href="javascript:void(0)" data-uk-modal="{target:'#add_member_modal'}"><i class="material-icons">person_add</i></button>
			<a class="md-fab md-fab-small md-fab-danger d_inline" href="javascript:void(0)" data-uk-modal="{target:'#modal_upload_members'}"><i class="material-icons">file_upload</i></a>
			<a class="md-fab md-fab-small md-fab-success d_inline" href="javascript:void(0)" data-uk-modal="{target:'#head_counts_modal'}"><i class="material-icons">account_circle</i></a>
		</div>
	</div>

	<!-- jQuery -->
	<script type="text/javascript" src="js/jquery.js"></script>

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


	<!-- Dropify -->
	<script src="bower_components/dropify/dist/js/dropify.min.js"></script>

	<script type="text/javascript" src="js/Chart.min.js"></script>
	<?php
	//Gettig data for chart
	$query = $db->query("SELECT SUM(num) as num, church_service.name as servicename FROM `attendance` JOIN church_service ON attendance.service = church_service.id JOIN branches ON attendance.branch = branches.id WHERE branches.church = \"$churchID\" GROUP BY servicename ") or die("Can'te get chart data $db->error");

	$chart_data = array();
	while ($data = $query->fetch_assoc()) {
		$chart_data[$data['servicename']] = $data['num'];
	}
	?>
	<script>

		//scripts
		$("#selectChart").on('change', function(data){
			selectedChart = $(this).val();

			// if(selectedChart == 'gender'){
			//     //load attendance data based on gender
			//     myChart

			// }
		})

	var ctx = document.getElementById("mem_attendance").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode(array_keys($chart_data)); ?>,
			datasets: [{
				label: '# of members(headcounts)',
				data: <?php echo json_encode(array_values($chart_data)); ?>,
				backgroundColor: [
					'rgba(0, 150, 136, 0.2)',
				],
				borderColor: [
					'rgba(0,150,136,1)',
				],
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true
					}
				}]
			}
		}
	});
	</script>

	<script src="js/uploadFile.js"></script>
	<script type="text/javascript">
		var churchID  = <?php echo $churchID; ?>;
		$('.dropify').dropify();

		$("#memExport").on('submit', function(e){
			e.preventDefault();
			uploadFile();

		});

		$(".selectize").selectize();

		function log(data){
			console.log(data)
		}

		// Button for generation
		$("#generateChart").on('click', function(){
			//extracting desired features
			service = $("#selectService").val()
			gender = $("#selectGender").val()

			startDate = $("#startDateInput").val()
			endDate = $("#endDateInput").val()

			//TODO: get logic here
		})

		$("#head_counts_form").on('submit', function(e){
			e.preventDefault();

			//head counts modal on submission
			service = $("#service-input").val();
			date = $("#date-input").val();
			number = $("#number-input").val();
			branch = $("#branch-input").val();

			if(service && date && number){
				//sending the data
				$.post('api/index.php', {action:'record_headcount', church:<?php echo $churchID; ?>, branch:branch, service:service, date:date, number:number}, function(data){
						try{
							ret = JSON.parse(data);
							if(ret.status){
								//Saved
								$("#head_counts_form").html("Saved successfully!");
								setTimeout(function(){
									location.reload();
								}, 700);
							}else{
								$("#head_counts_form").html("Error recording!<br />"+data.msg);
							}
						}catch(err){
							log(err)
						}
				})
			}            
		});

		$("#add_member_btn").on('click', function(e){
			e.preventDefault();
			//add individual user
			name = $("#name_input").val();
			phone = $("#phone_input").val();
			email = $("#email_input").val();
			branch = $("#branch_input").val();
			address = $("#address_input").val();
			type = $("#type_input").val();

			if(name && branch && type){

				//Marking the progress
				//Marking the sending process
				$("#add_member_modal .act-dialog[data-role=init]").hide();
				$("#add_member_modal .act-dialog[data-role=done]").removeClass('display-none');

				//USer can be submitted
				$.post('api/index.php', {action:'add_member', church:<?php echo $churchID; ?>, name:name, phone:phone, email:email, address:address, branch:branch, type:type}, function(data){
					try{
						ret = data;
						// if(typeof(data) != "object"){
						// 	ret = JSON.parse(data);
						// }else{
						// 	ret = data;
						// }

						if(ret == 'done'){
							//User done
							//create successfully(Giving notification and closing the modal);
							$("#addStatus").html("<p class='uk-text-success'>Member added successfully!</p>");
							
							setTimeout(function(){
								UIkit.modal($("#add_member_modal")).hide();
								window.location = 'members.php';
							}, 3000);
						}else{
							alert("We have problem saving the user")
						}
					}catch(e){
						alert("error"+e)
					}
				})
			}else{
				alert("Provide user details")
			}
		})

	</script>

	<script>
		$(function() {
			if(isHighDensity()) {
				$.getScript( "assets/js/custom/dense.min.js", function(data) {
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