<?php
	$page = new page();
	//This file is FOR SEO and it will generate contents in meta tag with nam edescription;
	$pagename = $page->dbname;
	$pageRealName = $page->name;

	$sql = "SELECT mdescription FROM pages WHERE name=\"$pagename\"";

	$metaq = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	$metdata = mysqli_fetch_assoc($metaq);
	$metadata = $metdata['mdescription'];

	if(!empty($metadata)){		
		echo str_ireplace('$item', "$pageRealName", str_ireplace("_SITE_NAME", _SITE_NAME, $metadata));
	}
	else{
	?>Rwanda Schools information and REB RWANDA exams results. We offer schools online registration and Rwanda schools information with career guidance. Visit Edorica now.<?php } ?>