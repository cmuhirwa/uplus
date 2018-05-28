<?php
	//class to handle uplus groups tasks
	class group{
		public function details($groupId)
		{
			global $db;
			$query = $db->query("SELECT * FROM uplus.groups WHERE id = \"$groupId\" ") or trigger_error("Can't get group data $db->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}
		public function members($groupId)
		{
			global $db;
			$sql = "SELECT *, COALESCE(memberName, memberPhone) as name  FROM uplus.members WHERE groupId = \"$groupId\" ";
			$members = array();

			$query = $db->query($sql) or trigger_error("Can't get group data $db->error");
			// var_dump($query->num_rows);
			while ($data = $query->fetch_assoc()) {
				$members[] = $data;
			}

			return $members;
			
		}
	}

	//instating new class
	$Group = new group();
?>