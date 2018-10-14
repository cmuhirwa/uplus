<?php
	class provider{
		public function __construct(){
			global $db;
			$this->conn = $db;
		}
		public function services()
		{
			//list of available services
			$query = $this->conn->query("SELECT * FROM `servicecategories`") or trigger_error($this->conn->error);
			return $query->fetch_all(MYSQLI_ASSOC);
		}

		public function list()
		{
			//list of available providers
			$query = $this->conn->query("SELECT * FROM `serviceproviders`") or trigger_error($this->conn->error);
			return $query->fetch_all(MYSQLI_ASSOC);
		}
	}
	$Provider =  new provider();
?>