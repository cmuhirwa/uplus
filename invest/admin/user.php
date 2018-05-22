
<?php
	error_reporting(E_ALL); 
	ini_set('display_errors', 1);
	if(isset($_POST['addpst']))
	{
		$itemName = $_POST['itemName'];
		$productCode = $_POST['productCode'];
		$itemCompanyCode = $_POST['itemCompanyCode'];
		$unit = $_POST['unit'];
		$unityPrice = $_POST['unityPrice'];
		$quantity = $_POST['quantity'];
		$description = $_POST['description'];
		echo $itemName;
		echo' itemName<br/>';
		echo $productCode;echo' productCode<br/>';
		echo $itemCompanyCode;echo' itemCompanyCode<br/>';
		echo $unit;echo' unit<br/>';
		echo $unityPrice;echo' unityPrice<br/>';
		echo $quantity;echo' quantity<br/>';
		echo $description ;echo' description<br/>';
		$addtheitem = $db->query("INSERT INTO `items1`(`itemName`, `productCode`, `itemCompanyCode`, `unit`, `unityPrice`, description) 
		VALUES ('$itemName','$productCode','$itemCompanyCode','$unit','$unityPrice','$description')
		")or die (mysqli_error());
		
		
		$sql2 = $db->query("SELECT * FROM items1 ORDER BY itemId DESC limit 1");
			while($row = mysqli_fetch_array($sql2)){
				$Imagename = $row['itemId'];
			}
			
		$sql5 = $db->query("INSERT INTO `bids`
		(`trUnityPrice`, `qty`, `itemCode`, `operation`,`companyId`,`operationStatus`, doneBy) 
		VALUES  ('$unityPrice','$quantity','$Imagename','In','$itemCompanyCode','1','$thisid')")or die(mysqli_error());
		
		if ($_FILES['fileField']['tmp_name'] != "") {																	 										 
			$newname = ''.$Imagename.'.jpg';
			move_uploaded_file( $_FILES['fileField']['tmp_name'], "../products/$newname");
		}
		header("location: user.php");
	}
	elseif(isset($_POST['editpst']))
	{
		$postId = $_POST['postId'];
		$postTitle = $_POST['postTitle'];
		$productCode = $_POST['productCode'];
		$quantity = $_POST['quantity'];
		$price = $_POST['price'];
		$priceStatus = $_POST['priceStatus'];
		$postDesc = $_POST['postDesc'];
		$postedBy = $username; //$_POST['postedBy'];
		$postDeadline = $_POST['postDeadline'];
		$productLocation = $_POST['productLocation'];
		
		include ("db.php");		
		$sql = $db->query("UPDATE posts SET postTitle='$postTitle',productCode='$productCode',quantity='$quantity',price='$price',priceStatus='$priceStatus',postDesc='$postDesc',postedBy='$postedBy',postDeadline='$postDeadline',productLocation='$productLocation' WHERE postId = '$postId'")or die (mysqli_error());
		
		header("location: user.php");
	}			
?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php
	include ("../db.php");
	include'userheader.php';
	require("functions.php");
?>

<!-- main sidebar -->
<div id="new_comp">
	<div id="page_content">
	    <div id="page_content_inner">
			<h4 class="heading_b uk-margin-bottom">DashBoard</h4>
			<?php 
				$sqlseller = $db->query("SELECT * FROM company1 WHERE companyUserCode = '$thisid'");
				$countComanies = mysqli_num_rows($sqlseller);
				if($countComanies>0)
					{
						while($row = mysqli_fetch_array($sqlseller)) 
							{
								$comanyId = $row['companyId'];
			?>
				
				<div class="uk-grid uk-grid-width-small-1-2 uk-grid-width-large-1-3 uk-grid-width-xlarge-1-4 uk-text-center " id="dashboard_sortable_cards" >
	                <div>
	                    <a href="forums.php">
						<div class="md-card md-card-hover md-card-overlay">
							<div class="md-card-content">
		                        <div style="vertical-align: middle; margin-top: 10%; font-size: 3rem">
	                        		<span class="" style=""><span class="countUpMe"><?php echo count(getForums()); ?></span></span>
	                        	</div>	 
	                        </div>
	                        <div class="md-card-overlay-content">
	                            <div class="uk-clearfix md-card-overlay-header">
	                                <i class="md-icon material-icons md-card-overlay-toggler">&#xE5D4;</i>
	                                <h3>
	                                    Forums
	                                </h3>
	                            </div>
	                            <?php echo $row['companyDescription'];?>
	                        </div>
	                    </div>
	                    </a>
	                </div>

	                <!-- Customers -->
	                <div>
	                    <a href="customers.php?compId=<?php echo $comanyId;?>">
						<div class="md-card md-card-hover md-card-overlay">
	                        <!-- <div class="md-card-content uk-flex uk-flex-center uk-flex-middle">
	                            <span class="peity_conversions_large peity_data">5,3,9,6,5,9,7</span>
	                        </div> -->
	                        <div class="md-card-content">
	                        	<?php
	                        		$sqlClients = $db->query('SELECT * FROM clients');
									$countClients = mysqli_num_rows($sqlClients);
									$countUsers = mysqli_num_rows($db->query('SELECT * FROM uplus.users'));
	                        	?>
	                        	<div style="vertical-align: middle; margin-top: 10%; font-size: 3rem">
	                        		<span class="" style=""><span class="countUpMe"><?php echo $countClients; ?></span>/
	                        		<span><?php echo $countUsers; ?></span></span>
	                        	</div>	                        	
		                        <!-- <div class="epc_chart" data-percent="53" data-bar-color="#009688">
	                                <span class="epc_chart_text"><span class="countUpMe">53</span>%</span>
	                            </div> -->
                            </div>
	                        <div class="md-card-overlay-content">
	                            <div class="uk-clearfix md-card-overlay-header">
	                                <i class="md-icon material-icons md-card-overlay-toggler">&#xE5D4;</i>
	                                <h3>
	                                    Customers
	                                </h3>
	                            </div>
	                            Users with CSD accounts out of all system users
	                            <!-- <p>Current Customers:</p> -->
								<?php
									// $sqlClients = $db->query('SELECT * FROM clients');
									// echo $countClients = mysqli_num_rows($sqlClients);
								?>
	                        </div>
	                    </div>
						</a>
	                </div>

					<div>
						<a href="stocks.php">
						<div class="md-card md-card-hover md-card-overlay">
	                        <div class="md-card-content uk-flex uk-flex-center uk-flex-middle">
	                        	<div class="md-card-content">
	                        		<div style="margin-top: 55%; font-size: 3rem">
		                        		<span class="" style=""><span class="countUpMe"><?php $summary = brokerTransactionsSummary($Company->companyId); echo $summary['num']; ?></span></span>
		                        	</div>	 
	                        	</div>
	                        </div>
	                        <div class="md-card-overlay-content">
	                            <div class="uk-clearfix md-card-overlay-header">
	                                <i class="md-icon material-icons md-card-overlay-toggler">&#xE5D4;</i>
	                                <h3>
	                                    Transactions
	                                </h3>
	                            </div>
	                            Total Share Value:
	                            <span>
	                            	<?php
									$totalHave ="";												 
									$totalqty="";
									$sql = $db->query("SELECT I.`itemId`, I.`itemName`,
									IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) Ins,
									IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Outs,
									IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) -
									IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Balance
									,I.`unit`, I.`unitPrice`
									FROM `items1` I WHERE I.itemCompanyCode= '$companyid' ORDER BY Balance DESC");
									$n=0;
									$countResults = mysqli_num_rows($sql);
									if($countResults > 0){
										WHILE($row= mysqli_fetch_array($sql))
										{
											$n++;
											$qty = $row['Balance'];
											$up = $row['unitPrice'];
											$outstanding = $qty * $up;
											$totalqty = $qty + $totalqty;
											$totalHave = $outstanding + $totalHave;
										}
									}else{
										$totalHave = 0;
									}
									echo number_format($totalHave);
									?>  Rwf
								</span>
								Total ROI: 0 Rwf
	                        </div>
	                    </div>
						</a>
					</div>
	            
	                
		            <div>
	                    <div class="md-card md-card-hover md-card-overlay">
	                        <div class="md-card-content">
	                            <div class="epc_chart" data-percent="53" data-bar-color="#009688">
	                                <span class="epc_chart_text"><span class="countUpMe">53</span>%</span>
	                            </div>
	                        </div>
	                        <div class="md-card-overlay-content">
	                            <div class="uk-clearfix md-card-overlay-header">
	                                <i class="md-icon material-icons md-card-overlay-toggler">&#xE5D4;</i>
	                                <h3>
	                                    Engagement
	                                </h3>
	                            </div>
	                            <p>Feeds View: 11,340</p>
	                            <p>Feeds Likes: 783</p>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- tasks -->
	            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
	                <div class="uk-width-medium-1-3">
	                    <div class="md-card">
	                        <div class="md-card-content" style="height: 468px; !imporntant">
	                            <div class="uk-overflow-container">
	                                <h3>Youth Financial Literacy Seminar</h3>
	                                <br><p>Youth came together to learn how to save and invest money</p>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            	<div class="uk-width-medium-2-3">
	                    <div class="md-card">
                            <div class="uk-slidenav-position" data-uk-slideshow="{animation:'scale'}">
                                <ul class="uk-slideshow" style="max-height: 500px !important;">
                                    <li><img style="max-height: 100%" src="assets/img/gallery/young.jpg" alt=""></li>
                                    <li><img style="max-height: 100%" src="assets/img/gallery/img2.jpg" alt=""></li>
                                    <li><img style="max-height: 100%" src="assets/img/gallery/img3.jpg" alt=""></li>
                                </ul>
                                <a href="#" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
                                <a href="#" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
                                <ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-flex-center">
                                    <li data-uk-slideshow-item="0"><a href="#"></a></li>
                                    <li data-uk-slideshow-item="1"><a href="#"></a></li>
                                    <li data-uk-slideshow-item="2"><a href="#"></a></li>
                                    <li data-uk-slideshow-item="3"><a href="#"></a></li>
                                </ul>
                            </div>
                        </div>
	                </div>
	            </div>
			<?php
				}
				}
				else{
					echo'<a href="javascript:void()" onclick="addcomp()">ADD A COMPANY</a>';
				}
			?>							 
	    </div>
	</div>
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

    <script>
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
    </script>
	
<script> <!--0 Add Company-->
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
</script>	
<script> <!--1 Show subcat-->
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
</script>
<script> <!--2 Show products-->
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
</script>
<script> <!--3 start new post-->
function new_post(){
	var productId =$("#productId").val();
	//alert(productId);
	$.ajax({
			type : "GET",
			url : "userscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				productId : productId,
			},
			success : function(html, textStatus){
				$("#new_post_show").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
</body>
</html>