<?php

//This checks and corrects school combinations duplicates
include_once "setup.php";
include_once "scripts/examres.php";

$School = WEB::getInstance("school");
$Comb = WEB::getInstance("combination");

$Exams = new examRes();

$query = mysqli_query($conn, "SELECT * FROM schoolcombinations");
$scombs = array();

while ($data = mysqli_fetch_assoc($query)) {
	# code...
	//Checking if there is another instance - scomb with school and combination
	$exiq = mysqli_query($conn, "SELECT * FROM schoolcombinations WHERE combination='$data[combination]' AND school = $data[school]  ORDER BY `schoolcombinations`.`id` DESC") or die(mysqli_error($conn));

	//echo mysqli_num_rows($exiq)." ";
	if(mysqli_num_rows($exiq)>1){
		echo $School->getSchool($data['school'], 'id', 'name')." "."$data[school]";
		echo " ".mysqli_num_rows($exiq)."dupl<br />";
		for($n=1; $exid = mysqli_fetch_assoc($exiq); $n++){
			//echo "n = $n ";
			if($n<mysqli_num_rows($exiq)){
				//Going to delete
				mysqli_query($conn, "DELETE FROM schoolcombinations WHERE id = $exid[id]") or die(mysqli_error($conn));
			}

			$scombs = array_merge($scombs, array($exid['school'] => $exid['combination']));
			//echo "==$exid[combination]";
		}
		//die ;
		echo "<br />";
		
	}

	
	
}
print_r($scombs);