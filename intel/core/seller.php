<?php
	class seller extends location{
		function list_all(){
			// return all wholesalers
			global $conn;
			$query = $conn->query("SELECT * FROM wholesalers AS W JOIN users AS U ON W.userId = U.id WHERE archived = 'no' ORDER BY W.createdDate DESC") or trigger_error("Error with SELLER retrieval $conn->error");

			$sellers = array();

			while($data = $query->fetch_assoc()){
				//
				$sellers[] = $data;
			};

			return $sellers;

		}
	}

	$Seller = new seller();
?>