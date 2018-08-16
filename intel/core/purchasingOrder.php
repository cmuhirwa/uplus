<?php
	class purchasingOrder{
		public function add($status, $supplier, $currency, $warehouse, $budgetHolder, $shippingMode , $shipmentDate, $doneBy)
		{
			# adds a purchasing order
			global $conn;

			//checking purchasing order number and delivery note
			$purchNo = $this->nextOrderNumber();

			$query = $conn->query("INSERT INTO purchasing_orders(orderNumber, status, supplier, warehouse, currency, budgetHolder, shippingMode, shipmentDate, createdBy) VALUES (\"$purchNo\", \"$status\", \"$supplier\", \"$warehouse\", \"$currency\", \"$budgetHolder\", \"$shippingMode\", \"$shipmentDate\", \"$doneBy\") ") or trigger_error("Error with purchasing order issuance $conn->error");

			return $conn->insert_id;
		}
		public function addItem($product, $productUnitPrice, $productUnitMeasure, $productQuantity, $orderId, $doneBy)
		{
			# adds a purchasing order
			global $conn;
			$amount = $productUnitPrice*$productQuantity;
			$query = $conn->query("INSERT INTO purchasing_orders_items(productId, productUnitPrice, productUnitMeasure, productQuantity, orderId, amount, createdBy) VALUES (\"$product\", \"$productUnitPrice\", \"$productUnitMeasure\", \"$productQuantity\", \"$orderId\", \"$amount\", \"$doneBy\") ") or trigger_error("Error with purchasing order issuance $conn->error");
			return $conn->insert_id;
		}

		public function items($orderId)
		{
			#order items
			global $conn;
			$query = $conn->query("SELECT * FROM purchasing_orders_items WHERE orderId = \"$orderId\" AND archived = 'no' ") or trigger_error("Error with purchasing order issuance $conn->error");
			return $query->fetch_all(MYSQLI_ASSOC);
		}

		public function itemsStatics($orderId)
		{
			#order items
			global $conn;
			$query = $conn->query("SELECT SUM(amount) as totalAmount FROM purchasing_orders_items WHERE orderId = \"$orderId\" AND archived = 'no' ") or trigger_error("Error $conn->error");

			$res = $query->fetch_assoc();

			$data = array();

			$data['totalAmount'] = $res['totalAmount'];

			return $data;
		}



		public function details($orderId)
		{
			#order details
			global $conn;
			$query = $conn->query("SELECT * FROM purchasing_orders WHERE id = \"$orderId\" LIMIT 1 ") or trigger_error("Error with CROP details $conn->error");

			$data = $query->fetch_assoc();

			$data['items'] = $this->items($orderId);
			$data['totalAmount'] = $this->itemsStatics($orderId)['totalAmount'];
			return $data;
		}

		public function list()
		{
			# returns list of ORDERS
			global $conn;
			$query = $conn->query("SELECT * FROM purchasing_orders ORDER BY id DESC") or trigger_error("Error with order listing $conn->error");
			return $query->fetch_all(MYSQLI_ASSOC);
		}

		public function nextOrderNumber()
		{
			# return next order number
			global $conn, $dbName;
			$sql = "SELECT AUTO_INCREMENT as n FROM information_schema.TABLES WHERE TABLE_SCHEMA = \"$dbName\" AND TABLE_NAME = \"purchasing_orders\" ";
			$query = $conn->query($sql) or trigger_error("Error with purchasing_orders $conn->error");
			$data = $query->fetch_assoc();
			return $data['n'];
		}

		public function generateOrderNumber($type, $number)
		{
			if( $type == 'P' || $type == 'purchase'){
				$serial = (string)$number;

				for ($n=0; strlen($serial) < 4; $n++) { 
					$serial = "0".$serial;
				}
				return "POR$serial";
			}
		}



		public function listBudgetHolders()
		{
			# Adds crop in system
			global $conn;
			$query = $conn->query(" SELECT * FROM budgetHolders WHERE archived = 'no' ") or trigger_error("Error $conn->error");

			return $query->fetch_all(MYSQLI_ASSOC);
		}

		public function listCurrency($popularity=0)
		{
			# returns list of crop's variety
			global $conn;

			if($popularity>1){
				$popularity = 1;
			}

			$query = $conn->query("SELECT * FROM currency WHERE popularity >= \"$popularity\" ORDER BY popularity DESC ") or trigger_error("Error $conn->error");

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

	$POrder = new purchasingOrder();
?>