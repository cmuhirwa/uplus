<?php
function isSecure() {
	return
		(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| $_SERVER['SERVER_PORT'] == 443;
}

// if( !isSecure() && $_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != 'kiza.com'){
// 		$nurl = "https://www.".ltrim($_SERVER['HTTP_HOST'], "www.")."/$reqpath";
// 		header("HTTP/1.1 301 Moved Permanently");
// 		header("location:$nurl");
// 		die();
// };

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Intelligent warehouse | Welcome</title>
<link rel="shortcut icon" href="logo.png">
<!-- Bootstrap Css -->
<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Normalize Css -->
<link href="assets/Normalize/normalize.css" rel="stylesheet">
<!--Font Awesome Css-->
<link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!--Linear icon Css-->
<link href="assets/linearicons/css/icon-font.min.css" rel="stylesheet">
<!--Animate Css-->
<link href="assets/animate/animate.css" rel="stylesheet">
<!--Animate headline css-->
<link href="assets/cd-intro/headline.css" rel="stylesheet">
<!--Owl carousel css-->
<link href="assets/owlcarousel/css/owl.carousel.css" rel="stylesheet">
<link href="assets/owlcarousel/css/owl.theme.css" rel="stylesheet">
<!--Portfolio Css-->
<link href="assets/css/ionicons.min.css" rel="stylesheet">
<link href="assets/css/magnific-popup.css" rel="stylesheet">
<!--Slicknav Css-->
<link href="assets/slicknav/slicknav.css" rel="stylesheet">
<!--Custum Css-->
<link href="css/style.css" rel="stylesheet">
<!--Responsive Css-->
<link href="css/responsive.css" rel="stylesheet">
<!--Modernizr Js-->

<!-- SEO -->
<meta name="description" content="warehouse ltd we are a Hi-tech & ICT company bringing community problem solutions to life and help other entrepreneur to reach to their dreams">
<meta name="keywords" content="IntelliWare, IntelliWare, rwanda, agriculture, IoT rwanda, data, big data">

<script src="js/vendor/modernizr-3.5.0.min.js"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
<body>
<!-- Pre Loader -->
<div id="loader"></div>
<!--Header-->
<header id="home">
	<div class="header-top-area">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<!-- START LOGO -->
					<div class="logo"> <a href="/">INTELLIGENT WAREHOUSE</a> </div>
					<!-- END LOGO -->
					<div class="mobile-nav"></div>
				</div>
				<div class="col-md-9">
					<!-- START MAIN MENU -->
					<nav class="navbar navbar-default">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header"> </div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<!-- <div class="collapse navbar-collapse">
							<div class="navigation">
								<ul class="nav navbar-nav">
									<li><a href="/">Home</a>
									</li>
									<li><a href="#about-area-1">About us</a>
									</li>
									<li><a href="#features">Services</a>
									</li>
									<li><a href="#blog">Portfolio</a>
									</li>
									<li><a href="#contact-us-section">Contact Us</a></li>
								</ul>
							</div>
						</div> -->
						<!-- /.navbar-collapse -->
					</nav>
					<!-- END MAIN MENU -->
				</div>
			</div>
		</div>
	</div>
	<!-- Start Animated headline area -->
	<div class="home-slider-area">
		<div class="slider-sec">
			<div class="slider-op">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<div class="slider-sec-text">
								<div class="cd-intro">
									<h3 class="color-w font-28 txt-lft pb-10 ltr-s-4"> Making intelligent warehouses </h3>
									<h3 class="cd-headline clip is-full-width font-40 color-w font-w-8 txt-lft ptb-5"> <span>By making them </span>
										<span class="cd-words-wrapper">
											<b style="color: #3597DB;" class="is-visible">Automatic</b>
											<b style="color: #3597DB;">Optimum</b>
											<b style="color: #3597DB;">Traceable</b>
										</span>
									</h3>
									<h6 class="color-w txt-lft pb-20 ltr-s-2 font-w-1 ln-h-30"> Assisting the core part of community </h6>
									<a class="btn-two pull-left" href="#about-area-1">LEARN MORE</a> </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Animated headline area -->
</header>
<!--About Us Start-->
<section id="about-area-1" class="about-area-1">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="seo-about-img"> <img class="img-responsive" src="images/profile.png" alt=""> </div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="tb-about-2-content">
					<h3 class="pb-20">WELCOME TO <span class="Color-b">Intelligent warehouse</span></h3>
					<h1 class="font-w-6">What it does?</h1>
					<h5> "A very optimized solution to reduce errors and boost productivity"</h5>
					<p class="mb-15"> We are building an intelligent warehouse solution in nature to overcome challenges in today's warehouse solutions. One being complexity, we are developing machine learning algorithms to cope with such complexity and other challenges </p>
					<a href="#" class="btn">Read More</a> </div>
			</div>
		</div>
	</div>
</section>
<!--About Us End-->
<div class="clearfix"></div>
<!--Counter Start-->
<!--Counter end-->
<div class="clearfix"></div>
<!--Features start-->
<section id="features" class="features">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title ptb-20">
					<!-- <h2 class="font-w-8"><span class="color">A</span>WESOME <span class="color">S</span>ERVICES</h2> -->
					<h2 class="font-w-8"><span class="color">A blend of deep tecnologies we are blending</h2>
					<p class="font-w-6"><em>"We are using advanced technologies that we love and have mastered to bring solutions to the real world problem and in crucial environments like -health"</em></p>
				</div>
				<div class="section">
					<!-- Single Feature Start -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="feat p-25 bx-shadow">
							<center><img src="images/icons/icon_web.png" style="width:150px;">
							<h5 class="font-20 pt-15">Artificial intelligence</h5>
							<h6 class="font-13 font-w-6">Machine learning and computer vision are leading in warehouse automation and also assist warehouse staff to take data-based intelligent decisions</h6>
							</center>
						</div>
					</div>
					<!-- Single Feature End -->
					<!-- Single Feature Start -->
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="feat p-25 bx-shadow">
							<center><img src="images/icons/icon_app.png" style="width:150px;">
							<h5 class="font-20 pt-15">Portable systems</h5>
							<h6 class="font-13 font-w-6">BYOT is real. We building solution which can be used by every human using whichever device and also technologies which objects can also use to communicate to humans through embedded communications. We are all thrilled by human-object communication</h6>
							</center>
						</div>
					</div>
					<!-- Single Feature End -->
					<!-- Single Feature Start -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="feat p-25 bx-shadow">
							<center><img src="images/icons/icon_iot.png" style="width:150px;">
							<h5 class="font-20 pt-15">Internet Of Things, IoT</h5>
							<h6 class="font-13 font-w-6">We are building warehouses which are active and semantic to allow easy management and reduce ghost products, theft and commercial losses using IoT technologies like RFID, edge computing, ..</h6>
							</center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="demo" class="demo">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title ptb-20">
					<!-- <h2 class="font-w-8"><span class="color">A</span>WESOME <span class="color">S</span>ERVICES</h2> -->
					<h2 class="font-w-8"><span class="color">Our Computer vision systems</h2>
					<p class="font-w-6"><em>"We are using advanced technologies that we love and have mastered to bring solutions to the real world problem and in crucial environments like -health"</em></p>
				</div>
				<div class="section">
					<!-- Single Feature Start -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="feat p-25 bx-shadow">
							<center><img src="images/indu.jpg" class="img-responsive">
							<h5 class="font-20 pt-15">Warehouse augumentation</h5>
							<h6 class="font-13 font-w-6">Computer vision perception of the warehouse would be equiped in many parts of our system for flexibility and control</h6>
							</center>
						</div>
					</div>
					<!-- Single Feature End -->
					<!-- Single Feature Start -->
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="feat p-25 bx-shadow">
							<center><img src="images/materials.jpg" class="img-responsive">
							<h5 class="font-20 pt-15">Warehouse vision</h5>
							<h6 class="font-13 font-w-6">Camera monitoring of the warehoue to be used by agents in inspection, products intake and robots inspection if we reach level of using robots</h6>
							</center>
						</div>
					</div>
					<!-- Single Feature End -->
					<!-- Single Feature Start -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="feat p-25 bx-shadow">
							<center><img src="images/barcode.jpg" style="width:150px;">
							<h5 class="font-20 pt-15">Product recognition</h5>
							<h6 class="font-13 font-w-6">We are building warehouses which are active and semantic to allow easy management and reduce ghost products, theft and commercial losses using IoT technologies like RFID, edge computing, ..</h6>
							</center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="container">
	<div class="col-md-12">
		<a href="dashboard" class="btn">ACCESS</a>
	</div>
</div>

<?php
/*

<!--Feature end -->
<!--Service start-->
<div class="clearfix"></div>
<!--Service end-->
<!--Team start-->
<section id="team" class="team">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title ptb-20">
					<h2 class="font-w-8"><span class="color">O</span>UR TEAM</h2>
					<p class="font-w-6"></p>
				</div>
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img style="min-height: 200px;" src="images/team/lambert.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="https://web.facebook.com/rulindana"><i class="fa fa-facebook"></i></a> 
									<a href="https://www.instagram.com/lambert.rulindana/"><i class="fa fa-instagram"></i></a>
										<a href="https://plus.google.com/u/0/100116692602694734855"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h5 class="font-w-6 ">Lambert Rulindana</h5>
								<p>CEO <br><font style="font-weight: bold;">Digital Fabrication Specialist</font></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img src="images/team/kelly.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="https://web.facebook.com/iribagiza.kelly"><i class="fa fa-facebook"></i></a> 
									<a href="#"><i class="fa fa-instagram"></i></a>
										<a href="#"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h4 class="font-w-6">Rose Kelly Iribagiza</h4>
								<p>General Manager <br><font style="font-weight: bold;">Business IT</font></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img src="images/team/saphani.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="#"><i class="fa fa-facebook"></i></a> 
									<a href="#"><i class="fa fa-instagram"></i></a>
										<a href="#"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h4 class="font-w-6">Saphani Bazimya</h4>
								<p>COO <br><font style="font-weight: bold;">Software Engineer</font></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img src="images/team/sam.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="https://web.facebook.com/realfair.stanley"><i class="fa fa-facebook"></i></a> 
									<a href="https://www.instagram.com/grizzlysugira_samuel/"><i class="fa fa-instagram"></i></a>
										<a href="#"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h4 class="font-w-6">Samuel Sugira</h4>
								<p>CTO <br><font style="font-weight: bold;">Software Engineer</font></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img src="images/team/fruzna.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="https://web.facebook.com/profile.php?id=1365369072"><i class="fa fa-facebook"></i></a> 
									<a href="https://www.instagram.com/fruzsinahomolka/"><i class="fa fa-instagram"></i></a>
										<a href="https://plus.google.com/105515316279360599047"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h4 class="font-w-6">Fruzsina Homolka</h4>
								<p>Project Manager <br><font style="font-weight: bold;">Agri-food Sustainability Expert</font></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img src="images/team/pla.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="https://web.facebook.com/hakplacide"><i class="fa fa-facebook"></i></a> 
									<a href="#"><i class="fa fa-instagram"></i></a>
										<a href="https://plus.google.com/105515316279360599047"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h4 class="font-w-6">Placide Hakuzweyezu</h4>
								<p>Web Developer <br><font style="font-weight: bold;">Database Expert</font></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single-team">
							<div class="teamthumb"> <img src="images/team/mukwiye1.jpg" class="img-responsive" alt="">
								<div class="team-social">
									<a href="https://web.facebook.com"><i class="fa fa-facebook"></i></a> 
									<a href="#"><i class="fa fa-instagram"></i></a>
										<a href="https://plus.google.com/105515316279360599047"><i class="fa fa-google-plus"></i></a>
								</div>
							</div>
							<div class="team-title">
								<h4 class="font-w-6">Lambert Mukwiye</h4>
								<p>System Designer <br><font style="font-weight: bold;">Network and Telecommunications Expert</font></p>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
<!--Portfolio start-->
<!--Portfolio end-->
<div class="clearfix"></div>
<!--Section start-->
<div  class="section-part">
	<div class="container">
		<div class="col-md-12">
			<div class="row">
				<h3 class="center font-w-8 ptb-10 color">PROFESSIONAL ROADMAP HOPE YOU ENJOY IT.</h3>
				<div class="col-md-4">
					<div class="sec mtb-40">
						<h5 class="txt-rgt font-w-8">Awesome Ideas <i class="fa fa-lightbulb-o ml-15"></i></h5>
						<p class="txt-rgt">We collaborate with our customers to provide out standing project and work</p>
					</div>
					<div class="sec">
						<h5 class="txt-rgt font-w-8">Creative Design <i class="fa fa-delicious ml-15"></i></h5>
						<p class="txt-rgt">The human mind is not static for a purpose, so is IntelliWare! With an insatiable spirit,we strive to always produce highly qualified products.</p>
					</div>
				</div>
				<div class="col-md-4"> <img src="images/kwibuka/kiza_agri.jpg" class="img-responsive" alt=""> </div>
				<div class="col-md-4">
					<div class="sec mtb-40 txt-lft">
						<h5 class="txt-lft font-w-8"><i class="fa fa-tablet mr-15"></i> Fully Responsive</h5>
						<p class="txt-lft">We always strive to provide well responsive solution and platforms to always stay in better shape.</p>
					</div>
					<div class="sec">
						<h5 class="txt-lft font-w-8"><i class="fa fa-life-ring mr-15"></i> Full Support</h5>
						<p class="txt-lft">Not only provide well functioning systems and platform we also continue to measure the functioning of every our product.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--Section end-->
<div class="color-bg-b center">
	<div class="container">
		<div class="col-md-12">
			<div class="row">
				<div class="image-content p-30">
					<h2 class="text-center ln-h-50 color-w font-w-8">We have the experience and the knowledge to<br>
						get your vision comes true.</h2> </div>
			</div>
		</div>
	</div>
</div>
<!--Blog start-->
<section id="blog" class="blog">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title ptb-20">
					<h2 class="font-w-8"><span class="color">O</span>UR PORTFOLIO</h2>
					<p class="font-w-6">See what we' ve got for you. From our Customers.</p>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12 row-eq-height">
					<div class="item-blog bx-shadow">
						<!-- part img-->
						<div class="img-blog"> <img src="images/project.png" class="img-responsive" alt=""> <a href="agri.html"><strong>20</strong><br>
							Dec<br>
							2017 </a> </div>
						<!-- part content -->
						<div class="content-blog text-center p-15"> <a href="agri.html">
							<h6 class="font-16 ptb-15 font-w-8 ltr-s-2">IntelliWare Agri</h6>
							</a>
							<p class="font-w-6">Connecting farmers into trade and provide real time monitoring of their field and warehouse</p>
							<a href="agri.html" class="btn-blg font-w-6">READ MORE <i class="fa fa-arrow-right font-15"></i></a> </div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 row-eq-height">
					<div class="item-blog bx-shadow">
						<!-- part img-->
						<div class="img-blog"> <img src="images/umuguzi.PNG" class="img-responsive" alt=""> <a href="blog-single.html"><strong>20</strong><br>
							Dec<br>
							2017 </a> </div>
						<!-- part content -->
						<div class="content-blog text-center p-15"> <a href="#">
							<h6 class="font-16 ptb-15 font-w-8 ltr-s-2">Umuguzi.com</h6>
							</a>
							<p class="font-w-6">A web based platform to help advertisers and local sellers to give a pride to their products online. </p>
							<a href="#" class="btn-blg font-w-6">READ MORE <i class="fa fa-arrow-right font-15"></i></a> </div>
					</div>
				</div>
				<div style="display: none;" class="col-md-3 col-sm-6 col-xs-12">
					<div class="item-blog bx-shadow">
						<!-- part img-->
						<div class="img-blog"> <img src="images/blog3.jpg" class="img-responsive" alt=""> <a href="blog-single.html"><strong>20</strong><br>
							Dec<br>
							2017 </a> </div>
						<!-- part content -->
						<div class="content-blog text-center p-15"> <a href="blog-single.html">
							<h6 class="font-16 ptb-15 font-w-8 ltr-s-2">Amazing blog</h6>
							</a>
							<p class="font-w-6">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod </p>
							<a href="blog-single.html" class="btn-blg font-w-6">READ MORE <i class="fa fa-arrow-right font-15"></i></a> </div>
					</div>
				</div>
				<div style="display: none;" class="col-md-3 col-sm-6 col-xs-12">
					<div class="item-blog bx-shadow">
						<!-- part img-->
						<div class="img-blog"> <img src="images/blog4.jpg" class="img-responsive" alt=""> <a href="#"><strong>20</strong><br>
							Dec<br>
							2017 </a> </div>
						<!-- part content -->
						<div class="content-blog text-center p-15"> <a href="blog-single.html">
							<h6 class="font-16 ptb-15 font-w-8 ltr-s-2">Amazing blog</h6>
							</a>
							<p class="font-w-6">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod </p>
							<a href="blog-single.html" class="btn-blg font-w-6">READ MORE <i class="fa fa-arrow-right font-15"></i></a> </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="clearfix"></div>
<!--Blog end-->
<!--Client start-->
<div style="display: none;" class="client">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title ptb-20">
					<h2 class="font-w-8"><span class="color">O</span>UR CLIENTS</h2>
					<p class="font-w-6">Sed ut perspiciatis unde omnis iste natus error sit voluptatem</p>
				</div>
			</div>
		</div>
		<div id="client-slider" class="owl-carousel">
			<div class="item client-logo"> <a href="#"><img src="images/1.png" class="img-responsive" alt=""/></a> </div>
			<div class="item client-logo"> <a href="#"><img src="images/2.png" class="img-responsive" alt=""/></a> </div>
			<div class="item client-logo"> <a href="#"><img src="images/3.png" class="img-responsive" alt=""/></a> </div>
			<div class="item client-logo"> <a href="#"><img src="images/4.png" class="img-responsive" alt=""/></a> </div>
			<div class="item client-logo"> <a href="#"><img src="images/5.png" class="img-responsive" alt=""/></a> </div>
			<div class="item client-logo"> <a href="#"><img src="images/6.png" class="img-responsive" alt=""/></a> </div>
		</div>
	</div>
</div>
<!--Client end-->
<!--Contact start-->
<!-- Start Contact-section area -->
<section id="contact-us-section" class="contact-us-section pb-30">
	<div class="container">
		<!--Sec Title-->
		<div class="section-title m-0 pb-28">
			<h2 class="font-w-8 center"><span class="color">C</span>ONTACT US</h2>
			<p class="font-w-6 center">Do not hesitate to contact us for any information.</p>
		</div>
		<div class="section contact-us">
			<div class="container">
				<div class="outer-box">
					<div class="company-data">
						<ul class="row">
							<li class="col-md-4 col-sm-4 col-xs-12">
								<div class="box"> <span class="fa fa-map-marker"></span> Telecom House, 8 KG 7 Ave, Kigali </div>
							</li>
							<li class="col-md-4 col-sm-4 col-xs-12">
								<div class="box">
									<span class="fa fa-phone"></span> +25078852251<br>
									+250784762982
								</div>
							</li>
							<li class="col-md-4 col-sm-4 col-xs-12">
								<div class="box"> <span class="fa fa-envelope"></span> <a href="#">placidelunis@gmail.com</a><br>
									<a href="#">bazimyas@gmail.com</a> </div>
							</li>
						</ul>
					</div>
					<!-- Contact Form Start -->
					<div class="form-box clearfix">
						<form id="contactform" data-toggle="validator" class="shake scroll-reveal">
							<div class="form-group col-sm-6">
								<input type="text" class="form-control" id="name" placeholder="Your name" required data-error="Name missing">
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group col-sm-6">
								<input type="email" class="form-control" id="email" placeholder="Your email" required>
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group col-sm-12">
								<textarea id="message" class="form-control" rows="6" placeholder="Write your message here" required></textarea>
								<div class="help-block with-errors"></div>
							</div>
							<div class="col-sm-12"> <a class="btn" href="#">Send Message</a>
								<div id="msgSubmit"></div>
							</div>
						</form>
					</div>
					<!-- Contact Form End -->
				</div>
			</div>
		</div>
	</div>
</section>

<section id="footer" class="footer">
	<div class="container">
		<div class="row">
		</div>
	</div>
	</section>
	<!--Contact end-->

*/
?>
<footer>
	<div class="clearfix"></div>
	<div class="container">
		<p class="text-center pt-10">&copy; Copyright
			<script>
var d=new Date();
document.write(d.getFullYear());
</script>
	InteliWare | All Rights Reserved.</p>
	</div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="assets/jquery/jquery-3.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/easing/jquery.easing.min.js"></script>
<script src="assets/isotope/jquery.isotope.js"></script>
<script src="assets/jquery/imagesloaded.pkgd.min.js"></script>
<script src="assets/cd-intro/main.js"></script>
<script src="assets/wow/wow.min.js"></script>
<script src="assets/slicknav/jquery.slicknav.js"></script>
<script src="assets/owlcarousel/js/owl.carousel.min.js"></script>
<script src="assets/jquery/jquery.magnific-popup.min.js"></script>
<script src="assets/number-animation/jquery.animateNumber.min.js"></script>
<!-- Contact Form Script -->
<script src="assets/contact-form/js/validator.min.js"></script>
<script src="assets/contact-form/js/form-scripts.js"></script>
<script src="assets/jquery/plugins.js"></script>
<script src="js/custom.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117780068-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-117780068-1');
</script>
</body>
</html>
