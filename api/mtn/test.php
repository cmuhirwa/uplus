<?php
	$cli = new SoapClient('https://10.33.1.14:8052/mot/mm/debit');
	var_dump($cli);
	$curl_post_data = '<?xml version="1.0" encoding="UTF-8"?>
<ns0:debitrequest xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_0">
<fromfri>FRI:250788315891/MSISDN</fromfri>
<tofri>FRI:uplus.sp/USER</tofri>
<amount>
<amount>100</amount>
<currency>RWF</currency>
</amount>
<externaltransactionid>345678IUN</externaltransactionid>
<frommessage/>
<tomessage/>
<referenceid>345678IUN</referenceid>
</ns0:debitrequest>';
	$service_url = 'https://10.33.1.14:8052/mot/mm/debit';
	$curl = curl_init($service_url);
	curl_setopt ($curl, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
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