<?php
	//class to handle uplus user details and tasks
	class user{
		public function details($userId)
		{
			global $db;
			$query = $db->query("SELECT * FROM uplus.users WHERE id = \"$userId\" ") or trigger_error("Can't get user data $db->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}
	}

	//instating new class
	$User = new user();
?>