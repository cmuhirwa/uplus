<?php
	
	//Including the database comfirmation

	if(!empty($page) && $page == 'admin'){
		include_once "../scripts/dbcons.php";
	}else{
		include_once "scripts/dbcons.php";
	}
	
#connection
class web{
	public $conn;
	public $loadList = array();
	public static $instance = array();
	public $filePositions = array("foot", "head");

	function __construct() {
		$this->conn = $this->connect();
		$this->initLoadList();
		$this->filePositions = array("foot", "head");
	}

	public function connect(){
		global $conn;
		if($conn)			
			return $conn;

		$conn = mysqli_connect(_HOST, _DBUNAME, _DBPWD);
		if(!$conn){
			die('We are experiencing a server error, we can not connect now.<br />Check back sooon:  '.mysqli_connect_error($conn));
		}else {
			mysqli_select_db($conn, _DBNAME) or die("Error selecting database ".mysqli_error($conn)) or die("Could not select DB".mysqli_error($conn));
			mysqli_set_charset($conn, "UTF8");
			return $conn;
		}
	}//function connect() closing


	public function getFile($root_path, $pagelevel, $type='relative'){
		//This function wil allow us to include a file from each level of website we would be.
		$path = $root_path;

		if($type == 'absolute'){
			for($n=0; $n<=$pagelevel && false !== strpos($root_path, "../"); $n++){
				$path = str_ireplace("../", '', $path);
			}
		}else{
			for($temp=1; $temp<$pagelevel; $temp++){
				$path="../".$path;
			}
		}

		return $path;
	}
	public static function require($filename, $position){
		// This function adds $filename to be queued in $position so as to be loaded as the resource of the application
		$edorica = WEB::getInstance('web');
		//$page = WEB::getInstance('page');

		$basket = WEB::getInstance('basket');

		$page = $basket->get('pageInstance');

		$level = $page->flevel;

		$actualFile = $edorica->getFile($filename, $level);

		//A list of positions of which files are loaded into
		$fpos = $edorica->filePositions;

		if(file_exists($actualFile) || 1){
			//Lets get the position and put it there to be loaded by the service there
			$loadList  = $basket->get('loadList');

			//Checking if the position was initialised
			if(array_key_exists($position, $loadList)){
				//The position is initialised
				//We have to include the file in teh array
				$posFiles = $loadList[$position]; //Keeping all other requied files in the array

				if(is_array($posFiles)){
					//as the position contains array, we can now merge into this new file in the position
					$posFiles = array_merge($posFiles, array($actualFile));

					//Adding position files in the whole loadList
					$loadList[$position] = $posFiles;

					$basket->set("loadList", $loadList);
				}else{
					//Here the positin has no files so we add file
					$loadList[$position] = array($actualFile);
					$basket->set("loadList", $loadList);

				}
			}else{
				//Here we have to initialize the position
				//We will just add it in the variable loadPositions and inittialize the positons

				//$edorica->filePositions = array_merge($fpos, array($position=>''));

				//Going to add the position in the basket
				$loadList = array_merge($loadList, array($position=>''));

				$basket->set('loadList', $loadList);

				return $edorica->require($filename, $position);
			}

			$loadList = array_merge($loadList, array($filename));

			//Keeping the required file in the class property_exists
		}else{
			echo "Loading invalid file $actualFile";
		}
		
	}
	private function set($prop, $value){
			if(method_exists($this, $prop)){
					echo "Method exists";
			}
	}
	public static function loadclass($class){
		//Function to load class definition
		//Checking if the class already exists
		if(class_exists($class)){
			//Here class is already loaded
		}else{
			//Going to try loading a class
			$classfile  = "scripts/".$class.".php";

			if(file_exists($classfile))
				include_once $classfile;
			else if(file_exists("../".$classfile)){
				//For admin inclusion
				include_once "../".$classfile;
			}else{
				echo "class not found";				
			};
		}
	}
	public static function getInstance($class){
		//Class to get instance of an object everywhere

		if(isset(self::$instance[$class]))
			return self::$instance[$class];
		else{
			WEB::loadclass($class);
			self::$instance[$class] = new $class();
			return self::$instance[$class];

		}

	}
	public function setLoadList($position, $data){		
		//This function sets the list of the file- resources to be used in the application

		$data = $this->loadList[$position] ?? null;
		if(!empty($data)){
			//Here we have to merge the files list
			$this->loadList[$position] = array_merge($data, array($data));
		}else{
			$this->loadList[$position] = array($data);
		}

	}
	function getLoadList($position=''){
		//This function loads the filenames and filepaths to bei= included in the application
		$basket = WEB::getInstance('basket');
		$loadList  = $basket->get('loadList');

		//Checking if the element was initialized and manipulated as an array
		if(is_array($loadList)){
			//For now this is enough
			if(array_key_exists($position, $loadList)){
				//Here we have called the function to get the files to be loaded in a position and they we were found
				return $loadList[$position];
			}else if(empty($position)){
				//no position specificatiion issued we offer everything
				return $loadList;
			}else{
				return array();
			}
			
		}
	}
	function initLoadList(){
		//List of positions available for file loading
		$basket = WEB::getInstance('basket');

		$basket->set("loadList", array());

		$pos = $this->filePositions;
		for($n=0; $n<2; $n++){
			
			$filename = $pos[$n];
			$loadList = $basket->get("loadList");

			//Checking if the position we are going to load is not already loaded so that we ca just avoid overwriting some data
			if(is_array($loadList) && !array_key_exists($filename, $loadList) ){
				//Adding  the file in the array
				$basket->set('loadList', array_merge($loadList, array($filename=>'')));
			}
		}
	}
}//class web closing
?>
