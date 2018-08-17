<?php
error_reporting(E_ALL);
#Site info
$css="css";
$scripts="scripts";
$site_domain = $current_host = !empty($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'bot'; //If the host is not defined then we thinkk this is a bot

$salt='@#.#@';
$admin_email = "placide@edorica.com";
define("_CONTACT_EMAIL", 'contact@edorica.com'); //Email that users can contact regarding website
define("_SIM_APPLICATIONS", 3);
define("_WEB_ADDRESS", "www.edorica.com");
//Facebook page name to be used in sharing and fb linking
define('_fb_username', 'edorica');

if(!empty($level) && $level == '2'){
	include_once "../scripts/dbcons.php";
	include_once "../scripts/web.php";

	$edorica = new web();
	$conn = $edorica->conn;

	include_once "../scripts/category.php";
	include_once "../scripts/page.php";
}else{
	include_once "scripts/dbcons.php";
	include_once "scripts/web.php";

	$edorica = new web();
	$conn = $edorica->conn;

	include_once "scripts/category.php";
	include_once "scripts/page.php";
}

$flevel = $page->level('flevel');


#links
$site_path="/edorica/";
$getquery="page";
$site_name="Edorica";
$serverquery="";
$home=$serverquery.'/';
$login=$serverquery."login";
$register=$serverquery."register";
$school_page=$serverquery."schools";
$logout=$serverquery."logout";
$profile=$serverquery."profile";
$about = $serverquery."about";
$contact=$serverquery."contact";
$school = $schools_link = $serverquery."schools";
$info=$serverquery."info";
$exam_results=$serverquery."exam-results";
$checkmail = $serverquery."checkmail";
$recover_password = $serverquery."recover_password";
$courses = "courses";
$categories = "school-categories";
$school_combinations = "school-combinations";
$school_register  = $serverquery.'school-register';
$school_requests  = $serverquery.'requests';
$slocation  = $serverquery.'schools-location';
$search  = $serverquery.'search';

#Improved links
$school_page = $schools_link = $edorica->getFile($school_page, $page->level);
$contact = $edorica->getFile($contact, $page->level('flevel'));

$login =  $edorica->getFile($login, $page->level('flevel'));
$register =  $edorica->getFile($register, $page->level('flevel'));
$school = $edorica->getFile($school, $page->level('flevel'));
$school_register = $edorica->getFile($school_register , $page->level('flevel'));
$profile = $edorica->getFile($profile , $page->level('flevel'));
$school_requests = $edorica->getFile($school_requests , $page->level('flevel'));
$exam_results =  $edorica->getFile($exam_results , $page->level('flevel'));
$courses =  $edorica->getFile($courses , $page->level('flevel'));
$about =  $edorica->getFile($about , $page->level('flevel'));

$logout =  $edorica->getFile($logout, $page->level('flevel'));
$search =  $edorica->getFile($search, $page->level('flevel'));
$school_admin_login = $edorica->getFile("sadmin_login", $page->level('flevel'));

$school_admin = $edorica->getFile("manage", $page->level('flevel'));

#Helpers
$school_info='scripts/school_info.php';
$max_promos = 3; //Maximum promo elements
$titles="scripts/titles.php";									//It generates page titles
$page_settings="scripts/page_settings.php";
$functions="functions.php";
$forgot_password="#";
$recover_password_page = "pages/recover.php";
$classes  = "scripts/classes.php";
$contact_script = 'scripts/contact_script.php'; //It helps us to listen to our customers feedback
$modules_class = 'scripts/modules.php';

#Folders
$pages="pages";
$modules="modules";

#Modules Links
$promo="modules/promo.php";
$left="modules/left.php";
$right="modules/right.php";
$header="modules/header.php";
$login_form=$modules."/login_form.php";
$login_form="pages/login/login_form.php";
$register_form="pages/reg/index.php";
$reg_functions='pages/reg/reg_functions.php';

define("PROMO", $edorica->getFile($promo, $flevel));
$left="modules/left.php";
$right="modules/right.php";
define("REG_FUNCTIONS", $edorica->getFile($reg_functions, $flevel));
define("HEADER", $edorica->getFile($login_form, $flevel));
define('REGISTER_FORM', $edorica->getFile($register_form, $flevel));
define('LOGIN_FORM', $edorica->getFile($login_form, $flevel));

define("_FROM_EMAIL", "wswapped@gmail.com");
define("_DUMB_PHONE", "788304561");
$register_form="pages/reg/index.php";



#Pages
$home_page=$pages."/home.php";
$contact_page=$pages."/contact.php";
$schools_page=$pages."/schools.php";
$about_page=$pages."/about.php";
$login_page=$pages."/login.php";
$register_page=$pages."/register.php";
$register_page=$pages."/reg/index.php";
$logout_page=$pages."/logout.php";
$profile_page=$pages."/profile.php";
$forgotpassword=$pages."/forgotpassword.php";
$checkmail_page = $pages."/reg/checkmail.php";
$recover_password_page = $pages."/recover_password.php";




#Login
$login_password_hash="pwd77";
$login_username_hash="UUID";
$login_email_hash="mailanda";
$login_failed=0;
$login_success=1;

function tellError($msg){
	echo mysqli_error($conn);
}

 #Classes/*
 function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}


#Cookies
$prestatus=0;
function login_cookie($name, $value, $time){
	global $conn;
	if(!isset($_COOKIE[$name])){
		setcookie($name, $value, time()+(86400*30), "/");
		//Checking if there is preferred redirect
		if(isset($_COOKIE['pref_login_redirect']) && $_COOKIE['pref_login_redirect']!=''){

			setcookie("pref_login_redirect", $value, time()-(86400*30), "/");

			$URL = mysqli_real_escape_string($conn, $_COOKIE['pref_login_redirect']);
			header("location: $URL");
			}
		//else header("location:index.php");
		}
}
function login_status($prestatus=0){
	$User = WEB::getInstance('user');
	return $User->login_status();

	global $conn, $login_email_hash, $login_password_hash;
	//We will first check if logged in cookies are set
	//Cookies names are called login_email_hash and login_password_hash


	if(!empty($_COOKIE[$login_email_hash]) && !empty($_COOKIE[$login_password_hash]) ){

		$email = mysqli_real_escape_string($conn, $_COOKIE[$login_email_hash]);
		$pwd = mysqli_real_escape_string($conn, $_COOKIE[$login_password_hash]);

		//Checking if  credentials are correct
		$creq = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND (password IS NOT NULL AND password='$pwd')") or die(mysqli_error($conn));
		if(mysqli_num_rows($creq)>0){
			$userID = mysqli_fetch_assoc($creq);
			$userID = $userID['id'];
			return $userID;
			}
		else return false;

	}else if($prestatus==$GLOBALS['login_success']){
		return 1;
	}else return false;
}

function logout($cookie1, $cookie2){
	setcookie($cookie1, '0', time()-100, '/');
	setcookie($cookie2, '0', time()-100, '/');
}
?>
