<?php include("template/header.php");?>
 <!-- Page -->
  <div class="page animsition">
   
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-lg-12 col-sm-12">
	    <div class="row">
			<!--<div class="col-md-12 col-xs-12 masonry-item ">
				 Widget 
				<div class="widget widget-article widget-shadow">
					<div class="widget-left cover plyr" id="tutorials">
						<iframe class="cover-background" width="559" height="315" frameborder="0" allowfullscreen>
						  
						</iframe>
					</div>
					<div class="widget-body" style="background-color: #fff;">
	                	<h4 class="widget-title">How to use Uplus</h4>
		                <p>This videos will take you through how to use uplus, efectivly by raising money from any country, any currency instantly into rwf.</p>
		                 <a href="javascript:void(0)" onclick="videosavings()" style="font-size: 16px;"><i class="icon md-play"></i>&nbsp; Create a contribution</a> <em style="font-size: 11px;">(0:45)</em><br>
		                 <a href="javascript:void(0)" onclick="videoinfo()" style="font-size: 16px;"><i class="icon md-play"></i>&nbsp; Send bulk SMS for free</a> <em style="font-size: 11px;">(00:30)</em><br>
		                 <a href="javascript:void(0)" onclick="videocontribution()" style="font-size: 16px;"><i class="icon md-play"></i>&nbsp; Receive money</a> <em style="font-size: 11px;">(01:05)</em><br>
	                 
		                <div class="widget-body-footer">
		                  <div class="widget-actions pull-left">
		                    <a href="javascript:void(0)">
		                      <i class="icon md-share"></i>
		                    </a>
		                   
		                  </div>
		                  <a style="display: none;" class="btn btn-warning pull-right" href="learn">
		                    <i class="icon md-chevron-right"></i> Learn More
		                  </a>
		                </div>
	              	</div>
				</div>
				<!-- End Widget -->
			</div>
			<div class="col-md-12 col-xs-12">
				<div class="panel">
				<div class="panel-heading">
              <h3 class="panel-title">
                Groups Status <span class="badge badge-info" style="background-color: #00897b;">
				<?php
				$sql = $db->query("SELECT * FROM groups G INNER JOIN groupuser GU ON GU.groupId = G.id  WHERE G.adminId ='$thisid' AND GU.archive <> 'YES' ORDER BY id DESC");
				echo $countAll = mysqli_num_rows($sql);
				?></span>
              </h3>
            </div>
				<div class="table-responsive">
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<td>ID</td>
							<td>Contribution Name</td>
							<td>Targeted Amount</td>
							<td>Current Amount</td>
							<td>Shares</td>
							<td>Likes</td>
							<td>visits</td>
							<td>SMS</td>
							<td>Action</td>
						</tr>
					</thead>
					<tbody>
						<?php
						$n=0;
						$sql = $db->query("SELECT * FROM `groups` WHERE adminId ='$thisid' ORDER BY visits DESC, id DESC");
						while($row = mysqli_fetch_array($sql))
						{
							$groupId = $row['id'];
							$sqlGetbalance = $outCon->query("SELECT Balance FROM groupbalance WHERE id = '$groupId'");
							$rowBalance = mysqli_fetch_array($sqlGetbalance);
							$balance = $rowBalance['Balance'];
							$n++;
							$state = $row['state'];
							
							if($state == 'public')
							{
								$state='<td style="border-bottom: 1px solid green;">'.$row['groupName'].'</td>';
							}else
							{
								$state='<td style="border-bottom: 1px solid red;">'.$row['groupName'].'</td>';
							}
								$state='<td >'.$row['groupName'].'</td>';
							
							
						echo'<tr>
							<td>'.$n.'</td>
							'.$state.'
							<td>'.number_format($row['targetAmount']).' Rwf</td>
							<td>'.number_format($balance).' Rwf</td>
							<td>'.$row['likes'].'</td>
							<td>'.$row['likes'].'</td>
							<td>'.$row['visits'].'</td>
							<td>'.$row['sms'].'</td>
							<td><a href="editCont'.$row['id'].'" class="btn btn-dark btn-xs" style="text-decoration: none;">Manage</a></td>
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
      <!-- <div class="col-lg-3 col-sm-3">
        <div class="qa-message-list" id="wallmessages">
          <div class="message-item" id="m3">
            <div class="message-inner">
              <div class="message-head clearfix">
                <div class="avatar pull-left"><a href="./index.php?qa=user&qa_1=monu"><img src="proimg/1.jpg"></a></div>
                <div class="user-detail">
                  <h5 class="handle">uPlus Team</h5>
                  <div class="post-meta">
                    <div class="asker-meta">
                      <span class="qa-message-what"></span>
                      <span class="qa-message-when">
                        <span class="qa-message-when-data">Oct 31</span>
                      </span>
                      <span class="qa-message-who">
                        <span class="qa-message-who-pad">:</span>
                        <span class="qa-message-who-data"><a href="./index.php?qa=user&qa_1=monu">Promo</a></span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="qa-message-content">
				To test, After you created your conrtibution group,
				Verify your withdraw account,
				Share your fund on facebook,
				And get the first 5,000 Rwf contribution (free) from uPlus.
              </div>
            </div>
          </div>
		</div>
      </div> -->
    </div>
  </div>
  
<button style="display: none;" class="site-action btn-raised btn btn-success btn-floating" data-target="#addTopicForm" data-toggle="modal" type="button" id="add">
	<span style="
    position: absolute;
    background: #4caf50;
    border-bottom-left-radius: 20px;
    border-top-left-radius: 20px;
    top: 11px;
    font-size: 15px;
    left: -92px;
    padding-bottom: 5px;
    padding-top: 5px;
    padding-right: 13px;
    margin-left: 0px;
">&nbsp; Create Group 
</span>
  <i class="icon md-plus" aria-hidden="true" style="
    font-size: 32px;
    text-align: center;
    margin-left: 1px;
"></i>
</button>
  <!-- NEW GROUP POPUP -->
  <div class="modal fade" id="addTopicForm" aria-hidden="true" aria-labelledby="addTopicForm" role="dialog" tabindex="-1">
    <div class="modal-dialog">
    <div class="modal-content" id="exampleWizardForm">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="exampleModalTabs">Create a Group</h4>
    </div>
 <!-- Steps -->
	<div class="steps steps-sm row" data-plugin="matchHeight" data-by-row="true" role="tablist">
	<div class="step col-md-4 current" data-target="#exampleAccount" role="tab">
	  <span class="step-number">1</span>
	  <div class="step-desc">
		<span class="step-title">Info</span>
	  </div>
	</div>

	<div class="step col-md-4" data-target="#exampleBilling" role="tab">
	  <span class="step-number">2</span>
	  <div class="step-desc">
		<span class="step-title">Finance</span>
	  </div>
	</div>

	<div class="step col-md-4" data-target="#exampleGetting" role="tab">
	  <span class="step-number">3</span>
	  <div class="step-desc">
		<span class="step-title">Confirm</span>
	  </div>
	</div>
	</div>
	<!-- End Steps -->
    <div class="modal-body wizard-content">
	  <!-- Panel Wizard Form -->
		<div class="panel-body ">
		 <!-- Wizard Content -->
			<div class="wizard-pane active" id="exampleAccount" role="tabpanel">
			  <div id="exampleAccountForm">
					<div id="stepFill">
						<input hidden id="step" value="info">
					</div>
				<input hidden id="groupType" name="groupType" value="CONTRIBUTOIN" />
				<input hidden  id="contType" name="contType"  value="fixed"/>
				
				<div class="form-group">
					<label class="control-label" for="groupName">Group Name:</label>
					<input type="text" class="form-control round" id="groupName" required name="groupName" placeholder=""/> 
					<input hidden type="text" id="thisId" name="thisId" value="<?php echo $thisid?>"/>
					<input hidden type="text" id="adminPhone" name="adminPhone" value="<?php echo$phone?>"/>
					<input hidden type="text" id="adminName" name="adminName" value="<?php echo$name?>"/>
				</div>
				<div class="form-group">
					<label class="control-label" for="groupDesc">Group Description:</label>
					<textarea class="form-control" style="height: 110px;" id="groupDesc" required name="groupDesc" placeholder="Something to describe your group..."></textarea>
				</div>
			  </div>
			</div>
			<div class="wizard-pane" id="exampleBilling" role="tabpanel">
			  <div id="exampleBillingForm">
				<div id="finance">
					First fill in the info
				</div>
			  </div>
			</div>
			<div class="wizard-pane" id="exampleGetting" role="tabpanel">
			  <div id="invite">
				First fill in the finance
			  </div>
			</div>
		 <!-- End Wizard Content -->
		</div>
	  <!-- End Panel Wizard One Form -->
	</div>
   </div>
</div>
</div>
  <!-- End NEW GROUP POPUP -->


    
<!-- PENDINGS Alert -->
  </div>
  </div>
  <!-- End Page -->


  <!-- Footer -->
  <footer class="site-footer" style="text-align: center;">
    <div class="site-footer-legal">© 2017 uPlus Mutual Partners LTD</div>
	<a  href="apps/"><i class="icon md-android"></i></a>&nbsp;&nbsp;/&nbsp;&nbsp; 
	<a  href="javascript:void()"><i class="icon md-apple"></i></a>&nbsp;&nbsp;/&nbsp;&nbsp;
	<a  href="javascript:void()"><i class="icon md-windows"></i></a>
    <div class="site-footer-right">
      Powering collective investments <i class="red-600 wb wb-globe"></i> Worldwide
    </div>
  </footer>
 
   
<?php include('template/notifications.php');?>
 
  
	<script src="assets/js/ajax_call.js"></script>

  <!-- Core  -->
  <script src="assets/global/vendor/jquery/jquery.min.js"></script>
  <script src="assets/global/vendor/bootstrap/bootstrap.min.js"></script>
  <script src="assets/global/vendor/animsition/animsition.min.js"></script>
  <script src="assets/global/vendor/asscroll/jquery-asScroll.min.js"></script>
  <script src="assets/global/vendor/mousewheel/jquery.mousewheel.min.js"></script>
  <script src="assets/global/vendor/ashoverscroll/jquery-asHoverScroll.min.js"></script>

  <!-- Plugins -->
  <script src="assets/global/vendor/switchery/switchery.min.js"></script>
  <script src="assets/global/vendor/slidepanel/jquery-slidePanel.min.js"></script>

 <!-- Plugins For This Page -->
  <script src="assets/global/vendor/formvalidation/formValidation.min.js"></script>
  <script src="assets/global/vendor/formvalidation/framework/bootstrap.min.js"></script>
  <script src="assets/global/vendor/matchheight/jquery.matchHeight-min.js"></script>
  <script src="assets/global/vendor/jquery-wizard/jquery-wizard.min.js"></script>

  <!-- Scripts -->
  <script src="assets/global/js/core.min.js"></script>
  <script src="assets/js/site.min.js"></script>

  <script src="assets/js/sections/menu.min.js"></script>
  <script src="assets/js/sections/menubar.min.js"></script>
  <script src="assets/js/sections/sidebar.min.js"></script>

  <script src="assets/global/js/components/asscrollable.min.js"></script>
  <script src="assets/global/js/components/animsition.min.js"></script>

  <script src="assets/global/js/components/jquery-wizard.min.js"></script>
  <script src="assets/global/js/components/matchheight.min.js"></script>

  <script src="assets/examples/js/forms/wizard.min.js"></script>
  <script src="assets/examples/js/forms/advanced.min.js"></script>
  
 <script>
function getContType(groupType)
{
	var groupType =$("#groupType").val();
	if (groupType == "CONTRIBUTOIN")
	{
		document.getElementById('contributionType').innerHTML = '<label class="control-label margin-bottom-15" for="contType">Contribution Type:</label><select class="form-control round" id="contType" name="contType" required="required"><option></option><option value="fixed">ONCE-OFF.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg: wedding, funerals...</option><option value="periodical">RECURRING.&nbsp;&nbsp;&nbsp;&nbsp;eg: Church tithe, Umutekano...</option></select><br/>';
	}
	else
	{
		document.getElementById('contributionType').innerHTML = '<input id="contType" hidden name="contType" value="none">';
	};
}
 </script>
<script>
 //  GROUP CREATION

	//1 Pass Info Then Finance // 2 Pass Finance Then Invite
function nexttoaccounts(){
	var step = document.getElementById('step').value;
	//alert (step);
	if(step == 'info')
	{
		var groupType = document.getElementById('groupType').value;
		  if (groupType == null || groupType == "") {
				//alert("groupType must be filled out");
				return false;
			}
		  var contType = document.getElementById('contType').value;
		  if (contType == null || contType == "") {
				//alert("contType must be filled out");
				return false;
			}
		  var groupName = document.getElementById('groupName').value;
		  if (groupName == null || groupName == "") {
				//alert("groupName must be filled out");
				return false;
			}
		  var groupDesc = document.getElementById('groupDesc').value;
		  if (groupDesc == null || groupDesc == "") {
				//alert("groupDesc must be filled out");
				return false;
			}
		  var thisId = document.getElementById('thisId').value;
		  var adminName = document.getElementById('adminName').value;
		  var adminPhone = document.getElementById('adminPhone').value;
		  if(groupType == 'IKIMINA'){
		  document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="2aIkiminaFinance">'
		  }
		  else if(groupType == 'CONTRIBUTOIN' && contType == 'periodical'){
			document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="2bContributionTitheFinance">'
		  }
		  else if(groupType == 'CONTRIBUTOIN' && contType == 'fixed'){
			document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="2cContributionWedding">'
		  }
		  else if(groupType == 'INVESTMENT'){
			document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="2dInvestmentFinance">'
		  }
		document.getElementById('finance').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
	
		  //alert (groupType);
			$.ajax({
			  type : "GET",
			  url : "scripts/newgroup.php",
			  dataType : "html",
			  cache : "false",
			  data : {
				
				groupType : groupType,
				contType : contType,
				groupName : groupName,
				groupDesc : groupDesc,
				thisId : thisId,
				adminName : adminName,
				adminPhone : adminPhone
			  },
			  success : function(html, textStatus){
				$("#finance").html(html);
			  },
			  error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			  }
		  });
	}
////////////////////////////////////////////////////////////////
	
	//2.a Pass IKIMINA Finance Then Invite people
	else if(step == '2aIkiminaFinance')
	{
		//alert("INVITE FOR IKIMINA");
		 var contributionAmount = document.getElementById('contributionAmount').value;
		  if (contributionAmount == null || contributionAmount == "") 
		  {
				alert("contributionAmount must be filled out");
				return false;
			}
		  var transactionDays = document.getElementById('transactionDays').value;
		  if (transactionDays == null || transactionDays == "") {
				alert("transactionDays must be filled out");
				return false;
			}
		  var startingDate = document.getElementById('startingDate').value;
		  if (startingDate == null || startingDate == "") {
				alert("startingDate must be filled out");
				return false;
			}
		  var Saving = document.getElementById('Saving').value;
		  if (Saving == null || Saving == "") {
				alert("Saving must be filled out");
				return false;
			}
		  
		  document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="3aIkiminaInvite">';
		  document.getElementById('invite').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
		  $.ajax({
			  type : "GET",
			  url : "scripts/newgroup.php",
			  dataType : "html",
			  cache : "false",
			  data : {
				
				contributionAmount : contributionAmount,
				transactionDays : transactionDays,
				startingDate : startingDate,
				Saving : Saving
			  },
			  success : function(html, textStatus){
				$("#invite").html(html);
			  },
			  error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			  }
		  });

	}
	
	//2.b Pass TIGHT Finance Then Invite people
	else if(step == '2bContributionTitheFinance')
	{
		var bank = document.getElementById('bank').value;
		if (bank == null || bank == "") 
		{
			alert("bank must be filled out");
			return false;
		}
		var bankaccount = document.getElementById('bankaccount').value;
		if (bankaccount == null || bankaccount == "") 
		{
			alert("bankaccount must be filled out");
			return false;
		}
		 alert(bank);
		document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="3bTitheInvite">';
		document.getElementById('invite').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
		$.ajax({
			type : "GET",
			url : "scripts/newgroup.php",
			dataType : "html",
			cache : "false",
			data : {
				
				tightbank : bank,
				bankaccount : bankaccount
			},
			success : function(html, textStatus){
			$("#invite").html(html);
			},
			error : function(xht, textStatus, errorThrown){
			alert("Error : " + errorThrown);
			}
		});
	}

	//2.c Pass Wedding Finance Then Invite people
	else if(step == '2cContributionWedding')
	{
	 var bank = document.getElementById('bank').value;
	  if (bank == null || bank == "") 
	  {
			alert("bank must be filled out");
			return false;
		}
	  var bankaccount = document.getElementById('bankaccount').value;
	  if (bankaccount == null || bankaccount == "") {
			alert("bankaccount must be filled out");
			return false;
		}
	 var WeedingAmount = document.getElementById('WeedingAmount').value;
	  if (WeedingAmount == null || WeedingAmount == "") 
	  {
			alert("WeedingAmount must be filled out");
			return false;
		}
	  var WeddingDate = document.getElementById('WeddingDate').value;
	  if (WeddingDate == null || WeddingDate == "") {
			alert("WeddingDate must be filled out");
			return false;
		}
	
	document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="3cWeedingInvite">';
	document.getElementById('invite').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
	  $.ajax({
		  type : "GET",
		  url : "scripts/newgroup.php",
		  dataType : "html",
		  cache : "false",
		  data : {
			
			WeedingAmount : WeedingAmount,
			WeddingDate : WeddingDate,
			Weddingbank : bank,
			Weddingbankaccount : bankaccount
		  },
		  success : function(html, textStatus){
			$("#invite").html(html);
		  },
		  error : function(xht, textStatus, errorThrown){
			alert("Error : " + errorThrown);
		  }
	  });
	}

	//2.d Pass INVESTMENT Finance Then Invite people
	else if(step == '2dInvestmentFinance')
	{
	    var investmentAmount = document.getElementById('investmentAmount').value;
	    if (investmentAmount == null || investmentAmount == "") 
		  {
				alert("investmentAmount must be filled out");
				return false;
			}
		  var offerDate = document.getElementById('offerDate').value;
		  if (offerDate == null || offerDate == "") {
				alert("offerDate must be filled out");
				return false;
			}
		 var totalShares = document.getElementById('totalShares').value;
		  if (totalShares == null || totalShares == "") 
		  {
				alert("totalShares must be filled out");
				return false;
			}
		  var ashare = document.getElementById('ashare').value;
		  if (ashare == null || ashare == "") {
				alert("ashare must be filled out");
				return false;
			}
		 var bankaccount = document.getElementById('bankaccount').value;
		  if (bankaccount == null || bankaccount == "") {
				alert("bankaccount must be filled out");
				return false;
			}
		
		document.getElementById('stepFill').innerHTML = '<input id="step" hidden value="3dInvestmentInvite">';
		document.getElementById('invite').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
		$.ajax({
		    type : "GET",
		    url : "scripts/newgroup.php",
		    dataType : "html",
		    cache : "false",
		    data : {
				
				investmentAmount  : investmentAmount,
				offerDate         : offerDate,
				totalShares       : totalShares,
				ashare            : ashare,
				bankaccount       : bankaccount
			  },
			  success : function(html, textStatus){
				$("#invite").html(html);
			  },
			  error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
		    }
	    });
	}

/////////////////////////////////////////////////////////////////

}	
///////////////////////////////////////////////////////////////////////////
</script>
<script>
// VIDEOS TUTORIALS
function videoinfo(){
	document.getElementById('tutorials').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
	
 document.getElementById('tutorials').innerHTML = '<iframe width="560" height="315" src="https://www.youtube.com/embed/_QoR_i0IIfY" frameborder="0" allowfullscreen></iframe>';
  }
function videosavings(){
	document.getElementById('tutorials').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
	document.getElementById('tutorials').innerHTML = '<iframe width="559" height="315" src="https://www.youtube.com/embed/vj7XExwChwI" frameborder="0" allowfullscreen></iframe>';
  }
function videocontribution(){
	document.getElementById('tutorials').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';	
 document.getElementById('tutorials').innerHTML = '<iframe src="https://player.vimeo.com/video/72700224" width="559" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
  }
function videoloans(){
	document.getElementById('tutorials').innerHTML = '<div style="padding-left:40%;padding-bottom:40px;padding-top:40px;"><div class="loader-wrapper active"><div class="loader-layer loader-red-only"><div class="loader-circle-left"><div class="circle"></div> </div><div class="loader-circle-gap"></div><div class="loader-circle-right"><div class="circle"></div></div></div> </div></div>';
 document.getElementById('tutorials').innerHTML = '<iframe width="559" height="315" src="https://www.youtube.com/embed/8b5-iEnW70k" frameborder="0" allowfullscreen></iframe>';
 
  }
</script>
</body>

</html>



                
