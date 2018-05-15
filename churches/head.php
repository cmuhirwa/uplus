<?php
    ob_start();
	include_once("db.php");
    include_once("functions.php");
	session_start();
    define("SMS_PRICE", 13); //Constant for SMS price

    $userType = $userType??'church';

    //users Hierachy

    $userId = getUser();

    $userType = checkType($userId);

    if ($userId) {
        $userData = checkLogin($userType);
        $churchID = $userData['church'];

        $churchData = getChurch($churchID);

        $churchname = $churchData['name'];
        
        $churchID = $userData['church'];

        $adminImage = $userData['profileImage'];
    }else{
    	header("location: login.php");
    }
?>
<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Remove Tap Highlight on Windows Phone IE -->
<meta name="msapplication-tap-highlight" content="no"/>

<link rel="icon" type="image/png" href="assets/img/favicon-16x16.png" sizes="16x16">
<link rel="icon" type="image/png" href="assets/img/favicon-32x32.png" sizes="32x32">

<title><?php echo !empty($title)?$title." | ":"" ?> U+ Church</title>

<!-- uikit -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.40/css/uikit.min.css" /> -->
<link rel="stylesheet" href="bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

<!-- flag icons -->
<link rel="stylesheet" href="assets/icons/flags/flags.min.css" media="all">

<!-- style switcher -->
<!-- <link rel="stylesheet" href="assets/css/style_switcher.min.css" media="all"> -->

<!-- altair admin -->
<link rel="stylesheet" href="assets/css/main.min.css" media="all">

<!-- themes -->
<link rel="stylesheet" href="assets/css/themes/themes_combined.min.css" media="all">

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/style.css" media="all">

<!-- matchMedia polyfill for testing media queries in JS -->
<!--[if lte IE 9]>
    <script type="text/javascript" src="bower_components/matchMedia/matchMedia.js"></script>
    <script type="text/javascript" src="bower_components/matchMedia/matchMedia.addListener.js"></script>
    <link rel="stylesheet" href="assets/css/ie.css" media="all">
<![endif]-->
<script type="text/javascript">
    const currentUser = <?php echo $userId; ?>;
</script>