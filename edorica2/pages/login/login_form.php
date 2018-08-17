<?php
include_once "scripts/user.php";
$User = new user();
include_once($reg_functions);
if($User->login_status()){

	header("location:$profile");

	die();

}

$ogin_form_included=1;

?>
<div class="login-header">

		<?php

	if($current_page==$login){

			echo "<h1 class = 'stitle'>Login</h1>";

	}
	?>

	</div>

	<div class="login-form">

	<form  name="login" method="post" action="<?php echo $login; ?>">

 

				<input type="email" class="input" name="email" placeholder="Enter Your e-mail" value="<?php echo retainValue('email'); ?>" required/>

				<li style="list-style:none">

			<?php if(isset($validation_u_message)){ echo $validation_u_message;} ?>

				</li>

								

				<input type="password" class="input" name="pwd"  placeholder="Password" required/>

				

				<li style="list-style:none">

			<?php if(isset($validation_p_message)){ echo $validation_p_message;} ?>

				</li>

				<input name="st" type="hidden" value="subtd" />

				<input type="submit" class="input" name="subt" value="Log In" class="submit login-button"/>

	</form>

				<p class="no-access"><a href="<?php echo $recover_password; ?>">Forgot password?</a></p>

				<p class="l-signup"><a href="<?php echo $register; ?>">Sign Up</a></p>

	</div>

 <?php if(isset($reg_form_included)) { ?>

		<span class="registerl">New to <?php echo $site_name." "; ?><a id="signUpPage" class="sign-up">Sign Up!</a> </span>

		<script>

		var signUpPage = document.querySelector("#signUpPage");

		signUpPage.addEventListener("click", openSignUp);

		function openSignUp()

		{

				$.get("<?php echo $register; ?>", function(data){$("body").html(data)})

				

				}

		</script>

<?php } ?>