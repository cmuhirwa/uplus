<?php
	// Destry session if it hasn't been used for 15 minute.
	session_start();
	$inactive = 900;
	if(isset($_SESSION['timeout']) ) 
	{
		$session_life = time() - $_SESSION['timeout'];
		if($session_life > $inactive)
		{
		header("Location: logout.php"); 
		}
	}

	$_SESSION['timeout'] = time();
	if (!isset($_SESSION["username"])) 
	{
		header("location: login.php"); 
		exit();
	}
	include_once "db.php";	
 
	$session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
	$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
	$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
	$sql = $db->query("SELECT * FROM users WHERE loginId='$username' AND pwd='$password' LIMIT 1"); // query the person
	// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
	$existCount = mysqli_num_rows($sql); // count the row nums

	if ($existCount > 0) {
		$row = mysqli_fetch_array($sql);

		$thisid = $row["id"];
		$userName = $names = $row["names"];
		$user_profile = $row["profile_picture"];
		$account_type = $row["account_type"];

		if($account_type =='admin')
		{
			header("location: admin.php");
			exit();
		}else if($account_type =='broker'){
			//getting brokerage company
			$query = $db->query("SELECT * FROM broker_user WHERE userCode = \"$thisid\" AND archived = 'NO' LIMIT 1 ") or trigger_error($db->error);
			$Broker = $query->fetch_assoc();
			$currentCompanyId = $Broker['companyId'];
		}else if ($account_type == 'bank') {
			$query = $db->query("SELECT * FROM broker_user WHERE userCode = \"$thisid\" AND archived = 'NO' LIMIT 1 ") or trigger_error($db->error);
			$Banker = $Broker = $query->fetch_assoc();
			$currentCompanyId = $Banker['companyId'];
		}

		//getting user company
		$companyQ = $db->query("SELECT * FROM company WHERE companyId = \"$currentCompanyId\" LIMIT 1 ") or trigger_error($db->error);
		if($companyQ->num_rows){
			$Company = (object)$companyQ->fetch_assoc();
		}else{
			die("You don't have company, contact admin");
		}
	}
	else{
		echo "			
			<br/><br/><br/><h3>Your account has been temporarily deactivated</h3>
			<p>Please contact: <br/><em>(+25) 0784848236</em><br/><b>muhirwaclement@gmail.com</b></p>		
			Or<p><a href='logout.php'>Click Here to login again</a></p>";
		exit();
	}
?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Remove Tap Highlight on Windows Phone IE -->
	<meta name="msapplication-tap-highlight" content="no"/>

	<link rel="icon" type="image/png" href="../assets/images/fbn-logo-blue.png" sizes="32x32">

	<title><?php echo !empty($title)?$title." | ":"" ?> U-Invest</title>

	<!-- additional styles for plugins -->
	<!-- weather icons -->
	<link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css" media="all">
	<!-- metrics graphics (charts) -->
	<link rel="stylesheet" href="bower_components/metrics-graphics/dist/metricsgraphics.css">
	<!-- chartist -->
	<link rel="stylesheet" href="bower_components/chartist/dist/chartist.min.css">
	<!-- c3.js (charts) -->
	<link rel="stylesheet" href="bower_components/c3js-chart/c3.min.css">
		
	
	<!-- uikit -->
	<link rel="stylesheet" href="bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

	<!-- flag icons -->
	<link rel="stylesheet" href="assets/icons/flags/flags.min.css" media="all">

	
	<!-- altair admin -->
	<link rel="stylesheet" href="assets/css/main.min.css" media="all">

	<!-- themes -->
	<link rel="stylesheet" href="assets/css/themes/themes_combined.min.css" media="all">

	<!-- Dropify -->
	<link rel="stylesheet" href="assets/skins/dropify/css/dropify.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="assets/css/style.css" media="all">

</head>
<body class=" sidebar_main_open sidebar_main_swipe">
	<!-- main header -->
	<header id="header_main">
		<div class="header_main_content">
			<nav class="uk-navbar">
								
				<!-- main sidebar switch -->
				<a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
					<span class="sSwitchIcon"></span>
				</a>
				
				
				<div class="uk-navbar-flip">
					<ul class="uk-navbar-nav user_actions">
						<li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
							<a href="#" class="user_action_image"><img class="md-user-image" src="/<?php echo($user_profile); ?>" alt=""/></a>
							<div class="uk-dropdown uk-dropdown-small">
								<ul class="uk-nav js-uk-prevent">
									<li><a href="page_user_profile.html">My profile</a></li>
									<li><a href="page_settings.html">Settings</a></li>
									<li><a href="logout.php">Logout</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</header><!-- main header end -->
	<!-- main sidebar -->
	   
	<aside id="sidebar_main">		
		<div class="sidebar_main_header">
			<div class="sidebar_logo">
				<a href="user.php" class="sSidebar_hide sidebar_logo_large">
					<img class="logo_regular" src="/<?php echo $Company->logo ?>" alt="" height="25" width="71"/>
					<img class="logo_light" src="/<?php echo $Company->logo ?>" alt="" height="15" width="71"/>
				</a>
				<a href="user.php" class="sSidebar_show sidebar_logo_small">
					<img class="logo_regular" src="/<?php echo $Company->logo ?>" alt="" height="32" width="32"/>
					<img class="logo_light" src="/<?php echo $Company->logo ?>" alt="" height="32" width="32"/>
				</a>
			</div>
		</div>
		
		<div class="menu_section">
			<ul>
				<?php 
					$sqlseller1 = $db->query("SELECT * FROM company1 WHERE companyUserCode = '$thisid'");
					$countComanies1 = mysqli_num_rows($sqlseller1);
					if($countComanies1>0)
						{
							while($row = mysqli_fetch_array($sqlseller1)) 
							{
								$companyid = $row['companyId'];
								$companyName = $row['companyName'];
								?>
							<li title="Dashboard">
								<a href="user.php">
										<span class="menu_icon"><i class="material-icons">home</i></span>
										<span class="menu_title"><?php echo $row['companyName'];?></span>
								 </a>
							</li>
							<li title="Dashboard">					
							</li>
							<li title="Members">
								<a href="customers.php?compId=<?php echo $Company->companyId;?>">
									<span class="menu_icon">
									<i class="material-icons"></i>
									</span>
									<span class="menu_title">Customers</span>
								</a>
							</li>
							<li title="Communication">
								<a href="javascript:void()">
									<span class="menu_icon"><i class="material-icons">comment</i></span>
									<span class="menu_title">Communication</span>
								</a>
								
								<ul>
									<li>
										<a href="forums.php">Forum</a>
									</li>
									<li>
										<a href="feeds.php">Feeds</a>
									</li>
								</ul>
							</li>
							<li title="Stocks">
								<a href="javascript:void()">
									<span class="menu_icon"><i class="material-icons">monetization_on</i></span>
									<span class="menu_title">Stocks</span>
								</a>
								
								<ul>
									<li>
										<a href="stocks.php">Stock</a>
									</li>
									<li>
										<a href="trade.php">Trade</a>
									</li>
								</ul>
							</li>
							<?php
						}
					}
				?>                    
			</ul>
		</div>
	</aside><!-- main sidebar end -->

	