<?php
// START INITIATE
	include ("db.php");
	define("DEFAULT_USER_IMAGE", "https://uplus.rw/assets/images/20.jpg");

	//return JSON Content-Type
    header('Content-Type: application/json');

    //hostname for file referencing
    $hostname = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";

    // keep api request log for debuggin
	$f = fopen("logs/invest.txt", 'a') or die("Unable to open file!");;
	fwrite($f, json_encode($_POST)."\n\n");
	fclose($f);

	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if(isset($_POST['action']))
		{
			//check if the function is defined
			if(function_exists($_POST['action'])){
				$_POST['action']();
			}else{
				echo 'Make sure you understand';
			}
		}
		else
		{
			echo 'Action could not be found! Please read the API documentation';
		}
	}
	else
	{
		echo 'POST ONLY UPLUS API V02';
	}
// END INITIATE

// START FORUMS
	function listForums()
	{
		require('db.php');
		global $hostname;
		$memberId		= mysqli_real_escape_string($db, $_POST['memberId']);
		$query = $investDb->query("SELECT F.id forumId, F.title, F.subtitle, F.icon, IFNULL((SELECT M.mine FROM forummember M WHERE M.memberId = '$memberId' AND M.forumId = F.id),'YES') AS mine  FROM forums F WHERE archive <> 'YES'")or die(mysqli_error($investDb));
		$forums = array();
		while ($forum = mysqli_fetch_array($query))
		{
			if($forum['mine'] == 'YES')
			{
				$joined = '0';
			}
			else
			{
				$joined = '1';
			}
			$forumId = $forum['forumId'];
			
			$countQuery = $investDb->query("SELECT * FROM forummember WHERE forumId = '$forumId' AND mine = 'NO'")or die(mysqli_error($investDb));
		   	$joinedCount = mysqli_num_rows($countQuery);
		    $forums[] = array(
				"forumId"		=> $forumId,
				"forumTitle"	=> $forum['title'],
				"forumSubtitle"	=> $forum['subtitle'],
				"forumIcon"		=> $hostname.$forum['icon'],
				"joined"		=> $joined,
				"joinedCount"	=> $joinedCount
			);
		}
		header('Content-Type: application/json');
		$forums = json_encode($forums);
		echo $forums;
	}

	function joinForum()
	{
		require('db.php');
		$memberId	= mysqli_real_escape_string($db, $_POST['memberId']);
		$forumId	= mysqli_real_escape_string($db, $_POST['forumId']);
		if(mysqli_num_rows($investDb->query("SELECT * FROM forumuser WHERE forumCode = '$forumId' AND (userCode = '$memberId' AND archive <> 'YES')"))>0)
		{
			echo "User Already In with memberId (".$memberId.") And forumId: (".$forumId.")";
		}
		elseif (mysqli_num_rows($investDb->query("SELECT * FROM forumuser WHERE forumCode = '$forumId' AND (userCode = '$memberId' AND archive = 'YES')"))>0) {
			$query 		= $investDb->query("UPDATE forumuser SET archive = 'NO' WHERE forumCode = '$forumId' and userCode = '$memberId'")or die(mysqli_error($investDb));
			echo "User Brought back in, with memberId (".$memberId.") And forumId: (".$forumId.")";
		}
		else
		{
			$query 		= $investDb->query("INSERT INTO forumuser (forumCode, userCode, createdBy) VALUES ('$forumId','$memberId','$memberId')")or die(mysqli_error($investDb));
			echo "Done with memberId (".$memberId.") And forumId: (".$forumId.")";
		}	
	}

	function exitForum()
	{
		require('db.php');
		$memberId	= mysqli_real_escape_string($db, $_POST['memberId']);
		$forumId	= mysqli_real_escape_string($db, $_POST['forumId']);
		if(mysqli_num_rows($investDb->query("SELECT * FROM forumuser WHERE forumCode = '$forumId' AND userCode = '$memberId'"))>0)
		{
			$query 		= $investDb->query("UPDATE forumuser SET archive = 'YES' WHERE forumCode = '$forumId' and userCode = '$memberId'")or die(mysqli_error($investDb));
			echo "Done user exited the forum with memberId (".$memberId.") And forumId: (".$forumId.")";
		}
		else
		{
			echo "User wasent in the forum With memberId: (".$memberId.") And forumId: (".$forumId.")";
		}
	}

	function loopFeeds()
	{
		require('db.php');
		require_once('../invest/admin/db.php');
		require_once('../invest/admin/functions.php');
		$memberId	= mysqli_real_escape_string($db, $_POST['memberId']??"");

		$all_feeds = listFeeds($memberId);

		// $sql = $investDb->query("SELECT F.id feedId, F.feedForumId, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = F.id) as nlikes, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = F.id AND userCode = '$memberId') as liked, (SELECT COUNT(*) FROM feed_comments  WHERE feedCode = F.id) as comments, F.feedTitle, U.name feedBy, U.userImage feedByImg, F.createdDate feedDate,F.feedContent FROM investments.feeds F INNER JOIN uplus.users U ON F.createdBy = U.id")or die(mysqli_error($investDb));
		$feeds = array();

		for ($n=0; $n<count($all_feeds); $n++)
		{
			$row = $all_feeds[$n];
			//liked status of the user
			$liked = $row['liked']==0?"NO":"YES";
			$feeds[] = array(
				"feedId"		=> $row['id'],
				"feedForumId"	=> $row['feedForumId'],
				"feedTitle"		=> $row['feedTitle']??"",
				"feedById"		=> $row['createdBy'],
				"feedBy"		=> $row['feedByName'],
				"feedByImg"		=> $row['feedByImg']??DEFAULT_USER_IMAGE,
				"feedLikes"		=> $row['nlikes'],
				"feedLikeStatus"=> $liked, 
				"feedComments" 	=> $row['ncomments'],
				"feedDate"		=> $row['createdDate'],
				"feedContent"	=> $row['feedContent'],
			);
		}

		//getting forum images
		foreach ($feeds as $i => $feed) 
		{
			$feedId 	= $feed['feedId'];
			$images 	= array();
			$video = 'None';
            $sql 		= $investDb->query("SELECT `imgUrl` FROM `investmentimg` WHERE `investCode` = '$feedId'")or die (mysqli_error($investDb));
            while($rowImage = mysqli_fetch_array($sql))
            {
            	$ext = strtolower(pathinfo($rowImage['imgUrl'], PATHINFO_EXTENSION)); //extension

            	//checking for video
            	if(strtolower($ext) == 'mp4'){
            		$video = $rowImage['imgUrl'];
            	}

        		$images[]  = array(
                    "imgUrl"         => $rowImage['imgUrl']
                );   
            }
            $feeds[$i]['feedImage'] = $images;
            $feeds[$i]['video'] = $video;
		}
		
        mysqli_close($db);
        mysqli_close($eventDb);
        header('Content-Type: application/json');
		$feeds = json_encode($feeds);
		echo $feeds;
	}
	function loadMoreFeeds()
	{
		require('db.php');
		require_once('../invest/admin/db.php');
		require_once('../invest/admin/functions.php');
		$memberId	= $investDb->real_escape_string($_POST['memberId']??"");
		$forum = $investDb->real_escape_string($_POST['forumId']??"");
		$lastFeed = $investDb->real_escape_string($_POST['lastFeedId']??"");

		$all_feeds = forumFeeds($forum, $memberId, $lastFeed);

		// $sql = $investDb->query("SELECT F.id feedId, F.feedForumId, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = F.id) as nlikes, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = F.id AND userCode = '$memberId') as liked, (SELECT COUNT(*) FROM feed_comments  WHERE feedCode = F.id) as comments, F.feedTitle, U.name feedBy, U.userImage feedByImg, F.createdDate feedDate,F.feedContent FROM investments.feeds F INNER JOIN uplus.users U ON F.createdBy = U.id")or die(mysqli_error($investDb));
		$feeds = array();

		for ($n=0; $n<count($all_feeds) && $n<3; $n++)
		{
			$row = $all_feeds[$n];
			//liked status of the user
			$liked = $row['liked']==0?"NO":"YES";
			$feeds[] = array(
				"feedId"		=> $row['id'],
				"feedForumId"	=> $row['feedForumId'],
				"feedTitle"		=> $row['feedTitle']??"",
				"feedById"		=> $row['createdBy'],
				"feedBy"		=> $row['feedByName'],
				"feedByImg"		=> $row['feedByImg']??DEFAULT_USER_IMAGE,
				"feedLikes"		=> $row['nlikes'],
				"feedLikeStatus"=> $liked,
				"feedComments" 	=> $row['ncomments'],
				"feedDate"		=> $row['createdDate'],
				"feedContent"	=> $row['feedContent'],
			);
		}

		//getting forum images
		foreach ($feeds as $i => $feed) 
		{
			$feedId 	= $feed['feedId'];
			$images 	= array();
            $sql 		= $investDb->query("SELECT `imgUrl` FROM `investmentimg` WHERE `investCode` = '$feedId'")or die (mysqli_error($investDb));
            while($rowImage = mysqli_fetch_array($sql))
            {
                $images[]  = array(
                    "imgUrl"         => $rowImage['imgUrl']
                );
            }
            $feeds[$i]['feedImage'] = $images;
		}
		
        mysqli_close($db);
        mysqli_close($eventDb);;
		echo json_encode($feeds);
	}

	function likeFeed()
	{
		require('db.php');
		$userId		= mysqli_real_escape_string($db, $_POST['userId']);
		$feedId		= mysqli_real_escape_string($db, $_POST['feedId']);

		//check if the user has liked the feed
		$query = $investDb->query("SELECT * FROM feed_likes WHERE feedCode = \"$feedId\" AND userCode = \"$userId\" ");
		if($query->num_rows){
			//here user already liked
			echo json_encode("skipped");
		}else{
			//make the user like
			$investDb->query("INSERT INTO feed_likes(feedCode, userCode) VALUES(\"$feedId\", \"$userId\")");
			echo json_encode("Done");
		}
		
		
	}

	function listCommentsFeed()
	{
		require('db.php');

		$feed = $_POST['feedId']??"";

		if($feed){
			$query = $investDb->query("SELECT C.comment, C.commentDatetime as commentDate, U.name as commentByName, U.userImage as commentByImg FROM feed_comments as C JOIN uplus.users as U ON C.userCode = U.id WHERE C.feedCode = \"$feed\" ORDER BY commentDatetime DESC ") or trigger_error($investDb->error);
			$comments = array();

			while ($data = $query->fetch_assoc()) {
				$comments[] = $data;
			}
			echo json_encode($comments);			
		}else{
			echo json_encode("Fail");
		}

		
	}

	function commentFeed()
	{
		require('db.php');
		$userId		 = mysqli_real_escape_string($db, $_POST['userId']);
		$feedId		 = mysqli_real_escape_string($db, $_POST['feedId']);
		$feedComment = mysqli_real_escape_string($db, $_POST['feedComment']);

		$investDb->query("INSERT INTO feed_comments(feedCode, userCode, comment) VALUES(\"$feedId\", \"$userId\", \"$feedComment\")");

		echo json_encode("Done");
	}

	function postFeed()
	{
		require('db.php');
		global $hostname;
		$request = $_POST;
		// /post feeds
        $userId = $request['memberId']??"";
        $post_content = $investDb->real_escape_string($request['feedContent']??"");

        //type of the post
        $type = $investDb->real_escape_string($request['type']??"");

        //target forum
        $target_audience = $request['targetForum']??$request['feedId'];

        //check if target is public
        if(!is_numeric($target_audience) || $target_audience == 'public' ){
        	$target_audience = '';
        }

        // title
        $title = $request['title']??"";

        //attachments link
        $attachments = $request['attachments']??"";
        // $attachments = stripslashes($attachments);
        // $attachments = str_ireplace("'", "\"", $attachments);
        $attachments = stripslashes($request['attachments']??"");
        $attachments = str_ireplace("'", "\"", $attachments); #repairing android sent strings with single quote

        $attachments = json_decode($attachments, true);

        //the type of person who posted - admin or member if empty it'll be elisa app
        $userType = $request['userType']??'member';        

        //setting empty forum ffeed id for public feeds
        if($target_audience){
        	$sql = "INSERT INTO feeds(feedContent, createdBy, feedForumId) VALUES(\"$post_content\", \"$userId\", \"$target_audience\")";
        }else{
        	$sql = "INSERT INTO feeds(feedContent, createdBy) VALUES(\"$post_content\", \"$userId\")";
        }
        $query = $investDb->query($sql) or trigger_error($investDb->error);


        if($query){
            $feed_id = $investDb->insert_id;
            

            //link attachments like videos on android and everything from admin
            if(!empty($attachments)){
            	//already uploaded attachments
	            for($n=0; $n<count($attachments); $n++){
	                $att = $attachments[$n];
	                $sql = "INSERT INTO investmentimg(imgUrl, investCode) VALUES(\"$att\", $feed_id) ";
	                $investDb->query($sql) or trigger_error($investDb->error);
	            }
	        }

	        //Android images in base64
	        if(!empty($request['feedAttachments'])){

	        	//attachments from Android
	        	$attachments = $request['attachments']??"";
	        	$attachments = json_decode($attachments, true);
	        	
	        	if(is_array($attachments)){


	        		//looping through image
	        		foreach ($attachments as $key => $value) {
	        			if($value == "'none'"){
	        				continue;
	        			}


		        		$filename = "invest/gallery/feeds/";
					    // $image_parts = explode(";base64,", $value);
					    // $image_type_aux = explode("image/", $image_parts[0]);
					    // $image_type = $image_type_aux[1];
					    $image_base64 = base64_decode($value);
					    $file = $filename . uniqid() . '.png';
					    file_put_contents("../".$file, $image_base64);

					    //storing in the database
					    $sql = "INSERT INTO investmentimg(imgUrl, investCode) VALUES(\"$hostname$file\", $feed_id) ";
	                	$investDb->query($sql) or trigger_error($investDb->error);
		        	}
	        	}else{
	        		// die("Failed, Not attachments");
	        	}
	        };

	        //If images were sent in form - Not working now
	        if(!empty($_FILES) && 0){
	        	//here we've to upload these files, this oftenly happens for android requests
	        	$attachments = $_FILES;
	        	foreach ($attachments as $handlename => $attachment) {
	        		$sent_file_name = $attachment['name'];

	        		$ext = strtolower(pathinfo($sent_file_name, PATHINFO_EXTENSION)); //extension

	        		//forumlating how the file will be names
	        		$filename = "invest/gallery/feeds/".substr($sent_file_name, 0, -4)."_".time().".".$ext;
	        		

	        		$allowed_extensions = array('preventerrorsguys_dont remove please', 'jpg', 'png', 'mp3', 'aac', 'mp4');

	        		//checking extension
	        		if(array_search($ext, $allowed_extensions)){
			            //we can now upload
			            
			            if(move_uploaded_file($attachment['tmp_name'], "../".$filename)){
			            	$sql = "INSERT INTO investmentimg(imgUrl, investCode) VALUES(\"$hostname$filename\", $feed_id) ";
	                		$investDb->query($sql) or trigger_error($investDb->error);
			            }
			        }else{
			            $response = array('status'=>false, 'msg'=>"Invalid file type");
			        }
	        	}
	        }
            $response = 'Done';
        }else{
            $response = 'Failed';   
        }
        echo json_encode($response);
	}

	function deleteFeed()
	{
		require 'db.php';
		global $hostname;
		$request = $_POST;
		// /delete feeds
        $userId = $request['userId']??"";
        $feedId = $request['feedId']??"";

        //checking authority
        if(1){
        	if($userId && $feedId){
	            $sql = "UPDATE feeds SET archivedDate = NOW(), archivedBy = \"$userId\", archive = 'YES', updatedDate = NOW(), updatedBy = \"$userId\" WHERE id = \"$feedId\"";
	            $query = $investDb->query($sql) or trigger_error($investDb->error);
	            $response = "Done";
	        }else{
	            $response = "Failed";
	        }
        }
        echo json_encode($response);
    }

    function uploadAttachment(){
    	$hostname = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";
    	//uploading the file for attachments
        $attachment = $_FILES['file'];
        $sent_file_name = $attachment['name'];
        $ext = strtolower(pathinfo($sent_file_name, PATHINFO_EXTENSION)); //extension

        $filename = "invest/gallery/feeds/".substr($sent_file_name, 0, -4)."_".time().".".$ext;

        $allowed_extensions = array('preventerrorsguys_dont remove please', 'jpg', 'png', 'mp3', 'aac', 'mp4');

        if(array_search($ext, $allowed_extensions)){
            //we can now upload
            move_uploaded_file($attachment['tmp_name'], "../".$filename);

            //checking if there is hostname in the filename
            // if(strpos($filename, $hostname) <= 1 && strpos($filename, $hostname) !=false ){
            //     $filename = $hostname.$filename;
            // }
            $filename = $hostname.$filename;

            $response = $filename??"";
        }else{
            $response = "Failed";
        }

        echo json_encode($response);
    }
// END FORUMS


// START INVESTMENT
	function requestCSD()
	{
		// user requesting CSD account
		require 'db.php';
		require_once('../invest/admin/functions.php');

		$request = $_POST;
		$title = $request['tilte']??'';
		$nationality = $request['nationality']??"";
		$gender = $request['gender']??"";

		$userId = $request['userId']??"";

		$dob = date("Y-m-d", strtotime($request['dateOfBirth']??""));
		$NID = $request['NID']??"";
		$passport = $request['passport']??"";
		$country = $request['country']??"";
		$city = $request['city']??"";


		if($userId && $gender && $dob && $NID){
			//here we fetch details from app
			$userData = user_details($userId);

			#checking if the user has already asked for the investment account
			$investData  = checkClientUser($userId, 'invest');
			$csdAccount = getUserCSD($userId);

			$createCSD = false; #flag for creating a CSD account

			if($investData){
				//here the user has asked the the CSD already so let's check status
				$csdStatus = $investData['status'];

				if($csdStatus == 'declined'){
					$createCSD = true;
				}else{
					$response = $csdStatus;
				}
			}else{
				$createCSD = true;
			}

			if($createCSD){
				$query = $investDb->query("INSERT INTO clients(userCode, dob, gender, NID, residentIn, nationality, city, status, statusOn, service) VALUES(\"$userId\", \"$dob\", \"$gender\", \"$NID\", \"$nationality\", \"$country\", \"$city\", 'pending', NOW(), 'invest') ");
				if($query){
					$response = 'Done';
				}else{
					$response = "Failed $investDb->error";
				}
			}
		}else{
			$response = 'Failed';
		}
		
		echo json_encode($response);
	}

	function requestBankACC()
	{
		// user requesting Bank account
		require 'db.php';
		$request = $_POST;


		$title = $request['title']??'';
		$userId = $request['userId']??"";
		$gender = $request['gender']??"";


		$dob = date("Y-m-d", strtotime($request['dateOfBirth']??""));
		$nationality = $request['nationality']??"";
		$NID = $request['NID']??"";
		$passport = $request['passport']??"";

		$country = $request['country']??"";
		$city = $request['city']??"";

		
		if($userId && $gender && $dob && $nationality && $NID){
			//here we fetch details from app
			$query = $db->query("SELECT * FROM uplus.users WHERE id = \"$userId\" ");
			$userData = $query->fetch_assoc();

			$query = $investDb->query("INSERT INTO clients(service, userCode, dob, gender, NID, residentIn, nationality, country, city, status, statusOn) VALUES('bank', \"$userId\", \"$dob\", \"$gender\", \"$NID\", \"$nationality\", \"$nationality\", 'Rwanda', 'Kigali', 'pending', NOW()) ");

			if($query){
				$response = 'Done';
			}else{
				$response = "Failed $investDb->error";
			}

		}else{
			$response = 'Fail';
		}

		echo json_encode($response);
	}

	function requestGroupCSD()
	{
		// user requesting CSD group account
		require 'db.php';
		require '../scripts/class.group.php';
		require '../invest/admin/functions.php';

		$request = $_POST;

		$groupId = $request['groupId']??"";
		$country = $request['country']??"Rwanda";
		if($groupId){
			//here we fetch details from app
			$groupData = $Group->details($groupId);


			//check if group exists
			if($groupData && $groupData['archive']!='yes'){
				//check the group CSD status
				$groupInvestData = checkGroup($groupId);

				//check group investmet data
				$groupCsd = $Group->csd($groupId);

				$csdStatus  = $groupInvestData['status'];

				if($csdStatus == 'pending'){
					$response = 'CSD Account request is pending';
				}else if($csdStatus == 'approved'){
					$response = "CSD Account request is approved with $groupCsd";
				}else{
					//here we can
					$query = $investDb->query("INSERT INTO clients(groupCode, clientType, country, nationality) VALUES(\"$groupId\", 'group', \"$country\", \"$country\")") or trigger_error($investDb->error);
					if ($query) {
						$response = "Done";
					}else{
						$response = "Fail 4";
					}
				}
			}else{
				$response = "Done";
			}
		}else{
			$response = "Fail 1";
		}
		echo json_encode($response);
	}

	function approveCSD()
	{
		//broker is going to approve the CSD request
		require 'db.php';
		require '../invest/admin/functions.php';
		require '../scripts/class.group.php';
		$request = $_POST;

		$csd = $request['CSDAccount']??""; #for bank service this is also bank account but no time for gramma
		$doneBy = $request['approvedBy']??"";
		$clientId = $request['clientId']??"";

		//Cient data
		$clientData = checkClient($clientId);
		if($clientData && $csd && $doneBy){
			//client exists
			$clientType = $clientData['clientType'];
			
			//checking id the user is a broker
			$query = $investDb->query("SELECT * FROM users WHERE id = \"$doneBy\" AND account_type = 'broker' LIMIT 1 ") or trigger_error($investDb->error);
			if($query->num_rows){
				//here user is a  broker we can now assign the CSD
				$investDb->query("UPDATE clients SET csdAccount = \"$csd\", status = 'approved', statusBy = \"$doneBy\", statusOn = NOW() WHERE id = \"$clientId\" ") or trigger_error($db->error);

				//broker data
				$brokerQ = $investDb->query("SELECT C.companyName FROM broker_user B JOIN company C ON B.companyId = C.companyId WHERE B.userCode = '$doneBy'");
				$brokerData = $brokerQ->fetch_assoc();

				if($clientType == 'group'){
					$groupId = $clientData['groupCode'];
					$groupData = $Group->details($groupId);
					$groupName = $groupData['groupName'];

					//getting group admin's phone
					$adminPhone = $groupData['adminPhone'];
					if($adminPhone){
						//Sending the message to the user					
						$message = "Dear admin of $groupName, CSD account for your group has been approved with account number: $csd in $brokerData[companyName] broker, you can now start investing today";
						sendsms($adminPhone, $message);
					}

				}else{
					$userId = $clientData['userCode'];
					$userData = user_details($userId);
					$userphone = $userData['phone'];

					$clientName = $userData['name'];
					if($userphone){
						//Sending the message to the user					
						$message = "Dear $clientName, your csd account has been approved with account number: $csd in $brokerData[companyName] broker, you can now start investing today";

						sendsms($userphone, $message);
					}
				}
				$response = "Done";
			}else{
				$response = "Failed";
			}
		}else{
			$response = "Failed";
		}

		echo json_encode($response);
	}

	function approveBankACC()
	{
		//banker approves the bank acc request
		require 'db.php';
		require '../invest/admin/functions.php';
		require '../scripts/class.group.php';
		$request = $_POST;

		$account = $request['account']??"";
		$doneBy = $request['approvedBy']??"";
		$clientId = $request['clientId']??"";

		//Cient data
		$clientData = checkClient($clientId);

		if($clientData && $account && $doneBy){
			//client exists
			$clientType = $clientData['clientType'];
			
			//checking id the user is a broker
			$query = $investDb->query("SELECT * FROM users WHERE id = \"$doneBy\" AND account_type = 'bank' LIMIT 1 ") or trigger_error($investDb->error);

			if($query->num_rows){
				//User doing this is a bank associate we can assign account
				$investDb->query("UPDATE clients SET accountNumber = \"$account\", status = 'approved', statusBy = \"$doneBy\", statusOn = NOW() WHERE id = \"$clientId\" ") or trigger_error($db->error);

				//broker data
				$brokerQ = $investDb->query("SELECT C.companyName FROM broker_user B JOIN company C ON B.companyId = C.companyId WHERE B.userCode = '$doneBy'");
				$brokerData = $brokerQ->fetch_assoc();

				if($clientType == 'group'){
					$groupId = $clientData['groupCode'];
					$groupData = $Group->details($groupId);
					$groupName = $groupData['groupName'];

					//getting group admin's phone
					$adminPhone = $groupData['adminPhone'];
					if($adminPhone){
						//Sending the message to the user					
						$message = "Dear admin of $groupName, Bank account for your group has been approved with account number: $account in $brokerData[companyName], you can now start banking today";
						sendsms($adminPhone, $message);
					}

				}else{
					$userId = $clientData['userCode'];
					$userData = user_details($userId);
					$userphone = $userData['phone'];

					$clientName = $userData['name'];
					if($userphone){
						//Sending the message to the user					
						$message = "Dear $clientName, your Bank account has been approved with account number: $account in $brokerData[companyName], you can now start banking today";

						sendsms($userphone, $message);
					}
				}
				$response = "Done";
			}else{
				$response = "Failed";
			}
		}else{
			$response = "Failed";
		}

		echo json_encode($response);
	}

	function declineCSD()
	{
		//broker is going to decline the CSD request
		require 'db.php';
		$request = $_POST;

		$doneBy = $request['approvedBy']??"";
		$user = $request['accountUser']??"";
		$message = $request['message']??"";

		if($user && $doneBy){
			//checking id the user is a broker
			$query = $investDb->query("SELECT * FROM users WHERE id = \"$doneBy\" AND account_type = 'broker' LIMIT 1 ") or trigger_error($db->error);
			if($query->num_rows){
				//here user is a  broker we can now assign the CSD
				$investDb->query("UPDATE clients SET status = 'declined', statusBy = \"$doneBy\", statusOn = NOW() WHERE id = \"$user\" ") or trigger_error($db->error);

				//Client data for messaging
				$clientData = checkClient($user);
				$userId = $clientData['userCode'];

				if($userId){
					//get user details
					$userData = $User->details($userId);
					$userphone = $userData['phone'];
					$feedBankMessage = "Dear $userData[name], Your CSD account request was not approved with reason: $message";
					sendsms($userphone, $feedBankMessage);
				}

				$response = "Done";
			}else{
				$response = "Failed";
			}
		}else{
			$response = "Failed";
		}
		echo json_encode($response);
	}

	function declineBankACC()
	{
		//banker is going to decline the CSD request
		require 'db.php';
		require '../invest/admin/functions.php';
		require '../scripts/class.user.php';
		$request = $_POST;

		$doneBy = $request['approvedBy']??"";
		$user = $request['accountUser']??"";
		$message = $request['message']??"";

		if($user && $doneBy){
			//checking id the user is a banker
			$query = $investDb->query("SELECT * FROM users WHERE id = \"$doneBy\" AND account_type = 'bank' LIMIT 1 ") or trigger_error($db->error);
			if($query->num_rows){
				//here user is a  banker we can now assign the Bank account
				$investDb->query("UPDATE clients SET status = 'declined', statusBy = \"$doneBy\", statusOn = NOW() WHERE id = \"$user\" ") or trigger_error($db->error);


				//Client data for messaging
				$clientData = checkClient($user);
				$userId = $clientData['userCode'];

				if($userId){
					//get user details
					$userData = $User->details($userId);
					$userphone = $userData['phone'];
					$feedBankMessage = "Dear $userData[name], Your bank account request was not approved with reason: $message";
					sendsms($userphone, $feedBankMessage);
				}

				$response = "Done";
			}else{
				$response = "Failed 1";
			}
		}else{
			$response = "Failed 2";
		}
		echo json_encode($response);
	}

	function userAccounts(){
		//company adding stocks
		require 'db.php';
		require '../invest/admin/functions.php';

		$request = array_merge($_POST, $_GET);
		$userId = $request['userId'];

		$response = array('csdStatus'=>"none", 'csdAccount'=>"none", 'bankStatus'=>"none", 'bankAccount'=>"none");

		//getting csd info
		$investData = checkClientUser($userId, 'invest');
		if($investData){
			$status = $investData['status']??"none";
			$response['csdStatus'] = $status;

			//return account if account is approved
			if(strtolower($status) == 'approved')
			{
				$response['csdAccount'] = $investData['csdAccount']??"none";
			}
		}

		$bankData = checkClientUser($userId, 'bank');
		if($investData){
			$status = $bankData['status']??"none";
			$response['bankStatus'] = $status;

			//return account if account is approved
			if(strtolower($status) == 'approved')
			{
				$response['bankAccount'] = $bankData['accountNumber']??"none";
			}
		}

		echo json_encode($response);
	}

	function messageBrokerClient()
	{
		# Broker messaging a client
		require 'db.php';
		require '../invest/admin/functions.php';

		$request = $_POST;

		$client = $request['clientId']??"";
		$broker = $request['brokerId']??"";
		$message = $request['message']??"";
		// $channels = implode(",", json_decode($request['channels']??array(), true));
		$channels = implode(", ", $request['channels']??"");
		$channels_array = (array)$request['channels']??"[]";

		if($client && $broker && $message){

			//getting client's phone


			//checking the contact of the user
			$userq = $investDb->query("SELECT * FROM clients WHERE id = \" $client\" ") or trigger_error($investDb->error);
			if($userq){
				$userdata = $userq->fetch_assoc();
				$userId = $userdata['userCode'];
				
				$clientUserData = user_details($userId);
				$phone = $clientUserData['phone'];
				$email = $clientUserData['email'];

				if(array_search('email', $channels_array) !== false){
					// Semail($email, "Uplus broker message", $message);
				}

				if($phone && array_search('sms', $channels_array) !== false){
					sendsms($phone, $message);
				}
			}

			//inserting a message
			$query = $investDb->query("INSERT INTO clients_messaging(userCode, messageBy, createdBy, message, channels) VALUES(\"$client\", \"$broker\", \"$broker\", \"$message\", \"$channels\") ") or trigger_error($db->error);
			$response = "Done";
		}else{
			$response = "Failed";
		}
		echo json_encode($response);			
	}

	function addStock()
	{
		//company adding stocks
		require 'db.php';
		require '../invest/admin/functions.php';

		$request = $_POST;
		$stockCompany = $request['company']??"";
		$number = $request['numberOfShares']??"";
		$unitPrice = $request['unitPrice']??"";
		$createdBy = $request['createdBy']??"";


		//Check the company associated woth user - #createdBy
		$companyq = $investDb->query("SELECT companyId FROM broker_user WHERE userCode = \"$createdBy\" AND archived = 'no' LIMIT 1 ") or trigger_error($investDb->error);
		if($companyq->num_rows){
			$companyData = $companyq->fetch_assoc();
			$brokerId = $companyData['companyId'];
			//insert the stocks
			$investDb->query("INSERT INTO broker_security(brokerId, companyId, sharesNumber, unitPrice, createdBy) VALUES($brokerId, \"$stockCompany\", \"$number\", \"$unitPrice\", \"$createdBy\") ") or trigger_error($investDb->error);
			$response = "Done";
		}else{
			$response = "Failed";

		}
		echo json_encode($response);
	}

	function listStocks()
	{
		//list stock prices with their stock people

		// require 'db.php';
		require_once '../invest/admin/db.php';
		require '../invest/admin/functions.php';


		global $investDb;
		$request = $_POST;	
		$userId = $request['userId']??""; //to checj the walet


		$sql = "SELECT B.companyId, B.id as securityId, B.brokerId, B.sharesNumber, B.unitPrice, B.createdDate, C.companyName,
			(SELECT companyName FROM company WHERE companyId = B.brokerId LIMIT 1) AS brokerName,
			COALESCE( (SELECT N.unitPrice FROM broker_security AS N WHERE N.id<securityId LIMIT 1), '0') AS prevPrice
			 FROM broker_security AS B JOIN company AS C ON C.companyId = B.companyId WHERE type ='stock' ORDER BY B.createdDate DESC";
		$query = $investDb->query($sql) or trigger_error($investDb->error);
		$companies = $companyDetails = array();

		
		
		while ($data = $query->fetch_assoc()) {
			$prevPriceDiv = $data['prevPrice']==0?1:$data['prevPrice']; //prevPrice for division omitting 0
			$change = round( ( ($data['unitPrice'] - $data['prevPrice'])/$data['unitPrice'])*100, 1);
			$compData = $cd = array(
						'unitPrice'=>$data['unitPrice'],
						'date'=>$data['createdDate'],
						'securityId'=>$data['securityId'],
						'prevPrice'=>$data['prevPrice'],
						// 'change'=>(string)( ( ($data['unitPrice'] - $data['prevPrice'])/$data['unitPrice'])*100),
						'change'=>  "$change",
					);
			if(isset($companies[$data['companyId']])){				
				//here we'll concatenate				
				$companies[$data['companyId']][] = $compData;
			}else{
				$companies[$data['companyId']][] = $compData;
			}

			//Packing company details
			if(!isset($companyDetails[$data['companyId']])){				
				//keep once only
				$companyDetails[$data['companyId']] = $data;
			}

		}

		//android format
		foreach ($companyDetails as $key => $data) {
			$ret[] = array(
				'stockName'=>$data['companyName'],
				'stockId'=>$data['companyId'],
				'brokerName'=>$data['brokerName'],
				'brokerId'=>$data['brokerId'],
				'data'=>$companies[$data['companyId']]
			);

			if($userId)
			{
				//add his balance
				$ret['walletBalance'] = (string)userWallet($userId);
			}
		}

		$response = $ret;		

		echo json_encode($response);
	}

	function purchase()
	{
		//user buying the shares
		require 'db.php';
		require '../invest/admin/functions.php';

		$request = $_POST;
		$stockId = $request['stockId']??""; //id of the stock the user is buying
		$userId = $request['userId']??""; //iWho's buying
		$quantity = $request['quantity']??""; //quantity

		if($stockId && $userId && $quantity){
			//checking price per share
			$shareQuery = $investDb->query("SELECT B.*, C.number AS remaining FROM broker_security as B JOIN broker_companies AS C ON C.brokerId = B.brokerId WHERE B.companyId = \"$stockId\" AND B.archived = 'no' ORDER BY createdDate DESC ") or trigger_error($investDb->error);
			if($shareQuery){
				$shares = $shareQuery->fetch_assoc();

				$totalAmt = $quantity*$shares['unitPrice'];

				//NOTE: we are not checking stock remaining now

				if($quantity<=$shares['remaining'] || 1){
					//here we can buy
					//todo: implement payment
					$investDb->query("INSERT INTO transactions(stockId, userCode, quantity, totalAmount, type, createdBy) VALUES(\"$stockId\", \"$userId\", \"$quantity\", \"$totalAmt\", \"buy\", \"$userId\") ") or trigger_error($investDb->error);
					$response = 'Done';
				}else{
					$response = 'Fail, insufficient stock';
				}
			}else{
				$response = "Fail, can't find stock";
			}			
		}else{
			$response = 'Fail, provide all required parameters';
		}
		$userId = $request['userId']??""; //id of the security the user is buying
		echo json_encode($response);
	}

	function actTransaction()
	{
		require_once '../invest/admin/db.php';
		require '../invest/admin/functions.php';

		$request = $_POST;

		//action on transactions
		$transId  = $request['transId'];
		$act  = $request['act'];
		$doneBy  = $request['doneBy'];

		//getting transaction data
		$transData = getTransaction($transId);
		$transType = $transData['type'];
		$transClient = $transData['userCode'];

		//checking the details of the customer
		$clientData = user_details($transClient);
		$clientName = $clientData['name'];		

		$stockInfo = stockInfo($transData['stockId']);
		$stockName = $stockInfo['stockName'];

		$query = $investDb->query("UPDATE transactions SET status = \"$act\", updatedBy = \"$doneBy\", updatedDate = NOW() WHERE id = \"$transId\" ") or trigger_error($investDb->error);
		if($query){

			//notifying the client
			$phone = $clientData['phone'];
			if($transType == 'sell'){
				if($act == 'approve'){
					$message = "Dear $clientName, Your request to sell $transData[quantity] shares of $stockName was approved, Your funds have been sent to your account successfully";
				}else{
					$message = "Dear $clientName, Your request to sell $transData[quantity] shares of $stockName was rejected.";
				}
			}else if($transType == 'buy'){
				if($act == 'approve'){
					$message = "Dear $clientName, Your request to buy $transData[quantity] shares of $stockName was approved, Thank you for using U_Invest";
				}else{
					$message = "Dear $clientName, Your request to buy $transData[quantity] shares of $stockName was rejected. Your funds  have been refunded successfully";
				}				
			}

			sendsms($phone, $message);

			echo json_encode("Done");
		}else{
			echo json_encode("Failed");
		}
	}

	function sellStocks()
	{
		//allows user to sell her stocks
		require 'db.php';
		require '../invest/admin/functions.php';
		$request = $_POST;

		$userId = $request['userId']??"";
		$quantity = $request['quantity']??""; //how much user wants to sell
		$stockId = $request['stockId']??"";

		//insert into transactions
		if($stockId && $userId && $quantity){
			//check if the user has the stocks in $stockId
			$check = $investDb->query("SELECT SUM(quantity) as quantity FROM transactions WHERE userCode = \"$userId\" AND stockId = \"$stockId\" AND archived = 'no' AND type = 'buy' ") or trigger_error("Failed"+$investDb->error);
			if($check->num_rows)
			{
				//check the number
				$cdata = $check->fetch_assoc();
				if($quantity <= $cdata['quantity'])
				{
					//Amount to be paid
					$totalAmt = latestStockPrice($stockId)*$quantity;

					//details about stock
					$stockq = $investDb->query("SELECT * FROM company WHERE companyId = '$stockId' LIMIT 1 ") or trigger_error($investDb->error);
					$stockData = $stockq->fetch_assoc(); 
					//Going to send message to the user
					$userData = user_details($userId);
					$message = "Dear $userData[name], you just sold your $quantity at ".number_format($totalAmt)." FRW shares of $stockData[companyName] and they're pending  for broker's approval";

					sendsms($userData['phone'], $message);

					//order can be placed
					$investDb->query("INSERT INTO transactions(stockId, userCode, quantity, totalAmount, type, createdBy) VALUES(\"$stockId\", \"$userId\", \"$quantity\", \"$totalAmt\", \"sell\", \"$userId\") ") or trigger_error($investDb->error);
					$response = 'Done';

				}else{
					$response = "Fail, insufficient shares";
				}
			}
			else
			{
				$response = 'Fail';

			}
		}else{
			$response = 'Fail';

		}
		echo json_encode($response);
	}

	function stocksTransactions()
	{
		//user sale and purchase histories
		require 'db.php';
		require '../invest/admin/functions.php';
		$request = $_POST;

		$user = $request['userId']??"";

		if($user){
			$query = $investDb->query("SELECT T.*, C.companyName FROM transactions T JOIN company C ON T.stockId = C.companyId WHERE userCode = \"$user\" ORDER BY T.createdDate DESC ") or trigger_error($investDb->error);

			$hist = array();
			while ($data = $query->fetch_assoc()) {
				$hist[] = array(
					'stockId'=>$data['stockId'],
					'stockName'=>$data['companyName'],
					'userId'=>$data['userCode'],
					'quantity'=>$data['quantity'],
					'totalAmount'=>$data['totalAmount'],
					'type'=>$data['type'],
					'status'=>$data['status'],
					'date'=>$data['createdDate']
				);
			}
		}
		echo json_encode($hist);
	}

	function stockTransactions()
	{
		//stock sale and purchase histories
		require 'db.php';
		require '../invest/admin/functions.php';
		$request = $_POST;

		$user = $request['userId']??"";
		$stock = $request['stockId']??"";

		if($user){
			$query = $investDb->query("SELECT T.*, C.companyName FROM transactions T JOIN company C ON T.stockId = C.companyId WHERE userCode = \"$user\" AND stockId = '$stock' ORDER BY T.createdDate DESC ") or trigger_error($investDb->error);

			$hist = array();
			while ($data = $query->fetch_assoc()) {
				$hist[] = array(
					'stockId'=>$data['stockId'],
					'stockName'=>$data['companyName'],
					'userId'=>$data['userCode'],
					'quantity'=>$data['quantity'],
					'totalAmount'=>$data['totalAmount'],
					'type'=>$data['type'],
					'status'=>$data['status'],
					'date'=>$data['createdDate']
				);
			}
		}
		echo json_encode($hist);
	}

	function userInvestmentSummary(){
		require 'db.php';
		require '../invest/admin/functions.php';
		$request = $_POST;
		$userId = $request['userId'];
		$data = userInvestProfile($userId);
		echo json_encode($data);		
	}
// END INVESTMENT


?>
