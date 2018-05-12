<?php
		include_once("db.php");

		$standard_date = "d F Y";

		function getUser(){
				global $conn;
				if (isset($_SESSION['loginusername'])) {
						$loginusername = $_SESSION['loginusername'];
						$loginpassword = $_SESSION['loginpassword'];
						$selectid = $conn ->query("SELECT * FROM users WHERE loginName='$loginusername' AND loginpsw='$loginpassword'");
						$fetchid = mysqli_fetch_array($selectid);
						$userId = $fetchid['Id'];
						return $userId;
				}else return false;
		}

		function checkType($user){
			global $conn;

			//checks user type
			$query = $conn->query("SELECT type FROM users WHERE Id = \"$user\" ");
			if($query){
				$userData = $query->fetch_assoc();
				return $userData['type'];
			}else return false;
		}
		function total_users(){
			//returns all the users of church system
			global $conn;

			$query = $conn->query("SELECT COUNT(*) as count FROM uplus.users") or trigger_error($conn->error);
			return $query->fetch_assoc()['count'];
		}

		function checkLogin($userType){
			//function to check if logged in person is $userType
			global $conn;
			$user = getUser();
			if(!$user)
				return false;

			//checking the type
			$query = $conn->query("SELECT * FROM users WHERE Id = \"$user\" LIMIT 1 ") or die("usercheck error $conn->error");
			$userData = $query->fetch_assoc();

			return $userData;

			//ignored for now

			if($userData['type'] == $userType){
				return $userData;
			}else return false;
		}

		function user_details($userid){
				//Function to get user's details
				global $conn;
				$user = $conn->query("SELECT * FROM members WHERE id = \"$userid\" LIMIT 1 ") or die("Errror getting user's details $conn->error");

				$user = $user->fetch_assoc();
				return $user;
		}

		function clean_string($string) {
		 $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
		 $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		 return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	 }


		function church_event($churchID){
			global $conn;
			$events = array();
			$query = $conn->query("SELECT * FROM event WHERE church = \"$churchID\" ORDER BY eventStart DESC LIMIT 20") or die("$conn->error");
			while ($data = $query->fetch_assoc()) {
					$events[] = $data;
			}
			return $events;
		}

		function member_types($church = ''){
			global $conn;
			//Function to help us get member types
			$query = $conn->query("SELECT * FROM member_types") or die("Can't get mtypes $conn->error");
			$types = array();

			while ($data = $query->fetch_assoc()) {
				$types[] = $data;
			}
			return $types;
		}
		
		function staff_details($userid){
			//Function to return details for users
			global $conn;
			$query = $conn->query("SELECT *, CONCAT(fname, ' ', lname) as name FROM users WHERE Id = \"$userid\" LIMIT 1 ") or die("Can't get staff: $conn->error");
			return $query->fetch_assoc();
		}

		function branch_rep($branch){
			//Group representative
			global $conn;
		}

		function group_types($church = ""){
			global $conn;
			//Function to help us get group types
			if($church){
				$sql = "SELECT DISTINCT(group_types.name) FROM group_types JOIN groups ON group_types.name = groups.type JOIN branches ON groups.branchid = branches.id WHERE church = \"$church\" ";
				$query = $conn->query($sql) or die("Error $conn->error");

				$types = array();

			while ($data = $query->fetch_assoc()) {
					$types[] = $data['name'];
			}

			}else{
				$query = $conn->query("SELECT * FROM group_types ORDER BY `group_types`.`drank` ASC") or die("Can't get gtypes $conn->error");
				$types = array();

			while ($data = $query->fetch_assoc()) {
					$types[] = $data;
			}
			}
			
			
			
			return $types;
		}

		function group_details($group){
			//group details
			global $conn;
			$query = $conn->query("SELECT * FROM groups WHERE groups.id = \"$group\" LIMIT 1");
			if($query->num_rows > 0){
				$group_data = $query->fetch_assoc();
				//getting number of members
				$members = $conn->query("SELECT COUNT(*) as num FROM group_members WHERE groupid = $group_data[id]");
				$members = $members->fetch_assoc();
				$group_data['members'] = $members['num'];

				return $group_data;
			}else{
				return false;
			}
			var_dump($query);
		}

		function getChurch($church){
			global $conn;
			//get church details
			$query = $conn->query("SELECT * FROM church WHERE id = \"$church\" ") or trigger_error("Can't get church $conn->error");
			return $query->fetch_assoc();
		}

		function churchAdmin($churchID){
			global $conn;

			$query = $conn->query("SELECT *, CONCAT(fname, ' ', lname)  as name FROM users WHERE type = 'church' AND church = \"$churchID\" LIMIT 1 ") or trigger_error($conn->error);

			if($query->num_rows)
				return $query->fetch_assoc();
			else return false;
		}
		function church_feeds($church)
		{
			//function to return the posts from $churc
			global $conn;
			$sql = "SELECT *, posts.type as type, (SELECT COUNT(*) FROM posts_like WHERE postId = posts.id) as nlikes, (SELECT COUNT(*) FROM posts_comments  WHERE postId = posts.id) as ncomments FROM posts JOIN users ON posts.postChurchAdmin = users.Id  WHERE users.church = \"$church\" AND  posts.archived = 'no' ORDER BY postedDate DESC ";

			$query = $conn->query($sql) or trigger_error($conn->error);

			$posts = array();

			while ($data = $query->fetch_assoc()) {

				// var_dump($data['ptype']);
				//generating target string
				if($data['type'] == 'forum'){
					$forumid = $data['targetForum'];
					$forum_data = getForum($forumid);
					$data['target_string'] = $forum_data['forumtitle'];
				}else if($data['type'] == 'church'){
					$churchid = $data['targetChurch'];
					$church_data = getChurch($churchid);
					$data['target_string'] = $church_data['name'];
				}else{
					$data['target_string'] = $data['type'];
				}

				$posts[] = $data;
			}
			return $posts;
		}

		function get_feed($feed)
		{
			//function to return the details on the feed
			global $conn;
			$sql = "SELECT *, posts.type as type, (SELECT COUNT(*) FROM posts_like WHERE postId = posts.id) as nlikes, (SELECT COUNT(*) FROM posts_comments  WHERE postId = posts.id) as ncomments FROM posts JOIN users ON posts.postChurchAdmin = users.Id  WHERE posts.id = \"$feed\" ";

			$query = $conn->query($sql) or trigger_error($conn->error);

			$data = $query->fetch_assoc();
			return $data;
		}

		function getForum($forumId){
			//returns forum data
			global $conn;
			$query = $conn->query("SELECT * FROM forums WHERE id = \"$forumId\" ") or trigger_error($conn->error);

			return $query->fetch_assoc();
		}


		function church_forums(){
			//returns church
			global $conn;
			$query = $conn->query("SELECT * FROM forums WHERE archived = 'no' ORDER BY id DESC") or trigger_error("can't  get forums $conn->error");

			$forums = array();

			while ($data = $query->fetch_assoc()) {
				$forums[] = $data;
			}
			return $forums;
		}

		function group_members($group){
			//members details
			global $conn;
			$query = $conn->query("SELECT * FROM group_members as gm JOIN members as m ON gm.member = m.id WHERE gm.groupid = \"$group\"") or die("Can;t get group members $conn->error");
			$members = array();
			while ($data = $query->fetch_assoc()) {
				$members[] = $data;
			}
			return $members;
		}

		function branch_leader($branch, $position=''){
			//getting all branch leaders
			global $conn;

			$query = $conn->query("SELECT * FROM users JOIN branch_leaders ON users.Id = branch_leaders.user WHERE branch_leaders.branch = \"$branch\" AND position LIKE  \"%$position%\" ") or die("Error getting branch staff");
			$leaders = array();
			while ($data = $query->fetch_assoc()) {
				$leaders[] = $data;
			}
			return $leaders;
		}
		
		function church_branches($church){
			//Getting branches
			global $conn;
			$branchesQuery = $conn->query(  "SELECT * FROM branches WHERE church = $church ") or die("Can't get branches ".$conn->error);
			$branches = array();

			while ($data = $branchesQuery->fetch_assoc()) {
				$branches[] = $data;
			}
			return $branches;
		}

		function church_donations($church){
			//Function to return church donations
			global $conn;

			$query = $conn->query("SELECT *, members.name as membername, donations.id as donation_id, donations.date as donation_date, branches.name as branchname FROM donations JOIN members ON donations.member = members.id JOIN branches ON members.branchid = branches.id WHERE branches.church  = \"$church\" ") or die("Can't get donations $conn->error");
			$donations = array();

			while ($data = $query->fetch_assoc()){
				$donations[] = $data;
			}
			return $donations;
		}

		function church_services($church, $branch = ''){
			//function for services of church even in branch
			global $conn;

			//if branch was not specified
			if(empty($branch)){
						$temp = array();
						$branches = churchbranches($church);

						if(empty($branches))
							return false;

						for($n=0; $n<count($branches); $n++){
							$temp[] = $branches[$n]['id'];
						}
						$branch = $temp;
			}else{
						$branch = array($branch);
				};


				$sql = "SELECT * FROM church_service WHERE church_service.branchid IN (".implode(", ", $branch).") ";
				$query = $conn->query($sql) or trigger_error("Can't get cservices $conn->error");

				$services = array();
				while ($data = $query->fetch_assoc()) {
						$services[]= $data;
				}
				return $services;
		}

		function get_branch($branch){
			//Getting details of a branch
			global $conn;
			$query = $conn->query("SELECT * FROM branches WHERE branches.id = \"$branch\" LIMIT 1 ") or die("Can't get branch $conn->error");
			return $query->fetch_assoc();
		}

		function donation_sources($church){
			//Function to return all donation source/payment methods
			global $conn;
			$sql = "SELECT DISTINCT(source) as source, SUM(donations.amount) as amount FROM donations JOIN members ON donations.member = members.id JOIN branches ON members.branchid = branches.id WHERE branches.church = \"$church\" GROUP BY source ";
			$query = $conn->query($sql) or die("Can't get the donation sources $conn->error");
			$sources = array();

			while ($data = $query->fetch_assoc()) {
				$sources[$data['source']] = $data;
			}
			return $sources;
		}

		function service_details($service_id){
			//Function to get user's details
			global $conn;
			$service = $conn->query("SELECT * FROM service WHERE id = \"$service_id\" LIMIT 1 ") or die("Errror getting sevice's details $conn->error");

			$service = $service->fetch_assoc();
			return $service;
		}
		
		function donations_by_service($church){
			//status of donations by service on church
			global $conn;
			$sql = "SELECT COUNT(*) as num, SUM(donations.amount) as total, service.name as servicename FROM donations JOIN service ON donations.service = service.id WHERE service.church = \"$church\" GROUP BY service.name ";
			$query = $conn->query($sql) or die("Errror getting donations service status $conn->error");

			$donations = array();
			while($data = $query->fetch_assoc()){
				$donations[] = $data;
			}
			return $donations;
		}

		function group_non_members($group){
			//members details
			global $conn;
			$query = $conn->query("SELECT * FROM members WHERE members.id NOT IN (SELECT member FROM group_members WHERE group_members.groupid = \"$group\") ") or die("Can;t get group non-members $conn->error");
			$members = array();
			while ($data = $query->fetch_assoc()) {
				$members[] = $data;
			}
			return $members;
		}

		function logMessages($messageID, $receivers, $status='pending'){
			global $conn;

			$receivers = is_array($receivers)?$receivers:array($receivers);

			//Buildiing query - insert values
			$sql = "INSERT INTO messageslog(message, receiver, status) VALUES ";
			$iquery = '';
			for($n=0; $n<count($receivers); $n++){
				 $iquery .= "('$messageID', '$receivers[$n]', '$status'), ";
			}
			$iquery = trim($iquery,  ', ');
			$sql .= $iquery;

			$query = $conn->query($sql) or die($conn->error());

			return true;
		}

		function smsbalance($churchID){
			//SMS Balance
			global $conn;

			$query = $conn->query("SELECT * FROM smsbalance WHERE church = \"$churchID\" LIMIT 1 ") or die("Cant get the balance $conn->error");
			$data = $query->fetch_assoc();
			return $data['balance'];
		}

		function churchSMSname($churchID){
			//SMS Balance
			global $conn;

			if(!$churchID)
				return false;

			$query = $conn->query("SELECT COALESCE(smsName, name) smsName FROM church WHERE id = \"$churchID\" LIMIT 1 ") or die("Cant get the sms name $conn->error");
			$data = $query->fetch_assoc();
			return substr($data['smsName'], 0, 12);
		}

		function msend($logID){
				include 'db.php';
				//Getting details of the message log

				$det = $conn->query("SELECT email, subject, token, sender, smsName, members.phone as receiver, message.message as message, channel FROM messageslog JOIN message ON messageslog.message = message.id JOIN members ON messageslog.receiver = members.id WHERE messageslog.id = \"$logID\" LIMIT 1 ") or trigger_error("can get log data ".mysqli_error($conn));

				if(mysqli_num_rows($det)){
						$smsdet = mysqli_fetch_assoc($det);
						$channel = $smsdet['channel'];
						$receiver = $smsdet['receiver'];
						$message = $smsdet['message'];            

						$messageSender = $smsdet['sender'];

						$senderData = staff_details($messageSender);
						if($channel == 'sms'){
								//checking the sms name
							if(!empty($smsdet['smsName'])){
								$smsName = $smsdet['smsName'];
							}else{
								$smsName = churchSMSname($senderData['church']);
							}

							//Sending sms
							$smsstatus = sendsms($receiver, $message, '', $smsName);


								//reducing SMS
								if($smsstatus == 'Yes'){
									$query = $db->query("UPDATE smsbalance SET balance = balance-1 WHERE church = (SELECT church FROM users JOIN message on users.id = message.sender WHERE message.sender = \"$messageSender\" LIMIT 1 )");
								}

						}else if($channel == 'app'){
								$token = $smsdet['token'];
								$sendstatus = send_notification(array($token), array("message" => $message));

								$sendstatus = json_decode($sendstatus, 1);
								if($sendstatus['success'] == 1)
								{
									$smsstatus = "Yes"; 
								}else
								{
								$smsstatus = "No";
							 }                
						}
						else if($channel == 'email'){
								$token = $smsdet['token'];
								$email = $smsdet['email'];
								$subject = $smsdet['subject'];
								$sendstatus = email($email, $subject, $message);

								$sendstatus = json_decode($sendstatus, 1);
								if($sendstatus['success'] == 1)
								{
									 $smsstatus = "Yes"; 
								}else
								{
								$smsstatus = "No";
							 }                
						}

						if($smsstatus == "Yes")
						{
								$query = $conn->query("UPDATE messageslog SET status='sent' WHERE id = \"$logID\"") or die(mysqli_error($conn));
								return true;
						}
						elseif($smsstatus == "No")
						{
								$query = $conn->query("UPDATE messageslog SET status='failed' WHERE id = \"$logID\"") or die(mysqli_error($conn));
								return false;
						}
				}
				else
				{
						return false;
				}       
		}

		function sendsms($phone, $message, $subject="", $smsName="Uplus"){
			$recipients     = $phone;
			global $churchID;

			// $smsName = !empty( churchSMSname($churchID) )?churchSMSname($churchID):"Uplus";
			$data = array(
					"sender"        =>$smsName,
					"recipients"    =>$recipients,
					"message"       =>$message,
			);
			$url = "https://www.intouchsms.co.rw/api/sendsms/.json";
			$data = http_build_query ($data);
			$username="cmuhirwa";
			$password="clement123";
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
							
			if($httpcode == 200)
			{
					return "Yes";
			}
			else
			{
					return "No";
			}
		}

		function list_groups($churchID){
			global $conn;
			$query = $conn->query("SELECT groups.*, branches.name as branchname FROM groups JOIN branches ON groups.branchid = branches.id WHERE branches.church = \"$churchID\" ") or die("Cant get groups ".$conn->error);

			$churches = array();

			while ($data = $query->fetch_assoc()) {
				$churches[] = $data;
			}
			return $churches;
		}

		function branch_groups($branch){
			global $conn;
			$query = $conn->query("SELECT * FROM groups WHERE branchid = \"$branch\" ") or die("Cant branch get groups ".$conn->error);

			$branches = array();

			while ($data = $query->fetch_assoc()) {
				$branches[] = $data;
			}
			return $branches;
		}

		function email($email, $subject, $body, $header=''){
		}

		function branch_members($branch){
			//members of a branch
			global $conn;
			$query = $conn->query("SELECT * FROM members WHERE branchid = \"$branch\" ") or die("Can;t get branch members $conn->error");
			$members = array();

			while ($data  = $query->fetch_assoc()) {
				$members[] = $data;
			}
			return $members;
		}

		function add_user($name, $phone, $email, $gender, $address=''){
			global $conn;

			//check if email and phone are not used
			$check = $conn->query("SELECT * FROM uplus.users WHERE phone = \"$phone\" AND \"$email\" ") or trigger_error("User can't be got".$conn->error);
			if($check->num_rows<1){
				//here the user is new
				$query = $conn->query("INSERT INTO uplus.users(name, phone, email, gender, address) VALUES(\"$name\", \"$phone\", \"$email\", \"$gender\", \"$address\") ") or trigger_error("User can't be inserted".$conn->error);
				return $conn->insert_id;
			}else{
				//get the userId
				$userData = $check->fetch_assoc();
				return $userData['id'];
			}
		}

		function add_church_member($userId, $userType, $branchId, $platform='app', $createdBy=1){
			global $conn;

			//Attaches $userId to church with $branchId

			//remove the user from all the churches
			$conn->query("UPDATE church_members SET archived = 'yes' WHERE userCode = \"$userId\" ") or trigger_error($conn->error);

			$sql = "INSERT INTO church_members(userCode, branchid, type, joinedByPlatform, createdBy) VALUES(\"$userId\", \"$branchId\", \"$userType\", \"$platform\", \"$createdBy\") ";
			echo "$sql";
			$query = $conn->query($sql) or trigger_error($conn->error);
			if($query){
				return true;
			}else return false;
		}

		function getChurchList(){
			global $conn;

			$query = $conn->query("SELECT * FROM church") or trigger_error($conn->error);

			$churches = array();


			while ($data = $query->fetch_assoc()) {
				$churches[] = $data;
			}

			return $churches;
		}

		function church_members($churchid){
			//returns members of $churchid
			global $conn;
			$query = $conn->query("SELECT members.* FROM members JOIN branches ON members.branchid = branches.id WHERE branches.church = $churchid ") or die("Can't get members $conn->error");
			$members = array();
			while ($data = $query->fetch_assoc()) {
				$members[] = $data;
			}
			return $members;
		}

		function Semail($email, $subject, $body, $header=''){
				require_once 'mailer/PHPMailerAutoload.php';
				$email = "info@edorica.com";
				$server = "mail.edorica.com:465";
				$headers  = $header.= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;

				$mail->smtpConnect(
						array(
								"ssl" => array(
										"verify_peer" => false,
										"verify_peer_name" => false,
										"allow_self_signed" => true
								)
						)
				);

				//Enable SMTP debugging.
				$mail->Host = '$server';
				$mail->Port = 587;
				$mail->Username = $email;
				$mail->Password = 'laa1001laa';
				$mail->setFrom($email);
				$mail->addAddress($email);
				$mail->Subject = $subject;
				$mail->Body = $body;
				$mail->addCustomHeader($headers);

				$data = "";

				//send the message, check for errors
				if (!$mail->send())
				{
					 //Sending with traditional mailer
					 // $header = "From: $email";
					 // if(mail($email, $subject, $body, $headers."From:$email")){
					 //     $data = true; //Here the e-mail was sent
					 //     }
					 //  else{
					 //      $data = false;
					 //  }

						$data = false;
				}
				else
				{
					 $data = true;
				}

				echo json_encode($data);
		}
	
		function church_podcasts($church){
			//Get all the podcasts of a church
			global $conn;

			$church = $conn->real_escape_string($church);
			$query = $conn->query("SELECT * FROM podcasts WHERE church =\"$church\" AND status = 'active' ") or die("Error getting church podcats $conn->error");
			$pods = array();

			while ($data = $query->fetch_assoc()) {
				$pods[] = $data;
			}
			return $pods;
		}

		function addMessage($sender, $message, $channel, $subject, $scheduleTime="", $smsName=null){
				//$time = $time??date('Y-m-d h:m:s');
				global $conn;
				$sql = "INSERT INTO message(sender, message, channel, subject, scheduleTime, smsName) VALUES (\"$sender\", \"$message\", \"$channel\", \"$subject\", \"$scheduleTime\", \"$smsName\")";

				$query = mysqli_query($conn, $sql) or die("Okay".$conn->error);
				return mysqli_insert_id($conn);
		}

		function msgStat($id, $stat){
				global $conn;
				//Message logs of $id
				$sql = "SELECT * FROM messageslog WHERE message = \"$id\" AND  status=\"$stat\"";
				$query  = mysqli_query($conn, $sql) or die(mysqli_error($conn));

				$logs = array();
				while ($data = mysqli_fetch_assoc($query)) {
						$logs[] = $data;
				}
				return $logs;
		}

		function mlogs($id){
				global $conn;

				//Message logs of $id
				$query  = mysqli_query($conn, "SELECT * FROM messageslog WHERE message = \"$id\"") or die(mysqli_error($conn));

				$logs = array();
				while ($data = mysqli_fetch_assoc($query)) {
						$logs[] = $data;
				}

				return $logs;
		}

		function churchbranches($church){
				global $db;
				$query = $db->query("SELECT * FROM branches WHERE church = \"$church\" ") or die("Error getting church branches ".$db->conn);
				$branches = array();
				while ($data = $query->fetch_assoc()) {
					$branches[] = $data;
				}
				return $branches;
		}

		function send_notification ($tokens, $message)
		{
				$url = 'https://fcm.googleapis.com/fcm/send';
				$fields = array(
						 'registration_ids' => $tokens,
						 'data' => $message
						);
				$headers = array(
						'Authorization:key = AIzaSyCVsbSeN2qkfDfYq-IwKrnt05M1uDuJxjg',
						'Content-Type: application/json'
						);
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL, $url);
			 curl_setopt($ch, CURLOPT_POST, true);
			 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			 $result = curl_exec($ch);           
			 if ($result === FALSE) {
					 die('Curl failed: ' . curl_error($ch));
			 }
			 curl_close($ch);
			 return $result;
		}
?>