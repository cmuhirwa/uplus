<?php
function validateInput($task, $var){

	/*

	Here task 1, will be checking if empty

		task 2 will be verifying numbers in a variable

	*/

		if($task=="empty" || $task == 1){

		if($var == '' || empty($var)){ return true;}

		else return false;		

		}

		

		//waiting a bit

		if($task="num"||empty($var)){}

}


function str2arr($str){
	//Converting string to array
	if(!is_string($str)) return false;
	$arr = array();
	for($n=0; $n<strlen($str); $n++){
		$arr[$n] = $str[$n];
	}
	return $arr;
}


function validateInputReg($array){

	//checking if firstname was sent

	if(isset($array['fname'])){

		$fname= mysqli_real_escape_string($array['fname']);

		$validation_fname=validateInput("empty", $fname)?"First Name Required":"";

		//if($validation_fname!=""){ echo "Validation set";}

		echo $validation_fname;

		}

	}



function getschool($conn, $cat, $length){

	mysqli_query($conn, "SELECT * FROM schools");

	$query = mysqli_query($conn, "SELECT * FROM schools WHERE cat IN (SELECT id FROM cat WHERE $cat=1) LIMIT $length");

	

	if(!$query){

		die("ERROR selecting schools from the database:<br />".mysqli_error($conn));

		}

	//Number of schools got from the database

	$schools_num = mysqli_num_rows($query);

	$n = 0;

	

	//Fetching all the schools from the database

	while($n < $schools_num){

		$n++;	

		$school = mysqli_fetch_assoc($query);

		$schools[] = $school;

		}

	$schools[] = array('num'=>$schools_num, 'cat'=>$cat);

	return $schools;

	}



function fetch_result($result){

		//Checking if $result is mysqli_result

		if(get_class($result) == 'mysqli_result'){

			$num = mysqli_num_rows($result);

			$n = 0;

			while($n < $num){

				$tmp = mysqli_fetch_assoc($result);

				$ret[] = $tmp;

				$n++;

				}

			}

		return $ret;

		};

#Funtion to return Secondary_school's and training center's Combination

// function secondary_combinations($conn, $combinationID){

// 		$query = mysqli_query($conn, "SELECT * FROM combinations WHERE id = '$combinationID'") or die("Error: ".mysqli_error($conn)); //Selecting combination from the database

		

// 		$scd = mysqli_fetch_assoc($query);

// 		unset($scd['id'], $scd['school']);



// 		$scd = array_diff($scd, array(""));



// 		$combinationsd = array_keys($scd);



// 		$cnum = count($combinationsd);	//number of combinations found

// 		$scnum = $cnum;



// 		for($n=0; $n<$cnum; $n++){

// 			//Here we check values of combination, it must not b e null n not zero. It contains classes Supported

// 			if($scd[$combinationsd[$n]]!='' && $scd[$combinationsd[$n]]!=0){

// 				$cname = $combinationsd[$n];



// 				$nameit = array('technical', 'arts', 'humanity');

				

// 				if(combinationType($cname)=='technical' || combinationType($cname)=='arts' || combinationType($cname)=='humanity' || $cname=='PRI' || $cname=='NUR'){

// 					$cname=combinationFullname($cname);	

// 				}

// 				elseif ($cname=='OLC') {

// 					$cname = "O' Level";

// 				}



// 				//Outputing

// 				echo $cname;

// 				if($n!=$scnum-1){

// 					echo ", ";

// 				};

// 			}

// 			else{

// 			}

// 		}
// }

function combinationType($name){

	global $conn;

	$ctq = mysqli_query($conn, "SELECT type FROM combinations_def WHERE combName='$name'");

	$ctd = mysqli_fetch_assoc($ctq);

	return $ctd['type'];

}



function combinationFullname($name){

	global $conn;

	$nq = mysqli_query($conn, "SELECT des FROM combinations_def WHERE combName='$name'");

	$nd = mysqli_fetch_assoc($nq);

	return $nd['des'];

}





function school_facilities($facility_id, $school){

	$school_facility_id=$school['facilities'];

	$school_options_query="SELECT * FROM facilities WHERE id='$facility_id'";

	$school_facilities_query_result=mysqli_query($GLOBALS['conn'], $school_facilities_query);

	

	if(!$school_facilities_query_result){ echo "".mysqli_error($GLOBALS['conn']); }

	$school_facilities=mysqli_fetch_assoc($school_facilities_query_result);

	

	$school_available_facilities=array('sports', 'labs', 'environment');

	

	#Checking number of available facilities, here i need built in function for checking array size

	for($int=0; isset($school_available_facilities[$int]); $int++);

	

	#Checking if school has a specific facility

	for($loop=0; $loop<$int; $school_options["$school_available_facilities[$loop]"]){

		$curr = $school_available_facilities[$loop];

		if($school_facilities["$school_available_facilities[$loop]"]==1){

			echo $curr." ";

			}

	$loop++;

		}

}



function userexists($conn, $email){

	$q = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

	if(!$q){

		die(mysqli_error($conn));

		}

	else{

		$data = mysqli_fetch_assoc($q);

		if(count($data)==1) return 1;

		else if(count($data)<1) return 0;

		else return "Error";

		}

	}

	

function comfirmed($conn, $email){

	$q = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'") or die(mysqli_error($conn));

	$q = mysqli_fetch_assoc($q);

	$userid = $q['id'];

	

	$rq = mysqli_query($conn, "SELECT id, status FROM `crequests` WHERE type='emailconf' and id=$userid") or die(mysqli_error($conn));

	$rq = mysqli_fetch_assoc($rq);

	$cstatus = $rq['status'];

	if($cstatus!=''){

		return $status;

		}

	else{

		return 1;

	}

	

	

	}
?>