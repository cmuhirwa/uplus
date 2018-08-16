<?php

$Comb = WEB::getInstance("combination");

$combname = strtoupper($current_name);

$mySChool = WEB::getInstance("school");

$fullCombName = $mySChool->comb_name($combname);



//Getting info about combination

$comq = mysqli_query($conn, "SELECT * FROM combinations_def WHERE combName='$combname'") or die(mysqli_error($conn));
$comd = mysqli_fetch_assoc($comq);
$combCat = $comd['type']; //Category of combination

//Seacrching course category link
$catcq = mysqli_query($conn, "SELECT name FROM `combinations_def` FULL JOIN courses_def ON type LIKE CONCAT('%', courses_def.search ,'%') AND type='$combCat'") or die(mysqli_error($conn));
$ccat = mysqli_fetch_assoc($catcq);
$ccat = $ccat['name'];
$courseLink = $mySChool->courseLink($ccat);



//Getting number of schools teaching combination
$combschools = $Comb->combSchools($combname);

$nschools = count($combschools);
?>
<h1 class="stitle">Schools in Rwanda with <em class='no-dec' title="<?php echo $fullCombName; ?>">

<?php

	if(preg_match('^science^', $combCat) || preg_match('^human^', $combCat)){
        echo $combname;
    }else echo $fullCombName;

?>

</em></h1>

<div class="comb_quick">
    <div class="course-cont">
        <h2 class="cat_header" title="<?php  echo $fullCombName; ?>"><em class="no-dec"><?php echo $fullCombName; ?></em></h2>

        <ul>
            <li>Course Type: <a href="<?php echo $courseLink; ?>"><?php echo ucwords(str_ireplace("_", " ", $combCat)); ?></a></li>
            <li>Combination code: <?php echo $combname; ?></li>
            <li>Full Name: <?php echo $fullCombName; ?></li>
            <li>Number of Schools: <?php echo $nschools; ?></li>
        </ul>

    </div>

    

    <div class="course-cont">

        <h3 class="cat_header" title="<?php  echo $fullCombName; ?>"><?php echo (preg_match('^science^', $combCat))?$combname:$fullCombName; ?> Schools</h3>

        <?php

		//Getting schools with combination

		for($ns =0; $ns<$nschools; $ns++){
        $scdata = $mySChool->getSchool($combschools[$ns], 'id');        
		$schoolName = $scdata['name'];
		?>

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

        <?php } ?>

    </div>

</div>