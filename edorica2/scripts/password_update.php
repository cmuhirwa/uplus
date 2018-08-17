<?php
if(isset($_POST['rsubmit'])){	/*
	*Here the new passowrd recovery form was submitted
	*I proceed by validation and updating
	*/
	//checking if password and rpassword are set and equal
	if( isset($_POST['pwd'])&& isset($_POST['rpwd']) && !empty($_POST['rpwd']) && !empty($_POST['pwd'])){
		$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
		$rpwd = mysqli_real_escape_string($conn, $_POST['rpwd']);
		
		//Checking if pwd and rpwd are equal
		if($pwd ==$rpwd){
			//Hrere let's update user
			
			}
		else
		{
			//Pwd fielsds are not equal
			echo "Passwords should be the same!";
			}
		}
	else{
		//Here either password or rpwd was not set
		echo "Please fill all fields";
		}
	die();
	}
?>
