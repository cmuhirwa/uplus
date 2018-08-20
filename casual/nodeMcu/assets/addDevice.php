<?php
if(isset($_POST["deviceName"])){
	$conn = mysqli_connect("localhost","root","","epiz_22468831_bazimya");
	$DeviceName = $_POST["deviceName"];
	$DeviceCode = $_POST["deviceCode"];
	$sql = "INSERT INTO `devices_list` (`deviceId`, `device_name`, `dateAdded`, `DeviceCode`) VALUES (NULL, '$DeviceName', CURRENT_TIMESTAMP, '$DeviceCode')";
	$res = mysqli_query($conn,$sql);
	if(mysqli_affected_rows($conn)){
		echo "added correctly ";?>
        <script type="text/javascript">
        	location.reload();
        </script>
		<?php
	}
}
?>
