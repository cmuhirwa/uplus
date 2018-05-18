<?php
    include_once '../db.php';
    include_once 'functions.php';
    die("mind your way");
    $users = $db->query("SELECT * FROM sms.kubwimana");
    while ($data = $users->fetch_assoc()) {
        $phone = "0"+$data['phone'];
        // $message = "Umuryango wa Maj Gatera Jonas unejejwe no kubatumira mu nama y'ubukwe buzaba 2/6/2018 bw'umukobwa wabo Kagoyire F iba uyu munsi saa 17:00 ku Gisimenti Stella2.";
        // $message = "Umuryango wa Maj Gatera Jonas unejejwe no kubatumira mu nama y'ubukwe buzaba 2/6/2018 bw'umukobwa wabo Kagoyire uyu munsi saa 17:30 kuri GREEN CORNER haruguru CSS-Remera";
        // $message = "Inama y'ejo izaba saa 17:00 kuri GREEN CORNER haruguru ya CSC-Remera";
        $message = "Umuryango wa Kubwimana David ubatumiye munama ya 2 itegura ubukwe bwa Jado  buzaba kuri 2/6 ikazaba kuwa 5 hejuru yaho imodokazinjirira muri gare yo mu mugi 17h";

        if(sendsms($phone, $message, '', 'Ubukwe bwa Jado' )){
            echo "$phone<br />";
        };
    }
?>