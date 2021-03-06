<?php
	// include_once("db.php");

	$standard_date = "d F Y";
	$hostname = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";

	function total_users()
	{
		//returns all the users of uplus system
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
		$query = $investDb->query("SELECT *, companyName as stockName FROM company WHERE companyId = \"$stockId\" ") or trigger_error($investDb->error);
		return $query->fetch_assoc();
	}

	function checkGroupCSD($groupId){
		//checks the CSD of the group
		global $investDb;
		$query = $investDbs->query("SELECT csdAccount FROM clients WHERE groupCode = \"$groupId\" AND archived = 'no' ") or trigger_error($investDb->error);
		if($query->num_rows){
			$data = $query->fetch_assoc();
			return $data['csdAccount'];
		}else return false;
		
	}



	function checkGroup($groupId){
		//checks the investment info of the group
		global $investDb;
		$query = $investDb->query("SELECT * FROM clients WHERE groupCode = \"$groupId\" ") or trigger_error($investDb->error);
		if($query->num_rows){
			$data = $query->fetch_assoc();
			return $data;
		}else return false;
		
	}

	function checkClientUser($userId, $service = ''){
		//checks the investment info of the user of the service = invest or bank
		global $investDb;
		$sql  = "SELECT * FROM clients WHERE userCode = \"$userId\" AND service LIKE \"%$service%\" ";
		// echo "$sql";
		$query = $investDb->query($sql) or trigger_error($investDb->error);
		if($query->num_rows){
			$data = $query->fetch_assoc();
			return $data;
		}else return false;
		
	}
	function getUserCSD($userId){
		global $investDb;
		//returns the CSD account of the user

		$investData = checkClientUser($userId, 'invest');
		if(is_array($investData)){
			$csdAccount = $investData['csdAccount']??"";
		}else{
			$csdAccount = '';
		}
		return $csdAccount;
	}

	function getUserBankAccount($userId){
		global $investDb;
		//returns the Bank account of the user

		$investData = checkClientUser($userId, 'bank');
		if(is_array($investData)){
			$account = $investData['accountNumber']??"";
		}else{
			$account = '';
		}
		return $account;
	}

	function checkClient($clientId){
		//checks the investment info of the user
		global $investDb;
		$query = $investDb->query("SELECT * FROM clients WHERE id = \"$clientId\" LIMIT 1 ") or trigger_error($investDb->error);
		if($query->num_rows){
			$data = $query->fetch_assoc();
			return $data;
		}else return false;
		
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

		$sql = "SELECT T.*, U.name as clientName, C.companyName FROM transactions as T JOIN company as C ON T.stockId = C.companyId JOIN uplus.users AS U ON U.id = T.usercode WHERE T.userCode=\"$userId\" AND T.archived = 'no' ";
		$query = $investDb->query($sql) or trigger_error($investDb->error);
		$transactions = array();
		while ($data = $query->fetch_assoc()) {
			$transactions[] = $data;
		}
		return $transactions;
	}

	function userWallet($userId){
		global $investDb;

		// $sql = "SELECT COALESCE((SELECT SUM(totalAmount) FROM transactions WHERE status = 'approved' AND archived = 'no' AND type = 'buy' AND userCode = \"$userId\" ) - ( COALESCE(SELECT SUM(quantity) as shares FROM transactions WHERE status = 'approved' AND archived = 'no' AND type = 'sell' AND userCode = \"$userId\" ), 0) * (SELECT unitPrice FROM broker_security ORDER BY createdDate DESC LIMIT 1), 0) AS balance";
		$sql = "SELECT T.stockId, SUM(T.quantity) as purchases, COALESCE( (SELECT SUM(quantity) FROM transactions AS T1 WHERE T1.userCode = \"$userId\" AND T1.type = 'sell' AND T.status = 'approved'), 0) as sold FROM transactions as T WHERE T.userCode = \"$userId\" AND T.type = 'buy' GROUP BY T.stockId, T.userCode, T.status";
		$query = $investDb->query($sql) or trigger_error($investDb->error);

		// $modularQ = "SELECT SUM(sell.quantity) sellShare, SUM(buy.quantity) buyShare, buy.stockId buyStock, sell.stockId sellStock FROM transactions as sell JOIN transactions AS buy ON(sell.type = 'sell' AND buy.type = 'buy' ) WHERE sell.archived = 'no' AND buy.archived = 'no' AND sell.status = 'approved' AND buy.status = 'approved' GROUP BY sell.stockId, buy.stockId";

		$data = $query->fetch_all(MYSQLI_ASSOC);

		$totalBalance = 0; #initialize total wallet worth

		//looping through all, finding currrent worth of shares
		foreach ($data as $key => $stockBal) {
			$stockBalance = $stockBal['purchases'] - $stockBal['sold'];
			$balWorth = $stockBalance * latestStockPrice($stockBal['stockId']);
			$data[$key]['shares'] = $stockBalance;
			$data[$key]['balanceWorth'] = $balWorth;
			$totalBalance += $balWorth;
		}

		return $totalBalance;
	}

	function userInvestProfile($userId){
		//returns summary if the user's investment
		global $investDb;

		//get all transactions
		$transactions = userTransactions($userId);

		// $query = "SELECT * FROM transactions WHERE userId = \"$userId\" GROUP BY stockId "

		//keeping invested, sold
		$invested = $sales = 0;

		//storing company specific data
		$company = array();

		foreach ($transactions as $key => $transaction) {
			$amount = $transaction['totalAmount'];
			$type = $transaction['type'];
			$stockId = $transaction['stockId'];
			$status = $transaction['status'];

			//storing company
			if(empty($company[$stockId])){
				//initialize in case company aint
				$company[$stockId] = array('buy'=>0, 'sell'=>0);
			}

			if($transaction['status'] == 'approved'){
				if($type == 'buy'){
					$invested += $amount; 
				}else if($type == 'sell'){
					$sales += $amount; 
				}				

				$company[$stockId][$type] = ($company[$stockId][$type][$status]??0)+$amount;				
			}				
		}

		//Calculating profit
		foreach ($company as $stockId => $compData) {
			$company[$stockId]['profit'] = ($company[$stockId]['buy'] - $company[$stockId]['sell']);
		}
				

		$totalProfit = ($invested-$sales);
		//preparing output
		$ret = array('totalInvestment'=>$invested, 'totalSales'=>$sales, 'totalProfit'=>$totalProfit, 'stocks'=>$company);
		return $ret;
		
	}

	function brokerTransactions($brokerId){
		global $investDb;
		$query = $investDb->query("SELECT * FROM transactions WHERE brokerId = \"$brokerId\"  AND archived = 'no' ORDER BY createdDate DESC") or trigger_error($investDb->error);
		$trans = array();
		while ($data = $query->fetch_assoc()) {
			$trans[] = $data;
		}
		return $trans;
	}

	function brokerTransactionsSummary($brokerId){
		global $investDb;
		$query = $investDb->query("SELECT COUNT(*) as num, SUM(totalAmount) as amount FROM transactions WHERE archived = 'no'  ") or trigger_error($investDb->error);
		$data = $query->fetch_assoc();
		return $data;
	}

	function getTransaction($transId){
		//returns data on the transaction
		global $investDb;
		$transId = $investDb->real_escape_string($transId);
		$query = $investDb->query("SELECT * FROM transactions WHERE id = \"$transId\"") or trigger_error($investDb->error);
		$data = $query->fetch_assoc();
		return $data;
	}

	function listFeeds($memberId = 1)
	{
		//function to return the posts from $user
		global $db, $investDb;		
		$query = $db->query("SELECT F.*, F.id as fid, u.userImage as feedByImg, COALESCE(u.name, u.phone) as feedByName, (SELECT COUNT(*) FROM investments.feed_likes WHERE feedCode = F.id) as nlikes, (SELECT COUNT(*) FROM investments.feed_comments  WHERE feedCode = F.id) as ncomments, (SELECT COUNT(*) FROM investments.feed_likes WHERE feedCode = F.id AND userCode = '$memberId') as liked FROM investments.feeds as F JOIN uplus.users AS u ON u.id = F.createdBy WHERE archive = 'NO' OR ISNULL(archive) ORDER BY createdDate DESC") or trigger_error($db->error, E_USER_ERROR);

		$posts = array();

		while ($data = $query->fetch_assoc()) {
			$feedId = $data['fid'];
			//getting post attachments
			$attq = $investDb->query("SELECT imgUrl FROM investments.investmentimg WHERE investCode = $feedId") or trigger_error($investDb->error);

			$att = array();
			while ( $attData = $attq->fetch_assoc()) {
				$att[] = $attData['imgUrl'];
			}
			$liked = $data['liked']==0?"NO":"YES";
			$data["feedLikeStatus"] = $liked;
			$data['feedAttachments'] = $att;
			$data['video'] = "";
			$data['videoThumbnail'] = "";
			$data['createdDateText'] = textDiffDates(date(DATE_ATOM), $data['createdDate']);

			//checking images and videos
			$vidQ = $investDb->query("SELECT * FROM feed_videos WHERE feedCode = $feedId AND archived = 'no' ORDER BY createdDate DESC LIMIT 1 ") or trigger_error($investDb->error);


			if($vidQ && $vidQ->num_rows){
				$videoData = $vidQ->fetch_assoc();
				$data['video'] = $videoData['video'];
				$data['videoThumbnail'] = HOSTNAME.$videoData['thumbnail'];
			}
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

		$query = $investDb->query("SELECT T.*, U.name as clientName, C.companyName FROM transactions as T JOIN company as C ON T.stockId = C.companyId JOIN uplus.users AS U ON U.id = T.usercode WHERE T.type ='buy' ORDER BY createdDate DESC") or trigger_error($investDb->error);
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
			$currentPriceDate = $data['priceDate'];
			$currentPrice = $data['unitPrice'];
			//calculate the change
			$changeq = $investDb->query("SELECT * FROM broker_companies WHERE companyId = \"$stockId\" AND priceDate<'$currentPriceDate' LIMIT 1 ") or trigger_error($investDb->error);
			$changeData = $changeq->fetch_assoc();
			$prevPrice = $changeData['unitPrice'];
			//percentage
			$change = (($currentPrice-$prevPrice)*100/($prevPrice??$currentPrice));
			$change = round($change, 1);

			$data['change'] = $change;
			$data['prevPrice'] = $prevPrice;

			$hist[] = $data;
		}
		return $hist;

	}

	function stockPurchases($brokerId){
		//returns stock sales of the broker
		global $investDb;

		$query = $investDb->query("SELECT T.*, U.name as clientName, C.companyName FROM transactions as T JOIN company as C ON T.stockId = C.companyId JOIN uplus.users AS U ON U.id = T.usercode WHERE T.type ='sell' ORDER BY T.createdDate DESC") or trigger_error($investDb->error);
		$sales = array();

		while ($data = $query->fetch_assoc()) {
			$sales[] = $data;
		}

		return $sales;
	}

	function getForums(){
		//returns all the forums
		global $investDb, $hostname;

		$query = $investDb->query("SELECT * FROM forums WHERE archive = 'NO' ") or trigger_error($investDb->error);
		$forums = array();
		while ($data = $query->fetch_assoc()) {
			$data['logo'] = $hostname;
			$forums[] = $data;
		}
		return $forums;
	}

	function forumFeeds($forum, $memberId = '', $fromFeedId='')
	{

		//function to return the posts in the forum and if $fromFeedId is specified we start from there
		global $investDb;

		//defining fromfeedclause
		$feedq = 1;
		if($fromFeedId){
			$feedq = "feeds.id < $fromFeedId";
		}

		$sql = "SELECT feeds.*, feeds.id as fid, U.name as feedByName, (SELECT COUNT(*) FROM feed_likes WHERE feedCode = feeds.id) as nlikes, (SELECT COUNT(*) FROM feed_comments  WHERE feedCode = feeds.id) as ncomments, (SELECT COUNT(*) FROM investments.feed_likes WHERE feedCode = id AND userCode = '$memberId') as liked FROM feeds JOIN uplus.users U ON U.id = feeds.createdBy WHERE feeds.feedForumId= \"$forum\" AND feeds.archive = 'no' AND $feedq ORDER BY feeds.createdDate DESC";
		// echo "$sql";	

		$query = $investDb->query($sql) or trigger_error("Error retrieving ".$investDb->error, E_USER_ERROR);

		$posts = array();

		while ($data = $query->fetch_assoc()) {

			//curre feed id
			$feedId = $data['fid'];

			//getting post attachments
			$attq = $investDb->query("SELECT imgUrl FROM investmentimg WHERE investCode = $data[fid]") or trigger_error($investDb->error);

			$att = array();
			while ( $attData = $attq->fetch_assoc()) {
				$att[] = $attData['imgUrl'];
			}

			//Adding video
			$data['video'] = "";
			$data['videoThumbnail'] = "";

			//checking images and videos
			$vidQ = $investDb->query("SELECT * FROM feed_videos WHERE feedCode = $feedId AND archived = 'no' ORDER BY createdDate DESC LIMIT 1 ") or trigger_error($investDb->error);


			if($vidQ && $vidQ->num_rows){
				$videoData = $vidQ->fetch_assoc();
				$data['video'] = $videoData['video'];
				$data['videoThumbnail'] = HOSTNAME.$videoData['thumbnail'];
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
		global $investDb;
		$query = $investDb->query("SELECT * FROM forums WHERE id = \"$forumId\" ") or trigger_error($investDb->error);

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
		$password="uplus#hard";
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

			//tokens should be array
			if(!is_array($tokens)){
				$tokens = array($tokens);
			}

			//tokens should be array
			if(!is_array($message)){
				$message = array('message'=>$tokens);
			}

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
	function diffDates($date1, $date2, $format = false) 
	{
		$diff = date_diff( date_create($date1), date_create($date2));

		if ($format)
			return $diff->format($format);
		
		return array('y' => $diff->y,
				'm' => $diff->m,
				'd' => $diff->d,
				'h' => $diff->h,
				'i' => $diff->i,
				's' => $diff->s
			);	
	}
	function textDiffDates($date1, $date2, $format = false){
		//returns textual differents in dates
		$diff = (object)diffDates($date1, $date2, $format = false);
		$text = '';
		if($diff->y >0){
			$text = "$diff->y year".($diff->y>1?'s':'');
		}else if($diff->m >0){
			$text = "$diff->m month".($diff->m>1?'s':'');	
		}else if($diff->m >0){
			$text = "$diff->m month".($diff->m>1?'s':'');	
		}else if($diff->d >0){
			if($diff->m == 1){
				$text = "Yesterday";	
			}else{
				$text = "$diff->d day".($diff->d>1?'s':'');
			}
		}else if($diff->h >0){
			$text = "$diff->h hour".($diff->h>1?'s':'');
		}else if($diff->i >0){
			if($diff->i < 3){
				$text = 'Just now';
			}else{
				$text = "$diff->i mins";
			}
		}
		return $text;
	}
?>