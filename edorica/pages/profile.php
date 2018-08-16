<?php
include_once "scripts/user.php";
$User = new user();

//Checking if the user is logged in
$userID = $User->login_status();

if(!$userID){

	?>

    <div class="modbox">
        You are not currently logged in.
        To access your profile page please login <a href="<?php echo $login ?>">here</a>
        <p>You will be redirected to login page after 5 seconds</p>
        <?php header( "refresh:5; url=$login" ); ?>
    </div>

    <?php

	die();

	}

?>

<?php
//Fetching Data About our user

$userdata = $User->info($userID);
$username = $userdata['lname'];
?>
<div class="profile container" id="p2">

	<div class="profile_menu">

    <ul>

    <li>Profile</li>

    <li><a href="<?php echo $school_requests; ?>">Requests</a></li>

    <li>Messages</li>

    <li>Information</li>

    </ul>

    </div>

   <h1>School Requests</h1>

   Dear <?php echo ucwords($username); ?>,<br />

<?php ?>

</div>

