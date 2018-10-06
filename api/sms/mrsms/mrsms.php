 <?php

require_once './classes.php';
//use MRSMS\MRSMSAPI;
$api_key = 'Y211aGlyd2E6Y2xlbWVudDEyMw=='; // Step 3: Change the from number below. It can be a valid phone number or a String 

$from = 'UPLUS';
  // Step 4: the number we are sending to - Any phone number in international format

$destination = '+250784848236';
        //when you are sending sms use this linee 

$action='send-sms';
  // Step 5: Use this url(this our gateway url)
$url = 'https://mistasms.com/sms/api';
 // here you put  sms body

$sms = 'test message from Mr SMS';
   // unicode sms
$unicode = 1; //For Plain Message
$unicode = 0; //For Unicode Message
 // Create SMS Body for request

$sms_body = array(
    'api_key' => $api_key,
    'to'      => $destination,
    'from'    => $from,
    'sms'     => $sms,
    'unicode' => $unicode,
);


// Step 6: instantiate a new Mr SMS API request
$client = new MRSMSAPI();
// Step 7: Send SMS
$response = $client->send_sms($sms_body, $url);

print_r($response);

// Step 8: Get Response
$response=json_decode($response);

// Display a confirmation message on the screen
echo 'Message: '.$response->message;
//Step 9: Get your inbox
$get_inbox=$client->get_inbox($api_key,$url);
//Step 10: Get your account balance
$check_balance=$client->check_balance($api_key,$url);
