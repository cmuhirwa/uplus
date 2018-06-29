<?php
	//emails
	class email{
		function send($email, $subject, $body, $header='')
		{
				require_once 'mailer/PHPMailerAutoload.php';
				$senderEmail = "uplusrw@gmail.com";
				$senderName = "uPlus";
				$server = "smtp.gmail.com";
				$headers  = $header.= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;

				// $mail->smtpdbect(
				// 		array(
				// 				"ssl" => array(
				// 						"verify_peer" => false,
				// 						"verify_peer_name" => false,
				// 						"allow_self_signed" => true
				// 				)
				// 		)
				// );

				//Enable SMTP debugging.
				$mail->Host = $server;
				$mail->Port = 587;
				$mail->Username = $senderEmail;
				$mail->Password = 'uplusmprw';
				$mail->setFrom($email, $senderName);
				$mail->addAddress($email);
				$mail->Subject = $subject;
				$mail->Body = $body;
				$mail->addCustomHeader($headers);

				$data = "";

				//send the message, check for errors
				if (!$mail->send())
				{
					 //Sending with traditional mailer
					 // $header = "From: $email";
					 // if(mail($email, $subject, $body, $headers."From:$email")){
					 //     $data = true; //Here the e-mail was sent
					 //     }
					 //  else{
					 //      $data = false;
					 //  }

						$data = false;
				}
				else
				{
					 $data = true;
				}

				echo json_encode($data);
		}
	}
	
	//instating new class
	$Email = new email();
?>