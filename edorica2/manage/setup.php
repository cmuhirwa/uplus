<?php
	//Getting database data
	include_once "../scripts/dbcons.php";
	$conn = new mysqli(_HOST, _DBUNAME, _DBPWD, _DBNAME) or die("Cant connect ".$conn->error);

	function check_login(){
		global $conn;
		//Verifying if user is loggend in
		$user_id = $_SESSION['staff_id']??"";

		//Querying
		$user_query = $conn->query("SELECT * FROM staff WHERE id = \"$user_id\" LIMIT 1") or die("error getting user; ".$conn->error);

		if($user_query->num_rows >0 ){
			$user_data = $user_query->fetch_assoc();
			return $user_data;
		}else{
			header("location: ../sadmin_login");
		}
	}
	
?>