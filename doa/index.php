
<?php 
?>
<!DOCTYPE html>
<html>
<head>
	<title>NDINDE</title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css" media="all">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css" media="all">
	<link rel="stylesheet" href="css/styles.css" media="all">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
</head>
<body style="background: #efefef;">
	<div class="container">
		<div class="jumbotron topbar">
			<div class="row">
				<div class="col-md-2">
					<img src="https://firebasestorage.googleapis.com/v0/b/uplusmp.appspot.com/o/users_photos%2F351.jpg?alt=media&token=cf5c2a7b-b0db-426d-9104-b1ffb48ee82chttps://firebasestorage.googleapis.com/v0/b/uplusmp.appspot.com/o/users_photos%2Fcropped1690598495.jpg?alt=media&token=c77bca2c-fb6a-4bdc-8767-85948e31d44b" alt="..." style="width: 100px;" class="img-circle">
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Handle ID</label>
							<div class="col-sm-9">
							  <input type="email" class="form-control" id="inputEmail3" disabled value="25.001/CREDITSCORE/1199280018360074">
							</div>
						</div>
						<div class="form-group">
							<label for="currentTime" class="col-sm-3 control-label">Current Time</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="currentTime" disabled placeholder="Password">
							</div>
						</div>
					  	<div class="col-sm-3"></div>
					  	<div class="panel-group col-sm-9">
					    	<div class="panel panel-default">
					     	 	<div class="panel-heading">
						        	<h4 class="panel-title">
						          		<a data-toggle="collapse" href="#collapse1">More Info</a>
						        	</h4>
						      	</div>
						      	<div id="collapse1" style="color: #000;" class="panel-collapse collapse">
							        <ul class="list-group">
										<li class="list-group-item">Name:	MUHIRWA CLEMENT</li>
										<li class="list-group-item">AGE: 01/01/1990</li>
										<li class="list-group-item">GENDER: MALE</li>
							        </ul>
						     	</div>
						    </div>
				  		</div>
					</form>
				</div>
				<div class="col-md-2">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <a href="login.php" type="submit" class="btn btn-danger">Exit</a>
						    </div>
				  		</div><div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Grant Access</button>
						    </div>
				  		</div>
				  	</form>
				</div>
			</div>
		</div>
	  	<dir class="row mainContent">
	  		<div class="col-md-4 contentHolder">
	  			<div class="holderHead">Sources</div>
	  			<div class="card card-fluid">
		  			<div class="list-group" style="padding-right: 10px;">
						<a href="javascript:void()" onclick="callRecords(1)" id="source1" class="list-group-item active"><span class="badge">3</span>Ownership</a>
						<a href="javascript:void()" onclick="callRecords(2)" id="source2" class="list-group-item"><span class="badge">14</span>Education</a>
						<a href="javascript:void()" onclick="callRecords(3)" id="source3" class="list-group-item"><span class="badge">0</span>Criminal</a>
						<a href="javascript:void()" onclick="callRecords(4)" id="source4" class="list-group-item"><span class="badge">148</span>Medical</a>
						<a href="javascript:void()" onclick="callRecords(5)" id="source5" class="list-group-item"><span class="badge">5</span>Family</a>
					</div>
				</div>
			</div>
	  		<div class="col-md-4 contentHolder">
	  			<div class="holderHead">Records</div>
	  			<div  id="recordsHolder" class="list-group" style="padding-right: 10px;">
				</div>
			</div>
	  		<div class="col-md-4 contentHolder">
	  			<div class="holderHead">Information</div>
	  			<p id="detailsHolder" style="margin: 10px 15px 10px;">Expandable Information Here!</p>
			</div>
	  	</dir>
	</div>
   <!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">

		  <!-- Modal content-->
		  <div class="modal-content">
		    <div class="modal-header">
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		      <h4 class="modal-title">Grant Access to:</h4>
		    </div>
		    <div class="modal-body">
		        <div style="padding: 18px 0;">
					<h5  style="float: left;">Ownership</h5>
					<div class="btn-group btn-toggle" style="float: right;"> 
						<button class="btn btn-xs btn-default">ON</button>
						<button class="btn btn-xs btn-primary active">OFF</button>
					</div>
				</div>
				<div style="padding: 18px 0;">
					<h5  style="float: left;">Education</h5>
					<div class="btn-group btn-toggle" style="float: right;"> 
						<button class="btn btn-xs btn-primary active">ON</button>
						<button class="btn btn-xs btn-default">OFF</button>
					</div>
				</div>
				<div style="padding: 18px 0;">
					<h5  style="float: left;">Criminal</h5>
					<div class="btn-group btn-toggle" style="float: right;"> 
						<button class="btn btn-xs btn-default">ON</button>
						<button class="btn btn-xs btn-primary active">OFF</button>
					</div>
				</div>
				<div style="padding: 18px 0;">
					<h5  style="float: left;">Medical</h5>
					<div class="btn-group btn-toggle" style="float: right;"> 
						<button class="btn btn-xs btn-default">ON</button>
						<button class="btn btn-xs btn-primary active">OFF</button>
					</div>
				</div>
				<div style="padding: 18px 0;">
					<h5  style="float: left;">Family</h5>
					<div class="btn-group btn-toggle" style="float: right;"> 
						<button class="btn btn-xs btn-default ">ON</button>
						<button class="btn btn-xs btn-primary active">OFF</button>
					</div>
				</div>
		    </div>
		    <div class="modal-footer">
				<div class="btn-group btn-toggle"> 
					<button class="btn btn-default" onclick="myFunction()"  data-toggle="collapse" data-target="#collapsible">Generate Key</button>
				</div>
				<div class="well collapse" id="collapsible" style="font-weight: 800;"> 
					<div id="genkey"></div>
				</div>
		    </div>
		</div>
		  
		</div>
	</div>

<script>
	var d = new Date();
	document.getElementById('currentTime').value = d;
	function myFunction() {
	    var x = Math.floor((Math.random() * 9999) + 1000);
	    document.getElementById("genkey").innerHTML = 'Authendication Key: '+x;
	}
	(callRecords(1))();

	function callRecords(sourceId) {

		var elem = document.querySelector(".list-group-item.active")
		elem.classList.remove('active');

		var elem = document.querySelector("#source"+sourceId)
		elem.classList.add('active');

		document.getElementById('detailsHolder').innerHTML ='';
		document.getElementById('recordsHolder').innerHTML ='<svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">'
		   +'<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';

		$.ajax({
			type : "GET",
			url : "records.php",
			dataType : "html",
			cache : "false",
			data : {
				sourceId: sourceId
			},
			success : function(html, textStatus){
				//alert('reslut back');
			$('#recordsHolder').html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
		});
	}
	function callDetails(recordId) {

		
		document.getElementById('detailsHolder').innerHTML ='<svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">'
		   +'<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';

		$.ajax({
			type : "GET",
			url : "records.php",
			dataType : "html",
			cache : "false",
			data : {
				recordId: recordId
			},
			success : function(html, textStatus){
				//alert('reslut back');
			$('#detailsHolder').html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
		});
	}
</script>
</body>
</html>
