<?php
	//enforcing HTTPS
	function isSecure() {
	  return
	    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
	    || $_SERVER['SERVER_PORT'] == 443;
	}

	session_start();
	ob_start();
	define("_SETUP", 'setup.php');
	define("_NOT_FOUND", "not_found");
	define("_DB_SEPARATOR", "-"); //constant which specifies how values are separated in the db like student name and the separator is _ if student_name
	define("_SITE_NAME", "Edorica");
	include_once(_SETUP);
	define("_DS", DIRECTORY_SEPARATOR);

	

	define("_LOGO", "images"._DS."edorica.png"); //Site logo path
	$moduleObj = $myModule = WEB::getInstance('module');
	error_reporting(E_ALL);

	$mySchool = WEB::getInstance("school");


	/* This variable will help in including modules*/
	$current_page = $page->dbname;
	$current_name = $page->name;


	//Special pages which are stand alone like APIs
	if($current_page == 'extend'){
		include "pages/api.php";
		die();
	}

	//page level, essential for file inclusion
	$level = $page->level('flevel');

	/* This will allow us to render pages and their modules correctly*/
	if(file_exists($page_settings)){
		include_once($page_settings);
	}
	
	//request path
	$reqpath = trim($_SERVER['REQUEST_URI'], '/');

	if( !isSecure() && $_SERVER['HTTP_HOST'] != "edorica-o.com"){
        $nurl = "https://www.".ltrim($_SERVER['HTTP_HOST'], "www.")."/$reqpath";
        header("HTTP/1.1 301 Moved Permanently");
        header("location:$nurl");
        die();
    };

    //Site meta
    $Meta = WEB::getInstance("sitemeta");
    $title = $Meta->title();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title." | "._SITE_NAME; ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php  echo $page->getFile(_LOGO, $level); ?>" />
	<link rel="canonical" href="https://www.edorica.com/<?php echo $reqpath; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php include("scripts/meta_descriptions.php"); ?>">
	<meta name="keywords" content="<?php include('scripts/meta_keywords.php'); ?>">	
	<meta charset="utf-8">
	<?php include_once('css/modalcss.php');	?>
</head>

<body lang="en">


<?php //Whole site ?>
<div class="site">
	<?php
		//$page->loadPosition("menu");
	if(supported("header", $current_page, $conn)){?>
		<div class="header">
		<!--Header: Logo, NavBar, search, login -->
		<?php
		if(file_exists($header)){
			include($header);}
		?>
		</div>
		 <?php  }else if($page->hasmodule('menu', $page->id)){
				//Checking if the modern module system is applied here!
				?>
				 <div class="header"><?php $page->loadPosition('menu'); ?></div>
				<?php
		 }
		 ?>
		 <div class="after-header"></div>
		
<?php //Promotional Section ?>
		<div class="exam-results-cont">
			<?php $page->loadPosition('promo'); ?>
		</div>

	<?php if(supported("promo", $current_page, $conn)){ ?>
		<div class="promo boxcont">
			<p class="pbtitle">Current Featured School</p>
			<?php
			if(file_exists($promo)){
				include($promo);
			}
			?>
		<div class="clear"></div>
		</div>
		<?php } ?>

<?php //<!--Main Content--> ?>
<div class="content">
<?php if(supported("left", $current_page, $conn)){ ?>
		<!--Left Content-->
		<div class="left">
		<?php if($current_page!=$logout){
		if(file_exists($left)){
				include($left);
				}} ?>
		</div>
		<div class="clear-740px"></div>
<?php } ?>



<?php //<!--Middle Content--> ?>
<?php ?>
<div class="middle">
	<?php
	//Querrying Page Link
	$pname = mysqli_real_escape_string($conn, $current_page);
	$lquery = mysqli_query($conn, "SELECT link FROM pages WHERE name='$pname'");
	if($lquery){
		$ldata = mysqli_fetch_assoc($lquery);
		if($ldata){
			$link_file = $ldata['link'];

			if(file_exists($link_file)){
				//If the file in the database is found
			include_once($link_file);
				}
			else{
				//If the file in the database is not found .. Here I think we will Use Xsml in case database is down
				echo "Page could not be found";
				}
		}
		else{
			//Here link is empty
			}
	}
	else{
		echo "Error Getting Page: <br />".mysqli_error();
		}


	?>
</div>
<div class="middle_clear"></div>
<?php if(supported("right", $current_page, $conn)){
 ?>
		<?php //<!--Right Content--> ?>
		<div class="right">
		<?php
			$page->loadPosition("right");
			if(file_exists($right)||$page->right==1){
			include($right);
			}
		?>
		<div class="clear-740px"></div>
	</div><?php //Closing Right ?>

<?php } ?>
<div class="clear"></div>
<?php //<!--Closing Content--> ?>


<?php //<!--Footer */-->?>
<div class="footer">
	<?php //<!--Sub footer to include some navigation--> ?>
	<div class="sub_footer">
		<div class="foot-right-menu">
			<ul class="footer_quicklinks">
				<li><a href="<?php echo $contact; ?>">Contact Us</a></li>
				<li><a href="<?php echo $about; ?>">About Us</a></li>
				<!-- <li>Our Services</li> -->
			</ul>
		</div>
		<div class="foot-left-menu">
			<ul>
				<li>
					<a target="new" href="http://www.facebook.com/<?php echo _fb_username; ?>">Connect With Us</a>
				</li>
				<li class="fb-like" data-href="http://www.facebook.com/<?php echo _fb_username; ?>" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false">
				</li>
			</ul>
		</div>
	</div>
	<footer><?php echo $site_name; ?> &copy; <?php $year = getdate(); echo $year['year']; ?></footer>
</div>
</div>
<script>
if(navigator.onLine){
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-90167508-1', 'auto');
	ga('send', 'pageview');
}

</script>
<?php
	//Loading the files that application required to be in foot - scripts
	
	$footFiles = $edorica->getLoadList('foot');

	$counter = count($footFiles);
	for ($n=0; $n<$counter && is_array($footFiles); $n++) {
		$value = $footFiles[$n];
		?>
		<script type="text/javascript" src="<?php echo $value ?>"></script>
		<?php
	}
?>

<script type="text/javascript">
	console.log("%c\n Welcome to Edorica, developer! If you find something useful to improve, share with us at <?php echo _CONTACT_EMAIL; ?>! Happy debugging\n ", "padding:4px, font-family:verdana; color:blue; background-color:#ffe")
</script>
</body>
</html>
<?php
	mysqli_close($conn);
	ob_end_flush();
?>
