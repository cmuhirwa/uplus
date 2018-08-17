<?php

error_reporting(E_ALL);

$test=true;

if(!isset($site_name)){

	$site_name="edorica";

	}

?>

<div class="login">

  <div class="login-header">

    <h1>Register</h1>

  </div>

  <div class="login-form">

  <form  name="register" method="post" action="<?php echo $register; ?>">

    <h2>First name:</h2>

    <input type="text" name="fname" placeholder="fname" <?php if(isset($test)){echo "value='Kali'";} ?>/><br>

    <li style="list-style:none"><?php if(isset($validation_fname)){ echo $validation_fname; };?></li>

    <h3>Last Name:</h3>

    <input type="text" name="lname" class="input" placeholder="lname" <?php if(isset($test)){echo "value='Kali'";} ?>/><br>

    <li style="list-style:none"><?php if(isset($validation_lname)){ echo $validation_lname; };?></li>

    <span class="birtday_container">

    <h2>Birthday:</h2>

    <input type="number" class="input" maxlength="5" name="birthday" minlength="4" placeholder="Year" <?php if(isset($test)){echo "value='1990'";} ?> /><br>

    <li style="list-style:none"><?php if(isset($validation_birthday)){ echo $validation_birthday; };?></li>

    </span>

    <h4>Email:</h4>

	<input type="text" class="input" name="email" placeholder="E-mail" <?php if(isset($test)){echo "value='Kali@email'";} ?>/><br>

    <li style="list-style:none"><?php if(isset($validation_email)){ echo $validation_email; };?></li>

    <h5>Password:</h5>

    <input type="password" class="input" name="pwd" placeholder="Password" <?php if(isset($test)){echo "value='Kali'";} ?>/><br>

    <li style="list-style:none"><?php if(isset($validation_password)){ echo $validation_password; };?></li>

    <h6>Confirm a password:</h6>

    <input type="password" class="input" name="rpwd" placeholder="Password"<?php if(isset($test)){echo "value='Kali'";} ?>/><br>

    <li style="list-style:none"><?php

    if(isset($validation_rpassword)){

		if(isset($pwdconf)){echo $validation_rpassword."<br />".$pwdconf;}

		else echo $validation_rpassword;}

	else if(isset($pwdconf)){ echo $pwdconf; }?></li>

    <input type="hidden" name="st" value="sbtd" />

    <div class="center">
        <input type="submit" value="Sign Up" class="submit input login-button"/>
    </div>

    <br>

  </form>

    <span class="registerl">Already have <?php echo $site_name." "; ?>account? <a id="loginPageLink" href=""<?php echo $new_login; ?>" class="sign-up">Log In!</a> </span>  

  </div>

</div> 

<script>

var loginPageLink = document.querySelector("#loginPageLink");

loginPageLink.addEventListener("click", OpenLoginPage);

function OpenLoginPage()

{	document.querySelector("#loginPageLink").href='';

	$.get("<?php echo $new_login; ?>signup.php", function(data){$("body").html(data)})}

</script>  

</body>

</html>

