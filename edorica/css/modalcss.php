<?php
$gabriela = $edorica->getFile("css/fonts/Gabriela-Regular.ttf", $level);

$gisha = $edorica->getFile("css/fonts/gisha.ttf", $level);
$oxygen = $edorica->getFile("css/fonts/oxygen.otf", $level);
$pnova = $edorica->getFile("css/fonts/proxima-nova.otf", $level);


?>
 
<link rel="stylesheet" type="text/css" href="<?php echo $edorica->getFile("css/facss/font-awesome.min.css", $level); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $edorica->getFile("css/slick/slick.css", $level); ?>"/>

<link rel="stylesheet" type="text/css" href="<?php echo $edorica->getFile("css/layout.css", $level); ?>"/>
<style>
	<?php 
// 	include_once("css/facss/font-awesome.min.css");
// 	include_once("css/layout.css");
	include_once "css/position.php";
	?>

	@font-face{
		src:url(<?php echo $edorica->getFile("css/fonts/PT_Serif-Web-Regular.ttf", $level); ?>);
		font-family:serifa;
	}
	@font-face{
		src:url(<?php echo $edorica->getFile("css/fonts/PT_Sans-Web-Regular.ttf", $level); ?>);
		font-family:sansa;
	}
	@font-face{
		src:url(<?php echo $edorica->getFile("css/fonts/OpenSans-Regular.ttf", $level); ?>);
		font-family:"Open Sans";
	}
	@font-face{
		font-family: gabriela;
		src: url(<?php echo $gabriela; ?>);
	}
	@font-face{
		font-family: gisha;
		src: url(<?php echo $gisha; ?>);
	}
	@font-face{
		font-family: proxima-nova;
		src: url(<?php echo $pnova; ?>);
	}
	@font-face{
		font-family: oxygen;
		src: url(<?php echo $oxygen; ?>);
	}
	div.glyphicon.glyphicon-envelope {
		font-size: 30px;
		color: #E0B025;
	}

	<?php

	//Styling specific pages and/or modules

	if($current_page=="profile"){ ?>		

		@media screen and (min-width:740px){
			.middle{
				margin:2% 0 0 15%;
			}
		}

	<?php }	?>

	<?php
	if($current_page=="checkmail" || $current_page=="recover_password"){
		?>
		.middle{
			background:none;
			padding: 10px;
		}

	<?php } ?>
</style>
<?php

if($current_name == $school || $page->base_page($conn) == $school){
	?>
    <link rel="stylesheet" href="<?php echo $edorica->getFile('css/schools.css', $level); ?>" />
    <?php
	}

?>
<?php
//Creating fx that will help in setting styles with PHP

function setstyle($style){

	?>
    <style>
		<?php echo $style; ?>
	</style>
    <?php
	}

?>
<?php

//Setting styles in school registration page
$mySchool = WEB::getInstance("school");

$myPage = new page();
if($mySchool->isregpage()){
	//Getting registration step
	//Checking step
	$getvars = $myPage->get();

	if(isset($getvars['step']) && $getvars['step']!='' && $getvars['step']<5){

		$process = mysqli_real_escape_string($conn, $getvars['step']);

		}

	else $process=1;

	?>
    <style>
	.process-container.p<?php  echo $process; ?>{
		display: block;

		}
	</style>
    <?php
} ?>

<?php
	//Loading the files that application required to be in head - styles
	
	$headFiles = $edorica->getLoadList('head');

	$headFilesCss = $edorica->getLoadList('headcss');
	$headFilesCss = array_unique($headFilesCss);

	//Merging both file lists
	$headFiles = array_merge(is_array($headFiles)?$headFiles:array(), $headFilesCss);
	
	$counter = count($headFiles);
	for ($n=0; $n<$counter && is_array($headFiles); $n++) {
		$value = $headFiles[$n];
		?>
		<link rel="stylesheet" href="<?php echo $value ?>">
		<?php
	}
?>