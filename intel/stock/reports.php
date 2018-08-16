
            <?php include("topheader.php");?>
			
			<!-- Top Bar End -->


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
                                <h4 class="pull-left page-title">GENERAL REPORT</h4>
                                
                            </div>
                        </div>
						
						
						
                        <div class="row">
                            <!-- RESULT TABLE -->
							<div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="userName"> 
										<div class="row">
											<div class="col-md-3">
												<div class="input-group">
													<input type="text" id="searchUser" onkeyup="search()" name="example-input1-group2" class="form-control input-sm" placeholder="Search">
													<span class="input-group-btn">
														<button type="button" class="btn-sm btn waves-effect waves-light btn-primary"><i class="fa fa-search"></i></button>
													</span>
												</div>
											</div>
											<div class="col-md-2">
											</div>
											<div class="col-md-1">
												<h3 class="panel-title"> <center> From</center></h3>
											</div>
											
											<div class="col-md-2">
												<div class="input-group">
													<input type="date" onchange="between()" id="fromDate" class="form-control input-sm" max="<?PHP
														$sqldate = $db->query("SELECT date(doneOn), `transactionID` FROM `transactions` ORDER BY `transactionID` ASC LIMIT 1")or die (mysql_error());
														WHILE($row = mysqli_fetch_array($sqldate))
														{
															echo $row['date(doneOn)'];
														}
														?>" placeholder="mm/dd/yyyy">
													<span class="input-group-addon">
														<i class="glyphicon glyphicon-calendar"></i>
													</span>
												</div>
											</div>									
											<div class="col-md-1">
												<h3 class="panel-title"> <center><i class="ion-arrow-right-c"></i></center></h3>
											</div>
											<div class="col-md-2">
												<div class="input-group">
													<input type="date" onchange="between()" id="toDate" class="form-control input-sm" placeholder="mm/dd/yyyy">
													<span class="input-group-addon">
														<i class="glyphicon glyphicon-calendar"></i>
													</span>
												</div>
											</div>									
										</div>
									</div> 
									<div class="panel-body">
                                        <div id="userReport" class="inbox-widget nicescroll mx-box">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
													<th>N/O</th>
													<th>Date</th>
													<th>By</th>
													<th>Operation</th>
													<th>Item Name</th>
													<th>Quantity</th>
													<th>Unity Price</th>
													<th>Total</th>
												</tr>
                                            </thead>


                                            <tbody>
                                                 <?php 
										$sql = $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
										ORDER BY T.transactionID DESC");
										$n=0;
										WHILE($row= mysqli_fetch_array($sql))
										{
											$n++;
										echo'<tr class="gradeX">
                                            <td>'.$n.'</td>
                                            <td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
                                            <td>'.$row['doneBy'].'</td>
                                            <td>'.$row['operation'].'</td>
                                            <td>'.$row['itemName'].'</td>
                                            <td>'.number_format($row['Quantity']).' '.$row['unit'].'s</td>
                                            <td>'.number_format($row['Price']).' Rwf</td>
                                            <td>'.number_format($row['total']).' Rwf</td>
                                            
                                        </tr>';
										}
										
										?>

                                             </tbody>
                                        </table>
										
										</div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                        </div> <!-- End Row -->
						
						<div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-border panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">IMIGENDEKERE Y'UBUCURUZI BWAWE</h3> 
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


            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

        </div>
		
		
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
								<div class="col-md-12 col-sm-12 col-xs-12">
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
		
		<script src="assets/plugins/flot-chart/jquery.flot.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.time.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.resize.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.pie.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.selection.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.stack.js"></script>
        <script src="assets/plugins/flot-chart/jquery.flot.crosshair.js"></script>
        <script src="assets/pages/jquery.flot.init.js"></script>
        
		
		<script src="js/apicall.js"></script>

		
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
		
<script> <!--5 Load product to Edit-->
function between(){
	var fromDate = $("#fromDate").val();	
	var toDate = $("#toDate").val();
		
	//alert(fromDate);
	//alert(toDate);
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				fromDate : fromDate,
				toDate : toDate,
			},
			success : function(html, textStatus){
				$("#userReport").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// USER SEARCH
function search(){
	var searchUser = $("#searchUser").val();	
	//alert(searchUser);
	//var uId=userNam
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				searchUser : searchUser,
			},
			success : function(html, textStatus){
				$("#userReport").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}

function userRep(userNam){
	//alert(userNam);
	var uId=userNam
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				uId : uId,
			},
			success : function(html, textStatus){
				$("#userReport").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
function usName(userNam){
	//alert(userNam);
	var userNam=userNam
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				uName : userNam,
			},
			success : function(html, textStatus){
				$("#userName").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>

<script>
function itemInfo(itemInfoId,userId){ 

$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				itemInfoId1 : itemInfoId,
				customerId : userId,
				
				
				
			},
			success : function(html, textStatus){
				$("#itemInfoPop").html(html);
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