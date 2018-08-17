<?php
include_once "scrap/botcon.php";
class Examres{
	//Class to handle exam results
	function level($code){
		$level = false;

		if(strpos($code, "OLC")){
			$level =  "Senior 3";
		}else if(is_numeric($code) || strpos($code, "PRI")){
			$level =  "Primary 6";
		}else $level = "S6";

		return $level;

	} 
	function validateclass($classcode){
		//Function to validate if the $classcode submitted is correct or not.
		//Uhm getting the class's combination
		$comb = $this->code2comb($classcode."001");

		//Here we validate the combination		
		$myComb = WEB::getInstance("combination");

		if($myComb->is_comb($comb)){
			//Combination validated successfully
			//Calling validate code 2 do the job
			$val  = $this->validatecode(strtoupper($classcode."001"));
			if($val) return true;
			else return false;		
		}else{
			//Combination is incorrect
			return false;
		}
	}
	function nclass($code, $type){
		//Function to count the number of student's marks kept in the database
		global $conn;
		$Examres = WEB::getInstance("Examres");

		if($type = 'student'){
			//Here we change the code to class code
			$class = $Examres->code2class($code);
			}else $class = $code; 


		//Getting all marks in the database
		$query = mysqli_query($conn, "SELECT COUNT(*) as sum FROM `6marks` WHERE code LIKE '$class%'");
		$data = mysqli_fetch_assoc($query);

		$sum = $data['sum'];

		return $sum;

	}
	function getclassmarks($class){
		//Function gets marks for the class
		//WE first validate the class and get the num of the class

		//Getting basket instance for keeping data
		$basket = WEB::getInstance('basket');

		$Examres = WEB::getInstance('Examres');


		if($this->validateclass($class)){
			//Class is syntatically correct

			//Let's first check if this class was already put in basket
			$bmarks = $basket->get($class."marks");
			if(is_array($bmarks)){
				return $bmarks; 
			}
			unset($bmarks);
			//Getting students in class
			$num = $this->classnum($class);

			//Getting the marks
			$marks = $errors =array();
			for($n=1; $n<=$num; $n++){
				$student_code = ($n<10)? "00".$n :($n<100?"0".$n:$n); //Making a compact student code
				$student_code = $class.$student_code;
				
				$student_data = $Examres->getMarks($student_code);

				//checking if there was successful retrieval of marks
				if($student_data){
					//Putting marks in array
					$marks = array_merge($marks, array($student_code=>$student_data));
				}				
			}
			//Keeping marks in the basket
			$basket->set($class."marks", $marks);
			return $marks;
		}else{
			return false;
		}
	}
	function results($code){
		//Function for getting marks for an api; so it does everything
		$code = $this->sanitize($code);

	}
	function sanitize($code){
		//Removes some words and symbols which are uncessesarry
		$code = preg_replace('/[^A-Za-z0-9]/', "", $code);

		//removing SMS stuffs
		$code = str_ireplace("S3", '', str_ireplace("P6", '', str_ireplace("S6", '', $code)));
		return $code;
	}
	function code2class($code){
		//Function to change code to class
		
		$myComb = WEB::getInstance("combination");
		if($this->level($code) == "Primary 6" || $this->level($code) == "P6"){
			$nstop = 8;
		}else{
			if( array_keys($myComb->combAuth($this->code2comb($code)), "REB"))
				$nstop = 10;
			else{
				$nstop = 12;
			}
		}

		$class='';
		for($n=0; $n<$nstop; $n++)
			$class .= $code[$n];
		return $class;

	}
	function validatecode($reg_code){
		global $conn;
		WEB::getInstance("combination");
		$allCombs = combination::allCombs();

		$reg_code = strtoupper($reg_code);

		if(preg_match("~^(P6)[0-9]{11}$~", $reg_code) || preg_match("~^(P6)\d{7}(OLC)\d{3}$~", $reg_code) || preg_match("~^[0-9]{11}$~", $reg_code) || preg_match("~^(\d{7}|\d{9})(\w{3})\d{3}$~", $reg_code)){
			
			//Checking if combination belongs among our combinations
			$comb = $this->code2comb($reg_code);

			if(array_keys($allCombs, $comb)){
				//Checking if our combination exiists among allcombinations in our database

				//Here the combination exists
				return true;
			}else if(is_numeric($reg_code)){
				//Primary User
				return true;
			}else{
				//Wrong code
				//echo "We think your combination is incorrect - $comb<br />";

				//Logging the error in the database
				$query = mysqli_query($conn, 
					"INSERT INTO exam_results_error_log(code, reason, user) VALUES(\"$reg_code\", \"Code is Invalid, becaue combination cant be found and is not numeric to say it's for primary students\", 'visitor')") or die(mysqli_error($conn));
				return false;
			}
		}else{
			//Here code is invalid by regularr check		
			$query = mysqli_query($conn, 
				"INSERT INTO exam_results_error_log(code, reason, user) VALUES(\"$reg_code\", \"Code is Invalid, failed to pass regular expression check\", 'visitor')") or die(mysqli_error($conn));
			return false;
		}
	}
	function logview($code){
		global $conn;
		//This function logs the views of national exams results
		$ip = !empty($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";
		$useragent = mysqli_real_escape_string($conn, !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"");
		$query = mysqli_query($conn, "INSERT INTO resview(code, ip, uagent) VALUES('$code', '$ip', \"$useragent\")") or die(mysqli_error($conn));

		//Checking if the scrap was requested
		$classcode = $this->code2class($code);
		$query = mysqli_query($conn, "SELECT * FROM scrap_request WHERE code LIKE \"%$classcode%\"") or die(mysqli_error($conn));
		if(mysqli_num_rows($query)<1)
			$this->askscrap($classcode);

		}
	function resin($code, $username, $type='check'){
		//Function to store the user who entered result
		global $conn;
		$ip = $_SERVER['REMOTE_ADDR'];
		$uagent = mysqli_real_escape_string($conn, !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"");
		
		$query = mysqli_query($conn, "INSERT INTO resinputs(code, username, type, ip, uagent) 
		VALUES('$code', '$username', '$type', '$ip', '$uagent')") or die(mysqli_error($conn));
		}

	function type($code){
		//Function to know the type of the code, whether It's for REB, WDA OR TTC
		//Uhm
		//Let's first get the combination of this code
		$combination  = $this->code2comb($code);

		if(!$combination){
			echo "Invalid combination - $combination";
			return false;
		}

		//including school class to use combination class
		
		$mycomb =  WEB::getInstance("combination");

		//Getting the type of the combination
		$combType = $mycomb->combAuth($combination);
		return $combType;

	}
	function classes($scode){
		global $conn;
		$Combination = WEB::getInstance('combination');
		//Function which find the classes of the school with $scode of which we have marks of.

		$classes = array();

		//Checking from 6marks for reb
		$reb_marks = $conn->query("SELECT COUNT(SUBSTR(code, 8, 3)) num, SUBSTR(code, 8, 3) as class FROM 6marks WHERE code LIKE \"$scode%\" GROUP BY class");
		while ($data = $reb_marks->fetch_assoc()) {
			//checking if this is correct three letter combination

			if(!strrpos("test".$data['class'], "16")){
				$classes[$data['class']] = array('num'=>$data['num'], 'level'=>'S6', 'classcode'=>$scode."16".$data['class']);
			}
			else{
				//Here we detected a WDA combaination
				$wda_marks = $conn->query("SELECT COUNT(SUBSTR(code, 10, 3)) num, SUBSTR(code, 10, 3) as class FROM 6marks WHERE code LIKE \"".$scode."16%\" GROUP BY class");
				while ($wdata = $wda_marks->fetch_assoc()) {
					$classes[$wdata['class']] = array('num'=>$wdata['num'], 'level'=>'S6', 'classcode'=>$scode."16".$wdata['class']);;
				}
			}
		}

		//Checking in primary
		$pri_marks = $conn->query("SELECT COUNT(code) as num FROM pres WHERE code LIKE \"$scode%\" LIMIT 1");
		if($pri_marks->num_rows == 1){
			$pri_data = $pri_marks->fetch_assoc();
			if($pri_data['num'])
				$classes['PRI'] = array('level'=>"P6", 'num'=>$pri_data['num'], 'classcode'=>$scode."PRI");
		}

		//Checking in O'level
		$o_marks = $conn->query("SELECT COUNT(code) as num FROM ores WHERE code LIKE \"$scode%\" LIMIT 1");
		if($o_marks->num_rows == 1 ){
			$o_data = $o_marks->fetch_assoc();
			if($o_data['num'])
				$classes['OLC'] = array('level'=>"S3", 'num'=>$o_data['num'], 'classcode'=>$scode."OLC");
		}

		return($classes);
	}
	function classperformance($classcode){
		global $conn;
		//function for summarizing class's performance

		//validating class
		if($this->validateclass($classcode)){
			//getting table
			$marks = $this->getclassmarks($classcode);
			var_dump($marks);

		}else{
			echo "Invalid class";
		}
	}
	function code2scode($code){
		//This will convert student code 2 school code
		$class = $this->code2class($code);
		$comb = $this->code2comb($code);

		$Comb = WEB::getInstance("combination");
		$scode = str_ireplace($comb, '', $class);

		$combauth = $Comb->combAuth($comb);

		//Checking if combination is from WDA or TTC
		if(array_keys($combauth, 'WDA') || array_keys($combauth, 'TTC')){
			$excode = $Comb->excode($comb);

			//Removing combination's exam code after school's code
			$scode = rtrim($scode, $excode);
		}

		return $scode;
	}
	function code2comb($code){
		$comb = false; //Initialization
		//Getting the combination name out of index code
		if(is_numeric($code)) $comb = "PRI";

		//Checking if string has 7, 8, 9 offsets which are considered to be combination code in the code
		else if(isset($code[7], $code[8], $code[9]) && !is_numeric($code[7])){
			$comb = $code[7].$code[8].$code[9];
		}

		if(strlen($code)==15){
			//Here we could be looking for TTC or WDA
			$comb = $code[9].$code[10].$code[11];
		}
		return $comb;
	}

	function getrespage($code){
		global $conn, $edorica;
		//Getting the level of the user with $code
		$level = $this->level($code);

		//Checking authotity of code		
		$myComb = WEB::getInstance("combination");
		$comb = $this->code2comb($code);
		$auth = $myComb->combAuth($comb);


		if(empty($auth)) return false;
		else $auth = $auth[0];

		//Checking if our spider has downloaded the page
		$efile = $edorica->getFile("scrap/files/$code.php", $level);
		if(file_exists($efile)){
			$html = file_get_contents($efile);
			return $html;
		}

		if($auth == "REB" || $auth == "TTC"){
			$classcode=$this->classcode($code);
			//$svar = $classcode=="OLC"?'ol':'pl';

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
			//$url = "http://edorica-o.com/api/reb.php";

			
			//Getting exam rresults page's content

			$html = $this->curl($url);
			return $html;
		}else if($auth == "WDA"){

			//We reserve job for external bot
			include_once "scripts/wdafiles.php";

			$data = checkpage($code);

			if($data) return $data;
			else{
	            $url = "http://eduke.ml/wda/scrapreq.php?req=$code";
	            $url = "http://www.wda.gov.rw/en/-exam-publication-view?field_index_value=$code";
				$data = $this->curl($url);	
				return $data;
			}


			// //Going to fetch HTML from WDA
			// $url = "http://www.wda.gov.rw/en/-exam-publication-view?field_index_value=".$code;

			// //$url = "edorica-o.com/api/wdat.php";
			// $html = $this->curl($url);

			// include_once "scripts/simple_html_dom.php";
			// $html = str_get_html($html);
			// $rcookie ='has_js=1;';
			// if($html->find("script")){
			// 	$js = ($html->find("script", 0)->innertext);
			// 	$cookie = str_ireplace("document.cookie = '", '', $js);

			// 	$rcookie ='has_js=1;';
			// 	for($n=0; $n<strpos($cookie, "'"); $n++){
			// 		$rcookie .= $cookie[$n];
			// 	}
			// }
			// $html = $this->curl($url, $rcookie);

			
			// return $html;
		}else if($auth == "TTC"){
			$url = "http://www.ce.ur.ac.rw/ttcs/exam-results";
				
			$fields = array("search"=>$code, "rq"=>null);
			$html = $this->postcurl($url, $fields);
			return $html;
		}else{
			//Logging access error
			$query = mysqli_query($conn, "INSERT INTO exam_results_error_log(code, user, reason) VALUES(\"$code\", 'visitor', 'Data not yet there')");
			echo "Data coming soon! See ya soon :)";
		}
		
	}
	function postcurl($url, $fields, $cookies=''){

		//open connection
		$curl = curl_init();

		$fields_req='';
		foreach ($fields as $key => $value) {
			$fields_req .= "$key=$value&";
		}
		$fields_req = trim($fields_req);

		//set the url, number of POST vars, POST data
		curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_POST => count($fields),
				CURLOPT_POSTFIELDS => $fields_req,
				CURLOPT_COOKIE => $cookies,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Mobile Safari/537.36',
				));

		//execute post
		$result = curl_exec($curl);
		return $result;

	}
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
			
			//Send the request & save response to $resp
			$html = $resp = curl_exec($curl);

			return $html;

	}
	function classcode($code){
		if(strpos($code, "OLC")) return "OLC";
		else if(is_numeric($code) || strpos($code, "PRI")) return "PRI";
		else return "S6";
	}
	function resultsfields($code){
		//Here we've to first know the code then get its fields - for display
		global $conn;

		//Fields are three
		//ID, marks meta, subjects marks

		$fields = array("id"=>array('code'=>'Code', 'name'=>'Name', 'gender'=>'Gender'));

		$classtype = $this->classcode($code);
		if($classtype == "PRI"){
			//Here we want primary's exams results data and return their rows
			$fields = array_merge($fields, array("meta"=>array('aggregate'=>'Aggregate', 'division'=>'Division')));

			$marksfields = array('maths'=>"Mathematics", 'science'=>"Science", 'english'=> "English", 'kinyarwanda'=>"Kinyarwanda", 'social_studies'=>"Social Studies");
			$fields = array_merge($fields, array("marks"=>$marksfields));
		}else if($classtype == "OLC"){
			//Here we want o'level's exams results data and return their rows
			$fields = array_merge($fields, array("meta"=>array('aggregate'=>'Aggregate', 'division'=>'Division')));

			$marksfields = array('maths'=>"Mathematics", 'physics'=>"Physics", 'english'=> "English", 'kinyarwanda'=>"Kinyarwanda", 'history'=>"History", 'geography'=>'Geography', 'chemistry'=>'Chemistry', 'biology'=>'Biology', 'entrepreneurship'=>'Entrepreneurship');

			$fields = array_merge($fields, array("marks"=>$marksfields));
		}else if($classtype == "S6"){
			//Here we want S6's exams results data and return their rows
			$subjects = array();
			$query = mysqli_query($conn, "SELECT subject FROM marks WHERE student =\"$code\"") or die(mysqli_error($conn));

			for($n=0; ($data = mysqli_fetch_assoc($query)); $n++){
				$subjects = array_merge($subjects, array($this->subabbreviate($data['subject'])=>$data['subject']));
			}
			$fields = array_merge($fields, array("meta"=>array('aggregate'=>'Aggregate', 'mention'=>'Mention')));
			$fields = array_merge($fields, array("marks"=>$subjects));		
		}
		return $fields;

	}
	function checkclass($code, $num=200){
		//Function to check if the whole class is in our DB
		//Num is the number of students in class

		$class_stat = array();
		for($n=1; $n<=$num; $n++){
			//echo ($n<10)." ";
			if($n<10) $student_code = "00".$n;
			else if ($n<100) $student_code = "0".$n;
			else $student_code = $n;
			
			$reg_code = $code."$student_code";
			//Going to check if all records are in the DB
			
			if(!empty($this->check($reg_code))) $class_stat[$reg_code] = 1;
			else $class_stat[$reg_code] = 0;
		}
		if(array_keys($class_stat, 0)){
			//Checking if scrap request is procesed
			global $conn;
			$query = mysqli_query($conn, "SELECT * FROM scrap_request WHERE code = \"$code\"") or die(mysqli_error($conn));
			if(mysqli_num_rows($query)>0) return true;
			else{
				$this->askscrap($code);
				return false;
			}

		}
	}
	function classnum($classcode){
		//Function to tell num in class
		//We first check in askscrap

		$defaultclassnum = 100; //Default number of students in class

		//Okay Let's first try checking among the kept marks in the DB
		global $conn;

		//Going to get the class level and table so as to know where the marks are stored
		$classlevel = $this->classcode($classcode);
		$table = $this->restable($classlevel);

		//Here we're going to look in the table of exams results info to check number of students.
		$query = mysqli_query($conn, "SELECT code FROM  $table WHERE code LIKE '%$classcode%' ORDER BY code DESC LIMIT 1") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);

		$maxnum = array();

		$max_in_db = substr($data['code'], -3);
		$standardnum = 300;

		//Getting number of students in askcrap 
		$query = mysqli_query($conn, "SELECT maxnum, status FROM scrap_request WHERE code LIKE \"%$classcode%\" ")or die(mysqli_error($conn));	
		while ($data = mysqli_fetch_assoc($query)) {
			$maxnum = array_merge($maxnum, array($data['maxnum']));
		}

		if(!empty($maxnum) && $maxnum<$standardnum) return $maxnum;
		elseif ($max_in_db>15) {
			//Here 15 refers to smallest possible class number
			return $max_in_db;
		}else{
			return $defaultclassnum;
		}
	}
	function askscrap($classcode){
		global $conn;
		mysqli_query($conn, "INSERT INTO scrap_request(code) VALUES(\"$classcode\")");
	}

	function getMarks($code){
		//Function does all the tasks for the marks to be got
		//We validate the code
		if($this->validatecode($code)){


			$marks = $this->check($code);
			if($marks){
				//Here marks are found
				$this->logview($code);
				return $marks;
			}else{
				//Here marks are not in the DB
				$marks = $this->scrap($code);
				if(!empty($marks)){
					//Here we save marks

					$this->insert($this->classcode($code), $marks['meta'], $marks['marks']);
				}else{
					//Error
					return false;
				}
				return $marks;
			}
			
			
		}else{
			echo "Invalid Code!<br />";
		}	
	}

	function scrap($code){
		global $conn;
		$errors = array();
		$html = $this->getrespage($code);
		include_once "scripts/simple_html_dom.php";
		$html = str_get_html($html);
		
		$mycomb =  WEB::getInstance("combination");

		$ccomb = $this->code2comb($code); //This returns array

		//Going to check for errors
		if(!$html){
			//Here the HTML was not fetched successfully
			$query = mysqli_query($conn, "INSERT INTO exam_results_error_log(code, user, reason) VALUES(\"$code\", 'visitor', 'HTML was not fetched successfully')") or die(mysqli_error($conn));
			return false;
		}
		$adata = array();
		if(!empty($ccomb) && array_keys($mycomb->combAuth($ccomb), "WDA")){
			//Scraping WDA's website
			//Getting metadata
			if(empty($html->find(".panel-col-top", 0))){
				//Here results are not there
				return false;
			}

			$metadata =  $html->find(".panel-col-top", 0)->find(".inside", 0);

			$namedata = $metadata->find(".views-field", 0)->find("span", 0)->plaintext;
			$lnamedata = $metadata->find(".views-field", 1)->find("span", 0)->plaintext;
			$genderdata = $metadata->find(".views-field", 2)->find(".field-content", 0)->plaintext;
			$schoolata = $metadata->find(".views-field", 4)->find(".field-content", 0)->plaintext;

			//Packing meta data
			$meta['name'] = trim($namedata).' '.trim($lnamedata);
			$meta['gender'] = trim($genderdata);
			$meta['school'] = trim($schoolata);
			$meta['code'] = $code;			
		
			//Getting marks
			$marks = array();
			$subjectscontainer = $html->find(".panel-col-first", 0)->find(".inside", 0);
			$markscontainer = $html->find(".panel-col-last", 0)->find(".inside", 0);
			for($n = 0; $subjectscontainer->find(".views-field", $n) && $markscontainer->find(".views-field", $n); $n++) {
				$subject = $subjectscontainer->find(".views-field", $n)->find(".field-content", 0)->plaintext;
				$mks = $markscontainer->find(".views-field", $n)->find(".field-content", 0)->plaintext;
				$marks[trim($subject)] = trim($mks);
			}


			//Getting more meta on bottom of the page

			$summarydata = $html->find(".panel-col-bottom", 0)->find(".inside", 0);

			$mentiondata = $aggregate = '';
			if($summarydata){
				$aggregate =$summarydata->find(".views-label-field-aggregate", 0);
				if($aggregate){
					$aggregate = $aggregate->next_sibling()->plaintext;

				}
				$mentiondata = $summarydata->find(".views-label-field-mention2", 0);
				if($mentiondata){
					$mentiondata = $mentiondata->next_sibling()->plaintext;
				}
			}

			// $aggregate = $summarydata->find(".views-field", 0)->find(".field-content", 0)->plaintext;
			// $mentiondata = $summarydata->find(".views-field", 1)->find(".field-content", 0)->plaintext;


			
			$meta['aggregate'] = trim($aggregate);
			$meta['mention'] = trim($mentiondata);

			$ret = array_merge(array("meta"=>$meta), array("marks"=>$marks) );
			return $ret;

		}else if(0 && !empty($ccomb) && array_keys($mycomb->combAuth($ccomb), "TTC")){
			//Scrapping ttc
			//Checking if page is correctly loaded with true marks

			if(empty($html->find("#printable"))) return false;

			$metadata = $html->find(".table-striped", 0)->find("tr",  1)->find("td",  1)->find(".info", 0)->plaintext;
			$meta['name'] = $metadata;
			$meta['code'] = $code;
			$meta['gender'] = NULL;

			$markscontainer = $html->find(".results", 0)->find("table", 0);

			$marks = array();
			for($n =0; $subj = $markscontainer->find("tr", 0)->find("th", $n); $n++){
				$mks = $markscontainer->find("tr", 1)->find("td span", $n)->plaintext;
				$marks[trim($subj->plaintext)] = trim($mks);	
			}

			if(isset($marks['Marks'], $marks['Grade'], $marks['Mention'])){
				$meta['grade'] = $marks['Grade'];
				$meta['aggregate'] = $marks['Marks'];
				$meta['mention'] = $marks['Mention'];

				//Removing meta from marks
				unset($marks['Marks'], $marks['Grade'], $marks['Mention']);
			}

			$ret = array_merge(array("meta"=>$meta), array("marks"=>$marks));
			return $ret;
				
		}else{
			$exerror = $html->find("table[class=msg]", 0);


			if($exerror){
				if(preg_match("^No results available for student with Registration^", $exerror->plaintext)){
					$errors = array_merge($errors, array(1));
					//echo "Invalid student registration code";
					return false;
				}
				else{
					//This is the rarest error
					//echo $exerror->plaintext;
					//going to record this error for tracking and check if suchlike errors are allowed to be displayed
					$errortext = mysqli_real_escape_string($conn, $exerror->plaintext);
					$checkq = mysqli_query($conn, "SELECT * FROM exam_errors WHERE string = \"$errortext\" AND status = 'allowed' ");
					if($checkq && mysqli_num_rows($checkq)){
						$errors = array_merge($exerror->plaintext, array(1));
					}else{
						//Inserting in DB
						$query = mysqli_query($conn, "INSERT INTO exam_errors(string) VALUES(\"$errortext\") ");

					}
					$errors = array_merge($errors, array(1));
					return false;
				}
			}
			//Here we can continue getting data
			//Getting class code to help in chosing vars

			//Canditate info
			$cinfo = $html->find(".candinfos", 1);

			// var_dump($html->find("fieldset table tbody tr"));
			

			$cdata = array();
			$student_level = $this->level($code);
			if($this->level($code) == "S6"){
				foreach($html->find("td") as $key=>$data){
					if(preg_match("^Candidate Name^", $data->plaintext))
						$cdata['name'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Gender^", $data->plaintext))
						$cdata['gender'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Registration Num^", $data->plaintext))
						$cdata['code'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Aggregate^", $data->plaintext))
						$cdata['aggr'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Mention^", $data->plaintext))
						$cdata['mention'] = $data->next_sibling()->plaintext;
				}
				//Packing cand data in array
				$basedata = array('code'=>$cdata['code'], 'name'=>$cdata['name'], 'mention'=>$cdata['mention'], 'gender'=>$cdata['gender'], 'aggregate'=>$cdata['aggr']);
				$res = $html->find("#results", 0);

				$marks = array();
				$csc = $res->find("th");
				$mks = $res->find("td");

				foreach ($csc as $cindex=>$course) {
					$coursename = strtolower( str_ireplace(" ", "_", $course->plaintext));
					if($coursename=="entrepr."){
						$marks = array_merge($marks, array("entrepreneurship"=>$mks[$cindex]->plaintext));
					}else if($coursename=="geo"){
						$marks = array_merge($marks, array("geography"=>$mks[$cindex]->plaintext));
					}else{
						$marks = array_merge($marks, array("$coursename"=>$mks[$cindex]->plaintext));
					}
				}
			}else{
				foreach($html->find("td") as $key=>$data){
					if(preg_match("^Candidate Name^", $data->plaintext))
						$cdata['name'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Gender^", $data->plaintext))
						$cdata['gender'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Registration Num^", $data->plaintext))
						$cdata['code'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Aggregate^", $data->plaintext))
						$cdata['aggr'] = $data->next_sibling()->plaintext;
					else if(preg_match("^Division^", $data->plaintext))
						$cdata['division'] = $data->next_sibling()->plaintext;
				}

				//Checking admissions details
				$admissions_details = $html->find("table table tbody tr");
				$adata = array('school'=>false, 'combination'=>false, 'location'=>false, 'headteacher'=>false, 'phone'=>false, 'admission'=>false);
				foreach ($admissions_details as $key => $ad_info) {
					if(preg_match("^School^", $ad_info->find("td", 0)->plaintext)){
						if($ccomb == "OLC"){
							$sc_data = explode(", ", $ad_info->find("td", 1)->plaintext);
							$adata['school'] = $sc_data[0];
							$adata['combination'] = $sc_data[1]??"";
						}else{
							$adata['school'] = $ad_info->find("td", 1)->plaintext;
							$adata['combination'] = "PRI";
						}						
					}else if(preg_match("^Location^", $ad_info->find("td", 0)->plaintext)){						
						$adata['location'] = $ad_info->find("td", 1)->plaintext;
					}else if(preg_match("^Name^", $ad_info->find("td", 0)->plaintext)){						
						$adata['headteacher'] = $ad_info->find("td", 1)->plaintext;
					}else if(preg_match("^Telephone^", $ad_info->find("td", 0)->plaintext)){						
						$adata['phone'] = $ad_info->find("td", 1)->plaintext;
					}else if(preg_match("^Admission^", $ad_info->find("td", 0)->plaintext)){						
						$adata['admission'] = $ad_info->find("td a", 0)->href;
					}
				}
				//Inserting admission data
				$comb = $this->code2comb($code);
				$table = $this->restable($comb);

				//Checking if link was scrapped
				$remote_letter_link = $conn->real_escape_string($adata['admission']);	
				$query = $conn->query("SELECT * FROM national_admissions WHERE remote_link = \"$remote_letter_link\" LIMIT 1 ") or die("Cant get letter link $conn->error");
				if($query->num_rows > 0){
					$data = $query->fetch_assoc();
					$letter_link = $data['local_link'];
				}else{
					//here the document is not downloaded
					if($remote_letter_link){
						$doc = $this->curl($remote_letter_link);
						$letter_link = "scrap/admission_letters/".$code."_letter_".time().".pdf";
						$file = fopen($letter_link, 'w+');
						fwrite($file, $doc);
						fclose($file);
					}else{
						$letter_link = "";
					}
				}

				//Recording admission details
				$sql = "INSERT INTO national_admissions(code, school, combination, headteacher, phone, location, remote_link, local_link) VALUES('$code', \"$adata[school]\", \"$adata[combination]\", \"$adata[headteacher]\", \"$adata[phone]\", \"$adata[location]\", \"$remote_letter_link\", \"$letter_link\" )" or die("Can't insert admission details $conn->error");
				$conn->query($sql);

				//Packing cand data in array
				$basedata = array('code'=>$cdata['code'], 'name'=>$cdata['name'], 'division'=>$cdata['division'], 'gender'=>$cdata['gender'], 'aggregate'=>$cdata['aggr']);
				$res = $html->find("#results", 0);

				$marks = array();
				$csc = $res->find("th");
				$mks = $res->find("td");

				foreach ($csc as $cindex=>$course) {
					$coursename = strtolower( str_ireplace(" ", "_", $course->plaintext));
					if($coursename=="entrepr."){
						$marks = array_merge($marks, array("entrepreneurship"=>$mks[$cindex]->plaintext));
					}else if($coursename=="geo"){
						$marks = array_merge($marks, array("geography"=>$mks[$cindex]->plaintext));
					}else{
						$marks = array_merge($marks, array("$coursename"=>$mks[$cindex]->plaintext));
					}
				}
			}
		
			//returning data as a bidimensional array

			$ret = array('meta'=>$basedata, 'marks'=>$marks, 'admission'=>$adata);
			return $ret;
		}
	}

	function restable($classcode){
		if($classcode == 'OLC') return 'ores';
		else if($classcode=='PRI') return 'pres';
		else if($classcode == "S6") return "6marks";
	}
	function insert($classcode, $details, $marks){
		global $conn;
		//This function inserts marks in the database
		
		//Going to create values query
		$sqlvals = '';
		$all_data = array_merge($details, $marks);
		$sup_values = $sup_fields='';
		
		//Striping HTML in details for security
		foreach($all_data as $record=>$value)
				$all_data[$record] = mysqli_real_escape_string($conn, $value);
		
		
		if($this->level($details['code']) == "S6"){
			//Inserting meta

			//Checking if school was also set in array
			$school = !empty($details['school'])?$details['school']:null;
			$grade = !empty($details['grade'])?$details['grade']:null;

			$query = mysqli_query($conn, "INSERT INTO 6marks(code, name, gender, aggregate, mention, school, grade) VALUES(\"$details[code]\", 
				\"$details[name]\", \"$details[gender]\", \"$details[aggregate]\", \"$details[mention]\", \"$school\", \"$grade\")") or die(mysqli_error($conn));

			//Looping through all marks and inserting in table
			foreach($marks as $key=>$value){
				$insq = mysqli_query($conn, "INSERT INTO marks(student, subject, value) VALUES(\"$details[code]\", \"$key\", \"$value\")") or die(mysqli_error($conn));
			}
		}else{

		}

		foreach($all_data as $record=>$value){
			$sup_fields .= $record.", ";
			$sup_values .= "'".$value."', ";
		}
		$sup_fields = trim($sup_fields, ', ');
		$sup_values = trim($sup_values, ', ');
		


		$res_table = $this->restable($classcode);
		$query =  "INSERT INTO $res_table($sup_fields) VALUES ($sup_values)";
		$query = mysqli_query($conn, $query);

		if(mysqli_errno($conn)){
			if(mysqli_errno($conn)==1062){
				echo "User already existed";
				return false;
			}
		}
		else{
			return true;
		}
	}
	function classsubjectmarks($class, $subject){
		//This function returns the marks got in a specific subject in a class
		global $conn;

		//validating the class first
		if($this->validateclass($class)){
			//getting the class level
			$classlevel = $this->classcode($class."003"); //Added 003 to just make a real code

			if($classlevel=='S6'){
				//Here we have to look marks in marks table
				$query = mysqli_query($conn, "SELECT value FROM marks WHERE student LIKE '$class%' AND subject = \"$subject\"") or die(mysqli_error($conn));
				$data = mysqli_fetch_assoc($query);
				
			}
		}


	}
	function gets6marks($code){
		//Function to return all marks associated with code
		global $conn;
		$query = mysqli_query($conn, "SELECT * FROM marks WHERE student = \"$code\"") or die(mysqli_error($conn));
		$marks = array();

		while($data = mysqli_fetch_assoc($query)){
			$marks = array_merge($marks, array($data['subject']=>$data['value']));
		}
		if(empty($marks)) return false;
		//Meta data
		$query = mysqli_query($conn, "SELECT * FROM 6marks WHERE code = \"$code\"") or die(mysqli_error($conn));
		
		$meta = mysqli_fetch_assoc($query);
		if(!is_array($meta)) return false;

		$marks = array_merge(array("marks"=>$marks, "meta"=>$meta));
		return $marks;
		
	}
	function check($code){
		global $conn;
		//member to check if $code belongs in database

		//Getting class level
		$classcode = $this->classcode($code);

		if($this->level($code) == "S6"){
			$marks = $this->gets6marks($code);		
			return $marks;
		}else{
			$table = $this->restable($classcode);

			$query = mysqli_query($conn, "SELECT * FROM $table WHERE code=\"$code\"") or die(mysqli_error($conn));
			if(mysqli_num_rows($query)>=1){
				$res = mysqli_fetch_assoc($query);

				//Here am going to create a semantic array
				$fields = $this->resultsfields($code);
				$retmarks = array();
				foreach($fields as $key=>$value){

					foreach ($value as $dbfield => $pfield) {
						$retmarks[$key][$dbfield] = $res[$dbfield];
					}

				}

				//Checking admission details for PRI and OLC
				if($classcode == 'PRI' || $classcode == "OLC"){
					$query = $conn->query("SELECT * FROM national_admissions WHERE code = \"$code\" ORDER BY entry_date DESC LIMIT 1") or die("Error getting admission $conn->error");
					$adata = $query->fetch_assoc();
					$retmarks['admission'] = $adata;
				}
				return $retmarks;
			}
			else return false;
		}		
	}
		// function keepexstats($class, $subject){
		// 	//This function keeps a stat of a subject in the basket which will help us to know subjects performance in a class
		// 	//we will use basket
		// 	//Here class will be the string representing the schoool and subject will be the array containing subject:marks
		// 	$basket = WEB::getInstance('basket');

		// 	if(!is_string($class) || !is_array($subject)){
		// 		//Here data sent are wrong
		// 		return false;
		// 	}

		// 	//Storing records
		// 	//Initialising basket with courses

		// 	var_dump($basket->get('exstats'));

		// 	foreach ($subject as $course => $marks) {
		// 		//Checking if the subject exists in the stats
		// 		if(array_keys($basket->get('exstats'), $subject)) echo "kk";
		// 	}
		// 	var_dump($subject);
		// 	die ;

		// 	$sdata = array($class=>$subject);

		// 	$basket->set('exstats', $sdata);
		// }
	function classexcode($classcode){
		//class example code
		//Function to return one student code kept in database, this could be used to test if we have at least got one student of the class in the database
		global $conn;
	
		//Getting the table name storing marks
		$table = $this->restable($this->classcode($classcode."003")); //003 is just a string to make up full exam code

		//Querying the table for ex code
		$query = "SELECT code FROM $table WHERE code LIKE '$classcode%' LIMIT 1";

		$query = mysqli_query($conn, $query) or die(mysqli_error($conn));

		if(mysqli_num_rows($query)){		
			$data = mysqli_fetch_assoc($query);
			$scode = $data['code'];
			return $scode;
		}else{
			//Here no code found
			//Sending just a random code
			$randcod = $classcode."004";
			return $randcod;
		}
	}
	function saved_marks($user, $code){
		//Checking if the user saved marks
		global $conn;
		$query = mysqli_query($conn, "SELECT * FROM results_saves WHERE user = \"$user\" AND code = \"$code\" LIMIT 1") or due("Error checking marks save ".mysqli_error($conn));
		if(mysqli_num_rows($query)){
			return 1;
		}else return false;

	}
	function subpname($subject, $type, $output){
		global $conn;
		//Here we get a subject short print name
		$query = "SELECT $output FROM subjects_definition WHERE `$type` = '$subject'";
		//echo $query."<br />";
		$query = mysqli_query($conn, $query) or die(mysqli_error($conn));
		if($query){
			$data = mysqli_fetch_assoc($query);
				return !empty($data[$output])?$data[$output]:false;
		}else return  false;
	}
	public function check_admission_scrap($code)
	{
		//Function to check if admission details of student
		global $conn;
		$query = $conn->query("SELECT * FROM national_admissions WHERE code  = \"$code\" ") or die("Can't check admission details $conn->error");
		return $query->num_rows;
	}
	function subabbreviate($subname){
		//Here we abbreviate a course name
		global $conn;
		$query =mysqli_query($conn, "SELECT name FROM subjects_definition WHERE value=\"$subname\"") or die(mysqli_error($conn));
		if(mysqli_num_rows($query)>0){
			$data = mysqli_fetch_assoc($query);
			return $data['name'];
		}else return $subname;
	}

	function subjectname($name){
		global $conn;
		//Finding the real subject name out of abbreviated and informal subjects names from external sources
		$name = mysqli_real_escape_string($conn, $name);
		$query = mysqli_query($conn, "SELECT * FROM subjects_definition WHERE name = \"$name\"") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);
		if(!empty($data)){
			$retname = $data['value'];
		}else{
			$retname = $name;
		}
		return $retname;
	}
	function result_save($code, $user){
		global $conn;

		//Validating code
		if($this->validatecode($code)){
			//Checking if $result is already saved by the user
			$query = mysqli_query($conn, "SELECT * FROM results_saves WHERE code = \"$code\" AND user = \"$user\"") or die("Error: ".mysqli_query($conn));

			if(mysqli_num_rows($query)){
				//Result is already saved
				return true;
			}else{
				$sql ="INSERT INTO results_saves(code, user) VALUES(\"$code\", \"$user\")";
				$query = mysqli_query($conn, $sql) or die("Error saving marks, ".mysqli_error($conn));
				if($query)
					return true;
				else return false;
			}
		}else{
			//Code is wrong
			return false;
		}	
	}
	function printmarks($marks){
		//Going to find the real name of the subject
		foreach ($marks as $key => $value) {
			$name = $this->subjectname($key);
			?>
				<li><?php echo ucwords($name); ?>: <?php echo $marks[$key]; ?></li>
			<?php
		}
	}
}
?>