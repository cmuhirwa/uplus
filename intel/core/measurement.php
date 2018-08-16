<?php
	class measurements{
		public function add($product, $productUnitPrice, $productUnitMeasure, $productQuantity, $priceCurrency, $doneBy)
		{
			# adds a purchasing order
			global $conn;
			$query = $conn->query("INSERT INTO purchasing_orders(productId, productUnitPrice, productUnitMeasure, productQuantity, priceCurrency, createdBy) VALUES (\"$product\", \"$productUnitPrice\", \"$productUnitMeasure\", \"$productQuantity\", \"$priceCurrency\", \"$doneBy\") ") or trigger_error("Error with purchasing order issuance $conn->error");
			return $conn->insert_id;
		}

		public function details($unit)
		{
			# Details of measurement unit
			global $conn;
			$query = $conn->query("SELECT * FROM unit_measure WHERE symbol = \"$unit\" ") or trigger_error("Error with purchasing order issuance $conn->error");
			return $query->fetch_assoc();
		}

		public function changeMainUnit($productId, $mainUnit, $doneBy)
		{
			# makes unit a main unit for $productId
			global $conn;

			//remove the current main unit
			$query = $conn->query("DELETE FROM product_unit_measures WHERE productId = \"$productId\"") or trigger_error("Error $conn->error");

			if($query){
				//now let's check if the measurement is already amng the available measurements
				$q1 = $conn->query("SELECT * FROM  product_unit_measures WHERE productId = \"$productId\" AND measurementUnit = \"$mainUnit\" ") or trigger_error("Error $conn->error");

				if($q1->num_rows){
					//here we can only update
					$q1 = $conn->query("UPDATE product_unit_measures SET main = 1 WHERE productId = \"$productId\"") or trigger_error("Error $conn->error");
					return $conn->insert_id;
				}else{
					$addStatus = $this->addProductUnit($productId, $mainUnit, 1, $doneBy);
					return $addStatus;
				}
			}else{
				return fale;
			}
		}

		public function addProductUnit($productId, $unit, $main=0, $doneBy)
		{
			# adds a purchasing order
			global $conn;
			$query = $conn->query("INSERT INTO product_unit_measures(productId, measurementUnit, main, createdBy) VALUES (\"$productId\", \"$unit\", \"$main\", \"$doneBy\") ") or trigger_error("Error $conn->error");
			return $conn->insert_id;
		}

		public function listUnits()
		{
			# returns list of measurements units
			global $conn;
			$query = $conn->query("SELECT * FROM unit_measure") or trigger_error("Error with units listing $conn->error");
			return $query->fetch_all(MYSQLI_ASSOC);
		}
	}

	$Measurements = new measurements();
?>