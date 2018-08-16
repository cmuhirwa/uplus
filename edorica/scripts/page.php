<?php
class page extends web{
	public $path;
	public $level;
	public $flevel;
	public $get;
	public $conn;
	
function __construct(){
	$this->path = $this->getpath();
	$this->level = $this->level('plevel');
	$this->flevel = $this->level('flevel');
	$this->get = $this->get();
	$this->dbname = $this->getPageName();
	$this->name = $this->endPageName();
	$this->conn = $this->connect();
	$this->id = $this->id();

	//Storing all http routes
	$this->routes = $this->call_parts();
	
	//Putting ourselves in basket so other funcitons can access this object
	$basket = WEB::getInstance('basket');
	$basket->set('pageInstance', $this);

	//Requiring page-specific files
	$this->requirePageFiles($this->id);
}
function get(){
	unset($ret);
	//Function to return GET variables
	$get_vars = $this->path;

	if(isset($get_vars['query_vars'])){
		$ret = $get_vars['query_vars'];
	}else $ret='';

	return $ret;
}
function hasmodule($modulename, $page){
	global $conn;
	//Function to tell if page has $modulename as its part
	//Looking in the database od page nodules for page

	$query = mysqli_query($conn, "SELECT pagemodules.id FROM pagemodules JOIN mods_def as mods ON pagemodules.module = mods.id WHERE page='$page'") or die(mysqli_error($conn));

	$data = mysqli_fetch_assoc($query);
	
	if(!empty($data)) return true;
	else return false;


}
public function loadPosition($positionName){
	global $conn;
	//Function that loads position's modules in a page.
	//Here we check if the $positionName is defined as a position in database

	//Getting pageID and loading all modules associated with position name
	$pageID = $this->id();

	$query = mysqli_query($conn, "SELECT mods.* FROM pagemodules as mods JOIN pagepositions as pos ON pos.id = mods.position WHERE mods.page='$pageID' AND pos.name='$positionName'") or die(mysqli_error($conn));
	while($module = mysqli_fetch_assoc($query)){
		//Here we loop through all modules and load them by ID
		$modID = $module['module'];
		$Mymodule = WEB::getInstance('module');
		$Mymodule->loadModule($modID);
	}

}
public function id(){
	global $conn;
	//Function to find current page's id
	$pagename = $this->dbname;

	$query = mysqli_query($conn, "SELECT id FROM pages WHERE name=\"$pagename\" AND level = '$this->level' LIMIT 1") or die(mysqli_error($conn));
	$data = mysqli_fetch_assoc($query);

	if($data){
		$id = $data['id'];
		return $id;
	}else{
		return false;
	}

}
function endPageName(){
	global $conn;
	//This return the last and exact page name like school name and so
	// $call_parts = $this->path;

	// $call_parts = $call_parts['call_parts'];

	$call_parts = $this->call_parts();

	$retname = $call_parts[count($call_parts)-1];


	$retname = ucwords(str_replace('-', ' ', $retname));

	//Here we are going to check the real database name of the page
	if(($this->level>1) && !empty($this->dbname)){
		//Here we have to find the sub category's real name

		$pageID = $this->id(); //Current page's ID
		$parentPage = $this->parentPage($pageID); //Here we get parent of current page

		$query = "SELECT * FROM subpages WHERE parent='$parentPage' AND handler='$pageID'";
		$query = mysqli_query($conn, $query) or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);

		if(!empty($data)){
			$pageItems = $data['items'];
			//Page items consists of table name and column name
			//Here we are going to use data from url but to get correct format of DB data so as to maintain originaity of data

			$pageItems = explode(", ", $pageItems);

			if(count($pageItems) == 1){
				$name = $pageItems[0];
			}else{
				$table = $pageItems[0];
				$column = $pageItems[1];
				//Different tables have different ways of storing and structuring their data that we want to represent now from URL
				//We are calling url2db to take the page name whis extracted from the URL and parse it to the database standards of $table
				$retname = $this->URL2db($retname, $table);
				$retname = mysqli_real_escape_string($conn, $retname);

				$query = mysqli_query($conn, "SELECT * FROM $table WHERE $column=\"$retname\"") or die(mysqli_error($conn));
				$odata = mysqli_fetch_assoc($query);

				if(!empty($odata)){
					//Here there was successfull result
					$name = $odata[$column];
				}else{
					//Uhm, no data got, page seems wrong!!!!!!!!
					$name = "not_found";
				}
			}
			

			
		}else{
			//Here page could not be found
			//echo "Page could not be found";
			$name = "not_found";
		}
		return $name;
	}
	
}
function parentPage($childPage){
	global $conn;
	//This function takes childPagename and checks if it has a parent and attampt finding it's parent
	//Using DB table called subpages we are going to attempt

	$query = mysqli_query($conn, "SELECT * FROM subpages WHERE handler='$childPage' LIMIT 1") or die(mysqli_error($conn));
	$data = mysqli_fetch_assoc($query);
	if(!empty($data)){
		//Here the parent exists
		return $data['parent'];
	}else{
		//Seems that the page does not have parent
		return false;
	}
	$pname = $this->name;
	$dbName = $this->dbname;

	if($pname == $dbName){
		//Here print name and database page names are equal, then no parent and children
		return false;		
	}else{
		//Here we find the parent, and parent is the DBname
		return $dbName; 
	}
}
function name2id($pagename){
	global $conn;
	$query = mysqli_query($conn, "SELECT id FROM pages WHERE name=\"$pagename\" LIMIT 1") or die(mysqli_error($conn));
	$data = mysqli_fetch_assoc($query);
	if(!empty($data)){
		$pageID = $data['id'];
		return $pageID;
	}else return false;
}
function getPageName(){
	global $conn;
	$current_page = $this->base_page(); //parent page

	$current_pageID= $this->name2id($current_page); //Getting ID of current base page
	//Checking if current page is a root directory and finding sub direcory
	//checking database's page-level

	$query = "SELECT level FROM pages WHERE name=\"$current_page\"";

	$plq = mysqli_query($conn, $query) or die("Error Checking page level".mysqli_error());

	$page_level = mysqli_fetch_assoc($plq);
	$page_level = $page_level['level'];

	if($page_level<$this->level){
		//Here there exists multiple pages being requested
		//Let's look for pages
		$path = $this->path['call_parts'];
		$pages = $this->distinctURL($path, '');	
		$n = count($pages);

		$pageID = $current_pageID;

		for($temp=2; $temp<=$n; $temp++){
			//Here we need to look in db for a subpage with the level
			$query = "SELECT items, handler FROM subpages WHERE parent='$pageID'";
			$catq = mysqli_query($conn, $query);
			if($catq){

				while ($catd = mysqli_fetch_assoc($catq)) {					
					$handler = $catd['handler'];
					$items = $catd['items'];

					//Here We Check If There found items to be represented, if $items is empty. then items cant be found
					if($items=='' || $handler==''){
						//No items found to be rendered or no handler page to display items
						$current_page = _NOT_FOUND;
						break;
					}

					$items = explode(",", $items);

					$query = "SELECT * FROM pages WHERE id='$handler' AND level=$temp";
					$hq = mysqli_query($conn, $query);

					if($hq){
						$hd = mysqli_fetch_assoc($hq);
						$pageID = $hd['id'];

						//Checking if subpage item exists
						if($temp+1 != $n){
							$data = mysqli_real_escape_string($conn, $pages[$temp-1]);

							if(count($items) == 1){
								//Here there is fixed static route
								$current_page = $data;
								break; //We got it
							}else{
								$table = mysqli_real_escape_string($conn, $items[0]);
								$field = mysqli_real_escape_string($conn, $items[1]);
								$table = trim($table);    //Removing possible useless spaces

								$field = trim($field);    //Removing possible useless spaces
								//Checking if current fields is space separated and then forming links - here there will be third array offset						

								if(isset($items[2]) && trim($items[2])=='-') $data = $this->URLtostring($data);					

								$data = str_ireplace("-", "_", $data);


								$data = $this->text2db($data, $table);

								$current_name = $data;
														
								$data_query = mysqli_query($conn, "SELECT * FROM `$table` WHERE `$field`=\"$data\"");

								if($data_query){
									$datad = mysqli_fetch_assoc($data_query);
									if($datad){
										$current_page = $hd['name'];
										break;
									}else $current_page = _NOT_FOUND;
								}

								//If the page is not found in reference list in database 

								else{
									$current_page = _NOT_FOUND;
								}
							}
						}else{

						}
					}
					else $current_page= _NOT_FOUND;
				}
			}
			else{
				$current_page= _NOT_FOUND;
				}

		}
	}
return $current_page;
}

public function getpath() {
	$path = array();
	if (isset($_SERVER['REQUEST_URI'])) {
		$url = $_SERVER['REQUEST_URI'];
		//Striping all slashes
		for($n=0; strpos($url, '//'); $n++){
			$url = str_ireplace("//", "/", $url);
		}

		$request_path = explode('?', $url);

		$path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
		$path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
		$path['call'] = utf8_decode($path['call_utf8']);
		if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
			$path['call'] = '';
		}
		$path['call_parts'] = explode('/', $path['call']);

		//removing null array elements
		//$path['call_parts'] = $this->distinctURL($path['call_parts'], '');

		if(count($request_path)>=2){
			//There is query to the URL like fb.com?id
			$path['query_utf8'] = urldecode($request_path[1]);
			$path['query'] = utf8_decode(urldecode($request_path[1]));
			$path['query'] = rtrim($path['query'], '/');

			$vars = explode('&', $path['query']);
			foreach ($vars as $var) {
			$t = explode('=', $var);
			//if size of $t is less than 2, means that there is query var but no value
			if(isset($t[1])){
				$path['query_vars'][$t[0]] = $t[1];
			}
			else{
			}
			
		}

		}
		else{
			$path['query_utf8'] = urldecode($request_path[0]);	
		} 
	}
	return $path;
}

function URL2db($pagename, $table){
	//Changing URL's data into DB values
	
	if($table == 'courses_def'){
		//Removing spaces
		$retname = str_ireplace(" ", "_", $pagename);
		$retname = strtolower($retname); //changing string to lower case
	}else if($table == 'category_def'){
		$retname = category::p2dbname($pagename);	
	}else{
		$retname = $pagename;
	}

	return $retname;
}
function call_parts(){
	// Funtion to return array of all HTTP routes requested
	// @@ return array
	$raw_routes = $this->path; //This is initialised in the class construct, it gives all raw details
	
	//CHecking if routes
	//This migth rise the problem when we are at home, but it's handled in other funcs
	if (!empty($raw_routes['call_parts'])) {

		$call_parts = $raw_routes['call_parts'];

		//Getting a list of empty routes so as to remove them
		$emptyRoutes = array_keys($call_parts, '');

		$routes = $call_parts;
		
		$temp = count($emptyRoutes);

		//Checking if the page is not homepage, so as to not make empty routes as index is null
		if($temp!=1){
			//Looping through all empty routes and unseting them from routes
			foreach ($emptyRoutes as $key => $value) {
				unset($routes[$value]);
			}
		}
		
		return $routes;
	}else return false;

}
function text2db($name, $type=''){
	global $conn;
	if($type == 'category_def'){
		$ret_str = str_ireplace("-", ' ', $name);
		$ret_str = str_ireplace("_", " ", $ret_str);

		$ret_str = category::p2dbname($ret_str);

		// $ret_str = str_ireplace(" ", _DB_SEPARATOR, $ret_str);
		// $ret_str = str_ireplace("-", _DB_SEPARATOR, $ret_str);
		// $ret_str = strtolower($ret_str); //changing string to lower case
		
		//Going to add s
		//$ret_str = rtrim($ret_str, "s");		
		}
	else if($type == 'courses_def'){
		//Removing spaces
		$ret_str = str_ireplace(" ", _DB_SEPARATOR, $name);
		$ret_str = strtolower($ret_str); //changing string to lower case
		}
	else if($type == 'schools'){
		//Schools name are stored as they are no further processing needed
		$ret_str = str_ireplace("_", " ", str_ireplace("-", ' ', $name));

		}
	else{
		//Removing spaces
		$ret_str = str_ireplace(" ", _DB_SEPARATOR, $name);
		$ret_str = strtolower($ret_str); //changing string to lower case
		}
	return $ret_str;
	}

function site_contact(){
	global $home, $contact;
	?>
    <ul>
    	<li>Name: <?php $this->printlink($home, _SITE_NAME); ?> Administration</li>
        <li>Phone: +250726396284</li>
        <li>e-mail: <a href="mailto:<?php  echo _CONTACT_EMAIL; ?>"><?php  echo _CONTACT_EMAIL; ?></a></li>
        <li><a href="<?php echo $contact; ?>">Contact Us Here</a></li>
    </ul>
    <?php
    }

 public function requirePageFiles($pageID){
 	global $conn;
 	//Function to require page specific files
 	$pageID = mysqli_real_escape_string($conn, $pageID);
 	$query = "SELECT * FROM page_file WHERE page = '$pageID' LIMIT 20";
 	$query = mysqli_query($conn, $query) or die(mysqli_error($conn));

 	while ($file = mysqli_fetch_assoc($query)) {
 		//Requirig the file
 		WEB::require($file['path'], $file['loadPosition']);
 	}
 }

function db2URL($name, $type=''){
	global $conn;
	if($type == 'category_def'){
		$ret_str = str_ireplace('_', '-', $name);
		$ret_str = strtolower($ret_str); //changing string to lower case
		}
	else if($type == 'courses_def'){
		//Removing spaces
		$ret_str = str_ireplace("_", '-', $name);
		$ret_str = strtolower($ret_str); //changing string to lower case
		}
	else if($type == 'schools'){
		//Schools name are stored as they are no further processing needed
		$ret_str = $name;
		}
	else{
		//Removing spaces
		$ret_str = str_ireplace(" ", _DB_SEPARATOR, $name);
		$ret_str = strtolower($ret_str); //changing string to lower case
		}
	return $ret_str;
	}	

function standardURL($URL){
	$ret = strtolower($URL);
	$ret = trim($ret);
	$ret = str_ireplace(" ", "-", $ret);
	return $ret;
}

function URLtostring($string){
	$ret = str_ireplace("-", " ", $string);
	$ret = trim($ret);
	return $ret;
}

function distinctURL($path, $val=''){
	$path_keys = array_keys($path);	
	$arrlen = count($path);
	$ret = array();
	for($n=0; $n<$arrlen; $n++){
		if($path[$path_keys[$n]]==$val){
			unset($path[$path_keys[$n]]);
			}
		else $ret=array_merge($ret, array($path[$path_keys[$n]]));
		}
	
	return $ret;
	}
function array_dist($array, $dup){
	return array_diff($array, $dup);
	}

function redirect($URL){
	header("location:$URL");
	?>
		<div class="redirect">If you are not automatically redirected, click <a href="<?php echo $URL ; ?>">here</a></div>
	<?php
}

function print_array($array){
	if(is_array($array)){
		echo "<pre class='debug'>";
		print_r($array);
		echo "</pre>";
		}
	else return false;
	}
function printlink($link, $text){
	?>
	<a href="<?php echo $link; ?>"><?php echo $text; ?></a>
	<?php
}
function base_page(){
		global $conn;
		$path = $this->path;

		$call_parts = $path["call_parts"];
		
		//removing unnesecary backslashes
		$call_parts = $this->distinctURL($call_parts, '');
		$call_len = count($call_parts);
		
		if(empty($call_parts)){
			$current_page =  "home";
			}
			
		elseif (count($call_parts)>=1) {
			$current_page = $call_parts[0];
		}
		else{
			$current_page = 'home';
		}
		
		//Checking if current page exists or we could redirect to 404 not found
		$test_page = mysqli_real_escape_string($conn, $current_page); //Protecting database
		$page_query = mysqli_query($conn, "SELECT id FROM pages WHERE name='$test_page' LIMIT 1");

		if($page_query){
			$page = mysqli_fetch_assoc($page_query);
			if($page==''){
				$current_page= _NOT_FOUND;}	
		}
		else{
			echo "Error testing Page: <br />".mysqli_error($conn)."";
			}	
		return $current_page;
	
}
function getName(){
	return $this->base_page();
}

public function level($type='plevel'){
	$path = $this ->path;
	$call_parts = $path['call_parts'];
	//var_dump($call_parts);
	
	//for file level 
	if($type == 'plevel'){			
		$ret='';
		$len = count($call_parts);
		for ($num =0; $num<$len; $num++) {
			$key = $call_parts[$num];
			if($key=="/" || $key==''){
			}
			else{
				$ret++;
			}
		}
		//Here when the user visits host level will be zero and lets set it to one instead.
		if($ret=='') $ret=1; 
		return $ret;
	}
	else if($type=='flevel'){
		return count($call_parts);
		}
	}
public function post($URL, $data){	
	// Get cURL resource
	$curl = curl_init();
	
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $_SERVER['HTTP_HOST'].'/'.$URL,
		CURLOPT_USERAGENT => 'Android Sony',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => $data
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	
	if($resp){
		echo "$resp";
		// Close request to clear up some resources
		curl_close($curl);
		}
	else{	
		die(curl_errno($curl)." : ".curl_error($curl)."<br />");
		}

	}
	
function renderMiddle($conn, $current_page){
	
	//Querrying Page Link
	$pname = mysqli_real_escape_string($conn, $current_page);
	$lquery = mysqli_query($conn, "SELECT link FROM pages WHERE name='$pname'");
	if($lquery){
		$ldata = mysqli_fetch_assoc($lquery);
		if($ldata){
			$link = $ldata['link'];
			$link_file =$this->getFile($link, $this->level());
			
			if(file_exists($link_file)){
				//If the file in the database is found				
				include_once($link_file);
				}
				
			else{
				//If the file in the database is not found .. Here I think we will Use Xsml in case database is down
				echo "Page could not be found";
				}
		}
		else{
			//Here link is empty
			}
	}
	else{
		echo "Error Getting Page: <br />".mysqli_error();
		}

	}

}

$page = new page();
?>