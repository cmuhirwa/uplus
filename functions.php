<?php
	// function sendNotification ($tokens, $message)
	// {
	// 	$url = 'https://fcm.googleapis.com/fcm/send';


	// 	//tokens should be array
	// 	if(!is_array($tokens)){
	// 		$tokens = array($tokens);
	// 	}

	// 	$fields = array(
	// 		 'registration_ids' => $tokens,
	// 		 'data' => array('message' => $notification)
	// 		);

	// 	$headers = array(
	// 		'Authorization:key = AIzaSyCVsbSeN2qkfDfYq-IwKrnt05M1uDuJxjg',
	// 		'Content-Type: application/json'
	// 		);

	// 	 $ch = curl_init();
	// 		 curl_setopt($ch, CURLOPT_URL, $url);
	// 		 curl_setopt($ch, CURLOPT_POST, true);
	// 		 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// 		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	// 		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// 		 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	// 		 $result = curl_exec($ch);           
	// 		 if ($result === FALSE) {
	// 				 die('Curl failed: ' . curl_error($ch));
	// 		 }
	// 		 curl_close($ch);
	// 		 return $result;
	// }

?>