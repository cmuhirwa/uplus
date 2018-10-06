 <?php


function mrsms($destination, $sms)
{
  require_once './classes.php';
  $unicode = 1;$unicode = 0;
  $sms_body = array(
      'api_key' => 'Y211aGlyd2E6Y2xlbWVudDEyMw==',
      'to'      => $destination,
      'from'    => 'UPLUS',
      'sms'     => $sms,
      'unicode' => $unicode,
  );
  $client = new MRSMSAPI();
  $response = $client->send_sms($sms_body, 'https://mistasms.com/sms/api');
}

mrsms('250784848236', 'test sms');