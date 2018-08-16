<div class="container-fluid">
	<?php include_once 'modules/topBreadcump.php'; ?>
	<!-- .row -->
	<div class="row">
		<?php
            $query = $conn->query("SELECT * FROM purchasing_orders WHERE status = 'confirmed' ") or trigger_error($conn->error);
            $products = $query->fetch_all(MYSQLI_ASSOC);
        ?>
		<div class="col-sm-12">
			<div class="white-box">
				<h3 class="box-title m-b-0">Receive goods from suppliers</h3>
				<p class="text-muted m-b-30">Enter the purchase order or delivery note number to receive</p>

				<form id="checkOrderForm" method="POST">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group" method = 'POST'>
		                        <label class="col-md-12">Delivery NO:</label>
		                        <div class="col-md-12">
		                            <input type="text" class="form-control" name ='orderNumber' value=""> </div>
		                    </div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
		                    	<button class="btn btn-primary">Receive</button>
		                    </div>
						</div>
					</div>
				</form>



				<?php
					if(is_array($products)){
						?>
							<div class="table-responsive">
								<table id="example23" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
			                                <th>#</th>
			                                <th>Order Number</th>
			                                <th>Comfirmed date</th>
			                                <th>Manage</th>
			                            </tr>
									</thead>
									<tfoot>
										<tr>
			                                <th>#</th>
			                                <th>Order Number</th>
			                                <th>Comfirmed date</th>
			                                <th>Manage</th>
			                            </tr>
									</tfoot>
									<tbody>
										<?php
			                                $n=0;
			                                foreach ($products as $key => $order) {
			                                    $n++;
			                                    $order = (object)$order;
			                                    $orderCode = $POrder->generateOrderNumber("P", $order->id);
			                                    ?>
			                                        <tr>
			                                            <td><?php echo $n; ?></td>
			                                            <td><?php echo $orderCode; ?></td>
			                                            <td><?php echo date($standard_date); ?></td>
			                                            <td>
			                                                <button type="button" class="btn btn-danger btn-outlinse btn-circle btn-lg m-r-5 deleteModalOpenBtn" data-toggle="modal" data-target="#deleteModal" data-whatever="@mdo"><i class="ti-trash"></i></button>
			                                                <button type="button" class="btn btn-info btn-outline btn-circle btn-lg m-r-20"><i class="ti-upload"></i></button>
			                                            </td>
			                                        </tr>
			                                    <?php
			                                }
			                            ?>
									</tbody>
								</table>
							</div>
						<?php
					}
				?>

				<?php
					if(!empty($_POST)){
						$orderNumber = $_POST['orderNumber'];

						$orderData = $POrder->details($orderNumber);

						if(empty($orderData['orderNumber'])){
							echo "Order can not be found";
						}else{
								$status = $orderData['status'];
								if($status != 'confirmed'){
									echo "<p class='text-danger'>Order is not yet confirmed by the supplier</p>";
								}else{
									$warehouseData = $Warehouse->details($orderData['warehouse']);

									?>
										<h3>Goods from <b class="text-warning">#<?php echo $POrder->generateOrderNumber("P", $orderData['id']); ?></b></h3>

										<?php
											include $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";

											$Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
											$code = $Bar->getBarcode($POrder->generateOrderNumber("P", $orderData['id']), $Bar::TYPE_CODE_128);
											echo $code;
										?>
										<p>
											Warehouse: <?php echo $warehouseData['name'] ?>
										</p>
										<p>
											Total amount: <?php echo number_format($orderData['totalAmount'])." ".$orderData['currency']; ?>
										</p>

										<div class="table-responsive">
											<table id="example23" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
												<thead>
													<tr>
						                                <th>#</th>
														<th>Item</th>
														<th>Quantity</th>
														<th>Unit Price</th>
														<th>Total Amount</th>
						                            </tr>
												</thead>
												<tbody>
													<?php
						                                $n=0;
						                                foreach ($orderData['items'] as $key => $order) {
						                                    $n++;
						                                    $order = (object)$order;
						                                    // var_dump($order);
						                                    $orderId = $order->id;
						                                    $orderCode = $POrder->generateOrderNumber('P', $orderId);

						                                    $productData = $Product->details($order->productId);
						                                    ?>
						                                        <tr>
						                                            <td><?php echo $n; ?></td>
						                                            <td><?php echo $productData['productName']; ?></td>
						                                            <td><?php echo number_format($order->productQuantity); ?></td>
						                                            <td><?php echo number_format($order->productUnitPrice); ?></td>
						                                            <td><?php echo number_format($order->amount); ?></td>
						                                            <!-- <td>                                   
						                                                <button type="button" class="btn btn-success btn-outlinse btn-circle btn-lg m-r-5 editModalOpenBtn" data-toggle="modal" data-target="#editModal" data-whatever="@mdo"><i class="ti-pencil-alt"></i></button>
						                                                <button type="button" class="btn btn-danger btn-outlinse btn-circle btn-lg m-r-5 deleteModalOpenBtn" data-toggle="modal" data-target="#deleteModal" data-whatever="@mdo"><i class="ti-trash"></i></button>
						                                            </td> -->
						                                        </tr>
						                                    <?php
						                                }
						                            ?>
												</tbody>
											</table>
										</div>
									<?php

								}
							?>

							<?php
						}
					}
				?>
			</div>
		</div>
		<div class="col-md-12">
			<div class="btn-fab-wrapper">
				<button type="button" class="btn btn-primary btn-circle btn-xl" data-toggle="modal" data-target="#addProductModal"><i class="fa fa-plus"></i> </button>
			</div>
		</div>
	</div>
</div>
<?php
	$js_files = array('js/products.js');
?>