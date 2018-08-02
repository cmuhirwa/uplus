<?php  
error_reporting(E_ALL); 
ini_set('display_errors', 1);
	$db = new mysqli("localhost", "clement", "clement123" , "uplus");
	
	if($db->connect_errno){
		die('Sorry we have some problem with the Social Database!');
	}


?>



