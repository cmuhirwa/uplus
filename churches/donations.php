<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
     <!-- weather icons -->
    <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css" media="all">
    <!-- metrics graphics (charts) -->
    <link rel="stylesheet" href="bower_components/metrics-graphics/dist/metricsgraphics.css">
    <!-- chartist -->
    <link rel="stylesheet" href="bower_components/chartist/dist/chartist.min.css">
    <!-- c3.js (charts) -->
    <link rel="stylesheet" href="bower_components/c3js-chart/c3.min.css">
    <?php
        $title = "Donations";
        //Including common head configuration

        include_once "head.php";
    ?>
</head>
<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- main header -->
    <?php
        include_once "menu-header.php";
    ?>
    <!-- main sidebar -->
    <?php
        include_once "sidebar.php";
    ?>

    <div id="page_content">
        <div id="page_content_inner">
            <h3 class="heading_b uk-margin-bottom"><?php echo $churchname; ?> - Donations</h3>

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-3">
                    <div class="md-card">
                        <div class="md-card-content">
                            <h4 class="heading_c uk-margin-bottom">Overall Donations</h4>
                            <div id="donations_chart" class="c3chart"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="md-card md-card-uplus">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                                Donation Source (Rwf)
                            </h3>
                        </div>
                        <div class="md-card-content">
                            <?php
                                $sources = donation_sources($churchID);

                                //sum of contributed through the platform
                                $sum_donation = 0;
                                $query = $conn->query("SELECT SUM(amount) as sum FROM donations JOIN members ON donations.member = members.id JOIN branches ON members.branchid = branches.id WHERE branches.church = \"$churchID\" ORDER BY donations.date DESC LIMIT 1 ") or die("Can't get donation sum $conn->error");
                                $dons = $query->fetch_assoc();

                                $sum_donation = $dons['sum'];
                            ?>
                            <table class="uk-table uk-table-hover">
                            <tbody>
                            <tr>
                                <td>MTN Mobile money</td>
                                <td><?php echo number_format($sources['mtn']['amount']??0); ?> Frw</td>
                            </tr>
                            <tr>
                                <td>Tigo Cash</td>
                                <td><?php echo number_format($sources['tigo']['amount']??0); ?> Frw</td>
                            </tr>
                            <tr>
                                <td>Visa Cards</td>
                                <td><?php echo number_format($sources['visa']['amount']??0); ?> Frw</td>
                            </tr>
                            <tr>
                                <td>Master Cards</td>
                                <td><?php echo number_format($sources['master_card']['amount']??0); ?> Frw</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($sum_donation); ?></b> Frw</td>
                            </tr>
                            </tfoot>
                        </table>
                        <br>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="md-card md-card-uplus">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                                Withdraw Account:
                            </h3>
                        </div>
                        <div class="md-card-content">
                            <table width="100%">
                                <tr>
                                    <td>
                                        Holder Bank:
                                    </td> 
                                    <td>
                                        <select class="md-input" disabled>
                                            <option>Bank Of Kigali</option>
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Beneficialy:
                                    </td> 
                                    <td>
                                        <input type="text" class="md-input" name="wacc" value="New Life Gospel Church" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Account Number:
                                    </td> 
                                    <td>
                                        <input type="text" class="md-input" name="wacc" value="04-344BG/789" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                       <button class="md-btn md-btn-success">Change</button>
                                    </td> 
                                    <td>
                                    </td>
                            </table>
                            <br> <br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                <div class="uk-width-large-4-4">
                    <div class="md-card uk-margin-medium-bottom">
                        <div class="md-card-content">
                            <table id="dt_individual_search" class="uk-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date In</th>
                                        <th>Amount</th>
                                        <th>Branch</th>
                                        <th>For</th>
                                        <th>From/To</th>
                                        <th>Method</th>
                                        <th>Acc Number</th>
                                        <th>Transaction ID</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Date In</th>
                                        <th>Amount</th>
                                        <th>Branch</th>
                                        <th>For</th>
                                        <th>From/To</th>
                                        <th>Method</th>
                                        <th>Account</th>
                                        <th>Transaction ID</th>
                                    </tr>
                                </tfoot>

                                <tbody>
                                    <?php
                                        $donations = church_donations($churchID);
                                        for($n=0; $n<count($donations); $n++){
                                            $donation = $donations[$n];
                                            $servicedata  = service_details($donation['service']);
                                            ?>
                                                <tr>
                                                    <td><?php echo $n+1; ?></td>
                                                    <td><?php echo $donation['donation_date'] ?></td>
                                                    <td><?php echo number_format($donation['amount']); ?></td>
                                                    <td><?php echo $donation['branchname']; ?></td>
                                                    <td><?php echo $servicedata['name']; ?></td>
                                                    <td><?php echo $donation['membername'] ?></td>
                                                    <td><?php echo $donation['source'] ?></td>
                                                    <td><?php echo $donation['account'] ?></td>
                                                    <td><?php echo $donation['donation_id'] ?></td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md-fab-wrapper ">
            <!-- <a class="md-fab md-fab-primary" href="javascript:void(0)"><i class="material-icons">add</i></a> -->
            <button class="md-fab md-fab-primary d_inline" href="javascript:void(0)" data-uk-modal="{target:'#service_create'}"><i class="material-icons">shopping_basket</i></button>
        </div>
        <!-- Add group modal -->
        <div class="uk-modal" id="service_create" aria-hidden="true" style="display: none; overflow-y: auto;">
            <div class="uk-modal-dialog" style="width:600px; top: 339.5px;">
                <div class="uk-modal-header uk-tile uk-tile-default">
                    <h3 class="d_inline">Add Basket</h3>
                </div>
                <form method="POST">
                    <div class="md-card">
                        <div class="md-card-content">
                                <div class="uk-grid">                  
                                    <div class="uk-width-medium-1-1 uk-row-first">
                                            <div class="uk-form-row">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-2-2 uk-row-first">
                                                        <div class="md-input-wrapper" id="servname-cont"><label>Basket name</label><input type="text" id="servname" class="md-input"><span class="md-input-bar "></span></div>                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row">
                                                <div class="md-input-wrapper">
                                                    <label>Enter description</label>
                                                    <textarea id="servdes" cols="30" rows="3" class="md-input"></textarea>
                                                    <!-- <input type="text" id="group_location" class="md-input"><span class="md-input-bar "></span></div>      -->
                                            </div>
                                            <div class="uk-form-row">
                                                <div class="group_create_status"></div>
                                            </div>
                                    </div>
                                    <div class="uk-width-medium-1-2">
                                        <div id="group_map"></div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>

                    <div class="uk-modal-footer uk-text-right">
                        <button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
                        <button id="add_service_btn" class="md-btn md-btn-success pull-right">ADD</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>
    <!-- uikit functions -->
    <script src="assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="assets/js/altair_admin_common.min.js"></script>

    <!-- page specific plugins -->
    <!-- datatables -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables buttons-->
    <script src="bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="assets/js/custom/datatables/buttons.uikit.js"></script>
    <script src="bower_components/jszip/dist/jszip.min.js"></script>
    <script src="bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.colVis.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.html5.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.print.js"></script>
    
    <!-- datatables custom integration -->
    <script src="assets/js/custom/datatables/datatables.uikit.min.js"></script>

    <!--  datatables functions -->
    <script src="assets/js/pages/plugins_datatables.min.js"></script>
    
     <!-- page specific plugins -->
    <!-- d3 -->
    <script src="bower_components/d3/d3.min.js"></script>
    <!-- c3.js (charts) -->
    <script src="bower_components/c3js-chart/c3.min.js"></script>
    
    <!--  charts functions -->
    <!-- <script src="assets/js/pages/plugins_charts.min.js"></script> -->
    
    <script>
        $(function() {
            if(isHighDensity()) {
                $.getScript( "bower_components/dense/src/dense.js", function() {
                    // enable hires images
                    altair_helpers.retina_images();
                });
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
        $window.load(function() {
            // ie fixes
            altair_helpers.ie_fix();
        });
    </script>
    <!-- Loading chart -->
    <?php
        $dontypes = donations_by_service($churchID);
        $chartdata = array();
        for ($n=0; $n < count($dontypes) ; $n++) { 
            $chartdata[$dontypes[$n]['servicename']] = $dontypes[$n]['total'];
        }
    ?>
    <script type="text/javascript">
        var chart = c3.generate({
            bindto: '#donations_chart',
            data: {
                // iris data from R
                columns: [
                    <?php
                        for ($n=0; $n < count($dontypes) ; $n++) {
                            $dontype = $dontypes[$n];
                            echo "['$dontype[servicename]', '$dontype[total]'],\n";
                        }
                    ?>
                    ],
                type : 'donut',
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }
            },
            donut:{
                title: "<?php  echo number_format($sum_donation); ?> Frw",
            }
        });
    </script>
    <script type="text/javascript">
        $("#add_service_btn").on('click', function(){
            service_name = $("#servname").val()
            service_description = $("#servdes").val()
            if(service_name && service_description){
                //Sending data to server
                $.post('api/index.php', {action:'add_service', name:service_name, description:service_description, church:<?php echo $churchID ?>}, function(data){
                    
                })
            }else{
                alert("Please fill in everything.")
            }         
        })
    </script>
</body>
</html>