<?php
	include('topheader.php');?>

	<!-- Top Bar End -->
	<?php
	$totalHave ="";												 
	$tableshow ="";
	$sql = $db->query("SELECT I.`itemId`, I.`itemName`,
	IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) Ins,
	IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Outs,
	IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) -
	IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Balance
	,I.`unit`, I.`unityPrice`
	FROM `items` I ORDER BY Balance DESC");
	$n=0;
	while($row= mysqli_fetch_array($sql))
	{
		$n++;
		$qty = $row['Balance'];
		$up = $row['unityPrice'];
		$outstanding = $qty * $up;
		$tableshow.='<tr class="gradeX">
	        <td>'.$n.'</td>
	        <td>'.$row['itemName'].'
	        </td>
	        <td>'.number_format($row['Balance']).' '.$row['unit'].'s</td>
	        <td>'.number_format($row['unityPrice']).' Rwf</td>
	        <td>'.number_format($outstanding).' Rwf</td>
	        <td class="hidden-print"">
	            &nbsp;&nbsp;&nbsp;
				<a href="javascript:void()" onclick="itemInfo(itemInfoId='.$row['itemId'].')"   data-toggle="modal" data-target="#itemHist">info</a>
			</td>
	    </tr>';
		$totalHave = (int)$outstanding + (int)$totalHave;
	}
?>


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
                    <h4 class="pull-left page-title">Products in warehouse <i class="ion-ios7-cart-outline"></i></h4>
                </div>
            </div>


            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #ffffff;"><?php echo number_format($totalHave);?> RWF</h3>
                        </div>
                        <div class="panel-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="m-b-30">
									<!-- Large modal -->
                            <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#inmodel">Purchase</button>
							<button class="btn btn-danger waves-effect waves-light breadcrumb pull-right" onclick="generateInvoice();"  data-toggle="modal" data-target="#outmodel">Sale Orders</button>
									
									</div>
								</div>
							</div>
							<div id="itamePlace">
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
										<th>N/O</th><th>Item Name</th>
										<th>Quantity</th>
										<th>Unit Price</th>
										<th>Outstanding</th>
										<th class="hidden-print"">Actions</th>
									</tr>
                                </thead>


                                <tbody>
                                	<?php echo $tableshow;?>
                                </tbody>
                            </table>
							</div>
                        </div>
                    </div>
                </div>

            </div> <!-- End Row -->

        </div> <!-- container -->
                   
    </div> <!-- content -->
	<?php include"footer.php";?>
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
	   	<form id="purchaseOrderForm">
	    	<div class="panel panel-color panel-primary">
				<div class="panel-heading"> 
						<div class="row">
							<div class="col-md-3">
							PURC ORD <input required name="purchaseOrder" id="purchaseOrder" value="<?php echo $POrder->generateOrderNumber('P', $POrder->nextOrderNumber()) ?>"  onkeyup="checkExistance()" class="form-control">
							</div>
							<div class="col-md-2">
							DELIV NOT<input required name="deliverlyNote" id="deliverlyNote" value="N/A" onkeyup="checkExistance()" onchange="checkExistance()" class="form-control">
							</div>
							<div class="col-md-2">
							DOC REF NO.
							
							<input required name="docRefNumber" id="docRefNumber" value="N/A" class="form-control">
							</div>
							<!-- <div class="col-md-3">
							PROVIDER NAME<input required name="customerName" id="customerName" value="N/A" class="form-control">
							</div> -->
							<!-- <div class="col-md-2">
							PROVIDER REF.<input required name="customerRef" id="customerRef" value="N/A" class="form-control">
							</div>	 -->									
						</div>
				</div> 
				<div class="panel-body">					
					<div class="panel panel-default">
	                    <div class="panel-heading">
	                    	<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="itemCode" class="control-label">Supplier/Vendor:</label>
										<select class="select2 form-control" data-placeholder="Choose a vendor..."  name="orgInput" id="orgInput">
											<option >Select organisation</option>
											<?php
												$suppliers = $Supplier->list();
												for ($n=0; $n < count($suppliers) ; $n++) {
													$supplier = $suppliers[$n];
													echo'<option value="'.$supplier['id'].'">'.$supplier['name'].'</option>';
												}
											?>
										</select>
									</div>
								</div>
								<div style="margin: 20px"></div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for="itemCode" class="control-label">Item Name:</label>
										<select class="select2 form-control" data-placeholder="Choose an Item..."  name="itemCode1" id="itemCodeInput">
											<option >Select item</option>
											<?php
												$items = $Product->list();
												$n=0;
												for ($n=0; $n < count($items); $n++) {
													$item = $items[$n];
													echo'<option value="'.$item['productId'].'">'.$item['productName'].'</option>';
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
												<input class="form-control" type="number" id="orderQuantityInput"/>
												<span class="input-group-addon" id="itemUnitDisplay">
													<select id="itemUnitSelect" style="border: 0;">
														<option>Unit</option>
													</select>
												</span>									
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group"> 
											<label for="unitPriceInput" class="control-label">Unit Price:</label>
											<input class="form-control" type='number' id="unitPriceInput"/>							
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group"> 
											<label for="itemCode" class="control-label">Total Price:</label>
											<div class="input-group">
												<input class="form-control" name="" disabled id="totalPriceDisplay"/>
												<span class="input-group-addon">RWF</span>
											</div>
										</div>
										<div class="form-group"> 
											<button type="button" class="btn btn-primary" id="addPurchaseItemBtn">ADD</button>
										</div>
									</div>
								</div>
				
								<div id="itemPlace" style="display: none;">
									<table id="datatable-buttons" class="table table-striped table-bordered">
		                                <thead>
		                                    <tr>
												<th>N/O</th>
												<th>Item Name</th>
												<th>Quantity</th>
												<th>Unit Price</th>
												<th>Outstanding</th>
												<!-- <th class="hidden-print"">Actions</th> -->
											</tr>
		                                </thead>


		                                <tbody id="orderItemsDisplay">
		                                	
		                                </tbody>
		                            </table>
								</div>
							</div>
						</div>
	                    <!-- <div class="panel-body">
	                        <div class="row">
	                            <div class="col-md-12 col-sm-12 col-xs-12">
									<div id="infoDiv">
									</div>
	                                <div id="listTable">
	                                	<table>
	                                		<th>
	                                			<tr>
	                                				<td>Item Name</td>
	                                				<td></td>
	                                				<td></td>
	                                			</tr>
	                                		</th>
	                                	</table>
	                                </div>
	                            </div>
	                        </div>
	                    </div> -->
	                </div>
				
					<div class="row"> 
						<div class="col-md-12"> 
							<div class="form-group no-margin"> 
								<label for="operationNotes" class="control-label">Note:</label> 
								<textarea class="form-control autogrow" name="operationNotes" id="operationNotes" 
								placeholder="Write something about this operation" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 104px"></textarea>
							</div> 
							<div class="form-group">
							<label for="attachedFile" class="control-label">Attach additional files:</label> 
							<input class="form-control" type="file"></div>
						</div> 
					</div>
					<hr/>
					<div class="row"> 
						<!-- <div class="col-md-12"> 
							<div class="pull-right">
								<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button> 
								<button type="button" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></button> 
							</div>
						</div> -->
						<div class="col-md-12"> 
							<div class="pull-right">
								<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancel</button>
								<button type="Submit" class="btn btn-success waves-effect">Submit</button>
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
										<select class="select2 form-control" data-placeholder="Choose an Item..." name="itemInvoiceCode" id="" onchange="getInvoiceItemsDet()">
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
								<textarea class="form-control autogrow" name="inOpNote" id="inOpNote" 
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
	<!--  ITEM HISTORY INFO -->
<div class="modal fade bs-example-modal-lg" id="itemHist" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
	<div class="modal-dialog modal-lg">
	   <div class="modal-content p-0 b-0">
		 <div id="itemInfoPop">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading"> 
				Loadding...</div> 
				<div class="panel-body"> 
					
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12" >
									<div class="loader"></div>
								</div>
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
	<script type="text/javascript">
		var purchaseOrderId = 0;

		//item selected //loading the details
		$("#itemCodeInput").on('change', function(){
			var productId = $(this).val();

			//checking the details
			$.post(apiLink, {action:"getProduct", productId:productId}, function(data){

				//check status
				if(data.status){
					data = data.data;
					mainUnit = data.units.main.measurementUnit
					unitsSelect = $("#itemUnitDisplay #itemUnitSelect")
					unitsSelect.html("<option value="+mainUnit+">"+mainUnit+"</option>");

					otherUnits = mainUnit = data.units.others
					for (var i = otherUnits.length - 1; i >= 0; i--) {
						unit = otherUnits[i]
					}
					
					console.log(data)
				}else{
					alert("Error "+data.msg)
				}
				
			})
		});

		//total price calculation triggers
		$("#orderQuantityInput").keyup(function(){
			//trigger function
			calculateOrderTotal();
		})

		$("#unitPriceInput").keyup(function(){
			//trigger function
			calculateOrderTotal();
		})

		function calculateOrderTotal(){
			//check the variables
			quantity = $("#orderQuantityInput").val()
			unityPrice = $("#unitPriceInput").val()
			$("#totalPriceDisplay").val(quantity*unityPrice)
		}

		// function checkOrder(){
		// 	//check if there is a currentPurchaseOrder
		// 	if(purchaseOrderId == 0){
		// 		//here we need to create the purchasing order
		// 		var supplier = $("#orgInput").val();
		// 		if(!isNaN(parseInt(supplier))){
		// 			$.post(apiLink, {action:'addPurchasingOrder', status:'draft', supplier:supplier, doneBy:currentUserId}, function(data){
		// 					data = data.data
		// 					mainUnit = data.unit.main.measurementUnit
		// 					alert(mainUnit)
		// 					console.log(data)
		// 				});
		// 		}else{
		// 			alert("Please choose supplier");
		// 			return false;
		// 		}
		// 	}
		// }
		$("#addPurchaseItem").on('click', function(){
			
			
		})
	</script>
	<script src="assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="assets/plugins/jquery-multi-select/jquery.multi-select.js"></script>
	<script>
		/**
		 * Number.prototype.format(n, x)
		 * 
		 * @param integer n: length of decimal
		 * @param integer x: length of sections
		 */
		Number.prototype.format = function(n, x) {
		    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
		    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
		};

		//multiselect start
		$('#my_multi_select1').multiSelect();
		$('#my_multi_select2').multiSelect({
			selectableOptgroup: true
		});

		$('#my_multi_select3').multiSelect({
			selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
			selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
			afterInit: function (ms) {
				var that = this,
					$selectableSearch = that.$selectableUl.prev(),
					$selectionSearch = that.$selectionUl.prev(),
					selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
					selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

				that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
					.on('keydown', function (e) {
						if (e.which === 40) {
							that.$selectableUl.focus();
							return false;
						}
					});

				that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
					.on('keydown', function (e) {
						if (e.which == 40) {
							that.$selectionUl.focus();
							return false;
						}
					});
			},
			afterSelect: function () {
				this.qs1.cache();
				this.qs2.cache();
			},
			afterDeselect: function () {
				this.qs1.cache();
				this.qs2.cache();
			}
		});
		// Select2
		jQuery(".select2").select2({
			width: '100%'
		});
	</script>
	<script type="text/javascript" src="js/product.js"></script>
</body>
</html>