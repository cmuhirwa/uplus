<?php
	//class to handle uplus user details and tasks ..
	class user{
		public function details($userId)
		{
			global $db;
			$query = $db->query("SELECT * FROM uplus.users WHERE id = \"$userId\" ") or trigger_error("Can't get user data $db->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}

		public function investLogin($userName, $password)
		{
			global $investDb;
			$userName = $investDb->real_escape_string($userName);
			$password = $investDb->real_escape_string($password);
			$query = $investDb->query("SELECT * FROM users WHERE loginId = \"$userName\" AND pwd = \"$password\" LIMIT 1 ") or trigger_error("Can't get user data $investDb->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}

		function listAll()
		{
			global $db;
			$result = $db->query("SELECT * FROM users WHERE archived = 'no' ORDER BY createdDate DESC") or trigger_error($db->error);
			return $result->fetch_all(MYSQLI_ASSOC);
		}
	}
	
	//instating new class
	$User = new user();
?>