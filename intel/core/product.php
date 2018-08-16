<?php
	class product{
		public function add($productName, $doneBy)
		{
			# Adds product in system
			global $conn;
			$query = $conn->query("INSERT INTO products(productName, createdBy) VALUES (\"$productName\", \"$doneBy\") ");

			if($query){
				return $conn->insert_id;
			}else{
				return $conn->error;
			}
		}

		public function editName($productId, $name)
		{
			# Edits product
			global $conn;
			$query = $conn->query("UPDATE products SET productName = \"$name\" WHERE productId = \"$productId\" ") or trigger_error("Error $conn->error");

			return $conn->insert_id;
		}

		public function details($productId)
		{
			# returns product details
			global $conn;
			$query = $conn->query("SELECT * FROM products WHERE productId = \"$productId\" LIMIT 1 ") or trigger_error("Error with product details $conn->error");

			$data = $query->fetch_assoc();
			$data['units'] = $this->measurementUnits($productId);

			return $data;
		}

		public function measurementUnits($productId)
		{
			# returns product measurement units
			global $conn;
			$query = $conn->query("SELECT * FROM product_unit_measures WHERE productId = \"$productId\" LIMIT 1 ") or trigger_error("Error with product unit $conn->error");

			$ret = array('main'=>array(), 'others'=>array());
			while ($data = $query->fetch_assoc()) {
				//checking main measurement unit
				if($data['main']==1){
					$ret['main'] = $data;
				}else{
					$ret['others'][] = $data;
				}
			}

			return $ret;
		}

		public function mainMeasurementUnit($productId)
		{
			# returns product's main measurement unit
			global $conn;
			$query = $conn->query("SELECT * FROM product_unit_measures WHERE productId = \"$productId\" AND main=1 LIMIT 1 ") or trigger_error("Error with product unit $conn->error");

			$data = $query->fetch_assoc();
			if($data){
				$ret = $data['measurementUnit'];
			}else{
				$ret = '';
			}

			return $ret;
		}

		public function list()
		{
			# returns list of products
			global $conn;
			$query = $conn->query("SELECT * FROM products WHERE archived = 'no' ") or trigger_error("Error with product details $conn->error");

			$crops = array();

			while($data = $query->fetch_assoc()){
				$crops[] = $data;
			};

			return $crops;
		}

		public function itemList()
		{
			# returns list of items
			global $conn;
			$query = $conn->query("SELECT * FROM items ") or trigger_error("Error with items listing $conn->error");

			$crops = array();

			while($data = $query->fetch_assoc()){
				$crops[] = $data;
			};

			return $crops;
		}
		public function generateProductCode($code)
		{
			//textual representation of productcode
			$code = (string)$code;

			for ($n=0; strlen($code) < 3; $n++) { 
				$code = "0".$code;
			}
			return "PRD$code";
		}
		public function delete($product, $doneBy)
		{
			# Archive product
			global $conn;
			$query = $conn->query("UPDATE products SET archived = 'yes', archivedBy = \"$doneBy\", archivedDate = NOW() WHERE productId = \"$product\" ");

			if(!$query){
				return $conn->error;
			}else{
				return $conn->insert_id;
			}

			
		}

		public function listProductGroups()
		{
			# returns list of product groups
			global $conn;
			$query = $conn->query("SELECT * FROM order_groups WHERE archived = 'no' ") or trigger_error("Error $conn->error");
			return $query->fetch_all(MYSQLI_ASSOC);
		}

		public function addGroup2Product($productId, $groupId, $doneBy)
		{
			global $conn;

			//check if the group aint already on the good
			$query = $conn->query("SELECT * FROM product_groups WHERE productId = \"$productId\" AND groupId = \"$groupId\" AND archived = 'no' ") or trigger_error($conn->error);
			if(!$query->num_rows){
				$query = $conn->query("INSERT INTO product_groups(productId, groupId, createdBy) VALUES(\"$productId\", \"$groupId\", \"$doneBy\") ") or trigger_error($conn->error);
				return $conn->insert_id;
			}else{
				//here the product is already in group
				return "Product is already member of group";
			}

			
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

	$Product = new product();
?>