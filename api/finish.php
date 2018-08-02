<?php
include 'db.php';
$groupId = "196";

/*
$invitorId = "1";
for($i=1; $i <= 20; $i++)
{
	$invitedId  = Rand(955, 995);
	$sql = $db->query("SELECT phone FROM users WHERE id='$invitedId'");
	$row = mysqli_fetch_array($sql);
	$invitedPhone = $row['phone'];
	// CHECK IF THE USER IS ALREADY IN THE GROUP
	$sql = $db->query("SELECT * FROM groupuser WHERE groupId ='$groupId' AND userId='$invitedId'");
	$checkExits = mysqli_num_rows($sql);
	if($checkExits > 0)
	{
		// CHECK IF THE USER DID LEAVE BEFORE
		$sql1 = $db->query("SELECT * FROM groupuser WHERE (groupId ='$groupId' AND userId='$invitedId') AND archive = 'YES'");
		$checkExits1 = mysqli_num_rows($sql1);
		if($checkExits1 > 0)
		{
			// BRING THE USER BACK IN THE GROUP
			$sql = $db->query("UPDATE groupuser SET archive = null WHERE groupId ='$groupId' AND userId='$invitedId'");
			// CHECK IF THE LIST OF TREASURERS IS NOT FULL AND ADD HIM
			$sqlList = $db->query("SELECT * FROM groupuser WHERE groupId = '$groupId' AND type = 'Group treasurer'");
			if(mysqli_num_rows($sqlList) <= 2)
			{
				// THERE IS SOME PLACE FOR YOU
				$sql = $db->query("UPDATE groupuser SET type = 'Group treasurer' WHERE groupId ='$groupId' AND userId='$invitedId'");
				echo 'Became treasurer';
			}
			echo 'Member '.$invitedPhone.', is brought back in the group </hr>';
		}
		else
		{
			echo 'Member '.$invitedPhone.', is already in the group </hr>';
		}
	}
	else
	{
		// PREPARE MEMBER TYPE
		$getMemberType= $db->query("SELECT * FROM groupuser WHERE groupId='$groupId' AND type = 'Group treasurer'");
		$countTres = mysqli_num_rows($getMemberType);
		if($countTres >= 3)
		{
			$memberType = '';
		}
		else
		{
			$memberType = 'Group treasurer';
		}
		
		// ADD MEMBER FOR THE FIRST TIME IN THIS GROUP
		$sql = $db->query("INSERT INTO groupuser (joined, groupId, userId, type, createdBy, createdDate, updatedBy, updatedDate) 
			VALUES ('yes','$groupId','$invitedId','$memberType','$invitorId', now(), '$invitorId', now())")or die(mysqli_error($db));

		if($db)
		{
			
				echo 'Member with '.$invitedPhone.' is added </hr>';
			
		}
		else
		{
			'The user is not invited </hr>';
		}
	}
}
//$invitedId  = Rand(955, 995);
/*/


$status = "Successfull";

for($i=1; $i <= 23; $i++)
{
	$memberId  = Rand(955, 995);// Rand(939, 945); //Rand(895, 920);
	//$memberId  = Rand(919, 927);
	$int= mt_rand(1521379500, 1521379500);
	$transaction_date = date("Y-m-d H:i:s",$int);
	$amount = Rand(3000, 5000);
	$amount	= floor($amount/1000)*1000; 
//echo $amount;
	$sql = $db->query("SELECT phone FROM users WHERE id='$memberId'");
	$row = mysqli_fetch_array($sql);
	$fromPhone = $row['phone'];
	
	$sql = $outCon->query("SELECT * FROM grouptransactions WHERE memberId='$memberId' AND groupId='$groupId'");
	$checkExits = mysqli_num_rows($sql);
	if($checkExits > 0)
	{
		echo "- Already: ".$fromPhone." </br>";
	}
	else 
	{
		$outCon->query("INSERT INTO grouptransactions(memberId, groupId, amount, fromPhone, bankId, operation, transaction_date, status) 
		VALUES ('$memberId', '$groupId', '$amount', '$fromPhone', '1', 'DEBIT', '$transaction_date', '$status')") or die(mysqli_error($outCon));
		if($outCon) 
		{
			echo"-> Done: ".$amount." From ".$fromPhone."</br>";
		}
	}
}

?>