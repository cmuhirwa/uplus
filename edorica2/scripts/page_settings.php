<?php
include($functions);
/*This constant will allow us to define every page's modules without repeating its name*/
define("MODS", "_mods");

//function to determine module support on specific page
function supported($module, $page, $conn){
	/*when modules are left or right, i append 'm' coz right&&left are mySql reserved keywords, and can't be stored in database securely*/
	if($module=='left'|| $module=='right'){$module=$module.'m';}
	
	$page_query=mysqli_query($conn, "SELECT modules FROM pages WHERE name='$page'");

	if(!empty($page_query)){
		$page_data = mysqli_fetch_assoc($page_query);

		$mod_query = mysqli_query($conn, "SELECT * FROM modules WHERE id='$page_data[modules]'") or die("Error Selecting Modules:<br />".mysqli_error($conn));

		$page_modules=mysqli_fetch_assoc($mod_query);
		if(!empty($page_modules)){
			$pageMODS = array_keys($page_modules, 1);
			foreach($pageMODS as $mod){
				if($mod==$module&&$page_data!=''){
					return 1;
				}
			}

		}else{
			return false;
			//echo "Module not found";
		}
	}else{
		//Checking module was not successfull
		echo mysqli_error($conn);
	}
	
}
/*Function to print arrays, most of the time will be used for debugging*/
function printarray($array){
	foreach($array as $print) echo $print."<br>";
	}
/*This function will be used to render modules*/
function rendermodule($page, $module){
	
	}
 ?>