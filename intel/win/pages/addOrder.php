 <!-- Validation wizard -->
<div class="row" id="validation">
	<div class="col-12">
		<div class="white-box">
			<div class="card-body wizard-content">
				<h4 class="card-title">Preparing purchase order <b><span class="text-warning">##<?php echo $POrder->generateOrderNumber('P', $POrder->nextOrderNumber()); ?></span></b></h4>
				<h6 class="card-subtitle">Most comphrensive way of preparing purchase order</h6>
				<form id="purchaseOrderForm" class="validation-wizard wizard-circle m-t-40">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="wfirstName2"> Currency:
									<span class="danger">*</span>
								</label>
								<select class="form-control select2" id="orderCurrencyInput" style="width: 100%">
		                            <option>Select</option>
					                <?php
					                	$currencies = $POrder->listCurrency();
					                	foreach ($currencies as $key => $currency) {
					                		?>
					                			<option value="<?php echo $currency['symbol'] ?>"><?php echo $currency['name']; ?></option>
					                		<?php
					                	}
					                ?>
		                        </select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="wfirstName2"> Warehouse:
									<span class="danger">*</span>
								</label>
								<select class="form-control select2" id="orderWareHouseInput" style="width: 100%">
		                            <option>Select</option>
					                <?php
					                	$warehouses = $Warehouse->list();
					                	foreach ($warehouses as $key => $warehouse) {
					                		?>
					                			<option value="<?php echo $warehouse['id'] ?>"><?php echo $warehouse['name']; ?></option>
					                		<?php
					                	}
					                ?>
		                        </select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="itemCodeInput"> Product name :
									<span class="danger">*</span>
								</label>
								<select class="form-control select2" id="itemCodeInput" style="width: 100%">
		                            <option>Select</option>
					                <?php
					                	$products = $Product->list();
					                	foreach ($products as $key => $product) {
					                		?>
					                			<option value="<?php echo $product['productId'] ?>"><?php echo $product['productName']; ?></option>
					                		<?php
					                	}
					                ?>
		                        </select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="wlastName2"> Quantity :
									<span class="danger">*</span>
								</label>
								<div class="input-group">
                                    <input type="number" class="form-control" id="productQuantityInput" placeholder="Quantity">
                                    <div class="input-group-addon">
                                    	<select class="form-control select2" id="itemUnitSelect" style="width: 100%">
				                            <option>Unit</option>
				                        </select>
				                    </div>
                                </div>
                            </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="unitPriceInput">Unit price:</label>
								<input type="number" class="form-control" id="unitPriceInput"> </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="wphoneNumber2">Total amount:</label>
								<input type="text" class="form-control" id="totalPriceDisplay" disabled="disabled"> </div>
						</div>
						<div class="col-md-12">
							<button type="button" class="btn btn-success btn-outline btn-circle m-r-5 m-b-5 editModalOpenBtn pull-right" id="addPurchaseItemBtn"><i class="ti-plus"></i></button>
						</div>

						<div class="col-md-12">
							<div class="table-responsive" id="itemPlace" style="display: none;">
								<table id="example23" class="DataTable display nowrap table table-hover table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
			                                <th>#</th>
			                                <th>Product Name</th>
			                                <th>Quantity</th>
			                                <th>Unit price</th>
			                                <th>Amount</th>
			                                <th>Manage</th>
			                            </tr>
									</thead>
									<!-- <tfoot>
										<tr>
											<th>#</th>
			                                <th>Product Name</th>
			                                <th>Quantity</th>
			                                <th>Unit price</th>
			                                <th>Amount</th>
			                                <th>Manage</th>
			                            </tr>
									</tfoot> -->
									<tbody id="orderItemsDisplay">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
		                        <label for="recipient-name" class="control-label">Supplier / vendor:</label>
		                        <select class="form-control select2" id="selectSupplier" style="width: 100%">
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
		                </div>
		                <div class="col-md-12">
		                    <div class="form-group">
		                        <label for="recipient-name" class="control-label">Budget holder:</label>
		                        <select class="form-control select2" id="selectBudgetHolder" style="width: 100%">
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
		                </div>

		                <div class="col-md-12">
							<div class="form-group">
		                        <label for="recipient-name" class="control-label">Shipping mode:</label>
		                        <select class="form-control select2" id="shippingOptionInput" style="width: 100%">
		                            <option>Select</option>
		                            <option>Air</option>
		                            <option>Ocean</option>
		                            <option>Land</option>
		                        </select>
		                    </div>
		                </div>
		                <div class="col-md-12">
		                    <div class="form-group">
		                        <label for="recipient-name" class="control-label">Preferred shipment date:</label>
		                        <input type="date" class="form-control" id="shipmentDate">
		                    </div>
		                </div>
					</div>
					<p class="successCenter text-success"></p>
					<button class="btn btn-primary" type="submit">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>


<?php /*
<!-- vertical wizard -->
<div class="row">
	<div class="col-12">
		<div class="white-box">
			<div class="card-body wizard-content ">
				<h4 class="card-title">Step wizard</h4>
				<h6 class="card-subtitle">You can find the
					<a href="http://www.jquery-steps.com/" target="_blank">offical website</a>
				</h6>
				<form action="#" class="tab-wizard vertical wizard-circle">
					<!-- Step 1 -->
					<h6>Personal Info</h6>
					<section>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="firstName1">First Name :</label>
									<input type="text" class="form-control" id="firstName1"> </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="lastName1">Last Name :</label>
									<input type="text" class="form-control" id="lastName1"> </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="emailAddress1">Email Address :</label>
									<input type="email" class="form-control" id="emailAddress1"> </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="phoneNumber1">Phone Number :</label>
									<input type="tel" class="form-control" id="phoneNumber1"> </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="location1">Select City :</label>
									<select class="custom-select form-control" id="location1" name="location">
										<option value="">Select City</option>
										<option value="Amsterdam">India</option>
										<option value="Berlin">USA</option>
										<option value="Frankfurt">Dubai</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="date1">Date of Birth :</label>
									<input type="date" class="form-control" id="date1"> </div>
							</div>
						</div>
					</section>
					<!-- Step 2 -->
					<h6>Job Status</h6>
					<section>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="jobTitle1">Job Title :</label>
									<input type="text" class="form-control" id="jobTitle1"> </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="videoUrl1">Company Name :</label>
									<input type="text" class="form-control" id="videoUrl1">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="shortDescription1">Job Description :</label>
									<textarea name="shortDescription" id="shortDescription1" rows="6" class="form-control"></textarea>
								</div>
							</div>
						</div>
					</section>
					<!-- Step 3 -->
					<h6>Interview</h6>
					<section>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="int1">Interview For :</label>
									<input type="text" class="form-control" id="int1"> </div>
								<div class="form-group">
									<label for="intType1">Interview Type :</label>
									<select class="custom-select form-control" id="intType1" data-placeholder="Type to search cities" name="intType1">
										<option value="Banquet">Normal</option>
										<option value="Fund Raiser">Difficult</option>
										<option value="Dinner Party">Hard</option>
									</select>
								</div>
								<div class="form-group">
									<label for="Location1">Location :</label>
									<select class="custom-select form-control" id="Location1" name="location">
										<option value="">Select City</option>
										<option value="India">India</option>
										<option value="USA">USA</option>
										<option value="Dubai">Dubai</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="jobTitle2">Interview Date :</label>
									<input type="date" class="form-control" id="jobTitle2">
								</div>
								<div class="form-group">
									<label>Requirements :</label>
									<div class="m-b-10">
										<div class="radio radio-info">
											<input type="radio" name="radio6" id="rd-5" value="option4">
											<label for="rd-5"> Employee </label>
										</div>
										<div class="radio radio-info">
											<input type="radio" name="radio6" id="rd-6" value="option4">
											<label for="rd-6"> Contract </label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
					<!-- Step 4 -->
					<h6>Remark</h6>
					<section>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="behName1">Behaviour :</label>
									<input type="text" class="form-control" id="behName1">
								</div>
								<div class="form-group">
									<label for="participants1">Confidance</label>
									<input type="text" class="form-control" id="participants1">
								</div>
								<div class="form-group">
									<label for="participants1">Result</label>
									<select class="custom-select form-control" id="participants1" name="location">
										<option value="">Select Result</option>
										<option value="Selected">Selected</option>
										<option value="Rejected">Rejected</option>
										<option value="Call Second-time">Call Second-time</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="decisions1">Comments</label>
									<textarea name="decisions" id="decisions1" rows="4" class="form-control"></textarea>
								</div>
								<div class="form-group">
									<label>Rate Interviwer :</label>
									<div class="c-inputs-stacked">
										<div class="checkbox checkbox-info">
											<input id="chk-1" type="checkbox">
											<label for="chk-1"> 1 star </label>
										</div>
										<div class="checkbox checkbox-info">
											<input id="chk-2" type="checkbox">
											<label for="chk-2"> 2 star </label>
										</div>
										<div class="checkbox checkbox-info">
											<input id="chk-3" type="checkbox">
											<label for="chk-3"> 3 star </label>
										</div>
										<div class="checkbox checkbox-info">
											<input id="chk-4" type="checkbox">
											<label for="chk-4"> 4 star </label>
										</div>
										<div class="checkbox checkbox-info">
											<input id="chk-5" type="checkbox">
											<label for="chk-5"> 5 star </label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.row -->

*/
?>