<?php  
error_reporting(E_ALL); 
ini_set('display_errors', 1);
define("HOSTNAME", $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/");

$db = new mysqli("localhost", "clement", "clement123" , "uplus");

if($db->connect_errno){
	die('Sorry we have some problem with the Social Database!');
}

$conn = $investdb = $investDb= new mysqli("localhost", "clement", "clement123" , "investments");

if($investdb->connect_errno){
	die('Sorry we have some problem with the Investment Database!');
}

$outCon  = new mysqli("localhost", "clement", "clement123" , "rtgs");
if($outCon->connect_errno){
	die('Sorry we have some problem with the Money Database!');
}

$eventDb = new mysqli("localhost", "clement", "clement123" , "events");

if($eventDb->connect_errno){
	die('Uplus is currently not available in your country!');
}
?>



