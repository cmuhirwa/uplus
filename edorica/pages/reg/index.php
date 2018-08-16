<?php

require_once("setup.php");

include_once('functions.php');

require_once("reg_functions.php");
require_once "scripts/user.php";
$User = new user();

error_reporting(E_ALL);




if($User->login_status() ){

	header("location:$profile");

	die();

}

$reg_form_included =1;

?>



<div class="register-form form">

	<?php

    	if($current_page != 'register'){?><div class="login_title">Register</div><?php }

		else {?> <h1 class="stitle">Sign Up to <?php echo $site_name; ?></h1>

		<?php }

	?>

	    

    <form method="POST" action="<?php echo $register; ?>">

        <input placeholder="First Name" type="text" class="input" name="fname" maxlength="200" value="<?php echo retainValue('fname'); ?>" required><br />

        <input placeholder="Last Name" type="text" class="input" name="lname" maxlength="200" value="<?php echo retainValue('lname'); ?>" required><br />

        <input placeholder="Birthday eg 2000" type="number" class="input" min="1990" max="2013" size="4" type="number" name="birthday" value="<?php echo retainValue('birthday'); ?>" required><br />

        <input placeholder="Email" type="text" class="input" name="email" value="<?php echo retainValue('email'); ?>" required><br />

        <input placeholder="Password" type="password" class="input" name="pwd" required><br />

        <input placeholder="Repeat Password" type="password" class="input" name="rpwd" required><br />

        <input type="hidden" name="submit_check" class="input" value="1">
        <div class="center">
        	<input type="submit" class="input" value="Register" name="submit" class="submit">
        </div>
        

    </form>

</div>

<p>

	Already member? <a href="<?php echo $login; ?>">Login</a>

</p>

<div class="register_errors">

<?php

$test_field = 'submit_check';

//This tests if the form was submitted by passing one field of the form as testing value and method used with the form.

function isFormSent($test_field, $method='POST'){

	if($method="POST"){

		if(isset($_POST[$test_field])){

			return true;

			}

		else return false;

		}

	if($method="GET"){

		if(isset($_GET[$test_field])){

			return true;

			}

		else return false;

		}

}	



//We first check if the form was submitted and suitable method was used.

if(isFormSent($test_field, 'POST')){

	//creating array of needed form-fields to see if they were submitted by function altogether.

	$valstat = inputVal($conn);

		

	if($valstat){

		//Put in the database

		$fname = mysqli_real_escape_string($conn, $_POST['fname']);

		$lname = mysqli_real_escape_string($conn, $_POST['lname']);

		$email = mysqli_real_escape_string($conn, $_POST['email']);

		$birthday = mysqli_real_escape_string($conn, $_POST['birthday']);

		$password = mysqli_real_escape_string($conn, $_POST['pwd']);

		

		//Hashing the password

		$password = bin2hex(mhash(MHASH_MD5, $password, $salt));

		

		$q = mysqli_query($conn, "INSERT INTO users (

		fname,lname, birthday, email, password) VALUES ('$fname', '$lname', '$birthday', '$email' ,'$password')");

		

		//Checking How our database INSERT query worked.

		if($q){

			//We want to verify email;

			$user = mysqli_insert_id($conn); //Getting ID of our last inserted user;

			$time = getdate();

			$stime = $time['year']."-".$time['mon']."-".$time['mday'].' '.$time['hours'].":".$time['minutes'].":".$time['seconds'];

			$hash = mhash(MHASH_MD5, $email, $stime);

			$hash = bin2hex($hash);

			$q = mysqli_query($conn, "INSERT INTO crequests(user, type, time, hash) VALUES ($user, 'emailconf', '$stime', '$hash')");

			if(!$q){

				die( mysqli_error($conn));

				}

			else{

				//Sending mail;

				if(sendmail($email, $hash, $checkmail)){header("location:$checkmail?msg=success");}

				else echo "e-mail was not sent because: ".mysqli_error($conn);

				}

			}

		else{

			echo mysqli_error($conn);}

		}



	

	}

else{

	//form was not submitted

	if(isset($_REQUEST[$test_field])){

		//Invalid mehod was used. -- Security concern

		};

	}

?>



<?php

function inputVal($conn){

$validation_error = '';

if(isset($_POST['fname'])){

		$fname = mysqli_real_escape_string($conn, $_POST['fname']);

		$validation_fname=validateInput("empty", $fname)?"First Name required":"";

		if($validation_fname){

			echo "<div class='error'>First Name required</div>";

			}

		$validation_error.=$validation_fname?"fname ":"";

		}

	else{

		$validation_fname='First Name required';

		$validation_error.=$validation_fname?"fname ":"";	

		echo "<div class='error'>First Name required</div>";

		}

		

	if(isset($_POST['lname'])){

		$lname = mysqli_real_escape_string($conn, $_POST['lname']);

		$validation_lname=validateInput("empty", $lname)?"Last Name required":"";

		$validation_error.=$validation_lname?"lname ":"";

		if($validation_lname){

			echo "<div class='error'>Last Name required</div>";

			};

		

		}

	else{

		$validation_lname='Last Name required';

		$validation_error.=$validation_lname?"lname ":"";	

		echo "<div class='error'>Last Name required</div>";	

		}

		

	//Validating Birthday

	if(isset($_POST['birthday'])){

		$birthday = mysqli_real_escape_string($conn, $_POST['birthday']);

		$validation_birthday=validateInput("empty", $birthday)?"Birthday required":"";

		$validation_error.=$validation_birthday?"birthday ":"";

		if($validation_birthday){

			echo "<div class='error'>Birthday required</div>";

			}		

		}

	else{

		$validation_birthday='Birthday required';

		$validation_error.=$validation_birthday?"birthday ":"";

		echo "<div class='error'>Birthday required</div>";	

		}

	

	//Validating email

	if(isset($_POST['email'])){

		$email = mysqli_real_escape_string($conn, $_POST['email']);

		$validation_email=validateInput("empty", $email)?"E-mail required":"";

		if($validation_email){echo "<div class='error'>e-mail required</div>";}



		//Checking if email is of correct format

		else if(!preg_match('/@/', $email) || !preg_match('/./', $email)){

					$validation_email = "Invalid email";

					$validation_error.=$validation_email?"email<symbols>":"";

					if($validation_email){

							echo "<div class='error'>Invalid Email - Complete your email</div>";

							}								

		

		}

		//Checking if email is already used		}

		else{

			$q = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

			if(!$q){

				echo mysqli_error($conn);

				}

			else if(count(mysqli_fetch_assoc($q))){

				echo "Email already used.";

				$validation_error.="email<used>";

				}

			}

		}

		

	else{

		$validation_email='E-mail required';

		$validation_error.=$validation_email?"email ":"";		

		}

	//Validating password

	if(isset($_POST['pwd'])){

		$password = mysqli_real_escape_string($conn, $_POST['pwd']);

		$validation_password=validateInput("empty", $password)?"Password required":"";

		

		if($validation_password){

			$validation_error.=$validation_password;

			echo "<div class='error'>Password required</div>";

			}

		

		}

	else{

		$validation_password='Password required';

		$validation_error.=$validation_password?"pwd":"";

		echo "<div class='error'>Password required</div>";

		}

	//Password Confirmation

	if(isset($_POST['rpwd'])){

		$rpassword = mysqli_real_escape_string($conn, $_POST['rpwd']);

		$validation_rpassword=validateInput("empty", $rpassword)?"Confirm Password":"";

		$validation_error.=$validation_rpassword?"rpwd":"";

		if($validation_rpassword){

			echo "<div class='error'>Repeat your Password</div>";

			};

		

		}

	else{

		$validation_rpassword='Confirm Password';

		$validation_error.=$validation_rpassword?"rpwd":"";	

		echo "<div class='error'>Repeat your Password</div>";

		}

	//Checking if pwd==rpwd

	if(!$validation_password && !$validation_rpassword){

		if($password != $rpassword){

			$pwdconf = "Passwords do not match";

			$validation_error.=$pwdconf?"pwdconf":"";

			if($pwdconf){ echo "<div class='error'>Passwords do not match</div>";

			}

			

			}

		else{} }

		if(empty($validation_error)) return true;

		else return false;

}



?>

</div>