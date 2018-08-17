<?php
	//Remote server connection and authentication
	//Defining hoost and database related constants
	define("_HOST", "localhost");
	define("_DBUNAME", "edoricac_edorica");
	define("_DBPWD", 'Admin@#.');
	define("_DBNAME", "edoricac_bot");

	//Connecting
	$botconn = mysqli_connect(_HOST,  _DBUNAME, _DBPWD) or die("Connection error".mysqli_connect_error());

	mysqli_select_db($botconn, _DBNAME);
?>