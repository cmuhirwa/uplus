<?php
include "../db.php";

$query = $db->query("SELECT * FROM users");
while ($data = $query->fetch_assoc()) {
	$phone = $data['phone'];
	if(strlen($phone) == 10){
		//updating
		$nphone = '25'.$phone;

		//updating
		$sql = "UPDATE users SET phone = '$nphone' WHERE id = ".$data['id'];
		echo "$sql<br />";
		$db->query($sql);
	}
}
