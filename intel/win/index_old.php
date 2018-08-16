<?php
    ob_start();
    include_once '../conn.php';
    include_once '../functions.php';
    require 'auth.php';
    require 'core/location.php';
    require 'core/cooperative.php';
    require 'core/crop.php';
    require 'core/product.php';
    require 'core/purchasingOrder.php';
    require 'core/message.php';
    require 'core/institution.php';
    require 'core/seller.php';
    $user_name = $user_data['name']??"";
    $current_user_pic = $user_data['profile_picture'];
    $standard_date = "d/m/Y";
    $standard_time = $standard_date." H:i";

    $current_cooperative = $user_data['cooperativeId'];

    //keeping coop_name for usage
    $current_cooperative_data = $Cooperative->get_cooperative($current_cooperative);
    $current_cooperative_name = $current_coop_name = $current_cooperative_data['name'];
    $current_coop_image = $current_cooperative_data['cooperativeImage'];
?>
<!doctype html>
 <html lang="en"> 
<head>
    <?php
        $title = "Welcome $user_name";
        include_once "modules/head.php";
    ?>
</head>
<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <?php

        //getting requested pages
        //checking the page
        $reqURI = trim($_SERVER['REQUEST_URI']??"", "/");


        //REMOVING GET_VARIABLES
        if($pos  = strripos($reqURI, "?")){
            //here get are sent
            $reqURI = substr_replace($reqURI, '', $pos);
        }

        $req_parts = explode("/", $reqURI);


        //remove all the parts before admin/
        $projectname = 'win';
        $s = array_search($projectname, $req_parts);
        for ($n=$s; $n >= 0; $n--) {
            unset($req_parts[$n]);
        }

        $req_parts = array_values($req_parts);

        $current_page_action = $base_page = $req_parts[0]??'home'; #base required page
    ?>
    <!-- main header -->
    <?php include 'modules/menu.php'; ?>
    <!-- main header end -->
    <!-- main sidebar -->
    <?php include 'modules/sidebar.php'; ?>
    <div id="page_content">
        <div id="page_content_inner">

            <!-- statistics (small charts) -->
            <!-- circular charts -->
            <!-- summary report panel -->
            
            <!-- end of summary report panel -->
            <!-- tasks -->
            <?php

                //Check requested route existence
                $pageFile = "pages/$base_page.php";
                if(file_exists($pageFile)){
                    include $pageFile;
                }else{
                    include "pages/404.php";
                }
            ?>
        </div>
    </div>

    <!-- secondary sidebar -->
    <?php include 'modules/configPanel.php'; ?>
    <!-- secondary sidebar end -->

    <script type="text/javascript">
        // variables declaration
        const current_cooperative = "<?php echo $user_data['cooperativeId']; ?>";
        const current_user = "<?php echo $user; ?>";
        const api_link = "../api/index.php";
    </script>
</body>
</html>