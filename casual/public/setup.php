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
          <p>Manager Names</p>
          
        </div>
      </div>
       <ul class="sidebar-menu" data-widget="tree">
        <li> <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="employees.php"><i class="fa fa-user"></i> <span>Employees</span></a>
        </li>
        
        <li class="treeview">
          <a href="payrolls.php">
            <i class="fa fa-folder"></i> <span>Payrolls</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          <ul class="treeview-menu">
            <li>
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
        <li class="active">
            <a href="#"><i class="fa fa-gear"></i> <span>Setup</span></a>
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
      <div class="row">
        <div class="col-md-6">
         <div class="box box-solid">
          <div class="box-body baner" style="background: black"> 
            <div class="col-xs-12">
              <div class="col-xs-1">
                <i class="fa fa-user"></i>
              </div>
              <div class="col-xs-10">
                <p class="h4baner">CATEGORIES</p>
              </div>
              <div class="col-xs-1">
               <a class="btn btn-success addCat"><i class="fa fa-plus"></i></a>
              </div>
            </div>
          </div>
          </div>
        </div>
        <div class="col-md-6">
         <div class="box box-solid">
          <div class="box-body baner" style="background: black"> 
            <div class="col-xs-12">
              <div class="col-xs-1">
               <i class="fa fa-money"></i>
              </div>
              <div class="col-xs-10">
                <p class="h4baner">WALLET</p>
              </div>
              <div class="col-xs-1">
               <a class="btn btn-success addMonay"><i class="fa fa-plus"></i></a>
              </div>
            </div>
          </div>
          </div>
        </div>  
      </div>

      <!-- Default box -->
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-body" id="categoriesContent">

            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box-body" id="walletContent">
            </div>
          </div>
        </div>
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

<div class="modal" id="addCatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <span><b>Add a category</b></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div id="alert-box"></div>
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-6">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control" id="catName" placeholder="">
                </div>
              </div>
              <div class="col-xs-6">
                <div class="form-group">
                  <label>Amount</label>
                  <input type="text" class="form-control" id="catAmount" placeholder="Rwf">
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
      <button type="button" id="save-criteria-job" class="btn btn-primary" onclick="addCategory()" data-dismiss="modal">Submit</button>
      <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
</div> 

<div class="modal" id="addMonayModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <span><b>Load Your Wallet</b></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div id="alert-box"></div>
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-6">
                <div class="form-group">
                  <label>Mobile Account</label>
                  <input type="text" class="form-control" id="phone" placeholder="">
                </div>
              </div>
              <div class="col-xs-6">
                <div class="form-group">
                  <label>Amount</label>
                  <input type="text" class="form-control" id="amount" placeholder="Rwf">
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
      <button type="button" id="save-criteria-job" class="btn btn-primary" onclick="loadMoney()" data-dismiss="modal">Submit</button>
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
  
  
  function addCategory() {
    var catAmount = document.getElementById('catAmount').value;
    if (catAmount == null || catAmount == "") {
          alert("phone must be filled out");
          return false;
      }
    var catName = document.getElementById('catName').value;
    if (catName == null || catName == "") {
          alert("catName must be filled out");
          return false;
      }
    
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          action        : 'addCategory',
          catAmount     : catAmount,
          catName       : catName
        },
        success : function(html, textStatus){
            loadData();
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }

  
  function loadMoney() {
    var amount = document.getElementById('amount').value;
    if (amount == null || amount == "") {
          alert("phone must be filled out");
          return false;
      }
    var phone = document.getElementById('phone').value;
    if (phone == null || phone == "") {
          alert("phone must be filled out");
          return false;
      }
    
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          action      : 'loadMoney',
          amount      : amount,
          account     : phone
        },
        success : function(html, textStatus){
            loopWallet();
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }

  function removeCat(catId) {
     $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          action    : 'removeCat',
          catId     : catId
        },
        success : function(html, textStatus){
            loadData();
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
      });  
  }

  function loadData() {
    
    loopWallet();
    // body...
    $.ajax({
        type      : "POST",
        url       : "api.php",
        dataType  : "html",
        cache     : "false",
        data : { 
          action      : 'loopCategories'
        },
        success : function(html, textStatus){
          $("#categoriesContent").html(html);
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }

  loadData();

  function loopWallet() {
    // body...
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : { 
          action      : 'loopWallet'
        },
        success : function(html, textStatus){
          $("#walletContent").html(html);
          //alert(html);
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }
</script>
</body>
</html>
