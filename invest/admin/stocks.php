<?php
	error_reporting(E_ALL); 
	ini_set('display_errors', 1);
	$HOSTNAME = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";
?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php

include'userheader.php';
include'functions.php';

$brokerId = $Broker['companyId'];

if(isset($_POST['newPrice'])){
	$newPrice = $_POST['newPrice'];
	$companyId = $_POST['companyId'];

	$sql = "INSERT INTO `broker_security`(`brokerId`,`companyId`,`unitPrice`,`createdBy`) VALUES (\"$brokerId\", \"$companyId\", \"$newPrice\", \"$brokerId\")";
	$query = $investDb->query($sql) or trigger_error($investDb->error);
}
?>

<!-- main sidebar -->
<div id="new_comp">
	<?php
	$stock = $_GET['id']??"";
		if(!empty($stock)){

			//If submit request is issued
			if(!empty($_POST)){
				$title = $_POST['forumtitle']??"";
				$intro = $_POST['intro']??"";

				if($intro && $title){
					$forum_logo = $_FILES['forum_logo'];

					if($forum_logo['size']>10){

						$ext = strtolower(pathinfo($forum_logo['name'], PATHINFO_EXTENSION)); //extensin

						if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg'){
							$filename = "invest/gallery/".strtolower(clean_string($title))."_".time().".$ext";
							if(!move_uploaded_file($forum_logo['tmp_name'], "../../$filename")){
								trigger_error("Error uploading the file");
								$filename = $usual_logo;
							}
						}else{
							$filename = $usual_logo;
						}
					}else{
						$filename = $usual_logo;
					}


					//referencing the host
					$filename = $HOSTNAME.$filename;

					//updating
					$sql = "UPDATE forums SET title = \"$title\", subtitle = \"$intro\", icon = \"$filename\", updatedDate = NOW(), updatedBy = '$userId' WHERE id = \"$forum\"  ";
					$query = $conn->query($sql) or trigger_error($conn->error);
					if($query){
						// header("location:".$_SERVER['REQUEST_URI']);
					}
				}else{
					echo "Sure??";
				}
			}


			$stockInfo = stockInfo($stock);
			$stockName = $stockInfo['companyName'];
			$stockData  = stockHistory($stock);
			?>
				<div id="page_content">
					<div id="page_content_inner">
						<div class="md-card">
							<div class="md-card-content">
								<div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
									<div class="uk-row-first"><h4 class=""><?php echo $stockName; ?></h4></div>

								</div>
								<!-- <button class="md-btn md-btn-warning">DELETE</button> -->
							</div>
						</div>
						<div style="margin-top:50px;"></div>
						
						<div id="chartContainer"></div>

						<div class=" uk-grid uk-margin-bottom uk-grid-medium" data-uk-grid-margin>   
							<div class="uk-width-large-4-4">
								<div class="md-card">
									<?php
									?>
									<div id="status"></div>
									<div class="md-card-content">
										<div class="dt_colVis_buttons">
										</div>
										<table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Price</th>
													<th>Change</th>
													<!-- <th>Date</th> -->
												</tr>
											</thead>
											<tbody>
												<?php 
													$n=0;
													
													foreach ($stockData as $key => $data){
														$admin  = staff_details($data['createdBy']);
														$n++;
														echo '<tr>
														<td>'.$n.'</td>
														<td>'.date("d-M-Y H:i", strtotime($data['priceDate'])).'</td>
														<td>'.number_format($data['unitPrice']).' frw</td>
														<td>'.$data['change'].'%</td>														
														</tr>';
													}
												?>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>              
						</div>
					</div>					
				</div>
			<?php
		}else{
	?>
		<div id="page_content">
			<div id="page_content_inner">
				<div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
					<div class="uk-row-first"><h4 class="">Stocks</h4></div>
				</div>

				<div class=" uk-grid uk-margin-bottom uk-grid-medium" data-uk-grid-margin>   
					<div class="uk-width-large-4-4">
						<!-- <div class="md-card">
							<div class="md-card-content">
								<div class=" uk-grid">
									<div class="uk-width-3-4">
										<h4 class="heading_c uk-margin-bottom">Forums engagement</h4>
									</div>
									<div class="uk-width-1-4">
										<form class="-form">
											<div class="uk--select"  data-uk-form-select>
												<select id="selectChart" class="md-input">
													<option value="service">Service</option>
													<option value="gender">Gender</option>
													<option value="days">Days</option>
													<option value="time">Time interval</option>
												</select>
											</div>
										</form>
									</div>
								</div>
								
								<canvas id="mem_attendance" class="attendance" width="400" height="80"></canvas>
								<div ></div>
							</div>
						</div> -->
						<div class="md-card">
							<?php
								$companies = brokerStocksSummary($Broker['companyId']);
							?>
							<div id="status"></div>
							<div class="md-card-content">
								<div class="dt_colVis_buttons">
								</div>
								<table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Stock Name</th>
											<th>Previous price</th>
											<th>Current Price</th>
											<th>New Price</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$n=0;
											
											foreach ($companies as $key => $data){
												$admin  = staff_details($data['createdBy']);
												$n++;
												echo '<tr>
												<td>'.$n.'</td>
												<td><a href="stocks.php?id='.$data['companyId'].'">'.$data['companyName'].'</a></td>
												<td>'.$data['prevPrice'].'</td>
												<td>'.$data['unitPrice'].' ('.number_format($data['change']).'%)</td>
												<td><form method="post" action="stocks.php"><input name="newPrice" /><input type="hidden" name="companyId" value=
												"'.$data['companyId'].'"><button>CHANGE</button></form></td>
												</tr>';
											}
										?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>              
				</div>
			</div>
			<div class="modals">            
				<div class="uk-modal" id="add_member_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
					<div class="uk-modal-dialog" style="top: 339.5px;">
						<div class="uk-modal-header uk-tile uk-tile-default">
							<h3 class="d_inline">Add stock</h3>
						</div>
						<form id="add_stocks_form">
							<div class="md-input-wrapper">
								<label>Company</label>
								<select class="md-input" id="stockCompany" required>
									<option> -- Select --</option>
									<?php
										$stockCompanies = getStockCompanies();
										foreach ($stockCompanies as $key => $stock) {
											echo "<option value=$stock[companyId]>$stock[companyName]</option>";
										}
									?>
								</select>
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper">
								<label>Number of shares</label>
								<input type="text" name="membername" id="numberofshare_input" class="md-input input-number" maxlength="6" required="required">
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper">
								<label>Unit price</label>
								<input type="text" name="membername" id="unitprice_input" class="md-input input-number" maxlength="6" required="required">
								<span class="md-input-bar "></span>
							</div>
							<!-- <div class="md-input-wrapper md-input-filled">
								<textarea cols="30" rows="3" id="forum_intro" class="md-input autosized" placeholder="What's the forum about?" style="overflow-x: hidden; word-wrap: break-word;"></textarea>
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper md-input-filled">
								<input type="file" id="input-forum-logo" name="logo" data-height="100" data-height="100" class="dropify" data-allowed-file-extensions="png jpg jpeg" required="required"/>
								<span class="md-input-bar "></span>
							</div> -->
							<div id="addStatus" class="card mt-3" style="margin-top:20px"></div>

							<div class="uk-modal-footer uk-text-right act-dialog" data-role='init'>
								<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
								<button class="md-btn md-btn-success pull-right" id="add_stocks_btn">ADD</button>
							</div>

							<div class="uk-modal-footer uk-text-right act-dialog display-none" data-role='done'>
								<button type="button" class="md-btn md-btn-flat uk-modal-close"><img src="assets/img/rot_loader.gif" style="max-height: 50px"> Adding stocks...</button>
							</div>
						</form>
						

					</div>
				</div>
			</div>
		</div>
		<div class="md-fab-wrapper">
			<button class="md-fab md-fab-primary" href="javascript:void(0)" data-uk-modal="{target:'#add_member_modal'}"><i class="material-icons">add</i></button>
		</div>
	<?php } ?>
</div>

	<!-- google web fonts -->
	<script>
		WebFontConfig = {
			google: {
				families: [
					'Source+Code+Pro:400,700:latin',
					'Roboto:400,300,500,700,400italic:latin'
				]
			}
		};
		(function() {
			var wf = document.createElement('script');
			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
			'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		})();
	</script>

	<!-- common functions -->
	<script src="assets/js/common.min.js"></script>
	<!-- uikit functions -->
	<script src="assets/js/uikit_custom.min.js"></script>
	<!-- altair common functions/helpers -->
	<script src="assets/js/altair_admin_common.min.js"></script>

	<!-- page specific plugins -->
	<!-- d3 -->
	<script src="bower_components/d3/d3.min.js"></script>
	<!-- metrics graphics (charts) -->
	<script src="bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
	<!-- chartist (charts) -->
	<script src="bower_components/chartist/dist/chartist.min.js"></script>
	 <!-- peity (small charts) -->
	<script src="bower_components/peity/jquery.peity.min.js"></script>
	<!-- easy-pie-chart (circular statistics) -->
	<script src="bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
	<!-- countUp -->
	<script src="bower_components/countUp.js/dist/countUp.min.js"></script>
	<!-- handlebars.js -->
	<script src="bower_components/handlebars/handlebars.min.js"></script>
	<script src="assets/js/custom/handlebars_helpers.min.js"></script>
	<!-- CLNDR -->
	<script src="bower_components/clndr/clndr.min.js"></script>

	<!--  dashbord functions -->
	<script src="assets/js/pages/dashboard.min.js"></script>

	<!-- Dropify -->
	<script src="bower_components/dropify/dist/js/dropify.min.js"></script>

	<script type="text/javascript" src="js/Chart.min.js"></script>

	<!-- file input -->
	<script src="assets/js/custom/uikit_fileinput.min.js"></script>

	<!--  user edit functions -->
	<script src="assets/js/pages/page_user_edit.min.js"></script>

	<!-- Firebase -->
	<script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>

	<!-- HighStock -->
	<script src="https://code.highcharts.com/stock/highstock.js"></script>

	<script src="js/uploadFile.js"></script>

	<script type="text/javascript" src="js/forums.js"></script>

	<script type="text/javascript">
		const current_user = <?php echo $thisid; ?>;
		$(".input-number").on('keypress', function(e){
            if(isNaN(e.key) && e.key != '.'){
                alert("Numbers only allowed")
                return false;
            }
        });

		$('.dropify#input-forum-logo').dropify();
		$(".selectize").selectize();

		$("#delete_forum_btn").on('click', function(){
			//when the foru, is to be deleted
			forum_id = $(this).data('forum');

			//ask for confirmation
			UIkit.modal.confirm("Do you want to archive this forum?", function(){
				// will be executed on confirm.
				$.post('api/index.php', {action:'archive_forum', forum:forum_id, user:<?php echo $thisid; ?>}, function(data){
					// alert(data)
					if(typeof(data) != 'object'){
						ret = JSON.parse(data);
					}else{
						ret = data;
					}
					location = 'forums.php';
				})
			});
		})

		$("#activate_forum_btn").on('click', function(){
			//when the foru, is to be activated
			forum_id = $(this).data('forum');

			//ask for confirmation
			UIkit.modal.confirm("Do you want to Re-Activate this forum?", function(){
				// will be executed on confirm.
				$.post('api/index.php', {action:'activate_forum', forum:forum_id, user:<?php echo $thisid; ?>}, function(data){
					if(typeof(data) != 'object'){
						ret = JSON.parse();
					}else{
						ret = data;
					}

					location.reload();
				})
			})
		})

		function log(data){
			console.log(data)
		}

		$("#add_stocks_form").on('submit', function(e){
			e.preventDefault();
			//add individual user
			company = $("#stockCompany").val();
			shares = $("#numberofshare_input").val();
			unitprice = $("#unitprice_input").val();

			if(company && shares && unitprice){
				//Marking the progress
				//Marking the sending process
				$("#add_member_modal .act-dialog[data-role=init]").hide();
				$("#add_member_modal .act-dialog[data-role=done]").removeClass('display-none');

				var formdata = new FormData();
				fields = {action:'addStock', company:company, numberOfShares:shares, unitPrice:unitprice, createdBy:current_user};

				for (var prop in fields) {
					formdata.append(prop, fields[prop]);
				}
				var ajax = new XMLHttpRequest();

				ajax.addEventListener("load", function(){
					response = JSON.parse(this.responseText);
					console.log(response)
					if(response =="Done"){
						$("#addStatus").html("<p class='uk-text-success'>Forum added successfully!</p>");
						
						setTimeout(function(){
							UIkit.modal($("#add_member_modal")).hide();
							location.reload();
						}, 3000);
					}else{
						alert("Error")
					}

				}, false);

				ajax.open("POST", "../../api/invest.php");
				ajax.send(formdata);
			}else{
				alert("Provide all the details")
			}

		})
	</script>

	<!-- Firebase -->
	<script>
	  // Initialize Firebase
	  // TODO: Replace with your project's customized code snippet
	  // var config = {
	  //   apiKey: "AIzaSyB1qCWTLud__LGEFQQCZU98iMiy-Dp8Tbk",
	  //   authDomain: "learnbase-baa6d.firebaseapp.com",
	  //   databaseURL: "https://learnbase-baa6d.firebaseio.com",
	  //   projectId: "learnbase-baa6d",
	  //   storageBucket: "learnbase-baa6d.appspot.com",
	  //   messagingSenderId: "483987540771"
	  // };
	  // firebase.initializeApp(config);

	  // const preObject = document.getElementById("firebase")
	  // const dbRefObj = firebase.database().ref().child('firebase')

	  // dbRefObj.on('value', function(){
		
	  // })
		$(function() {
			if(isHighDensity()) {
				$.getScript( "bower_components/dense/src/dense.js", function() {
					// enable hires images
					altair_helpers.retina_images();
				});
			}
			if(Modernizr.touch) {
				// fastClick (touch devices)
				FastClick.attach(document.body);
			}
		});
		$window.load(function() {
			// ie fixes
			altair_helpers.ie_fix();
		});

// <!--0 Add Company-->
function addcomp(){

	var comp = 'yes';
		
	$.ajax({
			type : "GET",
			url : "createCompany.php",
			dataType : "html",
			cache : "false",
			data : {
				
				comp : comp,
			},
			success : function(html, textStatus){
				$("#new_comp").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
	// <!--1 Show subcat-->
function get_sub(){
	var catId =$("#catId").val();
	//alert(catId);
	$.ajax({
			type : "GET",
			url : "userscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				catId : catId,
			},
			success : function(html, textStatus){
				$("#suboption").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
	// <!--2 Show products-->
	function get_prod(){
		var subCatId =$("#subCatId").val();
		//alert(subCatId);
		$.ajax({
				type : "GET",
				url : "userscript.php",
				dataType : "html",
				cache : "false",
				data : {
					
					subCatId : subCatId,
				},
				success : function(html, textStatus){
					$("#prodoption").html(html);
				},
				error : function(xht, textStatus, errorThrown){
					alert("Error : " + errorThrown);
				}
		});
	}



	// $.post('../api/index.php', {action:'listStocks'}, function(){
		
	// })

	$.getJSON('../aapl-c.json', function (data) {
    // Create the chart
    Highcharts.stockChart('chartContainer', {


        rangeSelector: {
            selected: 1
        },

        title: {
            text: '<?php echo $stockName; ?> Stock Price'
        },

        series: [{
            name: '<?php echo $stockName; ?> Stock Price',
            data: data,
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
});

</script>
</body>
</html>