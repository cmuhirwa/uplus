<?php
$conn = mysqli_connect("localhost","root","","epiz_22468831_bazimya");
if(isset($_GET["ip_address"])){
      $ip_address = $_GET["ip_address"];
      $deviceCode = $_GET["deviceCode"];
          $sql = "INSERT INTO `devices_network` (`deviceId`, `device_IP_address`,`deviceCode`, `date`) VALUES (NULL, '$ip_address','$deviceCode', CURRENT_TIMESTAMP)";
          $res = mysqli_query($conn,$sql);
          $returnId = mysqli_insert_id($conn);
      if(mysqli_affected_rows($conn))
      		echo "device connected with id: ".$returnId;
      else echo "there was a problem while connecting to the system";
} 
else echo "not set";
?>