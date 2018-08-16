<?php
class combination{
	function link($name){
		global $school_combinations, $edorica, $level;
		$combLink = $school_combinations."/".strtolower($name);
		$combLink = $edorica->getFile($combLink, $level);
		
		return $combLink;
		}
	function type($name){
		//Function to check combination's type
		global $conn;
		$ctq = mysqli_query($conn, "SELECT type FROM comb_type WHERE combName='$name'");

		$ctd = mysqli_fetch_assoc($ctq);

		return $ctd['type'];
	}
	function displaymode($combname){
		global $conn;
		//Function which takes short combinname and find the way it's printed

		//Checking if this is real combination
		if($this->is_comb($combname)){
			$query = mysqli_query($conn, "SELECT displaymode as mode FROM combinations_def WHERE combName = \"$combname\" LIMIT 1");
			$data = mysqli_fetch_assoc($query);

			return $data['mode'];
		}
		else return false;	
	}
	function combAuth($combName){
		//Function to return combination's authority like REB
		global $conn;

		//Getting comb type
		$type = $this->type($combName);
		if(empty($type)){
			return false;
		}

		//Getting type's autority
		$query =  mysqli_query($conn, "SELECT authority FROM category_type JOIN  comb_type ON type = name WHERE combName='$combName'") or die(mysqli_error($conn));
		$auths = array();

		while ($data = mysqli_fetch_assoc($query)) {
			$auths = array_merge($auths, array($data['authority']));
		}
		return array_unique($auths);

	}
	
	function taught($name){
		//Where the combination is taught
		global $conn;
		$query =  "SELECT category FROM combinations_def WHERE combname='$name'";
		
		$combcatq = mysqli_query($conn, $query) or die(mysqli_error($conn));
		
		$combcats = mysqli_fetch_assoc($combcatq);
		$catsin = $combcats['category'];

		if(empty($catsin)) return 0;
		
		$catsq = mysqli_query($conn, "SELECT name FROM category_def WHERE id IN ($catsin)") or die(mysqli_error($conn));
		$ncats = array();
		while($catsd = mysqli_fetch_assoc($catsq)){
			$ncats = array_merge($ncats, array($catsd['name']));
			};

		return $ncats;
		
	}
	function combSchools($combination){
		//This function returns the array containing schools' ID of schools teaching $combinations
		global $conn;
		
		$query = "SELECT school FROM schoolcombinations WHERE combination='$combination'";
		$query = mysqli_query($conn, $query) or die(mysqli_error($conn));

		$schools =array();
		while ($data = mysqli_fetch_assoc($query)) {
			$currentSchool = $data['school'];
			if(!empty($currentSchool)) $schools = array_merge($schools, array($currentSchool));
			
		}
		return $schools;
	}
	
	function is_comb($combname){
		//Function to check if $combname is a combination
		global $conn;
		$query = mysqli_query($conn, "SELECT combName FROM combinations_def WHERE combName=\"$combname\"") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);
		if(!empty($data)){
			return $data['combName'];
		}else return false;
	}
	public static function allCombs(){
		//Returning all combinations
		global $conn;
		$query = mysqli_query($conn, "SELECT combName FROM combinations_def") or die(mysqli_error($conn));
		$combs = array();

		//Looping through results - combinations
		while ($data = mysqli_fetch_assoc($query)) {
			$combs = array_merge($combs, array($data['combName']));
		}
		return $combs;
	}
	function combs_taught($catname){
		//This function returns combinations taught in a category
		/*********************************************************/
		/*	We scan the id of the category with the name provided
		/*	we get combination where category is like the categoryID
		/***********************************************************/
		global $conn;

		$catname = str_ireplace(" ", "_", $catname);
		
		$query = "SELECT id FROM category_def WHERE  name LIKE '%$catname%'";
		$catq = mysqli_query($conn, $query) or die(mysqli_error($conn));
		$catdata = mysqli_fetch_assoc($catq);
		$catID = $catdata['id'];
		if($catID){
			//We are going to get combinations with this catID
			$comq = mysqli_query($conn, "SELECT * FROM combinations_def WHERE category LIKE '%$catID%'")or die(mysqli_error($conn));
			
			//Checking if there are combinations with catID found in the database
			if(mysqli_num_rows($comq)){
				//Looping through all combinations and saving them in an array
				$catcombs = array(); //For storing category combs
				while($combdata = mysqli_fetch_assoc($comq)){
					$combname = $combdata['combName'];
					$catcombs = array_merge($catcombs, array($combname));
					}
					return $catcombs;
			}
			else{
			//Here There are no combinations associated with the category
			return false;
			}
			
			}else{
			//Here category does not exist
			return false;
			}
	}
	function comb_name($name){
		//Function to return combination name out of letter
		global $conn;
		$comq = mysqli_query($conn,  "SELECT * FROM combinations_def WHERE combName='$name'") or die(mysqli_error($conn));
		$comq = mysqli_fetch_assoc($comq);
		$full_name = $comq['des'];
		return $full_name;
	}
	function excode($comb){
		//Functionto return addition combination exam code
		global $conn;
		$query = mysqli_query($conn, "SELECT excode FROM combinations_def WHERE combName = '$comb' LIMIT 1") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);
		if(!empty($data['excode'])) return $data['excode'];
		}

	}
?>