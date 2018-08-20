<?php
  if (isset($_GET['payroll'])) {
    $payrollId = $_GET['payroll'];
   } 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Payroll info | CASUAL APP</title>
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

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
   <style type="text/css">
    .progress-bar{
      background-color: #06467c;
    }
    #handlesHolder, .card.card-fluid {
        overflow-y: scroll;
        height: 400px;
    }
    #handlesHolder{
      padding: 5px 10px;
    }
  </style>
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
        <li class="active treeview">
          <a href="payrolls.php">
            <i class="fa fa-folder"></i> <span>Payrolls</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          <ul class="treeview-menu">
            <li class="active">
              <a href="#"><i class="fa fa-circle-o"></i>
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
        <div class="box-body baner" style="background: #0e6e65"> 
          <div class="col-xs-12">
            <div class="col-xs-1">
              <a class="btn btn-primary" href="payrolls.php"><i class="fa fa-arrow-left"></i></a>
            </div>
            <div class="col-xs-10" style="font-size: 16px; font-weight: 600;">
              <table>
                  <tr><td><a style="color: #fff; cursor: pointer;">PAYROLL: </td><td>&nbsp;&nbsp;
                  <?php 
                    require('db.php');
                    $sql = $db->query("SELECT  fromDate, toDate FROM payrolls WHERE id = '$payrollId'");
                    $row = mysqli_fetch_array($sql);
                    echo ''.strftime("%d %b", strtotime($row['fromDate'])).' - '.strftime("%d %b", strtotime($row['toDate'])).'';
                  ?></a></td></tr>
                  <tr style="color: #fff;font-weight: 400; cursor: pointer;"><td>AMOUNT: </td><td id="payrollBalance"></td></tr>
                  <tr style="color: #fff;font-weight: 400; cursor: pointer;"><td>PAID: </td><td id="paidBalance"></td></tr>
                  <tr style="color: #fff;font-weight: 400; cursor: pointer;"><td>UNPAID: </td><td id="unpaidBalance"></td></tr>
                
                </table>
            </div>
            <div class="col-xs-1">
              <a class="btn btn-success add_new_payroll"><i class="fa fa-plus"></i></a><br><br>
              <a class="btn btn-warning" id="payCasuals"><i class="fa fa-thumbs-up"></i></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Default box -->
      <div class="box">
        <div class="box-body" id="empContent">

        </div>
       

        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
  <div class="row mainContent">
    <div class="col-md-1"></div>
      
        <div class="col-md-10">
          <div class="progress">
          <div class="progress-bar progress-bar-striped active progress-bar-dark" role="progressbar" id="handleProgress" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
            0%
          </div>
        </div>
        </div>
        <div class="col-md-1"></div>
      </div>
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
         <div class="modal-header"> <span><b>Add a employees on the payroll</b></span>
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
           </div>
         <div class="modal-body">
          <div id="alert-box"></div>
           <table class="pull-left col-md-8 table-striped table-bordered">
           <thead>
             <tr>
               <th>N</th>
               <th>Name</th>
               <th>Action</th>
             </tr>
           </thead>
           <tbody>
            <?php
             // $n=0;
              include 'db.php';
              $sql = $db->query("SELECT * FROM casuals ORDER BY id DESC");
              $n = mysqli_num_rows($sql);
              while ($row = mysqli_fetch_array($sql)) 
              {
                echo '<tr>
                  <td>'.$n.'</td>
                  <td>'.$row['name'].'</td>
                  <td>
                  <select id="addEmp'.$row['id'].'" onchange="addEmpOnPayroll('.$row['id'].')"><option value="0"></option>';

                      $sql2 = $db->query("SELECT * FROM categories ORDER BY id DESC");
                      while ($rowCategory = mysqli_fetch_array($sql2)) 
                      {
                        echo'<option value="'.$rowCategory['id'].'">'.$rowCategory['catName'].'</option>';
                      }

                  echo'</select>
                  </td>
                </tr>';
                $n--;
              }
            ?>
            
          </tbody>
          </table>
              <div class="clearfix" ></div>
      <div class="modal-footer">
        <button type="button" id="save-criteria-job" class="btn btn-primary" onclick="addPayroll()">Submit</button>
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
      </div>
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
  var payrollCode = '<?php echo $payrollId;?>';
  function addEmpOnPayroll(empId) {
    var catCode = document.getElementById('addEmp'+empId).value;
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          
          action      : 'addEmpOnPayroll',
          catCode     : catCode,
          empId       : empId,
          payrollCode : payrollCode
        },
        success : function(html, textStatus){
          $("#alert-box").html(html);
          loopEmpOnPayroll(payrollCode);
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }

  function loopEmpOnPayroll(payrollCode) {
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          
          action      : 'loopEmpOnPayroll',
          payrollId   : payrollCode        
        },
        success : function(html, textStatus){

              //PAYROLL BALANCE
              $.ajax({
                  type : "POST",
                  url : "api.php",
                  dataType : "html",
                  cache : "false",
                  data : {
                    
                    action      : 'payrollBalance',
                    payrollId   : payrollCode        
                  },
                  success : function(html, textStatus){
                    $("#payrollBalance").html(html);
                  },
                  error : function(xht, textStatus, errorThrown){
                    alert("Error : " + errorThrown);
                  }
              });
              //PAID BALANCE
              $.ajax({
                  type : "POST",
                  url : "api.php",
                  dataType : "html",
                  cache : "false",
                  data : {
                    
                    action      : 'paidBalance',
                    payrollId   : payrollCode        
                  },
                  success : function(html, textStatus){
                    $("#paidBalance").html(html);
                  },
                  error : function(xht, textStatus, errorThrown){
                    alert("Error : " + errorThrown);
                  }
              });
              
              //UNPAID BALANCE
              $.ajax({
                  type : "POST",
                  url : "api.php",
                  dataType : "html",
                  cache : "false",
                  data : {
                    
                    action      : 'unpaidBalance',
                    payrollId   : payrollCode        
                  },
                  success : function(html, textStatus){
                    $("#unpaidBalance").html(html);
                  },
                  error : function(xht, textStatus, errorThrown){
                    alert("Error : " + errorThrown);
                  }
              });
          $("#empContent").html(html);
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });


  }
  loopEmpOnPayroll(payrollCode);
</script>
<script type="text/javascript">
  //looping 
  //when button is clicked
  genBtn = document.getElementById('payCasuals');


  var progressElem = document.getElementById('handleProgress');
  var percentage = 0;

  genBtn.addEventListener('click', function(){
    $("#handleProgress").css('width', 0+'%')
    $("#handleProgress").html(0+'%');
    //get all people to generate handle
    var handleElems = document.querySelectorAll('#peopleTable tr.UNPAID')
    var progressElem = document.getElementById('handleProgress')

    var nGenerated = 0; //number of handles generated
    var nhandleElems = handleElems.length;
    


    var n = 0;
    function myLoop() {
      
      setTimeout(function() 
      {
        handleElem = handleElems[n];
        //getting details of user
        casualCode   = handleElem.dataset.names;
        payrollCode  = handleElem.dataset.gender;
        amount       = handleElem.dataset.amount;
        account       = handleElem.dataset.account;
        //alert(amount);
        $.ajax({
          type: "POST",
          url: "api.php",
          dataType: "html",
          async: true,
          cache: false,
          data: {
            action        : 'payCasual',
            casualCode    : casualCode,
            payrollCode   : payrollCode,
            amount        : amount,
            account       : account
          },
          success: function(html, textStatus){
            //progress changing
             console.log(html)
            
            // progressElem.style.width = percentage+'%'
            // progressElem.innerText = percentage+'%'
            if(html.length>0){
              setTimeout(function(){
              nGenerated++
              percentage = ((nGenerated/nhandleElems)*100).toFixed(0)
              changeProgress(percentage, nGenerated, html)

            }, 100);
            }
            loopEmpOnPayroll(payrollCode);          
          },
          error : function(xht, textStatus, errorThrown){
            alert("Error : " + errorThrown);
          }
        });
        n++;
        if (n < ((handleElems.length)+1)) {
          myLoop();
        }
      }, 1000)
    };  
      myLoop();
    
  });
  function changeProgress(percentage, n, html){
    //document.getElementById('doaCounter').innerHTML = 'DOA ('+n+')';
    
    percentage = percentage.toString()
    // alert(percentage)
    // progressElem.style.width = percentage+'%'
    $("#handleProgress").css('width', percentage+'%')
    $("#handleProgress").html(percentage+'%');
    if(percentage == 100){
      alert(html);
    }
  }
</script> 

</body>
</html>
