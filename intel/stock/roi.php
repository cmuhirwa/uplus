
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
                                <h4 class="pull-left page-title">RETURN ON INVESTMENT</h4>
                                
                            </div>
                        </div>
						

						<div class="row">
                             <!-- USER LIST -->
                            <div class="col-lg-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
										<div class="row">
										    <div class="col-md-4">
												<h4 class="panel-title">ITEMS</h4>
											</div>
											<div class="col-md-8">
												<div class="input-group">
													<input type="text" id="searchUser" onkeyup="search()" name="example-input1-group2" class="form-control input-sm" placeholder="Search">
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
											$totaroi = "";
											$totali = "";
											$sqltotalperUser = $db->query("SELECT itemCode, itemName, sum(`GAIN_PER_OPERATION`) totalgain, sum(`investment`) investment 
															FROM `returnoninvestment` 
															GROUP BY `itemCode` 
															ORDER BY totalgain desc");
												$n = 0;
												while($row = mysqli_fetch_array($sqltotalperUser))
												{
													$n++;
													$roi = $row['totalgain'];
													$investment = $row['investment'];
												echo'<a href="javascript:void()" onclick="fmcg(itemCode='.$row['itemCode'].'); fmcg1(itemid1='.$row['itemCode'].')">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img">#'.$n.'</div>
                                                    <p class="inbox-item-author">'.$row['itemName'].'</p>
                                                    <p class="inbox-item-date">'.number_format($row['totalgain']).' Rwf</p>
                                                </div>
                                            </a>';
												$totaroi =(int) $roi + (int)$totaroi;
												$totali = (int)$investment + (int)$totali;
											}
											?>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <!-- RESULT TABLE -->
							<div class="col-lg-8">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="header1"> 
										<div class="row">
											<div class="col-md-3">
												<h3 class="panel-title">{{Name}}</h3>
											</div>
											
											<div class="col-md-4">
												<div class="input-group">
													<input type="date" onchange="between()" id="fromDate" class="form-control input-sm" min="<?PHP
														$sqldate = $db->query("SELECT date(doneOn), `transactionID` FROM `transactions` ORDER BY `transactionID` ASC LIMIT 1")or die (mysql_error());
														WHILE($row = mysqli_fetch_array($sqldate))
														{
															echo $row['date(doneOn)'];
														}
														?>" disabled placeholder="mm/dd/yyyy">
													<span class="input-group-addon">
														<i class="glyphicon glyphicon-calendar"></i>
													</span>
												</div>
											</div>									
											<div class="col-md-1">
												<h3 class="panel-title"> <center><i class="ion-arrow-right-c"></i></center></h3>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<input type="date" onchange="between()" id="toDate" class="form-control input-sm" disabled placeholder="mm/dd/yyyy">
													<span class="input-group-addon">
														<i class="glyphicon glyphicon-calendar"></i>
													</span>
												</div>
											</div>									
										</div>
									</div> 
									<div class="panel-body">
                                        <div id="roiReport" class="inbox-widget nicescroll mx-box"><hr/>
                                       <h5 class="text-success"><center>The total Return on investment is: 
									   <b><?php echo number_format((int)$totaroi);?> Rwf</b> from: <b><?php echo number_format((int)$totali);?> Rwf</b>
									   <br/> which makes it <b> <?php if($totali>0){echo number_format(($totaroi * 100) / $totali);}else{ echo '0'; }?>% </b><hr/>
									   click on the item to see how it has been generating money!
									   
									   </center></h5>
										</div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

					   </div>
                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <footer class="footer text-right">
                    2016 © KGL-INVETO.
                </footer>

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
// ITEM SEARCH (DONE)
function search(){
	var searchroi = $("#searchUser").val();	
	//alert(searchUser);
	//var uId=userNam
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				searchroi : searchroi,
			},
			success : function(html, textStatus){
				$("#userResurts").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// NAME ON THE HEADER (DONE)
function fmcg(itemCode){
	//alert(userNam);
	var itemCode=itemCode
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				roiitemCode : itemCode,
			},
			success : function(html, textStatus){
				$("#header1").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// FILTER BY TIME INTERVAL ()
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
				
				roifromDate : fromDate,
				roitoDate : toDate,
			},
			success : function(html, textStatus){
				$("#roiReport").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// GET ITEM FMCG FULL REPORT (DONE)
function fmcg1(itemid1){
	var itemid1=itemid1
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				roiitemid1 : itemid1,
			},
			success : function(html, textStatus){
				$("#roiReport").html(html);
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