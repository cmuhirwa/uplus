<?php
	error_reporting(E_ALL); 
	ini_set('display_errors', 1);
	$HOSTNAME = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";
?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php
	include'userheader.php';
	include'functions.php';
?>

<!-- main sidebar -->
<div id="new_comp">
	<?php

		$forum = $_GET['id']??"";
		if(!empty($forum)){

			//If submit request is issued
			if(!empty($_POST)){
				$forumData = getForum($forum);
				$usual_logo =  $forumData['icon'];
				$title = $_POST['forumtitle']??"";
				$intro = $_POST['intro']??"";

				if($intro && $title){
					$forum_logo = $_FILES['forum_logo'];

					if($forum_logo['size']>10){

						$ext = strtolower(pathinfo($forum_logo['name'], PATHINFO_EXTENSION)); //extensin

						if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg'){
							$filename = "invest/gallery/".strtolower(clean_string($title))."_".time().".$ext";
							if(!move_uploaded_file($forum_logo['tmp_name'], "../../$filename")){
								trigger_error("Error uploading the file");
								$filename = $usual_logo;
							}else{
								$filename = $HOSTNAME."invest/gallery/".strtolower(clean_string($title))."_".time().".$ext";
							}
						}else{
							$filename = $usual_logo;
						}
					}else{
						$filename = $usual_logo;
					}


					//referencing the host
					// $filename = $filename;

					//updating
					$sql = "UPDATE forums SET title = \"$title\", subtitle = \"$intro\", icon = \"$filename\", updatedDate = NOW(), updatedBy = '$thisid' WHERE id = \"$forum\"  ";
					$query = $conn->query($sql) or trigger_error($conn->error);
					if($query){
						// header("location:".$_SERVER['REQUEST_URI']);
					}
				}else{
					echo "Sure??";
				}
			}

			$forumData = getForum($forum);

			$forum_id = $forumData['id'];
			$forum_title = $forumData['title']??"";
			$forum_logo = $usual_logo =  $forumData['icon'];
			$forum_status = empty($forumData['archiveDate'])?'active':'archive';
			$forum_n_joined = n_forum_users($forum_id);
			$forum_joined = forum_users($forum_id);

			$forum_not_joined = forumn_non_users($forum_id);
			?>
				<div id="page_content">
					<div id="page_content_inner">
						<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="uk-form-stacked" id="user_edit_form" enctype="multipart/form-data">
							<div class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-large-7-10">
									<div class="md-card">
										<div class="user_heading" data-uk-sticky="{ top: 48, media: 960 }">
											<div class="user_heading_avatar fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img src="/<?php echo $forumData['icon']; ?>" alt="user avatar"/>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail"></div>
												<div class="user_avatar_controls">
													<span class="btn-file">
														<span class="fileinput-new"><i class="material-icons">&#xE2C6;</i></span>
														<span class="fileinput-exists"><i class="material-icons">&#xE86A;</i></span>
														<input type="file" name="forum_logo" id="user_edit_avatar_control">
													</span>
													<a href="#" class="btn-file fileinput-exists" data-dismiss="fileinput"><i class="material-icons">&#xE5CD;</i></a>
												</div>
											</div>
											<div class="user_heading_content">
												<h2 class="heading_b"><span class="uk-text-truncate" id="user_edit_uname"><?php echo $forum_title; ?></span><span class="sub-heading" id="user_edit_position">Started <?php echo date($standard_date, strtotime($forumData['createdDate'])); ?></span></h2>
											</div>
											<div class="md-fab-wrapper">
												 <?php
													if($forum_status == 'active'){
												?>
													<div class="md-fab md-fab-small md-fab-danger">
														<i class="material-icons md-color-red" id="delete_forum_btn" data-forum = "<?php echo $forum; ?>" title="delete forum">&#xE872;</i>
													</div>
												<?php }else{
													?>
														<div class="md-fab md-fab-small md-fab-success">
															<i class="material-icons md-color-red" id="activate_forum_btn" data-forum = "<?php echo $forum; ?>" title="Re-Activate forum">done</i>
														</div>
													<?php
												} ?>
												
											</div>
										</div>
										<div class="user_content">
											
											<div class="md-input-wrapper md-input-filled">
												<label>Forum title</label>
												<input type="text" name="forumtitle" id="forumtitle_input" value="<?php echo $forum_title; ?>" class="md-input" required="required">
												<span class="md-input-bar "></span>
											</div>
											<div class="md-input-wrapper md-input-filled">
												<textarea cols="20" maxlength="120" rows="2" id="forum_intro" name="intro" class="md-input autosized" placeholder="What's the forum about?" style="overflow-x: hidden; word-wrap: break-word;"><?php echo $forumData['subtitle']; ?></textarea>
												<span class="md-input-bar "></span>
											</div>
											<div class="md-input-wrapper">
												<button class="md-btn md-btn-primary" type="submit">UPDATE</button>
											</div>                                         
										</div>
									</div>
								</div>
								<div class="uk-width-large-3-10">
									<div class="md-card">
										<div class="md-card-content">
											<h3 class="heading_c uk-margin-medium-bottom">Summary</h3>
											<div class="uk-form-row">
												<i class="md-icon material-icons md-color-light-blue-500">person_outline</i> <?php echo $forum_n_joined." of ".total_users(); ?> joined
													<?php
														if(!empty($forumData['updatedDate']) && 0){
															?>
															  <li>Last updated: <?php echo $forumData['updatedDate'] ?> by <i><?php echo staff_details($forumData['updatedBy'])['name'] ?></i></li>  
															<?php
														}

													?>

												<!-- <?php
													if($forum_status == 'active' ){
												?>
													<input type="checkbox" checked data-switchery id="user_edit_active" disabled/>
													<label for="user_edit_active" class="inline-label">Forum Active</label>
												<?php }else{
													?>
														<input type="checkbox" data-switchery id="user_edit_active" disabled/>
														<label for="user_edit_active" class="inline-label">Forum Archived</label>
													<?php
												} ?> -->
											</div>                                
										</div>
									</div>
								</div>
							</div>
						</form>

						<?php
							//Showing members of the forum
							if($forum_n_joined>0){
						?>
						<div class="uk-grid uk-margin-top">
							<div class="uk-width-1-1">
								<div class="md-card">
									<div class="md-card-content">
										<h4 class="heading_a uk-margin-bottom">Forum members</h4>
										<div class="dt_colVis_buttons">
										</div>
										<table id="dt_individual_search" class="uk-table" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>#</th>
													<th>Image</th>
													<th>Names</th>													
													<th>Gender</th>
													<th>Phone</th>
													<th>Email</th>
													<th>Joined Date</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$n=0;
												foreach($forum_joined as $key=> $member)
													{
														$n++;
														$memberData = user_details($member['userCode']);
														$gender  = empty($memberData['gender'])?"Male":"Female";

														$userImage = $memberData['userImage'];
														if(!$userImage){
															$userImage = HOSTNAME."frontassets/img/logo_main_3.png";
														}

														echo '<tr>
														<td>'.$n.'</td>
														<td><img class="md-user-image" style="width:42px;height:42px" src="'.$userImage.'"/></td>
														<td>'.$memberData['name'].'</td>
														<td>'.$gender.'</td>
														<td>'.$memberData['phone'].'</td>
														<td>'.$memberData['email']??"-".'</td>
														<td>'.date($standard_date, strtotime($member['createdDate']) ).'</td>';
													}
												?> 
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>

					</div>

					<div class="md-fab-wrapper">
						<button class="md-fab md-fab-primary" href="javascript:void(0)" data-uk-modal="{target:'#add_member_modal'}"><i class="material-icons">person_add</i></button>
					</div>

					<div class="modals">            
						<div class="uk-modal" id="add_member_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
							<div class="uk-modal-dialog" style="top: 339.5px;">
								<div class="uk-modal-header uk-tile uk-tile-default">
									<h3 class="d_inline">Invite members to forum</h3>
								</div>
								<form id="invite_member_form">
									<div class="md-carda">
										<div class="md-card-contenta">
											<div class="uk-tab-center1">
												<ul class="uk-tab" data-uk-tab="{connect:'#tabs_5'}">
													<li class="uk-active"><a href="#">Users</a></li>
													<li><a href="#">Email</a></li>
												</ul>
											</div>
											<ul id="tabs_5" class="uk-switcher uk-margin">
												<li>
													<label>Select members to invite</label>
													<table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
														<thead>
															<tr>
																<th><input type="checkbox" class="uk-checkbox checkall" data-md-ichecka></th>
																<th>Names</th>
																<th>Gender</th>
																<th>Type</th>
															</tr>
														</thead>
														<tbody id='app_invite_users'>
															<?php 
															$n=0;

															foreach($forum_not_joined as $key=> $member)
																{
																	$n++;
																	$memberData = $InvestUser->details($member['id']);

																	// var_dump($memberData);
																	echo '<tr>
																	<td> <input type="checkbox" class="uk-checkbox checkbox_elem" data-id='.$memberData['id'].'> </td>
																	<td>'.$memberData['name'].'</td>
																	<td>'.$memberData['gender'].'</td>
																	<td>'.$memberData['account_type'].'</td>';
																}
															?> 
															
														</tbody>
													</table>
												</li>
												<li>
													
													<div class="md-input-wrapper md-input-filled uk-margin-top">
														<label>Enter emails you want to envite</label>
														<textarea id="invite_emails"  class="md-input label-fixed" placeholder="Email list separated by comma ','"></textarea>
														<span class="md-input-bar "></span>
													</div>
												</li>
											</ul>
										</div>                            
									</div>

									<div class="md-input-wrapper md-input-filled uk-margin-top">
										<label>Invitation message</label>
										<textarea id="invite_text"  class="md-input label-fixed" placeholder="Enter invitation">You are invited to join '<?php echo $forum_title; ?>' forum on u-invest, https://uplus.rw click here for more info</textarea>
										<span class="md-input-bar "></span>
									</div>
								</form>
								<div id="addStatus" class="card mt-3" style="margin-top:20px"></div>


								<div class="uk-modal-footer uk-text-right act-dialog" data-role='init'>
									<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
									<button class="md-btn md-btn-success pull-right" id="invite_member_btn">Invite</button>
								</div>

								<div class="uk-modal-footer uk-text-right act-dialog display-none" data-role='done'>
									<button type="button" class="md-btn md-btn-flat uk-modal-close"><img src="assets/img/rot_loader.gif" style="max-height: 50px"> Inviting people to the forum...</button>
								</div>

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
			<?php
		}else{
	?>
		<div id="page_content">
			<div id="page_content_inner">
				<div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
					<div class="uk-row-first"><h4 class="">All forums</h4></div>
				</div>

				<div class=" uk-grid uk-margin-bottom uk-grid-medium" data-uk-grid-margin>   
					<div class="uk-width-large-4-4">
						<!-- <div class="md-card">
							<div class="md-card-content">
								<div class=" uk-grid">
									<div class="uk-width-3-4">
										<h4 class="heading_c uk-margin-bottom">Forums engagement</h4>
									</div>
									<div class="uk-width-1-4">
										<form class="-form">
											<div class="uk--select"  data-uk-form-select>
												<select id="selectChart" class="md-input">
													<option value="service">Service</option>
													<option value="gender">Gender</option>
													<option value="days">Days</option>
													<option value="time">Time interval</option>
												</select>
											</div>
										</form>
									</div>
								</div>
								
								<canvas id="mem_attendance" class="attendance" width="400" height="80"></canvas>
								<div ></div>
							</div>
						</div> -->
						<div class="md-card">
							<?php
								$forums = getForums();
								foreach ($forums as $key => $data){
								}
							?>
							<div id="status"></div>
							<div class="md-card-content">
								<!-- <div class="dt_colVis_buttons"></div> -->
								<table id="dt_individual_search" class="uk-table" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Forum title</th>
											<th>Created By</th>
											<th>Date created</th>
											<th>Participants</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$n=0;
											
											foreach ($forums as $key => $data){
												$admin  = staff_details($data['createdBy']);
												$n++;
												echo '<tr>
												<td>'.$n.'</td>
												<td>'.$data['title'].'</td>
												<td>'.$admin['name'].'</td>
												<td>'.date($standard_date." H:i:s", strtotime($data['createdDate'])).'</td>
												<td>'.n_forum_users($data['id']).'</td>
												<td><a href="forums.php?id='.$data['id'].'"><i class="material-icons">mode_edit</i></a></td>
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
							<h3 class="d_inline">New Forum</h3>
						</div>
						<form id="create_forum_form">
							<div class="md-input-wrapper">
								<label>Forum title</label>
								<input type="text" name="membername" id="forumtitle_input" class="md-input" required="required">
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper md-input-filled">
								<!-- <label>Introduction</label> -->
								<textarea cols="30" rows="3" id="forum_intro" class="md-input autosized" placeholder="What's the forum about?" style="overflow-x: hidden; word-wrap: break-word;"></textarea>
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper md-input-filled">
								<input type="file" id="input-forum-logo" name="logo" data-height="100" data-height="100" class="dropify" data-allowed-file-extensions="png jpg jpeg" required="required"/>
								<span class="md-input-bar "></span>
							</div>
						</form>
						<div id="addStatus" class="card mt-3" style="margin-top:20px"></div>

						<div class="uk-modal-footer uk-text-right act-dialog" data-role='init'>
							<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
							<button class="md-btn md-btn-success pull-right" id="create_forum_btn">CREATE</button>
						</div>

						<div class="uk-modal-footer uk-text-right act-dialog display-none" data-role='done'>
							<button type="button" class="md-btn md-btn-flat uk-modal-close"><img src="assets/img/rot_loader.gif" style="max-height: 50px"> Creating a forum...</button>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="md-fab-wrapper">
			<button class="md-fab md-fab-primary" href="javascript:void(0)" data-uk-modal="{target:'#add_member_modal'}"><i class="material-icons">add</i></button>
		</div>
	<?php } ?>
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
	<!-- d3 -->
	<script src="bower_components/d3/d3.min.js"></script>
	<!-- metrics graphics (charts) -->
	<script src="bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
	<!-- chartist (charts) -->
	<!-- <script src="bower_components/chartist/dist/chartist.min.js"></script> -->
	 <!-- peity (small charts) -->
	<script src="bower_components/peity/jquery.peity.min.js"></script>
	<!-- easy-pie-chart (circular statistics) -->
	<script src="bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
	<!-- countUp -->
	<script src="bower_components/countUp.js/dist/countUp.min.js"></script>
	<!-- handlebars.js -->
	<script src="bower_components/handlebars/handlebars.min.js"></script>
	<script src="assets/js/custom/handlebars_helpers.min.js"></script>
	<!-- CLNDR -->
	<script src="bower_components/clndr/clndr.min.js"></script>

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

	<!--  dashbord functions -->
	<!-- <script src="assets/js/pages/dashboard.min.js"></script> -->

	<!-- Dropify -->
	<script src="bower_components/dropify/dist/js/dropify.min.js"></script>

	<script type="text/javascript" src="js/Chart.min.js"></script>

	<!-- file input -->
	<script src="assets/js/custom/uikit_fileinput.min.js"></script>

	<!--  user edit functions -->
	<script src="assets/js/pages/page_user_edit.min.js"></script>

	<!-- Firebase -->
	<script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>

	<script src="js/uploadFile.js"></script>

	<script type="text/javascript" src="js/forums.js"></script>

	<script type="text/javascript">
		var churchID  = 1;
		$('.dropify#input-forum-logo').dropify();
		$(".selectize").selectize();

		$("#delete_forum_btn").on('click', function(){
			//when the foru, is to be deleted
			forum_id = $(this).data('forum');

			//ask for confirmation
			UIkit.modal.confirm("Do you want to archive this forum?", function(){
				// will be executed on confirm.
				$.post('api/index.php', {action:'archive_forum', forum:forum_id, user:<?php echo $thisid; ?>}, function(data){
					// alert(data)
					if(typeof(data) != 'object'){
						ret = JSON.parse(data);
					}else{
						ret = data;
					}
					location = 'forums.php';
				})
			});
		})

		$("#activate_forum_btn").on('click', function(){
			//when the foru, is to be activated
			forum_id = $(this).data('forum');

			//ask for confirmation
			UIkit.modal.confirm("Do you want to Re-Activate this forum?", function(){
				// will be executed on confirm.
				$.post('api/index.php', {action:'activate_forum', forum:forum_id, user:<?php echo $thisid; ?>}, function(data){
					if(typeof(data) != 'object'){
						ret = JSON.parse();
					}else{
						ret = data;
					}

					location.reload();
				})
			})
		})

		function log(data){
			console.log(data)
		}

		$("#create_forum_btn").on('click', function(e){
			e.preventDefault();
			//add individual user
			title = $("#forumtitle_input").val();
			intro = $("#forum_intro").val();
			logo =  document.querySelector("#input-forum-logo").files[0];

			if(title && intro){
				//Marking the progress
				//Marking the sending process
				$("#add_member_modal .act-dialog[data-role=init]").hide();
				$("#add_member_modal .act-dialog[data-role=done]").removeClass('display-none');

				var formdata = new FormData();
				fields = {action:'create_forum', title:title, intro:intro, admin:<?php echo $thisid; ?>, logo:logo};

				for (var prop in fields) {
					formdata.append(prop, fields[prop]);
				}
				var ajax = new XMLHttpRequest();

				ajax.addEventListener("load", function(){
					response = this.responseText;
					try{
						ret = JSON.parse(response);
						if(ret.status){
							//User done
							//create successfully(Giving notification and closing the modal);
							$("#addStatus").html("<p class='uk-text-success'>Forum added successfully!</p>");
							
							setTimeout(function(){
								UIkit.modal($("#add_member_modal")).hide();
								location.reload();
							}, 3000);
						}
					}catch(e){
						alert("error"+e)
					}

				}, false);

				ajax.open("POST", "api/index.php");
				ajax.send(formdata);
			}else{
				alert("Provide user details")
			}

		})
	</script>

	<!-- Firebase -->
	<script>
	  // Initialize Firebase
	  // TODO: Replace with your project's customized code snippet
	  // var config = {
	  //   apiKey: "AIzaSyB1qCWTLud__LGEFQQCZU98iMiy-Dp8Tbk",
	  //   authDomain: "learnbase-baa6d.firebaseapp.com",
	  //   databaseURL: "https://learnbase-baa6d.firebaseio.com",
	  //   projectId: "learnbase-baa6d",
	  //   storageBucket: "learnbase-baa6d.appspot.com",
	  //   messagingSenderId: "483987540771"
	  // };
	  // firebase.initializeApp(config);

	  // const preObject = document.getElementById("firebase")
	  // const dbRefObj = firebase.database().ref().child('firebase')

	  // dbRefObj.on('value', function(){
		
	  // })
	</script>


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
</script>
</body>
</html>