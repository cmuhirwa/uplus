<?php  

	$db = new mysqli("localhost", "clement", "clement123" , "uplus");
	
	if($db->connect_errno){
		die('Sorry we have some problem with the Database!');
	}

	//configuring the relative request path
	$reqURI = trim($_SERVER['REQUEST_URI'], '/');
	$reqParts = explode("/", $reqURI);
	$host = $_SERVER['HTTP_HOST']??'uplus.rw';
	if($host!='localhost'){
		if(preg_match("/.+\.php$/", $reqURI)){
			// 	//here file ends in .php so we have to ewmove last index before counting
			$count = count($reqParts)-1;
		}else{
			$count = count($reqParts);
		}

		$temp = "";
		for($n=0; $n<$count; $n++){
			$temp.="../";
		}

		//appending
		$fileDb = $temp.'db.php';
		include_once($fileDb);
		echo "$fileDb";
	}else{
		die("localhost is not good for now.");
	}         
?>