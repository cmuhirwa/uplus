<?php // Get me backif i havent logedin
session_start();
	if (!isset($_SESSION["email"])) {
		header("location: logout.php"); 
	exit();
}
?>
<?php 
		
$account_type = preg_replace('#[^0-9]#i', '', $_SESSION["account_type"]); // filter everything but numbers and letters
 $password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
 $email = $_SESSION["email"]; // filter everything but numbers and letters
include "db.php"; 
$sql = $con->query("SELECT * FROM `users` WHERE `email` = '$email' and `pwd` = '$password' LIMIT 1")or die ($db->error);
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) 
{ 
	while($row = mysqli_fetch_array($sql))
	{ 
		$thisid = $row["id"];
		$name = $row["name"];
		$level = $row["level"];
	}
}
else
{
echo "

<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
Or<p><a href='logout.php'>Click Here to login again</a></p>

";
exit();
}
?>

<?php

include 'db.php';
$transactions="";
$troubleshoot="";
$pushSql =$con->query("SELECT * FROM transactionsview WHERE operation = 'DEBIT' AND status <> 'BALANCE'");
$n=0;
while ($row=mysqli_fetch_array($pushSql)) 
{
	$pushTransactionId 	= $row['transactionId'];
	$amount				= $row['amount'];
	$forGroupId 		= $row['forGroupId'];
	$push3rdparty 		= $row['3rdparty'];
	$push3rdpartyId 	= $row['3rdpartyId'];
	$pushBank 			= $row['bankName'];
	$pushBankId 		= $row['bankCode'];
	$transaction_date 	= $row['transaction_date'];
	$pushName 			= $row['actorName'];
	$pushStatus 		= $row['status'];
	$pushAccountNumber	= $row['accountNumber'];
	$n++;
	$pullTransactionId = $pushTransactionId + 1;
	$pullSql =$con->query("SELECT * FROM transactionsview WHERE transactionId = '$pullTransactionId'")or die (mysqli_error());
	while($pullRow=mysqli_fetch_array($pullSql))
	{
		$pullStatus 		= $pullRow['status'];
		$pull3rdpartyId 	= $pullRow['3rdpartyId'];
		$pull3rdparty 		= $pullRow['3rdparty'];
		$pullName 			= $pullRow['actorName'];
		$pullBank	 		= $pullRow['bankName'];
		$pullBankId 		= $pullRow['bankCode'];
		$pullAccountNumber	= $pullRow['accountNumber'];
	}
	if($pushStatus=='TARGET_AUTHORIZATION_ERROR'){
		$pushStatus='NO MONEY';
		$troubleshoot = "";
	}
	if($pushStatus=='NETWORK ERROR'){
		$pushStatus='NET?';
		$troubleshoot = "";
	}
	if($pushStatus=='ACCOUNTHOLDER_WITH_FRI_NOT_FOU'){
		$pushStatus='PHON?';
		$troubleshoot = "";
	}
	if($pullStatus=='NETWORK ERROR'){
		$pullStatus='NET?';
	}
	if($pushStatus=='CALLED'){
		$pushStatus='INNITIATED';
		$troubleshoot = "";
	}
	if($pullStatus=='CALLED'){
		$pullStatus='INNITIATED';
	}
	if($pushStatus=='Approved' || $pushStatus=='APPROVED'){
		$bg		="#4caf50";
		$bgPull	="#ff9800";
		$troubleshoot = "<button>Troubleshoot</button>";
	}
	elseif($pushStatus=='DECLINED' || $pushStatus=='Declined' || $pushStatus=='NO MONEY' || $pushStatus=='NET?'){
		$bg		="#f44336";
		$troubleshoot = "";
	}
	elseif($pushStatus=='PENDING' || $pushStatus=='REQUESTED'){
		$bg		="#ff9800";
		$bgPull	="#000";
		$troubleshoot = "";
	}
	else{
		$bg		="#000";
		$bgPull	="#000";
	}
	if($pullStatus=='COMPLETE'){
		$bg2	="#4caf50";
		$bgPull	="#4caf50";
	}
	elseif($pullStatus=='DECLINED' || $pullStatus=='Error sending money.' || $pullStatus=='NET?'){
		$bg2	="#f44336";
		$bgPull	="#f44336";
	}
	else{
		$bg2	="#000";
	}
	$link1="'account.php?accountId=".$pushAccountNumber."&clientId=".$pushName."&page=".$pushBankId."'";
	$link2="'account.php?accountId=".$pullAccountNumber."&clientId=".$pullName."&page=".$pullBankId."'";
	$transactions.= '
		<tbody>
			<tr>
				<td>'.$n.'</td>
				<td>'.number_format($amount).'</td>
				<td>'.$pushStatus.' / '.$pullStatus.'</td>
				<td>'.$push3rdpartyId.' / '.$pull3rdpartyId.'</td>
				<td>'.strftime("%d %b", strtotime($row['transaction_date'])).'</td>
				<td style="background: '.$bg.'; cursor: pointer; color: #fff" onclick="location.href = '.$link1.'">'.$pushName.' | '.$pushBank.'</td>
				<td style="background: '.$bgPull.'; cursor: pointer; color: #fff" onclick="location.href = '.$link1.'">'.$pushName.' | '.$pushBank.'</td>
				<td style="background: '.$bg2.'; cursor: pointer; color: #fff" onclick="location.href = '.$link2.'">'.$pullName.'| '.$pullBank.'</td>
				<td>'.$troubleshoot.'</td>
			</tr>
		</tbody>';
	}	
?>

<!DOCTYPE html>
<html>
<head>
	<title>BANKS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
	//including navbar
	include 'modules/navbar.php';
?>

<div class="container"> 
	<div style="color: #fff; font-size: 20px; background-color: #007569; height: 100px;     box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
	color: #fff;">
		<img style="margin: 15px; border: solid 2px white; " src="img/rtgs.png" height="70" >
		&nbsp;&nbsp;&nbsp;(<?php echo $n;?>) Transactions in all banks
	</div>
	<br>
	<div class="jumbotron">
		<div class="row">
			<div class="col-xs-2">
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered" style="float: left;">
						<thead>
							<tr>
								<th>Bank list</th>
							</tr>
						</thead>
						<?php
							include 'db.php';
							$sql =$con->query("select * from banks");
							while ($row=mysqli_fetch_array($sql)) {
								echo '<tbody><tr>
							<td><a href="bank.php?page='.$row['id'].'">'.$row['name'].'</a></td>
						</tr></tbody>';
							}
						?>
							
					</table>
				</div>
			</div>
			<div class="col-xs-10">
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered" style="float: left;">
						<thead>
							<tr>
								<th>#</th>
								<th>Amount</th>
								<th>Status</th>
								<th>3rdpartyId</th>
								<th>Date__</th>
								<th>From</th>
								<th>Pull</th>
								<th>To</th>
								<th>Action</th>
							</tr>
						</thead>
						<?php echo $transactions;?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
	</div>
</div>
</body>
</html>