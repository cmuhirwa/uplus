<?php
	ob_start();
	session_start();
	include('db.php');
?>

<?php
	if (!isset($_SESSION["phone1"])) {
	    header("location: logout.php");
	    exit();
	}

  $session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
  $phone = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["phone1"]); // filter everything but numbers and letters
  $password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
  $sql = $db->query("SELECT * FROM users WHERE phone='$phone' AND password='$password' LIMIT 1"); // query the person
  // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
  $existCount = mysqli_num_rows($sql); // count the row nums
  $label="";
  if ($existCount > 0) { 
    while($row = mysqli_fetch_array($sql)){ 
         $thisid = $row["id"];
         //$dateJoin = strftime("%b %d, %Y", strtotime($row["joinedDate"]));       
         $name = $row["name"];
         $email = $row["email"];
         $phone = $row["phone"];
         $gender = $row["gender"];
         $profession = $row["profession"];
         $bio = $row["bio"];
         }
        if($name == ""){
           $label.='Your Name Please?';
         }else{
           $label.='';
         
  }} 
    else{
    echo "
    
    <br/><br/><br/><h3>Your account has been temporally deactivated</h3>
    <p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>   
    Or<p><a href='logout'>Click Here to login again</a></p>
    
    ";
      exit();
  }

  include_once 'event/functions.php';

  //Event details
    $eventId = $_GET['eventId']??"";
    $eventData = $event_data = get_event($eventId);
    $eventName = $eventData['Event_Name'];
    $eventLocation = $eventData['Event_Location'];
    $eventStart = $eventData['Event_Start'];
    $eventImage = $eventData['Event_Cover'];

    $tickets = $eventData['tickets'];

    $agents = $eventData['agents'];
?>

<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Ikimina">
    <meta name="author" content="Clement">

    <title>Ticket | uPlus</title>

    <link rel="icon" type="image/png" href="frontassets/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="frontassets/img/favicon-32x32.png" sizes="32x32">
  
 
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/global/css/bootstrap.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/css/bootstrap-extend.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/css/site.min3f0d.css?v2.2.0">
    <link rel="stylesheet" type="text/css" href="assets/css/eventcss.css">

 <!-- Plugins -->
    <link rel="stylesheet" href="assets/global/vendor/animsition/animsition.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/asscrollable/asScrollable.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/switchery/switchery.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/intro-js/introjs.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/slidepanel/slidePanel.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/flag-icon-css/flag-icon.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/waves/waves.min3f0d.css?v2.2.0">


    <!-- Plugins For This Page -->
    <link rel="stylesheet" href="assets/global/vendor/footable/footable.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/chartist-js/chartist.min3f0d.css?v2.2.0">
    <!-- Plugins For This Page -->
    <link rel="stylesheet" href="assets/global/vendor/bootstrap-sweetalert/sweet-alert.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/toastr/toastr.min3f0d.css?v2.2.0">
    <!-- Page -->
    <link rel="stylesheet" href="assets/examples/css/advanced/alertify.min3f0d.css?v2.2.0">

   <!-- Page -->
    <link rel="stylesheet" href="assets/examples/css/widgets/chart.min3f0d.css?v2.2.0">


  <!-- Plugins For Form Wizard -->
    <link rel="stylesheet" href="assets/global/vendor/jquery-wizard/jquery-wizard.min3f0d.css?v2.2.0">
    <link rel="stylesheet" href="assets/global/vendor/formvalidation/formValidation.min3f0d.css?v2.2.0">

  
  
  
      <!-- Plugins For This Page -->
      <link rel="stylesheet" href="assets/global/vendor/select2/select2.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/bootstrap-tagsinput/bootstrap-tagsinput.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/bootstrap-select/bootstrap-select.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/icheck/icheck.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/switchery/switchery.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/asrange/asRange.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/asspinner/asSpinner.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/clockpicker/clockpicker.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/ascolorpicker/asColorPicker.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/bootstrap-touchspin/bootstrap-touchspin.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/card/card.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/jquery-labelauty/jquery-labelauty.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/bootstrap-datepicker/bootstrap-datepicker.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/bootstrap-maxlength/bootstrap-maxlength.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/jt-timepicker/jquery-timepicker.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/jquery-strength/jquery-strength.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/multi-select/multi-select.min3f0d.css?v2.2.0">
      <link rel="stylesheet" href="assets/global/vendor/typeahead-js/typeahead.min3f0d.css?v2.2.0">

      <!-- Page -->
      <link rel="stylesheet" href="assets/examples/css/forms/advanced.min3f0d.css?v2.2.0">
      
      <!-- Fonts 
      <link rel="stylesheet" href="assets/global/fonts/web-icons/web-icons.min3f0d.css?v2.2.0">-->
      <!-- <link rel="stylesheet" href="assets/global/fonts/brand-icons/brand-icons.min3f0d.css?v2.2.0"> -->
      <link rel="stylesheet" href="assets/global/fonts/material-design/material-design.min3f0d.css?v2.2.0">
      <!-- <link rel="stylesheet" href="assets/global/fonts/brand-icons/brand-icons.min3f0d.css?v2.2.0"> -->
      <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:400,400italic,700'>

      <link rel="stylesheet" type="text/css" href="assets/css/timeline.css">
      <link rel="stylesheet" href="assets/css/style.css">
     
      <!-- Scripts -->
      <script src="assets/global/vendor/modernizr/modernizr.min.js"></script>
      <script src="assets/global/vendor/breakpoints/breakpoints.min.js"></script>
      <script>
        Breakpoints();
      </script>
      
      <script src="assets/js/jquery.js"></script>
       <script src="assets/js/uploadFile.js"></script>

      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
      </script>
      <!-- <script src="assets/js/ajax_call.js"></script> -->
</head>
<body >

  <nav class="site-navbar navbar navbar-inverse navbar-fixed-top navbar-mega" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided"
      data-toggle="menubar">
        <span class="sr-only">Toggle navigation</span>
        <span class="hamburger-bar"></span>
      </button>
      <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse"
      data-toggle="collapse">
        <i class="icon md-more" aria-hidden="true"></i>
      </button>
      <a href="index.php">
        <div style="padding: 12px 50px;" class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
          <img style="    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    height: 50px;
    width: 50px;
    border-radius: 100px;
    /* margin: auto; */
    background-color: #fff;
    cursor: pointer;" class="navbar-brand-logo" src="frontassets/img/logo_main_3.png" title="Uplus">
        </div></a>
      <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-search"
      data-toggle="collapse">
        <span class="sr-only">Toggle Search</span>
        <i class="icon md-search" aria-hidden="true"></i>
      </button>
    </div>

    <div class="navbar-container container-fluid">
      <!-- Navbar Collapse -->
      <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
    <!-- Navbar Toolbar -->
        <ul class="nav navbar-toolbar">
          <li class="hidden-float">
            <a class="icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search"
            role="button">
              <span class="sr-only">Toggle Search</span>
            </a>
          </li>
        </ul>
    
    
        <!-- Navbar Toolbar Right -->
        <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
          <li class="dropdown" id="profile">
            <a class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false"
            data-animation="scale-up" role="button">
              <span class="avatar avatar-online">
                <img src="proimg/<?php echo $thisid;?>.jpg" alt="...">
                <i></i>
              </span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li role="presentation">
                <a href="profile" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profile</a>
              </li>
              <li role="presentation">
                <a href="privacy" role="menuitem"><i class="icon md-settings" aria-hidden="true"></i> Settings</a>
              </li>
              <li class="divider" role="presentation"></li>
              <li role="presentation" >
                <a href="logout" role="menuitem"><i class="icon md-power" aria-hidden="true"></i> Logout</a>
      </li>
            </ul>
          </li>
        </ul>
        <!-- End Navbar Toolbar Right -->
      </div>
      <!-- End Navbar Collapse -->

      <!-- Site Navbar Seach -->
      <div class="collapse navbar-search-overlap" id="site-navbar-search">
        <form role="search">
          <div class="form-group">
            <div class="input-search">
              <i class="input-search-icon md-search" aria-hidden="true"></i>
              <input type="text" class="form-control" name="site-search" placeholder="Search...">
              <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"
              data-toggle="collapse" aria-label="Close"></button>
            </div>
          </div>
        </form>
      </div>
      <!-- End Site Navbar Seach -->
    </div>
  </nav>
 
 <div class="site-menubar site-menubar-dark">
    <div class="site-menubar-body">
      <ul class="site-menu">
        <li class="site-menu-item has-sub active open">
          <a href="events">
          <i class="site-menu-icon md-calendar" aria-hidden="true"></i>
            <span class="site-menu-title">Events</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="agents">
          <i class="site-menu-icon md-account" aria-hidden="true"></i>
            <span class="site-menu-title">Agents</span>
          </a>
        </li> 
    </ul>
    </div>
  </div>

  <!-- Page -->
<div class="page animsition">
    <div class="page-content container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel">
                <div class="panel-heading">
                    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img  class="img-responsive"  src="<?php echo $eventImage; ?>">
                        </div>
                        <div class="col-md-6">

                            <ul class="list-group">
                              <li class="list-group-item">
                                <h3><?php
                                        echo $eventName;
                                      ?>
                                  </h3>
                              </li>
                                <li class="list-group-item">Location: <?php echo $eventLocation; ?></li>
                                <li class="list-group-item">Starting time: <?php echo $eventStart; ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                          <thead>
                            <tr>
                              <td>ID</td>
                              <td>Event Name</td>
                              <td>Total Tickets</td>
                              <td>Sold Tickets</td>
                              <td>Remaining Tickets</td>
                              <td>Shares</td>
                              <td>Clicks</td>
                              <!-- <td>Action</td> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $n=0;

                              $sql = $eventDb->query("
                                SELECT SUM(P.event_seats) seats, SUM(P.price*P.event_seats) price, E.Event_Name, E.id_event
                                FROM pricing P 
                                INNER JOIN eventing_pricing EP
                                ON P.pricing_id = EP.pricing_code
                                INNER JOIN events E
                                ON E.id_event = EP.event_code
                                WHERE E.id_event ='$eventId'");
                              
                                $row = mysqli_fetch_array($sql);

                                $eventName      = $row['Event_Name'];
                                $eventId      = $row['id_event'];
                                $totalTickets     = $row['seats'];
                                $ticketPrice    = $row['price'];
                                $rowSold      = mysqli_fetch_array($eventDb->query("SELECT SUM(amount) soldAmount, COUNT(amount) soldTickets FROM transaction WHERE cust_event_choose = '$eventId'"));
                                $totalSoldTickets = $rowSold['soldTickets'];
                                $totalSoldAmount  = $rowSold['soldAmount'];
                                $n++;
                              echo'<tr>
                                <td>'.$n.'</td>
                                <td>'.$eventName.'</td>
                                <td>'.number_format($totalTickets).' ('.number_format($ticketPrice).' Rwf)</td>
                                <td>'.number_format($totalSoldTickets).' ('.number_format($totalSoldAmount).' Rwf)</td>
                                <td>'.number_format($totalTickets-$totalSoldTickets).' ('.number_format($ticketPrice-$totalSoldAmount).' Rwf)</td>
                                <td>0</td>
                                <td>0</td>
                                
                              </tr>';
                              ?>
                          </tbody>
                        </table>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                      	<div class="sumbox">
                      		<div class="sumbox-title"></div>
                      		<div class="sumbox-subtitle"></div>
                      	</div>
                        <div class="panel">
                        	<!-- <div class="panel-title"></div> -->
                        	
                        	<div class="panel-body">
                        		<?php
                        			$totalAssigned = 0;
                        			foreach ($agents as $key => $ag_data) {
                        				$totalAssigned +=$ag_data['givenTickets'];
                        			}
                        		?>
                        		<p style="font-weight: bold;">Assigned tickets</p>
                        		<b style="font-weight: 500; font-size: 150%"><?php echo $totalAssigned; ?></b>
                        	</div>

                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="panel">                        	
                        	<div class="panel-body">
                        		<p style="font-weight: bold;">Unassigned tickets</p>
                        		<b style="font-size: 150%"><?php echo ($totalTickets - $totalAssigned); ?></b>
                        	</div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>           
        </div>
        <div class="col-lg-12 col-sm-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                  Event Agents
                  <span class="badge badge-info" style="background-color: #00897b;">
                    <?php                    
                    echo $n_agents = count($agents);
                    ?>
                  </span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <td>ID</td>
                          <td>Agent Name</td>
                          <td>Given Tickets</td>
                          <td>Sold Tickets</td>
                          <td>Remaining Tickets</td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php                  
                          for($n=0; $n<$n_agents; $n++)
                          {
                            $ag               = $agents[$n];
                            $agentName        = $ag['agentName'];
                            $totalTickets     = $ag['givenTickets'];
                            $totalSoldTickets = 10;
                            $ticketPrice      = $row['price'];

                            $rowSold      = mysqli_fetch_array($eventDb->query("SELECT SUM(amount) soldAmount, COUNT(amount) soldTickets FROM transaction WHERE cust_event_choose = '$eventId'"));
                            $totalSoldTickets = $rowSold['soldTickets'];
                            $totalSoldAmount  = $rowSold['soldAmount'];

                          echo'<tr>
                            <td>'.($n+1).'</td>
                            <td>'.$agentName.'</td>
                            <td>'.number_format($totalTickets).' ('.number_format($ticketPrice).' Rwf)</td>
                            <td>'.number_format($totalSoldTickets).' ('.number_format($totalSoldAmount).' Rwf)</td>
                            <td>'.number_format($totalTickets-$totalSoldTickets).' ('.number_format($ticketPrice-$totalSoldAmount).' Rwf)</td>
                            <td><a href="editevent'.$eventId.'" class="btn btn-dark btn-xs" style="text-decoration: none;">Manage</a> | <a data-target="#addAgentModal" data-toggle="modal" class="btn btn-warning btn-xs" style="text-decoration: none;">Edit</a></td>
                          </tr>';
                          }
                          ?>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
      </div>
  </div>
</div>



<button class="site-action btn-raised btn btn-success btn-floating" data-target="#addAgentModal" data-toggle="modal" type="button" id="add">
  <span style="
    position: absolute;
    background: #4caf50;
    border-bottom-left-radius: 20px;
    border-top-left-radius: 20px;
    top: 11px;
    font-size: 15px;
    left: -108px;
    padding-bottom: 5px;
    padding-top: 5px;
    padding-right: 13px;
    margin-left: 0px;
">&nbsp; Add an Agent 
</span>
  <i class="icon md-plus" aria-hidden="true" style="
    font-size: 32px;
    text-align: center;
    margin-left: 1px;
"></i>
</button>
  <!-- NEW EVENT POPUP -->
<div class="modal fade" id="addAgentModal" aria-hidden="true" aria-labelledby="addAgentModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content" id="exampleWizardForm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="exampleModalTabs">Add agent</h4>
      </div>
      <div class="modal-body wizard-content">
      <!-- Panel Wizard Form -->
      <div class="panel-body addAgentMod">
       <!-- Wizard Content -->
      <form id="addAgent" action="scripts/newevent.php" method="POST">
        <div class="wizard-pane active" id="exampleAccount" role="tabpanel">
          <div id="exampleAccountForm">
            <div id="stepFill">
              <input id="step" hidden value="info">
            </div>
            <div class="form-group">
              <label class="control-label" for="eventTitle">Agent phone:</label>
              <input type="text" maxlength="13" class="form-control" id="agentPhone" required name="eventTitle" placeholder="Enter phone to search user u want to make agent">
            </div>
            <div class="row tickets-allocation">
                <div class="col-md-12">
                    <!-- <p class="text-muted">Enter number of tickets you assign to agent</p> -->
                    <div style="margin-top: 30px"></div>
                </div>
                <?php
                    for($n=0; $n<count($tickets); $n++){
                        $ticket = $tickets[$n];
                        $ticketmax = $ticket['ticketSeats'] - $ticket['assignedTickets'];
                        ?>
                        <div class="col-md-6">
                            <label><?php echo $ticket['ticketName']." <i>($ticketmax)</i>"; ?></label>
                            <div class="form-group">
                            	<input type="number" class="allocation_input" data-ticket="<?php echo $ticket['ticketId'] ?>" data-max="<?php echo $ticketmax; ?>" max="<?php echo $ticketmax; ?>">
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <input type="hidden" id="eventId" value="<?php echo $eventId; ?>">
            <br>
          </div>
        </div>
        <div class="form-states">
          <div class="form-group pull-right" data-for="send">
              <!-- <button type="button" class="btn btn-danger" data-dismis='modal'>CANCEL</button>&nbsp;&nbsp;&nbsp;&nbsp; -->
              <button type="submit" class="btn btn-primary">ADD</button>
          </div>
          <div class="form-group pull-right display_none" data-for="progress">
              <button type="submit" class="btn btn-primary">ADD</button>
          </div>
          <div class="form-group pull-right display_none" data-for="send">
              <!-- <button type="button" class="btn btn-danger" data-dismis='modal'>CANCEL</button>&nbsp;&nbsp;&nbsp;&nbsp; -->
              <button type="submit" class="btn btn-primary">ADD</button>
          </div>
        </div>
      </form>
       <!-- End Wizard Content -->
      </div>
      <!-- End Panel Wizard One Form -->
    </div>
     </div>
  </div>
</div>
<!-- End NEW EVENT POPUP -->

  <!-- Footer -->
  <footer class="site-footer" style="text-align: center;">
    <div class="site-footer-legal">© <?php echo date("Y") ?> uPlus Mutual Partners LTD</div>
  <a  href="apps/"><i class="icon md-android"></i></a> 
  <div class="site-footer-right">
      Digital Contribution <i class="red-600 wb wb-globe"></i> Platform
    </div>
  </footer>
 
   
<?php include('template/notifications.php');?>
 
  <!-- <script src="assets/js/ajax_call.js"></script> -->

  <!-- Core  -->
  <script src="assets/global/vendor/jquery/jquery.min.js"></script>
  <script src="assets/global/vendor/bootstrap/bootstrap.min.js"></script>
  <script src="assets/global/vendor/animsition/animsition.min.js"></script>
  <script src="assets/global/vendor/asscroll/jquery-asScroll.min.js"></script>
  <script src="assets/global/vendor/mousewheel/jquery.mousewheel.min.js"></script>
  <script src="assets/global/vendor/ashoverscroll/jquery-asHoverScroll.min.js"></script>



  <script>
	//agent
		
	  function log(data){
	  	console.log(data)
	  }

	  $("#addAgent").on('submit', function(e){
	  	e.preventDefault();
	  	agentPhone = $("#agentPhone").val();
	  	event = $("#eventId").val();


	  	tickets = {};
	  	allocation_elems = $(".tickets-allocation input.allocation_input");
	  	log(allocation_elems)
	  	for(n = 0; n<allocation_elems.length; n++){
	  		ticket_unit = allocation_elems[n];
	  		ticket_id = $(ticket_unit).data('ticket')
	  		ticket_number = $(ticket_unit).val()

	  		tickets[ticket_id] = ticket_number;
	  		if(ticket_number){
          $.post('api/index.php', {action:'addAgent', phone:agentPhone, ticketId:ticket_id, givenTickets:ticket_number, eventId:event, invitorId:<?php echo $thisid; ?>}, function(data){
            try{
              ret = JSON.parse(data);
              if(ret.status){
                //successfully added element
                $("#addAgentModal").modal('hide');
                location.reload();
              }else{
                //error
              }
            }catch(e){
              //error decoding
              alert("Decoding error")
            }
	        });
        }	
	  	}
	  });
	</script>

  <!-- Plugins -->
  <script src="assets/global/vendor/switchery/switchery.min.js"></script>
  <script src="assets/global/vendor/slidepanel/jquery-slidePanel.min.js"></script>

 <!-- Plugins For This Page -->
  <script src="assets/global/vendor/formvalidation/formValidation.min.js"></script>
  <script src="assets/global/vendor/formvalidation/framework/bootstrap.min.js"></script>
  <script src="assets/global/vendor/matchheight/jquery.matchHeight-min.js"></script>
  <!-- <script src="assets/global/vendor/jquery-wizard/jquery-wizard.min.js"></script> -->

  <!-- Scripts -->
  <script src="assets/global/js/core.min.js"></script>
  <script src="assets/js/site.min.js"></script>

  <script src="assets/js/sections/menu.min.js"></script>
  <script src="assets/js/sections/menubar.min.js"></script>
  <script src="assets/js/sections/sidebar.min.js"></script>

  <script src="assets/global/js/components/asscrollable.min.js"></script>
  <script src="assets/global/js/components/animsition.min.js"></script>
  <script src="assets/global/js/components/matchheight.min.js"></script>
  <script src="assets/examples/js/forms/advanced.min.js"></script>
 


</body>

</html>



                
