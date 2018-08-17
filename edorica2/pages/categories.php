<?php
//Getting school object
$mySchool = WEB::getInstance("school");

?>
<div class="courses">

	<h1 class="page-title">Rwanda School Categories</h1>

</div>

<div class="cat_page">

	<div class="cat_container"><?php

		$dedata  = debug_backtrace();
		$dedata = array_shift($dedata);
		$catquery = mysqli_query($conn, "SELECT * FROM category_def	ORDER BY rank DESC") or die("$dedata[file]:$dedata[line] - ".mysqli_error($conn));

		$cat_num = mysqli_num_rows($catquery);

		if($cat_num<1){
			echo "No categories found<br />Come back soon";
			}

		else{
			//echo "<div class='cats_found'><em class='no-dec'>$cat_num categories found!</em></div><br />";

			$n = 0;

			while($cats_data = mysqli_fetch_assoc($catquery)){					

				$n++; //Counter, used for heading
				$current_cat = $cats_data['name'];

				//Querying number of schools with this category because every school has its own-unique category
				$dbcat =$current_cat;
				$catSchools = array_values(array_unique($mySchool->schoolsWithCat($dbcat)));

				$maxSc = 20; //Maximum number of schools with cat that are to be displayed

				$catLink = $mySchool->catLink($page->db2URL($cats_data['pname'], 'category_def'));

				$num_schools_with_cat = count($catSchools);
				?>

                <div class="cont-summary">

                   	<h<?php echo $n; ?> class="cat_header">
                   		<em class="no-dec">
	                   		<a href="<?php  echo $catLink; ?>">
	                   			<?php echo ucwords(str_ireplace("_", " ", $cats_data['pname'] )); ?>
	                   		</a>
                    	</em>
					</h<?php echo $n; ?>>
                    <?php
					//If there are schools then we have to list them: name and location

					if($num_schools_with_cat>=1){

						for($ns = 0; $ns<$num_schools_with_cat && $ns<=$maxSc; $ns++){
							?><div class="school_with_cat"> <?php //Putting every school in its own div
							$schoolID = $catSchools[$ns];

							$scquery = mysqli_query($conn, "SELECT * FROM schools WHERE id=$schoolID") or die("".mysqli_error($conn));
							$scdata = mysqli_fetch_assoc($scquery);
							$schoolName = $scdata['name'];

					?>
					<ul>
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
                    <?php
                        echo "</div>"; //closing schools_with_cat

						}

					}

					?>

                        <ul class="cat_status">

                       		<li class="cta view_more_cat shicon">
                            	<a href="<?php echo $catLink; ?>">View More <i class="hovercon fa  fa-arrow-right"></i></a>
                            </li>

                        	<li>
                            	<?php
                                	echo $num_schools_with_cat." ";
									if($num_schools_with_cat==1) echo ucwords(str_ireplace("_", " ", $current_cat));
									else echo ucwords(str_ireplace("_", " ", $cats_data['pname']));
								?>
                            </li>

                        </ul>

                        <div class="clear"></div>

                    </div>

                    

                    <?php

					};

			}

	?></div>
    </div>

</div>