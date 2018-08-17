<?php

$ccname = $current_name; //Current Course name

$cdname = $ccname; //database course name

$cpname = str_ireplace("-", " ", $cdname);

$cpname = str_ireplace("_", " ", $cpname);

$cpname = ucwords($cpname);
$Comb = WEB::getInstance("combination");

$mySchool = WEB::getInstance("school");
?>





<h1 class="title page-title"><em class="no-dec">Schools teaching <i><strong class="no-dec">"<?php echo $cpname; ?>"</strong></i></em></h1>

		<div class="course-cont">

			<?php

                //Getting schools with current course and displaying them as list

				$name; //Course name

				

				//Current coutse combinations which we will use to find schools with such combinations

				$ccomq = mysqli_query($conn, "SELECT combName FROM `combinations_def` FULL JOIN courses_def ON type LIKE CONCAT('%', courses_def.search, '%') WHERE '$cdname' LIKE CONCAT('%', courses_def.search, '%')") or die(mysqli_error());

				$n=0;
				$combschools = array();

				while($combs = mysqli_fetch_assoc($ccomq)){
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

                                <a href="<?php echo $mySchool->link($scdata['id']); ?>">

                                    <?php echo $schoolName; ?>

                                </a>

                                <i class="hovercon fa  fa-chevron-circle-right"></i> 

                            </li>

                            <li class="shicon sc_cat_location">

                                <?php echo $mySchool->slocation($conn, $scdata['location']); ?>

                                <i class="hovercon fa fa-location-arrow"></i>

                            </li>

                        </ul>

                    </div>

					<?php } ?>

</div>

<div class="options">

<h2>Combinations in <?php echo $cpname; ?></h2>

<?php

//Displaying combinations in this category



$ccomq = mysqli_query($conn, "SELECT combName FROM `combinations_def` FULL JOIN courses_def ON type LIKE CONCAT('%', courses_def.search, '%') WHERE '$cdname' LIKE CONCAT('%', courses_def.search, '%')") or die(mysqli_error($conn));

//We want to make query of combinations under same category that takes all combinations of same categpry together and find schools teaching them

$myComb = WEB::getInstance("combination");

while($combs = mysqli_fetch_assoc($ccomq)){

	$comb = $combs['combName'];

	$combLink = $myComb->link($comb);

	$fullCombName = $mySchool->comb_name($comb); //Getting full combination name

	?>

    <li><a title="<?php echo $fullCombName; ?>" href="<?php echo $combLink; ?>"><?php echo $comb; ?></a></li>

    <?php

	};



?>

</div>