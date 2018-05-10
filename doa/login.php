
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
</head>
<body style="background: #efefef;">
	<div class="container">
		<div class="jumbotron topbar">
			<div class="row">
				<div class="col-md-4">
				
				</div>
				<div class="col-md-8">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Handle ID</label>
							<div class="col-sm-9">
							  	<div class="input-group">
									<div class="input-group-addon">25.001/</div>
									<input type="text" class="form-control" placeholder="...">
									<span class="input-group-btn">
										<a href="index.php" class="btn btn-success" type="button">Login</a>
									</span>
								</div>
							</div>
						</div>
					</form>	
				</div>
			</div>
		</div>
	  	<dir class="row mainContent" style="text-align: center;">
	  		<div class="col-md-12 contentHolder">
	  			<div class="holderHead">Information</div>
	  			<div class="tree" style="text-align: center;">
					<ul>
						<li>
							<a href="nida.php">NID<br><?php  
	  								include('db.php');
		  						$sqlDoa1 = $db->query("SELECT handleId FROM nida WHERE handleId <>'' AND handleId IS NOT NULL")or die(mysqli_error($db));
		  						echo mysqli_num_rows($sqlDoa1); ?>/<?php
		  						$sqlNida = $db->query("SELECT * FROM nida");
		  						echo mysqli_num_rows($sqlNida); ?></a>
							<ul>
								<li>
									<a href="reb.php">REB<br>3,289,120</a>
									<ul>
										<li>
											<a href="#">UOK</a>
										</li>
										<li>
											<a href="#">UR</a>
										</li>
										<li>
											<a href="#">UTB</a>
										</li>
										<li>
											<a href="#">AKILA</a>
										</li>
										<li>
											<a href="#">CAMBRIDGE</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="#">RNRA</a>
								</li>
								<li>
									<a href="#">RRA</a>
								</li>
								<li>
									<a href="#">MINIJUST</a>
								</li>
								<li>
									<a href="#">EMIGRATION</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
	  	</dir>
	  	<br>
	  	<dir class="row mainContent" style="text-align: center;">
	  		<div class="col-md-12 contentHolder">
	  			<div class="holderHead" style="background: #a1034d; color: #fff; font-weight: 700; font-size: 14px">Findout</div>
	  			<br>
	  			<div class="row">
					<div class="col-md-2">
						
					</div>
					<div class="col-md-8">
						<form class="form-horizontal">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Handle ID</label>
								<div  class="col-sm-6" style="padding: unset;">
									<div class="input-group">
										<div class="input-group-addon">25.001/</div>
										<input type="text" id="handleToResolve" class="form-control" placeholder="...">
									</div>
								</div>
								<div class="col-sm-4" style="padding: unset;">
								  	<div class="input-group">
										<input type="text" id="handleCode" class="form-control" placeholder="Optional Key...">
										<span class="input-group-btn">
											<button onclick="resolve()" class="btn btn-success" type="button">Resolve</button>
										</span>
									</div>
								</div>
							</div>
						</form>	
					</div>
					<div class="col-md-2">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8" id="handlesInfoHolder">
						
					</div>
				</div>
			</div>
	  	</dir>
	</div>
	<script type="text/javascript">
		function resolve() {
			var handleIdResolve = document.getElementById("handleToResolve").value;
			var handleCode = document.getElementById("handleCode").value;
			document.getElementById('handlesInfoHolder').innerHTML ='<svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">'
		   	+'<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';

			$.ajax({
					type : "GET",
					url : "functions.php",
					dataType : "html",
					cache : "false",
					data : {
						action: 'resolveHandle',
						handleId: handleIdResolve,
						handleCode: handleCode
					},
					success : function(html, textStatus){
						//alert('reslut back');
					$('#handlesInfoHolder').html(html);
					},
					error : function(xht, textStatus, errorThrown){
						alert("Error : " + errorThrown);
					}
				});
		}
	</script>
</body>
</html>
