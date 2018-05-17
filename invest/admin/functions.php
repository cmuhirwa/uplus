<?php
	// include_once("db.php");

	$standard_date = "d F Y";

	function total_users()
	{
		//returns all the users of church system
		global $db;

		$query = $db->query("SELECT COUNT(*) as count FROM uplus.users") or trigger_error($db->error);
		return $query->fetch_assoc()['count'];
	}

	function forum_users($forumId)
	{
		//function to return all the users of the forum
		global $investDb;
		$forumId = $investDb->real_escape_string($forumId);
		$query = $investDb->query("SELECT * FROM forumuser WHERE forumCode = \"$forumId\" and archive = 'NO' ") or trigger_error($investDb->error);

		$users = array();
		while ($data = $query->fetch_assoc()) {
			$users[] = $data;
		}
		
		return $users;
	}

	function forumn_non_users($forumId)
	{
		//function to return all the users of the forum
		global $investDb;
		$forumId = $investDb->real_escape_string($forumId);
		$query = $investDb->query("SELECT * FROM users WHERE id NOT IN (SELECT userCode FROM forumuser WHERE forumCode = \"$forumId\" and archive = 'NO') ") or trigger_error($investDb->error);

		$users = array();
		while ($data = $query->fetch_assoc()) {
			$users[] = $data;
		}
		
		return $users;
	}


	function n_forum_users($forumId)
	{
		//function to return number of the users in forum
		global $investDb;
		$forumId = $investDb->real_escape_string($forumId);
		$query = $investDb->query("SELECT COUNT(*) as num FROM forumuser WHERE forumCode = \"$forumId\" and archive = 'NO' LIMIT 1 ") or trigger_error($investDb->error);
		$data = $query->fetch_assoc();
		$n_user = $data['num'];
		
		return $n_user;
	}

	function user_details($userid)
	{
		//Function to get user's details
		global $db;
		$user = $db->query("SELECT * FROM uplus.users WHERE id = \"$userid\" LIMIT 1 ") or trigger_error("Errror getting user's details $db->error");

		$user = $user->fetch_assoc();
		return $user;
	}

	function staff_details($staff){
		//returns staff
		global $investDb;
		return user_details($staff);
	}


	function clean_string($string)
	{
		$string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	 }

	function getFeeds($user)
	{
		//function to return the posts from $user
		global $db;
		$query = $db->query("SELECT *, feeds.id as fid, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = feeds.id) as nlikes, (SELECT COUNT(*) FROM feed_comments  WHERE feedCode = feeds.id) as ncomments FROM feeds JOIN users ON feeds.createdBy = users.Id  WHERE users.id = \"$user\" ORDER BY createdDate DESC ") or trigger_error("sdsd".$db->error, E_USER_ERROR);

		$posts = array();

		while ($data = $query->fetch_assoc()) {

			//getting post attachments
			$attq = $db->query("SELECT imgUrl FROM investmentimg WHERE investCode = $data[fid]") or trigger_error($investDb->error);

			$att = array();
			while ( $attData = $attq->fetch_assoc()) {
				$att[] = $attData['imgUrl'];
			}

			$data['feedAttachments'] = $att;

			$posts[] = $data;
		}
		return $posts;
	}

	// function brokerStocks($brokerId)
	// {
	// 	//returns the company that the broker works with a broker is a company with $brokerId
	// 	global $investDb;
	// 	$query = $investDb->query("SELECT * FROM broker_companies as B WHERE B.brokerId = $brokerId") or trigger_error($investDb->error);
	// 	$companies = array();
	// 	while ($data = $query->fetch_assoc()) {
	// 		$companies[] = $data;
	// 	}
	// 	return $companies;
	// }

	function brokerStocksSummary($brokerId)
	{
		//returns the company that the broker works with a broker is a company with $brokerId
		global $investDb;
		
		$sql = "SELECT companyId FROM `broker_security` GROUP BY `companyId`";
		// echo "$sql";
		$query = $investDb->query($sql) or trigger_error($investDb->error);
		$companies = array();
		while ($row = $query->fetch_assoc()) {
			$companyId = $row['companyId'];
			$sql = $investDb->query("SELECT B.unitPrice, C.companyName, B.companyId, B.createdBy 
						FROM broker_security B
						INNER JOIN company C 
						ON C.companyId = B.companyId
						WHERE B.companyId = '$companyId' 
						ORDER BY B.id DESC LIMIT 2") or trigger_error($investDb->error);

			$data = mysqli_fetch_array($sql);
			$prevData = mysqli_fetch_array($sql);
			$data['prevPrice'] = $prevData['unitPrice'];
			$change = $data['unitPrice'] - $data['prevPrice'];
			$data['change'] = ($change * 100)/ ($data['prevPrice']??$change);
			$companies[] = $data;
		}
		return $companies;
	}

	function getStockCompanies()
	{
		//returns the company that the broker works with a broker is a company with $brokerId
		global $investDb;
		$query = $investDb->query("SELECT * FROM company WHERE type ='stock' ") or trigger_error($investDb->error);
		$companies = array();
		while ($data = $query->fetch_assoc()) {
			$companies[] = $data;
		}
		return $companies;
	}

	function stockInfo($stockId){
		//returns the detals of stock
		global $investDb;
		$query = $investDb->query("SELECT * FROM company WHERE companyId = \"$stockId\" ") or trigger_error($investDb->error);
		return $query->fetch_assoc();
	}

	function timeStockPrice($stockId, $date)
	{
		# Stock price at $date moment
		global $investDb;

		$date = date('Y-m-d h:i:s', strtotime($date));

		$sql = "SELECT unitPrice FROM broker_security WHERE companyId = \"$stockId\" AND createdDate>= '$date' LIMIT 1 ";
		// echo "$sql";
		$query = $investDb->query($sql) or trigger_error($investDb->error);

		$data = $query->fetch_assoc();
		return $data['unitPrice']??0;
	}

	function latestStockPrice($stockId)
	{
		# Stock price at $date moment
		global $investDb;

		$sql = "SELECT unitPrice FROM broker_security WHERE companyId = \"$stockId\" ORDER BY createdDate DESC LIMIT 1 ";
		$query = $investDb->query($sql) or trigger_error($investDb->error);

		$data = $query->fetch_assoc();
		return $data['unitPrice']??0;
	}

	function getStocks()
	{
		#Lists all the stocks
		global $investDb;
		$query = $investDb->query("SELECT B.*, C.companyName FROM broker_security AS B JOIN company AS C ON C.companyId = B.brokerId WHERE type ='stock' ") or trigger_error($investDb->error);
		$companies = array();
		while ($data = $query->fetch_assoc()) {
			$companies[] = $data;
		}
		return $companies;
	}

	function userTransactions($userId){
		global $investDb;

		$query = $investDb->query("SELECT T.*, U.name as clientName, C.companyName FROM transactions as T JOIN company as C ON T.stockId = C.companyId JOIN uplus.users AS U ON U.id = T.usercode WHERE T.userCode=\"$userId\" AND T.archived = 'no' ") or trigger_error($investDb->error);
		$transactions = array();
		while ($data = $query->fetch_assoc()) {
			$transactions[] = $data;
		}
		return $transactions;
	}

	function listFeeds($memberId='')
	{
		//function to return the posts from $user
		global $db;		
		$query = $db->query("SELECT F.*, F.id as fid, u.userImage as feedByImg, COALESCE(u.name, u.phone) as feedByName, (SELECT COUNT(*) FROM investments.feed_likes WHERE feedCode = F.id) as nlikes, (SELECT COUNT(*) FROM investments.feed_comments  WHERE feedCode = F.id) as ncomments, (SELECT COUNT(*) FROM investments.feed_likes WHERE feedCode = F.id AND userCode = '$memberId') as liked FROM investments.feeds as F JOIN uplus.users AS u ON u.id = F.createdBy WHERE archive = 'NO' OR ISNULL(archive) ORDER BY createdDate DESC") or trigger_error($db->error, E_USER_ERROR);

		$posts = array();

		while ($data = $query->fetch_assoc()) {

			//getting post attachments
			$attq = $db->query("SELECT imgUrl FROM investments.investmentimg WHERE investCode = $data[fid]") or trigger_error($db->error);

			$att = array();
			while ( $attData = $attq->fetch_assoc()) {
				$att[] = $attData['imgUrl'];
			}
			$liked = $data['liked']==0?"NO":"YES";
			$data["feedLikeStatus"] = $liked;
			$data['feedAttachments'] = $att;

			$posts[] = $data;
		}
		return $posts;
	}

	function brokerMessages($broker, $client)
	{
		//return s messages between broker to $client
		global $investDb;
		$query = $investDb->query("SELECT * FROM clients_messaging WHERE userCode = \"$client\" AND messageBy = \"$broker\" ORDER BY createdDate DESC ") or trigger_error($investDb->error);
		$messages = array();
		while ($data = $query->fetch_assoc()) {
			$messages[] = $data;
		}
		return $messages;
	}

	function stockSales($brokerId){
		//returns stock sales of the broker
		global $investDb;

		$query = $investDb->query("SELECT T.*, U.name as clientName, C.companyName FROM transactions as T JOIN company as C ON T.stockId = C.companyId JOIN uplus.users AS U ON U.id = T.usercode WHERE T.type ='buy' ") or trigger_error($investDb->error);
		$sales = array();

		while ($data = $query->fetch_assoc()) {
			$sales[] = $data;
		}

		return $sales;
	}

	function stockHistory($stockId)
	{
		global $investDb;
		$query = $investDb->query("SELECT * FROM broker_companies WHERE companyId = \"$stockId\" AND archived = 'no' ") or trigger_error($investDb->error);
		$hist = array();
		while ($data = $query->fetch_assoc()) {
			$hist[] = $data;
		}
		return $hist;

	}
	
	function stockPurchases($brokerId){
		//returns stock sales of the broker
		global $investDb;

		$query = $investDb->query("SELECT T.*, U.name as clientName, C.companyName FROM transactions as T JOIN company as C ON T.stockId = C.companyId JOIN uplus.users AS U ON U.id = T.usercode WHERE T.type ='sell' ") or trigger_error($investDb->error);
		$sales = array();

		while ($data = $query->fetch_assoc()) {
			$sales[] = $data;
		}

		return $sales;
	}

	function getForums(){
		//returns all the forums
		global $investDb;

		$query = $investDb->query("SELECT * FROM forums WHERE archive = 'NO' ") or trigger_error($investDb->error);
		$forums = array();
		while ($data = $query->fetch_assoc()) {
			$forums[] = $data;
		}
		return $forums;
	}

	function forumFeeds($forum)
	{
		//function to return the posts in the forum
		global $db;
		$query = $db->query("SELECT *, feeds.id as fid, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = feeds.id) as nlikes, (SELECT COUNT(*) FROM feed_comments  WHERE feedCode = feeds.id) as ncomments FROM feeds WHERE feeds.feedForumId= \"$forum\" ORDER BY createdDate DESC ") or trigger_error("sdsd".$db->error, E_USER_ERROR);

		$posts = array();

		while ($data = $query->fetch_assoc()) {

			//getting post attachments
			$attq = $db->query("SELECT imgUrl FROM investmentimg WHERE investCode = $data[fid]") or trigger_error($investDb->error);

			$att = array();
			while ( $attData = $attq->fetch_assoc()) {
				$att[] = $attData['imgUrl'];
			}

			$data['feedAttachments'] = $att;

			$posts[] = $data;
		}
		return $posts;
	}

	function feedComments($feedId)
	{
		//returns the comments on the feed
		global $investDb;

		$query = $investDb->query("SELECT C.*, U.name as commentByName, U.userImage as commentByImg FROM feed_comments as C JOIN uplus.users as U ON C.userCode = U.id WHERE C.feedCode = \"$feedId\" ORDER BY commentDatetime DESC ") or trigger_error($investDb->error);
		$comments = array();

		while ($data = $query->fetch_assoc()) {
			$comments[] = $data;
		}

		return $comments;
	}

	function getForum($forumId)
	{
		//returns forum data
		global $db;
		$query = $db->query("SELECT * FROM forums WHERE id = \"$forumId\" ") or trigger_error($db->error);

		return $query->fetch_assoc();
	}

	function sendsms($phone, $message, $subject="", $smsName="Uplus")
	{
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


	function Semail($email, $subject, $body, $header='')
	{
			require_once 'mailer/PHPMailerAutoload.php';
			$email = "info@edorica.com";
			$server = "mail.edorica.com:465";
			$headers  = $header.= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;

			$mail->smtpdbect(
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