<?php
//Login page
include_once "scripts/user.php";
$User = new user();
$Page = new page();

//Here we have to first check if the user has logged in
if((bool)$User->login_status() === true){
	//Here we've to redirect the user to his homepage
	$Page->redirect($profile);
}else{
	//Here the user has not logged in
	//Let's first check if this page is a submission of a login_form, this will be achieved by just checking POST
	if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['subt'])){
		//this page is just the submissiion on a login page
		
		$login_info = array(); //Warnings and errors container

		//Checking if all required loggin info are set
		if(!empty($_POST['email'])){
			$email = mysqli_real_escape_string($conn, $_POST['email']);

			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				//Here e-mail is validated well, remaining is to check if it's created and available

				//okay, Let's validate password
				if(!empty($password = mysqli_real_escape_string($conn, $_POST['pwd']) ) && strlen($password)>8){
					//Here the syntax of email and password are okay

					//Going to check if email and password corresponds to the user
					$userID = $User->authenticate($email, $password);

					if($userID){
						//Here the user is correct and we have to check her homepage and redirect here there

						//Let's first authenticate the user in this session
						$User->authsession($userID);
						
						$Page->redirect($profile);
					}else{
						//Invalid credentials
						$login_info = array_merge($login_info, array("Incorrect email and password."));

					}

				}else{
					//Here password is less than 8chars
					$login_info = array_merge($login_info, array("Password too weak, short. Please enter 8 or more characters of your password."));
				}

			}else $login_info = array_merge($login_info, array("Invalid email, Please check the format"));

		}else{
			//Here email is not set
			$login_info = array_merge($login_info, array("Email is required") );
		}

	}

	//Let's load the login form in case there is an error in the submitted input or if this is the initial login page
	if(empty($_POST['subt']) || !empty($login_info)){
		//Here We've to display the login form.

		?><div class="login_page"> <?php

		if(!empty($login_info)){
			//here we've to display errors which are stored in $login_info
			echo "<div class='errors'>";
			foreach ($login_info as $key => $value) {
				echo "<div class='error'>$value</div>";
			}
			echo "</div>";
			
		}
		include "$login_form";

		?> </div> <?php
	}
}

?>