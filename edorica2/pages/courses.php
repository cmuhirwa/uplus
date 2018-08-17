<?php
$Comb = WEB::getInstance("combination");
$schools = WEB::getInstance('school');
?>

<div class="courses">
	<h1 class="page-title">Schools By Courses</h1>
	<p>What do you want to study? Which skills do you want to get? Do you need school for your career development?</p>
	<p>Here we have brought you schools to help you in certain courses you want to take and which skills you want to acquire</p>
	<p>All Schools sorted in courses they teach for you to get one of your interest easily</p>
	<br />
</div>

<div class="courses-container cont-columns-cont">
<?php
	$coursequery = mysqli_query($conn, "SELECT * FROM courses_def ORDER BY rank ASC") or die(mysqli_error($conn));
	?>
	<p>
		<?php echo mysqli_num_rows($coursequery)." courses on "; ?><?php $page->printlink($home, _SITE_NAME); ?>
	</p>
<?php
	$ncounter=0;
	while($cdata = mysqli_fetch_assoc($coursequery)){
		if($cdata){
			$ncounter++; //Incrementing Counter
			$name = ucwords(str_ireplace("-", " ", $cdata['name'])); //Removing - for better HTML printing
	 		$name = ucwords(str_ireplace("_", " ", $cdata['name'])); //Removing _ for better HTML printing
		?>

        <div class="course-cont cont-column">
        	<h<?php echo $ncounter; ?> class="cat_header">
        		<a href="<?php echo $schools->courseLink($name); ?>">
        			<em class="no-dec">
        				<?php echo $name; ?>        				
        			</em>
        		</a>
        	</h<?php echo $ncounter; ?>>
            <div class="cbody">
            	<ul>

                <?php
                //Getting schools with current course and displaying them as list

				$name; //Course name			

				//Finding combinations in the course category
				$ccomq = mysqli_query($conn, "SELECT combName FROM `combinations_def` FULL JOIN courses_def ON type LIKE CONCAT('%', courses_def.search, '%') WHERE '$name' LIKE CONCAT('%', courses_def.search, '%')") or die(mysqli_error());	


				$n=0;
				$combschools = array();

				while($combs = mysqli_fetch_assoc($ccomq)){
					//Looping through combs in course category

					$n++;
					$comb = $combs['combName']; //Current combination


					$combschools = array_merge($combschools, $Comb->combSchools($comb));

				};

				//Removing duplicate schools, that's schools which teaches combs in same course-def
				$combschools = array_values(array_unique($combschools));
				

					//Fetching schools					

					$counter=0;

					for($nschools=0; $nschools<count($combschools); $nschools++){

						$counter++;

						$scID = $combschools[$nschools];

						$scq = mysqli_query($conn, "SELECT * FROM schools WHERE id=$scID") or die(mysqli_error($conn));

						$scdata = mysqli_fetch_assoc($scq);

						$schoolName = $scdata['name'];

						

						?>

                        <div class="school_with_cat">

                            <ul class="school_with_cat">

                                <li class="sc_cat_name shicon">

                                    <a href="<?php echo $schools->link($scdata['id']); ?>">

                                        <?php echo $schoolName; ?>

                                    </a>

                                    <i class="hovercon fa  fa-chevron-circle-right"></i> 

                                </li>

                                <li class="shicon sc_cat_location">

                                    <?php echo $schools->slocation($conn, $scdata['location']); ?>

                                    <i class="hovercon fa fa-location-arrow"></i>

                                </li>

                            </ul>

                       </div>

                        <?php

						//We have to list only 15 schools

						if($counter>=15){

							break;

							}

						}//Schools loop closing

					

				?>

                </ul>

            <div class="cat_status">

            	<ul>

                	<?php
						//Displaying view more button if number schools with course is greater that limit of schools --15

						if($counter>15){
							$mySchool = WEB::getInstance("school");
							$courseLink = $mySchool->courseLink($name);		

							?>
		                	<li class="cta view_more_cat shicon"><a href="<?php echo $courseLink; ?>">View More</a>
		                	</li>
	                		<?php 
	                	}
                	?>
                	<?php echo count($combschools)." $name Schools in the database"; ?>

                    <li>

                    	

                    </li>

                </ul>

            </div>

            </div>

        </div>

        <?php

		}

		else echo "No courses already, come soon!";

		};

	

?>

<div class="clear tiny-div"></div>
</div>