<?php

function sendmail($email, $hash, $checkmail_link, $again=0){

	//including mail class used to send e-mail

	include_once("scripts/mail.php");

	$mail = new mail();

	

	$site_domain = $GLOBALS['site_domain'];

	$conn = $GLOBALS['conn'];

	$message = "Click here to confirm your "._SITE_NAME." email address<br />http://".

				$site_domain."/".$checkmail_link."?msg=conf&email=".$email."&chk=$hash";

				$headers = 'From: admin@klab.com' . "\r\n";

				

				$subject = 'Confirm your e-mail address';

				

				//Going to try sending mail with phpmailer, if it fails I will use mail() function

				$mailer = $mail->send($email, $subject, $message);

				if($mailer){

					return true;

					}

				else return false;

				

				}



//Function t send recover mail

function sendrecmail($email, $hash, $link, $rec_code){

	//including mail class used to send e-mail

	include_once("scripts/mail.php");

	$mail = new mail();

	

	$site_domain = $GLOBALS['site_domain'];

	$conn = $GLOBALS['conn'];

	$site_name= $GLOBALS['site_name'];

	$subject = "Recover $site_name password"; //This will be the subject to the user mail



	$message = "Click here to change your $site_name.com password<br />http://".$site_domain."/".$link."?msg=rec&code=$rec_code&chk=$hash&mail=$email<br />You can use also: $rec_code";



				//Going to try sending mail with phpmailer, if it fails I will use mail() function

				$phpmailer = $mail->send($email, $subject, $message);

				

				if(!$phpmailer){ echo "PHP my admin failed<br />";

				$header = "From: "._FROM_EMAIL; 

					$email = mail($email, $subject, $message, $header);

					if($email){

						return true;

						}

					else{					

						return false;

						}

					}

					else return true;

				

	}

	

function redirect($URL){

	header("location:$URL");

	?>

	<div class="redirect">If you are not automatically redirected, click <a href="<?php echo $URL ; ?>">here</a></div>

	<?php

	}



function hashexists($conn, $hash){

	$q = mysqli_query($conn, "SELECT id FROM crequests WHERE hash='$hash'");

	if(!$q){

		die(mysqli_error($conn));

		}

	else{

		$data = mysqli_fetch_assoc($q);

		if(count($data)==1) return 1;

		else if(count($data)<1) return 0;

		else return "Error";

		}

	}



function retainValue($field, $method="POST"){

		if($method = "POST"){

			if(isset($_POST[$field])){

				$ret = htmlentities($_POST[$field]);

			}

			else $ret = NULL;			

		}

		else{

			if(isset($_GET[$field])){

				$ret = htmlentities($_GET[$field]);

			}

			else $ret = NULL;	

		}

		return $ret;

	}

	?>