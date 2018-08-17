<?php
	global $conn, $page, $level, $exam_results, $school_page, $slocation;
	$Module = WEB::getInstance('module');
	$Myschool = WEB::getInstance("school");
?>
<div class="homewelcome">
	<div class="getres-cont">
		<div class="getres">
			<p>
				Looking for National exams results for 2017?
			</p>
			<form method="POST" action="<?php echo $exam_results; ?>">
				<input type="text" name="regcode" type="text" max="20" placeholder="Enter your code" class="flat-input" id="exres-input" required="required">
				<input type="hidden" name="subt" type="hidden">
		        <button class="exelem flat-btn" type="submit">Get Result</button>
			</form>
			<p>REB, WDA, TTC</p>
		</div>
	</div>
	<div class="exp-home">
		<p>Here looking for school, you can choose category you want, school teaching course, or school located in a province</p>
	</div>
	<div class="tabs home-show-tabs">
		<div class="tab-elem">
			<div class="tab-head"><h3><a href="<?php echo $Myschool->catLink(); ?>">Categories</a></h3></div>
			<div class="tab-cont">
				<div class="pro-ico">
					<i class="fa fa-binoculars fa-3x"></i>
				</div>
				<ul class="pro-elems">
		            <li><a href="<?php echo $Myschool->catLink("secondary-schools"); ?>">Secondary schools</a></li>
		            <li><a href="<?php echo $Myschool->catLink("universities"); ?>">Universities</a></li>
		            <li><a href="<?php echo $Myschool->catLink("training-centers"); ?>">Training centers</a></li>
		        </ul>
		        <div class="clear"></div>
			</div>
		</div>
		<div class="tab-elem">
			<div class="tab-head"><h4><a href="<?php echo  $Myschool->courseLink(); ?>">Courses</a></h4></div>
			<div class="tab-cont">
				<div class="pro-ico">
					<i class="fa fa-pencil fa-3x"></i>
				</div>
				<ul class="pro-elems">
		            <li><a href="<?php echo $Myschool->courseLink("sciences-humanities"); ?>">Sciences and Humanities</a></li>
		            <li><a href="<?php echo $Myschool->courseLink("technical-studies"); ?>">Technical Courses</a></li>
		            <li><a href="<?php echo $Myschool->courseLink("arts-training"); ?>">Arts and Training</a></li>
		        </ul>
			</div>
		</div>
		<div class="tab-elem">
			<div class="tab-head">
				<h5><a href="<?php echo $slocation; ?>">Location</a></h5>
			</div>
			<div class="tab-cont">
				<div class="pro-ico">
					<i class="fa fa-globe fa-3x"></i>
				</div>			
				<ul class="pro-elems">
					<li><a href="">Kigali City</a></li>
					<li><a href="">South Province</a></li>
					<li><a href="">West Province</a></li>
				</ul>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>