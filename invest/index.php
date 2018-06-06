<?php
	include_once 'admin/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>FirstBank Nigeria</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="assets/lib/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/lib/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/lib/Linearicons/css/demo.css" />
	<link rel="stylesheet" type="text/css" href="assets/lib/select2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/lib/jquery.bxslider/jquery.bxslider.css" />
	<link rel="stylesheet" type="text/css" href="assets/lib/owl.carousel/owl.carousel.css" />
	<link rel="stylesheet" type="text/css" href="assets/lib/fancyBox/jquery.fancybox.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/animate.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/index9.css" />
	<!--[if IE]>
	<style>.form-category .icon {display: none;}</style>
	<![endif]--> 
	<link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/responsive9.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/layout.css" />
	<link rel="icon" href="https://www.firstbanknigeria.com/wp-content/uploads/2016/05/w-logo-blue.png" sizes="32x32" />
	
</head>
<body class="home market-home">
<!-- HEADER -->
<?php include("header.php");?>
<!-- end header -->
<!-- Home slideder-->


<!---->
<div class="content-page">
	<div class="container">
		<?php
			if(!empty($_GET['c'])){
				//Let's bring page for displaying the company details and just facilitate sales
				$company = $_GET['c'];

				$query = $db->query("SELECT * FROM items1 WHERE abrev = \"$company\" LIMIT 1") or die("Can't get companies $db->error");

				$company_data = $query->fetch_assoc();
				$company_name = $company_data['itemName'];
				?>
					<div class="product-single main-product">
						<div class="navbar nav-menu">
							<div class="navbar-label"><h3 class="title"><span class="icon fa fa-star"></span><span class="label-prod"><?php echo $company_data['itemName']; ?></span></h3></div>
						</div>
						<div class="tab-container">
							<div id="tab-2" class="tab-panel active">
								<div id="container" style="height: 400px; min-width: 310px"></div>
								<div class="">
									<button type="button" class="btn btn-success pull-right" id="buybtn" data-toggle="modal" data-target="#buyModal">BUY</button>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>

				<?php
			}else{
				//No special route requested, so let's show all companies
				?>
				<div class="product-single main-product">
					<div class="navbar nav-menu">
						<div class="navbar-label"><h3 class="title"><span class="icon fa fa-star"></span><span class="label-prod">INVESTMENTS IN NIGERIA</span></h3></div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<ul class="list-group">
								<?php
									$stocks = getStockCompanies();
									foreach ($stocks as $key => $stock) {
										$stockName = $stock['companyName'];
										$stockId = $stock['companyId'];
										echo '<li class="list-group-item stocksList success" data-stock="'.$stockId.'"><a href="javascript:;">'.$stockName.'</a></li>';
									}

								?>
							</ul>
						</div>
						<div class="col-md-10">
							<div id="chartContainer" data-stock = <?php echo $stocks[0]['companyId'] ?>></div>
						</div>
					</div>
					<div class="tab-container">
						<div id="tab-1" class="tab-panel active">
							<ul class="product-list owl-carousel" data-dots="false" data-loop="true" data-nav = "true" data-margin = "0" data-autoplayTimeout="1000" data-autoplayHoverPause = "true" data-responsive='{"0":{"items":1},"480":{"items":2}, "991":{"items":3},"1200":{"items":4}}'>
							 <?php
									$sql2 = $db->query("SELECT * FROM company WHERE type ='stock' ");
									while($row = mysqli_fetch_array($sql2))
										{
											$postTitle = $row['companyName'];
											$companyId = $row['companyId'];
											//$priceStatus = $row['unit'];
											$price = $row['unitPrice']??12;
											//$postDeadline = $row['postDeadline'];
											echo '   <li class="item">
																	<div class="left-block">
										<a href="post.php?postId='.$companyId.'">
											<img class="img-responsive" alt="'.$row['logo'].'.jpg"/>
										</a>
										<br/><br/>
										<div class="add-to-cart">
											<a title="Add to Cart" class="" href="post.php?postId='.$companyId.'">View</a>
										</div>
									</div>
									<div class="right-block">
										<div class="left-p-info">
											<h5 class="product-name"><a href="post.php?postId='.$companyId.'">'.$postTitle.'</a></h5>
											<div class="product-star">
												<i class="fa fa-star-o"></i>
												<i class="fa fa-star-o"></i>
												<i class="fa fa-star-o"></i>
												<i class="fa fa-star-o"></i>
												<i class="fa fa-star-o"></i>
											</div>
										</div>
										<div class="content_price">
											<span class="price product-price">'.number_format($price).' Rwf</span>
										</div>
									</div>
									<div class="right-block">
										<div class="left-p-info">
											<h5 class="product-name">Ending:</h5>
											
										</div>
										<div class="content_price">
											
										</div>
									</div>
								</li>';}
							 ?>
							 </ul>

						</div>
					</div>
				</div>
				<?php
			}
		?>       
	 </div>
</div>
<div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Request CSD Account</h5>
		  </div>
		  <div class="modal-body">
			<form>
				<div class="row">
					<div class="col-md-2">
						<label for="title">Title</label>
						<select name="title" id="title" class="form-control" required="required">
							<option value="">[--Select--]</option>
							<option value="H.E.">H.E. </option>
							<option value="Hon.">Hon. </option>
							<option value="Prof.">Prof. </option>
							<option value="Dr.">Dr. </option>
							<option value="Mr.">Mr. </option>
							<option value="Mrs.">Mrs. </option>
							<option value="Ms.">Ms. </option>
							<option value="Military">Military </option>
						</select>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="exampleInputEmail1">Surname</label>
							<input type="text" class="form-control" id="sname_input" aria-describedby="emailHelp" placeholder="Enter your name">
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="exampleInputEmail1">Other name</label>
							<input type="text" class="form-control" id="oname_input" aria-describedby="emailHelp" placeholder="Enter your name">
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="exampleInputEmail1">National ID/Passport</label>
					<input type="number" class="form-control" id="id_input" aria-describedby="emailHelp" placeholder="Enter your national ID card number">
				</div>

				<div class="row">
					<div class="col-md-2">
						<label for="gender_input">Gender</label>
						<select name="title" id="gender_input" class="form-control" required="required">
							<option value="">[--Select--]</option>
							<option value="F">Female </option>
							<option value="M">Male </option>
						</select>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="dob_input">Date of birth</label>
							<input type="date" class="form-control" id="dob_input" aria-describedby="emailHelp" placeholder="Enter your name">
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="nationality_input">Nationality</label>
							<input type="text" class="form-control" id="nationality_input" aria-describedby="emailHelp" placeholder="Enter your name">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="phone_input">Phone number</label>
					<input type="number" class="form-control" id="phone_input" aria-describedby="emailHelp" placeholder="Enter your phone number">
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="shares_input">Shares</label>
							<input type="number" class="form-control" id="shares_input" aria-describedby="emailHelp" placeholder="Number of shares">
						</div>
					</div>
					<!-- <div class="col-md-6">
						Here comes me
					</div> -->
				</div>
			</form>
		  </div>
		  <div class="modal-footer" data-role='init'>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="comfirm_buy">SUBMIT</button>
		  </div>
		  <div class="modal-footer display-none" data-role='sending'>
			<img src="assets/images/rot_loader.gif"> Sending
		  </div>
		  <div class="modal-footer display-none" data-role='done'>
			<div class="status">
			</div>
			<button class="btn btn-primary">DONE</button>
		  </div>

		</div>
	</div>
</div>

<?php
	$stockName = "BK";
?>

<a href="#" class="scroll_top" title="Scroll to Top" style="display: inline;">Scroll</a>
<!-- Script-->
<!-- <script type="text/javascript" src="assets/lib/jquery/jquery-1.11.2.min.js"></script> -->
<script type="text/javascript" src="../frontassets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/lib/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="assets/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="assets/lib/jquery.bxslider/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="assets/lib/owl.carousel/owl.carousel.min.js"></script>
<script type="text/javascript" src="assets/lib/jquery.countdown/jquery.countdown.min.js"></script>
<script type="text/javascript" src="assets/lib/fancyBox/jquery.fancybox.js"></script>
<script type="text/javascript" src="assets/lib/jquery.elevatezoom.js"></script>
<script type="text/javascript" src="assets/js/theme-script.js"></script>
<script type="text/javascript" src="assets/js/equalheight.js"></script>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<!-- <script src="js/jquery.js"></script> -->
<script>

$("#comfirm_buy").on('click', function(){
	//Let's get inputs
	title = $("#title").val();
	surname = name = $("#sname_input").val();
	othername = $("#oname_input").val();
	phone = $("#phone_input").val();
	id_input = $("#id_input").val();

	gender = $("#gender_input").val();
	dob = $("#dob_input").val();
	nationality = $("#nationality_input").val();

	shares = $("#shares_input").val();

	if(title && surname && othername && phone && id_input && gender && dob && nationality){
		//Validating user

		//Showing progress
		$(".modal-footer[data-role='init']").addClass('display-none');

		//Display progress
		$(".modal-footer[data-role='sending']").removeClass('display-none');

		setTimeout(function(){
			//Just waste some time to user
			;
		}, 1000);

		$.post('api/index.php', {action:'share_buy_request', title:title, surname:surname, oname:othername, phone:phone, id:id_input, gender:gender, dob:dob, nationality:nationality, number:shares}, function(data){
			
			//Interpretting the results of the query
			try{
				$(".modal-footer").addClass('display-none');
				$(".modal-footer[data-role='done']").removeClass('display-none');
				log_elem = $(".modal-footer[data-role='done'] .status");               
				ret = JSON.parse(data);
				if(ret.status)
				{
					//Successfully saved the request
					log_elem.addClass('alert alert-success')
					log_elem.html("<h4 class='alert-heading'>Your request was submitted!</h4><hr /><div class='mb-0'>"+ret.msg+"</div>")

				}else{
					//Successfully saved the request
					log_elem.addClass('alert alert-warning')
					log_elem.html("<h4 class='alert-heading'>Error processsing your request!</h4><hr /><div class='mb-0'>"+ret.msg+"</div>")
				}
			}catch(e){
				log_elem.addClass('alert alert-warning')
				log_elem.html("<h4 class='alert-heading'>Error with server, try soon later or contact the administrator!</h4><hr /><div class='mb-0'>"+e+"</div>")
			}
		});
	}else{
		//User has not filed in everyrhing
		alert("Please fill in everything")
	}

	
});


$(document).ready(function(){
	//check the stock we have to load
	chartElem = $("#chartContainer");

	initialStock  = $(chartElem).data('stock');
	loadChart(initialStock);

	//trigerring
	$(".stocksList").on('click', function(){
		stockId = $(this).data('stock');
		loadChart(stockId)
	})
})


function loadChart(stockId){
	$.post('../api/invest.php', {action: 'listStocks'}, function (data) {
		console.log(data)

		stockData = []
		//looping through to find our stock
		for(n=0; n<data.length; n++)
		{

			stock = data[n];
			if(stock.stockId == stockId){
				current_stock_data = stock.data;
				console.log(current_stock_data)

				//Loop ovewr data
				for (var i = current_stock_data.length - 1; i >= 0; i--) {
					t = current_stock_data[i];
					stockData.push([new Date(t.date).getTime(), parseInt(t.unitPrice)])
				}

				console.log(stockData);
				stockName = stock.stockName

				// Create the chart
				Highcharts.stockChart('chartContainer', {


					rangeSelector: {
						selected: 1
					},

					title: {
						text: stockName+' Stock Price'
					},

					series: [{
						name: stockName+' Stock Price',
						data: stockData,
						type: 'areaspline',
						threshold: null,
						tooltip: {
							valueDecimals: 2
						},
						fillColor: {
							linearGradient: {
								x1: 0,
								y1: 0,
								x2: 0,
								y2: 1
							},
							stops: [
								[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
							]
						}
					}]
				});

				//stop the looping; we've got what we wanted
				break;
				 
			}
		}
	});
}


// $.getJSON('aapl-c.json', function (data) {
// 	<?php
		$company_name = 'FirstBank';
// 	?>
// 	// Create the chart
// 	Highcharts.stockChart('chartContainer', {


// 		rangeSelector: {
// 			selected: 1
// 		},

// 		title: {
// 			text: '<?php echo $company_name; ?> Stock Price'
// 		},

// 		series: [{
// 			name: '<?php echo $company_name; ?> Stock Price',
// 			data: data,
// 			type: 'areaspline',
// 			threshold: null,
// 			tooltip: {
// 				valueDecimals: 2
// 			},
// 			fillColor: {
// 				linearGradient: {
// 					x1: 0,
// 					y1: 0,
// 					x2: 0,
// 					y2: 1
// 				},
// 				stops: [
// 					[0, Highcharts.getOptions().colors[0]],
// 					[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
// 				]
// 			}
// 		}]
// 	});
// });
</script>
</body>
</html>