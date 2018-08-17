<?php

//Register.php initialisation

session_start(); //Starting The Session

$validation_error ='';
$User = WEB::getInstance("user");

#if the user has logged in, will be redirected to profile

if($User->login_status()){
	header("location:$profile");
}

	

#Validating Inputs

//Checking if data is sent using post

if($_SERVER["REQUEST_METHOD"] == "POST"){

if(isset($_POST)){

	#Data Validation

	if(isset($_POST['fname'])){

		$fname = mysqli_real_escape_string($conn, $_POST['fname']);

		$validation_fname=validateInput("empty", $fname)?"First Name required":"";

		$validation_error.=$validation_fname?"fname":"";

		}

	else{

		$validation_fname='First Name required';

		$validation_error.=$validation_fname?"fname":"";	

		}

		

	if(isset($_POST['lname'])){

		$lname = mysqli_real_escape_string($conn, $_POST['lname']);

		$validation_lname=validateInput("empty", $lname)?"Last Name required":"";

		$validation_error.=$validation_lname?"lname":"";

		}

	else{

		$validation_lname='Last Name required';

		$validation_error.=$validation_lname?"lname":"";		

		}

		

	//Validating Birthday

	if(isset($_POST['birthday'])){

		$birthday = mysqli_real_escape_string($conn, $_POST['birthday']);

		$validation_birthday=validateInput("empty", $birthday)?"Birthday required":"";

		$validation_error.=$validation_birthday?"birthday":"";

		}

	else{

		$validation_birthday='Birthday required';

		$validation_error.=$validation_birthday?"birthday":"";	

		}

	

		//Validating email

	if(isset($_POST['email'])){

		$email = mysqli_real_escape_string($conn, $_POST['email']);

		$validation_email=validateInput("empty", $email)?"E-mail required":"";

			if(!$validation_email){

				if(!ereg("@", $email) || !ereg('.', $email)){

					$validation_email = "Invalid email";

					$validation_error.=$validation_email?"email":"";

					}

				}		

		}

	else{

		$validation_email='E-mail required';

		$validation_error.=$validation_email?"email":"";		

		}

	//Validating password

	if(isset($_POST['pwd'])){

		$password = mysqli_real_escape_string($conn, $_POST['rpwd']);

		$validation_password=validateInput("empty", $password)?"Password required":"";

		$validation_error=$validation_password?"pwd":"";

		}

	else{

		$validation_password='Password required';

		$validation_error=$validation_password?"pwd":"";

		}

	//Password Confirmation

	if(isset($_POST['rpwd'])){

		$rpassword = mysqli_real_escape_string($conn, $_POST['rpwd']);

		$validation_rpassword=validateInput("empty", $rpassword)?"Confirm Password":"";

		$validation_error=$validation_rpassword?"rpwd":"";

		}

	else{

		$validation_rpassword='Confirm Password';

		$validation_error=$validation_rpassword?"rpwd":"";	

		}

	//Checking if pwd==rpwd

	if(!$validation_password && !$validation_rpassword){

		if($password != $rpassword){

			$pwdconf = "Passwords do not match";

			$validation_error=$pwdconf?"pwdconf":"";}

		else{} }

	else{}

	

	

				if(empty($validation_error)){

					#Getting User's data

					#Checking That Username Already exists

					$fname=test_input($_POST['fname']);

					$lname=test_input($_POST['lname']);

					$email=$_POST['email'];

					$birthday=$_POST['birthday'];



					$password=test_input($_POST['pwd']);

					

					$user_reg_query= "INSERT INTO users (fname,lname, birthday, email, password) VALUES ('$fname', '$lname', '$birthday', '$email' ,'$password')";;

					$user_reg_action=mysqli_query($conn, $user_reg_query);

					if(!$user_reg_action){

						echo "could not sign up because: ".mysqli_error($conn);

						}

						

					else{

						login_cookie($login_username_hash, $fname, time()+(86400*30));

						login_cookie($login_password_hash, $password, time()+(86400*30));

						header("location:$profile");

						}

}

else if(!empty($validation_error)){ echo "Can't  signup"; }

}

	}

?>

<?php include_once("login/signup.php"); ?>

 <script>

 console.log("<?php echo $validation_error?$validation_error:"No error"; ?>");

 </script>