
<?php include('topheader.php');?>
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
								<h4 class="pull-left page-title">Suppliers/Vendors</h4>
								<ol class="breadcrumb pull-right">
									<li><a href="#">Home</a></li>
									<li class="active">Suppliers</li>
								</ol>
							</div>
						</div>


						
						<div class="row">
							<!-- USER LIST -->
							<div class="col-lg-4">
								<div id="userdiv">
									<div class="panel panel-default">
										<div class="panel-heading">
										<div class="row">
											<div class="col-md-12">
												<h4 class="panel-title">New Supplier</h4>
											</div>
										</div>
									</div>
										<div class="panel-body">
											<div class="inbox-widget nicescroll mx-box">
												Organisation Name<br/>
												<input type="text" name="name" id="name" class="form-control input-sm"><br/>
												Phone<br/>
												<input type="number" name="Phone" id="Phone" class="form-control input-sm"><br/>
												Email<br/>
												<input type="email" required name="Email" id="Email" class="form-control input-sm"><br/>
												WorkPlace<br/>
												<input type="text" name="WorkPlace" id="WorkPlace" class="form-control input-sm"><br/>
												Username<br/>
												<input type="text" name="username" id="username" class="form-control input-sm"><br/>
												Password<br/>
												<input type="password" name="password" id="password" class="form-control input-sm"><br/>
												Repeat Password<br/>
												<input type="password" name="passwordCheck" id="passwordCheck" class="form-control input-sm"><br/>
												<button onclick="insertUser()" class="btn btn-success waves-effect waves-light">Add</button>
											
											</div>
										</div>
									</div>
								</div>
							</div> <!-- end col -->
								
								
								<!-- RESULT TABLE -->
							<div class="col-lg-8">
								<div class="panel panel-default">
									<div class="panel-heading" id="usersList"> 
										<div class="row">
											<div class="col-md-12"><h3 class="panel-title">List of suppliers</h3>
											</div>
										</div>
									</div> 
									<div class="panel-body">
										<div id="listTable" class="inbox-widget nicescroll mx-box">
										<table width="100%" class="table table-striped table-bordered">
											<thead >
												<th>#</th>
												<th>Name</th>
												<th>Phone</th>
												<th>Email</th>
												<th>Location</th>
												<th>Actions</th>
											</thead>
											<tbody>
												<?php
													$n=0;
													$suppliers = $Supplier->list();
													$count = count($suppliers??array());
													if($count > 0)
													{
														for($n=0; $n<$count; $n++)
														{
															$supplier = $suppliers[$n];
															echo'
															<tr href="javascript:void()" onclick ="editUser(userId= '.$supplier['id'].')">
																<td>'.($n+1).'</td>
																<td>'.$supplier['name'].'</td>
																<td>'.$supplier['phone'].'</td>
																<td>'.$supplier['email'].'</td>
																<td>'.$supplier['location'].'</td>
																<td><a href="javascript:void()" onclick="removeUser(userId='.$supplier['id'].')">Remove</a></td>
															</tr>';
														}
																							
														}else{
															echo '<tr>There are no suppliers for now</tr>';
														}
												?>
											</tbody>
										</table>
										</div>
									</div>
								</div>
							</div>

						</div> <!-- End Row -->

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

<script>
// <!--5 Add a user-->
function insertUser()
{
	//insert a supplier
	var name = document.getElementById('name').value;
	if (name == null || name == "") {
		alert("Organisation name must be filled out");
		return false;
	}

	var Phone = document.getElementById('Phone').value;
	if (Phone == null || Phone == "") {
		alert("Phone must be filled out");
		return false;
	}

	var Email = document.getElementById('Email').value;
	if (Email == null || Email == "") {
		alert("Email must be filled out");
		return false;
	}

	var WorkPlace = document.getElementById('WorkPlace').value;
	var username = document.getElementById('username').value;
	if (username == null || username == "") {
		alert("username must be filled out");
		return false;
	}

	var password = document.getElementById('password').value;
	var passwordCheck = document.getElementById('passwordCheck').value;
	//alert(passwordCheck);
	if (password == null || password == "") {
		alert("password must be filled out");
		return false;
	}
	if (!password == passwordCheck) {
		alert("password not much");
		return false;
	}
	apiLink = "../api/index.php";
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "POST",
			url : apiLink,
			dataType : "json",
			cache : "false",
			data : {
				action: "addSupplier",			
				name : name,
				phone : Phone,
				email : Email,
				workplace : WorkPlace,
				username : username,
				password : password,
				doneBy: currentUserId		
			},
			success : function(html, textStatus){
				ret = html
				if(ret.status){
					alert("Congratulations!");
					window.setTimeout(function(){
						window.location.reload()
					}, 1000)
				}else{
					alert("Error: "+ret.msg)
				};
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// <!--5 load user to Edit-->
function editUser(userId)
{
	var editUser = userId;
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				editUser : editUser,
				
				
			},
			success : function(html, textStatus){
				$("#userdiv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// <!--5 Edit user-->
function updateUser()
{
	
	var Ename = document.getElementById('Ename').value;
	//alert(purchaseOrder);
	if (Ename == null || Ename == "") {
		alert("name must be filled out");
		return false;
	}
	var EPhone = document.getElementById('EPhone').value;
	if (EPhone == null || EPhone == "") {
		alert("EPhone must be filled out");
		return false;
	}
	var EEmail = document.getElementById('EEmail').value;
	if (EEmail == null || EEmail == "") {
		alert("EEmail must be filled out");
		return false;
	}
	var EWorkPlace = document.getElementById('EWorkPlace').value;
	var Eusername = document.getElementById('Eusername').value;
	if (Eusername == null || Eusername == "") {
		alert("Eusername must be filled out");
		return false;
	}
	var Epassword = document.getElementById('Epassword').value;
	if (Epassword == null || Epassword == "") {
		alert("Epassword must be filled out");
		return false;
	}
	var Eid = document.getElementById('Eid').value;
	if (Eid == null || Eid == "") {
		alert("Eid must be filled out");
		return false;
	}bringTable = '1';
	
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				Ename : Ename,
				Eid : Eid,
				EPhone : EPhone,
				EEmail : EEmail,
				EWorkPlace : EWorkPlace,
				Eusername : Eusername,
				Epassword : Epassword,
				bringTable : bringTable,
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// BRING TABLE
function bringTable()
{
	var bringTable = '1';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				bringTable : bringTable,
				
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// BRING TABLE
function removeUser(userId)
{
	var deleteUser = userId;
	var r = confirm("Are you sure you want to remove the user with id: "+deleteUser+"");
	if (r == true)
		{
		var bringTable = '1';
			$.ajax({
				type : "GET",
				url : "adminscript.php",
				dataType : "html",
				cache : "false",
				data : {
					deleteUser : deleteUser,
					bringTable : bringTable
				},
				success : function(html, textStatus){
					$("#listTable").html(html);
				},
				error : function(xht, textStatus, errorThrown){
					alert("Error : " + errorThrown);
				}
				});
		}
}
</script>	
</body>
</html>