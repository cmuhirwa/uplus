
<?php
    global $home, $school, $courses, $exam_results, $edorica, $level, $about, $contact;
?>
<header>

	<a id="logo" href="<?php echo $home; ?>"><img src="<?php echo $edorica->getFile(_LOGO, $level); ?>" alt="<?php echo _SITE_NAME; ?> logo" /></a>

    <nav class="dropdown">

    	<a href="#" id="menu_icon"><i class="fa fa-bars"></i></a>

        <ul class="menu_items">

            <li><a href="<?php echo $home; ?>">Home</a></li>
            <li><a href="<?php echo $school; ?>">Schools</a></li>
            <li><a href="<?php echo $courses; ?>">Courses</a></li>
            <li><a href="<?php echo $exam_results; ?>">Exam Results</a></li>
            <li><a href="<?php echo $about; ?>">About us</a></li>
            <li><a href="<?php echo $contact; ?>">Contact us</a></li>
        </ul>
    </nav>
    <div class="clear"></div>
</header>