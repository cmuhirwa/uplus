<?php

$conn = mysqli_connect("localhost","clement","clement123","epiz_22468831_bazimya");
if(isset($_GET["hh"])){
      $data = $_GET["hh"];
      $sql = "INSERT INTO `epiz_22468831_bazimya`.`datas` (`dat_id`, `dat_`) VALUES (NULL, '$data')";
      $res = mysqli_query($conn,$sql);
} else {
        echo " not set";
         }
?>