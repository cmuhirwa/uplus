<?php
if ($_SERVER["REQUEST_METHOD"]=="POST") 
{
	if(isset($_POST['action']))
	{
		$_POST['action']();
	}
	else
	{
		echo 'Please Read the API documentation';
	}
}
else
{
	echo 'UPLUS API V01';
}

function listGroups()
{
	include("../db.php");
	$sqlgroups = $db->query("SELECT * FROM groups WHERE archive is null ORDER BY id DESC");
	$groups = array();
	WHILE($group = mysqli_fetch_array($sqlgroups))
	{
		$groups[] = array(
		   "groupId"        => mysqli_real_escape_string($db, $group['id']),
		   "adminId"        => mysqli_real_escape_string($db, $group['adminId']),
		   "groupName"      => mysqli_real_escape_string($db, $group['groupName']),
		   "groupDesc"      => mysqli_real_escape_string($db, $group['groupDesc']),
		   "groupStory"     => mysqli_real_escape_string($db, $group['groupStory']),
		   "targetAmount"   => mysqli_real_escape_string($db, $group['targetAmount']),
		   "perPerson"      => mysqli_real_escape_string($db, $group['perPerson']),
		   "expirationDate" => mysqli_real_escape_string($db, $group['expirationDate']),
		   "likes"          => mysqli_real_escape_string($db, $group['likes']),
		   "groupImage"     => 'http://www.uplus.rw/temp/group'.$group['id'].'.jpeg'

		);

	}

	foreach ($groups as $i => $group) {
	$gAdminId = $groups[$i]['adminId'];
		$users = array();
		$sqlusers = $db->query("SELECT * FROM users WHERE id = '$gAdminId'");
		WHILE($user = mysqli_fetch_array($sqlusers))
		{
			$users[] = array(
				"adminName" => $user['name'],
				"adminPhone" => $user['phone']
				 );
		}
		$groups[$i]['groupAdmin'] = $users;
	}
	unset($groups[$i]['adminId']);

	header('Content-Type: application/json');

	$groups = json_encode($groups);

	echo '{ "groups" : '.$groups.' }';
}

function createGroup()
{
	require('../db.php');
	$groupName			= mysqli_real_escape_string($db, $_POST['groupName']);
	$groupTargetType	= mysqli_real_escape_string($db, $_POST['groupTargetType']);
	$targetAmount		= mysqli_real_escape_string($db, $_POST['targetAmount']);
	$perPersonType		= mysqli_real_escape_string($db, $_POST['perPersonType']);
	$perPerson			= mysqli_real_escape_string($db, $_POST['perPerson']);
	$adminId			= mysqli_real_escape_string($db, $_POST['adminId']);
	$adminPhone			= mysqli_real_escape_string($db, $_POST['adminPhone']);
	$accountNumber		= mysqli_real_escape_string($db, $_POST['accountNumber']);
	$bankId				= mysqli_real_escape_string($db, $_POST['bankId']);
	
	$db->query("INSERT INTO groups
		(groupName, adminId, adminPhone, 
		 targetAmount, perPerson, createdDate,
		 createdBy, state, groupTargetType, perPersonType)
		VALUES('$groupName',$adminId,'$adminPhone',
			$targetAmount,'$perPerson',now(),
			'$adminId','private','$groupTargetType','$perPersonType')") or die (mysqli_error());
	
	if($db)
	{
		$sqlid = $db->query("SELECT id FROM groups ORDER BY id DESC LIMIT 1") or die (mysqli_error());
		$rowid = mysqli_fetch_array($sqlid);
		$lastid = $rowid['id'];
		$outCon->query("INSERT INTO `groups`(`groupId`, `accountNumber`, `bankId`)
		VALUES('$lastid','$accountNumber','$bankId')")or die(mysqli_error());
		
		if($outCon)
		{
			$db->query("INSERT INTO groupuser
			(`joined`, `groupId`, `userId`,`createdBy`, `createdDate`)
			VALUES('yes','$lastid','$adminId','$adminId', now())")or die(mysqli_error());
			if($db)
			{
				listGroups();
			}
			else
			{
				echo 'The user not joined';
			}
		}
		else
		{
			echo 'money part not created';
		}
	}
	else
	{
		'group not added';
	}
}

function modifyGroup()
{
	require('../db.php');
	$groupName			= mysqli_real_escape_string($db, $_POST['groupName']);
	$groupTargetType	= mysqli_real_escape_string($db, $_POST['groupTargetType']);
	$targetAmount		= mysqli_real_escape_string($db, $_POST['targetAmount']);
	$perPersonType		= mysqli_real_escape_string($db, $_POST['perPersonType']);
	$perPerson			= mysqli_real_escape_string($db, $_POST['perPerson']);
	$adminId			= mysqli_real_escape_string($db, $_POST['adminId']);
	$adminPhone			= mysqli_real_escape_string($db, $_POST['adminPhone']);
	$accountNumber		= mysqli_real_escape_string($db, $_POST['accountNumber']);
	$bankId				= mysqli_real_escape_string($db, $_POST['bankId']);
	$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
	$state				= mysqli_real_escape_string($db, $_POST['state']);
	
	$db->query("UPDATE groups SET 
		groupName ='$groupName', adminId=$adminId, adminPhone='$adminPhone', 
		targetAmount=$targetAmount, perPerson='$perPerson', updatedDate= now(),
		updatedBy='$adminId', groupTargetType='$groupTargetType', perPersonType='$perPersonType'
		, state='$state' WHERE id= '$groupId'
		") or die (mysqli_error());
	
	if($db)
	{
		
		$outCon->query("UPDATE groups SET 
		 accountNumber='$accountNumber', bankId='$bankId'
		WHERE groupId = '$groupId'
		")or die(mysqli_error());
		
		if($outCon)
		{ 
			listGroups();
		}
		else
		{
			echo 'money part not UPDATED';
		}
	}
	else
	{
		'group not UPDATED';
	}
}

function deleteGroup()
{
	require('../db.php');
	$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
	$adminId			= mysqli_real_escape_string($db, $_POST['adminId']);
	$db->query("UPDATE groups SET 
		archive ='yes'
		WHERE id='$groupId' AND adminId=$adminId
		") or die (mysqli_error());
	
	if($db)
	{
		listGroups();
	}
	else
	{
		'you cant delete this group';
	}
}

function inviteMember()
{
	require('../db.php');
	$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
	$invitorId			= mysqli_real_escape_string($db, $_POST['invitorId']);
	$invitedPhone		= mysqli_real_escape_string($db, $_POST['invitedPhone']);
	$sql = $db->query("SELECT id FROM users WHERE phone =  $invitedPhone");
	$countUsers = mysqli_num_rows($sql);
	if($countUsers > 0)
	{
		$invitedArray = mysqli_fetch_array($sql);
		$invitedId = $invitedArray['id'];
	}
	else
	{
		$db->query("INSERT INTO users (phone,createdBy,createdDate) VALUES  ('$invitedPhone', '$invitorId', now())");
		if($db)
		{
			$sql = $db->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
			$invitedArray = mysqli_fetch_array($sql);
			$invitedId = $invitedArray['id'];

		}
	}
	
	$db->query("INSERT INTO groupuser (joined, groupId, userId, createdBy, createdDate) VALUES ('yes','$groupId','$invitedId','$invitorId', now())");

	if($db)
	{
		listGroups();
	}
	else
	{
		'The user is not invited';
	}
}
?>