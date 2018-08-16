<?php
$Myschool = WEB::getInstance("school");
global $school_page;
?>



<div class="cat qcc">

    <div class="cat-title">Courses</div>

    <div class="courses">

        <ol>
            <li><a href="<?php echo $Myschool->courseLink("sciences-humanities"); ?>">Sciences and Humanities</a></li>
            <li><a href="<?php echo $Myschool->courseLink("technical-studies"); ?>">Technical Courses</a></li>
            <li><a href="<?php echo $Myschool->courseLink("arts-training"); ?>">Arts and Training</a></li>
        </ol>

        <a href="<?php echo  $Myschool->courseLink(); ?>">

            <div class="gotoschools">Courses <span class="fa fa-angle-right gicon"></span></div>

        </a>

    </div>

</div>



<div class="cat qcc">

    <div class="cat-title">Categories</div>

    <div class="courses">

        <ol>
            <li><a href="<?php  echo $Myschool->catLink("universities"); ?>">Universities</a></li>
            <li><a href="<?php echo $Myschool->catLink("secondary-schools"); ?>">Secondary Schools</a></li>
            
            <li><a href="<?php echo $Myschool->catLink("primary-schools"); ?>">Primary Schools</a></li>
            
        </ol>

        <a href="<?php echo $Myschool->catLink(); ?>">
            <div class="gotoschools">Categories <span class="fa fa-angle-right gicon"></span></div>
        </a>
    </div>
</div>
<div class="clear"></div>