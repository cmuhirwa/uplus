<?php
	//Remote server connection and authentication for bot

	//Connecting
	$botconn = mysqli_connect("localhost",  "edoricac_edorica", "Admin@#.") or die("Connection error".mysqli_connect_error());

	mysqli_select_db($botconn, "edoricac_bot");
?>