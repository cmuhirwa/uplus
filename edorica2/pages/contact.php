<?php 

include_once "scripts/mail.php";
$mail = new mail();
$setup_file = "setup.php";
$get = $myPage->get();

if(file_exists($setup_file)){
	require_once($setup_file);
	}
else{
	//If set up file is o=not found then i try to assume that contact script is here:
	$contact_script = '../scripts/contact_script.php';
	}


if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit']) ){

	if(!empty($_POST['name'])){

		$name = mysqli_real_escape_string($conn, $_POST['name']);

		if(!empty($_POST['email'])){

			$email =  mysqli_real_escape_string($conn, $_POST['email']);

			if(!empty($_POST['message'])){
				$message =  mysqli_real_escape_string($conn, $_POST['message']);

				if(!empty($_POST['subject'])){

					$subject  =  mysqli_real_escape_string($conn, $_POST['subject']);

				}else $subject='';

				//Sending the message
				//Putting this message in the database.
				$html_message = "<h1>Contact Message from Web</h1>
					Name: $name,
					email: $email,
					subject: $subject,
					message: $message.						
					";
					
				$mail->send(_CONTACT_EMAIL, "Edorica contact message - $subject", $html_message);
				$mail->send('placidelunis@gmail.com', "Edorica contact message - $subject", $html_message);
				$q = mysqli_query($conn, "INSERT messages(name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')") or die(mysqli_error($conn))
				?>

                <p>Dear <?php echo $name?$name:"User"; ?>,<br /> Thanks for reaching out.</p>
                <p>We will reply you soon</p>

                <?php
            }else{
				echo "Please enter your message.";

				}

		}else{
			echo "We will reply you through your e-mail, Please enter it.";
		}
		
	}else echo "Enter Your Name";

}else{
?>
<div class="mdpage">
	<h1 class="h1" style="">Contact us</h1>

	<?php
		//CHecking some sent contents for contact
		$psubject = $get['subject']??""; //Proposed subject
	?>

	<p>Dear esteemed customer we are happy to hear from you.</p>

	Simply fill in this form and we will reply as soon as possible.<br>

	<div class="contact_form">

	    Fill in this form to contact us.<br>

	    <form action="<?php echo $contact; ?>" method="POST" name="contact" class="">

	        <input type="text" class="input" name="name" maxlength="60" placeholder="Enter your name" required>

	        

	        <input type="email" class="input" name="email" placeholder="Enter email" required>

	        <input type="text" class="input"  name="subject" placeholder="Enter Subject" <?php echo !empty($psubject)?"value=\"$psubject\"":''; ?>>

	        <textarea name="message" placeholder="Enter your message" required></textarea>

	        <input class="submit" name="submit" class="submit" type="submit" value="Submit">

	    </form>

	</div>
	<div class="modbox">
	<p>You can also contact us direct using:</p>
	<ul>
		<li>email: <?php echo _CONTACT_EMAIL; ?></li>
		<li>Facebook: <a href="http://www.facebook.com/<?php echo _fb_username; ?>">Edorica</a></li>
	</ul>
</div>
<?php

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit']) ){

	if(isset($_POST['name'])){

		$name = mysqli_real_escape_string($conn, $_POST['name']);

		if(isset($_POST['email'])){

			$email =  mysqli_real_escape_string($conn, $_POST['name']);

			if(isset($_POST['message'])){

				$message =  mysqli_real_escape_string($conn, $_POST['message']);

				

				if(isset($_POST['subject'])){
					$subject  =  mysqli_real_escape_string($conn, $_POST['subject']);
					}else $subject='';

				//Putting this message in the database.

				$mysqli_query($conn, "INSERT INTO INSERT messages(name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')") or die(mysqli_error($conn));
				?>

                <p>Dear <?php echo $namel; ?>; Thanks for reaching out.</p>

                <p>We will reply soon</p>

                <?php		

			}
			else{
				echo "Please enter your message.";
			}

		}
		else{
			echo "We will reply you through your e-mail, Please enter it.";
			}		

	}

	else echo "Enter Your Name";

	}

?>

<?php } ?>