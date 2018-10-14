<?php
	// Get me backif i havent logedin
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
	$sql = $con->query("SELECT * FROM `users` WHERE `email` = '$email' and `pwd` = '$password' LIMIT 1") or die ($db->error);
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
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
	<style type="text/css">
		.loader {
			border-top: 16px solid #4285f4;
			border-right: 16px solid #36a753;
			border-bottom: 16px solid #eb4435;
			border-left: 16px solid #fbbc03;
			border-radius: 50%;
			width: 120px;
			height: 120px;
			animation: spin 2s linear infinite;
		}

		.highcharts-credits{
			fill-opacity: 0;
	  	}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style> 
</head>

<body>
<?php
	//including navbar
	include 'modules/navbar.php';
?>
<div style="height: 70px;"></div>

<div class="container">
  <div class="row">
	<div class="col-md-12">
	  <div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<div class="panel panel-default">
					  <div class="panel-body" style="cursor: pointer;" onclick="tableing(tablesa='groups')">
							<i class="fa fa-users" aria-hidden="true"></i>
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
					  <div class="panel-body" style="cursor: pointer;" onclick="tableing(tablesa='users')">
					   <i class="fa fa-user" aria-hidden="true"></i>
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
					  <div class="panel-body" style="cursor: pointer;" onclick="tableing(tablesa='transactions')">
							<i class="fa fa-exchange" aria-hidden="true"></i>
					   (<?php 
							$sql = $con ->query("SELECT count(id) nTransactions FROM intouchapi") or die(mysql_error($db));
							$nTransactions = mysqli_fetch_array($sql)['nTransactions'];
							echo $nTransactions;
						?>) Transactions
					  </div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-default">
					  <div class="panel-body" style="cursor: pointer;" onclick="tableing(tablesa='money')">
							<i class="fa fa-money" aria-hidden="true"></i>
					   (<?php 
							$sql= $con->query("SELECT sum(amount) totalbalance FROM `directtransfers` WHERE (`id` % 2) = 1");
							$sql2= $con->query("SELECT sum(amount) gtotalbalance FROM `grouptransactions`");
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
					<div id="newChat"></div>
				</dir>
			</div>
			<div class="row">
				<dir class="col-md-12">
					<div id="tables"></div>
				</dir>
			</div>
			<div class="row">
				<!--
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
				-->
				<div class="col-md-3">
					<!--
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
					-->
					<div style="background:#007569; border-radius: 0 0 4px 4px; height:100%; padding: 60px" id="donetransfer">
						<button onclick="checkBalance()" class="btn btn-warning">Check Balance</button>
					</div>
				</div>
			</div>
		</div>
	  <br/><br/>
	  <?php require('chart.php');?>
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
		document.getElementById('donetransfer').innerHTML = '<div style="text-align: center;padding-top:10px; color: #fff; text-shadow: 1px 1px 2px #000000;"><h5>Connecting...<span id="time">00:30</span></h5></div>';
		
		var fiveMinutes = 30,
			display = document.querySelector('#time');
		startTimer(fiveMinutes, display);
		//alert(sendFromName);
		
		Number.prototype.formatMoney = function(c, d, t){
		var n = this, 
			c = isNaN(c = Math.abs(c)) ? 2 : c, 
			d = d == undefined ? "." : d, 
			t = t == undefined ? "," : t, 
			s = n < 0 ? "-" : "", 
			i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
			j = (j = i.length) > 3 ? j % 3 : 0;
		   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		 };

		$.ajax({ 
			type: 'POST', 
			url: '../api/index.php', 
			data: { action: 'balance' }, 
			dataType: 'json',
			success: function (data) { 
				$.each(data, function(index, element) {
					var balance = (element.balance).formatMoney(0, '.', ',');
					document.getElementById("donetransfer").innerHTML = '<div style="text-align: center;padding-top:10px; color: #fff; text-shadow: 1px 1px 3px #000000"><h5>Float Balance</h5><h4/>'+balance+' Rwf</h4>';

				});
			}
		});


		// $.ajax({

		// 	type: "POST", //rest Type
	 //        dataType: 'jsonp', //mispelled
	 //        url: "../api/index.php",
	 //        async: false,
	 //        contentType: "application/json; charset=utf-8",
	 //        success: function (msg) {
	 //        	//$("#donetransfer").html(msg);
	 //            console.log(msg);                
	 //        }
		// 	error : function(xht, textStatus, errorThrown){
		// 		document.getElementById('donetransfer').innerHTML = 'Error.'+ errorThrown; 
		// 	}
		// });
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

	function tableing(tablesa)
	{
		var table = tablesa;
		document.getElementById('newChat').innerHTML ='';
		document.getElementById('tables').innerHTML ='<div style="width=100%; height: auto;margin: 0 auto;padding: 10px;position: relative;"><div class="loader"></div></div>';
		$.ajax({
			type: "GET",
			url: "chart.php",
			dataType: "html",
			cache: "false",
			data:{
				table: table
			},
			success : function(html, textStatus){
				$("#newChat").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert(errorThrown);
			}
		});

		$.ajax({
			type : "GET",
			url : "tables.php",
			dataType : "html",
			cache : "false",
			data : {
				
				table: table
								
			},
			success : function(html, textStatus){
				$("#tables").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert(errorThrown); 
			}
		});

		
	}
</script>


</body>
</html>


