<?php

include_once "botcon.php";

//This will get a file requested save it and good
if(!empty($_GET['code'])){

	$code = $_GET['code'];
	// $url = "http://www.wda.gov.rw/en/-exam-publication-view?field_index_value=$code";
	$classcode = classcode($code);

	if($classcode == "OLC"){
		$svar = "ol";
		$level="S3";
	}
	else if(is_numeric($code)){
		$svar = "pl";
		$level="P6";
	}
	else $svar = '';

	$url = "http://196.44.242.28/retrieve".$svar."Marks.aspx?id=$code&le=".rawurlencode($level);

	//Getting HTML
	$data = curl($url, "has_js=1");

	


	//Saving the file
	$date = time();
	$fpath = "files/$code $date.html";

	$file = fopen($fpath, "w+") or die("Cant create file");

	//Writing contents
	fwrite($file, $data);

	//Updating check frequency
	updatefreq($code, $fpath);



	if(!empty($data['error'])){

		//Here error occured

		echo json_encode(array('status'=>0, 'error'=>$data['error'], 'path'=>$fpath));

	}else{

		echo json_encode(array('status'=>1, 'path'=>$fpath));

	}
}



//Here we get the requested wda FilesystemIterator

function curl($url, $cookies=''){

	//Function to return contents of exam results page using cURL

		$curl = curl_init();

		// Set some options - we are passing in a useragent too here

		curl_setopt_array($curl, array(

			CURLOPT_RETURNTRANSFER => 1,

			CURLOPT_URL => $url,

			CURLOPT_COOKIE => $cookies,

			CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Mobile Safari/537.36',

			));

		$cookies = tempnam('/tmp','cookie.txt');

		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies); 

		curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies);

		//Send the request & save response to $resp
		$html = $resp = curl_exec($curl);

		//Checking error
		if(!empty(curl_error($curl))){
			return array("error"=> curl_error($curl), 'html'=>$html);

		}
		return $html;
}
function classcode($code){
	if(strpos($code, "OLC")) return "OLC";
	else if(is_numeric($code)) return "PRI";
	else return "S6";
}
function printraw($data){
	?>
	<textarea style="width:60%; height:400px; border:0; outline:0">

		<?php echo $data; ?>
	</textarea>
	<?php
}

function updatefreq($code, $path){
	//This function will be used after getting page and will set the scrap frequest to incremented value

	global $botconn;
	$query = mysqli_query($botconn, "SELECT * FROM scraprequests WHERE code= '$code' ORDER BY code DESC LIMIT 1");

	if(mysqli_num_rows($query)){
		//Here the code exists we can update the frequency
		$data = mysqli_fetch_assoc($query);

		$freq = $data['freq'];

		if(!empty($freq) && $freq!=0){

			$freq = $freq+1;

		}else

			$freq = 1;

		mysqli_query($botconn, "UPDATE scraprequests SET freq='$freq', path=\"$path\" WHERE id = $data[id]") or die(mysqli_error($botconn));

		return true;



	}

}

?>