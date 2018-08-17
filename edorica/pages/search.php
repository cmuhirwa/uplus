<?php
//This is the page to handle searches
//Getting query
$edorica = WEB::getInstance('web');
$page = WEB::getInstance('page');
$School = WEB::getInstance('school');
$Module = WEB::getInstance("module");
$getvars = $page->get;
$query = $getvars['q']??null;
include_once($reg_functions);

?>
<h1 class="page-title">Search schools</h1>
<div class="search-page">
	<?php
		$Module->loadModule("adv-search");
	?>
</div>	

<?php
	$schools = array();
	if(!empty($query) && $_SERVER['REQUEST_METHOD'] == 'GET'){
		//Searching in the database of schools
		$sql = "SELECT * FROM schools WHERE schools.name LIKE \"%$query%\"";
		$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		$nSchools = mysqli_num_rows($query);	
		if($nSchools == 0){
			echo "Sorry, we have not found any school matching your query, try another word.";
		}else{
			//Looping through all the schools			
			while($data = mysqli_fetch_assoc($query)){
				$schools[] = $data;
			}
		}
	}else if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($getvars['type']) && $getvars['type'] == "adv"){
	//Advanced Search!	
	$prov = $getvars['province']??null;
	$course = $getvars['course']??null;
	$category = $getvars['cat']??null;

	//TODO: improve querying system
	$catquery = 1; ////Initializing query for build
	if(!empty($category)){
		$sc_with_cat = $School->schoolsWithCat($category);
		$scatq = implode($sc_with_cat, ',');
		
		$catquery = "schools.id LIKE ".implode($sc_with_cat, ' OR schools.id LIKE ');
		$catquery = rtrim($catquery, " OR schools.id LIKE ");
	}


	$coursequery = 1 ; //Initializing query for build
	if(!empty($course)){
		$sc_with_course = $School->schoolsWithCourse($course);
		$scourse = implode($sc_with_course, ',');
		$coursequery = "schools.id LIKE ".implode($sc_with_course, ' OR schools.id LIKE ');
		$coursequery = rtrim($coursequery, " OR schools.id LIKE ");
	}
	//coursequery should not be empty
	$coursequery = !empty($coursequery)?$coursequery:1;	
	$sql = "SELECT * FROM schools LEFT JOIN location ON schools.location = location.id WHERE location.province LIKE \"%$prov%\" AND ( $coursequery OR $catquery )";

	$query = mysqli_query($conn, $sql) or die("Cant advance the search: ".mysqli_error($conn));
	while ($data = mysqli_fetch_assoc($query)) {
		$schools[] = $data;
		// echo "$data[name]<br />";
	}
	}

	$found_schools = $schools;
	if(count($schools)){
		$nSchools = count($schools);
		?>
			<div class="results_summary">
				<p class="count-results"><?php echo $nSchools; ?> school<?php echo $nSchools>1?"s":''; ?> found</p>
			</div>
			<div class="search-results">
				<?php
					foreach ($found_schools as $key => $school) {
						$scname = $school['name'];
						$scid = $school['id'];

						//Getting school's link
						$slink = $School->link($scname);
						?>
							<div class="search-res-elem school-found">
								<p class="res-link"><a href="<?php echo $slink; ?>"><?php echo $scname; ?></a></p>
								<p class="sc-location-disp">Location: <?php echo  $School->location($school['location']); ?></p>
								<div class="search-cta"><?php $School->qcta($scid); ?></div>
							</div>							
						<?php
					}
				?>
			</div>
		<?php
	}
?>