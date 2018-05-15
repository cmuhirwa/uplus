<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<link rel="stylesheet" type="text/css" href="assets/css/uikit.min.css">
	<!-- dropify -->
	<link rel="stylesheet" href="assets/skins/dropify/css/dropify.css">
	<?php
		$title = "Groups";
		//Including common head configuration
		include_once "head.php";
	?>
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
			<?php

				if($userType == 'group'){
					//here we have to identify group ID of this group admin

					$sql = "SELECT id FROM groups WHERE representative = \"$userId\" LIMIT 1";

					$query = $conn->query($sql) or trigger_error("Can't get admin group $conn->error");
					$data = $query->fetch_assoc();
					$groupId = $data['id'];

					if(!$groupId){
						die("You are not a group admin");
					}
				}

				if(!empty($groupId)){
					$group_id = $groupId;
					$group_data = group_details($group_id);
					$groupname = $group_data['name'];
					$grp_location = $group_data['mapLocation']??$group_data['location'];
					$gmembers = group_members($group_id);
					?>
						<div id="page_content_inner" data-page="group">           
							<div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
								<div class="uk-row-first"><h4 class=""><?php echo $churchname; ?> - Groups</h4></div>
							</div>
							<div class="md-card uk-margin-bottom">
								<div class="md-card-toolbar">
									<h4 class="md-card-toolbar-heading-text"><?php echo $groupname; ?></h4>
									<div class="md-card-toolbar-actions">
										<!-- <i class="md-icon material-icons md-color-blue-grey-500"></i> -->
										<!-- <i class="md-icon material-icons md-color-light-blue-500"></i> --><!-- 
										<i class="md-icon material-icons md-color-light-blue-500">message</i> -->
										<i class="md-icon material-icons md-color-red-500" title="Add a member" id="grp_remove" data-grp = <?php echo $group_id; ?> >delete</i>                                        
									</div>
								</div>
								<div class="md-card-content">
									<?php
										//Showing group members                                            
										if($gmembers){
										?>
											<div class="uk-overflow-container" style="max-width: 1000px;">
												<table id="dt_tableExport" class="uk-table memtable" data-group="<?php echo $group_id; ?>" cellspacing="0">
													<thead>
														<tr>
															<th>
																<input class="uk-checkbox checkall" type="checkbox">
															</th>
															<th>#</th>
															<th>Image</th>
															<th>Name</th>
															<th>Join date</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
														<?php 
														
														for($n=0; $n<count($gmembers); $n++ )
															{
																$member = $gmembers[$n];
																// $member = user_details($gmember['member']);
																$ppic=!empty($member['profile_picture'])?$member['profile_picture']:'gallery/members/default.png';                                                                    
																?>
																<tr data-member="<?php echo $member['id']; ?>">
																	<td><input class="uk-checkbox" data-user = "<?php echo $member['id'] ?>" type="checkbox"></td>
																	<td><?php echo $n+1; ?></td>
																	<td><img class="md-user-image" src="<?php echo $ppic; ?>" alt="img"></td>
																	<td><?php echo $member['name']; ?></td>
																	<td><?php echo $member['join_date']; ?></td>
																	<td style="cursor: pointer;" class="removemember"><i class="material-icons">indeterminate_check_box</i></td>
																</tr>
																<?php
															}
														?> 
														
													</tbody>
												</table>
											</div>
										<?php
										}else{
											//No members
											?>
												No members in group yet. Start adding members
											<?php
										}
									?>
								</div>
							</div>
						</div>
						<!-- Add member fab -->
						<div class="md-fab-wrapper ">
							<!-- <a class="md-fab md-fab-primary" href="javascript:void(0)"><i class="material-icons">add</i></a> -->
							<button class="md-fab md-fab-primary d_inline" id="launch_group_create" href="javascript:void(0)" data-uk-modal="{target:'#group_add_member'}"><i class="material-icons">person_outline</i></button>
						</div>
					<?php
				}
			?>
		<!-- Adding members to group -->
		<div class="uk-modal" id="group_add_member" aria-hidden="true" style="display: none; overflow-y: auto;">
			<div class="uk-modal-dialog" style="max-width:800px;">
				<div class="uk-modal-header uk-tile uk-tile-default">
					<h3 class="d_inline">Members Attendance</h3>
				</div>
						<?php
							//Showing group members
							$gmembers = group_members($group_id);
							if($gmembers){
							?>
								<div class="uk-overflow-container">
									<div class="md-input-wrapper uk-margin-bottom uk-margin-top">
										<label>Meetup date</label>
										<input type="text" name="date" class="md-input" id="meetupInput" data-uk-datepicker="{format:'DD-MM-YYYY', minDate: '2017-01-01'}>
										<span class="md-input-bar ">
									</div>
									<table id="dt_tableExport" class="uk-table" data-group cellspacing="0" width="100%" >
										<thead>
											<tr>
												<th><input class="uk-checkbox checkall" type="checkbox" checked></th>
												<th>Image</th>
												<th>Name</th>
											</tr>
										</thead>
										<tbody id="group_members">
											<?php 
											
											for($n=0; $n<count($gmembers); $n++ )
												{
													$gmember = $gmembers[$n];
													$ppic=!empty($gmember['profile_picture'])?$gmember['profile_picture']:'gallery/members/default.png';

													?>
													<tr>
														<td><input class="uk-checkbox checkbox_elem" data-member=<?php echo $gmember['id']; ?> type="checkbox" checked></td>
														<td><img class="md-user-image" src="<?php echo $ppic; ?>" alt="img"></td>
														<td><?php echo $gmember['name']; ?></td>
													</tr>
													<?php
												}
											?> 
											
										</tbody>
									</table>
								</div>
							<?php
							}else{
								//No members
								?>
									Amazing!:) Your groups looks new, please add some members.
								<?php
							}
						?>
					<div class="member_add_status"></div>
				<div class="uk-modal-footer uk-text-right">
					<button class="md-btn md-btn-danger pull-left uk-modal-close">CANCEL</button>
					<button id="record_att_submit" class="md-btn md-btn-success pull-right">RECORD <span id="add_member_num"></span></button>
				</div>
			</div>
		</div>
		<!--  saveGroupChangesModal -->
		<div class="uk-modal" id="saveGroupChangesModal" aria-hidden="true" style="display: none; overflow-y: auto;">
			<div class="uk-modal-dialog" style="max-width:800px;">
				<div class="uk-modal-header uk-tile uk-tile-default">
					<h3 class="d_inline">Updating group</h3>
				</div>
				<div class="uk-overflow-container" style="max-width: 500px;">
					<p>Are you sure you want to update changes on the group?</p>
				</div>
					<div class="member_add_status"></div>
				<div class="uk-modal-footer uk-text-right">
					<button class="md-btn md-btn-danger pull-left uk-modal-close">CANCEL</button>
					<button id="saveGroupChangesConfirm" class="md-btn md-btn-success pull-right">CONFIRM <span id="add_member_num"></span></button>
				</div>
			</div>
		</div>
		<!-- <div id="mapLoad"></div> -->
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

	<!--  contact list functions -->
	<script src="assets/js/pages/page_contact_list.min.js"></script>

	<script src="bower_components/dropify/dist/js/dropify.min.js"></script>

	<!-- mockAjax -->
	<script src="bower_components/jquery-mockjax/dist/jquery.mockjax.min.js"></script>
	<!-- jqueryUI -->
	<script src="bower_components/x-editable/dist/jquery-editable/jquery-ui-datepicker/js/jquery-ui-1.10.3.custom.min.js"></script>
	<!-- poshytip -->
	<script src="assets/js/custom/xeditable/jquery.poshytip.min.js"></script>
	<!-- select2 -->
	<script src="assets/js/custom/xeditable/select2/select2.min.js"></script>
	<!-- xeditable -->
	<script src="bower_components/x-editable/dist/jquery-editable/js/jquery-editable-poshytip.js"></script>

	<!--  xeditable functions -->
	<script src="assets/js/pages/plugins_xeditable.min.js"></script>


	<!-- Group specific custom script -->
	<script type="text/javascript" src="js/groups.js"></script>
	<script type="text/javascript">
		$("#record_att_submit").on('click', function(){
			//when attendance recors is clicked
			date = $("#meetupInput").val();

			members_checkboxes = document.querySelectorAll("#group_members tr td:first-child input");

			members_attendace = [];

			for(n=0; n<members_checkboxes.length; n++){
				m_elem = members_checkboxes[n]
				log(m_elem)
				break
				member_id = $(members_checkboxes[n]).data('user')
				member_status = $(m_elem)

				members_attendace.push({member:member_id, attended:member_status.prop('checked')})
			}
			log(members_attendace)


		})
		function loadmaps(){
			//Checking pagename
			pagename = $("#page_content_inner").attr('data-page');

			group_location = $(".group_map").attr("data-location");
			if(pagename == 'group'){
				//Loading the group's map
				var kigali = {lat:-1.991019, lng:30.096819};
				var map_location = {lat:parseFloat(group_location.split(",")[0]), lng:parseFloat(group_location.split(",")[1])};
				log(map_location);
				var map = new google.maps.Map(document.querySelector('.group_map'), {
					zoom: 17,
					center: map_location
				});
				var marker = new google.maps.Marker({
				  position: map_location,
				  map: map
				});
			}else if(pagename == 'home'){
				//Loading map for choosing location
				var kigali = {lat:-1.991019, lng:30.096819};
				var map_location = map_location;

				var map = new google.maps.Map(document.querySelector('#group_map'), {
					zoom: 17,
					center: map_location
				});
				var marker = new google.maps.Marker({
				  position: map_location,
				  map: map
				});

			}
		}

		$("#select_rep").editable({
			source:[
				{
					value:1, text:'Admin admin'
				}
			]
		})
	</script>

	<!-- Google maps -->
	<script src="https://maps.google.com/maps/api/js?key=AIzaSyAlKttaE2WuI1xKpvt-f7dBOzcBEHRaUBA&libraries=places&callback=loadmaps"></script>

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