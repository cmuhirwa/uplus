<?php
	include "../db.php";
	include "../class.message.php";
	include '../functions.php';
	include '../mail.php';

	//return JSON Content-Type
	header('Content-Type: application/json');

	$Message = new broadcast();
	$request = array_merge($_POST, $_GET); //$_GET for devt nd $_POST for production
		$response = array();
	
	$action = $request['action']??"";

	if($action == "export_members")
	{
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
	}else if($action ==  'create_church'){
		//creating church
		$name = $request['name']??"";
		$location = $request['location']??"";

		$pic = $_FILES['picture'];
		$logo = $_FILES['logo'];

		//checking file image
		$ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION)); //extensin
		$logo_ext = strtolower(pathinfo($logo['name'], PATHINFO_EXTENSION)); //extensin


		if( ($ext == 'png' || $ext == 'jpg') && ($logo_ext == 'png' || $logo_ext == 'jpg') ){
			$filename = "gallery/church/$name"."_".time().".$ext";
			$logo_filename = "gallery/church/$name"."_logo_".time().".$ext";

			if(move_uploaded_file($pic['tmp_name'], "../$filename") && move_uploaded_file($logo['tmp_name'], "../$logo_filename")){
				//Creating church
				$sql = "INSERT INTO church(name, profile_picture, logo) VALUES(\"$name\", \"$filename\", \"$logo_filename\") ";
				$insert = $conn->query($sql);

				if($insert){
					$response = array('status'=>true, 'msg'=>"Created");
				}else{
					$response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
				}

				$response = array('status'=>true, 'msg'=>"Success", 'churchid'=>$conn->insert_id);

			}else $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again".json_encode($_FILES));

		}else{
			//We dont recognize this file format
			$response = array('status'=>false, 'msg'=>"Please upload an image png and jpg not $ext");
		}
	}else if($action == "list_churches"){
		$churches = getChurchList();
		$response = array();
		for ($n=0; $n<count($churches); $n++) {
			$church = $churches[$n];
			$response[] = array(
				'id'=>(int)$church['id'],
				'name'=>$church['name'],
				'logo'=>$church['logo']??"",
				'profile_picture'=>$church['profile_picture']??"",
				'smsName'=>$church['smsName'],
			);
		}
	}else if($action == "listChurches"){
		//listing the church branches
		$user = $request['userId']??"";
		if($user){
			$query = $conn->query("SELECT B.*, CONCAT(C.smsName, ' - ', B.name) as churchName, C.name as cname, B.name as bname FROM branches as B JOIN church as C ON C.id = B.church") or trigger_error($conn->error);

			$branches = array();

			while ($data = $query->fetch_assoc()) {
				//checking if the user joined
				$branchid = $data['id'];
				$exiq = $conn->query("SELECT * FROM church_members WHERE userCode = \"$user\" AND branchid = \"$branchid\" AND archived = 'no' ORDER BY createdDate DESC LIMIT 1") or trigger_error($conn->error);

				$branches[] = array(
					'churchName'=>$data['churchName'],
					'churchId'=>$branchid,
					'church'=>$data['cname'],
					'branch'=>$data['bname'],
					'churchImage'=>$data['profile_picture'],
					'joined'=>$exiq->num_rows==1?"Yes":"No",
				);
			}
			$response = $branches;
		}else{
			$response = array('status'=>false, 'msg'=>'provide user');
		}		
	}else if($action == "getChurch"){
		//check if the user joined church and returns the branch church information
		//branch is like church here
		$church = $request['churchId']??"";
		$user = $request['userId']??"";

		if($church){
			$query = $conn->query("SELECT B.*, CONCAT(B.name, ' ', C.name) as churchName, C.name as cname, B.name as bname, (SELECT COUNT(*) FROM church_members WHERE userCode = \"$user\" AND branchid = B.id AND archived = 'no' ORDER BY createdDate DESC LIMIT 1) as joined FROM branches as B JOIN church as C ON C.id = B.church WHERE B.id = \"$church\" LIMIT 1 ") or trigger_error($conn->error);

			$data = $query->fetch_assoc();
			$branches[] = array(
					'churchName'=>$data['churchName'],
					'churchId'=>$church,
					'church'=>$data['cname'],
					'branch'=>$data['bname'],
					'churchImage'=>$data['profile_picture'],
					'joined'=>$exiq->num_rows==1?"Yes":"No",
				);
			$response = $branches;
		}else{
			$response = "Fail";
		}		
	}else if($action == "get_groups" || $action == "getGroups"){
		//Elisaa want random groupps
		//give the church ID u want groups of
		$church = $request['church']??1; //1 is for Elisaa 's testing'
		$query = $conn->query("SELECT g.* FROM groups as g JOIN branches  as b ON g.branchid = b.id WHERE b.church =\"$church\"") or trigger_error($conn->error);

		$groups = array();
		while ($data = $query->fetch_assoc()) {
			$groups[] = array(
				'groupId'=>$data['id'],
				'groupName'=>$data['name'],
				// 'representative'=>$data['representative'],
				// 'branchid'=>$data['branchid'],
				// 'type'=>$data['type'],
				// 'location'=>$data['location'],
				// 'mapLocation'=>$data['mapLocation'],
				'groupImage'=>$data['profile_picture'],
			);
		}

		$response = $groups;
	}else if($action == "add_member"){
		$church_id = $request['church'];
		$name = $request['name']??"";
		$phone = $request['phone']??0;
		$gender = $request['gender']??'male';
		$email = $request['email']??"";
		$address = $request['address']??"";
		$platform = $request['platform']??"";
		$branch = $request['branch']??"";
		$type = $request['type']??"";
		$createdBy = $request['createdBy']??1; //who created the user


		$date = date("Y-m-d H:i:s");


		if(!empty($name) && !empty($branch) && !empty($type)){
			//inserting a user
			$userId = add_user($name, $phone, $email, $gender, $address);
			if($userId){
				//attach the user to church
				if(add_church_member($userId, $type, $branch, $platform, $createdBy)){
					$response = "done";
				}else{
					$response = "fail";
				}
			}else{
				$response = "fail";
			}
		}
	}else if($action == "joinChurch"){		
		//when the user want to join church
		$userId = $request['user']??'';
		$branch = $request['branch']??"";
		$memberType = $request['member_type']??"visitor";

		//meta
		$platform = $request['platform']??'app'; //where was the activity performed
		if($platform == 'app'){
			//app dont send who did the activity
			$createdBy = $userId;
		}else{
			$createdBy = $request['createdBy']??"";            
		}

		//checking if the user was already the active church member
		$query = $conn->query("SELECT * FROM church_members WHERE userCode = '$userId' AND branchid = '$branch' AND archived = 'no' ") or trigger_error($conn->error);
		if($query->num_rows == 0){
			$sql = "INSERT INTO church_members(userCode, branchid, joinedByPlatform, type, createdBy) VALUES('$userId', '$branch', \"$platform\", \"$memberType\", '$createdBy')";
			$query = $conn->query($sql) or trigger_error($conn->error);

			if($conn->insert_id){
				$response = "Done";
			}else{
				$response = "Failed, $conn->error";				
			}
		}else{
			$response = "Done";
		}
	}else if($action == 'donate'){
		//api for donation
		$amount = $request['amount']??"";
		$account = $request['account']??"";
		$userId = $request['userId']??"";
		$method = $request['method']??"mtn";


		if($userId && $account && $method){
			//getting user's church
			$userData = user_details($userId);

			$userChurch = $userData['church'];

			//inserting the donation
			$query = $conn->query("INSERT INTO donations(member, church, amount, account, source) VALUES(\"$userId\", \"$userChurch\", \"$amount\", \"$account\", \"$method\") ");
			if($query){
				$response = 'Pending';
			}else{
				$response = 'Failed';
			}
		}else{
			$response = 'Failed';
		}
	}else if($action == 'pledge'){
		//api for donation
		$amount = $request['amount']??"";
		$account = $request['account']??"";
		$userId = $request['userId']??"";
		$basket = $request['basketId']??"";
		$method = $request['method']??"mtn";


		if($userId && $account && $method){
			//getting user's church
			$userData = user_details($userId);

			$userChurch = $userData['church'];

			//inserting the donation
			$query = $conn->query("INSERT INTO donations(member, church, service, amount, account, source) VALUES(\"$userId\", \"$userChurch\", \"$basket\", \"$amount\", \"$account\", \"$method\") ");
			if($query){
				$response = 'Done';
			}else{
				$response = 'Failed';
			}
		}else{
			$response = 'Failed';
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
	}else if($action == 'sendSms'){
			//Getting data on field
			$phone = $request['phone']??0;
			$message = $request['message']??"";
			$token = $request['token']??"";

			if($token == 'ireebe'){
				if($phone && $message){
					//Sending the message
					$sms = sendsms($phone, $message, '', 'Ireebe');
					$response = 'done';
				}else{
					$response = 'Failed';
				}
			}else{
				$response = 'Failed';
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


						//Creating group i uplus
						$sql = "INSERT INTO uplus.groups(groupName, location, mapLocation, groupImage, adminId) VALUES(\"$name\", \"$location\", \"$mapLocation\", \"$filename\", \"$rep\" )";
						$query = $conn->query($sql) or die("Error $conn->error");
						if($query){
							//get group ID
							$groupId = $conn->insert_id;
							$cq = $conn->query("INSERT INTO churches.church_groups(groupCode, branchCode, type) VALUES($groupId, \"$church\", \"$type\") ") or trigger_error($conn->error);

						}


						$sql = "INSERT INTO groups(name, branchId, representative, type, location, mapLocation, profile_picture) VALUES(\"$name\", \"$church\", $rep, \"$type\", \"$location\", \"$mapLocation\", \"$filename\" )";
						// echo "$sql\n";
						
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
		$userId = $request['userId']??1; //user deleting this group

		$sql = "UPDATE church_groups SET archived = 'yes', archivedBy = $userId, archievedDate = NOW() WHERE groupCode = \"$groupid\" ";
		$conn->query($sql) or trigger_error($conn->error);
		$conn->query("UPDATE uplus.groups SET archived = 'yes', archivedBy = $userId, archievedDate = NOW() WHERE id = \"$groupid\" ");
		$conn->query($sql) or trigger_error($conn->error);
	}else if($action == 'invoice'){        
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
		$church = $request['churchId']??"";
		$query = $conn->query("SELECT * FROM service WHERE church = \"$church\" ");
		if($query){
			$baskets = array();
			while ($data = $query->fetch_assoc()) {
				$baskets[]  = array(
					'id'=> $data['id'],
					'name'=> $data['name'],
					'description'=> $data['description'],
				);
			}
			$response = $baskets;
		}else{
		  $response = "Failed"; 
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
		$query = $conn->query("SELECT * FROM forums")or die(mysqli_error($conn));
		$forums = array();
		while ($data = mysqli_fetch_array($query))
		{
			$forums[] = array(
				"forumId"          => $data['id'],
				"forumTitle"        => $data['forumtitle'],
				"forumSubtitle"     => $data['intro'],
				"forumlogo"         => $data['logo']
			);
		}
		$response = $forums;
	}else if($action == 'list_feeds'){
		//listing FEEDS - all feeds
		//TODO: pagination
		$query = $conn->query("SELECT * FROM posts ORDER BY postedDate")or die(mysqli_error($conn));
		$posts = array();
		while ($data = mysqli_fetch_array($query))
		{
			$posts[] = $data;
		}
		$response = $posts;
	}
	else if($action == 'listPodcasts')
	{
		$church = $request['churchId'];
		$sql = "SELECT * FROM posts WHERE type = 'podcast' AND targetChurch = '$church' AND archived = 'no' ORDER BY postedDate";
		$query = $conn->query($sql) or die(mysqli_error($conn));
		$posts = array();
		while ($data = mysqli_fetch_array($query))
		{
			// print_r($data['attachment']);
			// echo "<br />";
			// continue;
			$posts[] = array(
				'title'=>$data['title']??"",
				'podcastId'=>$data['id'],
				'podcastDescription'=>$data['content']??"",
				'podcastThumb'=>'gallery/podcasts/sermon.jpg',
				'podcastMediaLink'=> str_ireplace(" ", "_", json_decode($data['attachment'], true)[0]??""),
			);
		}
		
		$response = $posts;
	}
	else if($action ==  'create_forum'){
		//creating forum
		$title = $request['title']??"";
		$admin = $request['admin']??"";
		$intro = $request['intro']??"";

		$pic = $_FILES['logo'];

		//checking file image
		$ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION)); //extensin


		if( ($ext == 'png' || $ext == 'jpg') ){
			$filename = "gallery/church/$title"."_".time().".$ext";

			if(move_uploaded_file($pic['tmp_name'], "../$filename")){
				//Creating forum
				$sql = "INSERT INTO forums(forumtitle, admin, intro, logo) VALUES(\"$title\", \"$admin\", \"$intro\", \"$filename\") ";
				$insert = $conn->query($sql);

				if($insert){
					$response = array('status'=>true, 'msg'=>"Created", 'data'=>array('id'=>$conn->insert_id));
				}else{
					$response = array('status'=>false, 'msg'=>"Can't create: $conn->error");
				}

				$response = array('status'=>true, 'msg'=>"Success", 'data'=>array('forumId'=>$conn->insert_id, 'logo'=>$filename));

			}else $response = array('status'=>false, 'msg'=>"Error keeping file on server\nPlease try again".json_encode($_FILES));

		}else{
			//We dont recognize this file format
			$response = array('status'=>false, 'msg'=>"Please upload an image png and jpg not $ext");
		}
	}else if($action ==  'create_post'){
		//post feeds
		$userId = $request['user']??"";
		$post_content = $request['content']??"";
		$title = $request['title']??"";

		//if there targeted church
		$target_audience = $request['church']??1;

		//if there targeted forum
		$target_forum = $request['postForumId']??'';

		//type of the post
		$type = $request['type']??"";

		if($type && !$target_audience){
			$userData = staff_details($target_audience);
			$target_audience = $userData['church'];
		}

		//attachments link
		$attachments = $conn->real_escape_string($request['attachments'])??"[]";

		//the type of person who posted - admin or member if empty it'll be elisaa app
		$userType = $request['userType']??'member';

		//platform which the user posted with
		$platform = $request['platform']??"app";

		if($userType == 'admin'){
			$sql = "INSERT INTO posts(title, content, postedBy, type, postChurchAdmin, attachment, targetChurch, targetForum, platform) VALUES(\"$title\", \"$post_content\", 'admin', \"$type\", \"$userId\", \"$attachments\", \"$target_audience\", \"$target_forum\", \"$platform\") ";
			
		}else{
			$sql = "INSERT INTO posts(title, content, postedBy, type,  postMemberId, attachment, targetChurch, targetForum, platform) VALUES(\"$title\", \"$post_content\", 'member', \"$type\", \"$userId\", \"$attachments\", \"$target_audience\", \"$target_forum\", \"$platform\") ";
		}

		$query = $conn->query($sql);


		if($query){
			$response = array('status'=>true, 'msg'=>array('postId'=>$conn->insert_id));
		}else{
			$response = array('status'=>false, 'msg'=>"Error $conn->error");   
		}
	}else if($action == 'delete_feed'){
		//deleting the feed
		$feed = $request['feed']??"";
		$user = $request['user']??""; //who deleted this feed

		if($user && $feed){
			$query = $conn->query("UPDATE posts SET archived = 'yes', archivedDate = NOW(), archivedBy = \"$user\", updatedDate = NOW(), updatedBy = \"$user\" WHERE id = \"$feed\"  ") or trigger_error($conn->error);
			$response = array('status'=>true);
		}else{
			$response = array('status'=>false, 'msg'=>"Provide details");
		}

	}else if($action == "upload_feed_attachment"){
		//uploading the file for attachments
		$attachment = $_FILES['file'];
		$sent_file_name = $attachment['name'];
		$ext = strtolower(pathinfo($sent_file_name, PATHINFO_EXTENSION)); //extension

		$filename = "gallery/feeds/".substr(str_ireplace(" ", "_", $sent_file_name), 0, -4)."_".time().".".$ext;

		$allowed_extensions = array('preventerrorsguys_dont remove please', 'jpg', 'png', 'mp3', 'aac', 'mp4');

		if(array_search($ext, $allowed_extensions)){
			//we can now upload
			move_uploaded_file($attachment['tmp_name'], "../".$filename);
			$response = array('status'=>true, 'msg'=>$filename);
		}else{
			$response = array('status'=>false, 'msg'=>"Invalid file type");
		}
	}else if($action == 'archive_forum' ){
		$forum = $request['forum']??"";
		$user = $request['user']??""; //someone who is deleting this forum
		if($forum && $user){
			$query = $conn->query("UPDATE forums SET  archived = 'yes', archiveDate = NOW(), archivedBy = \"$user\" WHERE id = \"$forum\" ");
			if($query){
				$response = array('status'=>true);
			}else{
				$response = array('status'=>false, 'msg'=>"$conn->error");
			}
		}
	}
	else if($action == 'activate_forum' ){
		// die('deprecated');
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
