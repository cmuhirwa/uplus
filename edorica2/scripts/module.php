<?php 
//This class handles pages modules
class module{
	function pagehasmodule($page, $modulename){		
		global $conn;
		/*when modules are left or right, i append 'm' coz right&&left are mySql reserved keywords, and can't be stored in database securely*/
		$modulename = ($modulename=='left' || $modulename=='right')?$modulename.'m':$modulename;
		
		$page_query=mysqli_query($conn, "SELECT modules FROM pages WHERE name='$page'") or die(mysqli_error($conn));
		if(!empty($page_query)){ 
			$page_data=mysqli_fetch_assoc($page_query);
			$mod_query = mysqli_query($conn, "SELECT * FROM modules WHERE id='$page_data[modules]'") or die("Error Selecting Modules:<br />".mysqli_error($conn));
				
			
			$page_modules=mysqli_fetch_assoc($mod_query);				
			if(!empty($page_modules)) {
				$pageMODS = array_keys($page_modules, 1);
				foreach($pageMODS as $mod){
					if($mod==$modulename&&$page_data!='')
						return 1;
				}			
			}else{
				//Handling empty data
				echo "No modules";
				return false;
				}
			}			
	}
	function loadModule($module){
		//Module can be name or ID
		global $conn;

		$data = $module;
		
		if(is_numeric($module)){
			//Here the $module param is ID
			$type  ='id';
		}else{
			//Here the $module is string and we consider it to be name
			$type= 'name';
		}

		$mod_query = mysqli_query($conn, "SELECT * FROM mods_def WHERE $type='$data'"); //Querying the module
		if($mod_query){
			$mod_data = mysqli_fetch_assoc($mod_query);
			if($mod_data){
				$path = $mod_data['path'];
				include($path);
				}
			else echo "could not get module: ".$module; 
			}
		else{
			echo "Can't load module ".mysqli_error($conn);
			}		
		}
	function load($name){
		global $conn;
		if(!is_string($name)) return false;
		$mod_query = mysqli_query($conn, "SELECT * FROM mods_def WHERE name='$name'"); //Querying the module
		if($mod_query){
			$mod_data = mysqli_fetch_assoc($mod_query);
			if($mod_data){
				$path = $mod_data['path'];
				include($path);
				}
			else echo "could not get module: ".$name; 
			}
		else{
			echo "Can't load module";
			}
		
		}
	function login(){
		include_once("pages/login/login_form.php");
		}
}

?>