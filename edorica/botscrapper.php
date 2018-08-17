<?php
  include_once "setup.php";
  include_once "scrap/botcon.php";
  //Getting scrap requests
  $query = mysqli_query($conn, "SELECT * FROM scrap_request WHERE status!=1");
  include_once "scripts/examres.php";
  // include_once "scripts/mail.php";
  // $mail  = new mail();
  $Examres = new Examres();

while($data = mysqli_fetch_assoc($query)){
    $code = $data['code'];

    //$uquery = mysqli_query($conn, "UPDATE scrap_request SET status =1 WHERE id=$data[id]");

    if ($Examres->validateclass($code)) {
		//Looping into the class
		$num = 200;
		$scrap_errors = 0;

		for($n=1; $n<=$num; $n++){

			if($n<10) $student_code = "00".$n;
			else if ($n<100) $student_code = "0".$n;
			else $student_code = $n;
			$reg_code = strtoupper($code."".$student_code);

			//Re

			$smarks = $Examres->getMarks($reg_code);
			var_dump($smarks);
			die ;

			if(empty($smarks)){
			//Here marks are not there
			$scrap_errors++;
			if($scrap_errors>15){
			  break;
			} 

			}
		}
    }
    else if($Examres->validatecode($code)){
      $Examres->getMarks($code);
    }
}
?> 