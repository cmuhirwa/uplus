<?php
$email = "Hello";

$register_link = $register;
$login_link = $login;
require_once("reg_functions.php");
include_once('functions.php');
?>
<?php
//Checking msg
$path = $page->getPath();
?>
<pre>
<?php
if(isset($path['query_vars'])){
	$query_vars =$path['query_vars'];
	$msg = $query_vars?$query_vars['msg']:'';
	}
else $msg='';
?>
</pre>
<?php
?>

</pre>
<?php
if(isset($msg) && $msg !=''){
	$msg = mysqli_real_escape_string($conn, $msg);
	if($msg == "success"){
		
		?>
        <div class="panel">
            <h1 class="stitle">Check inbox and spam folder.</h1>
            <div class="modbox">
                Dear member,<br />
                Goto your email's inbox and check our message to confirm your email address.
                Remember to check your spam folder.
            </div>
            </div>
        <div class="send-again">
        	<div class="email-fold">Didn't Receive e-mail?</div>
        <form class="form" method="POST" action="<?php echo $checkmail."?msg=resend"; ?>">
        	<input type="email" name="email">
            <input type="submit" value="Submit">
        </form>
        </div>
        <?php		
	}//Closing  if($msg == "success")
	
	//Resending link to confirm email address
	else if($msg == 'resend'){
		//Checking if the email is in user table and has given the request
		if($_POST['email']){
			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$uqery = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
			
			$userdata = mysqli_fetch_assoc($uqery);
			if(count($userdata)<1){
				?>
                <div class="modbox">
                	Your email could not be found. Please Register <a href="<?php echo $register_link; ?>">here</a> 
                    or <a href="<?php echo $login_link; ?>">Login</a>.</div>
                <?php
				}
				//email exist in database
			else {
				//Checking If the user has requested something\
				$id = $userdata['id'];
				$requery = mysqli_query($conn, "SELECT * FROM crequests WHERE user=$id");
				$requery = mysqli_fetch_assoc($requery);
					if($requery['type']=='emailconf' && $requery['status']==0){
						$hash = $requery['hash'];
						if(sendmail($email, $hash, $checkmail)){
							redirect($checkmail."?msg=success");
							}
						else{
							echo "Error Sending email please check your email address! :)";
							echo mysqli_error($conn);
							}
						}
					else if($requery['status']!=0){
						?>
                        <div class="modbox">
                        	Your email has no configuration request.<br />
                            Please <a href="<?php echo $login_link; ?>">login</a> or <a href="<?php echo $register_link ?>">register</a>.
                        </div>
                        <?php
						}
					else ;				
						
				
				}
		}
		
	}//Closing if($msg == 'resend')
	
	else if($msg = 'conf'){
		if(isset($query_vars['email']) && isset($query_vars['chk'])){
			$email = mysqli_real_escape_string($conn, $query_vars['email']);
			$hash = mysqli_real_escape_string($conn, $query_vars['chk']);
			if(userexists($conn, $email) && hashexists($conn, $hash)){
				$q = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
				$q= mysqli_fetch_assoc($q);
				$id = $q['id'];
				
				//Checkin request with $user=$id
				$hq = mysqli_query($conn, "SELECT * FROM crequests WHERE user='$id' AND hash='$hash'");
				if($hq){
					$hash = mysqli_fetch_assoc($hq);
					if($hash['type']=='emailconf' && $hash['status']==0){
						$ohash =$hash['hash'];
						$cq = mysqli_query($conn, "UPDATE crequests SET status=1 WHERE hash='$ohash'");
						if($cq){
							//Successfully confirmed email
							?>
                            <div class="modbox">
                            Congratulations!<br>
							Successfully registered. Goto <a href="<?php echo $login_link; ?>">Login</a>
                            </div>
                            
                            <?php
							
							
							}
						else die(mysqli_error($conn));
						}
					}
				else{}
				}
			//if email or hash are incorrect
			else{?>
            <div class="modbox">
            	Dear user,<br />
                You have used incorrect URL.<br />
                Please check back link in your inbox and click on it or copy it and paste it in address bar.
                or <a href="<?php echo $_SERVER['PHP_SELF'].'?msg=resend' ; ?>">request</a> new URL.
            </div>
            
            <?php
				
				}
			}
		else{
			//incorrect URL
			?>
            <div class="modbox">
            	Dear user,<br />
                You have used incorrect URL.<br />
                Please check back link in your inbox and click on it or copy it and paste it in address bar.
                or <a href="<?php echo $_SERVER['PHP_SELF'].'?msg=resend' ; ?>">request</a> new URL.
            </div>
            
            <?php
			}
		}
	}//if $msg!=''
	else{
		//Here no query variables sent.... Just handle it
		?>
        <div class="modbox">
            Please check your email's inbox and comfirm your inbox.
            <a href="<?php echo $home; ?>">Continue to Home</a>
            </div>
        <?php
		
		}
?>