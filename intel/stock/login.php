
<?php 
session_start();
if (isset($_SESSION["username"])) {
    header("location: index.php"); 
    exit();
}
error_reporting(0);
?>
<?php
if (isset($_POST['username'])){
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	require 'db.php';
	$help ="";
	$sql_check_user = $db->query("SELECT * FROM users WHERE username = '$username' AND password = '$password' limit 1")or die ($db->error);
	$existCount= mysqli_num_rows($sql_check_user);
	if ($existCount == 1) { // evaluate the count
	     while($row = mysqli_fetch_array($sql_check_user)){ 
             $id = $row["id"];
             $account_type = $row["account_type"];
		 }
		
		 $_SESSION["id"] = $id;
		// echo $phone;
		 $_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		if($account_type =='admin')
		{
			header("location: index.php");
			exit();
		}
			elseif ($account_type =='user')
		{
			header("location: index.php");
		exit();
		}
    }else {$help.="";}
}
else{
	 $help="";
}
?>


<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="A fully Inventory MS">
        <meta name="author" content="Clement">

        <link rel="shortcut icon" href="assets/images/favicon_1.ico">

        <title>Intelliware!</title>

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css">
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css">
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css">

        <script src="assets/js/modernizr.min.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','../../www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65046120-1', 'auto');
  ga('send', 'pageview');

</script>
    </head>
    <body>


        <div class="wrapper-page">
            <div class="panel panel-color panel-primary panel-pages">
                <div class="panel-heading bg-img"> 
                    <div class="bg-overlay"></div>
                    <h3 class="text-center m-t-10 text-white"> Sign In to <strong>Intelliware</strong> </h3>
                </div> 


                <div class="panel-body">
				<form class="form-horizontal m-t-20" method="post" action="login.php"">
                    
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control input-lg" type="text" required="Plz Provide your username"  name="username" placeholder="Username">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control input-lg" type="password"  name="password" required="your password plz" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox-signup" type="checkbox">
                                <label for="checkbox-signup">
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-center m-t-40">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>
</form> 
                </div>                                 
                
            </div>
        </div>

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- Main  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/js/jquery.app.js"></script>
	
	</body>

<!-- Mirrored from moltran.coderthemes.com/dark/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 10 Apr 2016 14:14:01 GMT -->
</html>