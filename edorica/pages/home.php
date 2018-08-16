<div class="home-page">

    <div class="site-description">

        <h1 class="h1 fancy-text">Welcome To <?php echo $site_name; ?></h1>

        <p class="standtext">

            Join <?php echo $site_name; ?> to be smart.

            Join us now to take a smart path.

        </p>

    </div>

</div>

<p>

	Realize your dream education, select category or course to get schools, now!

</p>

<?php
	$module = WEB::getInstance('module');
	$module->loadModule("qcc");

?>

<div class="transparentbg">

	<h2 class="h1 fancy-text">Schools access</h2>
    <p>Want to access school or training center for your education and learning? We provide easy way of getting information to schools and also applying to them and other services to help you to your future dream now!</p>

    <div class="notebox">
    	<p class="notehead">How do You access school?</p>
        <blockquote>
        	You can get your <a href="<?php echo $slocation; ?>">school by location</a>, <a href="<?php echo $courses; ?>">courses</a> they teach or even through their <a href="<?php echo $categories; ?>">categories</a> all right here for you.
        </blockquote>
        <p>Use the search below to reach your school easily</p>
    </div>
    <div class="searchbox"><?php $module->loadModule("adv-search"); ?></div>

</div>