<?php  

	$db = new mysqli("localhost", "clement", "clement123" , "uplus");
	
	if($db->connect_errno){
		die('Sorry we have some problem with the Database!');
	}
	include_once '../../db.php';         
?>