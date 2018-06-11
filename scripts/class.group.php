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
		function memberContribution($userId, $groupId){
			//contribution of a user in a group
			global $db;

			$sqlContribution = $db->query("SELECT  
				IFNULL(
						(
							SELECT sum(t.amount) 
							FROM rtgs.grouptransactions t 
							WHERE ((t.status = 'Successfull' AND t.operation = 'DEBIT') AND (t.memberId = '$userId' AND t.groupId = '$groupId'))
						),0
						) AS memberContribution 
					FROM uplus.members m")	or die(mysql_error($sqlContribution));

			if($sqlContribution->num_rows){
				$data = $sqlContribution->fetch_assoc();
				return $data['memberContribution'];
			}else return 0;
		}
		public function csd($groupId)
	{
		global $investDb;
			//returns the csd of the group
			$query = $investDb->query("SELECT * FROM clients WHERE groupCode = \"$groupId\" ") or trigger_error($investDb->error);
			if($query->num_rows){
				$data = $query->fetch_assoc();
				$csd = $data['csdAccount'];

				if($data['status'] != 'declined'){
					return $csd;
				}else return false;

				
			}else{
				return false;
			}
		}
	}

	

	//instating new class
	$Group = new group();
?>