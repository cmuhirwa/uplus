<?php
include_once "scripts/user.php";
$User = new user();
$userID = $User->login_status();
if($userID)
{
	//Logged in!
}
else{
	header("location:$login");
}

$mySchool = WEB::getInstance("school");
$myPage = new page(); 
$getvars = $myPage->get();
// print_r($getvars);


if(empty($getvars)){

?>



<div class="recont">

	<h1>Your School Requests</h1>

    <div class="">

    	<?php

			//Getting all requests associated with the ID

			$req = mysqli_query($conn, "SELECT * FROM requests WHERE user='$userID' ") or die(mysqli_error($conn));

			if(mysqli_num_rows($req)>0){

				//Going to loop through all requests and display info

				while($reqdata = mysqli_fetch_assoc($req)){

					$schoolID = $reqdata['school'];

					$schoolname = $mySchool->getSchool($schoolID, 'id', 'name');

					$regclass = $reqdata['class'];

					

					?>

                    <div class="apin">

                    	<p class="reqhead"><?php echo $schoolname." ($regclass)"; ?></p>

                        <div class="approgre">

                        	<?php

								//Going to show progress of application

								$progq = mysqli_query($conn, "SELECT * FROM reg_progress as progs JOIN requests ON progs.requestID=requests.id WHERE requests.user=$userID AND requests.school=$schoolID ") or die(mysqli_error($conn));

								

								while($progdata = mysqli_fetch_assoc($progq)){

									$progname = ucwords(str_ireplace("_", " ", $progdata['name']));

									$time = $progdata['time'];

									echo $time." - $progname<br/>";

									}

							?>

                        </div>

                        <div class="reqctacont">

                        	<a class="reqcta" href="<?php echo $school_requests."?act=view&id=$reqdata[id]"; ?>">Provided info</a>

                        	<a class="reqcta" href="">Cancel</a>

                        </div>

                    </div>

                    <?php

					}

				}

			else{

				//User has no request already

				}

		?>

    </div>

</div>

<?php }else if(!empty($getvars['act'])){

		if(!empty($getvars['id'])){

			

			$myUser = new User();

			$userID = $myUser->id();

			function displayreq($userID, $reqID){

				global $conn;

				//Checking request

				$reqexiq = mysqli_query($conn, "SELECT * FROM requests WHERE id=$reqID AND user=$userID") or die(mysqli_error($conn));

				}

			displayreq($userID, $getvars['id']);

			}

	}

?>

<?php

?>