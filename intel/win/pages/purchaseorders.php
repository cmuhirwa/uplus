<div class="container-fluid">
	<?php include_once 'modules/topBreadcump.php'; ?>
	<!-- .row -->
	<div class="row">
		<!-- <div class="col-md-12">
			<div class="white-box">
				<h3 class="box-title">Drugs & Materials</h3> </div>
		</div> -->
		<?php
            $purchaseOrders = $POrder->list();
        ?>
		<div class="col-sm-12">
			<?php
				$page = $_GET['action']??"";
				if($page=='add'){
					include_once 'pages/addOrder.php';
					$js_files = array('../plugins/bower_components/moment/moment.js', '../plugins/bower_components/jquery-wizard-master/jquery.steps.min.js', '../plugins/bower_components/jquery-wizard-master/jquery.validate.min.js', '../plugins/bower_components/sweetalert/sweetalert.min.js', 'js/addOrder.js');
				}else{

			?>
				<div class="white-box">
					<div class="row">
						<div class="col-md-8">
							<h3 class="box-title m-b-0">Purchase orders</h3>
						</div>
						<div class="col-md-1 pull-right">
							<a class="btn btn-block btn-outline btn-info btn-rounded" href="?action=add">Add <i class="mdi mdi-plus"></i></a>
							
						</div>
					</div>
						
					<p class="text-muted m-b-30">Requests to suppliers</p>
					<div class="table-responsive">
						<table id="example23" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
							<thead>
								<tr>
	                                <th>#</th>
	                                <th>Order number</th>
	                                <th>Number of items</th>
	                                <th>Vendor</th>
	                                <th>Warehouse</th>
	                                <th>Amount</th>
	                                <th>Expected shipping date</th>
	                                <th>Issued date</th>
	                                <th>Status</th>
	                                <th>Manage</th>
	                            </tr>
							</thead>
							<tfoot>
								<tr>
									<th>#</th>
	                                <th>Order number</th>
	                                <th>Number of items</th>
	                                <th>Vendor</th>
	                                <th>Warehouse</th>
	                                <th>Amount</th>
	                                <th>Expected shipping date</th>
	                                <th>Issued date</th>
	                                <th>Status</th>
	                                <th>Manage</th>
	                            </tr>
							</tfoot>
							<tbody>
								<?php
	                                $n=0;
	                                foreach ($purchaseOrders as $key => $order) {
	                                    $n++;
	                                    $order = $POrder->details($order['id']);
	                                    // var_dump($order);
	                                    $order = (object)$order;
	                                    $orderId = $order->id;
	                                    $orderCode = $POrder->generateOrderNumber('P', $orderId);

	                                    $supplierData = $Supplier->details($order->supplier);
	                                    
	                                    $warehouseData = $Warehouse->details($order->warehouse);
	                                    ?>
	                                        <tr>
	                                            <td><?php echo $n; ?></td>
	                                            <td><?php echo $orderCode; ?></td>
	                                            <td><?php echo count($order->items) ?></td>
	                                            <td><?php echo ucfirst($supplierData['name']??"-"); ?></td>
	                                            <td><?php echo $warehouseData['name']??""; ?></td>
	                                            <td><?php echo number_format($order->totalAmount??"0"); ?></td>
	                                            <td><?php echo date($standard_time, strtotime($order->shipmentDate)); ?></td>
	                                            <td><?php echo date($standard_time, strtotime($order->createdDate)); ?></td>
	                                            <td><?php echo ucwords($order->status); ?></td>
	                                            <td>                                   
	                                                <button type="button" class="btn btn-success btn-outlinse btn-circle btn-lg m-r-5 editModalOpenBtn" data-toggle="modal" data-target="#editModal" data-whatever="@mdo"><i class="ti-pencil-alt"></i></button>
	                                                <button type="button" class="btn btn-danger btn-outlinse btn-circle btn-lg m-r-5 deleteModalOpenBtn" data-toggle="modal" data-target="#deleteModal" data-whatever="@mdo"><i class="ti-trash"></i></button>
	                                            </td>
	                                        </tr>
	                                    <?php
	                                }
	                            ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<!-- Modals -->
<div class="modal fade" id="editModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form id="updateProductForm">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title">Edit product #<span id="editProductIdInModal"></span></h4>
	           	</div>
	            <div class="modal-body">
	                
	                    <div class="form-group">
	                        <label for="recipient-name" class="control-label">Name:</label>
	                        <input type="text" class="form-control" id="productNameEdit">
	                    </div>
	                    <div class="form-group">
	                        <label for="message-text" class="control-label">Measurement Unit:</label>
	                        <select class="form-control select2" id="mainMeasurementUnitInput">
	                            <option>Select</option>
				                <?php
				                	$units = $Measurements->listUnits();
				                	foreach ($units as $key => $unit) {
				                		?>
				                			<option value="<?php echo $unit['symbol'] ?>"><?php echo $unit['name']."(".$unit['symbol'].")" ?></option>
				                		<?php
				                	}
				                ?>
	                            ?>
	                        </select>
	                    </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-success waves-effect waves-light">Update</button>
	            </div>
	        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form id="deleteProductForm">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title">Delete #<span class="editProductIdInModal"></span></h4>
	           	</div>
	            <div class="modal-body">
                	<p class="text-warning">You are going to delete <b><span class="productNameReplace"></span></b></p>

                	<small>
                		The product wont be able to do further entry transactions but will be archived in several other transactions
                	</small>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Password:</label>
                        <input type="password" class="form-control" required="required">
                    </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-success waves-effect" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">DELETE</button>
	            </div>
	        </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addProductModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form id="addProductForm">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title">Add purchase order <b class="text-warning">#<?php echo $POrder->generateOrderNumber('P', $POrder->nextOrderNumber()); ?></b></h4>
	           	</div>
	            <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Supplier / vendor:</label>
                        <select class="form-control select2" id="selectSuppslier" style="width: 100%">
                            <option>Select</option>
			                <?php
			                	$suppliers = $Supplier->list();
			                	foreach ($suppliers as $key => $supplier) {
			                		?>
			                			<option value="<?php echo $supplier['id'] ?>"><?php echo $supplier['name']; ?></option>
			                		<?php
			                	}
			                ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Budget holder:</label>
                        <select class="form-control select2" id="selectsBudgetHolder" style="width: 100%">
                            <option>Select</option>
			                <?php
			                	$budgetHolders = $POrder->listBudgetHolders();
			                	foreach ($budgetHolders as $key => $holder) {
			                		?>
			                			<option value="<?php echo $holder['id'] ?>"><?php echo $holder['name']; ?></option>
			                		<?php
			                	}
			                ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Warehouse:</label>
                        <select class="form-control select2" id="addMainMeasurementUnitInput" style="width: 100%">
                            <option>Select</option>
			                <?php
			                	$warehouseList = $Warehouse->list();
			                	foreach ($warehouseList as $key => $warehouse) {
			                		?>
			                			<option value="<?php echo $warehouse['id'] ?>"><?php echo $warehouse['name']; ?></option>
			                		<?php
			                	}
			                ?>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="addAlternativeMeasurementUnits" class="control-label">Alternative measurements:</label>
                        <select class="form-control select2 select2-multiple" id="addAlternativeMeasurementUnits" multiple="multiple" style="width: 100%">
                            <option>Select</option>
                            <?php
			                	foreach ($units as $key => $unit) {
			                		?>
			                			<option value="<?php echo $unit['symbol'] ?>"><?php echo $unit['name']."(".$unit['symbol'].")" ?></option>
			                		<?php
			                	}
			                ?>
                        </select>
                    </div> -->

                    <!-- <div class="form-group">
                        <label for="addProductGroups" class="control-label">Product group:</label>
                        <select class="form-control select2 select2-multiple" id="addProductGroups" multiple="multiple" style="width: 100%">
                            <option>Select</option>
                            <?php
			                	$listProductGroups = $Product->listProductGroups();
			                	foreach ($listProductGroups as $key => $group) {
			                		?>
			                			<option value="<?php echo $group['id'] ?>"><?php echo $group['groupName']; ?></option>
			                		<?php
			                	}
			                ?>
                        </select>
                    </div> -->
	            </div>

	            <div class="modal-footer">
	                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-success waves-effect waves-light">ADD</button>
	            </div>
	        </form>
        </div>
    </div>
</div>

<?php
	
?>