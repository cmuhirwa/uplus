<?php

$module = WEB::getInstance('module');
include_once "scripts/user.php";
$User = new user();

#These forms will be only rendered when user is not logged in

if(!$User->login_status()){

	$module->loadModule("qlogin_regiser");

	}

 ?>

 

 <?php

 #if the user has logged in, we will show this, for visiting his profile and quick actions

 if($User->login_status() ){

	 ?>

 <div class="user-shortcut">

     <ul class="access-links">

         <li>

            <a href="<?php echo $profile; ?>" id="profile-link"><i class="fa fa-user"></i> Profile</a>

        </li>

        <li>

            <a href="#"  id="notification-link"><i class="fa fa-bell"></i> Notifications</a>

        </li>

        <li>

            <a href="<?php echo $logout; ?>"  id="logout-link">Logout</a>

        </li>     

     </ul>

 </div>

<?php

 }

 ?>

</div>