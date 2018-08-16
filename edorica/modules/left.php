<?php

function courseLink($name=''){

	global $courses, $edorica, $level;

	$clink = $courses."/".$name;

	$clink = $edorica->getFile($clink, $level);

	return $clink;

	}



//Function to give category link

function catLink($name=''){

	global $edorica, $level, $categories;

	

	$catlink = $categories."/".$name;

	$catlink = $edorica->getFile($catlink, $level);

	

	return $catlink;

	}

?>



<div class="cat">

    <div class="cat-title">Courses</div>

    <div class="courses">

        <ol>

            <li><a href="<?php echo courseLink("sciences-humanities"); ?>">Sciences and Humanities</a></li>

            <li><a href="<?php echo courseLink("technical-studies"); ?>">Technical Courses</a></li>

            <li><a href="<?php echo courseLink("arts-training"); ?>">Arts and Training</a></li>

        </ol>

        <a href="<?php echo courseLink(); ?>">

            <div class="gotoschools">Courses <span class="fa fa-hand-o-right gicon"></span></div>

        </a>

    </div>

</div>



<div class="cat">

<div class="cat-title">Categories</div>

<div class="courses">

    <ol>
        <li><a href="<?php  echo catLink("nursery-schools"); ?>">Nursery Schools</a></li>
        <li><a href="<?php echo catLink("primary-schools"); ?>">Primary Schools</a></li>
        <li><a href="<?php echo catLink("secondary-schools"); ?>">High Schools</a></li>
   </ol>

        <a href="<?php echo $school_page; ?>">

            <div class="gotoschools">Schools <span class="fa fa-hand-o-right gicon"></span></div>

         </a>

    </div>

</div>