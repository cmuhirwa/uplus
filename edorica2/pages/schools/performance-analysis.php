<?php
	//Getting schools data
	$School = WEB::getInstance("school");
	$Examres = WEB::getInstance("Examres");

	$scname = ucwords(str_ireplace("_", " ", str_ireplace("-", " ",  $page->getpath()['call_parts'][1] )) );
	$scdata = $School->getSchool($scname, 'name', "*");
	$scid = $scdata['id'];
	$code = $scdata['code'];

	$scombs = $School->combinations($scid);
	
	$classes = $Examres->classes($code);

	//Checking classes we have the exams results stored

?>
<div class="intro-head">
	<h1 class="hpage-title title"><?php echo $scname; ?>'s national exam performance</h1>
</div>
<div class="box">
	<p>This page shows the perfomance of <?php echo $scname; ?> in 2017 National exams.<br />It is basic and should be not used for school judging but for school's performance insight<br />It is made to only help summarize the results in National exams to help school administrators, parents, students get idea of school's academic performance based on previous students and some standardized exams</p>
</div>
<div class="performance-cont">
	<?php
		if(count($classes) > 0){
			?>
				<div class="perf-classes">
					<?php
						foreach ($classes as $classname => $class_info) {
							$classcount = $class_info['num'];
							$classlevel = $class_info['level'];
							?>
							<div class="card">
								<div class="img-card">
									<img src="<?php echo $page->getFile("images/class-small.png", $level); ?>">
								</div>
								<div class="perf-summ">
									<p><?php echo "$classlevel ".$classname; ?></p>
									<p><?php echo $classcount; ?> students</p>
									<div class="perf-class-cta">
										<a class="btn class-cta-btn" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
										<div class="overcome-cta"></div>
									</div>
								</div>

							</div>
							<?php
						}
					?>
				</div>
				<div class="perf-analysis-cont">
					<?php
						foreach ($classes as $classname => $class_info) {
							$classcount = $class_info['num'];
							$classlevel = $class_info['level'];
							$classcode = $class_info['classcode'];
							?>
							<div class="perf-analy-card card" id="<?php echo $classname ?>">
								<!-- <div class="img-card">
									<img src="<?php echo $page->getFile('images/class.png', $level); ?>">
								</div> -->
								<div class="card-cont">
									<div class="perf-text">
										<div class="perf-header">
											<p><?php echo "$classlevel $classname - ".$School->comb_name($classname); ?></p>
											<p><?php echo "$classcount students"; ?></p>
										</div>
										<div class="analy">
											<?php
												var_dump($Examres->classperformance($classcode));
											?>
										</div>
									</div>
									
								</div>
							</div>
							<?php
						}
					?>
				</div>
			<?php
		}else{
			?>
				<div class="card">
					<p>We could not find classes of this school. Try to contact us with classes</p>
				</div>
			<?php
		}
	?>
	
</div>

<!-- <p><a href="http://www.facebook.com/edorica">Visit our Facebok</a> to know when!</p> -->