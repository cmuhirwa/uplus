<?php
$url = "https://10.33.1.14:8052/mot/mm/debit";
$myXMLData_ = '<?xml version="1.0" encoding="UTF-8"?>
<ns0:debitrequest xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_0">
<fromfri>FRI:250784848236/MSISDN</fromfri>
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
$password = 'Mtnecw@6530';
$ch = curl_init();
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

            //CURLOPT_VERBOSE        => true,
            CURLOPT_URL => $url,
        );
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$myXMLData_");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "cache-control: no-cache",
            "content-type: application/xml",
			"password: " . "$password",
        ]);
	curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/api/mtn/certs/m3-ca-1.pem');
	//curl_setopt($curl, CURLOPT_SSLKEY, '/var/www/html/api/mtn/certs/privatekey_unc.pem');
	curl_setopt($ch, CURLOPT_SSLKEY, '/var/www/html/api/mtn/certs/cakey.pem');
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, 'uplus123');
	$output = curl_exec($ch);
	var_dump($output);
	var_dump(curl_error($ch));
?>

