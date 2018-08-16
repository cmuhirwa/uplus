<meta charset="utf-8">
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<?php
//Going to see the number of schools in REB's pdf that match the directory of mineduc's name
$conn = mysqli_connect("localhost", "eduke", "admin");
mysqli_query($conn, "SET NAMES utf8");
mysqli_set_charset($conn, "utf8");
//Getting schools in test
$province = "North";

$test = testq("SELECT * FROM scombinations");
while($data = mysqli_fetch_assoc($test)){
	$name = str_ireplace("'", "\'", $data['SCHOOL']);
	//checking the school in eduke
	$edoricaq = edukeq("SELECT * FROM schools WHERE name='$name'");
	$edorica = mysqli_fetch_assoc($edoricaq);
	
	$pref_comb = $data['OPTION_CODE'];
	
	if($name == $edorica['name']){
		//Going to check if there is combination
		// $combq = edukeq("SELECT combinations FROM schools WHERE name='$name'");
		// $combd = mysqli_fetch_assoc($combq);
		// $comb = $combd['combinations'];
		$query = "UPDATE scombinations SET trueID='$edorica[id]' WHERE id = $data[id]";
		echo "$query<br />";
		testq($query);
		//echo $name." =  $edorica[code]<br />";
		
		}
	
	}
?>
<?php
function testq($query){
	global $conn;
	mysqli_select_db($conn, "test");
	$q = mysqli_query($conn, $query) or die(mysqli_error($conn));
	return $q;
	}
function edukeq($query){
	global $conn;
	mysqli_select_db($conn, "edori604_edorica");
	$q = mysqli_query($conn, $query) or die(mysqli_error($conn));
	return $q;
	}
?>