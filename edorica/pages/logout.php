<?php 
	include_once "scripts/user.php";
	$user = new user();

	$user->sessionlogout();

	header("location:$login");

	die();

    ?>