<?php
//Checking a WDA exam result
//Getting file
function checkpage($code){
	$path = 'files/';
	$filename = $path."$code.html";
	if(file_exists($filename)){
		$file = file_get_contents($filename);
		return $file;
	}else{
		//ask scrap
		return false;
	}
}
?>