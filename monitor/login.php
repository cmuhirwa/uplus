<?php 
session_start();
if (isset($_SESSION["email"])) {
    header("location: home"); 
    exit();
}
error_reporting(E_ALL);
?>
<?php
$help ="Please sign in Uplus Backend";
	 
if (isset($_POST['email'])){
	
	$email = $_POST['email'];
	$email = md5($email);
	$password = $_POST['password'];
	$password = md5($password);
	include "db.php";
    $sql_check_user = $con->query("SELECT * FROM `users` WHERE `email` = '$email' and `pwd` = '$password' LIMIT 1")or die ($db->error);
	$existCount= mysqli_num_rows($sql_check_user);
	if($existCount == 1)
	{ 
		while($row = mysqli_fetch_array($sql_check_user)){ 
			$id = $row["id"];
			$lastVisits = $row["visits"];
			$newVisits = $lastVisits + 1;
			$sqlVisit = $con->query("UPDATE users SET visits = '$newVisits', lastvisit = now() WHERE id = '$id'");
			$account_type = $row["level"];
		}
		$_SESSION["id"] = $id;
		$_SESSION["email"] = $email;
		$_SESSION["password"] = $password;
		$_SESSION["account_type"] = $account_type;
		header("location: home");
		exit();
    }
	else {
		$help ="Email and Password did not much!";}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>RTGS</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
	<script type="text/javascript" src="js/jquery.min.js"></script> <!-- Custom styles for this template -->

<style>
body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
</head>

  <body>

    <div class="container">

      <form class="form-signin" action="login.php" method="post">
        <h3 class="form-signin-heading"><?php echo $help;?></h3>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" style="background: #007569;" type="submit">Sign in</button>
      </form>
<?php //echo $password; echo '<hr/>'; echo md5('mugabisms@yahoo.com');?>
    </div> <!-- /container -->

  </body>
</html>
