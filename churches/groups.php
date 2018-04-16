<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/uikit.min.css">
    <!-- dropify -->
    <link rel="stylesheet" href="assets/skins/dropify/css/dropify.css">
    <?php
        $title = "Groups";
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
            <?php
                if(!empty($_GET['act']) && $_GET['act'] == 'edit'){
                    $group = $_GET['group'];

                    $groupData = $conn->query("SELECT groups.*, members.name as representative FROM groups JOIN members ON groups.representative = members.id WHERE groups.id = \"$group\" ") or die("error getting group data ".$conn->error);
                    $groupData = $groupData->fetch_assoc();

                    $group_types = group_types();

                    $gmembers = group_members($group_id);


                    ?>
                        <div id="page_content_inner" data-page="edit">
                            <div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
                                <div class="uk-row-first"><h4 class=""><?php echo $churchname; ?> - Groups</h4></div>
                            </div>
                            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                <div class="uk-width-large-4-4">
                                     <div class="md-card">
                                        <div class="md-card-toolbar">
                                            <h3 class="md-card-toolbar-heading-text">Edit Group</h3>
                                            
                                        </div>
                                        <div class="md-card-content">
                                            <form action="operations.php" method="POST">
                                                <div class="md-input-wrapper">
                                                    <select name="grouptype" data-md-selectize>
                                                        <option value="">Type...</option>
                                                        <?php
                                                            for($n=0; $n<count($group_types); $n++){
                                                                ?>
                                                                    <option value="<?php echo $member_types[$n]; ?>"><?php echo $group_types[$n]; ?><option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                                <select name="membertype" data-md-selectize="" tabindex="-1" style="display: none;" class="selectized"><option value="FULL" selected="selected">FULL</option></select>
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Group Name</label>
                                                    <input type="text" name="membername" value="<?php echo $groupData['name']; ?>" class="md-input">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                                <div class="parsley-row md-input-success">
                                                    <label for="val_select" class="uk-form-label">Operation Branch*</label>
                                                    <select id="val_select" data-md-selectize="" tabindex="-1" style="display: none;" class="selectized" required="" data-parsley-id="29">
                                                        <?php
                                                            $branches = churchbranches($churchID);
                                                            foreach ($branches as $key => $branch) {
                                                                # code...
                                                                ?>
                                                                    <option value="<?php echo $branch['id']; ?>"><?php echo $branch['name']; ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <div class="selectize_fix"></div>
                                                </div>
                                                <!-- <div class="md-input-wrapper md-input-filled">
                                                    <label>Operation Branch</label>
                                                    <select class="selectized">
                                                        <?php
                                                            $branches = churchbranches($churchID);
                                                            foreach ($branches as $key => $branch) {
                                                                # code...
                                                                ?>
                                                                    <option value="<?php echo $branch['id']; ?>"><?php echo $branch['name']; ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div> -->
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Type</label>
                                                    <input type="text" name="memberemail" class="md-input" value="<?php echo $groupData['type'] ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Representative</label>
                                                    <input type="text" name="memberlocation" value="ok" class="md-input">
                                                    <span class="md-input-bar "></span>
                                                </div>

                                                <div class="selectize_fix"></div>
                                                <div class="uk-grid">
                                                    <div class="uk-width-1-1">
                                                        <button class="md-btn md-btn-success">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>              
                        </div>
                    <?php
                }if(!empty($_GET['group'])){
                    $group_id = $_GET['group'];
                    $group_data = group_details($group_id);
                    $groupname = $group_data['name'];
                    $grp_location = $group_data['maplocation']??$group_data['location'];
                    $gmembers = group_members($group_id);
                    ?>
                        <div id="page_content_inner" data-page="group">           
                            <div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
                                <div class="uk-row-first"><h4 class=""><?php echo $churchname; ?> - Groups</h4></div>
                            </div>
                            <div class="md-card uk-margin-bottom">
                                <div class="md-card-toolbar">
                                    <h4 class="md-card-toolbar-heading-text"><?php echo $groupname; ?></h4>
                                    <div class="md-card-toolbar-actions">
                                        <!-- <i class="md-icon material-icons md-color-blue-grey-500"></i> -->
                                        <!-- <i class="md-icon material-icons md-color-light-blue-500"></i> -->
                                        <!-- <i class="md-icon material-icons md-color-light-blue-500">message</i> -->
                                        <i class="md-icon material-icons md-color-red-500" title="Add a member" id="grp_remove" data-grp = <?php echo $group_id; ?> >delete</i>                                        
                                    </div>
                                </div>
                                <div class="md-card-content">
                                    <div class="uk-grid uk-grid-width-medium-1-2">
                                        <div class="uk-row-first">
                                            <div class="uk-overflow-container">
                                                <table id="user" class="uk-table uk-text-nowrap">
                                                    <tbody>
                                                        <tr>
                                                            <td class="uk-width-1-3">Group name</td>
                                                            <td class="uk-width-2-3">
                                                                <div class="md-input-wrapper">
                                                                    <input type="text" id='groupName' name="name" class="md-input" required value="<?php echo $groupname; ?>">
                                                                    <input type="hidden" id='groupid' value="<?php echo $group_id; ?>">
                                                                    <span class="md-input-bar "></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Group type</td>
                                                            <td>
                                                                <div class="md-input-wrapper">
                                                                    <?php
                                                                        $types = group_types($churchID);
                                                                    ?>
                                                                    <select name="branch" id="groupType" data-md-selectize required="required" disabled>
                                                                        <?php
                                                                            $group_type = $group_data['type'];
                                                                            $types = group_types($churchID);
                                                                            for ($n=0; $n<count($types); $n++) {
                                                                                $type = $types[$n];
                                                                                if($type == $group_type){
                                                                                    echo '
                                                                                    <option value="'.$type.'" selected>'.$type.'</option>
                                                                                ';
                                                                                }else{
                                                                                    echo '
                                                                                    <option value="'.$type.'">'.$type.'</option>
                                                                                ';
                                                                                }
                                                                                
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <span class="md-input-bar "></span>
                                                                </div>
                                                        </tr>
                                                        <tr>
                                                            <td>Location</td>
                                                            <td>
                                                                <div class="md-input-wrapper">
                                                                    <input type="text" name="name" id="groupLocation" class="md-input" required value="<?php echo $group_data['location']; ?>">
                                                                    <span class="md-input-bar "></span>
                                                                </div>
                                                        </tr>
                                                        <tr>
                                                            <td>Representative</td>
                                                            <td>
                                                                <div class="md-input-wrapper">
                                                                    <?php
                                                                        $user_data = user_details($group_data['representative']);
                                                                    ?>
                                                                    <select name="branch" data-md-selectize id="groupRepresentative" required="required">
                                                                        <?php
                                                                            for ($n=0; $n<count($gmembers); $n++) {
                                                                                $member = $gmembers[$n];
                                                                                $mem_name = $member['name'];
                                                                                $mem_id = $member['id'];
                                                                                if($user_data['name'] == $member['name']){
                                                                                    echo '
                                                                                    <option value="'.$mem_id.'" selected>'.$mem_name.'</option>
                                                                                ';
                                                                                }else{
                                                                                    echo '
                                                                                    <option value="'.$mem_id.'">'.$mem_name.'</option>
                                                                                ';
                                                                                }
                                                                                
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <span class="md-input-bar "></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr></tr>
                                                        <tr>
                                                            <td>Profile picture</td>
                                                            <td><img src="<?php echo $group_data['profile_picture']; ?>"></td>
                                                            
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div id="groupUpdateStatus"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="group_map" style="height: 450px" data-location="<?php echo $grp_location; ?>" data-group-id="<?php echo $group_id; ?>"></div>
                                            <div class="">                                                
                                                <div style="position: absolute;bottom:15px; right: 2%">
                                                    <button class="md-btn md-btn-info" id="saveGroupChangesBtn" data-uk-modal="{target:'#saveGroupChangesModal'}"><i class="material-icons">save</i></button>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="md-card uk-margin-medium-bottom">
                                <div class="md-card-content">
                                    <?php
                                        //Showing group members                                            
                                        if($gmembers){
                                        ?>
                                            <div class="uk-overflow-container" style="max-width: 1000px;">
                                                <table id="dt_tableExport" class="uk-table memtable" data-group="<?php echo $group_id; ?>" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input class="uk-checkbox checkall" type="checkbox">
                                                            </th>
                                                            <th>#</th>
                                                            <th>Image</th>
                                                            <th>Name</th>
                                                            <th>Join date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        
                                                        for($n=0; $n<count($gmembers); $n++ )
                                                            {
                                                                $member = $gmembers[$n];
                                                                // $member = user_details($gmember['member']);
                                                                $ppic=!empty($member['profile_picture'])?$member['profile_picture']:'gallery/members/default.png';                                                                    
                                                                ?>
                                                                <tr data-member="<?php echo $member['id']; ?>">
                                                                    <td><input class="uk-checkbox" type="checkbox"></td>
                                                                    <td><?php echo $n+1; ?></td>
                                                                    <td><img class="md-user-image" src="<?php echo $ppic; ?>" alt="img"></td>
                                                                    <td><?php echo $member['name']; ?></td>
                                                                    <td><?php echo $member['join_date']; ?></td>
                                                                    <td style="cursor: pointer;" class="removemember"><i class="material-icons">indeterminate_check_box</i></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        ?> 
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php
                                        }else{
                                            //No members
                                            ?>
                                                No members in group yet. Start adding members
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- Add member fab -->
                        <div class="md-fab-wrapper ">
                            <!-- <a class="md-fab md-fab-primary" href="javascript:void(0)"><i class="material-icons">add</i></a> -->
                            <button class="md-fab md-fab-primary d_inline" id="launch_group_create" href="javascript:void(0)" data-uk-modal="{target:'#group_add_member'}"><i class="material-icons">person_add</i></button>
                        </div>
                    <?php
                }else{
                    //getting groups in church
                    $groups = list_groups($churchID);
                    ?>
                        <div id="page_content_inner" data-page="home">

                            <h3 class="heading_b uk-margin-bottom"><?php echo $churchname; ?> - Groups</h3>
                            <div class="md-card uk-margin-medium-bottom">
                                <div class="md-card-content">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <div class="uk-vertical-align">
                                                <div class="uk-vertical-align-middle">
                                                    <ul id="contact_list_filter" class="uk-subnav uk-subnav-pill uk-margin-remove">
                                                        <li class="uk-active" data-uk-filter=""><a href="#">All</a></li>
                                                        <?php
                                                            //Looping through group types
                                                            $group_types = group_types();
                                                            for($n=0; $n<count($group_types); $n++){
                                                                $gtype = $group_types[$n]['name'];
                                                                ?>
                                                                    <li data-uk-filter="<?php echo strtolower($gtype); ?>"><a href="#"><?php echo ucfirst($gtype); ?></a></li>
                                                                <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <label for="contact_list_search">Search... (min 3 char.)</label>
                                            <input class="md-input" type="text" id="contact_list_search"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 class="heading_b uk-text-center grid_no_results" style="display:none">No results found</h3>

                            <div class="uk-grid-width-medium-1-2 uk-grid-width-large-1-3 hierarchical_show" id="contact_list">
                                <?php
                                //looping through groups
                                for($n=0; $n<count($groups); $n++){
                                    $group = $groups[$n]; //current group
                                    $groupname = $group['name'];
                                    $branchname = $group['branchname'];
                                    $group_img = $group['profile_picture'];
                                    $group_type = $group['type'];

                                    $repdata = user_details($group['representative']);
                                    $repemail = $repdata['email'];
                                    $repphone = $repdata['phone'];

                                    $searchabledata = array(strtolower($groupname), strtolower($branchname), strtolower($group_type));
                                    // var_dump($searchabledata);
                                ?>
                                    <div data-uk-filter="<?php echo implode(", ", $searchabledata); ?>">
                                        <div class="md-card md-card-hover md-card-horizontal">
                                            <div class="md-card-head">
                                                <div class="md-card-head-menu" data-uk-dropdown="{pos:'bottom-left'}">
                                                    <i class="md-icon material-icons">&#xE5D4;</i>
                                                    <div class="uk-dropdown uk-dropdown-small">
                                                        <ul class="uk-nav">
                                                            <li><a href="#">Edit</a></li>
                                                            <li><a class="grp_remove" data-grp = <?php echo $group['id'] ?> href="#">Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="uk-text-center">
                                                    <a href="groups.php?group=<?php echo $group['id']; ?>">
                                                        <img class="md-card-head-avatar" src="<?php echo $group_img;?>" alt=""/>
                                                    </a>
                                                </div>
                                                <h3 class="md-card-head-text uk-text-center">
                                                    <?php echo $groupname; ?>                                <span class="uk-text-truncate"><?php echo $branchname; ?> </span>
                                                </h3>
                                                <div class="md-card-head-footmenu">
                                                    <div class="uk-grid">
                                                        <!-- <div class="uk-width-medium-1-3">
                                                            <a class="md-btn md-btn-edit md-btn-wave-light waves-effect waves-button waves-light" href="groups.php?group=<?php echo $group['id']; ?>">GOTO</a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="md-card-content">
                                                <ul class="md-list">
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="md-list-heading">Info</span>
                                                            <span class="uk-text-small uk-text-muted">Type: <?php echo ucfirst($group['type']); ?></span>
                                                            <span class="uk-text-small uk-text-muted">Location: <?php echo $group['location']; ?></span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="md-list-heading">Email</span>
                                                            <span class="uk-text-small uk-text-muted uk-text-truncate"><?php echo $repemail; ?></span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="md-list-heading">Phone</span>
                                                            <span class="uk-text-small uk-text-muted"><?php echo $repphone; ?></span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                              </div>
                        </div>
                        <div class="md-fab-wrapper ">
                            <!-- <a class="md-fab md-fab-primary" href="javascript:void(0)"><i class="material-icons">add</i></a> -->
                            <button class="md-fab md-fab-primary d_inline" id="launch_group_create" href="javascript:void(0)" data-uk-modal="{target:'#modal_default'}"><i class="material-icons">group_add</i></button>
                        </div>

                        <!-- Modals -->
                        <!-- Add group modal -->
                        <div class="uk-modal" id="modal_default" aria-hidden="true" style="display: none; overflow-y: auto;">
                            <div class="uk-modal-dialog" style="width:900px; top: 339.5px;">
                                <div class="uk-modal-header uk-tile uk-tile-default">
                                    <h3 class="d_inline">New Group</h3>
                                </div>
                                <form method="POST">
                                    <div class="md-card">
                                        <div class="md-card-content">
                                            <div class="uk-grid">                  
                                                <div class="uk-width-medium-1-2 uk-row-first">
                                                    <?php
                                                        $group_types = group_types();
                                                    ?>

                                                    <div class="uk-form-row">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <select id="group_type_select" class="md-input">
                                                                <option value="" disabled="" selected="" hidden="">Select type...</option>
                                                                <?php
                                                                    for($n=0; $n<count($group_types); $n++){
                                                                        $group_name = $group_types[$n]['name'];
                                                                        ?>
                                                                            <option value="<?php echo $group_name; ?>"><?php echo $group_name; ?> group</option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            <span class="md-input-bar "></span>
                                                        </div>
                                                    </div>
                                                    <div class="uk-form-row">
                                                        <div class="uk-grid" data-uk-grid-margin="">
                                                            <div class="uk-width-medium-2-2 uk-row-first">
                                                                <div class="md-input-wrapper" id="group_name-cont"><label>Group name</label><input type="text" id="group_name" class="md-input"><span class="md-input-bar "></span></div>                                       
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-form-row">
                                                        <div class="md-input-wrapper"><label>Enter location</label><input type="text" id="group_location" class="md-input"><span class="md-input-bar "></span></div>     
                                                    </div>
                                                    <div class="uk-form-row">
                                                        <div class="uk-grid" data-uk-grid-margin="">
                                                            <div class="uk-width-medium-2-2 uk-row-first">
                                                                <div class="md-input-wrapper md-input-filled change_selectize">
                                                                    <select id="group_rep" class="md-input">
                                                                      <option value="">Choose a representative</option>
                                                                      <?php
                                                                        //Going tp add members of the churches
                                                                        $members = church_members($churchID);
                                                                        foreach ($members as $key => $member) {
                                                                            ?>
                                                                                <option value="<?php echo $member['id']; ?>"><?php echo $member['name']; ?></option>
                                                                            <?php
                                                                        }
                                                                      ?>
                                                                    </select>
                                                                </div>                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-form-row">
                                                        <label>Group image</label>
                                                        <input type="file" id="input-fgroup-pic" class="dropify" data-allowed-file-extensions="png jpgeg jpg"/>
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

                                    <div class="uk-modal-footer uk-text-right">
                                        <button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
                                        <button id="group_add_submit" class="md-btn md-btn-success pull-right">Create</button>
                                    </div>
                                </form> 
                            </div>
                        </div>

                        <!-- Group created modal -->
                        <div class="uk-modal" id="group_created_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
                            <div class="uk-modal-dialog" style="width:400px; top: 339.5px;">
                                <div class="uk-modal-header uk-tile uk-tile-default">
                                    <h3 class="d_inline">Group created!</h3>
                                </div>
                                <div class="md-card">
                                    <div class="md-card-content">
                                        <div class="">
                                            <i class="material-icons uk-text-success">done</i><p class="uk-text-success">Your group was successfully created, It's now functioning</p>
                                        </div>  
                                    </div>
                                </div>
                                <div class="uk-modal-footer uk-text-right">                                    
                                    <div class="md-input-wrapper">
                                        <button class="md-btn md-btn-danger pull-left uk-modal-close">OK</button>
                                        <a id="group_added_btn" class="md-btn md-btn-success pull-right">GOTO Group</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    <?php
                }
            ?>
        <!-- Adding members to group -->
        <div class="uk-modal" id="group_add_member" aria-hidden="true" style="display: none; overflow-y: auto;">
            <div class="uk-modal-dialog" style="max-width:800px;">
                <div class="uk-modal-header uk-tile uk-tile-default">
                    <h3 class="d_inline">Add members to group</h3>
                </div>
                        <?php
                            //Showing group members
                            // $gmembers = group_members($group_id);
                            $gmembers = group_non_members($group_id);
                            if($gmembers){
                            ?>
                                <div class="uk-overflow-container" style="max-width: 500px;">

                                    <table id="dt_tableExport" class="uk-table" data-group cellspacing="0" width="100%" >
                                        <thead>
                                            <tr>
                                                <th><input class="uk-checkbox checkall" type="checkbox"></th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            for($n=0; $n<count($gmembers); $n++ )
                                                {
                                                    $gmember = $gmembers[$n];
                                                    $ppic=!empty($gmember['profile_picture'])?$gmember['profile_picture']:'gallery/members/default.png';

                                                    ?>
                                                    <tr>
                                                        <td><input class="uk-checkbox checkbox_elem" data-member=<?php echo $gmember['id']; ?> type="checkbox"></td>
                                                        <td><img class="md-user-image" src="<?php echo $ppic; ?>" alt="img"></td>
                                                        <td><?php echo $gmember['name']; ?></td>
                                                        <td><?php echo $gmember['type']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?> 
                                            
                                        </tbody>
                                    </table>
                                </div>
                            <?php
                            }else{
                                //No members
                                ?>
                                    Amazing!:) It looks as all church members are in group.
                                <?php
                            }
                        ?>
                    <div class="member_add_status"></div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="md-btn md-btn-danger pull-left uk-modal-close">CANCEL</button>
                    <button id="members_add_submit" class="md-btn md-btn-success pull-right">ADD <span id="add_member_num"></span></button>
                </div>
            </div>
        </div>
        <!--  saveGroupChangesModal -->
        <div class="uk-modal" id="saveGroupChangesModal" aria-hidden="true" style="display: none; overflow-y: auto;">
            <div class="uk-modal-dialog" style="max-width:800px;">
                <div class="uk-modal-header uk-tile uk-tile-default">
                    <h3 class="d_inline">Updating group</h3>
                </div>
                <div class="uk-overflow-container" style="max-width: 500px;">
                    <p>Are you sure you want to update changes on the group?</p>
                </div>
                    <div class="member_add_status"></div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="md-btn md-btn-danger pull-left uk-modal-close">CANCEL</button>
                    <button id="saveGroupChangesConfirm" class="md-btn md-btn-success pull-right">CONFIRM <span id="add_member_num"></span></button>
                </div>
            </div>
        </div>
        <!-- <div id="mapLoad"></div> -->
    </div>

    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery.js"></script>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>

    <!-- uikit functions -->
    <script src="assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="assets/js/altair_admin_common.min.js"></script>

    <!-- page specific plugins -->

    <!--  contact list functions -->
    <script src="assets/js/pages/page_contact_list.min.js"></script>

    <script src="bower_components/dropify/dist/js/dropify.min.js"></script>

    <!-- mockAjax -->
    <script src="bower_components/jquery-mockjax/dist/jquery.mockjax.min.js"></script>
    <!-- jqueryUI -->
    <script src="bower_components/x-editable/dist/jquery-editable/jquery-ui-datepicker/js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- poshytip -->
    <script src="assets/js/custom/xeditable/jquery.poshytip.min.js"></script>
    <!-- select2 -->
    <script src="assets/js/custom/xeditable/select2/select2.min.js"></script>
    <!-- xeditable -->
    <script src="bower_components/x-editable/dist/jquery-editable/js/jquery-editable-poshytip.js"></script>

    <!--  xeditable functions -->
    <script src="assets/js/pages/plugins_xeditable.min.js"></script>



    <!-- Group specific custom script -->
    <script type="text/javascript" src="js/groups.js"></script>
    <script type="text/javascript">
        function loadmaps(){
            //Checking pagename
            pagename = $("#page_content_inner").attr('data-page');

            group_location = $(".group_map").attr("data-location");
            if(pagename == 'group'){
                //Loading the group's map
                var kigali = {lat:-1.991019, lng:30.096819};
                var map_location = {lat:parseFloat(group_location.split(",")[0]), lng:parseFloat(group_location.split(",")[1])};
                log(map_location);
                var map = new google.maps.Map(document.querySelector('.group_map'), {
                    zoom: 17,
                    center: map_location
                });
                var marker = new google.maps.Marker({
                  position: map_location,
                  map: map
                });
            }else if(pagename == 'home'){
                //Loading map for choosing location
                var kigali = {lat:-1.991019, lng:30.096819};
                var map_location = map_location;

                var map = new google.maps.Map(document.querySelector('#group_map'), {
                    zoom: 17,
                    center: map_location
                });
                var marker = new google.maps.Marker({
                  position: map_location,
                  map: map
                });

            }
        }

        $("#select_rep").editable({
            source:[
                {
                    value:1, text:'Admin admin'
                }
            ]
        })
    </script>

    <!-- Google maps -->
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyAlKttaE2WuI1xKpvt-f7dBOzcBEHRaUBA&libraries=places&callback=loadmaps"></script>

    <script>
        $(function() {
            if(isHighDensity()) {
                $.getScript( "assets/js/custom/dense.min.js", function(data) {
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
</body>
</html>