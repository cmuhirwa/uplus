<?php
	class warehouse{
		public function details($warehouseId)
		{
			#order details
			global $conn;
			$query = $conn->query("SELECT * FROM warehouses WHERE id = \"$warehouseId\" LIMIT 1 ") or trigger_error("Error $conn->error");

			$data = $query->fetch_assoc();
			return $data;
		}
		public function list()
		{
			global $conn;
			$query = $conn->query("SELECT * FROM warehouses") or trigger_error("Error $conn->error");
			$data = $query->fetch_all(MYSQLI_ASSOC);
			return $data;
		}
	}

	$Warehouse = new warehouse();
?>