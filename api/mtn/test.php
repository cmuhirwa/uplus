<?php
	$curl_post_data = array(
		'fromfri'=>'0784762982',
		'fromfri'=>'0784848236',
		'amount'=>100,
		'externaltransactionid'=>21
	);
	$service_url = 'https://10.33.1.14:8052/mot/mm/debit';
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_USERPWD, "uplus.sp:Mtnecw@6530"); //Your credentials here
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

	$curl_res = curl_exec($curl);
	$response = json_decode($curl_res);
	curl_close($curl);

	var_dump($response);

?>