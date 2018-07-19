<?php
  class SMS{
	function sendSMS($phone, $message, $senderId=''){
	  // Be sure to include the file you've just downloaded
	  require_once($_SERVER['DOCUMENT_ROOT'].'/scripts/AfricasTalkingGateway.php');
	  // Specify your authentication credentials
	  $username   = "cmuhirwa";
	  $apikey     = "3d123a56ae6684fa0a48904de4725e6746606d24b78e4c6a7e207cd61ef52406";
	  // Specify the numbers that you want to send to in a comma-separated list
	  // Please ensure you include the country code (+250 for Rwanda in this case)
	  // $recipients = "+250736YYYXXX,+250734XXXZZZ";
	  $recipients = "+$phone";

	  // Create a new instance of our awesome gateway class
	  $gateway    = new AfricasTalkingGateway($username, $apikey);
	  try 
	  { 
		// Thats it, hit send and we'll take care of the rest. 
		$results = $gateway->sendMessage($recipients, $message, 'uplus');
		return $results;
	  }
	  catch ( AfricasTalkingGatewayException $e )
	  {
		// echo "Encountered an error while sending: ".$e->getMessage();
		return false;
	  }

	}
  }
  $SMS =  new SMS();
?>