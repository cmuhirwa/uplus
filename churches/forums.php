<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <?php
        $title = "Forums";
        //Including common head configuration
        include_once "head.php";
    ?>
     <!-- dropify -->
    <link rel="stylesheet" href="assets/skins/dropify/css/dropify.css">
</head>
<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <!-- main header -->
    <?php
        include_once "menu-header.php";
    ?>
    <!-- main sidebar -->
    <?php
        include_once "sidebar.php";
        $church_services = church_services($churchID);

        //
        $forum = $_GET['id']??"";
        if(!empty($forum)){
            $forumData = getForum($forum);
            $forum_title = $forumData['forumtitle']??"";
            $forum_logo = $usual_logo =  $forumData['logo'];
            $forum_status = empty($forumData['archiveDate'])?'active':'archive';
            ?>
                <div id="page_content">
                    <div id="page_content_inner">
                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="uk-form-stacked" id="user_edit_form" enctype="multipart/form-data">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-large-7-10">
                                    <div class="md-card">
                                        <div class="user_heading" data-uk-sticky="{ top: 48, media: 960 }">
                                            <div class="user_heading_avatar fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail">
                                                    <img src="<?php echo $forumData['logo']; ?>" alt="user avatar"/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                <div class="user_avatar_controls">
                                                    <span class="btn-file">
                                                        <span class="fileinput-new"><i class="material-icons">&#xE2C6;</i></span>
                                                        <span class="fileinput-exists"><i class="material-icons">&#xE86A;</i></span>
                                                        <input type="file" name="forum_logo" id="user_edit_avatar_control">
                                                    </span>
                                                    <a href="#" class="btn-file fileinput-exists" data-dismiss="fileinput"><i class="material-icons">&#xE5CD;</i></a>
                                                </div>
                                            </div>
                                            <div class="user_heading_content">
                                                <h2 class="heading_b"><span class="uk-text-truncate" id="user_edit_uname"><?php echo $forum_title; ?></span><span class="sub-heading" id="user_edit_position">Started <?php echo date($standard_date, strtotime($forumData['addedDate'])); ?></span></h2>
                                            </div>
                                            <div class="md-fab-wrapper">
                                                 <?php
                                                    if($forum_status == 'active'){
                                                ?>
                                                    <div class="md-fab md-fab-small md-fab-danger">
                                                        <i class="material-icons md-color-red" id="delete_forum_btn" data-forum = "<?php echo $forum; ?>" title="delete forum">&#xE872;</i>
                                                    </div>
                                                <?php }else{
                                                    ?>
                                                        <div class="md-fab md-fab-small md-fab-success">
                                                            <i class="material-icons md-color-red" id="activate_forum_btn" data-forum = "<?php echo $forum; ?>" title="Re-Activate forum">done</i>
                                                        </div>
                                                    <?php
                                                } ?>
                                                
                                            </div>
                                        </div>
                                        <div class="user_content">
                                            <?php
                                                if(!empty($_POST)){
                                                    $title = $_POST['forumtitle']??"";
                                                    $intro = $_POST['intro']??"";

                                                    if($intro && $title){
                                                        $forum_logo = $_FILES['forum_logo'];

                                                        if($forum_logo['size']>10){

                                                            $ext = strtolower(pathinfo($forum_logo['name'], PATHINFO_EXTENSION)); //extensin

                                                            if($ext == 'png' || $ext == 'jpg'){
                                                                $filename = "gallery/church/".strtolower(clean_string($title))."_".time().".$ext";
                                                                if(!move_uploaded_file($forum_logo['tmp_name'], "$filename")){
                                                                    trigger_error("Error uploading the file");
                                                                    $filename = $forum_logo;
                                                                }
                                                            }else{
                                                                $filename = $usual_logo;
                                                            }
                                                        }else{
                                                            $filename = $usual_logo;
                                                        }

                                                        //updating
                                                        $query = $conn->query("UPDATE forums SET forumtitle = \"$title\", intro = \"$intro\", logo = \"$filename\", updatedDate = NOW(), updatedBy = $userId WHERE id = \"$forum\"  ") or trigger_error($conn->error);
                                                        if($query){
                                                            header("location:".$_SERVER['REQUEST_URI']);
                                                        }
                                                    }else{
                                                        echo "Sure??";
                                                    }
                                                }
                                            ?>
                                            <div class="md-input-wrapper md-input-filled">
                                                <label>Forum title</label>
                                                <input type="text" name="forumtitle" id="forumtitle_input" value="<?php echo $forum_title; ?>" class="md-input" required="required">
                                                <span class="md-input-bar "></span>
                                            </div>
                                            <div class="md-input-wrapper md-input-filled">
                                                <textarea cols="20" rows="2" id="forum_intro" name="intro" class="md-input autosized" placeholder="What's the forum about?" style="overflow-x: hidden; word-wrap: break-word;"><?php echo $forumData['intro']; ?></textarea>
                                                <span class="md-input-bar "></span>
                                            </div>
                                            <div class="md-input-wrapper">
                                                <button class="md-btn md-btn-primary" type="submit">UPDATE</button>
                                            </div>                                         
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-large-3-10">
                                    <div class="md-card">
                                        <div class="md-card-content">
                                            <h3 class="heading_c uk-margin-medium-bottom">Summary</h3>
                                            <div class="uk-form-row">
                                                <i class="md-icon material-icons md-color-light-blue-500">person_outline</i> <span id="user_count"></span> <?php echo " of ".total_users(); ?> joined
                                                    <?php
                                                        if(!empty($forumData['updatedDate']) && 0){
                                                            ?>
                                                              <li>Last updated: <?php echo $forumData['updatedDate'] ?> by <i><?php echo staff_details($forumData['updatedBy'])['name'] ?></i></li>  
                                                            <?php
                                                        }

                                                    ?>

                                                <!-- <?php
                                                    if($forum_status == 'active' ){
                                                ?>
                                                    <input type="checkbox" checked data-switchery id="user_edit_active" disabled/>
                                                    <label for="user_edit_active" class="inline-label">Forum Active</label>
                                                <?php }else{
                                                    ?>
                                                        <input type="checkbox" data-switchery id="user_edit_active" disabled/>
                                                        <label for="user_edit_active" class="inline-label">Forum Archived</label>
                                                    <?php
                                                } ?> -->
                                            </div>                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            <?php
        }else{
    ?>
        <div id="page_content">
            <div id="page_content_inner">
                <div class="heading_a uk-grid uk-margin-bottom uk-grid-width-large-1-2">
                    <div class="uk-row-first"><h4 class="">All forums</h4></div>
                </div>

                <div class=" uk-grid uk-margin-bottom uk-grid-medium" data-uk-grid-margin>   
                    <div class="uk-width-large-4-4">
                        <!-- <div class="md-card">
                            <div class="md-card-content">
                                <div class=" uk-grid">
                                    <div class="uk-width-3-4">
                                        <h4 class="heading_c uk-margin-bottom">Forums engagement</h4>
                                    </div>
                                    <div class="uk-width-1-4">
                                        <form class="-form">
                                            <div class="uk--select"  data-uk-form-select>
                                                <select id="selectChart" class="md-input">
                                                    <option value="service">Service</option>
                                                    <option value="gender">Gender</option>
                                                    <option value="days">Days</option>
                                                    <option value="time">Time interval</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <canvas id="mem_attendance" class="attendance" width="400" height="80"></canvas>
                                <div ></div>
                            </div>
                        </div> -->
                        <div class="md-card">
                            <div id="status"></div>
                            <div class="md-card-content">
                                <div class="dt_colVis_buttons">
                                </div>
                                <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Forum title</th>
                                            <th>Created By</th>
                                            <th>Date created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $n=0;
                                        $forums = church_forums();
                                        foreach ($forums as $key => $data)
                                            {
                                                $admin  = staff_details($data['admin']);
                                                $n++;
                                                echo '<tr>
                                                <td>'.$n.'</td>
                                                <td>'.$data['forumtitle'].'</td>
                                                <td>'.$admin['name'].'</td>
                                                <td>'.date($standard_date." H:i:s", strtotime($data['addedDate'])).'</td>
                                                <td><a href="forums.php?id='.$data['id'].'"><i class="material-icons">mode_edit</i></a></td>
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
            <div class="modals">            
                <div class="uk-modal" id="add_member_modal" aria-hidden="true" style="display: none; overflow-y: auto;">
                    <div class="uk-modal-dialog" style="top: 339.5px;">
                        <div class="uk-modal-header uk-tile uk-tile-default">
                            <h3 class="d_inline">New Forum</h3>
                        </div>
                        <form id="create_forum_form">
                            <div class="md-input-wrapper">
                                <label>Forum title</label>
                                <input type="text" name="membername" id="forumtitle_input" class="md-input" required="required">
                                <span class="md-input-bar "></span>
                            </div>
                            <div class="md-input-wrapper md-input-filled">
                                <!-- <label>Introduction</label> -->
                                <textarea cols="30" rows="3" id="forum_intro" class="md-input autosized" placeholder="What's the forum about?" style="overflow-x: hidden; word-wrap: break-word;"></textarea>
                                <span class="md-input-bar "></span>
                            </div>
                            <div class="md-input-wrapper md-input-filled">
                                <input type="file" id="input-forum-logo" name="logo" data-height="100" data-height="100" class="dropify" data-allowed-file-extensions="png jpg" required="required"/>
                                <span class="md-input-bar "></span>
                            </div>
                        </form>
                        <div id="addStatus" class="card mt-3" style="margin-top:20px"></div>

                        <div class="uk-modal-footer uk-text-right act-dialog" data-role='init'>
                            <button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
                            <button class="md-btn md-btn-success pull-right" id="create_forum_btn">CREATE</button>
                        </div>

                        <div class="uk-modal-footer uk-text-right act-dialog display-none" data-role='done'>
                            <button type="button" class="md-btn md-btn-flat uk-modal-close"><img src="assets/img/rot_loader.gif" style="max-height: 50px"> Creating a forum...</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="md-fab-wrapper">
            <button class="md-fab md-fab-primary" href="javascript:void(0)" data-uk-modal="{target:'#add_member_modal'}"><i class="material-icons">add</i></button>
        </div>
    <?php } ?>
    <div id="firebase"></div>
    

    <!-- <div class="md-fab-wrapper md-fab-speed-dial-horizontal">
        <a class="md-fab md-fab-primary" href="javascript:void(0)"><i class="material-icons">add</i><i class="material-icons md-fab-action-close" style="display:none"></i></a>
        <div class="md-fab-wrapper-small">
            <button class="md-fab md-fab-small md-fab-warning d_inline" href="javascript:void(0)" data-uk-modal="{target:'#add_member_modal'}"><i class="material-icons">person_add</i></button>
            <a class="md-fab md-fab-small md-fab-danger d_inline" href="javascript:void(0)" data-uk-modal="{target:'#modal_upload_members'}"><i class="material-icons">file_upload</i></a>
            <a class="md-fab md-fab-small md-fab-success d_inline" href="javascript:void(0)" data-uk-modal="{target:'#head_counts_modal'}"><i class="material-icons">account_circle</i></a>
        </div>
    </div> -->

    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery.js"></script>

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


    <!-- Dropify -->
    <script src="bower_components/dropify/dist/js/dropify.min.js"></script>

    <script type="text/javascript" src="js/Chart.min.js"></script>

    <!-- file input -->
    <script src="assets/js/custom/uikit_fileinput.min.js"></script>

    <!--  user edit functions -->
    <script src="assets/js/pages/page_user_edit.min.js"></script>

    <!-- Firebase -->
    <script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>
    <script type="text/javascript">
        var messagesRef = new Firebase('https://learnbase-baa6d.firebaseio.com/forumChats');
        var forumsRef = new Firebase('https://learnbase-baa6d.firebaseio.com/forums');

        var forums_member_count = 0,  n=0, forums_member = {};

        messagesRef.limitToLast(10).on('child_added', function (snapshot) {
            var data = snapshot.val();
            fid = data.forumId
            
            if(forums_member[fid]){
                forums_member[fid] = forums_member[fid]+1;

            }else{
                forums_member_count += 1             
                forums_member[fid] = 1;
            }

            $("#user_count").html(forums_member_count)
        });
    </script>

    <script src="js/uploadFile.js"></script>
    <script type="text/javascript">
        var churchID  = <?php echo $churchID; ?>;
        $('.dropify#input-forum-logo').dropify();
        $(".selectize").selectize();

        $("#delete_forum_btn").on('click', function(){
            //when the foru, is to be deleted
            forum_id = $(this).data('forum');

            //ask for confirmation
            UIkit.modal.confirm("Do you want to archive this forum?", function(){
                // will be executed on confirm.
                $.post('api/index.php', {action:'archive_forum', forum:forum_id, user:<?php echo $userId; ?>}, function(data){
                    if(typeof(data) != 'object'){
                        ret = JSON.parse();
                    }else{
                        ret = data;
                    }
                    location = 'forums.php';
                })
            })
        })

        $("#activate_forum_btn").on('click', function(){
            //when the foru, is to be deleted
            forum_id = $(this).data('forum');

            //ask for confirmation
            UIkit.modal.confirm("Do you want to Re-Activate this forum?", function(){
                // will be executed on confirm.
                $.post('api/index.php', {action:'activate_forum', forum:forum_id, user:<?php echo $userId; ?>}, function(data){
                    if(typeof(data) != 'object'){
                        ret = JSON.parse();
                    }else{
                        ret = data;
                    }

                    location.reload();
                })
            })
        })

        function log(data){
            console.log(data)
        }

        $("#create_forum_btn").on('click', function(e){
            e.preventDefault();
            //add individual user
            title = $("#forumtitle_input").val();
            intro = $("#forum_intro").val();
            logo =  document.querySelector("#input-forum-logo").files[0];

            if(title && intro && logo){
                //Marking the progress
                //Marking the sending process
                $("#add_member_modal .act-dialog[data-role=init]").hide();
                $("#add_member_modal .act-dialog[data-role=done]").removeClass('display-none');

                var formdata = new FormData();
                fields = {action:'create_forum', title:title, intro:intro, admin:<?php echo $userId; ?>, logo:logo};

                for (var prop in fields) {
                    formdata.append(prop, fields[prop]);
                }
                var ajax = new XMLHttpRequest();

                ajax.addEventListener("load", function(){
                    response = this.responseText;
                    try{
                        ret = JSON.parse(response);
                        if(ret.status){
                            //User done
                            //create successfully(Giving notification and closing the modal);
                            forumsRef.push({forumSubtitle:intro, forumTitle:title, forumlogo:ret.data.logo, forumId:ret.data.forumId.toString()});   
                            $("#addStatus").html("<p class='uk-text-success'>Forum added successfully!</p>");
                            
                            setTimeout(function(){
                                UIkit.modal($("#add_member_modal")).hide();
                                location.reload();
                            }, 3000);
                        }
                    }catch(e){
                        alert("error"+e)
                    }

                }, false);

                ajax.open("POST", "api/index.php");
                ajax.send(formdata);
            }else{
                alert("Provide user details")
            }

        })
    </script>

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