<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
       <div class="user-details">
            <div class="pull-left">
                <img src="assets/images/users/<?php echo $thisid;?>.jpg" alt="" class="thumb-md img-circle">
            </div>
            <div class="user-info">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $user_data['names'];?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li>
                        <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li>
                        <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li>
                        <li><a href="logout.php"><i class="md md-settings-power"></i> Logout</a></li>
                    </ul>
                </div>
                
                <p class="text-muted m-0"><?php echo $user_data['account_type'];?></p>
            </div>
        </div>
         <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li class="">
                    <a href="index.php" class="waves-effect waves-light "><i class="ion-ios7-gear"></i><span>HOME</span></a>
                </li>
                <li class="has_sub">
                    <a href="products.php" class="waves-effect waves-light"><i class="ion-bag"></i><span>Products</span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li ><a href="list.php" class="waves-effect waves-light "><i class="ion-ios7-pulse-strong"></i>Product List</a></li>
                        <li ><a href="products.php" class="waves-effect waves-light "><i class="ion-ios7-pulse-strong"></i>Buy And Sell</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="po.php" class="waves-effect waves-light"><i class="ion-android-contacts"></i><span>People</span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="active"><a href="po.php" class="waves-effect waves-light active"><i class="ion-android-contact"></i>Users</a></li>
                        <li><a href="clients.php" class="waves-effect waves-light"><i class="ion-android-social-user"></i>Clients</a></li>
                        <li><a href="suppliers.php" class="waves-effect waves-light"><i class="ion-android-social"></i>Supplier</a></li>
                        
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="" class="waves-effect waves-light"><i class="ion-ios7-albums"></i><span>Documents</span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="invoices.php" class="waves-effect waves-light"><i class="ion-document"></i>Invoices</a></li>
                        <li><a href="po.php" class="waves-effect waves-light"><i class="ion-clipboard"></i>Proforma Invoice</a></li>
                        <li><a href="po.php" class="waves-effect waves-light"><i class="ion-document-text"></i>Purchase Order</a></li>
                    </ul>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="reports.php" class="waves-effect waves-light"><i class="ion-ios7-pulse-strong"></i><span>KPI Reports</span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="reports.php">General</a></li>
                        <li><a href="fmcg.php">Faster | Slow Items</a></li>
                        <li><a href="roi.php">Return On Investment</a></li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>