<?php
	//class to handle uplus user details and tasks
	class investUser extends user{
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
		public function details($userId)
		{
			global $investDb;
			$query = $investDb->query("SELECT * FROM users WHERE id = \"$userId\" ") or trigger_error("Can't get user data $investDb->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}
	}
	
	//instating new class
	$InvestUser = new investUser();
?>