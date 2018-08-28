
<?php
  require_once('Controllers/Authentication.php');
  $auth = new Authentication();
  $auth->checkAuthentication();
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Payrolls | CASUAL APP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="css/ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
   <link rel="stylesheet" href="css/_all-skins.min.css"> 
    <link rel="stylesheet" href="css/casual.css"> 


  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">CASUAL APP</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
   
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="images/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php  echo $_SESSION["first_name"].' '.$_SESSION["last_name"]; ?></p>
        </div>
      </div>
       <ul class="sidebar-menu" data-widget="tree">
        <li> <a href="index.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="employees.php"><i class="fa fa-user"></i> <span>Employees</span></a>
        </li>
        <li class="active treeview">
          <a >
            <i class="fa fa-folder"></i> <span>Payrolls</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          <ul class="treeview-menu">
            <li class="active">
              <a href="payrolls.php"><i class="fa fa-circle-o"></i>
                Auto
              </a>
            </li>
            <li>
              <a href="#"><i class="fa fa-circle-o"></i>
                Manuel
              </a>
            </li>
          </ul>
        </li>
        
        <li>
            <a href="setup.php"><i class="fa fa-gear"></i> <span>Setup</span></a>
        </li>
        <li>
            <a href="Controllers/logout.php"><i class="fa fa-sign-out text-red"></i> <span>Logout</span></a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <div class="box box-solid">
        <div class="box-body baner" style="background: #931367"> 
          <div class="col-xs-12">
            <div class="col-xs-11">
              <p class="h4baner">Payrolls</p>
            </div>
            <div class="col-xs-1">
             <a class="btn btn-success add_new_payroll"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Default box -->
      <div class="box">
        <div class="box-body" id="payrollContent">

          
        </div>
       
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2018 <a href="https://adminlte.io">Casual payroll</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- MODALS -->

<div class="modal" id="AddPayrollsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <span><b>Add a payroll</b></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div id="alert-box"></div>
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-6">
                <div class="form-group">
                  <label>From</label>
                  <input type="date" class="form-control" id="fromDate" placeholder="Duration">
                </div>
              </div>
              <div class="col-xs-6">
                <div class="form-group">
                  <label>To</label>
                  <input type="date" class="form-control" id="toDate" placeholder="Duration">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6">Start</div>
              <div class="col-xs-6">End</div>
              <div class="col-xs-3">
                <div class="form-group">
                  <label>ON</label>
                  <input type="time" class="form-control" id="startOn" placeholder="Duration">
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group">
                  <label>OFF</label>
                  <input type="time" class="form-control" id="startOff" placeholder="Duration">
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group">
                  <label>ON</label>
                  <input type="time" class="form-control" id="endOn" placeholder="Duration">
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group">
                  <label>OFF</label>
                  <input type="time" class="form-control" id="endOff" placeholder="Duration">
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
      <button type="button" id="save-criteria-job" class="btn btn-primary" onclick="addPayroll()">Submit</button>
      <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
</div> 

<!-- jQuery 3 -->
<script src="js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>

<script src="js/casual.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>

<script type="text/javascript">
  
  
  function addPayroll() {
    var fromDate = document.getElementById('fromDate').value;
    if (fromDate == null || fromDate == "") {
          alert("fromDate must be filled out");
          return false;
      }
    var toDate = document.getElementById('toDate').value;
    if (toDate == null || toDate == "") {
          alert("toDate must be filled out");
          return false;
      }
    var startOn = document.getElementById('startOn').value;
    if (startOn == null || startOn == "") {
          alert("startOn must be filled out");
          return false;
      }
    var startOff = document.getElementById('startOff').value;
    if (startOff == null || startOff == "") {
          alert("empName must be filled out");
          return false;
      }
    var endOn = document.getElementById('endOn').value;
    if (endOn == null || endOn == "") {
          alert("endOn must be filled out");
          return false;
      }
    var endOff = document.getElementById('endOff').value;
    if (endOff == null || endOff == "") {
          alert("endOff must be filled out");
          return false;
      }
    
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          
          action      : 'addPayroll',
          fromDate    : fromDate,
          toDate      : toDate,
          startOn     : startOn,
          startOff    : startOff,
          endOn       : endOn,
          endOff      : endOff
        },
        success : function(html, textStatus){
          $("#alert-box").html(html);
          loadData();
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });

  }
  function loadData() {
    // body...
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : { 
          action      : 'loopPayrollData'
        },
        success : function(html, textStatus){
          $("#payrollContent").html(html);
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }
  (loadData())();
</script>
</body>
</html>
