<?php
	header("Content_type:Application-json");
	$url = "https://www.0.freebasics.com/https/www.edorica.com/schools/academie-de-la-salle";
	$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	    'User-Agent:Mozilla/5.0 (Linux; U; Android 4.0.2; en-us; Galaxy Nexus Build/ICL53F) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
	    ));
		// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
	echo "string $resp";

?>