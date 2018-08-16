<?php
	class client{
		public function add($name, $phone, $email, $workplace, $tin, $userId, $doneBy)
		{
			# adds a purchasing order
			global $conn;
			$query = $conn->query("INSERT INTO clients(name, phone, email, userId, TIN, location, createdBy) VALUES (\"$name\", \"$phone\", \"$email\", \"$workplace\", \"$userId\", \"$tin\", \"$doneBy\") ") or trigger_error("Error with suuplier addition $conn->error");
			return $conn->insert_id;
		}

		public function details($cropid)
		{
			# returns number of cooperative members
			global $conn;
			$query = $conn->query("SELECT * FROM crops WHERE cropId = \"$cropid\" LIMIT 1 ") or trigger_error("Error with CROP details $conn->error");

			$data = $query->fetch_assoc();

			return $data;
		}

		public function list()
		{
			# returns list of suppliers
			global $conn;
			$query = $conn->query("SELECT * FROM clients ORDER BY id DESC") or trigger_error("Error with clients listing $conn->error");
			return $query->fetch_all(MYSQLI_ASSOC);
		}

		public function add_variety($cropId, $variety)
		{
			# Adds crop in system
			global $conn;
			$query = $conn->query("INSERT INTO crop_varieties(cropId, variety) VALUES (\"$cropId\", \"$variety\") ") or trigger_error("Error with CROP variety addition $conn->error");

			return $conn->insert_id;
		}

		public function varieties($crop='')
		{
			# returns list of crop's variety
			global $conn;
			$query = $conn->query("SELECT * FROM crop_varieties WHERE cropId = \"$crop\" ") or trigger_error("Error with CROP varieties $conn->error");

			$varieties = array();

			while($data = $query->fetch_assoc()){
				$varieties[] = $data;
			};

			return $varieties;
		}

		public function add_grade($cropId, $grade)
		{
			# Adds grade in system
			global $conn;
			$query = $conn->query("INSERT INTO crop_grades(cropId, grade) VALUES (\"$cropId\", \"$grade\") ") or trigger_error("Error with CROP grade addition $conn->error");

			return $conn->insert_id;
		}

		public function grades($crop='')
		{
			# returns list of crop's variety
			global $conn;
			$query = $conn->query("SELECT * FROM crop_grades WHERE cropId = \"$crop\" ") or trigger_error("Error with CROP grades $conn->error");

			$varieties = array();

			while($data = $query->fetch_assoc()){
				$varieties[] = $data;
			};

			return $varieties;
		}
	}

	$Client = new client();
?>