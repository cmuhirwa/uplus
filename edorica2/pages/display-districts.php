<?php
	require_once "scripts/location.php";
	$district = ucwords($page->name);

	$mySchool = WEB::getInstance("school");
	$districtSchools = $mySchool->searchLocation($district, 'district');
?>

<div class="contlist">
	<h1 class="title page-title"><?php echo $district; ?> schools</h1>
	<div class="cont-summary">
			<h2 class="cat_header">Schools in <?php echo $district ?></h2>
			<ul>
				<!-- <li>Number of Sectors:  <?php //echo count($districts); ?></li> -->
				<li>Number of schools:  <?php echo count($districtSchools); ?></li>
			</ul>
	</div>
	<div class="cont-summary">
			<ul class="cbody">
				<?php for($n=0; $n<count($districtSchools); $n++){
					$school = $districtSchools[$n];
					$scname = $school['name'];
				?>
				<li class="school_with_cat">
					<a href="<?php echo $mySchool->link($scname); ?>"><?php echo $scname; ?></a>
				</li>
				<?php } ?>
			</ul>
	</div>
	<?php
	?>
</div>
