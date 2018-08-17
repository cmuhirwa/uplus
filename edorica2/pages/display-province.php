<?php
	require_once "scripts/location.php";
	$province = ucwords($page->name);

	$mySchool = WEB::getInstance("school");
	$provSchools = $mySchool->searchLocation($province, 'province');

	$districts = location::districts($province);
?>
<div class="contlist">
	<h1 class="title page-title"><?php echo $province; ?> schools</h1>
	<div class="cont-summary">
			<h2 class="cat_header">Schools in <?php echo $province ?></h2>
			<ul>
				<li>Number of Districts:  <?php echo count($districts); ?></li>
				<li>Number of schools:  <?php echo count($provSchools); ?></li>
			</ul>
	</div>
	<?php
		//Looping through districts
		for ($count=0; $count < count($districts); $count++) { 
			# code...
			//Loading schools in district
			$ndis = $districts[$count]['name'];

			?>
			<div class="course-cont cont-column">
				<div class="cat_header">
					<a href="<?php echo location::districtLink($ndis, 0); ?>"><?php echo "$ndis"; ?></a>
				</div>
				<ul class="cbody school_with_cat">
			<?php
			
			$schools = $mySchool->searchLocation($ndis, 'district');
			$nschools = count($schools); //number of schools found under location category
			for($n = 0; $n<$nschools; $n++){
				$cschool = $schools[$n];
				$scname = $cschool['name'];
				?>
					<li class='school_with_cat'>
						<a href="<?php echo $mySchool->link($scname); ?>"><?php echo $scname; ?></a>
					</li>
				<?php
			}
			?></div><?php
		}
	?>
</div>
<div class="clear"></div>