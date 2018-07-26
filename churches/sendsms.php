<?php
    include_once '../db.php';
    // include_once 'functions.php';
    // die("mind your way");
    $users = $db->query("SELECT * FROM sms.inyatsi WHERE number > 27");

    while ($data = $users->fetch_assoc()) {
        $phone = "25".$data['phone'];
        // $message = "Umuryango wa Maj Gatera Jonas unejejwe no kubatumira mu nama y'ubukwe buzaba 2/6/2018 bw'umukobwa wabo Kagoyire F iba uyu munsi saa 17:00 ku Gisimenti Stella2.";
        // $message = "Umuryango wa Maj Gatera Jonas unejejwe no kubatumira mu nama y'ubukwe buzaba 2/6/2018 bw'umukobwa wabo Kagoyire uyu munsi saa 17:30 kuri GREEN CORNER haruguru CSS-Remera";
        // $message = "Inama y'ejo izaba saa 17:00 kuri GREEN CORNER haruguru ya CSC-Remera";
        // $message = "Umuryango wa Kubwimana David ubatumiye munama ya 2 itegura ubukwe bwa Jado  buzaba kuri 2/6 ikazaba kuwa 5 hejuru yaho imodokazinjirira muri gare yo mu mugi 17h";
        $message = "Mutoni Doreen anejejwe no  kubatumira mu nama ya mbere itegura ubukwe bwe izaba ejo taliki ya 26/07/2018 kuri hilltop hotel saa 17:00, 0783669599.";

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