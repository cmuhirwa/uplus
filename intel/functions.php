<?php
	include_once "dashboard/core/Function.inc.php";


	function check_user($userData, $dataType = 'farmerId'){
		///checking the existence of the farmer with $userData of $dataType
		global $conn;
		$sql = "SELECT * FROM farmer WHERE `$dataType` = \"$userData\" ";
		$query = $conn->query($sql) or trigger_error($conn->error);

		return $query->fetch_assoc();
	}

	function login($username, $password, $keep_cookie=true){
		//function to login a user
		//if $keep_cookie is true then we could keep record for future usage
		global $conn;
		$sql = "SELECT id FROM users WHERE username = \"$username\" AND password = \"$password\" LIMIT 1 ";
		$query = $conn->query($sql) or trigger_error("Error loggin in $conn->error");
		$data = $query->fetch_assoc();

		if($keep_cookie){
			if(!session_id()){
				session_start();
			}

			$_SESSION['id'] = $data['id'];
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
		}	
		return $data['id'];
	}

	function get_user($user){
		global $conn;
		//returns details on the user
		$query = $conn->query("SELECT * FROM users WHERE id = \"$user\" LIMIT 1 ") or trigger_error("Can't get the user "+$conn->error);

		$user_data = $query->fetch_assoc();
		return $user_data;
	}

	function logout(){
		if(!session_id()){
			session_start();
		}

		session_destroy();
		header("location:login");
	}

	function current_season(){
		//determines the current current_season
		return 1;
	}

	function is_cooperative_leader($user){
		//checks the coopertive a user leads if he's a leader
		global $conn;
		return true;
		$query = $conn->query("SELECT * FROM cooperative_committee WHERE user = \"$user\" AND committeePosition = \"admin\" LIMIT 1 ") or trigger_error("Error getting cooperative commitee");
		if($query && $query->num_rows>0){
			return $query->fetch_assoc();
		}else return false;
	}

	function add_farmer_cooperative($farmer, $cooperative){
		//adds user to the cooperative
		global $conn;

		if(!$farmer || !$cooperative){
			return false;
		}

		$sql = "INSERT INTO cooperative_members(userId, cooperativeId) VALUES(\"$farmer\", \"$cooperative\")";
		$query = $conn->query($sql) or trigger_error($conn->error);
		if(is_resource($query)){
			$id = $query->insert_id;
		}else{
			$id = null;
		}
		
	}
	function get_fertilizers(){
		//returns all the fertilizers
		global $conn;
		$query = $conn->query("SELECT * FROM fertilizers ORDER BY date DESC") or trigger_error($conn->error);
		$ferts = array();
		while ($data = $query->fetch_assoc()) {
			$ferts[] = $data;
		}
		return $ferts;
	}

	function get_fertilizer($fertilizerId){
		//returns information about fertilizer
		global $conn;
		$query = $conn->query("SELECT * FROM fertilizers WHERE id = \"$fertilizerId\" ") or trigger_error($conn->error);
		return $query->fetch_assoc();
	}

	function get_pesticides(){
		//returns all the pesticides
		global $conn;
		$query = $conn->query("SELECT * FROM pesticides ORDER BY name DESC") or trigger_error($conn->error);
		$pesticides = array();
		while ($data = $query->fetch_assoc()) {
			$pesticides[] = $data;
		}
		return $pesticides;
	}

	function get_pesticide($pesticide_id){
		//returns all info on the pesticide
		global $conn;
		$pesticide_id = (int)($conn->real_escape_string($pesticide_id));
		$query = $conn->query("SELECT * FROM pesticides WHERE id = \"$pesticide_id\" ") or trigger_error($conn->error);
		return $query->fetch_assoc();
	}
?>