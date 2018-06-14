<?php
	include "../db.php";
	include "../class.message.php";
    include '../functions.php';
    include '../../mail.php';

    //return JSON Content-Type
    header('Content-Type: application/json');

    //hostname for file referencing
    $hostname = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";

	$Message = new broadcast();
	$request = array_merge($_POST, $_GET); //$_GET for devt nd $_POST for production
    	$response = array();
	
	$action = $request['action']??"";

	if($action == "export_members"){
        $church_id = $request['church'];
        // $branch = $request['branch'];
        $user = $request['user'];


        //checking file
        if($_FILES['members-file']['size']>0){

        $target_dir = "uploads/churchmembers/";
        $tmp_file = basename($_FILES["members-file"]['tmp_name']);
        $target_file = $target_dir.basename($_FILES["members-file"]['name']);
        $uploadOk = 1;
        $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        move_uploaded_file(basename($_FILES["members-file"]['tmp_name']), $target_file);
        }else{
            echo "No file uploaded";
        }       
    }else if($action ==  'invite_users'){
        //inviting user to the forum
        //getting app users to be invited
        $users = $request['users']??array();

        //getting email users to be invited
        $emails = $request['emails']??array();

        $invitedBy = $request['invitedBy'];

        // $message
        $message = $request['message']??"";


        //logging the invite
        for($n=0; $n<count($users); $n++) {
            $user = $users[$n];
            $conn->query("INSERT INTO foruminvites(userCode, message, invitedBy) VALUES($user, \"$message\", $invitedBy)") or trigger_error($conn->error);
            if($conn->insert_id){
                $response = true;
            }else{
                $response = false;                
            }
            //getting the user email
            $userdata = user_details($user);
            $Mail->send($userdata['email'], 'Forum invitation', $message);

        }

        //logging the invite
        for($n=0; $n<count($emails); $n++) {
            // echo "string";
            $user = $emails[$n];
            $conn->query("INSERT INTO foruminvites(email, message, invitedBy) VALUES(\"$user\", \"$message\", $invitedBy)") or trigger_error($conn->error);

            if($conn->insert_id){
                $response = true;
            }else{
                $response = false;                
            }

            $Mail->send($user, 'Forum invitation', $message);
        }
        

        $name = $request['name']??"";
        $location = $request['location']??"";

    }else if($action == "list_churches"){
        $response = getChurchList();
    }else if($action == "get_groups"){
        //Elisaa want random groupps
        //give the church ID u want groups of
        $church = $request['church']??1; //1 is for Elisaa 's testing'
        $query = $conn->query("SELECT * FROM groups as g JOIN branches  as b ON g.branchid = b.id WHERE b.church =\"$church\"") or trigger_error($conn->error);

        $groups = array();
        while ($data = $query->fetch_assoc()) {
            $groups[] = $data;
        }

        $response = array('status'=>true, 'data'=>$groups);

    }else if($action == "add_member"){
        $church_id = $request['church'];
        $name = $request['name']??"";
        $phone = $request['phone']??0;
        $email = $request['email']??"";
        $address = $request['address']??"";
        $branch = $request['branch']??"";
        $type = $request['type']??"";

        $date = date("Y-m-d H:i:s");


        if(!empty($name) && !empty($branch) && !empty($type)){
            $sql = "INSERT INTO members(name, phone, email, branchid, address, type, createdDate) VALUES (\"$name\", \"$phone\", \"$email\", \"$branch\", \"$address\", \"$type\", '$date')";
            $query = $db->query($sql);
            
            if($query){
                $response = array('status'=>true);
            }else{
                $response = array('status'=>false, 'msg'=>"Error $db->error");
            }
        }else{
            $response = array('status'=>false, 'msg'=>"Please provide info to create church member");
        }
	}else if($action == 'buy'){
        $phonenumber = $request['phone'];
        $count = $request['count'];
        $church  = $request['church'];
        $cost = $count*13;

        $query = $db->query("INSERT INTO transactions(church, phone, nsms, cost, mode, status) VALUES(\"$church\", \"$phonenumber\", \"$count\", '$cost', 'mobile', 'pending')") or die("Can't log purchase ".$db->error);



        //INcreasing the smsbalance
        $db->query("UPDATE smsbalance SET balance = balance+$count WHERE church = '$church' ") or die("error updating church ".$db->error);


        echo json_encode(array('status'=>true, 'balance'=>$Message->churchbalance($church)));
    }else if($action == 'invoice'){
    	//Invoice for transaction
    	$transaction = $request['id']??"";
    	$query = $db->query("SELECT *, status as transaction_status FROM transactions WHERE id = \"$transaction\" LIMIT 1 ") or die("Can't get transaction details ".$db->conn);
    	$data = $query->fetch_assoc();
    	$data['status'] =1;
        $response = $data;
    }else if($action == 'send_sms'){
            //Getting data on field
            $phone = $request['phone']??0;
            $message = $request['message']??"";

            if($phone && $message){
                //Sending the message
                $sms = sendsms($phone, $message);
            }
    }else if($action == 'schedule_sms'){
            //Getting data on field
            $phone = $request['phone']??0;
            $message = $request['message']??"";

            if($phone && $message){
                //Sending the message
                $sms = sendsms($phone, $message);
            }
    }else if($action == 'create_group'){
    	//api route for group creatinon
    	$name = $request['name'];
    	$type = $request['type'];
    	$location = $request['location'];
    	$rep = $request['rep'];
    	$church = $request['church'];

        $mapLocation = $request['mapLocation']??"-1.9912405,30.096438000000035";

        if(!empty($_FILES)){
            $pic = $_FILES['profile_picture'];
            if($pic['error'] == 0){
                //Image has no error
                //checking if it's image
                $ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION));
                if($ext == 'png' || $ext == 'jpg'){
                    //moving file to disk
                    $filename = "gallery/groups/$name"."_".time().".$ext";
                    if(move_uploaded_file($pic['tmp_name'], "../$filename")){
                        //Updating database


                        //Creating group
                        $sql = "INSERT INTO groups(name, branchId, representative, type, location, mapLocation, profile_picture) VALUES(\"$name\", \"$church\", $rep, \"$type\", \"$location\", \"$mapLocation\", \"$filename\" )";
                        // echo "$sql\n";
                        $conn->query($sql) or die("Error $conn->error");
                        $response = array('status'=>true, 'msg'=>"Success", 'groupid'=>$conn->insert_id);

                    }else $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again");
                }else{
                    //We dont recognize this file format
                    $response = array('status'=>false, 'msg'=>"The file uploaded seems to be not an image, $ext only png and jpeg are allowed\nPlease try again");
                }
            }else{
                $response = array('status'=>false, 'msg'=>"Error uploading group image\nPlease try again");
            }
        }    	
    }else if($action == "update_group"){
        //updating group
        $name = $request['name'];
        $type = $request['type'];
        $location = $request['location'];
        $rep = $request['representative'];
        $group = $request['group'];
        
        //query
        $query = $db->query("UPDATE groups SET name = \"$name\", representative = \"$rep\", type = \"$type\", location = \"$location\" WHERE id = \"$group\" ");
        if($query){
            $response = array('status'=>true, 'msg'=>"Updated group Successfully");
        }else{
            $response = array('status'=>false, 'msg'=>"Error updating group $db->error");

        }
    }else if($action == 'addmembers'){
        //Adding members to group
        $group = $request['group'];
        $members = $request['members'];

        //Adding group members
        $response = array('added'=>array(), 'not_added'=>array());
        for($n=0; $n<count($members); $n++){
            $member = $members[$n];
            //testing if user is already member of group
            $sql = "SELECT * FROM group_members WHERE member = \"$member\" AND groupid = \"$group\" ";
            $test = $conn->query($sql) or die("$conn->error");
            if($test->num_rows<1){
                //add user to group
                $query = $conn->query("INSERT INTO group_members(member, groupid) VALUES(\"$member\", \"$group\") ");
                if($query){
                    $response['added'] = array_merge($response['added'], array($member));
                }else{
                    //Not added
                    $response['not_added'] = array_merge($response['not_added'], array("$member"=>$conn->error));
                }                
            }else{
                // user already a member
                 $response['not_added'][] = array("$member"=>"user already a member");
            }
        }
    }else if($action == 'remove_users'){
        //removing user from group
        $members = $request['members'];
        $group = $request['group'];

        //checking if it's one user or many user
        $members = is_array($members)?$members:array($members);

        // looping to remove_users
        for($n=0; $n<count($members); $n++){
            $sql = "DELETE FROM group_members WHERE member = \"$members[$n]\" AND groupid = \"$group\" ";
            $conn->query($sql) or die("Can't remove user from group $conn->error");            
        }
    }else if($action == 'delete_group'){
        //Deleting group
        $groupid = $request['group']??0;

        $conn->query("DELETE FROM groups WHERE id = \"$groupid\" ");
    }else if($action == "create_branch"){
        //creating branch
        $name  = $request['name']??"";
        $church = $request['church']??"";
        $location = $request['location']??"";
        $representative = $request['representative']??"";

        if(!empty($name) && !empty($church) && !empty($location) && !empty($representative) ){
            //checking file
            if(!empty($_FILES)){
                var_dump($_FILES);
                $pic = $_FILES['picture'];
                if($pic['error'] == 0){
                    //Image has no error
                    //checking if it's image
                    $ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION));
                    if($ext == 'png' || $ext == 'jpg'){
                        //moving file to disk
                        $filename = "gallery/branches/$name"."_".time().".$ext";
                        if(move_uploaded_file($pic['tmp_name'], "../$filename")){
                            //Creating group

                            $insert = $conn->query("INSERT INTO branches(name, location, repId, church, profile_picture) VALUES(\"$name\", \"$location\", \"$representative\", \"$church\", \"$filename\") ");

                            if($insert){
                                $response = array('status'=>true, 'msg'=>"Created");
                            }else{
                                $response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
                            }

                            $response = array('status'=>true, 'msg'=>"Success");

                        }else $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again");
                    }else{
                        //We dont recognize this file format
                        $response = array('status'=>false, 'msg'=>"The file uploaded seems to be not an image, $ext only png and jpeg are allowed\nPlease try again");
                    }
                }else{
                    $response = array('status'=>false, 'msg'=>"Error uploading group image\nPlease try again");
                }
            }else{
                //here we can create branch in db
                $insert = $conn->query("INSERT INTO branches(name, location, repId, church) VALUES(\"$name\", \"$location\", \"$representative\", \"$church\") ");

                if($insert){
                    $response = array('status'=>true, 'msg'=>"Created");
                }else{
                    $response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
                }
            }

            

        }else{
            $response = array('status'=>false, 'message'=>'fillin all the details');
        }
    }else if($action == "add_event"){
        //creating church event
        $name  = $request['name']??"";
        $church = $request['church']??"";
        $description = $request['description']??"";
        $event_start = $request['event_start']??"";
        $event_end = $request['event_end']??"";

        if(!empty($name) && !empty($church) && !empty($description) && !empty($event_start) ){
            //checking file
            if(!empty($_FILES)){
                $pic = $_FILES['image'];
                if($pic['error'] == 0){
                    //Image has no error
                    //checking if it's image
                    $ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION));
                    if($ext == 'png' || $ext == 'jpg'){
                        //moving file to disk
                        $filename = "gallery/branches/$name"."_".time().".$ext";
                        if(move_uploaded_file($pic['tmp_name'], "../$filename")){
                            //adding the event

                            $insert = $conn->query("INSERT INTO event(eventName, eventStart, eventEnd, church, eventDescription, picture) VALUES(\"$name\", \"$event_start\", \"$event_end\", \"$church\", \"$description\", \"$filename\") ");

                            if($insert){
                                $response = array('status'=>true, 'msg'=>"Created");
                            }else{
                                $response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
                            }

                            $response = array('status'=>true, 'msg'=>"Success");

                        }else $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again");
                    }else{
                        //We dont recognize this file format
                        $response = array('status'=>false, 'msg'=>"The file uploaded seems to be not an image, $ext only png and jpeg are allowed\nPlease try again");
                    }
                }else{
                    $response = array('status'=>false, 'msg'=>"Error uploading group image\nPlease try again");
                }
            }else{
                //here we can create branch in db
                $insert = $conn->query("INSERT INTO branches(name, location, repId, church) VALUES(\"$name\", \"$location\", \"$representative\", \"$church\") ");

                if($insert){
                    $response = array('status'=>true, 'msg'=>"Created");
                }else{
                    $response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
                }
            }

            

        }else{
            $response = array('status'=>false, 'message'=>'fillin all the details');
        }
    }else if($action == "add_podcast"){
        //adding podcast
        $name  = $request['name']??"";
        $church = $request['church']??"";
        $intro = $request['intro']??"";

        if(!empty($name) && !empty($church) ){
            //checking file
            if(!empty($_FILES)){
                $pic = $_FILES['file'];
                if($pic['error'] == 0){
                    //file has no error
                    //checking if it's image
                    $ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION));
                    if($ext == 'mp3' || $ext == 'aac' || $ext == 'mp4'){
                        //moving file to disk
                        $filename = "gallery/podcasts/$name"."_".time().".$ext";
                        // $filename = "../api/podcasts/$name"."_".time().".$ext";

                        if(move_uploaded_file($pic['tmp_name'], "../$filename")){
                            //Creating podcast
                            $sql = "INSERT INTO podcasts(name, file, intro, church, status) VALUES(\"$name\", \"$filename\", \"$intro\", \"$church\", 'active') ";
                            $insert = $conn->query($sql);

                            if($insert){
                                $response = array('status'=>true, 'msg'=>"Created");
                            }else{
                                $response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
                            }

                            $response = array('status'=>true, 'msg'=>"Success");

                        }else $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again");
                    }else{
                        //We dont recognize this file format
                        $response = array('status'=>false, 'msg'=>"The file uploaded seems to be not an audio or video(mp4 only) file, $ext only mp3 and aac are allowed\nPlease try again");
                    }
                }else{
                    $response = array('status'=>false, 'msg'=>"Error uploading group image\nPlease try again");
                }
            }else{
                $response = array('status'=>false, 'message'=>'upload the podcast');
            }

            

        }else{
            $response = array('status'=>false, 'message'=>'fillin all the details');
        }
    }else if($action == "delete_podcast"){
        //adding podcast
        $podcast_id = $request['podcast']??"";

        if(!empty($podcast_id) ){
            $q = $conn->query("UPDATE podcasts p SET status = 'archive' WHERE p.id = \"$podcast_id\" ");
            if($q){
                $response = array('status'=>true);
            }else{
                $response = array('status'=>false, 'message'=>"Error $conn->error");
            }
            
        }else{
            $response = array('status'=>false, 'message'=>'fillin all the details');
        }
    }else if($action == "listBaskets"){
        //listing the baskets
        $church = $request['church']??"";
        $query = $conn->query("SELECT * FROM service WHERE church = \"$church\" ");
        if($query){
            $baskets = array();
            while ($data = $query->fetch_assoc()) {
                $baskets[]  = $data;
            }
            $response = array('status'=>true, 'data'=>$baskets);
        }else{
          $response = array('status'=>false, 'msg'=>"Error: $conn->error");  
        }
    }else if($action == "addDonation"){
        //adding donation
        $church = $request['church']??"";
        $branch = $request['branch']??"";
        $service = $request['service']??"";
        $method = $request['method']??"";
        $account = $request['account']??"";
        $member = $request['member']??"";

        $query = $conn->query("INSERT INTO donations(member, church, service, amount, amount, account, source) VALUES(\"$member\", \"$church\", \"$service\", \"$amount\", \"$account\", \"$method\" ");

        if($query){
            $response = array('status'=>true, 'data'=>$baskets);
        }else{
            $response = array('status'=>false, 'msg'=>"Error: $conn->error"); 
        }
    }else if($action == "record_headcount"){
        //head counts recording
        $church = $request['church']??"";
        $service = $request['service']??"";
        $date = $request['date']??"";
        $number = $request['number']??"";
        $branch = $request['branch']??'';

        $date = date_format(date_create($date),"Y-m-d");

        if(!empty($church) && !empty($service) && !empty($date) && !empty($number))
        {
            //Adding in the database
            $query = $conn->query("INSERT INTO attendance(num, service, branch, date) VALUES(\"$number\", \"$service\", \"$branch\", \"$date\") ");
            if($query){
                $response = array('status'=>true);
            }else{
                $response = array('status'=>false, 'msg'=>"Error: $conn->error");
            }
        }else{
            $response = array('status'=>false, 'msg'=>"Provide all the details");
        }
    }
    else if($action == 'list_forums' || $action == 'listForums' )
    {
        //listing forums
        $forums = get_forums();
        var_dump($forums);
        foreach ($forums as $key => $data) {
            $forums[] = array(
                "forumId"          => $data['id'],
                "forumTitle"        => $data['forumtitle'],
                "forumSubtitle"     => $data['intro'],
                "forumlogo"         => $data['logo']
            );
        }
        $response = $forums;
    }else if($action == 'delete_feed'){
        //deleting the feed
        $feed = $request['feed']??"";
        $user = $request['user']??""; //who deleted this feed

        if($user && $feed){
            $sql = "UPDATE feeds SET archivedDate = NOW(), archivedBy = \"$user\", archive = 'yes', updatedDate = NOW(), updatedBy = \"$user\" WHERE id = \"$feed\"";
            $query = $conn->query($sql) or trigger_error($conn->error);
            $response = array('status'=>true);
        }else{
            $response = array('status'=>false, 'msg'=>"Provide details");
        }
    }
    else if($action ==  'create_forum'){
        //creating forum
        $title = $request['title']??"";
        $admin = $request['admin']??"";
        $intro = $request['intro']??"";


        $filename = 'invest/gallery/forum_default.jpg'; //default forum image

        if(!empty($_FILES['logo'])){
            $pic = $_FILES['logo'];

            //checking file image
            $ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION)); //extensin

            if( ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') ){
                $filename = "invest/gallery/$title"."_".time().".$ext";

                if(move_uploaded_file($pic['tmp_name'], "../../../$filename")){
                }else{
                    $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again".json_encode($_FILES));
                } 

            }
        }

        //Creating forum in db
        // $filename = $filename;
        $sql = "INSERT INTO forums(title, createdBy, subtitle, icon) VALUES(\"$title\", \"$admin\", \"$intro\", \"$filename\") ";
        $insert = $conn->query($sql);

        if($insert){
            $response = array('status'=>true, 'msg'=>"Created");
        }else{
            $response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
        }

        
    }else if($action ==  'create_post'){
        //post feeds
        $userId = $request['user']??"";
        $post_content = $request['content']??"";

        //type of the post
        $type = $request['type']??"";

        // title
        $type = $request['title']??"";

        //attachments link
        $attachments = json_decode($request['attachments'], true)??array();

        //the type of person who posted - admin or member if empty it'll be elisaa app
        $userType = $request['userType']??'member';

        //target forum
        $target_audience = $request['targetForum']; 

        $sql = "INSERT INTO feeds(feedContent, createdBy, feedForumId) VALUES(\"$post_content\", \"$userId\", \"$target_audience\")";
        $query = $conn->query($sql);


        if($query){
            $feed_id = $conn->insert_id;
            for($n=0; $n<count($attachments) && is_array($attachments); $n++){
                $att = $attachments[$n];
                $sql = "INSERT INTO investmentimg(imgUrl, investCode) VALUES(\"$att\", $feed_id) ";
                $conn->query($sql) or trigger_error($conn->error);
            }

            $response = array('status'=>true, 'msg'=>array('postId'=>$conn->insert_id));
        }else{
            $response = array('status'=>false, 'msg'=>"Error $conn->error");   
        }
    }else if($action == "upload_feed_attachment"){
        //uploading the file for attachments
        $attachment = $_FILES['file'];
        $sent_file_name = $attachment['name'];
        $ext = strtolower(pathinfo($sent_file_name, PATHINFO_EXTENSION)); //extension

        $filename = "invest/gallery/feeds/".substr($sent_file_name, 0, -4)."_".time().".".$ext;

        $allowed_extensions = array('preventerrorsguys_dont remove please', 'jpg', 'png', 'mp3', 'aac', 'mp4');

        if(array_search($ext, $allowed_extensions)){
            //we can now upload
            move_uploaded_file($attachment['tmp_name'], "../../../".$filename);

            //checking if there is hostname in the filename
            if(strpos($filename, $hostname) <= 1 && strpos($filename, $hostname) !=false ){
                $filename = $hostname.$filename;
            }

            $response = array('status'=>true, 'msg'=>$filename);
        }else{
            $response = array('status'=>false, 'msg'=>"Invalid file type");
        }
    }else if($action == 'archive_forum' ){
        $forum = $request['forum']??"";
        $user = $request['user']??""; //someone who is deleting this forum
        if($forum && $user){
            $query = $conn->query("DELETE FROM `forums` WHERE id='$forum'");
		
            if($query){
                $response = array('status'=>true);
            }else{
                $response = array('status'=>false, 'msg'=>"$conn->error");
            }
        }
    }
    else if($action == 'activate_forum' ){
        die('deprecated');
        $forum = $request['forum']??"";
        $user = $request['user']??""; //someone who is deleting this forum
        if($forum && $user){
            $query = $conn->query("UPDATE forums SET status = 'active' WHERE id = \"$forum\" ");
            if($query){
                $response = array('status'=>true);
            }else{
                $response = array('status'=>false, 'msg'=>"$conn->error");
            }
        }
    }else{
    	$response = array('status'=>false, 'msg'=>"Provide action - $action");

    }

    echo json_encode($response);
    flush();
    die();
?>
