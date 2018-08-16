<?php  
$dbName = 'intelliware';
$conn = $db = new mysqli("localhost", "root", "" , $dbName);
	
	if($db->connect_errno){
		die('Sorry we have some problem with the Database!');
	}             
	$str = "";
	//echo md5($str);
?>