<div class="promocont">
<?php
global $conn;
$current_school=1;
$School = WEB::getInstance("school");
$mySchool = WEB::getInstance("school");
$schoolObj = WEB::getInstance("school");


//Importing external file resourses sp that the module will work well.
WEB::require('css/slick/slick.css', 'head');
WEB::require('js/jquery.js', 'foot');
WEB::require('js/slick/slick.min.js', 'foot');
WEB::require('js/promoslider.js', 'foot');


#Checking Number of Schools In database that will be promoted

$school_query_result = mysqli_query($conn, 'SELECT id FROM `schools` ORDER BY time ASC');


if(!$school_query_result){ echo "Could not select Schools for promotion ".mysqli_error($conn);}

$schools_promo=mysqli_num_rows($school_query_result);

$num = 6;

$schools = array();

for ($j = 0 ; $j < $num; ++$j){

	$bschool = rand(1, $schools_promo);

	//Getting school's Data
	$school = $mySchool->getSchool($bschool, 'id', '*');
	$schools[] = $school;
	$id = $school['id'];
	$img  = $edorica->getFile($school['image'], $level);

	$scombs = $School->combinations($school['id']);
	//If school has no combination then lets skip
	if(!$scombs){
		--$j;
		continue;
	}

	$registerlink = $mySchool->registerlink($id, 'id');
	?>

	<div class="promo_container box">

			<div class="title promo_title"><a href="<?php echo $mySchool->link($school['id']); ?>"><?php echo $school['name'];?></a></div>

				<div class="promo_content">

				<div class="promo_images">

				</div>

				<div class="promo_description">

					<ul>
						<li>Combinations: <?php echo $School->printcombs($scombs);?></li>
						<li>Location: <?php echo $schoolObj->slocation($conn, $school['location']); ?></li>
						<!-- <li>Facilities: <?php echo $school['facilities']; ?></li> -->

					</ul>

				</div>

				<div class="promo_actions">

				<?php $mySchool->qcta($id); ?>

			  </div>

			</div>

	</div>

	<?php } ?>
</div>
<div class="clear"></div>
	<div class="slider-controllers hidden">
		<div class="slick-btn slick-prev-btn">
			<p><span class="vert-middle">Prev</span><span class="fa fa-chevron-circle-left"></span></p>
		</div>
		<div class="slick-btn slick-next-btn">
			<p><span class="fa fa-chevron-circle-right"></span><span class="vert-middle">Next</span></p>
		</div>
	</div>
