<?php

//This is package accepts the scrap requests

//It takes the argument and put it into the database for the bot to scrap the page

include_once "dbcon.php";

if(!empty($_POST['req']) || !empty($_GET['req'])){
	//Checking the DB
	$code = !empty($_GET['req'])?$_GET['req']:$_POST['req'];
	askscrap($code);
}

if(!empty($_POST['get'])){

	//Here the javascript API needs codes to scrap

	$maxnum = !empty($_POST['max'])?$_POST['max']:null;

	$codes = getreq();

	echo json_encode($codes);



	die(); //Avoid some errors



}

if(!empty($_POST['check'])){

	//Checking if a code is scrapped

	global $botconn;

	$code = mysqli_real_escape_string($botconn, $_POST['check']);

	$query = mysqli_query($botconn, "SELECT id, freq FROM scraprequests WHERE code ='$code'");



	$data = mysqli_fetch_assoc($query);

	if($data['freq']>0) $status = array('status'=>'1');

	else $status = array('status'=>'0');



	echo json_encode($status);



	die(); //Avoid some errors



}

function askscrap($code){
	global $botconn;

	$code  = mysqli_real_escape_string($botconn, $code);

	//checking if scrap is requested more than three times
	$query = mysqli_query($botconn, "SELECT * FROM scraprequests WHERE code = '$code' AND freq>0 ") or die(mysqli_error($botconn));

	if(mysqli_num_rows($query)<1){
		//Here we ask scrap
		$query = mysqli_query($botconn, "INSERT INTO scraprequests(code) VALUES ('$code')") or die(mysqli_error($botconn));
		return true;
	}else{
		//Here we have to return the document
		$data = mysqli_fetch_assoc($query);
		$fpath = $data['path'];
		$file = file_get_contents($fpath);
		echo $file;
	}
	
}

function getreq($num=''){

	//Function to return codes which needs scraps



	//Checking maxnumers or setting limit of 20

	$num = !empty($num)?$num:20;



	global $botconn;

	$query = "SELECT * FROM scraprequests WHERE freq<1";

	$query = mysqli_query($botconn, $query) or die(mysqli_error($botconn));

	$n=0; //counter

	$codes = array();



	while ($data = mysqli_fetch_assoc($query)) {

		if($n>$num) break; //Keeping in the record



		$codes = array_merge($codes, array($data['code']));

		$n++;

	}

	return $codes;

}

?>