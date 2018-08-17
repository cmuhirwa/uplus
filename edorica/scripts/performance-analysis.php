<?php
//Performance analysis module
$School = WEB::getInstance("school");
$basket = WEB::getInstance("basket");
$Comb = WEB::getInstance("combination");
//Checking if in basket there is school's information
$scdata = $basket->get("school");
if(!empty($scdata)){
	//Here school's data was set
	//Checking if exams code was set
	if(!empty($scdata['id']) && !empty($scdata['code'])){
		$scid = $scdata['id'];
		$scode = $scdata['code'];
		//Here exam code is set
		?>
			<div class="mod perfanalysis">
		<?php
		echo '<h6 class="fmodtitle">National Exams Performance</h6>';

		//Getting classes
		$combs = $School->combinations($scid);

		?>
			<div class="perfcont">
		<?php
		//Forming school class indexes
		for($n=0; $n<count($combs); $n++){
			$combexcode = $Comb->excode($combs[$n]);


			echo "<a rel='nofollow' href='".$School->classExLink($scode.''.$combexcode.''.$combs[$n])."'>$combs[$n]</a>";
		}
		?>	</div>
			</div>
		<?php
	}else{
		//All data is not set
		echo "No code associated with this school, Please comment it down we'll get marks as soon as possible";
	}
}else{
	//This module is loaded not on a school page.
}
//$School->code2school("0303030");
?>