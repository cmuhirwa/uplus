<?php
include_once "scripts/user.php";
$User = new user();
include_once($reg_functions);
$Page = WEB::getInstance("page");

// if($User->login_status()){
// 	header("location:$profile");
// 	die();
// }
WEB::require('css/admin_login.css', 'head');

?>
	<div class="loading">
		<p id="login-title">Edorica School Admin</p>
	</div>

	<div class="login">
		<div class="validation">
			<?php
				if(!empty($_POST['subt'])){
					$name = $_POST['u']??"";
					$pwd = $_POST['p']??"";
					if(!empty($name) && !empty($pwd)){
						//Logging in
						$sql = "SELECT * FROM staff WHERE (username = \"$name\" OR email = \"$name\") AND password = \"$pwd\"";
						$query = mysqli_query($conn, $sql) or die("admin logging in error: ".mysqli_error($conn));
						if(mysqli_num_rows($query)>0){
							$data = mysqli_fetch_assoc($query);
							echo "Redirecting to dashboard ...";
							$_SESSION['staff_id'] = $data['id'];
							$Page->redirect($school_admin);
						}else echo "Incorrect pwd";
					}else{
						echo "Provide username and password!";
					}
				}
			?>
		</div>

	  	<form method="post" action="<?php echo $school_admin_login; ?>">
		    <input class="afield" type="text" name="u" placeholder="Username or email" required="required" />
		    <input class="afield" type="password" name="p" placeholder="Password" required="required" />
		    <input type="hidden" name="subt" value="Password" required="required" />
		    <button type="submit" class="btn btn-primary btn-block btn-large">Login</button>
	    </form>
	    <a class="pwd-rec-link" href="<?php echo $school_admin_login; ?>?action=recover">Forgot password?</a>
	</div>
<?php if(isset($reg_form_included)) { ?>

	<span class="registerl">New to <?php echo $site_name; ?><a id="signUpPage" class="sign-up">Sign Up!</a> </span>

	<script>
		var signUpPage = document.querySelector("#signUpPage");
		signUpPage.addEventListener("click", openSignUp);
		function openSignUp(){
				$.get("<?php echo $register; ?>", function(data){$("body").html(data)});
		}
	</script>
<?php } ?>