<?php
    include_once '../db.php';
    include_once 'functions.php';
    // die("mind your way");
    $users = $db->query("SELECT * FROM uplus.saphani");
    while ($data = $users->fetch_assoc()) {
        $phone = "0"+$data['COL 2'];
        $message = "Umuryango wa Maj Gatera Jonas unejejwe no kubatumira mu nama y'ubukwe buzaba 2/6/2018 bw'umukobwa wabo Kagoyire F iba uyu munsi saa 17:00 ku Gisimenti Stella2.";
        $message = "Umuryango wa Maj Gatera Jonas unejejwe no kubatumira mu nama y'ubukwe buzaba 2/6/2018 bw'umukobwa wabo Kagoyire izaba ejo kuri GREEN CORNER haruguru CSC-Remera";
        if(sendsms($phone, $message, '', 'KAGOYIRE F' )){
            echo "$phone<br />";
        };
    }
?>