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
        <li class="active">
            <a href="#"><i class="fa fa-user"></i> <span>Employees</span></a>
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
        <div class="box-body baner"> 
          <div class="col-xs-12">
            <div class="col-xs-11">
              <p class="h4baner">Employees</p>
            </div>
            <div class="col-xs-1">
             <a class="btn btn-success add_new_employee"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Default box -->
      <div class="box">
        <div class="box-body" id="empContent">

          <table class="table table-striped table-bordered">
            <thead>
            <tr>
              <th>
                No
              </th>
              <th>
                Names
              </th>
              <th>
                Phone
              </th>
              <th>
                NID
              </th>
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
                $nid = $row['nid'];
                echo '<tr onclick="loopHandle('.$nid.')" class="loopEmpHandle">
                    <td>'.$n.'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['phone'].'</td>
                    <td>'.$row['nid'].'</td>
                  </tr>';
                  $n--;
              }
            ?></tbody>
          </table>
          
        </div>

<!--
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Area Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="areaChart" style="height:250px"></canvas>
              </div>
            </div>
          </div>
         /.box-footer-->
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


<div class="modal" id="AddEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <span><b>Add an employee</b></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
      </div>
      <div class="box box-success modal-body" style="border-top: unset;">
          <div id="alert-box"></div>
          <table class="pull-left col-md-8 ">
            <tbody>

              <div class="form-group">
                <label>Names</label>
                <input type="text" class="form-control" id="empName" placeholder="Names">
              </div>

              <div class="form-group">
                <label>NID</label>
                <input type="text" class="form-control" id="nid" placeholder="National ID Number">
              </div>
              <div class="form-group">
                <label>PHONE</label>
                <input type="text" class="form-control" id="phoneNumber" placeholder="Phone Number">
              </div>
            </tbody>
          </table>
          <div class="clearfix"></div>
          <div class="modal-footer">
            <button type="button" id="save-criteria-job" onclick="saveEmploye()" class="btn btn-primary" >Submit</button>
            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
          </div>
          <div id="loadEmp"></div>
          
      </div>
    </div>
  </div>
</div>

<div class="modal" id="loopEmpHandleModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <span><b>CASUAL DATA</b></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
      </div>
      <div class="box box-success modal-body" style="border-top: unset;">
          <div id="alert-box"></div>
          
      <div id="resolveEmployee"></div>
          <div class="clearfix"></div>
          <div class="modal-footer">
            </div>
          <div id="loadEmp"></div>
          
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

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>

<script type="text/javascript">
  function saveEmploye() {
    var empName = document.getElementById('empName').value;
    if (empName == null || empName == "") {
          alert("empName must be filled out");
          return false;
      }
    var nid = document.getElementById('nid').value;
    if (nid == null || nid == "") {
          alert("nid must be filled out");
          return false;
      }
    var phoneNumber = document.getElementById('phoneNumber').value;
    if (phoneNumber == null || phoneNumber == "") {
          alert("empName must be filled out");
          return false;
      }
    
    document.getElementById('loadEmp').innerHTML ='<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
    
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          
          action      : 'registerEmployee',
          empName     : empName,
          nid         : nid,
          phoneNumber : phoneNumber
        },
        success : function(html, textStatus){
          $("#alert-box").html(html);
          loopEmpData();
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }
  function loopEmpData() {

    
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          
          action      : 'loopEmpData'
        },
        success : function(html, textStatus){
          $("#empContent").html(html);
          document.getElementById('loadEmp').innerHTML ='';
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }
  function loopHandle(employeeId) {
   // var handleId = '25.001/CREDITSCORE/'+employeeId;
    //alert(employeeId);
    document.getElementById('resolveEmployee').innerHTML ='<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
    $.ajax({
        type : "POST",
        url : "api.php",
        dataType : "html",
        cache : "false",
        data : {
          
          action      : 'resolveHandle',
          handleId      : employeeId
        },
        success : function(html, textStatus){
          //alert(html);
          $("#resolveEmployee").html(html);
          document.getElementById('loadEmp').innerHTML ='';
        },
        error : function(xht, textStatus, errorThrown){
          alert("Error : " + errorThrown);
        }
    });
  }
</script>

<script src="js/casual.js"></script>
<script src="js/chartjs/Chart.js"></script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
    // This will get the first returned node in the jQuery collection.
    var areaChart       = new Chart(areaChartCanvas)

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Electronics',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
        {
          label               : 'Digital Goods',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        }
      ]
    }

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale               : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : false,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - Whether the line is curved between points
      bezierCurve             : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension      : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius          : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth     : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke           : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth      : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill             : true,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio     : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive              : true
    }

    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions)

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartOptions         = areaChartOptions
    lineChartOptions.datasetFill = false
    lineChart.Line(areaChartData, lineChartOptions)

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
      {
        value    : 700,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Chrome'
      },
      {
        value    : 500,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'IE'
      },
      {
        value    : 400,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'FireFox'
      },
      {
        value    : 600,
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Safari'
      },
      {
        value    : 300,
        color    : '#3c8dbc',
        highlight: '#3c8dbc',
        label    : 'Opera'
      },
      {
        value    : 100,
        color    : '#d2d6de',
        highlight: '#d2d6de',
        label    : 'Navigator'
      }
    ]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
  })
</script>
</body>
</html>