<!DOCTYPE html>
<?php 
$conn = mysqli_connect("localhost","root","","epiz_22468831_bazimya");
?>
<html>
<title> Attendance Sample </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script type="text/javascript" src="js/jquery.js"></script>
<body>

<div class="w3-container" style="text-align: center;">
  <div class="w3-card w3-border-top w3-border-bottom w3-border-blue">
    <?php
      $sql = "SELECT * FROM `devices_list`";
      $res = mysqli_query($conn,$sql);
      $nums = mysqli_num_rows($res);
    ?>
    <h2>Lists of available Devices <span class="w3-badge"><?php echo $nums ?></span></h2>
    <button class="w3-button w3-xlarge w3-circle w3-teal addAdevice">+</button>
    <table class="w3-table-all">
      <thead>
        <tr class="w3-blue">
          <th> Device Name </th>
          <th> Device Codes </th>
          <th> Actions </th>
        </tr>
      </thead>
      <?php
         while($rows = mysqli_fetch_array($res,MYSQLI_BOTH)){
      ?>
      <tr>
        <td><?php echo $rows["device_name"]?></td>
        <td><?php echo $rows["DeviceCode"]?></td>
        <td><button class="w3-button w3-black w3-tiny"> Attendances </button>
            <button class="w3-button w3-black w3-tiny"> Registration </button>
            <button class="w3-button w3-teal w3-tiny"> Edit </button>
            <button class="w3-button w3-red w3-tiny"> Delete </button>
        </td>
      </tr>
      <?php
         }
      ?>
    </table>
    <button class="w3-button w3-xlarge w3-circle w3-teal addAdevice">+</button>
  </div>
  <div id="addAdeviceDiv" class="w3-teal" style="width: 50%; margin: auto;">
  </div>
</div>
<script type="text/javascript">
  $(".addAdevice").click( function(xxx){
    xxx.preventDefault();
    $("#addAdeviceDiv").load("assets/addDevice.html");
  })
</script>
</body>
</html>