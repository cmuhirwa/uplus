<?php
	class institution{
		function list_all(){
			// return all cooperatives
			global $conn;
			$query = $conn->query("SELECT * FROM cooperatives WHERE archived = 'no' ORDER BY dateAdded DESC") or trigger_error("Error with cooperative retrieval$conn->error");

			$coops = array();

			while($data = $query->fetch_assoc()){
				//getting location
				$location = $this->get_location($data['location']);
				$coops[] = array_merge($data, $location);
			};

			return $coops;

		}
		public function n_members($cooperative)
		{
			# returns number of cooperative members
			global $conn;
			$query = $conn->query("SELECT COUNT(*) as num FROM cooperative_members as c JOIN users AS u ON c.userId = u.id WHERE cooperativeId = \"$cooperative\" LIMIT 1 ") or trigger_error("Error with coop details $conn->error");

			$data = $query->fetch_assoc();

			return $data['num'];
		}

		public function get_members($cooperative)
		{
			# returns cooperative members
			global $conn;
			$query = $conn->query("SELECT * FROM cooperative_members as c JOIN users AS u ON u.id = c.userId WHERE cooperativeId = \"$cooperative\" ORDER BY c.id DESC") or trigger_error("Error with coop details $conn->error");

			$coops = array();

			while($data = $query->fetch_assoc()){
				$coops[] = $data;
			};

			return $coops;
		}

		public function get($institution_id)
		{
			# returns cooperative details
			global $conn;
			$query = $conn->query("SELECT * FROM institutions WHERE id = \"$institution_id\" ") or trigger_error("Can't get institution $conn->error");
			$coop = $query->fetch_assoc();

			// //getting location
			// $coop = array_merge($coop, $this->get_location($coop['location']));

			// //getting position and leaders
			// $coop = array_merge($coop, array('committee'=>$this->committee($coop_id)));

			return $coop;
		}

		function check_user_institution($user_id, $institution_id){
			global $conn;
			//checks the role of user in organization
			$query = $conn->query("SELECT * FROM institution_users WHERE userId = $user_id AND id = 1") or trigger_error($conn->error);
			if($query->num_rows){
				$data = $query->fetch_assoc();
				return $data;
			}else return false;
		}

		public function get_communications($coop_id, $institution){
			global $conn;
			$query = $conn->query("SELECT * FROM cooperative_communication WHERE institutionId = \"$coop_id\" AND cooperativeId = \"$coop_id\" ");
			$comms = array();

			while ($data = $query->fetch_assoc()) {	
				$comms[] = $data;
			}
			return $comms;
		}
	}

	$Institution = new institution();
?>