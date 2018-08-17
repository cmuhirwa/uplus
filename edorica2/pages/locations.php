<?php
$mySchool = WEB::getInstance("school");
include_once "scripts/location.php";
?>
<h1 class="stitle">Locations of Rwandan Schools</h1>

<p>	

	Stop Hunting the school in your preferred location, We've brought them together for you to get yours easily.

    You Will View Provinces, which you can browse up to districts.

</p>

<p>

	Rwanda has five province, and 30 district. Schools are everywhere in the districts, <?php echo _SITE_NAME; ?> aims at providing all schools' information.

    Browse schools and get information you want about the school.

</p>

<div class="schools_cat_listing">

<?php

	$provinces = location::provinces(); //Getting Provinces

	//Displaying The Provinces' Quick Links
	echo "<div class='modmetacont'>";
	for ($n = 0; $n<count($provinces); $n++) {
		$provname = $provinces[$n];
		//Counting schools in province
		$nprov = count($mySchool->searchLocation($provname, 'province'));
		?>
		<div class="modmeta">
			<p class="metahead"><a href="#<?php echo $provname; ?>"><?php echo $provname ?></a></p>
			<p class="mometacont"><?php echo $nprov; ?> schools</p>
		</div>
	<?php
	}
	echo "</div>"; //Closing Mod meta cont's div

	for($n = 0; $n<count($provinces); $n++){

		$counter = $n;
		$provname = $provinces[$n];
		?>
        <div class="course-cont cont-column" id="<?php echo $provname; ?>">
        	<h<?php echo 1+$counter ?> class="cat_header">
        		<a href="<?php echo location::provlink($provname); ?>"><?php echo ucwords($provname); ?></a>
        	<?php echo "</h".(1+$counter); ?>>

        <?php

		//Selecting schools in the province
		$provSchools = $mySchool->searchLocation($provname, 'province');

		//Displaying schools in province
		for($ns =0; $ns<count($provSchools); $ns++){
			$scdata = $provSchools[$ns];
			$schoolName = $scdata['name'];
			?>
            <ul class="school_with_cat">
            	<li>
                	<a href="<?php echo $mySchool->link($scdata['id']); ?>">
                		<?php echo $schoolName; ?>
            		</a>
                </li>

            </ul>

            <?php

			}

		

		?>

        </div>

        <?php

	};

?>

</div>