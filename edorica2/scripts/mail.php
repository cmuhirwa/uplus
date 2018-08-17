<?php
	class mail{
		function send($email, $subject, $body, $header=''){
			  require_once 'mailer/PHPMailerAutoload.php';

				$headers  = $header.= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			   $mail = new PHPMailer;
			   $mail->isSMTP();
			   $mail->SMTPSecure = 'tls';
			   $mail->SMTPAuth = true;

			   //Enable SMTP debugging.
			 	//$mail->SMTPDebug = 3;

			   $mail->Host = 'tls://smtp.gmail.com:587';
			   $mail->Port = 587;
			   $mail->Username = 'wswapped@gmail.com';
			   $mail->Password = 'Laa1001Laa#';
			   $mail->setFrom('wswapped@gmail.com');
			   $mail->addAddress($email);
			   $mail->Subject = $subject;
			   $mail->Body = $body;
			   $mail->addCustomHeader($headers);
			   //send the message, check for errors
			   if (!$mail->send()) {
				   //Sending with traditional mailer
				   $this->init("default"); //Initializing mail parameters SMPTP settings

				   $header = "From: "._FROM_EMAIL;
				   if(mail($email, $subject, $body, $headers."From:"._FROM_EMAIL)){
					   return true; //Here the e-mail was sent
					   }
					else{
						//echo "ERROR: " . $mail->ErrorInfo;
						return false;
						}



			   }
			   else {
				   return true;
			   }

			}
		function init($name = "default"){
			ini_set("SMTP", "smtp.gmail.com");
			ini_set("smtp_server", "smtp.gmail.com");
			ini_set("smtp_port ", "587");
			ini_set("smtp_ssl", "auto");
			ini_set("auth_username", "wswapped@gmail.com");
			ini_set("auth_password", "laa1001laa");
			}

	}
	$mail = new mail();
?>
