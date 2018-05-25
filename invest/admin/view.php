<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php
	include("userheader.php");
	include_once("functions.php");
	//including group class
    include("../../scripts/class.group.php");
?>
<?php
if(isset($_GET['viewid']))
{
	$viewid = $_GET['viewid'];
	$sqlview = $db->query("SELECT * FROM clients where id = '$viewid' LIMIT 1");

	$client = $row = mysqli_fetch_array($sqlview);
	
	$clientType = $client['clientType'];
	if($clientType == 'group'){
		$clientId = $client['groupCode'];
		//Loading group details
		$groupId = $client['groupCode'];
		$clientData = $groupData = $Group->details($groupId);
		$names = $groupData['groupName'];
		$nationality = $row['country'];
		$clientImg = $clientData['groupImage'];

	}else{
		$title = $row['title'];

		$clientId = $client['userCode'];
					
		$names = $row['names'];
		$dob = $row['dob'];
		$gender = $row['gender'];			
		$nidPassport = $row['NID'];
		$nationality = $row['nationality'];
		$postalLine1 = $row['postalLine1'];
		$postalLine2 = $row['postalLine2'];
		$phyisicalLine3 = $row['phyisicalLine3'];
		$postCode = $row['postCode'];
		$city = $row['city'];
		$email = $row['e-mail'];

		$clientUserId = $client['userCode'];

		$clientData = user_details($clientUserId);
		$clientImg = $clientData['userImage'];

	}


	//Getting general clients information
	$status = $row['status'];
	$country = $row['country'];
	$taxCode = $row['taxCode'];
	$residentIn = $row['residentIn'];
	$telephone = $row['telephone'];
	$fax = $row['fax'];
	
	$bankName = $row['bankName'];
	$branch = $row['branch'];
	$accountNumber = $row['accountNumber'];
	$csdAccount= $row['csdAccount'];

	
}?>
	<style>
		@media print
		{    
		    .csdBtns
			{
				display: none !important;
		   	}
		}
	</style>
    <div id="page_content">
        <div id="page_content_inner">
        	<?php
        		if($clientType == 'Individual'){
        	?>
				<table width="100%" >
					<tr>
						<td width="10%"><img src="../assets/images/bnr.jpg"></td>
						<td width="65%">
							<center >
								<h2><b>Central Securities Depository - Rwanda</h2>
								<h4>Securities Account Opening/Update Form - Individuals: No <b><?php echo $csdAccount??"Pending"; ?></b></h4>
							</center>
						</td>
						<td width="15%">

							    <div style="
							    background-image: url(<?php echo $clientImg; ?>);
							    width: 176px;
							    height: 176px;
							    background-size: cover;
							    background-repeat: no-repeat;
							    background-position: center center;
							    float:  right;
							    background-color: #d3d5db;
							    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
							">
								<img src="<?php echo $clientImg; ?>" style="width: 176px;
							    height: 176px;float:  right;">
							</div>
						</td>
					<tr>
				</table>
				<hr style="margin: unset;">

	            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
	                <div class="uk-width-large-4-4">
	                    <div class="md-card uk-margin-medium-bottom">
	                        <div class="md-card-content">
								<center><h3 style="margin: unset; color:#b1461b;"><b>To be completed in BLOCK LETTERS</b></h3></center>
								<table width="100%" border="1" style="border-spacing: unset;">
									<tr>
										<td><b>Primary Applicant</b></td>
									</tr>
									<tr>
										<td>
											<table width="100%">
												<tr>
													<td>
														<table>
															<tr>
																<td width="100%">Names:<br><input value="<?php echo $names;?>" disabled></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td>
														<table>
															<tr>
																<td width="30%">Date Of Birth:<br><input value="<?php echo $dob;?>" disabled></td>
																<td width="20%">Gender:<br><input value="<?php echo $gender;?>" disabled></td>
																<td width="40%">National ID/Passport No:<br><input value="<?php echo $nidPassport;?>" disabled></td>
																<td width="30%">Nationality:<br><input value="<?php echo $nationality;?>" disabled></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr><td></td></tr>
												<tr><td></td></tr>
											</table>
										</td>
									</tr>
								</table>
								<div id="csd" class="uk-margin-top csdBtns">
									<?php
										if($status == 'approved'){
											echo "Approved";
										}else if($status == 'declined'){
											echo "Declined";
										}else{
											?>
												<button data-uk-modal="{target:'#approve_csd_modal'}" class="uk-button uk-button-primary">Approve</button>
												<button data-uk-modal="{target:'#deny_csd_modal'}" class="uk-button uk-button-danger">Decline</button>
											<?php
										}
									?>
									<br><br>
									<button onClick="window.print()" class="md-btn"><i class="material-icons">print</i></button>
								</div>
							</div>
						</div>
						<?php
							if($status == 'approved'){
								//Load some other stuffs for customer relationship
								$messages = brokerMessages($thisid, $viewid);
								?>
									<div class="md-card uk-margin-medium-bottom">
										<div class="md-card-content">
											<div class="uk-grid">
												<div class="uk-width-3-4" style="border-right: 1px solid #eee; padding-right: 5%">
													<h4>Client Stock Transactions</h4>
													<div class="dt_colVis_buttons">
													</div>
													<table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
														<thead>
															<tr>
																<th>#</th>
																<!-- <th>Client name</th> -->
																<th>Type</th>
																<th>Stock name</th>
																<th>Number</th>
																<th>Amount</th>
																<th>Date</th>
																<!-- <th>Action</th> -->
															</tr>
														</thead>
														<tbody>
															<?php
																$transactions = userTransactions($clientUserId);
																$n=0;
																foreach ($transactions as $key => $transaction){
																	// $totalAmt = $stockSale['quantity']*timeStockPrice($stockSale["stockId"], $stockSale['createdDate']);
																	$totalAmt = $transaction['totalAmount'];
																	$n++;
																	echo '<tr>
																	<td>'.$n.'</td>
																	<td>'.$transaction['type'].'</td>
																	<td>'.$transaction['companyName'].'</td>
																	<td>'.$transaction['quantity'].'</td>
																	<td>'.number_format($totalAmt).' FRW</td>
																	<td>'.date($standard_date." H:i:s", strtotime($transaction['createdDate'])).'</td>
																	</tr>';
																}
															?>
															
														</tbody>
													</table>
													<hr>
													<div class="commentsContainer uka-hidden">
														<div class="uk-grid">
															<div class="uk-width-3-4">
																<div style="height: 32px; width: 100%; padding: 0px; margin: 0 -100px;"></div>
																<form class="messageForm" method="POST" action="view.php?viewid=<?php echo $viewid; ?>">
																	<div class="md-input-wrapper">
																		<label>Message</label>
																		<textarea class="md-input" style="border: 1px solid #eee; border-radius: 2px;"></textarea>
																		<span class="md-input-bar "></span>
																	</div>
																	<input type="hidden" id="userId" value="<?php echo $viewid; ?>">
																	<div class="md-input-wrapper uk-float-right">
																		<p>
																			<?php
																				if($telephone){
																					?>
																					<span>
														                                <input type="checkbox" class="uk-checkbox messageChannels" name="smsMessage" data-md-icheck id='msgsendemail' />
														                                <label for="msgsendemail" class="inline-label">SMS</label>
														                            </span>&nbsp;&nbsp;&nbsp;&nbsp;
														                            <?php
																				}
																			?>

																			<?php
																				if($email){
																					?>
																					<span>
														                                <input type="checkbox" class="uk-checkbox messageChannels" name="emailMessage" data-md-icheck id="msgsendsms" />
														                                <label for="msgsendsms" class="inline-label">email</label>
														                            </span>
														                            <?php
																				}
																			?>						
														                            
											                            </p>
																	</div>
																	<div class="md-input-wrapper">
																		<button class="uk-button uk-button-default uk-float-right" type="submit">SEND</button>
																		<span class="md-input-bar "></span>
																	</div>
																</form>
															</div>
															<div class="uk-width-1-4">
															</div>
														</div>
														<div style="height: 12px; width: 100%; padding: 0px; margin: 0 -100px;"></div>
														<div class="clearfix uk-margin-top">                                                    
															<ul class="uk-list uk-list-line">
																<?php
																	foreach ($messages as $key => $message) {
																		?>
																		<li>
																			<div class="uk-grid">
																				<div class="comment-head">
																					<div class="thumbnail">
																						<img class="user avatar inline" style="height: 72px; width: 72px; border-radius: 50%" src="<?php echo $Company->standardLogo ?>">
																						<div class="inline">
																							<p style="vertical-align: middle; font-family: verdana; color: #0a3482">
																								<i><?php echo $userName ?></i>
																							</p>
																							<p class="uk-text-muted" style="margin: -15px 0 0 0">
																								<small><?php echo $message['createdDate'] ?></small>
																							</p>
																						</div>
																																									
																					</div>
																				</div>
																				<div class="uk-width-1-1 uk-margin-top">
																					<?php echo $message['message'] ?>
																				</div>
																			</div>
																		</li>
																		<?php
																	}
																?>
															</ul>
														</div>											
													</div>
												</div>
												<div class="uk-width-1-4">
													<div class="">
														<h4>Owners(1)</h4>
														<ul class="uk-list">
															<li><?php echo $names; ?></li>
														</ul>
													</div>												
												</div>
											</div>
											
										</div>
									</div>
								<?php
							}
						?>
					</div>
				</div>
			<?php
				}else if($clientType == 'group'){
					?>
						<table width="100%" >
							<tr>
								<td width="10%"><img src="../assets/images/bnr.jpg"></td>
								<td width="65%">
									<center >
										<h2><b>Central Securities Depository - Rwanda</h2>
										<h4>Securities Account Opening/Update Form - Groups: No <b><?php echo $csdAccount??"Pending"; ?></b></h4>
									</center>
								</td>
								<td width="15%">

									    <div style="
									    background-image: url(<?php echo $clientImg; ?>);
									    width: 176px;
									    height: 176px;
									    background-size: cover;
									    background-repeat: no-repeat;
									    background-position: center center;
									    float:  right;
									    background-color: #d3d5db;
									    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
									">
										<img src="<?php echo $clientImg; ?>" style="width: 176px;
									    height: 176px;float:  right;">
									</div>
								</td>
							<tr>
						</table>
						<hr style="margin: unset;">

			            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
			                <div class="uk-width-large-4-4">
			                    <div class="md-card uk-margin-medium-bottom">
			                        <div class="md-card-content">
										<center><h3 style="margin: unset; color:#b1461b;"><b>To be completed in BLOCK LETTERS</b></h3></center>
										<table width="100%" border="1" style="border-spacing: unset;">
											<tr>
												<td><b>Group Applicant</b></td>
											</tr>
											<tr>
												<td>
													<table width="100%">
														<form class="uk-form" style="padding: 25px">
															<div class="uk-grid">
																<div class="uk-width-1-2 uk-form-row">
																	<label for="nameIn" class="block">Names: </label>
																	<input type="text" id="nameIn" name="" value="<?php echo $names; ?>" disabled>
																</div>
																<div class="uk-width-1-2 uk-form-row">
																	<label for="countIn" class="block">Country: </label>
																	<input type="text" id="countIn" name="" value="<?php echo $nationality; ?>" disabled>
																</div>
															</div>
														</form>
													</table>
												</td>
											</tr>
										</table>
										<div id="csd" class="uk-margin-top csdBtns">
											<?php
												if($status == 'approved'){
													echo "Approved";
												}else if($status == 'declined'){
													echo "Declined";
												}else{
													?>
														<button data-uk-modal="{target:'#approve_csd_modal'}" class="uk-button uk-button-primary">Approve</button>
														<button data-uk-modal="{target:'#deny_csd_modal'}" class="uk-button uk-button-danger">Decline</button>
													<?php
												}
											?>
											<br><br>
											<button onClick="window.print()" class="md-btn"><i class="material-icons">print</i></button>
										</div>
									</div>
								</div>
								<?php
									if($status == 'approved'){
										//Load some other stuffs for customer relationship
										$messages = brokerMessages($thisid, $viewid);
										?>
											<div class="md-card uk-margin-medium-bottom">
												<div class="md-card-content">
													<div class="uk-grid">
														<div class="uk-width-3-4" style="border-right: 1px solid #eee; padding-right: 5%">
															<h4>Client Stock Transactions</h4>
															<div class="dt_colVis_buttons">
															</div>
															<table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
																<thead>
																	<tr>
																		<th>#</th>>
																		<th>Type</th>
																		<th>Stock name</th>
																		<th>Number</th>
																		<th>Amount</th>
																		<th>Date</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		$transactions = userTransactions($clientUserId);
																		$n=0;
																		foreach ($transactions as $key => $transaction){
																			// $totalAmt = $stockSale['quantity']*timeStockPrice($stockSale["stockId"], $stockSale['createdDate']);
																			$totalAmt = $transaction['totalAmount'];
																			$n++;
																			echo '<tr>
																			<td>'.$n.'</td>
																			<td>'.$transaction['type'].'</td>
																			<td>'.$transaction['companyName'].'</td>
																			<td>'.$transaction['quantity'].'</td>
																			<td>'.number_format($totalAmt).' FRW</td>
																			<td>'.date($standard_date." H:i:s", strtotime($transaction['createdDate'])).'</td>
																			</tr>';
																		}
																	?>
																	
																</tbody>
															</table>
															<hr>
															<div class="commentsContainer uka-hidden">
																<div class="uk-grid">
																	<div class="uk-width-3-4">
																		<div style="height: 32px; width: 100%; padding: 0px; margin: 0 -100px;"></div>
																		<form class="messageForm" method="POST" action="view.php?viewid=<?php echo $viewid; ?>">
																			<div class="md-input-wrapper">
																				<label>Message</label>
																				<textarea class="md-input" style="border: 1px solid #eee; border-radius: 2px;"></textarea>
																				<span class="md-input-bar "></span>
																			</div>
																			<input type="hidden" id="userId" value="<?php echo $viewid; ?>">
																			<div class="md-input-wrapper uk-float-right">
																				<p>
																					<?php
																						if($telephone){
																							?>
																							<span>
																                                <input type="checkbox" class="uk-checkbox messageChannels" name="smsMessage" data-md-icheck id='msgsendemail' />
																                                <label for="msgsendemail" class="inline-label">SMS</label>
																                            </span>&nbsp;&nbsp;&nbsp;&nbsp;
																                            <?php
																						}
																					?>

																					<?php
																						if($email){
																							?>
																							<span>
																                                <input type="checkbox" class="uk-checkbox messageChannels" name="emailMessage" data-md-icheck id="msgsendsms" />
																                                <label for="msgsendsms" class="inline-label">email</label>
																                            </span>
																                            <?php
																						}
																					?>						
																                            
													                            </p>
																			</div>
																			<div class="md-input-wrapper">
																				<button class="uk-button uk-button-default uk-float-right" type="submit">SEND</button>
																				<span class="md-input-bar "></span>
																			</div>
																		</form>
																	</div>
																	<div class="uk-width-1-4">
																	</div>
																</div>
																<div style="height: 12px; width: 100%; padding: 0px; margin: 0 -100px;"></div>
																<div class="clearfix uk-margin-top">                                                    
																	<ul class="uk-list uk-list-line">
																		<?php
																			foreach ($messages as $key => $message) {
																				?>
																				<li>
																					<div class="uk-grid">
																						<div class="comment-head">
																							<div class="thumbnail">
																								<img class="user avatar inline" style="height: 72px; width: 72px; border-radius: 50%" src="<?php echo $Company->standardLogo ?>">
																								<div class="inline">
																									<p style="vertical-align: middle; font-family: verdana; color: #0a3482">
																										<i><?php echo $userName ?></i>
																									</p>
																									<p class="uk-text-muted" style="margin: -15px 0 0 0">
																										<small><?php echo $message['createdDate'] ?></small>
																									</p>
																								</div>
																																											
																							</div>
																						</div>
																						<div class="uk-width-1-1 uk-margin-top">
																							<?php echo $message['message'] ?>
																						</div>
																					</div>
																				</li>
																				<?php
																			}
																		?>
																	</ul>
																</div>											
															</div>
														</div>
														<div class="uk-width-1-4">
															<div class="">
																<h4>Owners(1)</h4>
																<ul class="uk-list">
																	<li><?php echo $names; ?></li>
																</ul>
															</div>												
														</div>
													</div>
													
												</div>
											</div>
										<?php
									}
								?>
							</div>
						</div>
					<?php
				}
			?>
		</div>
	</div>


	<div class="uk-modal" id="approve_csd_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
		<div class="uk-modal-dialog" style="top: 339.5px;">
			<div class="uk-modal-header uk-tile uk-tile-default">
				<h3 class="d_inline">Approve CSD Account Request</h3>
			</div>
			<form id="csdRequest" method="POST" enctype="multipart/form-data">				
				<div class="md-card">
					<div class="md-card-content">
						<div class="md-input-wrapper md-input-filled">
							<label>CSD Account Number</label>
							<input type="text" name="forumtitle" id="csd_account_input" class="md-input" required="required">
							<span class="md-input-bar "></span>
						</div>

						<div class="md-input-wrapper md-input-filled">
							<p>
                                <input type="checkbox" name="checkbox_demo_mercury" id="checkbox_demo_1" data-md-icheck required />
                                <label for="checkbox_demo_1" class="inline-label">I confirm the authenticity to this user and agree to terms and conditions</label>
                            </p>
						</div>                    
					</div>
				</div>
				<input type="hidden" id="csd_account_user" value="<?php echo $viewid; ?>" type="<?php echo $clientType; ?>">
				<div class="uk-modal-footer uk-text-right">
					<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
					<button type="submit" class="md-btn md-btn-success pull-right">APPROVE</button>
				</div>
			</form>
		</div>
	</div>

	<div class="uk-modal" id="deny_csd_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
		<div class="uk-modal-dialog" style="top: 339.5px;">
			<div class="uk-modal-header uk-tile uk-tile-default">
				<h3 class="d_inline">Decline CSD Account Request</h3>
			</div>
			<form id="denyCSDform" method="POST" enctype="multipart/form-data">				
				<div class="md-card">
					<div class="md-card-content">
						<div class="md-input-wrapper md-input-filled">
							<label>Reason for denial</label>
							<textarea class="md-input" id="denialMessage"></textarea>
							<span class="md-input-bar "></span>
						</div>

						<div class="md-input-wrapper md-input-filled">
							<p>
                                <input type="checkbox" name="checkbox_demo_mercury" id="checkbox_demo_1" data-md-icheck required />
                                <label for="checkbox_demo_1" class="inline-label">I confirm the authenticity to this user and agree to terms and conditions</label>
                            </p>
						</div>                    
					</div>
				</div>
				<input type="hidden" id="csd_account_user" value="<?php echo $viewid; ?>">
				<div class="uk-modal-footer uk-text-right">
					<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
					<button type="submit" class="md-btn md-btn-success pull-right">DECLINE</button>
				</div>
			</form>
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
<script>
	const current_user = <?php echo $thisid; ?>;
	$("#csdRequest").on('submit', function(e){

		e.preventDefault();
		csd_account = $("#csd_account_input").val();
		csd_account_user = $("#csd_account_user").val();


		$.post('../../api/invest.php', {action:'approveCSD', CSDAccount:csd_account, accountUser:csd_account_user, approvedBy:current_user}, function(data){
			if(data == 'Done'){
				location.reload();
			}else{
				alert("Error approving the CSD request")
			}
		})
	});

	$("#denyCSDform").on('submit', function(e){
		e.preventDefault();
		message = $("#denialMessage").val();
		csd_account_user = $("#csd_account_user").val();

		$.post('../../api/invest.php', {action:'declineCSD', accountUser:csd_account_user, message:message, approvedBy:current_user}, function(data){
			if(data == 'Done'){
				location.reload();
			}else{
				alert("Error Declining the CSD request")
			}
		})
	});

	//submitting feed comment
	$(".messageForm").on('submit', function(e){
		e.preventDefault();

		user = $(this).find('#userId').val();
		message = $(this).find('textarea').val();

		var channels = [];

		sendSms = $('#msgsendsms');
		sendEmail = $('#msgsendemail');
		
		if(sendEmail.prop('checked')){
			channels.push('email')
		}
		if(sendSms.prop('checked')){
			channels.push('sms')
		}

		if(message.length>1 && channels.length>0){
			//submitting a message
			$.post('../../api/invest.php', {action:'messageBrokerClient', brokerId:current_user, clientId:user, message:message, channels:channels}, function(data){
				if(data.toLowerCase() == 'done'){
					location.reload();
				}else{
					alert("Problem with messaging, try again later")
				}
			})
		}else{
			alert("Please type message and select communication channels")
		}

	});

	function approved(){
		document.getElementById('csd').innerHTML = 'CSD Account Number:<input type="text" class="md-input" id="csdnumber"><button onclick="saveCsd()">Approve</button>';
	}
	function saveCsd(){
		var csdnumber =$("#csdnumber").val();	
		if (csdnumber == null || csdnumber == "") {
			alert("csdnumber must be filled out");
			return false;
		}
		document.getElementById('csd').innerHTML = 'Approved <input type="checkbox" value="check"><br>CSD Account Number: '+csdnumber;
	}
</script>
</body>
</html>
<!-- Localized -->
