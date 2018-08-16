<?php
$page = new page();



//This file is FOR SEO and it will generate contents in meta tag with nam edescription;
$pagename = $page->dbname;
$item = $page->name;

$metadata = '';
	// var_dump($item);
	// if($item){
	// 	$metadata = $item.",";
	// }

$metadata .= "Schools, Rwanda, students, registration, admission, exam-results, secondary schools, secondary, primary schools, nursery schools, online registraion"; //Default Keywords

$metaq = mysqli_query($conn, "SELECT keywords FROM pages WHERE name=\"$pagename\"") or die(mysqli_error($conn));

if(mysqli_num_rows($metaq)){
	$metdata = mysqli_fetch_assoc($metaq);
	
	if(!empty( $metdata['keywords'])) $metadata = $metdata['keywords'];
	}
else{}
	echo $metadata;

