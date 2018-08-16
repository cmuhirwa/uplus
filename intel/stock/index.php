
            <?php include('topheader.php');?>
			<!-- Top Bar End -->

        <!-- assets/images/users/<?php echo $thisid;?>.jpg -->
            <!-- ========== Left Sidebar Start ========== -->
            <?php
            	include_once "modules/sidebar.php";
            ?>
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
                            <div class="col-sm-12">
                                <h4 class="pull-left page-title">WELCOM TO RBC SYSTEM!</h4>
                                
                            </div>
                        </div>

                        <!-- Start Widget -->
                        <div class="row">
                            <a href="list.php">
							<div class="col-md-6 col-sm-6 col-lg-3">
                                <div class="mini-stat clearfix bx-shadow bg-info">
                                    <span class="mini-stat-icon"><i class="ion-social-usd"></i></span>
                                    <div class="mini-stat-info text-right">
                                        <span class="counter"><?php $sqllist= $db->query("select * from items");
										$countlist = mysqli_num_rows($sqllist);
										echo $countlist;?></span>
                                        Item List
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase text-white m-0">ADD NEW ITEMS<span class="pull-right"></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
							</a>
							<a href="products.php">
                            <div class="col-md-6 col-sm-6 col-lg-3">
                                <div class="mini-stat clearfix bg-purple bx-shadow">
                                    <span class="mini-stat-icon"><i class="ion-ios7-cart"></i></span>
                                    <div class="mini-stat-info text-right">
                                        <span class="counter"><?php
$totalHave ="";												 
$totalqty="";			
			$sql = $db->query("SELECT I.`itemId`, I.`itemName`,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) Ins,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Outs,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) -
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Balance
,I.`unit`, I.`unityPrice`
FROM `items` I ORDER BY Balance DESC");
										$n=0;
										WHILE($row= mysqli_fetch_array($sql))
										{
											$n++;
											$qty = $row['Balance'];
											$up = $row['unityPrice'];
											$outstanding = $qty * $up;
											$totalqty = (int)$qty + (int)$totalqty;
											$totalHave = (int)$outstanding + (int)$totalHave;
										// var_dump($totalHave);
										}
										echo number_format((int)($totalHave))	;
										?> 
</span>
                                        WORTH
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase text-white m-0">PURCHASE AND SELL<span class="pull-right"><?php echo number_format((int)$totalqty);?></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
							</a>                            
                            <div class="col-md-6 col-sm-6 col-lg-3">
                                <div class="mini-stat clearfix bg-success bx-shadow">
                                    <span class="mini-stat-icon"><i class="ion-eye"></i></span>
                                    <div class="mini-stat-info text-right">
                                        <span class="counter">20,544 Rwf</span>
                                        Loan
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase text-white m-0">From <span class="pull-right">13 Clients</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<a href="roi.php">
                            <div class="col-md-6 col-sm-6 col-lg-3">
                                <div class="mini-stat clearfix bg-primary bx-shadow">
                                    <span class="mini-stat-icon"><i class="ion-android-contacts"></i></span>
                                    <div class="mini-stat-info text-right">
                                        <span class="counter"><?php
											$totaroi = "";
											$totali = "";
											$sqltotalperUser = $db->query("SELECT itemCode, itemName, sum(`GAIN_PER_OPERATION`) totalgain, sum(`investment`) investment 
															FROM `returnoninvestment` 
															GROUP BY `itemCode` 
															ORDER BY totalgain desc");
												$n = 0;
												while($row = mysqli_fetch_array($sqltotalperUser))
												{
													$roi = $row['totalgain'];
													$investment = $row['investment'];
												$totaroi = (int) $roi +(int) $totaroi;
												$totali = (int)$investment +(int) $totali;
											}
											echo number_format((int)$totaroi);
											?> Rwf</span>
                                        RETURN
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase text-white m-0">TOTAL INVESTMENT<span class="pull-right"><?php echo number_format((int)$totali);?></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
							</a>
					   </div> 
                        <!-- End row-->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-border panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">MOVEMENT OF SELLES ORDER</h3> 
                                    </div> 
                                    <div class="panel-body"> 
                                        <div id="combine-chart">
                                            <div id="website-stats" style="height: 320px" class="flot-chart"></div>
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
<div class="modal fade bs-example-modal-lg" id="inmodel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
	<div class="modal-dialog modal-lg">
	   <div class="modal-content p-0 b-0">
	   <form onsubmit="return checkExistance()" method="post">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading"> 
						<div class="row">
							<div class="col-md-3">
							PURC ORD <input required name="purchaseOrder" id="purchaseOrder" onkeyup="checkExistance()" class="form-control">
							</div>
							<div class="col-md-2">
							DELIV NOT<input required name="deliverlyNote" id="deliverlyNote" onkeyup="checkExistance()" onchange="checkExistance()" class="form-control">
							</div>
							<div class="col-md-2">
							DOC REF NO.
							
							<input required name="docRefNumber" id="docRefNumber" class="form-control">
							</div>
							<div class="col-md-3">
							PROVIDER NAME<input required name="customerName" id="customerName" class="form-control">
							</div>
							<div class="col-md-2">
							PROVIDER REF.<input required name="customerRef" id="customerRef" class="form-control">
							</div>										
						</div>
				</div> 
				<div class="panel-body"> 
					
				<div class="panel panel-default">
                                    <div class="panel-heading">
                                        	<div class="row">
									<div class="col-sm-3">
										<div class="form-group"> 
											<label for="itemCode" class="control-label">Item Name:</label>
											<select class="form-control" name="itemCode" id="itemCode" onchange="getItemsDet()">
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
									<div id="qtydiv">
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
								
								<div id="itamePlace">
								
								</div>
									
									
								</div>
							
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
											<div id="infoDiv">
								</div>
                                                <div id="listTable">
													
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				
				<div class="row"> 
					<div class="col-md-12"> 
						<div class="form-group no-margin"> 
							<label for="operationNotes" class="control-label">Note:</label> 
							<textarea required class="form-control autogrow" name="operationNotes" id="operationNotes" 
							placeholder="Write something about this operation" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 104px"></textarea>
						</div> 
					</div> 
				</div>
				<hr/>
				<div class="row"> 
					<div class="col-md-12"> 
						<div class="pull-right">
							<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button> 
							<button type="button" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></button> 
						</div>
					</div>
				</div>				
				</div>
			</div>
		</form>
		</div><!-- /.modal-content -->
	 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	<!-- END wrapper -->
	<!--  Modal content for the above example -->
<div class="modal fade bs-example-modal-lg" id="outmodel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
	<div class="modal-dialog modal-lg">
	   <div class="modal-content p-0 b-0">
	  
			<div class="panel panel-color panel-primary">
				<div class="panel-heading"> 
					<div class="row">
						<div class="col-md-3">
							INVOICE NO.<input name="InvoinceNo" id="InvoinceNo" onkeyup="checkInvoince();bringPrint();" class="form-control">
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
								<input required name="DocNo" id="DocNo" onkeyup="checkInvoince()" onchange="checkInvoince()" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							TO NAME:<input required name="InvoiceCustomerName" id="InvoiceCustomerName" class="form-control">
						</div>
						<div class="col-md-2">
							TO ADDRESS<input required name="InvoiceDeliverlyNote" id="InvoiceDeliverlyNote" class="form-control">
						</div>
						<div class="col-md-2">
							TO CONTACTS<input required name="InvoiceCustomerRef" id="InvoiceCustomerRef" class="form-control">
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
										<select class="form-control" name="itemInvoiceCode" id="itemInvoiceCode" onchange="getInvoiceItemsDet()">
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
								<textarea required class="form-control autogrow" name="test" id="test" 
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
	<!-- END wrapper -->
	   
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

	     <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/pages/datatables.init.js"></script>
		<!-- Modal-Effect -->
        <script src="assets/plugins/modal-effect/js/classie.js"></script>
        <script src="assets/plugins/modal-effect/js/modalEffects.js"></script>
	

    
        <script src="assets/plugins/flot-chart/jquery.flot.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.time.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.resize.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.pie.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.selection.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.stack.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.crosshair.js"></script>
        <script src="assets/pages/jquery.flot.init.js"></script>
        
        
        
		
		<script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').dataTable();
                $('#datatable-keytable').DataTable( { keys: true } );
                $('#datatable-responsive').DataTable();
                $('#datatable-scroller').DataTable( { ajax: "assets/plugins/datatables/json/scroller-demo.json", deferRender: true, scrollY: 380, scrollCollapse: true, scroller: true } );
                var table = $('#datatable-fixed-header').DataTable( { fixedHeader: true } );
            } );
            TableManageButtons.init();
        </script>
	
<script> <!--1 INJECT IN THE STOCK-->
function initItem()
{
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
<!--5 Load product to Edit-->
function editItem(itemId)
{
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
<!--5 Load product to Edit-->
function getItemsDet()
{
	var itemIdtoGet = $("#itemCode").val();	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				itemIdtoGet : itemIdtoGet,
			},
			success : function(html, textStatus){
				$("#qtydiv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
<!--5 Load product to Edit-->
function checkExistance()
{
	var purchaseOrder1 = document.getElementById('purchaseOrder').value;
	var deliverlyNote1 = document.getElementById('deliverlyNote').value;
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				purchaseOrder1 : purchaseOrder1,
				deliverlyNote1 : deliverlyNote1,
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
<!--5 Load product to Edit-->
function insertItem()
{
	var purchaseOrder = document.getElementById('purchaseOrder').value;
	var deliverlyNote = document.getElementById('deliverlyNote').value;
	var unityPrice = document.getElementById('unityPrice').value;
	var itemCode = document.getElementById('itemCode').value;
	var qty = document.getElementById('qty').value;
	var docRefNumber = document.getElementById('docRefNumber').value;
	var customerName = document.getElementById('customerName').value;
	var customerRef = document.getElementById('customerRef').value;
	var operationNotes = document.getElementById('operationNotes').value;
	
	
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				purchaseOrder : purchaseOrder,
				deliverlyNote : deliverlyNote,
				unityPrice : unityPrice,
				itemCode : itemCode,
				qty : qty,
				docRefNumber : docRefNumber,
				customerName : customerName,
				customerRef : customerRef,
				operationNotes : operationNotes,
				
				
			},
			success : function(html, textStatus){
				$("#infoDiv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>

<script> <!--2 INJECT IN THE STOCK-->
//1 CHECK IF THE INVOICE EXISTS
function checkInvoince(){
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	var DocNo = document.getElementById('DocNo').value;
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				InvoinceNo : InvoinceNo,
				DocNo : DocNo,
			},
			success : function(html, textStatus){
				$("#listInvoiceTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// 2 SELECT ITEMS FROM DB
function getInvoiceItemsDet(){
	var invioceItemIdtoGet = $("#itemInvoiceCode").val();	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				invioceItemIdtoGet : invioceItemIdtoGet,
			},
			success : function(html, textStatus){
				$("#invioceItems").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// 3 TAKE OUT AN ITEM
function ouItem(){
	
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	var InvoiceDeliverlyNote = document.getElementById('InvoiceDeliverlyNote').value;
	var InvoiceUnityPrice = document.getElementById('InvioceUnityPrice').value;
	var itemInvoiceCode = document.getElementById('itemInvoiceCode').value;
	var InvioceQty = document.getElementById('InvoiceQty').value;
	var DocNo = document.getElementById('DocNo').value;
	var InvoiceCustomerName = document.getElementById('InvoiceCustomerName').value;
	var InvioceustomerRef = document.getElementById('InvoiceCustomerRef').value;
	var InvioceOperationNotes = document.getElementById('test').value;
	
	//alert(InvioceOperationNotes);
	//alert('HELLO!');
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				InvoinceNo : InvoinceNo,
				DocNo : DocNo,
				InvoiceDeliverlyNote : InvoiceDeliverlyNote,
				InvoiceUnityPrice : InvoiceUnityPrice,
				itemInvoiceCode : itemInvoiceCode,
				InvioceQty : InvioceQty,
				InvoiceCustomerName : InvoiceCustomerName,
				InvioceustomerRef : InvioceustomerRef,
				InvioceOperationNotes : InvioceOperationNotes,
				
				
				
			},
			success : function(html, textStatus){
				$("#listInvoiceTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// 4 REMOVE AN ITEM
function removeOnInv(removeTransaction){
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	var DocNo = document.getElementById('DocNo').value;
	
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				removeTransaction : removeTransaction,
				InvoinceNo : InvoinceNo,
				DocNo : DocNo,
				
			},
			success : function(html, textStatus){
				$("#listInvoiceTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// 5 INVOICE ITEM TOTAL
function invoiceTotal(){
	var unityPriceToAdd = document.getElementById('InvioceUnityPrice').value;
	var invoiceQtyToAdd = document.getElementById('InvoiceQty').value;
	
	var totalPrice = unityPriceToAdd * invoiceQtyToAdd;
	document.getElementById("invoiceTotalPrice").innerHTML = '<input class="form-control" value="'+totalPrice+'"disabled/><span class="input-group-addon">RWF</span>';
	
}

function bringPrint(){ 
	var InvoinceNo = document.getElementById('InvoinceNo').value;
	document.getElementById("printInvoice").innerHTML = '<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button> <a href="invoices.php?invoiceNo='+InvoinceNo+'" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></button>';
}						
</script>
	</body>

<!-- Mirrored from moltran.coderthemes.com/dark/tables-editable.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 10 Apr 2016 14:12:50 GMT -->
</html>