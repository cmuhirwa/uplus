<?php
// START INITIATE
	include ("db.php");

	//return JSON Content-Type
    header('Content-Type: application/json');

    //hostname for file referencing
    $hostname = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";

    //keep api request log for debuggin
   // $f = fopen("logs/invest.txt", 'a') or die("Unable to open file!");;
  //  fwrite($f, json_encode($_POST)."\n\n");
 //   fclose($f);

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
			echo 'Please read the API documentation';
		}
	}
	else
	{
		echo 'UPLUS API V02';
	}
// END INITIATE

// START FORUMS
	function listForums()
	{
		require('db.php');
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
				"forumIcon"		=> $forum['icon'],
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
				"feedBy"		=> $row['feedByName'],
				"feedByImg"		=> $row['feedByImg']??"",
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
        mysqli_close($eventDb);
        header('Content-Type: application/json');
		$feeds = json_encode($feeds);
		echo $feeds;
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
        $post_content = $request['feedContent']??"";

        //type of the post
        $type = $request['type']??"";

        //target forum
        $target_audience = $request['targetForum']??$request['feedId'];

        // title
        $title = $request['title']??"";

        //attachments link
        $attachments = json_decode($request['attachments']??"", true);

        //the type of person who posted - admin or member if empty it'll be elisa app
        $userType = $request['userType']??'member';        

        $sql = "INSERT INTO feeds(feedContent, createdBy, feedForumId) VALUES(\"$post_content\", \"$userId\", \"$target_audience\")";
        $query = $investDb->query($sql) or trigger_error($investDb->error);

        if($query){
            $feed_id = $investDb->insert_id;
            //checking sent attachments

            if(!empty($attachments)){
            	//already uploaded attachments
	            for($n=0; $n<count($attachments); $n++){
	                $att = $attachments[$n];
	                $sql = "INSERT INTO investmentimg(imgUrl, investCode) VALUES(\"$att\", $feed_id) ";
	                $investDb->query($sql) or trigger_error($investDb->error);
	            }
	        }else if(!empty($request['feedAttachments'])){


	        	//attachments from Android
	        	$attachments = $request['feedAttachments'];

	        	$attachments = trim($attachments, '{');
	        	$attachments = trim($attachments, '}');

	        	$attachments = explode(",", $attachments);
	        	
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
	        		die("Failed");
	        	}
	        }else if(!empty($_FILES) ){
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
		require('db.php');
		global $hostname;
		$request = $_POST;
		// /delete feeds
        $userId = $request['userId']??"";
        $feedId = $request['feedId']??"";

        //checking authority
        if(1){
        	if($user && $feed){
	            $sql = "UPDATE feeds SET archivedDate = NOW(), archivedBy = \"$user\", archive = 'YES', updatedDate = NOW(), updatedBy = \"$user\" WHERE id = \"$feed\"";
	            $query = $conn->query($sql) or trigger_error($conn->error);
	            $response = "Done";
	        }else{
	            $response = "Failed";
	        }
        }
        echo json_encode($response);
    }
// END FORUMS


// START INVESTMENT
	function requestCSD()
	{
		// user requesting CSD account
		require 'db.php';
		$request = $_POST;
		$title = $request['tilte']??'';

		$userId = $request['userId']??"";
		if($userId){
			//here we fetch details from app
			$query = $db->query("SELECT * FROM uplus.users WHERE id = \"$userId\" ");
			$userData = $query->fetch_assoc();

			$names = $userData['name'];
			$phone = $userData['phone']; 

		}else{
			$names = $request['names']??"";
			$phone = $request['phone']??"";
			$gender = $request['gender']??"";
		}

		$gender = $request['gender']??"";
		
		$dob = date("Y-m-d", strtotime($request['dateOfBirth']??""));
		$nationality = $request['nationality']??"";
		$NID = $request['NID']??"";
		$passport = $request['passport']??"";
		$country = $request['country']??"";
		$city = $request['city']??"";

		if($names && $phone && $gender && $dob && $nationality){
			$query = $investDb->query("INSERT INTO clients(names, dob, gender, telephone, NID, residentIn, nationality, country, city, status, statusOn) VALUES(\"$names\", \"$dob\", \"$gender\", \"$phone\", \"$NID\", \"$nationality\", \"$nationality\", 'Rwanda', 'Kigali', 'pending', NOW()) ") OR trigger_error($investDb->error);
			$response = 'Done';
		}else{
			$response =  "Failed";
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

				if(empty($groupInvestData) || $groupInvestData['status'] == 'declined' ){
					//here we can request new CSD
					$query = $investDb->query("INSERT INTO clients(groupCode, clientType, country, nationality) VALUES(\"$groupId\", 'group', \"$country\", \"$country\")") or trigger_error($investDb->error);
					if ($query) {
						$response = "Done";
					}else{
						$response = "Fail";
					}
				}else{
					$response = "Fail";
				}
			}else{
				$response = "Fail";
			}
		}else{
			$response = "Fail";
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

		$csd = $request['CSDAccount']??"";
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

					$clientName = $userData['names'];
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
				$response = "Done";
			}else{
				$response = "Failed";
			}
		}else{
			$response = "Failed";
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
				$phone = $userdata['telephone'];

				if(array_search('email', $channels_array) !== false){
					$sql = "SELECT * FROM uplus.users WHERE phone = \"$phone\" ";
					$userq = $investDb->query($sql) or trigger_error($investDb->error);
					$userqdata = $userq->fetch_assoc();
					// Semail($userqdata['email'], "Uplus broker message", $message);
				}

				if($userdata['telephone'] && array_search('sms', $channels_array) !== false){
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
			 FROM broker_security AS B JOIN company AS C ON C.companyId = B.companyId WHERE type ='stock' ORDER BY B.createdDate";
		$query = $investDb->query($sql) or trigger_error($investDb->error);
		$companies = $companyDetails = array();

		
		
		while ($data = $query->fetch_assoc()) {
			$prevPriceDiv = $data['prevPrice']==0?1:$data['prevPrice']; //prevPrice for division omitting 0
			$compData = $cd = array(
						'unitPrice'=>$data['unitPrice'],
						'date'=>$data['createdDate'],
						'securityId'=>$data['securityId'],
						'prevPrice'=>$data['prevPrice'],
						// 'change'=>(string)( ( ($data['unitPrice'] - $data['prevPrice'])/$data['unitPrice'])*100),
						'change'=>( ( ($data['unitPrice'] - $data['prevPrice'])/$data['unitPrice'])*100),
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
