<?php
	$conn = new mysqli('localhost', 'root', '', 'comwork');
	die("updating is already done check twice");
	$edorica = new mysqli('localhost', 'root', '', 'edoricac_edorica');

	$disquery = $conn->query("SELECT namesector as sector, namedistrict as district FROM sectors JOIN districts ON sectors.districtcode = districts.districtcode ") or die($conn->error);
	while ($data = $disquery->fetch_assoc()) {
		//INSERTING
		$sector = $data['sector'];
		$district = $data['district'];
		echo "$sector - $district<br />";
		$inquery = $edorica->query("INSERT INTO sectors(`name`, `district`) VALUES (\"$sector\", \"$district\") ") or die("jshdsjh ".$edorica->error);;
	}
?>