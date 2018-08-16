

<?php
require("db.php");
if(isset($_POST['newItemName']))
{
	$itemName = $_POST['newItemName'];
	$unityPrice = $_POST['newItemPrice'];
	$sql = $db->query("INSERT INTO `items`(`itemName`, `unityPrice`, `inDate`, `addedBy`) 
	VALUES ('$itemName','$unityPrice',now(),'me')
	")or die(mysqli_error());
	header("location:index.php");
	exit();
}
if(isset($_POST['updateItemName']))
{
	$itemName = $_POST['updateItemName'];
	$unityPrice = $_POST['updateItemPrice'];
	$itemId = $_POST['updateItemId'];
	$sql = $db->query("UPDATE `items` SET `itemName`='$itemName',`unityPrice`='$unityPrice' WHERE `itemId` ='$itemId'") or die(mysqli_error());
	header("location:index.php");
	exit();
}
?>
<?php // Destry session if it hasn't been used for 15 minute.
session_start();
if (!isset($_SESSION["username"])) {
 header("location: login.php"); 
    exit();
}
?>
<?php 
$session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
include "db.php"; 
$sql = $db->query("SELECT * FROM users WHERE loginId='$username' AND pwd='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) { 
	while($row = mysqli_fetch_array($sql)){ 
			 $thisid = $row["id"];
			 $names = $row["names"];
			 $account_type = $row["account_type"];
			}
		} 
		else{
		echo "
		
		<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
		<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
		Or<p><a href='../pages/logout.php'>Click Here to login again</a></p>
		
		";
	    exit();
	}
?>
<?php
// CHECK IF INVOICE EXISTS
if(isset($_GET['invoiceNo']))
{
	$purchaseOrder = $_GET['invoiceNo'];
	//echo $purchaseOrder;
	$sql = $db->query("SELECT 
T.`transactionID`, T.`itemCode`,I.`itemName`,I.kode,
T.`qty`,I.`unit`,  T.`trUnityPrice`, 

T.`customerRef`, T.`customerName`, T.`docRefNumber`, 
T.`deliverlyNote`, T.`operationNotes`, T.`doneOn`, T.`doneBy`, 

T.`qty` * T.`trUnityPrice` AS Total,`operation`
FROM `transactions` T INNER JOIN `items` I 
ON T.`itemCode` = I.`itemId`

WHERE `purchaseOrder`='$purchaseOrder' AND `operation` = 'out'
")or die(mysqli_error());
	
$sql1 = $db->query("SELECT 

T.`transactionID`, T.`customerRef`, T.`customerName`, T.`docRefNumber`,
T.`deliverlyNote`, T.`operationNotes`, T.`doneOn`, T.`doneBy`
FROM `transactions` T INNER JOIN `items` I 
ON T.`itemCode` = I.`itemId`

WHERE `purchaseOrder`='$purchaseOrder' AND `operation` = 'out' 
ORDER BY transactionID DESC LIMIT 1")or die(mysqli_error());
	
	$countout = mysqli_num_rows($sql);
	if($countout > 0)
	{
		WHILE($row= mysqli_fetch_array($sql1))
		{
			$customerPhone = $row['customerRef'];
			$customerName = $row['customerName'];
			$address = $row['deliverlyNote'];
			$comment = $row['operationNotes'];
			$tosign = $row['doneBy'];
			$dateDone = date( "d-m-Y", strtotime($row['doneOn']) );
		}
		
		$n=0;
		$dynamicList="";
		$invoiceTotal="";
		
		WHILE($row= mysqli_fetch_array($sql))
		{
			$n++;		
			$pricetotal =$row['Total'];
			$dynamicList.='<tr>
					<td>'.$n.'</td>
					<td>'.$row['itemName'].'</td>
					<td>#RRA_'.$row['kode'].'</td>
					<td>'.number_format($row['qty']).'</td>
					<td>'.number_format($row['trUnityPrice']).' Rwf</td>
					<td>'.number_format($row['Total']).' Rwf</td>
				</tr>';					

		$invoiceTotal += $pricetotal;
		}$totalWithTax = $invoiceTotal + ($invoiceTotal*0.00);
	}
}
else{
	$purchaseOrder = '';
	//echo $purchaseOrder;
			$customerPhone = '';
			$customerName = '';
			$address = '';
			$comment = '';
			$tosign = '';
			$dateDone = '';

		$dynamicList="";
		$invoiceTotal="";
			$pricetotal =1;
			$dynamicList.='<center><h3>Select an invoice to display</h3></center>';					

		$invoiceTotal =1;
		$totalWithTax = 1;
}
?>
<!DOCTYPE html>
<html>
    
<!-- Mirrored from moltran.coderthemes.com/dark/tables-editable.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 10 Apr 2016 14:12:46 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="assets/images/favicon_1.ico">

        <title>Inventory | <?PHP echo $purchaseOrder;?></title>
        
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css">
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css">
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css">

        <script src="assets/js/modernizr.min.js"></script>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="js/jquery.js"></script>

    </head>


    <body class="fixed-left">
        
        <!-- Begin page -->
        <div id="wrapper">
        
           <!-- Top Bar Start -->
            <?php include('topheader.php');?>
			<!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <div class="user-details">
                        <div class="pull-left">
                            <img src="assets/images/users/<?php echo $thisid;?>.jpg" alt="" class="thumb-md img-circle">
                        </div>
                        <div class="user-info">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $names;?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li>
                                    <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li>
                                    <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li>
                                    <li><a href="logout.php"><i class="md md-settings-power"></i> Logout</a></li>
                                </ul>
                            </div>
                            
                            <p class="text-muted m-0"><?php echo $account_type;?></p>
                        </div>
                    </div>
                   
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>
                            <li class="">
                                <a href="index.php" class="waves-effect waves-light "><i class="ion-ios7-gear"></i><span>HOME</span></a>
                            </li>
                            <li class="has_sub">
                                <a href="products.php" class="waves-effect waves-light"><i class="ion-bag"></i><span>Products</span>
                                    <span class="pull-right"><i class="md md-add"></i></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li ><a href="list.php" class="waves-effect waves-light "><i class="ion-ios7-pulse-strong"></i>Product List</a></li>
                                    <li ><a href="product.php" class="waves-effect waves-light "><i class="ion-ios7-pulse-strong"></i>Buy And Sell</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="po.php" class="waves-effect waves-light"><i class="ion-android-contacts"></i><span>People</span>
                                    <span class="pull-right"><i class="md md-add"></i></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li ><a href="po.php" class="waves-effect waves-light "><i class="ion-android-contact"></i>Users</a></li>
                                    <li><a href="po.php" class="waves-effect waves-light"><i class="ion-android-social-user"></i>Clients</a></li>
                                    <li><a href="po.php" class="waves-effect waves-light"><i class="ion-android-social"></i>Supplier</a></li>
                                    
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="" class="waves-effect waves-light"><i class="ion-ios7-albums"></i><span>Documents</span>
                                    <span class="pull-right"><i class="md md-add"></i></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li class="active"><a href="javascript:void()" class="waves-effect waves-light active"><i class="ion-document"></i>Invoices</a></li>
                                    <li><a href="po.php" class="waves-effect waves-light"><i class="ion-clipboard"></i>Proforma Invoice</a></li>
                                    <li><a href="po.php" class="waves-effect waves-light"><i class="ion-document-text"></i>Purchase Order</a></li>
                                </ul>
                                </a>
                            </li>
                            <li class="has_sub">
                                <a href="reports.php" class="waves-effect waves-light"><i class="ion-ios7-pulse-strong"></i><span>KPI Reports</span>
                                    <span class="pull-right"><i class="md md-add"></i></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li><a href="reports.php">General</a></li>
                                    <li><a href="fmcg.php">Faster | Slow Items</a></li>
                                    <li><a href="roi.php">Return On Investment</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        

                        
                        <div class="row">
                             <!-- USER LIST -->
                            <div class="col-lg-3 hidden-print">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading">
										<div class="row">
										    <div class="col-md-4">
												<h4 class="panel-title" style="color: #fff;">PRO INV</h4>
											</div>
											<div class="col-md-8">
												<div class="input-group">
													<input type="text" id="searchInvoice" onkeyup="search()" name="example-input1-group2" class="form-control input-sm" placeholder="Search...">
													<span class="input-group-btn">
														<button type="button" class="btn-sm btn waves-effect waves-light btn-primary"><i class="fa fa-search"></i></button>
													</span>
												</div>
											</div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div id="userResurts" class="inbox-widget nicescroll mx-box">
                                            <?php
											$sqltotalperUser = $db->query("SELECT purchaseOrder, operationStatus,
																			customerName, transactionID 
																			FROM transactions
																			WHERE operation = 'out'
																			GROUP BY purchaseOrder
																			ORDER BY transactionID DESC");
												$n = 0;
												
												while($row = mysqli_fetch_array($sqltotalperUser))
												{
													$status = $row['operationStatus'];
												if($status ==1)
												{
													$newstatus = '<span class="label label-success">Paid</span>';
												}
												elseif($status ==2)
												{
													$newstatus = '<span class="label label-warning">HalfPaid</span>';
												}
												else
												{
													$newstatus = '<span class="label label-danger">Unpaid</span>';
												}
													echo'
													<a href="invoices.php?invoiceNo='.$row['purchaseOrder'].'">
														<div class="inbox-item">
															<p class="inbox-item-author">'.$row['purchaseOrder'].'</p>
															<p class="inbox-item-text">'.$row['customerName'].'</p>
															<p class="inbox-item-date">'.$newstatus.'</p>
														</div>
													</a>';
												}
											
											?>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-md-9">
                                <div class="panel panel-default">
                                    <!-- <div class="panel-heading">
                                        <h4>Invoice</h4>
                                    </div> -->
                                    <div class="panel-body">
                                        <div class="clearfix">
                                            <div class="pull-left">
											<img src="assets/images/logo_dark.png" width="200" alt="giraict">
                                                
                                            </div>
                                            <div class="pull-right">
                                            <button class="btn btn-danger waves-effect waves-light breadcrumb pull-right" onclick="generateInvoice();"  data-toggle="modal" data-target="#outmodel">SELL</button>
                                                <h4>Pro Invoice # <br>
                                                    <strong><?PHP echo $purchaseOrder;?></strong>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-left m-t-30">
                                                    <address>
                                                      <strong>RBC.</strong><br>
                                                      KIGALI/ NYARUGENGE<br>
                                                      <abbr title="Tin number">TIN:</abbr> 000125488/763<br/>
                                                      <abbr title="Email">E:</abbr> info@RBC.rw<br/>
                                                      <abbr title="Phone">P:</abbr> 0786275982
                                                     </address>
                                                </div>
                                                <div class="pull-right m-t-30">
													<address>
                                                      <strong><?PHP echo $customerName;?>.</strong><br>
                                                      <?PHP echo $address;?><br>
                                                      <abbr title="Phone">P:</abbr> <?PHP echo $customerPhone;?>
                                                      </address>
                                                    <p><strong>Proforma Invoice Date: </strong> <?PHP echo $dateDone;?></p>
                                                </div>
                                            <center><h4>PROFORMA INVOICE</h4></center>
                                                
											</div>
                                        </div>
                                         <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table m-t-30">
                                                        <thead>
                                                            <tr>
																<th>#</th>
																<th>Item</th>
																<th>Description</th>
																<th>Quantity</th>
																<th>Unit Cost</th>
																<th>Total</th>
															</tr>
														</thead>
                                                        <tbody>
															<?php echo $dynamicList;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                          <div class="col-md-12">
                                                
                                                <div class="pull-left ">
                                                                                                         
                                                      <div class="form-group no-margin"> 
								<label for="InvoiceOperationNotes" class="control-label"><strong>COMMENT:</strong></label> 
								<textarea required class="form-control autogrow" rows="4" placeholder="<?php echo $comment;?>" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 80px"></textarea>
							</div>
By: <?php echo $tosign;?><br/>
<abbr title="................................">Sign:</abbr>							
							
							
                                                      
                                                </div>
                                                <div class="pull-right m-t-50">
                                                    <p class="text-right"><b>Sub-total:</b> <?PHP 
														echo number_format($invoiceTotal, 2, '.', ',');
														?></p>
													<p class="text-right">Discout: 0.0%</p>
													<p class="text-right">VAT: 0.0%</p>
													<hr>
													<h3 class="text-right">RWF
														<?PHP 
														echo number_format($totalWithTax, 2, '.', ',');
														?>
													</h3>
                                                </div>
                                            </div>
											
                                        </div>
                                        <hr>
                                        <div class="hidden-print">
                                            <div class="pull-right">
                                                <a href="javascript:window.print()" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                <a href="#" class="btn btn-primary waves-effect waves-light">Submit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <footer class="footer text-right">
                    2018 © RBC.
                </footer>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->
    
<!--  Modal content for the above example -->
<div class="modal fade bs-example-modal-lg" id="outmodel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg">
       <div class="modal-content p-0 b-0">
      
            <div class="panel panel-color panel-primary">
                <div class="panel-heading"> 
                    <div class="row">
                        <div class="col-md-3">
                            INVOICE NO. <div id="generatedInv"><input name="InvoinceNo" id="InvoinceNo" onkeyup="checkInvoince();bringPrint();" class="form-control"></div>
                        </div>
                        <div class="col-md-2">
                            DOC REF
                            <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-inverse dropdown-toggle" data-toggle="dropdown" style="overflow: hidden; position: relative"><span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0)">Cash</a></li>
                                    <li><a href="javascript:void(0)">Check</a></li>
                                    <li><a href="javascript:void(0)">Bank Slip</a></li>
                                    <li class="divider"></li>
                                    <li><a href="javascript:void(0)">Other</a></li>
                                </ul>
                            </div>
                                <input required name="DocNo" id="DocNo" value="N/A" onkeyup="checkInvoince()" onchange="checkInvoince()" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            TO NAME:<input required name="InvoiceCustomerName" id="InvoiceCustomerName" value="N/A" class="form-control">
                        </div>
                        <div class="col-md-2">
                            TO ADDRESS<input required name="InvoiceDeliverlyNote" id="InvoiceDeliverlyNote" value="N/A" class="form-control">
                        </div>
                        <div class="col-md-2">
                            TO CONTACTS<input required name="InvoiceCustomerRef" id="InvoiceCustomerRef" value="N/A" class="form-control">
                        </div>                                      
                    </div>
                </div> 
                <div class="panel-body"> 
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group"> 
                                        <label for="itemInvoiceCode" class="control-label">Item Name:</label>
                                        <select class="select2 form-control" data-placeholder="Choose an Item..." name="itemInvoiceCode" id="itemInvoiceCode" onchange="getInvoiceItemsDet()">
                                            <option >Select item</option>
                                            <?php
                                                $sql = $db->query("SELECT * FROM items ORDER BY itemId DESC");
                                                $n=0;
                                                WHILE($row= mysqli_fetch_array($sql))
                                                {
                                                    echo'<option value="'.$row['itemId'].'">'.$row['itemName'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="invioceItems">
                                    <div class="col-sm-3">
                                        <div class="form-group"> 
                                            <label for="itemCode" class="control-label">Quantity:</label>
                                            <div class="input-group">
                                                <input class="form-control" name="" disabled id=""/>
                                                <span class="input-group-addon">Unit</span>                                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"> 
                                            <label for="itemCode" class="control-label">Unit Price:</label>
                                            <input class="form-control" name="" disabled id=""/>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group"> 
                                            <label for="itemCode" class="control-label">Total Price:</label>
                                            <div class="input-group">
                                                <input class="form-control" name="" disabled id=""/>
                                                <span class="input-group-addon">RWF</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="itamePlace"></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div id="infoDiv"></div>
                                    <div id="listInvoiceTable"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-12"> 
                            <div class="form-group no-margin"> 
                                <label for="InvoiceOperationNotes" class="control-label">Note:</label> 
                                <textarea required class="form-control autogrow" name="inOpNote" id="inOpNote" 
                                placeholder="Write something about this operation" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 104px"></textarea>
                            </div> 
                        </div> 
                    </div> 
                    <hr/>
                    <div class="row"> 
                        <div class="col-md-12"> 
                            <div class="pull-right">
                                <div id="printInvoice">
                                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>              
            </div>
        
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/js/jquery.app.js"></script>

		
<script> <!--5 Load product to Edit-->
function initItem(){
	var initItem = '1';
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				initItem : initItem,
			},
			success : function(html, textStatus){
				$("#itamePlace").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
<script> <!--5 Load product to Edit-->
function editItem(itemId){
	var editItem = itemId
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				editItem : editItem,
			},
			success : function(html, textStatus){
				$("#itamePlace").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// USER INVOICE
function search(){
	var searchInvoice = $("#searchInvoice").val();	
	//alert(searchInvoice);
	//var uId=userNam
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				searchInvoice : searchInvoice,
			},
			success : function(html, textStatus){
				$("#userResurts").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}

</script>
	
	</body>

<!-- Mirrored from moltran.coderthemes.com/dark/tables-editable.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 10 Apr 2016 14:12:50 GMT -->
</html>