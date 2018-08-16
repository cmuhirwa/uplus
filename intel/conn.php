<?php
	$dbName = 'intelliware';
	$con = $conn = new mysqli('localhost', 'camerwa', '5rReqJs5lthAFUNJ', $dbName);
	if(!$conn){
		die($conn->connect_error);
	}
?>