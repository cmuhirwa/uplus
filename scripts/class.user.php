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

		function listAll()
		{
			global $db;
			$result = $db->query("SELECT * FROM users WHERE archived = 'no' ORDER BY createdDate DESC") or trigger_error($db->error);

			// $ret = array();
			// while ($data = $result->fetch_assoc()) {
			// 	$ret[] = $data;
			// }
			return $result->fetch_all(MYSQLI_ASSOC);
		}
	}
	
	//instating new class
	$User = new user();
?>