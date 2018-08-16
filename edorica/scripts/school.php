<?php
class School extends page{
	function catLink($name=''){
			global $edorica, $level, $categories;

			$catlink = $categories."/".$name;
			$catlink = $edorica->getFile($catlink, $level);

			//Converting link to lower case as convention
			$catlink = strtolower($catlink);

			//removing white spaces replacing them with hyphens - for security and SEO
			$catlink = str_ireplace(" ", "-", $catlink);


			return $catlink;
	}
	function code2school($code){
		global $conn;
		//This function given code of the school on national exams results and attempts at finding the edorica school associated with it

		//dONT KNOW WHY THIS CODE
		// $query = "SELECT sc.* FROM schools as sc LEFT JOIN 6markS ON 6marks.code WHERE 6marks.code = \"$code\" AND sc.code !=0 LIMIT 1";

		$query = "SELECT id FROM schools WHERE code = \"$code\" LIMIT 1";

		$query = mysqli_query($conn, $query);
		if(mysqli_num_rows($query)>0){
			$data = mysqli_fetch_assoc($query);
			$scid = $data['id'];
			return $scid;
		}else return false;
	}
	function isregpage(){
		//Function that chaecks if we are on the registration page
		$page = WEB::getInstance('page');
		$routes = $page->routes;
		if($page->level<2) return false;
		$scname = str_ireplace("-", " ", $routes[1])??false;

		if(!empty($routes[1]) && $this->is_school($scname)){
			if(!empty($routes[2]) && $routes[2]=='register'){
				return true;
			}else return true;
		}
	}
	function classExLink($class){
		//Function to return lunk for class marks
		global $exam_results;
		return $exam_results."?class=$class";
	}
	function schoolsWithCat($category){
		//This function returns array containing schools' ID of schools supporting the category

		//Checking combinations though in the category
		$myCombination = WEB::getInstance("combination");
		$combsInCat = $myCombination->combs_taught($category);

		$schools = array();
		if(!empty($combsInCat)){
			//Here we've got combinations taught in the category and now we are going to get schools teaching all those combinations
			for($n=0; $n<count($combsInCat); $n++){
				$schoolsInCurrentCategory = $myCombination->combSchools($combsInCat[$n]);

				if(!empty($schoolsInCurrentCategory)) $schools = array_merge($schools, $schoolsInCurrentCategory);
			}
		}
		return $schools;
	}

	function schoolsWithCourse($course){
		//Function to find schools with course
		global $conn;
		$sql = "SELECT DISTINCT(schools.id) as id FROM `schools` JOIN schoolcombinations ON schoolcombinations.school = schools.id JOIN combinations_def ON combinations_def.combName = schoolcombinations.combination WHERE combinations_def.type LIKE \"%$course%\" ";
		
		$query = mysqli_query($conn, $sql) or die("Cant get schools teaching $course: ".mysql_error($conn));

		$schools = array();
		while ($data = mysqli_fetch_assoc($query)) {
			$schools[] = $data['id'];
		}
		return $schools;
	}

	function getCombs($data, $type='id'){
		global $conn;

		//Function to get combinations in the school
		if($type!='id'){
			//finding school's ID
			$scID = $this->getSchool($data, $type, "id");
		}else{
			//Checking if the ID is school or not
			if($this->is_school($data)){

				$scID = (int)$data;
			}else{
				return array();
			};
		}

		//Getting combs in combinations table
		$oCombs = $this->combinations($scID);

		//Changing the string of combinations to array
		$oCombs = !empty($oCombs)?explode(", ", $oCombs):false;

		//Getting combinations linked in relational model
		$query = mysqli_query($conn, "SELECT combination FROM schoolcombinations WHERE school='$scID'") or die(mysqli_error($conn));

		$mcombs = array();

		for ($n=0; $data = mysqli_fetch_assoc($query); $n++) {
			$mcombs [$n] = $data['combination'];
		}

		//Checking if there is a null
		if(!empty($oCombs) && !empty($mCombs)){
			$combs = array_merge($oCombs, $mcombs);
			$combs = (array_unique($combs));
		}else if(!empty($oCombs) || !empty($mCombs)){
			$combs = !empty($oCombs)?$oCombs:$mCombs;
		}else $combs = array();


		return $combs;
	}
	function courseLink($name=''){
			//This function gives the course link
			global $courses, $edorica, $level;

			$name = str_ireplace(" ", "-", $name); //Removing space to hyphens for SEO ranking

			$clink = $courses."/".$name;
			$clink = $edorica->getFile($clink, $level);
			$clink = strtolower($clink); //Lowering the link chars
			return $clink;
	}
	function link($school, $type='relative'){
		//Function tp generate school's page link
		//type tells type to generate it can be relative or absolute with absolute we do not include full path to school

		global $edorica, $level, $conn;
		$schoolspage = $GLOBALS['school_page'];
		if($type=='absolute'){
			//Removing all backward chars
			$schoolspage = $edorica->getFile($schoolspage, $level, 'absolute');
		}

		if(!is_numeric($school)){
			$column = 'name';
		}else $column='id';

		$q = mysqli_query($conn, "SELECT name FROM schools WHERE $column=\"$school\"");
		if($q){
			$q = mysqli_fetch_assoc($q);
			$name = strtolower(str_replace(" ", "-", $q['name']));
			$link = $schoolspage."/".$name;

			if($type == 'absolute'){
				return $link;
			}

			$link = $edorica->getFile($link, $level);
			return strtolower($link);
		}else return false;
	}
	function perflink($school){
		//function to generate performance analysis link
		global $conn;
		//Checking if $school is real
		if($this->is_school($school)){
			//Here school is in database
			$sclink = $this->link($school);
			return $sclink."/exams-performance";
		}else{
			return "#";
		}
	}
	function classcombs($name, $scid=''){
			global $conn;
			//Function to return combinations classes in a combination
			//We will return general classes in combination if scid is not provided while scid is provided and is correct we'll return classes in that school

			$scid = $scid??false;
			//Classes container
			$classes = array();

			if(!empty($scid)){
				///Checking if the school given is correct or not
				if($this->is_school($scid)){
					//Building query
					$query = "SELECT classes.level FROM classes JOIN schoolcombinations as sc ON sc.id = classes.scomb WHERE sc.school = '$scid' AND sc.combination = \"$name\"";
				}else return false;			
				
			}else{
				$query = "SELECT level FROM classes WHERE combination = \"$name\"";
			}

			//Executing built queries
			$query = mysqli_query($conn, $query) or die(mysqli_error($conn));

			//Fetching classes
			while ($data = mysqli_fetch_assoc($query)) {
				$classes = array_merge($classes, array($data['level']));
			}
			return $classes;
	}

	function registerlink($data, $name='id'){
		global $school_register;
		$school = $this->is_school($data, $name); //Checking if school exist and getting its name or id
		if($school){
			//If the school exists
			$data = strtolower($data);

			if($name == 'id') return strtolower($this->link($school)."/register");
			else return $data;
		}
	}

	function combID($school){
		global $conn;
		$comq = mysqli_query($conn, "SELECT combinations FROM schools WHERE id=$school") or die(mysqli_fetch_assoc($conn));
		$comd = mysqli_fetch_assoc($comq);
		return $comd['combinations'];
	}
	function getcategory($id){
		global $conn;
		$myComb = WEB::getInstance('combination');
		//Function to return categories that a school teaches

		//We determine that from the combinations taught by the school
		$combs = $this->combinations($id);

		$combcats = array();
		for($n=0; $n<count($combs); $n++){
			//Here going to look for every combination's category
			$category = $myComb->taught($combs[$n]);

			//Looping through all combination's category
			for($n2=0; $n2<count($category); $n2++){
				$combcats = array_merge($combcats, array( str_ireplace("_", " ", ucwords($category[$n2]) )) );
			}
		}

		return array_values(array_unique($combcats));
	}
	function printcategory($schoolID){
		$combs = $this->getcategory($schoolID);

		for($n=0; $n<count($combs); $n++){
			$combname = $combs[$n];
			$combname = ucwords(str_ireplace("_", " ", $combname));
			echo $combname;
			echo $n==count($combs)-1?"":", ";
			}
	}

	function comblink($name){
			global $school_combinations, $edorica, $level;
			$combLink = $school_combinations."/".strtolower($name);
			$combLink = $edorica->getFile($combLink, $level);

			return $combLink;
			}
	function smartreg($school){
		//Function to check if the school supports online registration
		global $conn;
		$q = mysqli_query($conn, "SELECT smartReg FROM schools WHERE id=$school") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($q);
		$smartreg = $data['smartReg'];
		if($smartreg) return true;
		else return false;
		}
	//Function to return quick school cta - CALL TO ACTION
	function qcta($id){
		global $conn;
		$id = (int)$id;
		$registerlink = $this->registerlink($id, 'id');
		$sclink = $this->link($id);

		//Checking if the school supports smartreg
		if($this->smartreg($id)){
		?>
		<ul>
			<li><a href="<?php echo $registerlink; ?>">Register</a></li>
			<li><a href="<?php  echo $sclink; ?>">Visit School</a></li>
			<li><a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=http://www.edorica.com/<?php echo str_ireplace(".", "", $registerlink); ?>">Share</a></li>
		</ul>
		<?php
		}else{
			?>
	    <ul>
			<li><a href="<?php  echo $sclink; ?>">Visit School</a></li>
			<li><a target="_blank" href="http://www.facebook.com/sharer.php?u=http://www.edorica.com<?php echo '/'.$sclink; ?>">Share</a></li>
		</ul>
	        <?php
			}
	}
	//Function to return actions  on school profile page
	function cta($id){
		global $conn, $school_page;
		$id = (int)$id;

		//Checking if school provides our online registration
		$smartreg = $this->smartreg($id);
		$scname = $this->getSchool($id, "id", "name");
		$sclink = $this->link($scname);
		$absclink = $this->link($scname, 'absolute'); //Here we are requesting absolute link for external use
		if($smartreg){
			$registerlink = $this->registerlink($id, 'id');
			?>
			<ul>
				<li><a href="<?php echo $registerlink; ?>">Register</a></li>
				<li>
					<a target="_blank" href="http://www.facebook.com/sharer.php?u=http://www.edorica.com/<?php echo $absclink; ?>">Share</a>
				</li>
			</ul>
			<?php
		}else{
			?>
			<p class="stand">This school does not support SMART registration, if you are interested in attending this school, Please consider Asking for Smart Registration</p>
	        <ul class="school-cta">
	        	<li>
	        		<a href = "<?php echo $sclink.'?action=askreg'?> " rel='nofollow'>Request Registration</a>
	        	</li>
	        	<li>
					<a target="_blank" href="http://www.facebook.com/sharer.php?u=http://www.edorica.com/<?php echo $absclink; ?>">Share</a>
				</li>
	        </ul>
	        <?php
		}


		}

	//function to tell gender supported by the school
	function sex($sex){
		switch($sex){
			case 'M':
			case 'm':
				$ret = "All Boys";
				break;
			case 'F':
			case 'f':
				$ret = "All Girls";
				break;
			case 'B':
			case 'b':
			case 'MF':
				$ret = "Boys and Girls";
				break;
			default:
				$ret = "Invalid";
			}
			return $ret;
	}
	function location($scid, $ret_type='string'){
		global $conn;
		//Function to search school
		$lq = mysqli_query($conn, "SELECT * FROM location WHERE id = \"$scid\" ");

		if($lq){
			$ld = mysqli_fetch_assoc($lq);
			if($ret_type == 'string'){
				$location = $ld['province'].", ".$ld['district'].", ".$ld['sector'];
			}else{
				$location = array('province'=>$ld['province'], 'district'=>$ld['district'], 'sector'=>$ld['sector']);
			}			
		}
		else{
			//Error getting location
			$location =  "";
		}
		return $location;
	}
	function slocation($conn, $id){
		$lq = mysqli_query($conn, "SELECT * FROM location WHERE id=$id");
		if($lq){
			$ld = mysqli_fetch_assoc($lq);
			$location = $ld['province'].", ".$ld['district'].", ".$ld['sector'];
			}
		else{
			//Error getting location
			echo "N/A";
			$location =  "";
			}
		return $location;
	}

	function combshort($combination){
		global $conn;
		if(empty($combination)) return false;

		//Function to return combination letters of school
		//Selecting the combination
		$combq = mysqli_query($conn, "SELECT * FROM combinations WHERE id=$combination") or die(mysqli_error($conn));
		$combd = mysqli_fetch_assoc($combq);

		//Removing keys that are metadata fields in combinations table
		unset($combd['id'], $combd['school']);

		//Getting supported combinations
		$combs = array_keys($combd);

		$ret_combs= "";
		for($n=0; $n<count($combd); $n++){
			if($combd[$combs[$n]] && $combd[$combs[$n]]!=0){
				$combname =  $combs[$n];
				$ret_combs .= $combname.", ";
				}
			}
			$ret_combs = trim($ret_combs, ", ");
			return $ret_combs;


	}
	function printcombs($combinations){
		//Given combinations the array containing combs
		$myComb = WEB::getInstance("combination");

		if(is_array($combinations)){
			$n=0;
			foreach ($combinations as $value) {
				$n++;
				if( !preg_match("^science^", $myComb->type($value))){
					$pname = $this->comb_name($value);
				}else $pname = $value;
				?>
				<span><?php echo $pname; echo ($n!=count($combinations))?", ":"";?></span>
				<?php
			}
		}else{
			echo "N/A";
		}
	}

	function combinations($school){
		global $conn;
		// Function to return the combs that the school teaches
		if($this->is_school($school)){

			$combs = array();

			$query = mysqli_query($conn, "SELECT DISTINCT(combination) FROM schoolcombinations WHERE school='$school'") or die(mysqli_error($conn));
			while ($data = mysqli_fetch_assoc($query)) {
				$combs = array_merge($combs, array($data['combination']));
			}
			return $combs;
		}else{
			return false;
		}
	}


	function scombinations($school){
		global $conn;


		if(is_numeric($school)){
			//IF combination is int, then it's school ID, then we have to get the combination ID
			$comq = mysqli_query($conn, "SELECT combinations FROM schools WHERE id=$school") or die(mysqli_error($conn));
			$comq = mysqli_fetch_assoc($comq);
			$combination = $comq['combinations'];
			}
		else{
			$combination = $school['combinations'];
			}
		if(!$combination){
			//Here the school does not have combination
			//echo "N/A";
			return false;
			}
		//Selecting the combination
		$combq = mysqli_query($conn, "SELECT * FROM combinations WHERE id=$combination") or die(mysqli_error($conn));
		$combd = mysqli_fetch_assoc($combq);

		//Removing keys that are metadata fields in combinations table
		unset($combd['id'], $combd['school']);

		//Getting supported combinations
		$combs = array_keys($combd);

		$ret_combs= "";
		for($n=0; $n<count($combd); $n++){
			if($combd[$combs[$n]] && $combd[$combs[$n]]!=0){

				$combname =  $combs[$n];

				$myComb = WEB::getInstance("combination");

				if( preg_match("^science^", $myComb->type($combname))){
					$ret_combs .= $combname.", ";
					}
				else{
					$ret_combs .= $this->comb_name($combname).", ";
					}

			}

			}
			$ret_combs = trim($ret_combs, ", ");
			return $ret_combs;


		}


	function comb_name($name){
		//Function to return combination name out of letter
		global $conn;
		$comq = mysqli_query($conn,  "SELECT * FROM combinations_def WHERE combName='$name'") or die(mysqli_error($conn));
		$comq = mysqli_fetch_assoc($comq);
		$full_name = $comq['des'];
		return $full_name;
	}

	function hascategory($id, $category){
		global $conn;
		$category = strtolower($category);
		$category = str_ireplace(" ", "_", $category);
		$category = strtolower($category);

		$catq = mysqli_query($conn,  "SELECT * FROM category WHERE id=$id");
		if($catq){
			$catd = mysqli_fetch_assoc($catq);
			if($catd){
				unset($catd['id']);
				$search = array_keys($catd, 1);

				print_r($search);
				print_r(array($category));
				$bab = array_search($category, $search);
				if($bab) return 1;
				else return 0;
			}
		}
	}
	function searchLocation($locationName, $type){
		global $conn;
		//Given the type of location ex province or district, we search schools in that location represented by $locationName
		$searchable = location::units(); //Array presenting searchable location units
		if(array_keys($searchable, $type)){
			$query = mysqli_query($conn, "SELECT sc.* FROM location JOIN schools as sc ON location.id = sc.location WHERE location.`$type`='$locationName'") or die(mysqli_error($conn));

			$var = array(); $n=0;

			while ($row = mysqli_fetch_assoc($query)) {
				$var[$n] = $row;
				$n++;
			}
			return $var;
		}else{
			//Here unit is unsearchable
			return false;
		}
	}
	function countschoolsinLocation($locationName, $type){
		global $conn;
		//Given the type of location ex province or district, we search schools in that location represented by $locationName
		$searchable = array("province", 'district', 'sector'); //Array presenting searchable location units
		if(array_keys($searchable, $type)){
			$query = mysqli_query($conn, "SELECT COUNT(*) as sum FROM location JOIN schools as sc ON location.id = sc.id WHERE location.`$type`='$locationName'") or die(mysqli_error($conn));
			$nschools = mysqli_fetch_assoc($query);
			return $nschools['sum'];
		}else{
			//Here unit is unsearchable
			return false;
		}
	}

	function facilities($id){
		global $conn;
			$query = mysqli_query($conn, "SELECT facilities FROM schools WHERE id=$id");
			if($query){
				$data = mysqli_fetch_assoc($query);
				if($data && isset($data['facilities'])){
					return $data['facilities'];
					}
				else return "Facilities Not Found";
				}
				else return mysqli_error($conn);
	}

	function getSchool($data, $type='id', $output='*'){
		/*Function to return all data about school
		*You can pass it two parameters, id or name
		*/
		global $conn;
		$sql = "SElECT $output FROM schools WHERE $type=\"$data\"";
		$scoq = mysqli_query($conn, $sql);
		if(mysqli_errno($conn)==1054){
			die(mysqli_error($conn));
			return false;
			}
		else if(mysqli_errno($conn)){
			die(mysqli_error($conn));
			}
		$scdata = mysqli_fetch_assoc($scoq);
		if($output=='*'){
			return $scdata;
			}
		else if(isset($scdata[$output])){
			return $scdata[$output];
			}

		}
	function is_school($data, $type='id'){
		global $conn;
		//Checking the validity of school in the database
		if($type == 'id'){
			//Here the parameter passed is id,let's check if it is int
			if(is_numeric($data)){
				//Here id is int
				//Querying schools table against ID
				$sq = mysqli_query($conn, "SELECT name FROM schools WHERE id='$data'") or die(mysqli_error($conn));
				$nschools = mysqli_num_rows($sq);

				$sq = mysqli_fetch_assoc($sq);
				$schoolName = $sq['name'];
				//If found schools are less than 1 i return 0, to signal the error
				//else I'll return school_mame;
				if($nschools>=1) return $sq['name'];
				else return false;
			}
			else{
				//Here id is not int, maybe I have forgot to specify type of data am passing
				//I will call this function with name as second parameter
				return $this->is_school($data, "name");

			}
		}
		else if($type == 'name'){
			$sq = mysqli_query($conn, "SELECT id FROM schools WHERE name=\"$data\"") or die(mysqli_error($conn));
			$nschools = mysqli_num_rows($sq);

			$sq = mysqli_fetch_assoc($sq);

			//If school exists I will return it's ID else I return false;
			$ret = ($nschools>0)?$sq['id']:false;
			return $ret;
		}

	}

	function treshold($school, $combination, $class){
		//Function to return number of students allowed to be registered in a class
		//$school is the id of the school we want to check
		global $conn;

		$num=0; 
		$sql = "SELECT cr.number as count FROM class_recruits as cr JOIN classes as cl ON cr.class = cl.id JOIN schoolcombinations as sc ON cl.scomb = sc.id WHERE sc.school=\"$school\" AND sc.combination = \"$combination\" AND cl.level = \"$class\" LIMIT 1";
		$query = mysqli_query($conn, $sql) or die("Can't get threshold! ".mysqli_error($conn));
		if(mysqli_num_rows($query)){
			$data = mysqli_fetch_assoc($query);
			$num = $data['count'];
			return $num;
		}else{
			return false;
		}

	}
};
class sRegister extends School{
	public $schoolName;
	//Class to handle students registration process

	//function to validate step 1 of regisration, we need name of the school and the chose option
	function step1($school, $combination){
		global $conn, $schools_link;

		//Checking length of the class comb submitted as primary verification
		if(strlen($combination)!=5){
			echo "Invalid combination string identifier submitted, please try again!";
			return false;
		}

		//We first check if name is a real school
		$schoolID = $this->is_school($school, "name"); //Getting schoolID when school exists
		if(!$schoolID){
			echo "School Not found in the database<br />Please check schools list again<a href=\"$schools_link\">Schools Page</a> ";
			return false;
		}

		//Checking the option
		$combcat = $combination[0]; //Combination category is first letter of option parameter

		$comb = substr($combination, 2, 4);//chose combination
		$choseclass = $combination[1]; //Chose class

		//Getting class letters
		$class_letters = $this->class_letters();

		//Getting combinations
		if(array_keys($class_letters, $combcat)){
			$classcombs = $this->classcombs($comb, $schoolID);

			//Checking if school teaches combination and has class as chose
			if(array_keys($classcombs, $choseclass)){
				//Checking if there are available places in class
				if($this->treshold($schoolID, $comb, $choseclass) >=1){
					//Registration can proceed

					//Creating user object, so that we can get the user's ID
					$myUser = new user();
					$userID = $myUser->id();

					if(!$userID){
					  $userID = mysqli_real_escape_string($conn, $_COOKIE['PHPSESSID']);
					}

					//Saving the details for future usage in database

					//Checking if the user has requested a class also

					$exiq = mysqli_query($conn, "SELECT id FROM temp_requests WHERE user='$userID' AND name='class' ") or die(mysqli_error($conn));

					if(mysqli_num_rows($exiq)<1){
						$step1q = mysqli_query($conn, "INSERT INTO temp_requests(`id`, `user`, `name`, `value`, `time`)
						  VALUES (NULL, '$userID', 'class', '$combination', CURRENT_TIMESTAMP);
					  		") or die(mysqli_error($conn));							
					}
					else{
						$step1q = mysqli_query($conn, "UPDATE temp_requests SET value='$combination' WHERE user='$userID' AND name='class'") or die(mysqli_error($conn));
					}

					//Checking temp school already set by user
					$exiq = mysqli_query($conn, "SELECT id FROM temp_requests WHERE user='$userID' AND name='school'") or die(mysqli_error($conn));

					if(mysqli_num_rows($exiq)<1){
						$step1q = mysqli_query($conn, "INSERT INTO temp_requests(`id`, `user`, `name`, `value`, `time`)
						VALUES (NULL, '$userID', 'school', '$schoolID', CURRENT_TIMESTAMP);
						  ") or die(mysqli_error($conn));
					}
					else{
						$step1q = mysqli_query($conn, "UPDATE temp_requests SET value='$schoolID' WHERE user='$userID' AND name='school'") or die(mysqli_error($conn));
					}

					return 1;
				}
				else{
					//Here Places are full in this category
					?>
                      	<div>
                        Maximum Number of students was reached, Please choose another option on this school or find school with same combination here..
                        <a href="<?php echo $this->comblink($comb); ?>"><?php echo $this->comb_name($comb); ?> schools</a>
                        Hurry!!
                        </div>
                      <?php
				}
			}
		}
		else{
			//Category is correct but class is not there
			echo "Class you chose is not yet supported, Please come back soon or choose another class<br />";
		}
	}
	function verify1(){
		global $conn;
		//Creating user object
		$myUser = new user();
		$userID = $myUser->id();

		if($userID){

			//Here we are going to replace session ID with real user ID in the database
			$sessid = mysqli_real_escape_string($conn, $_COOKIE['PHPSESSID']);
			$upq = mysqli_query($conn, "UPDATE temp_requests SET user = $userID WHERE user='$sessid'") or die(mysqli_error($conn));
			}
		else{
			$userID = mysqli_real_escape_string($conn, $_COOKIE['PHPSESSID']);
			}
		//Checking if school and class were set in temp_req
		$exiq = mysqli_query($conn, "SELECT id FROM temp_requests WHERE user='$userID' AND ( name='class' or name='school' )") or die(mysqli_error($conn));

		if(mysqli_num_rows($exiq)>=2) return true;
		else return false;

	}
	function verify2(){
		//We verify step 2 by checking pref_school and pref_cookies set
		//We again use verify 1 to ensure step 2 was also done
		if($this->verify1()){
			if(isset($_COOKIE['step2']) && $_COOKIE['step2']==1 && login_status()){
			return 1;
			}
		else if(!login_status()){
			global $login;
			//User not logged in
			?>
            <p>Please login <a href="<?php echo $login; ?>">Here and comeback</a></p>
            <?php
			return false;
			}
		else return true;
			}
		else return false;

		}
	function updateparent($parentID, $studentID, $relation){
		global $conn;
		/**************************************************/
		/* This function updates parent in the database of parents */
		/* Parameters are as follows; */
		/* $parentID is the id of the parent in the usera table */
		/* $studentID is the id of the student who has parent with id $parentID */
		/* Relation : relation of children and parent */
		/**************************************************/

		//Checking of relationship existed
		$exiq = mysqli_query($conn, "SELECT relationID FROM parents WHERE parentID=$parentID AND student=$studentID AND relationship='$relation'") or die(mysqli_error($conn));
		if(mysqli_num_rows($exiq) > 0){
			$id = mysqli_fetch_assoc($exiq);
			return $id['relationID'];
			}
		else{
			$query = mysqli_query($conn, "INSERT INTO parents(parentID, student, relationship) VALUES ($parentID, $studentID, '$relation'); ") or die(mysqli_error($conn));
			return mysqli_insert_id($conn);
			}



		}
	function step3($userID, $fname, $femail, $fphone, $relation){
		global $conn, $myUser, $myMail, $admin_email, $contact;
		$names = chunkname($fname);
		$ffname = trim($names['lname']);
		$lfname = trim($names['fname']);

		///First we check if there is parent with such email or phone number
		$femail = empty($femail)?NULL:$femail;
		$parentexiq = mysqli_query($conn, "SELECT id, fname, lname, type FROM users WHERE
		(email='$femail' AND email IS NOT NULL AND email!='') OR (tel='$fphone' AND tel IS NOT NULL AND tel!='' )");
		if($parentexiq){
						//echo "Found ".mysqli_num_rows($parentexiq)." ".$relation."<br /><br />";
						//Checking if parents with same contacts were found
						//This could mean that two chieldren with same parents are registering
						if(mysqli_num_rows($parentexiq)>=1){

							//Going to get database names of the parent and compare with the user's entry
							$fdbdata = mysqli_fetch_assoc($parentexiq); //Father's database data
							$ffdbname = $fdbdata['fname'];	//Father's DB first name
							$fldbname = "".$fdbdata['lname']; 	//Father's DB lastname

							$fatherID = $fdbdata['id'];
							//We are going to check if names are same
							if(!strcasecmp($ffdbname, $ffname) && !strcasecmp($fldbname, $lfname)){
								//Duplicate parents
								//Here names are the same, we can proceed putting in the DB
								$this->updateparent($fatherID, $userID, $relation);

								//After updating, we return true
								return true;
								}else{

										//Here names are not matching
										//We are going to ask the user if his father also that name

										if(isset($_POST['parent-resolve']) && !empty($_POST['parent_too'])){

											//Here the user has chosen
											$parentreschoice = mysqli_real_escape_string($conn, $_POST['parent_too']);

											if($parentreschoice=='yes'){
												//User knows and accepts the parent
												$this->updateparent($fatherID, $userID, $relation);
												return true;
												}
											else if($parentreschoice =='no'){
												$userdata = $myUser->info($userID);
												$myMail->send($admin_email,
												"Duplicate parents Resolution", "
												<p>User $userID said he has parent with +250$fphone and $femail; and does not know the father associated with the addresees</p>", 								 																			"Content-Type: text/html; charset=ISO-8859-1\r\n");
												//Let's also send the error to the developerfgkg

												?>
                                                <p class="warning">Please use other phone number or email.<br />Or <a href="<?php echo $contact; ?>">contact us notifying the problem</a></p>

                                                <?php
												}
												else{
													echo "<p class='warning'>Invalid Choice</p>";
													}

													}else{
												?>
                                                <div class="process-note">
                                                    We have found that there is already a parent with the <mark>address </mark> you entered.
                                                    Help us to know if He's/She's your parent too or not.
                                                    <div class="parentdes">
                                                        <p>Is <?php echo $ffdbname.", $fldbname, your parent?"; ?></p>
                                                        <div class="">
                                                        	<input type="hidden" name="parent-resolve" value='1'/>

                                                            <input name="parent_too" type="radio" value="yes" id="parentyes" checked/>
                                                            <label for="parentyes">Yes</label>
                                                            <input name="relation" value="father" type="hidden"/>

                                                            <input name="parent_too" type="radio" value="no"  id="parentno"/>
                                                            <label for="parentno">No</label>

															<button class="parentresusbmit next-btn" type="submit" name="form3">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                		<?php
																}//Closing else of checking if parent resolution was submitted
															}

															}
											else{
												//Here the parent is new in the database
												//We have to check if the user had parent, This will protect duplicate entries in the database

												$exiq = mysqli_query($conn, "SELECT * FROM users FULL JOIN parents on parents.student=$userID WHERE id=parents.parentID AND parents.relationship='$relation' AND fname='$ffname' AND lname='$lfname' AND email='$femail' AND tel='$fphone'") or die(mysqli_error($conn));
												if(mysqli_num_rows($exiq)<1){
													$fatherq = mysqli_query($conn, "INSERT INTO users(fname, lname, email, tel, type) VALUES ('$ffname', '$lfname', '$femail', '$fphone', 'parent')") or die(mysqli_error($conn));
												$parentID = mysqli_insert_id($conn);
												$this->updateparent($parentID, $userID, $relation);
												return true;
													}
												else{
													//Here the parent already existed
													return true;
													}

												}

											}
													else{
														 echo mysqli_error($conn);
														}


		}
	function verify3(){
		global $conn;
		if($this->verify2()){
			//Checking parents
			$userID = login_status(); //User is already checked in verify2 to be logged in

			$pareq = mysqli_query($conn, "SELECT relationID FROM parents WHERE student=$userID") or die(mysqli_error($conn));
			if(mysqli_num_rows($pareq)>=2){
				//Every student must have 2parents at leat and inserted in the database
				return true;
				}
			else{
				return false;
				}

			}
		else return false;
	}
	function reg_class(){
		global $conn;
		$myUser = new user();
		$userID = $myUser->id();
		if(!$userID) return false;

		$classq = mysqli_query($conn, "SELECT value FROM temp_requests WHERE user='$userID' AND name='class'") or die(mysqli_error($conn));

		if(mysqli_num_rows($classq)>0){
			$classd = mysqli_fetch_assoc($classq);
			$class = $classd['value'];
			return $class;
		}
		else{
			echo "Current user has not chose class<br />";
			return false;
		}
	}

	function reg_school(){
		global $conn;
		$myUser = new user();
		$userID = $myUser->id();
		if(!$userID) return false;

		$scq = mysqli_query($conn, "SELECT value FROM temp_requests WHERE user='$userID' AND name='school'") or die(mysqli_error($conn));

		if(mysqli_num_rows($scq)>0){
			$scdata = mysqli_fetch_assoc($scq);
			$school = $scdata['value'];
			return $school	;
		}
		else{
			echo "Current user has not chose class<br />";
			return false;
		}
	}
	function class_letters(){
		global $conn;
		$clq = mysqli_query($conn, "SELECT class_letter FROM category_def") or die(mysqli_query($conn));
		$class_letters = array();
		while($cletter = mysqli_fetch_assoc($clq)){
			$class_letters = array_merge($class_letters, array($cletter['class_letter']) );
			}
		return $class_letters;
	}
	function is_class($class){

		//Here we check if a string is a valid class
		global $conn;
		$class = strtoupper($class);
		$class_cat = $class[0];

		//Getting letters of classes from the database
		$class_letters = $this->class_letters();

		//Checking if class exists
		if(empty(array_keys($class_letters, $class_cat)) ){
			echo "Invalid Class";
			return false;
		}

		$class_level = $class[1];
		//Here we're checking if the class level is supported
		//We do it by checking combinations_def table in column called classes
		//We need to get the combination name
		$combname = $this->comb_class($class);

		if(!$combname){
			echo "We can not get the class you want to take <br />";
			return false;
			}
		$supported_classes = $this->supported_classes_in_combination($combname);

		//Here we can not get classes taught in the combination
		if(!$supported_classes){
			//Here we can't get classes in combination
			echo "We can not get classes taught in  the combination you chose<br />";
			return false;
			}

		//If class is not among the classes thought in the combination, then we return false
		if(!array_keys($supported_classes, $class_level)){
			echo "There is no class in the combination you chose";
			return false;
		}
		return true;

	}
	function supported_classes_in_combination($comb){
		//Funtion to return classes supported in a combination regardless school
		global $conn;
		$sq = mysqli_query($conn, "SELECT classes FROM combinations_def WHERE combName='$comb'") or die(mysqli_error($conn));
		$classes  = mysqli_fetch_assoc($sq);
		$classes = $classes['classes'];
		$sclasses = array();

		if(strpos($classes, '-') ){
			//Exploding the range separated by -
			$temp = explode('-', $classes);

			for($n=0; $n<count($temp); $n++){
				for($nte = $temp[$n]; $nte<=@$temp[$n+1]; $nte++){
					$sclasses = array_merge($sclasses, array($nte));
					}
				}
			return $sclasses;
			}
		}
	function comb_class($class){
		//Given the class we attempt getting the name of the combination

		return substr($class, 2, 5);

		//TODO: Think it's deprecated way

		if($class[0]=='N'){

			return 'NUR';
			}
		else if($class[0] == 'P') return 'PRI';
		else if($class[0] == 'S' || $class[0] == 'L'){
			//The combination name starts iin the 3th values of the class
			$comb = '';
			if($class[0]=='S' && $class[1]<=3){
				return "OLC";
				}
			else if(strlen($class)>3){
				for($n=3; $n<strlen($class); $n++){

				$comb.=$class[$n];
				}
				return $comb;
				}
			else return false;

			}
		else return false;
		}
	function acreq($class){
		global $conn;
		//Checking if the class exists
		if(!$this->is_class($class)){
			echo "Invalid Class";
			return false;
			}
			$classname = $class[0].$class[1];
			$classcomb = $this->comb_class($class);

		//Getting requirements of the class
		//Getting all requirements of the
		$schoolID = $this->reg_school();
		$req = mysqli_query($conn, "SELECT * FROM reg_requirements WHERE type='acreq' AND
		(school=$schoolID AND class='$classname' AND combination='$classcomb')
		OR (ISNULL(school) AND class='$classname' AND ISNULL(combination))
		OR (school='$schoolID' AND class='$classname' AND ISNULL(combination))
		OR (school='$schoolID' AND ISNULL(class) AND ISNULL(combination))
		") or die(mysqli_error($conn));

		$input_status = array();

		while($reqdata = mysqli_fetch_assoc($req)){
			$reqname = $reqdata['name'];
			$required = $reqdata['required'];
			$label = $reqdata['label'];
			$fieldname = $reqname.$reqdata['dist'];
			//Going to handle form submission
			if(isset($_POST['form4'])){
				//Form 4 is submitted
				//Here we have to check all requirements

				if( ($required==1 && !empty($_POST[$fieldname]) ) || ( $required==0)){
					if(method_exists($this, $reqname)){
						$regdata = $this->$reqname("validate", $fieldname, $required, $label);

						if($regdata){
							//Going to create new user object to get the current user's ID
							$myUser = new user();
							$userID = $myUser->id();
							echo $regdata;
							//Putting reqdata on the db
							//We are going to first check if the user has already set that record
							$reqexiq = mysqli_query($conn, "SELECT id FROM reg_data WHERE student='$userID' AND name='$fieldname'") or die(mysqli_error($conn));
							if(mysqli_num_rows($reqexiq)>=1){
								//Going to update
								$reqq = mysqli_query($conn, "UPDATE reg_data SET value='$regdata' WHERE student='$userID' AND name='$fieldname'") or die(mysqli_error($conn));
								$input_status = array_merge($input_status, array($fieldname=>1));
								}
							else{
								echo "fdiuj";
								//Here we do INSERT query because record never existed
								$reqq = mysqli_query($conn, "INSERT INTO reg_data(student, name, value) VALUES ('$userID', '$fieldname', '$regdata')") or die(mysqli_error($conn));
								$input_status = array_merge($input_status, array($fieldname=>1));
								}
							}
					}
					else echo "No controller associated with $reqname";
					}else{
						echo "<p>Please fill in all required inputs<br /></p>";
						}


				}

			if(method_exists($this, $reqname)){
				$this->$reqname("display", $fieldname, $required, $label);
				}
			else echo "No controller associated with $reqname";
			}

		//Checking if all requirements were set in successively
		if(count(array_keys($input_status, 1)) == mysqli_num_rows($req)){
			return true;
			}

		/***************************************************************************/
		/* Here we're going to loop through all academic requirements which are for whole school,
		/* whole class level, whole combination and specifically the class. This means that school could set
		/* a requirement for all students, or students in specific academic year or specific combinatiion or just class as s5mpc
		/*
		/*
		/* After getting requirements, we are going to call functions which handle every requiremnt
		/***************************************************************************/
		}
	function log($request, $name, $reason=''){
		/********************************************/
		/*	This function  logs a registration process
		/*	That is any chane in the registration process
		/*	$request is the ID for the request we're logging
		/*	$name is the name of the process
		/*	$reason is the optional comment on the process like age, marks, or. llfcjd
		/***********************************************/
		global $conn;
		$request = (int)$request;
		$name = mysqli_real_escape_string($conn, $name);
		$reason = mysqli_real_escape_string($conn, $reason);

		if(!is_numeric($request)) return false;
		$logq = mysqli_query($conn, "INSERT INTO reg_progress(requestID, name, reason) VALUES($request, \"$name\", \"$reason\")") or die(mysqli_error($conn));
		if($logq) return true;
		else return false;
		}


	function prev_school($status, $name='', $required, $label=''){
		global $conn;
		$errors = array();
		if($status=='validate'){
			if(isset($_POST[$name])){
				//Checking if field was set=>required status;
				if( ($required==1 && !empty($_POST[$name]) ) || ( $required==0)){
					$submited_data = mysqli_real_escape_string($conn, $_POST[$name]);

					//Going to validate the school name
					if(preg_match("/^[a-zA-Z ]*$/", $submited_data)){
						//Here we are going to put in the database
						return $submited_data;
						}else{
							$errors = array_merge($errors, array("Special Characters are not allowed in school name"));
							}

					}else{
						$errors = array_merge($errors, array("Please enter your previous school name<br />") );
						}
				}else{
			}
		}else{
		?>
		<label><?php echo !empty($label)?$label:"Enter your previous school:" ?></label>
        <input type="text" maxlength="128" name="<?php echo $name; ?>" <?php echo $required==1?"required":""; ?>>
        <?php
		if(!empty($errors)){
			?>
            <div class="error">
            <?php
			for($n=0; $n<count($errors); $n++){
				echo "<p>$errors[$n]</p>";
				}
			?>
            </div>
            <?php
			}
	}
		?>
        <?php
	}

	function prev_marks($status, $name='', $required, $label=''){
		global $conn;
		if($status=='validate'){
			//Validating Marks on 100%
			if(!empty($_POST[$name])){
				$prevmarks = mysqli_real_escape_string($conn, $_POST[$name]);
				//Validating
				$prevmarks = (int)trim($prevmarks);
				if(is_numeric($prevmarks) && $prevmarks>0 && $prevmarks<100){
					return $prevmarks;
					}
				else echo "<p>Your marks should be made by numbers only<br />
				Marks should be greater than zero and less 100</p>";
				}
			}else{
		?>
		<label><?php echo !empty($label)?$label:"Enter your previous marks %:" ?></label>
        <input type="number" min="0" name="<?php echo $name; ?>" max="100"  <?php echo $required==1?"required":""; ?>>
        <?php
			}
		}
	function attended_toddler($status, $name='', $required, $label=''){
		global $conn;
		if($status=='validate'){
			if(isset($_POST[$name])){
				$bool = mysqli_real_escape_string($conn, $_POST[$name]);
				return $bool;
				}
			}else{
		?>
		<p><?php echo !empty($label)?$label:"Have you attended toddler (Y/N):" ?></p>
        <div>
            <input type="radio" name="<?php echo $name; ?>" value="yes" id="<?php echo $name."1"; ?>">
            <label for="<?php echo $name."1"; ?>">Yes</label>
            <input type="radio" name="<?php echo $name; ?>" value="no" id="<?php echo $name."2"; ?>">
            <label for="<?php echo $name."2"; ?>">No</label>
        </div>
        <?php
			}
		}

	function nindex($status, $name='', $required, $label=''){
		global $conn;
		$examRes = WEB::getInstance('examres');
		if($status=='validate'){
			$index = trim(strtoupper(mysqli_real_escape_string($conn, $_POST[$name])));
			if($examRes->validatecode($index)){
				return $index;

			}else{
				//Badly formed index number
				echo "Your registration code seems to be wrong, please repeat it again.<br />";
				}
			}else{

		?>
        <label for="nindex"><?php echo !empty($label)?$label:"Enter National Exam Student Index:" ?></label>
        <input type="text" maxlength="15" min="10" name="<?php echo $name; ?>" <?php echo $required==1?"required":""; ?>>
        <?php
			}
		}

	}
class req{
	//This class will help in inputing data on requirements by schools

	}
//$schoolObj = $schools = WEB::getInstance("school");



?>
