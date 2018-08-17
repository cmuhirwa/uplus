<?php
$catname = mysqli_real_escape_string($conn, $current_name);
$catdname = str_ireplace("_", " ", $catname); //Category database name

include_once "scripts/category.php";
//Getting category's print name
global $conn;
$query = mysqli_query($conn, "SELECT * FROM category_def WHERE name=\"$catname\" LIMIT 1") or die(mysqli_error($conn));
$data = mysqli_fetch_assoc($query);

$catpname = $data['pname'];  //category Print name

?>

<h1 class="page-title"><em class="no-dec"><?php echo ucwords($catpname); ?></em> in Rwanda</h1>

<div class="cat_schools cont-summary">

<?php

/*Searching schools in the database with our current category

*$catname is the value of rows in the db

*/
/**********************************************/
/*	Getting schools with category
/*	We'll test the combinations taught in the category and then query schools which teaches those combinations
/*	Combination class provides a method combs_taught($catname) which returns array containing combinaitons taught in category name
/**********************************************/
$mySchool = WEB::getInstance("school");

$myComb = WEB::getInstance("combination");;
$catcombs = $myComb->combs_taught($catname);

if(is_array($catcombs)){
	//If cat combs is array, then we loop through all combinations taught in category
	$combschools = array();
	foreach($catcombs as $combclass => $combname){
		$combschools = array_merge($combschools, $myComb->combSchools($combname));
		$combschools = array_values(array_unique($combschools));

	}
	//Query schools with combinations in the category

	if(count($combschools)>=1){
		//Schools with combination were found
		
		for($nschools=0; $nschools<count($combschools); $nschools++){
			

			$scid = $combschools[$nschools];
			//Here we are going to get the school with such combination
			$scdata = $mySchool->getSchool($scid, 'id');	

			//Checking if school data are not empty
			//The problem could raise when the combination does not have a corresponding school

			if(!empty($scdata)){
				//Extracting data from array to be used easily with HTML outputting
				$schoolName = $scdata['name'];
				?>

				<div class="school_with_cat cat_instance">
					<ul>
						<li class="sc_cat_name shicon">
							 <a href="<?php echo $mySchool->link($scdata['id']); ?>"><?php echo $schoolName; ?></a>
							 <i class="hovercon fa  fa-chevron-circle-right"></i>
						</li>

						<li class="shicon sc_cat_location">
							 <?php echo $mySchool->slocation($conn, $scdata['location']); ?>
							 <i class="hovercon fa fa-location-arrow"></i>
						</li>

					</ul>
				</div>

				<?php

			}else{
				//Here the combination exists but no school associated with it
				}
			}

		}

	else{
		//Here no schools found.
		}

}else{

}
?>
</div>