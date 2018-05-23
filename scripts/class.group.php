<?php
	//class to handle uplus groups tasks
	class group{
		public function details($groupId)
		{
			global $db;
			$query = $db->query("SELECT * FROM groups WHERE id = \"$groupId\" ") or trigger_error("Can't get group data $db->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}
	}

	//instating new class
	$Group = new group();
?>