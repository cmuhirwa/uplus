<?php

//Including Page Helpers

$functions = $edorica->getFile("functions.php", $level);

include_once "scripts/user.php";
$User = new user();

if(file_exists($functions)){

	include_once($functions);

}



if(file_exists($reg_functions)){

	include_once($reg_functions);

}

else{

	die("Error getting fx helper");

	}

	

//Checking if user is logged in

if($User->login_status()){

	header("location:$profile");

	die();

}



$show_form = 0;

?>

<?php

//In this block we are going to process request for recovering password

//We will achieve by checking a specific variable on get

$get_vars = $page->get();

if(isset($get_vars['msg']) && $get_vars['msg']!='' && $get_vars['msg']='rec'){

	if(isset($_POST['rsubmit'])){

		include_once("scripts/password_update.php");

		}

	

	//If all credentials are brought, this link was clicked in the email

	if( isset($get_vars['mail']) && isset($get_vars['chk']) && $get_vars['chk']!='' && $get_vars['mail']!=''){

		

		$email = mysqli_real_escape_string($conn, $get_vars['mail']);

		$hash = mysqli_real_escape_string($conn, $get_vars['chk']);

		

		//Checking if the email and hash in the link are in the database

		$user = userexists($conn, $email);

		if($user && hashexists($conn, $hash)){

			

			//Checking if hash and email are associated with pwdrec

			$hq = mysqli_query($conn, "SELECT * FROM crequests WHERE user='$user' AND hash='$hash'  AND type='pwdrec' ") or die("Error".mysqli_error($conn));

			$reqd = mysqli_fetch_assoc($hq);

			if($reqd){

				//if hash and email are associated, I display form for password change.

				?>

                <div class="sform pwd-change border_form">

                	<h1 class="stitle">Enter new password</h1>

                	<form method="POST" name="pwd_change" action="<?php echo $recover_password."?msg=rec"; ?>" method="POST">
                    	<input name="pwd" type="password" />
                        <input name="rpwd" type="password" />
                        <input name="rsubmit" type="submit" />
                    </form>

                </div>

                <?php

				//Including the password updating and recover validation file

                	

				?>

                <?php

				}

			else{

				echo "Invalid credentials<br />Try recovering again or check well your email's link";

				}

			}

		else{

			echo "Invalid Link check your email";

			}

		die(); //Protecting further processing other than real pwd recovering

		}

	else{

		?>

		<div class="modbox">

			We have sent you a recovery mail, Check your inbox and spam folder.

		</div>

		<?php		

		die();

	}

		

	}

?>




<div class="margin-center">
<h1 class="stitle">Recover your password</h1>

<div class="foform">

	<form name="forgotpassword" action="<?php echo $recover_password; ?>" method="POST">

    	<input name="email" type="email" value="<?php echo retainValue('email'); ?>" placeholder="Enter e-mail" />

        <input name="test" type="hidden" value="<?php echo "Wolf"; ?>" placeholder="Enter e-mail" /> <br />

        <input name="submit" type="submit" value="Recover">

    </form>

</div>
</div>

<?php

//Checking if form is submitted by checking if submit button was clicked with name "submit"

if(isset($_POST['submit'])){

	if(isset($_POST['email'])){

		//If the e-mail was submitted!

	$email = mysqli_real_escape_string($conn, $_POST['email']);

	

	//Checking if there is user with $email

	if(userexists($conn, $email)){

		//Checking if the user has confirmed his address

		$conf_status = comfirmed($conn, $email);

		

		if($conf_status==1){

			

			//Getting user id

			$uiq = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

			if($uiq){

				$uid = mysqli_fetch_assoc($uiq);

				$user = $uid['id'];

				}

			else die("Eror Getting user data with email you've provided".mysqli_error($conn));

			

			

			

			//Preparing to send email

			

			//Calculating time that we will insert in crequests table that we can be able to track request times

			$time = getdate();

			$stime = $time['year'].

			"-".$time['mon']."-".$time['mday'].' '.$time['hours'].":".$time['minutes'].":".$time['seconds'];

			

			//Calculating haash to be sent to the user and put in the database

			$hash = mhash(MHASH_MD5, $email, $stime);

			$hash = bin2hex($hash);

			

			echo phpversion()." vERION sESSION";

			

			$rec_code = random_int(100000, 999999); //This failed because it is only supported in PHP 7

			

			//inserting password reset request in database

			$q = mysqli_query($conn, "INSERT INTO crequests(id, user, type, time, hash, code) VALUES ('', $user, 'pwdrec', '$stime', '$hash', '$rec_code')");

			if(!$q){

				die("E3: ".mysqli_error($conn));

				}

			else{

				//Sending mail;

				$link = $recover_password; // this is the handler of pwd recovery

				

				if(sendrecmail($email, $hash, $link, $rec_code)){header("location:$recover_password?msg=rec");}

				else echo "We could not send your e-mail because:<b /r><li>Your email is invalid.<br><li>Server issues".mysqli_error($conn);

				}

			

			}

		else if($conf_status==0){

			?>

            <div class="modbox">

            	You Have not comfirmed your e-mail,<br />Please check your e-mail inbox and comfirm your email.

            </div>

            <?php					

			}

		else if(1);

		}

	else{?>

     <div class="modbox">

     	Your e-mail could not be found!<br />

        Please <a href="<?php echo $register; $show_form=1 ?>">register</a> your e-mail.

        </div>

     <?php

  		}

	}

	else{

		echo "Your e-mail address is required";

		}

}



?>

<script type="text/javascript">

	//Protecting too many chars in recovery code

	document.querySelector("#rec_code_in").addEventListener("focus", function(){

		console.log("jisak")

	if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);

	})

</script>