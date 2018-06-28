<?php
	//class to handle uplus forum details and tasks
	class forum{
		public function usersWithForum()
		{
			//returns the USERS with forums and number of forums
			global $investDb;
			$query = $investDb->query("SELECT DISTINCT(userCode), COUNT(forumCode) as count FROM `forumuser` GROUP BY userCode") or trigger_error("Can't get user data $investDb->error");

			if($query->num_rows){
				return $query->fetch_all(MYSQLI_ASSOC);
			}else return false;
			
		}		
	}
	
	//instating new class
	$Forum = new forum();
?>