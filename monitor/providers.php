<?php
	// Get me backif i havent logedin
	session_start();
	if (!isset($_SESSION["email"])) {
		header("location: logout.php"); 
		exit();
	}
	

	
		
$account_type = preg_replace('#[^0-9]#i', '', $_SESSION["account_type"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
$email = $_SESSION["email"]; // filter everything but numbers and letters

include "../db.php"; 
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
	echo "<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
	<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
	Or<p><a href='logout.php'>Click Here to login again</a></p>";
	exit();
}

include_once '../scripts/class.providers.php';
$services = $Provider->services();

//Providers list
$providers = $Provider->list();
?>
<!DOCTYPE html>
<html>
<head>
	<title>SERVICE PROVIDERS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap4.min.css">
	<link rel="icon" href="/uplus.png">
</head>
<body>
<?php
	//including navbar
	include 'modules/navbar.php';
?>

<?php
	//handle the routing
	$action = $_GET['action']??"home";

	//default homepage
	if($action == 'home'){
		?>
			<div class="container"> 
				<div style="color: #fff; font-size: 20px; background-color: #007569; height: 100px;     box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
				color: #fff;">
					<img style="margin: 15px; " src="/frontassets/img/service-providers.png" height="70" >
					&nbsp;&nbsp;&nbsp;(<?=count($providers);?>) Service Providers
				</div>
				<br>
				<div class="jumbotron">
					<div class="row">
						<div class="col-xs-2">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-bordered" style="float: left;">
									<thead>
										<tr>
											<th>Services</th>
										</tr>
									</thead>
									<?php
										foreach($services as $service) {
											echo '<tbody><tr>
										<td><a href="bank.php?page='.$service['id'].'">'.ucfirst($service['name']).'</a></td>
									</tr></tbody>';
										}
									?>
										
								</table>
							</div>
						</div>
						<div class="col-xs-10">
							<div class="">
								<a href="?action=add" class="btn btn-info pull-right">Add Provider</a>
								<br />
								<br />
							</div>
							<div class="table-responsive">
								<table class="table table-hover table-striped table-bordered" style="float: left;">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Location</th>
											<th>Services</th>
											<th>Contact Phone</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php								
											$n = 0;
											foreach ($providers as $key => $provider) {
												$n+=1;
												?>
												<tr>
													<td><?=$n?></td>
													<td><?=$provider['name']?></td>
													<td><?=$provider['location']?></td>
													<td></td>
													<td></td>
													<td><a href="?view=">View</a></td>
												</tr>
												<?php
											}
										?>
													
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}else if($action == 'add'){
		include 'modules/addServiceProvider.php';
	}
?>
<div class="footer" style="position: absolute;bottom: 0px;width: 100%;">
	<footer style="background-color: #eee; padding: 10px;" class="text-center">Uplus &copy; <?=date("Y")?></footer>	
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>