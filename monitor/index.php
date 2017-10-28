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
			$phone = $row["phone"];
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

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Transfers</title> 
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			var options = {
				chart: {
					renderTo: 'container',
					type: 'area'
				},
				title: {
					text: '',
					x: -20 //center
				},
				xAxis: {
					categories: [],
					title: {
						text: 'Days'
					}
				},
				yAxis: {
					title: {
						text: 'Transfers'
					},
					plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
				},
				tooltip: {
					valueSuffix: 'Transaction/Days'
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle',
					borderWidth: 0
				},
				series: []
			};
			$.getJSON("data.php", function(json) {
				options.xAxis.categories = json[0]['data']; //xAxis: {categories: []}
				options.series[0] = json[1];
				chart = new Highcharts.Chart(options);
			});
		});
	</script>
	<script src="js/highchats.js"></script>  
</head>

<body>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
      <span class="icon-bar"></span> 
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
      <a class="navbar-brand" href="../">RTGS</a>
    </div>
  <div class="collapse navbar-collapse" id="menu">
    <ul class="nav navbar-nav">
      <li class="active"><a href="javascript:void()">Home</a></li>
      <li><a href="transfers">Banks</a></li>
    </ul>
	<ul class="nav navbar-nav navbar-right">
		<li class="active" style="padding: 5px;"><span style="float: left; padding-right: 10px; text-align: right;"><?php echo $name;?><br/><span style="font-size: 12px"><?php echo $level;?></span></span> <button onclick="window.location.href='logout.php'" class="btn btn-danger">Logout</button> <span class="sr-only">(current)</span></li>
    </ul>
    </div>
  </div>
</nav>
  <div style="height: 70px;"></div>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="jumbotron">


	  <style>.highcharts-credits{
		      fill-opacity: 0;
	  }</style>
        <div class="container">
        	<div class="row">
        		<div class="col-md-3">
					<div class="panel panel-default">
					  <div class="panel-body" style="cursor: pointer;">
					    (<?php 
					    	$sql = $db->query("SELECT count(id) nGroups FROM groups") or die(mysql_error($db));
					    	if ($db) {
					    		$row = mysqli_fetch_array($sql);
					    		$nGroups = $row['nGroups'];
					    		echo $nGroups;
					    	}?>) Groups
					  </div>
					</div>
				</div>
        		<div class="col-md-3">
        			<div class="panel panel-default">
					  <div class="panel-body" style="cursor: pointer;">
					   (<?php 
					    	$sql = $db->query("SELECT count(id) nUsers FROM users") or die(mysql_error($db));
					    	if ($db) {
					    		$row = mysqli_fetch_array($sql);
					    		$nUsers = $row['nUsers'];
					    		echo $nUsers;
					    	}?>) Users
					  </div>
					</div>
				</div>
        		<div class="col-md-3">
        			<div class="panel panel-default">
					  <div class="panel-body" style="cursor: pointer;">
					   (<?php 
					    	$sql = $con ->query("SELECT count(id) nTransactions FROM directtransfers WHERE (`id` % 2) = 1") or die(mysql_error($db));
					    	$sql2 = $con ->query("SELECT count(id) nGTransactions FROM grouptransactions WHERE (`id` % 2) = 1") or die(mysql_error($db));
					    	$row = mysqli_fetch_array($sql);
					    	$row2 = mysqli_fetch_array($sql2);
					    	$nTransactions = $row['nTransactions'];
					    	$nGTransactions = $row2['nGTransactions'];
					    	echo $nTransactions+$nGTransactions;
					    ?>) Transactions
					  </div>
					</div>
				</div>
        		<div class="col-md-3">
        			<div class="panel panel-default">
					  <div class="panel-body" style="cursor: pointer;">
					   (<?php 
					    	$sql= $con->query("SELECT sum(amount) totalbalance FROM `directtransfers` WHERE (`id` % 2) = 1");
					    	$sql2= $con->query("SELECT sum(amount) gtotalbalance FROM `grouptransactions` WHERE (`id` % 2) = 1");
							$balancerow = mysqli_fetch_array($sql);
							$balancerow2 = mysqli_fetch_array($sql2);
								echo number_format($balancerow['totalbalance']+$balancerow2['gtotalbalance']);
					    	?>.00 Rwf) Amount
					  </div>
					</div>
				</div>
        	</div>
        	<div class="row">
        		<dir class="col-md-12">
        			<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        		</dir>
        	</div>
			<div class="row">
				<div class="col-md-3">
					<h4>Test Transfer</h4>
					Amount: 
					<input type="text" id="amountdone" class="form-control">
					<br>
					Use: 
					<select class="form-control" id="payMeth" onchange="givePay()" required>
						<option></option>
						<option value="phone">Phone</option>
						<option value="card">Card</option>
					</select>
					<br>
				  	<div id="meth">
						From: 
						<input type="text" id="mtnnumber" disabled class="form-control">
				  	</div>
				  	<br>
					To (Phone): 
					<input type="text" id="sendToAccount" class="form-control">
					<br>
					<button onclick="kwishura()" class="btn btn-primary">SEND</button>
					<br/><br/>
				</div>
				<div class="col-md-3">
					<h4>.</h4>
					Charges: 
					<div class="row"  style="margin: unset; padding: 0;" id="charges">
						<div class="col-md-8" style="padding: 0;" >
							<input  class="form-control" style="border-radius: 4px 0px 0 0;" value="5%" disabled />
						</div>
						<div class="col-md-4" style="padding: 0;">
							<button class="btn btn-default" style="border-radius: 0px 4px 0px 0px; width: 100%" onclick="chargeBtn()">Edit</button>
						</div>
					</div>
					<div style="background:#007569; border-radius: 0 0 4px 4px; height:100%; padding: 60px" id="donetransfer">
						<button onclick="checkBalance()" class="btn btn-warning">Check Balance</button>
					</div>
				</div>
			</div>
		</div>
      <br/><br/>
      </div>
    </div>
  </div>
  <div class="footer">
  </div>
</div>
<script>
	function givePay()
	{
		var payMeth = document.getElementById('payMeth').value;
		if(payMeth == 'phone')
		{
			document.getElementById('meth').innerHTML = 'From: <input type="text" id="mtnnumber" placeholder="eg: 0788888888 or 0722222222" required value="<?php echo $phone;?>" class="form-control">';
		}
		else if(payMeth == 'card')
		{
			document.getElementById('meth').innerHTML = 'From: <input type="text" placeholder="eg: 424242 424242 424242" required class="form-control">';
		}
		else
		{
			document.getElementById('meth').innerHTML = 'From: <input disabled class="form-control">';
		}
	}
</script>

<!--AJAX CALL THE STATUS-->
<script>
function checking(){
	var check =1;
	//alert('ChecKing Status');
	$.ajax({
		type : "GET",
		url : "../3rdparty/rtgs/transfer.php",
		dataType : "html",
		cache : "false",
		data : {
			
			check : check,
		},
		success : function(html, textStatus){
			//alert('incoming Status');
			$("#donetransfer").html(html);
			
		},
		error : function(xht, textStatus, errorThrown){
			document.getElementById('donetransfer').innerHTML = 'Error.'+ errorThrown; 
		}
	});
}
function stopit()
	{
		clearInterval(interval);
		document.getElementById('status').innerHTML = 'Canceled.';
	}
</script>

<script>
// Push or Pull the money
function kwishura(){
	var forGroupId			= 1;
	var sentAmount			= document.getElementById('amountdone').value;
	if (sentAmount == null || sentAmount == "") 
	{
		alert("You must fill in the amount");
		return false;
	}
	/*if (sentAmount < 500 ) 
	{
		alert("The minimum amount we can send is 500 Rwf");
		return false;
	}*/
	var sendFromAccount		= document.getElementById('mtnnumber').value;
	var sendFromName		= '<?php echo $name;?>';
	var sendToAccount		= document.getElementById('sendToAccount').value;
	//if (sendToAccount == null || sendToAccount == "") 
	//{
	//	alert("You must fill in a Phone Number you want to send to");
	//	return false;
	//}
	var match				= sendFromAccount.match(/7(.)/);
	match					= match && match[1];
	match1  = match;
	if(match1 == '8'){
		var sendFromBank = 1;
	}
	else if(match1 == '2'){
		var sendFromBank = 2;
	}
	
	var match				= sendToAccount.match(/7(.)/);
	match					= match && match[1];
	match2  = match;
	if(match2 == '8'){
		var sendToBank = 1;
	}
	else if(match2 == '2'){
		var sendToBank = 2;
	}
	//else{
	//	alert("Please Provide eather MTN or TIGO numbers");
	//	return false;
	//}
	var realphone1 			= sendFromAccount.substring(sendFromAccount.indexOf("7"));
	var prephone2 			= sendToAccount.substring(sendToAccount.indexOf("7"));
	var realphone2 			= '250'+prephone2;
		
	document.getElementById('donetransfer').innerHTML = '<div style="text-align: center;padding-top:10px; color: #fff; text-shadow: 1px 1px 2px #000000;"><h5>Connecting...<span id="time">00:30</span></h5></div>';
	
	var fiveMinutes = 30,
        display = document.querySelector('#time');
    startTimer(fiveMinutes, display);
	var sendToName = 'MONITOR';
				
	$.ajax({
			type : "GET",
			url : "../3rdparty/rtgs/transfer.php",
			dataType : "html",
			cache : "false",
			data : {
				forGroupId		:	forGroupId,	
				sentAmount		:	sentAmount,	
				phone1 			: 	realphone1,
				phone2 			: 	realphone2,
				sendFromName	:	sendFromName,
				sendToName		:	sendToName,
				
				sendFromAccount	:	realphone1,	
				sendFromBank	:   sendFromBank,	
				sendToBank		:   sendToBank,		
				sendToAccount	:   sendToAccount
			},
			success : function(html, textStatus){
				$("#donetransfer").html(html);
				document.getElementById('doneMtn').innerHTML = '';
			},
			error : function(xht, textStatus, errorThrown){
					document.getElementById('donetransfer').innerHTML = 'Error.'+ errorThrown; 
		
			}
	});
}
</script>

<script>
// Check for the balance
function checkBalance(){
	var forGroupId			= '';
	var sentAmount			= '';
	var sendFromName		= '<?php echo $name;?>';
	var sendToBank			= '';
	var sendToAccount		= '';
	
	var sendFromBank = '';
	var realphone1 			= '';
	var realphone2 			= '';
	var sendToName 			= 'CheckBalance';
	document.getElementById('donetransfer').innerHTML = '<div style="text-align: center;padding-top:10px; color: #fff; text-shadow: 1px 1px 2px #000000;"><h5>Connecting...<span id="time">00:30</span></h5></div>';
	
	var fiveMinutes = 30,
        display = document.querySelector('#time');
    startTimer(fiveMinutes, display);
	//alert(sendFromName);
	$.ajax({
			type : "GET",
			url : "../3rdparty/rtgs/transfer.php",
			dataType : "html",
			cache : "false",
			data : {
				
				forGroupId		:	forGroupId,	
				sentAmount		:	sentAmount,	
				phone1 			: 	realphone1,
				phone2 			: 	realphone2,
				sendFromName	:	sendFromName,
				sendToName		:	sendToName,
				
				sendFromAccount	:	realphone1,	
				sendFromBank	:   sendFromBank,	
				sendToBank		:   sendToBank,		
				sendToAccount	:   sendToAccount,
								
			},
			success : function(html, textStatus){
				//alert(sendToName);
				$("#donetransfer").html(html);
				document.getElementById('doneMtn').innerHTML = '';
			},
			error : function(xht, textStatus, errorThrown){
				document.getElementById('donetransfer').innerHTML = 'Error.'+ errorThrown; 
			}
	});
}
</script>

<script>
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

function chargeBtn()
{
	document.getElementById('charges').innerHTML = '<div class="col-md-8" style="padding: 0;" >'
							+'<input  class="form-control" style="border-radius: 4px 0px 0 0;" value="5" type="number" />'
						+'</div>'
						+'<div class="col-md-4" style="padding: 0;">'
							+'<button class="btn btn-default" style="border-radius: 0px 4px 0px 0px; width: 100%" onclick="chargeChangeBtn()">Change</button>'
						+'</div>';
}
function chargeChangeBtn()
{
	alert('Sorry your account is not alowed to change anything on charges');
	document.getElementById('charges').innerHTML = '<div class="col-md-8" style="padding: 0;" >'
							+'<input  class="form-control" style="border-radius: 4px 0px 0 0;" value="5%" type="text" disabled />'
						+'</div>'
						+'<div class="col-md-4" style="padding: 0;">'
							+'<button class="btn btn-default" style="border-radius: 0px 4px 0px 0px; width: 100%" onclick="chargeBtn()">Edit</button>'
						+'</div>';
}
</script>


</body>
</html>


