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
	  $recipients = $phone;

	  // Create a new instance of our awesome gateway class
	  $gateway    = new AfricasTalkingGateway($username, $apikey);
	  /*************************************************************************************
		NOTE: If connecting to the sandbox:
		1. Use "sandbox" as the username
		2. Use the apiKey generated from your sandbox application
		   https://account.africastalking.com/apps/sandbox/settings/key
		3. Add the "sandbox" flag to the constructor
		$gateway  = new AfricasTalkingGateway($username, $apiKey, "sandbox");
	  **************************************************************************************/
	  // Any gateway error will be captured by our custom Exception class below, 
	  // so wrap the call in a try-catch block
	  try 
	  { 
		// Thats it, hit send and we'll take care of the rest. 
		$results = $gateway->sendMessage($recipients, $message, 'Uplus');
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