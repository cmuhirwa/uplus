     <aside id="sidebar_main">
        
        <div class="sidebar_main_header">
            <div class="sidebar_logo">
                <a href="index.html" class="sSidebar_hide sidebar_logo_large">
                    <img class="logo_regular" src="../assets/images/cdh.png" alt="" height="25" width="71"/>
                    <img class="logo_light" src="../assets/images/cdh.png" alt="" height="15" width="71"/>
                </a>
                <a href="index.html" class="sSidebar_show sidebar_logo_small">
                    <img class="logo_regular" src="../assets/images/cdh.png" alt="" height="32" width="32"/>
                    <img class="logo_light" src="../assets/images/cdh.png" alt="" height="32" width="32"/>
                </a>
            </div>
        </div>
        
        <div class="menu_section">
            <ul>
                <li title="Dashboard">
                    <a href="user.php">
                            <span class="menu_icon"><i class="material-icons">home</i></span>
                            <span class="menu_title"><?php echo $row['companyName'];?></span>
                     </a>
                </li>
                <li title="Dashboard">                    
                </li>
                <li title="Members">
                    <a href="javascript:void()">
                        <span class="menu_icon">
                        <i class="material-icons"></i>
                        </span>
                        <span class="menu_title">Customers</span>
                    </a>
                    <ul>
                        <li>
                            <a href="customers.php?compId=<?php echo $comanyId;?>">All</a>
                        </li>
                        <li>
                            <a href="visitors.php">Individual</a>
                        </li>
                        <li>
                            <a href="#">Institution</a>
                        </li>
                        <li>
                            <a href="#">Foreign</a>
                        </li>
                        <li>
                            <a href="#">Domestic</a>
                        </li>
                    </ul>
                </li>
                <li title="Communication">
                    <a href="javascript:void()">
                        <span class="menu_icon"><i class="material-icons">comment</i></span>
                        <span class="menu_title">Communication</span>
                    </a>
                    
                    <ul>
                        <li>
                            <a href="forums.php">Forum</a>
                        </li>
                        <li>
                            <a href="feeds.php">Feeds</a>
                        </li>
                    </ul>
                </li>                
            </ul>
        </div>
    </aside><!-- main sidebar end -->