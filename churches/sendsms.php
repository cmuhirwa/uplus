<?php
    include_once '../db.php';
    // die("Hello guys, take care");
    $users = $db->query("SELECT * FROM sms.inyatsi");
    set_time_limit(0);

    while ($data = $users->fetch_assoc()) {
        $phone = "25".$data['phone'];
        $message = "Mutoni Doreen anejejwe no kubatumira mu nama ya kabiri itegura ubukwe bwe iraba uyu munsi taliki ya 02/08/2018 kuri hilltop hotel saa 5:00PM, 0783669599.";
        
        if(sendsms($phone, $message)){
            echo "$phone<br />";
        };
    }
    function sendsms($phone, $message){
        $url = "http://rslr.connectbind.com:8080/bulksms/bulksms?username=infk-kiza&password=lab250&type=0&dlr=1&destination=$phone&source=MUTONI%20D&message=".rawurlencode($message);
        // Get cURL resource
        // keep api request log for debuggin
        // $f = fopen("logs/saphani.txt", 'a') or die("Unable to open file!");;
        // fwrite($f, json_encode($_POST)."\n$url\n");
        // fclose($f);
        // echo "$url";

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Ireebe service'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        var_dump($resp);
        return $resp;
    }
?>