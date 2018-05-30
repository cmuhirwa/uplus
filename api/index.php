<?php
// START INITIATE
	include ("db.php");
	// var_dump($_SERVER["REQUEST_METHOD"]);
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if(isset($_POST['action']))
		{
			$_POST['action']();
		}
		else
		{
			echo 'Please read the API documentation';
		}
	}
	else
	{
		echo 'UPLUS API V02';
	}


// END INITIATE

// START LOG
    $f = fopen("logs/uplus.txt", 'a') or die("Unable to open file!");;
    fwrite($f, json_encode($_POST)."\n\n");
    fclose($f);
// END LOG

// START ACCOUNTS
	function signup()
	{
		require('db.php');
		$phoneNumber	= mysqli_real_escape_string($db, $_POST['phoneNumber']);
		//CLEAN PHONE
		$phoneNumber 	= preg_replace( '/[^0-9]/', '', $phoneNumber );
		$phoneNumber 	= substr($phoneNumber, -10); 

		
		//GET CSD ACCOUNT
		$sqlCsd = $investDb->query("SELECT * FROM clients WHERE telephone = '$phoneNumber' LIMIT 1");
		$countScd = mysqli_num_rows($sqlCsd);
		if($countScd > 0)
		{
			$row = mysqli_fetch_array($sqlCsd);
			$csdAccount = $row['csdAccount'];	
		}
		else
		{
			$csdAccount = "";
		}
		//CHECK IF THE USER ALREADY EXISTS
		$sqlcheckPin 	= $db->query("SELECT *  FROM users WHERE phone = '$phoneNumber' LIMIT 1");
		$countPin 		= mysqli_num_rows($sqlcheckPin);
		$code 			= rand(1000, 9999);
		$signInfo 		= array();
		if($countPin > 0)
		{
			
			while ($rowpin = mysqli_fetch_array($sqlcheckPin)) 
			{
				$profileName	= $rowpin['name'];
				$userId			= $rowpin['id'];
				if($profileName == "NULL" || $profileName == "null" || $profileName == null){
					$profileName = "";
				}
				$sql 			= $db->query("UPDATE users SET password = '$code' WHERE id = '$userId'")or die(mysqli_error($db));
				$signInfo = array(
			   		"pin"        => $code,
			   		"userId"     => $userId,
			   		"userName"   => $profileName,
					"csdAccount" => $csdAccount
			   );
			}
		}
		else
		{
			
			$sqlsavePin = $db->query("INSERT INTO `users`(
			phone, active, createdDate, password, visits, updatedBy, updatedDate) 
			VALUES('$phoneNumber', '0', now(), '$code', '0', '1', now())")or die (mysqli_error());

			$sqlcheckPin = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT 1");
			while ($rowpin = mysqli_fetch_array($sqlcheckPin)) {
				$code = $rowpin['password'];
				$signInfo = array(
			   		"pin"        => $rowpin['password'],
			   		"userId"     => $rowpin['id'],
			   		"userName"   => $rowpin['name']
			   );
			}
		}
		$results="";
		// 'went to require sms class';

		$recipients = '+25'.$phoneNumber;
		$message    = 'Welcome to UPLUS, please use '.$code.' to log into your account.';
		$data = array(
			"sender"		=>'UPLUS',
			"recipients"	=>$recipients,
			"message"		=>$message,
		);
		include 'sms.php';
		if($httpcode == 200)
		{
			sleep(3);
			mysqli_close($db);
			mysqli_close($outCon);
		
			header('Content-Type: application/json');
			$signInfo = json_encode($signInfo);
			echo '['.$signInfo.']';
		}
		else
		{
			echo 'System error';
		}
	}

	function updateProfile()
	{
		require('db.php');
		$userId				= mysqli_real_escape_string($db, $_POST['userId']);
		$userName			= mysqli_real_escape_string($db, $_POST['userName']);
		$userImage			= mysqli_real_escape_string($db, $_POST['userImage']);
		// GET THE VISITS
		$sql = $db->query("SELECT visits FROM users WHERE id = '$userId'");
		$rowVisit = mysqli_fetch_array($sql);
		$visits = $rowVisit['visits'];
		$newVisits = $visits + 1;


		$db->query("UPDATE users SET name = '$userName', userImage = '$userImage', active = 1, last_visit = now(), visits = '$newVisits' WHERE id = '$userId'")or die(mysqli_error());
		if($db)
		{

			echo $userImage;
		}
		else
		{
			echo 0;
		}

		mysqli_close($db);
		mysqli_close($outCon);
	}

	function attachdvc()
	{
		require('db.php');
		$userId				= mysqli_real_escape_string($db, $_POST['userId']);
		$token				= mysqli_real_escape_string($db, $_POST['Token']);
		$db->query("UPDATE users SET token = '$token' WHERE id = '$userId'");
		if($db){
			echo "yes";
		}else{
			echo "no";
		}
	}

	function transactions()
	{
		require('db.php');
		$userId	= mysqli_real_escape_string($db, $_POST['userId']);
		$sql 	= $outCon->query("SELECT status,amount,pushnumber,pullnumber,myid,type, transactiontime FROM intouchapi ORDER BY id DESC");
		$n=0;
		$returnedinformation   = array();   
		while ($row = mysqli_fetch_array($sql))
		{
			$n++;
			$pullnumber = $row['pullnumber'];
			$pushnumber = $row['pushnumber'];
			$myId  		= $row['myid'];
			
			if($row['type'] == "grouptransaction")
			{
				$pullnumber = "group";
				$sqlStatus = $outCon->query("SELECT status FROM grouptransactions WHERE id = '$myId'");
				$status = mysqli_fetch_array($sqlStatus)['status'];
				$sqlPull = $db->query("SELECT GR.groupName FROM uplus.groups GR INNER JOIN rtgs.grouptransactions GT ON GT.groupId = GR.id WHERE GT.id= '$myId' LIMIT 1");
				$pullName = mysqli_fetch_array($sqlPull)['groupName'];
				$sqlPush = $outCon->query("SELECT GR.name FROM uplus.users GR INNER JOIN rtgs.grouptransactions GT ON GT.memberId = GR.id WHERE GT.id= '$myId' LIMIT 1");
				$pushName = mysqli_fetch_array($sqlPush)['name'].'<em>('.$pushnumber.')</em>';
			}
			else
			{
				$sqlStatus = $outCon->query("SELECT status FROM directtransfers WHERE id = '$myId'");
				$status = mysqli_fetch_array($sqlStatus)['status'];
				$sqlPull = $outCon->query("SELECT actorName FROM directtransfers WHERE id = '$myId'+1");
				$pullName = mysqli_fetch_array($sqlPull)['actorName'].'('.$pullnumber.')';
				$sqlPush = $outCon->query("SELECT actorName FROM directtransfers WHERE id = '$myId'");
				$pushName = mysqli_fetch_array($sqlPush)['actorName'].'('.$pushnumber.')';
			}
			$amount 	= $row['amount'];
			if($status=="Failed"){
				$color = "#eb4435";
			}
			elseif($status=="Pending")
			{
				$color = "#fbbc03";
			}
			else
			{
				$color = "#36a753";
			}
			$transactionDate 	= strftime("%d, %b%y", strtotime($row["transactiontime"]));
			// GET THE USER TRANSATIONS
		
			$returnedinformation[] = array(
				"amount" 				=> number_format($amount)." Rwf",
	        	"pullName" 			=> $pullName,
	        	"phone" 			=> $pullnumber,
	        	"status"			=> $status,
	        	"transactionDate"	=> $transactionDate,
	        	"transactionColor"	=> $color
	    	);
		}
		header('Content-Type: application/json');
		$returnedinformation = json_encode($returnedinformation);
		echo $returnedinformation;
		//echo $n;
	}
// END ACCOUNTS

// START GROUPS 
	function listGroups()
	{
		include("db.php");
		$memberId		= mysqli_real_escape_string($db, $_POST['memberId']);
		$sqlgroups 		= $db->query("SELECT u.groupId, u.groupImage, u.updatedDate ,
		 u.syncstatus, u.groupName, u.groupTargetType, u.perPersonType, u.targetAmount, 
		 u.perPerson, u.adminId, u.adminName, u.groupDesc, r.Balance groupBalance
			FROM uplus.members u
			INNER JOIN rtgs.groupbalance r 
			WHERE u.groupId = r.id
			AND u.memberId = '$memberId' group by u.groupId")or die(mysqli_error());
		$groups 		= array();
		WHILE($group 	= mysqli_fetch_array($sqlgroups))
		{
			$groupId					= $group['groupId'];
			$sqlGroupBalance = $outCon->query("SELECT IFNULL((SELECT sum(t.amount) FROM rtgs.grouptransactions t WHERE ((t.status = 'Successfull' AND t.operation = 'DEBIT') AND (t.groupId = '$groupId'))),0) AS groupBalance FROM rtgs.groups g");
			$gBalanceRow 	= mysqli_fetch_array($sqlGroupBalance);
			$groups[] = array(
				"groupId"			=> $groupId,
				"groupImage"		=> $group['groupImage'],
				"updatedDate"		=> $group['updatedDate'],
				"syncstatus"		=> $group['syncstatus'],
				"groupName"			=> $group['groupName'],
				"groupTargetType"	=> $group['groupTargetType'],
				"perPersonType"		=> $group['perPersonType'],
				"targetAmount"		=> $group['targetAmount'],
				"perPerson"			=> $group['perPerson'],
				"adminId"			=> $group['adminId'],
				"adminName"			=> $group['adminName'],
				"groupDesc"			=> $group['groupDesc'],
				"groupBalance"		=> $gBalanceRow['groupBalance']
			);
		}

		mysqli_close($db);
		mysqli_close($outCon);
		header('Content-Type: application/json');
		$groups = json_encode($groups);
		echo $groups;
	}

	function createGroup()
	{
		require('db.php');
		$groupName			= mysqli_real_escape_string($db, $_POST['groupName']);
		$groupImage			= mysqli_real_escape_string($db, $_POST['groupImage']);
		$groupTargetType	= mysqli_real_escape_string($db, $_POST['groupTargetType']);
		$targetAmount		= mysqli_real_escape_string($db, $_POST['targetAmount']);
		$perPersonType		= mysqli_real_escape_string($db, $_POST['perPersonType']);
		$perPerson			= mysqli_real_escape_string($db, $_POST['perPerson']);
		$adminId			= mysqli_real_escape_string($db, $_POST['adminId']);
		
		if(!$adminId>0)
		{
			echo "Error: some admin problem";
		}
		if ($perPerson == "") {
			$perPerson = 500;
		}

		$sqliAdmin = $db->query("SELECT phone FROM users WHERE id = '$adminId'");
		$countAdmins = mysqli_num_rows($sqliAdmin);
		if($countAdmins > 0)
		{
			$rowid = mysqli_fetch_array($sqliAdmin);
			$adminPhone = $rowid['phone'];
		}

		$db->query("INSERT INTO groups
			(groupName, groupImage, adminId, adminPhone, 
			 targetAmount, perPerson, createdDate,
			 createdBy, state, groupTargetType, perPersonType, updatedBy, updatedDate)
			VALUES('$groupName', '$groupImage',$adminId,'$adminPhone',
				$targetAmount, $perPerson,now(),
				$adminId,'private','$groupTargetType','$perPersonType', '$adminId', now())") or die (mysqli_error($db));

		if($db)
		{
			$sqlid 	= $db->query("SELECT id FROM groups ORDER BY id DESC LIMIT 1") or die (mysqli_error());
			$rowid 	= mysqli_fetch_array($sqlid);
			$lastid = $rowid['id'];

			//ADD MEMBER TYPE
			$getMemberType	= $db->query("SELECT * FROM groupuser WHERE groupId='$lastid'");
			$countTres 		= mysqli_num_rows($getMemberType);
			if($countTres > 3){
				$memberType = '';
			}
			else
			{
				$memberType = 'Group treasurer';
			}
			
			//JOIN A USER TO A GROUP
			$db->query("INSERT INTO groupuser
			(`joined`, `groupId`, `userId`, type ,`createdBy`, `createdDate`, updatedBy, updatedDate)
			VALUES('yes','$lastid','$adminId','$memberType', '$adminId', now(), '$adminId', now())")or die(mysqli_error());

			if($db)
			{
				$sql = $outCon->query("INSERT INTO groups(groupId, accountNumber, bankId) 
				VALUES ('$lastid', '0784848236', '1')") or die (mysqli_error($outCon));
				if($outCon)
				{
					echo "".$lastid."";
				}
				else
				{
					echo 'error: money account not added';
				}
			}
			else
			{
				// Rollback
				$db->query("DELETE FROM groups WHERE id = '$lastid'");
				echo 'The user not joined';
			}
		}
		else
		{
			echo 'Group not created';
		}

		mysqli_close($db);
		mysqli_close($outCon);
	}

	function updateGroupShortLink()
	{
		require('db.php');
		$groupId	= mysqli_real_escape_string($db, $_POST['groupId']);
		$shortLink	= mysqli_real_escape_string($db, $_POST['shortLink']);
		$sql 		= $db->query("UPDATE groups SET shortLink = '$shortLink' WHERE id = '$groupId'")or die(mysqli_error($db));
	}

	function createcollection()
	{
		require('db.php');
		$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
		$accountNumber		= mysqli_real_escape_string($db, $_POST['accountNumber']);
		$bankId				= mysqli_real_escape_string($db, $_POST['bankId']);
		
		//CLEAN PHONE
		$accountNumber 	= preg_replace( '/[^0-9]/', '', $accountNumber );
		$accountNumber 	= substr($accountNumber, -10); 

		//CHECH IF THE ACCOUNT WASENT THERE BEFORE:
		$sql = $outCon->query("SELECT id FROM groups WHERE groupId= '$groupId' LIMIT 1");
		$check = mysqli_num_rows($sql);
		if($check > 0)
		{
			$row = mysqli_fetch_array($sql);
			$collectionId = $row['id'];
			$sql =  $outCon->query("UPDATE groups SET groupId = '$groupId', accountNumber = '$accountNumber', bankId = '$bankId' WHERE id = '$collectionId'");	
			echo 'updated the existing account';
		}
		else
		{
			$outCon->query("INSERT INTO groups(groupId, accountNumber, bankId)
			VALUES('$groupId','$accountNumber','$bankId')")or die(mysqli_error());
			
			if($outCon)
			{
				echo'Collection account added';
			}
			else
			{
				echo 'Collection account is not created';
			}
		}

		mysqli_close($db);
		mysqli_close($outCon);
	}

	function inviteMember()
	{
		require('db.php');
		$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
		$invitorId			= mysqli_real_escape_string($db, $_POST['invitorId']);
		$invitedPhonearray		= mysqli_real_escape_string($db, $_POST['invitedPhone']);
		
		$invitedPhonearray = explode(',', $invitedPhonearray);
		
		if (is_array($invitedPhonearray) || is_object($invitedPhonearray))
		{
		
		foreach($invitedPhonearray as $i => $item) 
		{
		    $invitedPhone = preg_replace( '/[^0-9]/', '', $invitedPhonearray[$i] );;
		
		
		
		//CLEAN PHONE
		$invitedPhone 	= preg_replace( '/[^0-9]/', '', $invitedPhone );
		$invitedPhone 	= substr($invitedPhone, -10); 

		//CHECK FOR POISON
		$sqlPoison = $db->query("SELECT id FROM groups WHERE id =  '$groupId'") or (mysqli_error());
		if(mysqli_num_rows($sqlPoison) > 0)
		{

			$sql = $db->query("SELECT id FROM users WHERE phone =  $invitedPhone") or (mysqli_error());
			$countUsers = mysqli_num_rows($sql);
			if($countUsers > 0)
			{
				//GET EXISTING USER
				$invitedArray = mysqli_fetch_array($sql);
				$invitedId = $invitedArray['id'];
			}
			else
			{
				//CREATE THE NEW USER
				$code = rand(0000, 9999);
				$db->query("INSERT INTO 
					users (phone,createdBy,createdDate, password, updatedBy, updatedDate) 
					VALUES  ('$invitedPhone', '$invitorId', now(), '$code', '$invitorId', now() )
					");
				if($db)
				{
					$sql 			= $db->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
					$invitedArray 	= mysqli_fetch_array($sql);
					$invitedId 		= $invitedArray['id'];
				}
			}

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
					echo 'Member '.$invitedPhone.', is brought back in the group';
				}
				else
				{
					echo 'Member '.$invitedPhone.', is already in the group';
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
					$gnamesql 	= $db->query("SELECT groupName FROM groups WHERE id = '$groupId' LIMIT 1");
					$loopg 		= mysqli_fetch_array($gnamesql);
					$groupName 	= $loopg['groupName'];
					$recipients = '+25'.$invitedPhone;
					$message    = 'You have been invited to join '.$groupName.' (a contribution group on UPlus). for more info, click here. https://xms9d.app.goo.gl/PeSx or *801*2# use '.$groupId.' as a group number';
					
					$data = array(
								"sender"		=>'UPLUS',
								"recipients"	=>$recipients,
								"message"		=>$message,
							);
					include 'sms.php';
					if($httpcode == 200)
					{
						echo 'Member with '.$invitedPhone.' is added';
					}
					else
					{
						echo 'System error';
					}
				}
				else
				{
					'The user is not invited';
				}
			}
		}
		else
		{
			echo "Poison Detected: ".$groupId;
		}
		
		}
		}
		else
		{
			//CLEAN PHONE
			$invitedPhone 	= preg_replace( '/[^0-9]/', '', $invitedPhonearray );
			$invitedPhone 	= substr($invitedPhone, -10); 

			//CHECK FOR POISON
			$sqlPoison = $db->query("SELECT id FROM groups WHERE id =  '$groupId'") or (mysqli_error());
			if(mysqli_num_rows($sqlPoison) > 0)
			{

				$sql = $db->query("SELECT id FROM users WHERE phone =  $invitedPhone") or (mysqli_error());
				$countUsers = mysqli_num_rows($sql);
				if($countUsers > 0)
				{
					//GET EXISTING USER
					$invitedArray = mysqli_fetch_array($sql);
					$invitedId = $invitedArray['id'];
				}
				else
				{
					//CREATE THE NEW USER
					$code = rand(0000, 9999);
					$db->query("INSERT INTO 
						users (phone,createdBy,createdDate, password, updatedBy, updatedDate) 
						VALUES  ('$invitedPhone', '$invitorId', now(), '$code', '$invitorId', now() )
						");
					if($db)
					{
						$sql 			= $db->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
						$invitedArray 	= mysqli_fetch_array($sql);
						$invitedId 		= $invitedArray['id'];
					}
				}

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
						echo 'Member '.$invitedPhone.', is brought back in the group';
					}
					else
					{
						echo 'Member '.$invitedPhone.', is already in the group';
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
						$gnamesql 	= $db->query("SELECT groupName FROM groups WHERE id = '$groupId' LIMIT 1");
						$loopg 		= mysqli_fetch_array($gnamesql);
						$groupName 	= $loopg['groupName'];
						$recipients = '+25'.$invitedPhone;
						$message    = 'You have been invited to join '.$groupName.' (a contribution group on UPlus). for more info, click here. https://xms9d.app.goo.gl/PeSx or *801*2# use '.$groupId.' as a group number';

						$data = array(
									"sender"		=>'UPLUS',
									"recipients"	=>$recipients,
									"message"		=>$message,
								);
						include 'sms.php';
						if($httpcode == 200)
						{
							echo 'Member with '.$invitedPhone.' is added';
						}
						else
						{
							echo 'System error';
						}
					}
					else
					{
						'The user is not invited';
					}
				}
			}
			else
			{
				echo "Poison Detected: ".$groupId;
			}
		}
		mysqli_close($db);
		mysqli_close($outCon);		
	}

	function publicInvite()
	{
		require('db.php');
		$groupId	= mysqli_real_escape_string($db, $_POST['groupId']);
		$sqlInvits 	= $db->query("SELECT COALESCE(u.name , u.phone) adminName, g.id, g.groupImage,
		 g.groupName, g.targetAmount, r.Balance groupBalance
			FROM uplus.groups g
			INNER JOIN uplus.users u
			ON g.adminId = u.id
			INNER JOIN rtgs.groupbalance r 
			ON g.id = r.id
			AND g.id = '$groupId' LIMIT 1")or die(mysqli_error());
		$groups 		= array();
		WHILE($group 	= mysqli_fetch_array($sqlInvits))
		{
			//$groupId		= $group['groupId'];
			$sqlGroupBalance= $outCon->query("SELECT IFNULL((SELECT sum(t.amount) FROM rtgs.grouptransactions t WHERE ((t.status = 'Successfull' AND t.operation = 'DEBIT') AND (t.groupId = '$groupId'))),0) AS groupBalance FROM rtgs.groups g");
			$gBalanceRow 	= mysqli_fetch_array($sqlGroupBalance);
			$groups[] = array(
				"groupId"			=> $group['id'],
				"groupImage"		=> $group['groupImage'],
				"groupName"			=> $group['groupName'],
				"targetAmount"		=> $group['targetAmount'],
				"adminName"			=> $group['adminName'],
				"groupBalance"		=> $gBalanceRow['groupBalance']
			);
		}
		foreach ($groups as $i => $group) {
			$groupId = $group['groupId'];
			$members = array();
		    $sqlMembers = $db->query("SELECT memberImage, COALESCE(`memberName`, `memberPhone`) `memberName` FROM members WHERE groupId LIKE '$groupId'")or die(mysqli_error($db));
		    while($rowMembers = mysqli_fetch_array($sqlMembers))
		    {
		    	$members[] 	= array(
			    	"memberName"	=> $rowMembers['memberName'],
					"memberImage"	=> $rowMembers['memberImage']
				);
		    }
		    $groups[$i]['members'] = $members;
		}


		mysqli_close($db);
		mysqli_close($outCon);
		header('Content-Type: application/json');
		$groups = json_encode($groups);
		echo $groups;
	}

	function listMembers()
	{
		require('db.php');
		$groupId	= mysqli_real_escape_string($db, $_POST['groupId']);
		$sqlMembers = $db->query("SELECT memberImage, groupId, targetAmount, groupName, groupTargetType, perPersonType, targetAmount, perPerson, adminId, adminName, groupDesc, memberId, memberPhone, COALESCE(`memberName`, `memberPhone`) `memberName`, memberDate, memberType FROM `members` WHERE groupId = '$groupId' ORDER BY memberType DESC") or die(mysqli_error());
		$members 	= array();
		$NumOfMembers = mysqli_num_rows($sqlMembers);
		$sqlGroupBalance = $outCon->query("SELECT (select sum(t.amount) FROM grouptransactions t WHERE ((t.status = 'Successfull' AND t.operation = 'DEBIT') AND (t.groupId = '$groupId')))-(SELECT sum(w.amount) FROM withdrowrequests w WHERE w.groupId = '$groupId') as groupBalance");
		$gBalanceRow 	= mysqli_fetch_array($sqlGroupBalance);
		$n=0;
		while($member = mysqli_fetch_array($sqlMembers))
		{
			$memberId	= $member['memberId'];
			$groupId	= $member['groupId'];
			   
			$sqlContribution = $db->query("SELECT  
				IFNULL(
						(
							SELECT sum(t.amount) 
							FROM rtgs.grouptransactions t 
							WHERE ((t.status = 'Successfull' AND t.operation = 'DEBIT') AND (t.memberId = '$memberId' AND t.groupId = '$groupId'))
						),0
						) AS memberContribution 
					FROM uplus.members m")	or die(mysql_error($sqlContribution));

			$contributionRow = mysqli_fetch_array($sqlContribution);
			
			$members[] = array(
			   "memberId"        	=> $member['memberId'],
			   "memberPhone"        => $member['memberPhone'],
			   "memberName"        	=> $member['memberName'],
			   "groupId"        	=> $member['groupId'],
			   "updatedDate"		=> $member['memberDate'],
			   "contributionDate"	=> $member['memberDate'],
			   "memberType"			=> $member['memberType'],
			   "memberImage"		=> $member['memberImage'],
			   "memberContribution"	=> $contributionRow['memberContribution'],
			   "groupBalance"		=> $gBalanceRow['groupBalance']
			);
		}
		mysqli_close($db);
		mysqli_close($outCon);	
		header('Content-Type: application/json');
		echo $members = json_encode($members);
	}

	function modifyGroup()
	{
		require('db.php');
		$groupName			= mysqli_real_escape_string($db, $_POST['groupName']);
		$groupImage			= mysqli_real_escape_string($db, $_POST['groupImage']);
		$groupTargetType	= mysqli_real_escape_string($db, $_POST['groupTargetType']);
		$targetAmount		= mysqli_real_escape_string($db, $_POST['targetAmount']);
		$perPersonType		= mysqli_real_escape_string($db, $_POST['perPersonType']);
		$perPerson			= mysqli_real_escape_string($db, $_POST['perPerson']);
		$adminId			= mysqli_real_escape_string($db, $_POST['adminId']);
		$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
		
		//CHECK IF YOU ARE THE OWNER FIRST
		$sqlchecking = $db->query("SELECT id FROM groups WHERE adminId = '$adminId'");
		$countcheck = mysqli_num_rows($sqlchecking);
		if($countcheck > 1)
		{
			$db->query("UPDATE groups SET 
				groupName ='$groupName', groupImage = '$groupImage', 
				targetAmount=$targetAmount, perPerson='$perPerson', updatedDate= now(),
				updatedBy='$adminId', groupTargetType='$groupTargetType', perPersonType='$perPersonType'
				 WHERE id= '$groupId' AND adminId = '$adminId'
				") or die (mysqli_error($db));
			
			if($db)
			{
				echo 'thanks the group is updated';
			}
			else
			{
				'group not UPDATED';
			}
		}
		else
		{
			echo "Sorry, you can't modify this group since you are not the one who created it";
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function exitGroup()
	{
		include "db.php";
		$groupId 	= mysqli_real_escape_string($db, $_POST['groupId']);
		$memberId 	= mysqli_real_escape_string($db, $_POST['memberId']);	
		
		$sqlGetGeoupName = $db->query("SELECT groupName, memberType FROM members WHERE groupId = '$groupId' AND memberId = '$memberId'");
		$groupNameRow = mysqli_fetch_array($sqlGetGeoupName);
		$groupName = $groupNameRow['groupName'];
		$memberType = $groupNameRow['memberType'];
		if($memberType == 'Group treasurer')
		{
			// MOVE THE TREASURER FUNCTION TO THE NEXT
			$db->query("UPDATE groupuser SET type = 'Group treasurer' WHERE (type <> 'Group treasurer' AND archive is null) AND groupId = '$groupId' ORDER BY id ASC LIMIT 1"); 
			if($db)
			{
				$sql = $db->query("UPDATE groupuser SET archive = 'YES', type = '', archivedDate = now() WHERE groupId = '$groupId' AND userId = '$memberId'")or die(mysqli_error($db));
				echo 'You are no longer a Treasurer of '.$groupName.', not even a Member.';
			}
		}
		elseif($memberType == '')
		{
				$sql = $db->query("UPDATE groupuser SET archive = 'YES', archivedDate = now() WHERE groupId = '$groupId' AND userId = '$memberId'")or die(mysqli_error($db));
				echo 'You are no longer a Member of '.$groupName.'.';
		}
		else
		{
			echo 'You are not in this group.';
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function contribute()
	{
		require('db.php');

		
		$groupId		= mysqli_real_escape_string($db, $_POST['groupId']);
		$amount 		= mysqli_real_escape_string($db, $_POST['amount']);
		$pushNumber 	= mysqli_real_escape_string($db, $_POST['pushnumber']);
		$pushBank		= mysqli_real_escape_string($db, $_POST['senderBank']);

		//CLEAN PHONE
		$pushNumber 	= preg_replace( '/[^0-9]/', '', $pushNumber );
		$pushNumber 	= substr($pushNumber, -10); 

		//GET THE MEMBER ID
		if (isset($_POST['memberId'])) 
		{
			$memberId		= mysqli_real_escape_string($db, $_POST['memberId']);
		}
		else
		{
			$sqlMId = $db->query("SELECT id FROM users WHERE phone = '$pushNumber' LIMIT 1");
			if(mysqli_num_rows($sqlMId)>0)
			{
				$rowMId = mysqli_fetch_array($sqlMId);
				$memberId = $rowMId['id'];
			}
			else
			{
				$memberId = 1;
			}
		}

		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100;


		// GET USER'S INFROMATION
		$sql = $db->query("SELECT groupName, memberName FROM members WHERE groupId = '$groupId' AND memberId = '$memberId' LIMIT 1");
		
		if($db)
		{
			while($row = mysqli_fetch_array($sql))
			{
				$groupName 		= $row['groupName'];
				$memberName		= $row['memberName'];
			}

			// SAVE THE TRANSACTION TO THE UPLUS DATABASE
			$sql = $outCon->query("INSERT INTO grouptransactions(
				memberId, groupId, amount, fromPhone, 
				bankId, operation, status, updatedBy, updatedDate)
			 	VALUES ('$memberId', '$groupId', '$amount', '$pushNumber', 
			 	'$pushBank', 'DEBIT', 'CALLED', '1', now())")
			 	 or mysqli_error($outCon);

			if($outCon)
			{
				// GET THE TRANSACTION ID
				$sqlRemovedId		= $outCon->query("SELECT id FROM grouptransactions ORDER BY id DESC LIMIT 1");
				$remId 				= mysqli_fetch_array($sqlRemovedId);
				$pushTransactionId 	= $remId['id'];

				//CALL THE API
				$url = 'https://www.intouchpay.co.rw/api/requestpayment/';
		
				$phone = '25'.$pushNumber;
				$username="muhirwa.clement";
				$var_time = time();
				$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
				$generate_hash =  hash('sha256', $generate);
				$txt_id = md5(time());
				$data = array();
				$data["username"] 				= $username;
				$data["timestamp"] 				= $var_time;
				$data["amount"] 				= $amount;
			    $data["password"] 				= $generate_hash;
				$data["partnerpassword"] 		= '8;b%-#K2$w\J3q{^dwr';
				$data["mobilephone"] 			= $phone;
				$data["requesttransactionid"]	= $txt_id;
				$data["accountid"] 				= '250150000003';

			    $options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data)
					)
				);
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				if ($result === FALSE) 
				{ 
					// API ERROR/ NETWORK ERROR
					$Update= $outCon->query("UPDATE grouptransactions SET status='NETWORK ERROR' WHERE id = '$pushTransactionId'");
					
					$returnedinformation	= array();
					
					$returnedinformation[] = array(
				       		"transactionId" => $pushTransactionId,
				       		"status" => "NETWORK ERROR",
						"memberId"	=> $memberId
				    	);
					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
				else
				{
					$result = json_decode($result);

					$success   				= $result->{'success'};
					if($success === FALSE){
						//Prepare data for db
						$status 				= "Failed";
						$requesttransactionid   = $pushTransactionId;

						$responsecode   		= $result->{'responsecode'};
						$transactionid  		= "no";
						$message   				= $result->{'message'};
					
					}
					else
					{
						//Prepare data for db
						$status 				= $result->{'status'};
						$requesttransactionid   = $result->{'requesttransactionid'};

						$responsecode   		= $result->{'responsecode'};
						$transactionid  		= $result->{'transactionid'};
						$message   				= $result->{'message'};
					}
					
					
					//SAVE THE TRANSACTION FROM MTN
					$sql = $outCon->query("INSERT INTO intouchapi(
							status, requesttransactionid, success, 
							responsecode, transactionid, message, 
							amount, pushNumber, pullNumber, myid, type
						) 
						VALUES (
						'$status', '$requesttransactionid', '$success', 
						'$responsecode', '$transactionid', '$message', 
						'$amount', '$pushNumber', '$groupId', '$pushTransactionId', 'grouptransaction'
						)
					")or die(mysqli_error($outCon));
					// UPDATE MY DB
					$sql2 = $outCon->query("UPDATE grouptransactions SET status='$status' WHERE id = '$pushTransactionId'") or die(mysql_error($outCon));

					$returnedinformation    = array();   
					$returnedinformation[] = array(
					       		"transactionId" => $pushTransactionId,
							"status" => $status,
							"memberId"	=> $memberId
					    );

					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
			}
			else
			{
				echo 'error inserting a transation';
			}
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function checkcontributionstatus()
	{
		require('db.php');
		$myId = mysqli_real_escape_string($db, $_POST['transactionId']);
		
		// Get the transfer id
		//sleep(5);
		$sql = $outCon->query("SELECT amount, pushnumber, pullnumber, checkCount, requesttransactionid FROM intouchapi WHERE myid = '$myId' AND type= 'grouptransaction' ORDER BY id DESC LIMIT 1");
		$transactionIdArray 	= mysqli_fetch_array($sql);
		$requesttransactionid 	= $transactionIdArray['requesttransactionid'];
		$checkCount 			= $transactionIdArray['checkCount'];
		$pushNumber 			= $transactionIdArray['pushnumber'];
		$pullNumber 			= $transactionIdArray['pullnumber'];
		$amount 				= $transactionIdArray['amount'];
		$newCheckCount 			= $checkCount + 1;

		$sql2 = $outCon->query("UPDATE intouchapi SET checkCount= '$newCheckCount' WHERE requesttransactionid = '$requesttransactionid'");

		// Check if the transfer status is back
		$sql3a = $outCon->query("SELECT id, status FROM intouchResponses WHERE requesttransactionid = '$requesttransactionid' LIMIT 1");
		$checkingCounts = mysqli_num_rows($sql3a);
		if($checkingCounts > 0)
		{
			// update the db to seen the answel
			$dataarray = mysqli_fetch_array($sql3a);
			$transId = $dataarray['id'];
			$status = $dataarray['status'];
			$outCon->query("UPDATE intouchResponses SET statusStatus= 'seen' WHERE id = '$transId'");

			$sql2 = $outCon->query("UPDATE grouptransactions SET status='$status' WHERE id = '$myId'") or die(mysql_error($outCon));
			$sql21 = $outCon->query("UPDATE intouchapi SET status='$status' WHERE myid = '$myId'") or die(mysql_error($outCon));

			if($outCon)
			{
				if($status == 'Successfull')
				{
					// FIND ME THE MEMBER ID
					$sql3 = $outCon->query("SELECT memberId FROM grouptransactions WHERE id = '$myId' LIMIT 1") or die(mysql_error($outCon));
					$rowMmId = mysqli_fetch_array($sql3);
					$memberId = $rowMmId['memberId'];
					
					// UPDATE THE MEMBER UPDATEDATE
					$timezone = date_default_timezone_get();
					date_default_timezone_set($timezone);
					$var_time = date('Y-m-d h:i:s', time());
					$sql4 = $db->query("UPDATE users SET updatedBy = '0', updatedDate = '$var_time' WHERE id = '$memberId'");


					// NOTIFY ALL MEMBERS THAT WE HAVE MEMBER X CONTRIBUTED SOME MONEY
					$sql5 = $db->query("SELECT memberPhone, groupName FROM members WHERE groupId = '$pullNumber'") or die(mysqli_error());
					$recipients = "";
					while($member = mysqli_fetch_array($sql5))
					{
						$recipients.= $member['memberPhone'].", ";
						$groupName 	= $member['groupName'];
					}

					//Get contributors name
					$sqlMid = $db->query("SELECT COALESCE(memberName, memberPhone) memberName FROM members WHERE memberId = '$memberId'") or die(mysqli_error());
					$rowMid = mysqli_fetch_array($sqlMid);
					$contributorName = $rowMid['memberName'];
					//CLEAN CONTACTS
					$recipients		= rtrim($recipients,", ");

					$message    = 'Congratulations, Member of '.$groupName.'. '.$contributorName.' Just contributed '.$amount.'Rwf in the group, your group is doing well, Keep up.';
					$data = array(
					 		"sender"		=>"UPLUS",
					 		"recipients"	=>$recipients,
					 		"message"		=>$message,
					 	);
				 	include 'sms.php';
				 	if($httpcode == 200){}

					// Bwiuld the answel
					$returnedinformation    = array();   
					$returnedinformation[] 	= array(
							"status" 		=> $status,
					        "transactionId" => $myId
					    );

					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
				else
				{
					// Bwiuld the answel
					$returnedinformation    = array();   
					$returnedinformation[] 	= array(
						"status" => $status,
					        "transactionId" => $myId
					    );

					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
			}
			else
			{
				// Bwiuld the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
						"status" => "Server Error",
				        "transactionId" => $myId
				    );

				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
			}
		}
		else
		{
			if($checkCount < 5)
			{
				sleep(7);
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
						"status" => 'Pending',
				        "transactionId" => $myId
				    );
			}
			elseif($checkCount < 10)
			{
				sleep(10);
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
					"status" => 'Pending',
			        "transactionId" => $myId
			    );
			}
			elseif($checkCount < 15)
			{
				sleep(20);
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
						"status" => 'Pending',
			        "transactionId" => $myId
			    );
			}
			elseif($checkCount > 17)
			{
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
					"status" => 'Pending',
			        "transactionId" => $myId
			    );
			}
			
			header('Content-Type: application/json');
			$returnedinformation = json_encode($returnedinformation);
			echo $returnedinformation;
		}
		mysqli_close($db);
		mysqli_close($outCon);	
	}

	function withdrawrequest()
	{
		include('db.php');
		$groupId			= mysqli_real_escape_string($db, $_POST['groupId']);
		$amount 			= mysqli_real_escape_string($db, $_POST['amount']);
		$memberId 			= mysqli_real_escape_string($db, $_POST['memberId']);
		$withdrawAccount 	= mysqli_real_escape_string($db, $_POST['withdrawAccount']);
		$withdrawBank 		= mysqli_real_escape_string($db, $_POST['withdrawBank']);

		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100;

		$sqlCheck 	= $outCon->query("SELECT id FROM withdrowrequests WHERE userId = '$memberId' AND (groupId = '$groupId' AND status = 'PENDING')");
		$counted 	= mysqli_num_rows($sqlCheck);
		//CHECK IF THERE IS NO PENDING REQUEST
		if(!$counted > 0)
		{
			//CHECK IF YOU ARE IN THE GROUP YOU ARE REQUESTING TO WITHDRAW
			$checkPoison = $db->query("SELECT * FROM members WHERE groupId = '$groupId' AND memberId = '$memberId'");
			if(mysqli_num_rows($checkPoison)> 0)
			{
				//CHECK IF THE GROUP HAS THE AMOUNT YOU ARE REQUESTING
				$sqlBal = $outCon->query("SELECT Balance FROM groupbalance WHERE id = '$groupId'");
				$rowBal = mysqli_fetch_array($sqlBal);
				$balCheck = $rowBal['Balance'];
				if($balCheck > $amount)
				{
					$sqlreq = $outCon->query("INSERT INTO withdrowrequests(amount, userId, groupId, withdrawAccount, withdrawBank, status, createdDate, createdBy, updatedBy, updatedDate)
					VALUES ('$amount','$memberId', '$groupId', '$withdrawAccount', '$withdrawBank', 'PENDING', now(),'$memberId', '$memberId', now())")or die (mysqli_error($outCon));
					if($outCon)
					{
						while($member = mysqli_fetch_array($db->query("SELECT memberPhone, groupName FROM members WHERE groupId = '$groupId'")))
						{
							$recipients.= $member['memberPhone'].", ";
							$groupName 	= $member['groupName'];
						}
						$withdrawerName = mysqli_fetch_array($sqlCheckTreasurer)['memberName'];
						//CLEAN CONTACTS
						$recipients		= rtrim($recipients,", ");
						$data = array(
							"sender"		=>"UPLUS",
							"recipients"	=>$recipients,
							"message"		=>'Member '.$withdrawerName.' Is requesting to withdraw '.number_format($amount).'Rwf from '.$groupName.' group, if you are the group treasurer please approve or reject his/her request',
						);

						
						echo 'Your request has been sent.';
					}
				}
				else
				{
					echo "The group doesn't have the amount you are requesting.";
				}
				
			}
			else
			{
				echo 'Poison Detected';
			}
		}
		else
		{
			echo 'Please wait for the first request.';
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function voteForTreasurer()
	{
		include 'db.php';
		$groupId 	= $_POST['groupId'];
		$memberId 	= $_POST['memberId'];
		$votedId1 	= $_POST['votedId1'];
		$votedId2 	= $_POST['votedId2'];
		$votedId3 	= $_POST['votedId3'];

		$results	= array();
		header('Content-Type: application/json');
		//CHECK IF THIS MEMBER NEVER VOTED BEFORE
		if(mysqli_num_rows($db->query("SELECT id FROM treasurervotes WHERE createdBy = '$memberId'"))>0)
		{
			$results[] = array(
				       	"status" => "You have voted before"
				    );
		}
		else
		{
			if($db->query("INSERT INTO treasurervotes(groupId, votedId, createdBy)
				 VALUES 
				 ('$groupId','$votedId1','$memberId'),
				 ('$groupId','$votedId2','$memberId'),
				 ('$groupId','$votedId3','$memberId')"))
			{
				$url = 'http://localhost:8080/uplus/api/service.php?condition=70&&groupId=79&&voteResults';
				$options = array(
					'http' => array(
					)
				);
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				
				$results[] = array(
				       	"status" => "Your vote has been casted"
				    );
			}
		}
		echo json_encode($results);
		mysqli_close($db);
	}

	function withdrawlist()
	{
		include("db.php");
		$groupId		= mysqli_real_escape_string($db, $_POST['groupId']);
		$sqlwithdraw	= $outCon->query("SELECT w.id, w.amount, w.groupId, COALESCE(u.name, u.phone) name FROM rtgs.withdrowrequests w INNER JOIN uplus.users u ON u.id = w.userId WHERE groupId = '$groupId' AND status = 'PENDING'")or die(mysqli_error($outCon));
		$withdraws 		= array();
		WHILE($withdraw = mysqli_fetch_array($sqlwithdraw))
		{
			$withdraws[] 	= array(
				"requestId"		=> $withdraw['id'],
				"groupId"		=> $withdraw['groupId'],
				"amount"		=> $withdraw['amount'],
				"memberName"	=> $withdraw['name'],
			);
		}
		mysqli_close($db);
		mysqli_close($outCon);
		header('Content-Type: application/json');
		$withdraws = json_encode($withdraws);
		echo $withdraws;
	}									

	function withdrawvote()
	{
		include("db.php");
		$requestId		= mysqli_real_escape_string($db, $_POST['requestId']);
		$groupId		= mysqli_real_escape_string($db, $_POST['groupId']);
		$treasurerId	= mysqli_real_escape_string($db, $_POST['treasurerId']);
		$vote			= mysqli_real_escape_string($db, $_POST['vote']);
		
		//CHECK IF THE APPROVER IS A TREASURER OF THIS GROUP
		$sqlCheckTreasurer = $db->query("SELECT * FROM members WHERE memberId = '$treasurerId' AND groupId = '$groupId'");
		$countTreasurer = mysqli_num_rows($sqlCheckTreasurer);
		if($countTreasurer == 1)
		{
			// CHECK IF YOU ARE NOT DOUBLE VOTING
			$sqlCheck 	= $outCon->query("SELECT * FROM requestsdecisions WHERE requestId = '$requestId' AND  createdBy = '$treasurerId'");
			$counted 	= mysqli_num_rows($sqlCheck);
			if(!$counted > 0)
			{

				$sqlNmofTrs = $db->query("SELECT * FROM members WHERE groupId = '$groupId' AND memberType = 'Group treasurer'");
				$countNoT	= mysqli_num_rows($sqlNmofTrs);
				$neededNoT	= $countNoT / 2;
				if($vote == 'YES')
				{
					$outCon->query("
					INSERT INTO requestsdecisions(requestId, vote, createdBy, createdDate, updatedBy, updatedDate) 
					VALUES ($requestId, 'YES', '$treasurerId', now(), '$treasurerId', now())")or die(mysqli_error($outCon));
					$refId = mysqli_insert_id($outCon); 
					if($outCon)
					{
						//CHECK IF ATLEAST 1/2 of all TREASURERS HAVE VOTTED AND APPROVE THE REQUEST
						$sqlCheckApproved 	= $outCon->query("SELECT * FROM requestsdecisions WHERE requestId = '$requestId' AND vote ='YES'");
						$countApprovals 	= mysqli_num_rows($sqlCheckApproved);
						if($countApprovals >= $neededNoT)
						{
							//APPROVE THE REQUEST

							// GET THE USER NUMBER TO SEND THE MONEY TO
							$sqlwithdrawInfo 	= $outCon->query("SELECT * FROM withdrowrequests WHERE id = '$requestId'");
							$withdraw 			= mysqli_fetch_array($sqlwithdrawInfo);
							$amount 			= $withdraw['amount'];
							$pullNumber 		= $withdraw['withdrawAccount'];

							//CLEAN PHONES
							$pullNumber 		= preg_replace( '/[^0-9]/', '', $pullNumber );
							$pullNumber 		= substr($pullNumber, -10);

							// CALL THE API
							
							// SENDING PAYMENT REQUEST
						
							$url = 'https://www.intouchpay.co.rw/api/requestdeposit/';
				
							$phone = '25'.$pullNumber;
							$username="muhirwa.clement";
							$var_time = time();
							$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
							$generate_hash =  hash('sha256', $generate);
							$txt_id = md5(time());
							$data = array();
							
							$data["username"] 				= $username;
							$data["timestamp"] 				= $var_time;
							$data["amount"] 				= $amount;
						   	$data["withdrawcharge"] 		= 0;
							$data["reason"] 				= "Send Money";
							$data["sid"] 					= "1";
							$data["password"] 				= $generate_hash;
							$data["mobilephone"] 			= $phone;
							$data["requesttransactionid"]	= $txt_id;

						    $options = array(
								'http' => array(
									'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
									'method'  => 'POST',
									'content' => http_build_query($data)
								)
							);
							$context  = stream_context_create($options);
							$result = file_get_contents($url, false, $context);
							if ($result == false) 
							{ 
								// SAVE THE SUPPORT MESSAGE TO THE DB
								// SEND ME THE SMS TO QUICLY FIX THE CONNECTION WITH INTOUCH
								echo 'There was a system error, a quick notification was sent to the Uplus support team, we shall contact you once fixed, thanks for your patience';
							}
							else
							{
								$result = json_decode($result);
									//Prepare data for db
								$success   				= $result->{'success'};
								$requesttransactionid   = $result->{'requesttransactionid'};
								$responsecode   		= $result->{'responsecode'};
								$referenceid   			= $result->{'referenceid'};

								// if the user recieved money
								if($success == true)
								{
									// TELL GROUP MEMBERS THAT THE MEMBER WITHDRAWEN SOME MONEY
									while($member = mysqli_fetch_array($db->query("SELECT memberPhone, groupName FROM members WHERE groupId = '$groupId'")))
									{
										$recipients.= $member['memberPhone'].", ";
										$groupName 	= $member['groupName'];
									}
									$withdrawerName = mysqli_fetch_array($sqlCheckTreasurer)['memberName'];
									//CLEAN CONTACTS
									$recipients		= rtrim($recipients,", ");
									$data = array(
										"sender"		=>"UPLUS",
										"recipients"	=>$recipients,
										"message"		=>'Member '.$withdrawerName.' Successfully withdrawen '.number_format($amount).'Rwf from '.$groupName.' group, After being approved by 3 group treasurers, If you dont agree, kindly call 0784848236.',
									);

									$sqlApprove = $outCon->query("UPDATE withdrowrequests SET status = 'APPROVED' WHERE id = '$requestId'");
									echo 'Request was approved, and the money is sent. Thanks for Approving this request.';
								}
								elseif($success == false)
								{
									// SAVE THE SUPPORT MESSAGE TO THE DB
									//revert the transaction
									$sqlRevert = $outCon->query("DELETE FROM requestsdecisions WHERE id = '$refId'");
									// SEND ME THE SMS TO QUICLY TOPUP THE ACCOUNT WITH $amount
									echo 'There was a system error, a quick notification with referenceid : '.$referenceid.' was sent to the Uplus support team, we shall contact you once fixed, thanks for your patience';
								}
							 //echo "tested";
							}
						}
						elseif($countApprovals < $neededNoT)
						{
							echo 'Thanks for Approving this request, One more treasurer to confirm.';
						}
						else
						{
							echo 'plz refine the codes.'.$neededNoT;
						}
					}
				}
				elseif($vote == 'NO')
				{
					//VOTE IS NO
					$outCon->query("
					INSERT INTO requestsdecisions(requestId, vote, createdBy, createdDate, updatedBy, updatedDate) 
					VALUES ($requestId, 'NO', '$treasurerId', now(), '$treasurerId', now())")or die(mysqli_error($outCon));
					
					if($outCon)
					{
						//CHECK IF ATLEAST 1/2 of all TREASURERS HAVE VOTTED AND REJECT THE REQUEST
						$sqlCheckApproved = $outCon->query("SELECT * FROM requestsdecisions WHERE requestId = '$requestId' AND vote ='NO'");
						$countApprovals 	= mysqli_num_rows($sqlCheckApproved);
						if($countApprovals >= $neededNoT)
						{
							//APPROVE THE REQUEST
							$sqlApprove = $outCon->query("UPDATE withdrowrequests SET status = 'REJECTED' WHERE id = '$requestId'");
							echo 'Request was rejected, Thanks for your vote on this request.';
						}
						else
						{
							echo 'Thanks for rejecting this request, One more treasurer to reject.';
						}
					}
				}
			}
			else
			{
				echo 'You have voted already.';
			}
		}
		else
		{
			echo 'Only treasurers are allowed to approve';
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function voteTreasurer()
	{
		include 'db.php';
		$groupId 	= $_POST['groupId'];
		$memberId 	= $_POST['memberId'];
		$votedId1 	= $_POST['votedId1'];
		$votedId2 	= $_POST['votedId2'];
		$votedId3 	= $_POST['votedId3'];

		$results	= array();
		header('Content-Type: application/json');
		//CHECK IF THIS MEMBER NEVER VOTED BEFORE
		if(mysqli_num_rows($db->query("SELECT id FROM treasurervotes WHERE createdBy = '$memberId'"))>0)
		{
			$results[] = array(
				       	"status" => "You have voted before"
				    );
		}
		else
		{
			if($db->query("INSERT INTO treasurervotes(groupId, votedId, createdBy)
				 VALUES 
				 ('$groupId','$votedId1','$memberId'),
				 ('$groupId','$votedId2','$memberId'),
				 ('$groupId','$votedId3','$memberId')"))
			{
				$url = 'http://localhost:8080/uplus/api/service.php?condition=70&&groupId=79&&voteResults';
				$options = array(
					'http' => array(
					)
				);
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				
				$results[] = array(
				       	"status" => "Your vote has been casted"
				    );
			}
		}
		echo json_encode($results);
		mysqli_close($db);
	}
// END GROUPS

// START EVENT
	function eventList()
	{
		include("db.php");
		$sqlEvents = $eventDb->query("SELECT * FROM events");
		$events[] = array();
		while ($row = mysqli_fetch_array($sqlEvents)) {
			//$eventId = $row['id_event'];
			$events[] = array(
				"eventId"			=> $row['id_event'],
				"eventName"			=> $row['Event_Name'],
				"eventDesc"			=> $row['Event_Desc'],
				"eventCover"		=> $row['Event_Cover'],
				"eventLocation"		=> $row['Event_Location'],
				"eventContact"		=> $row['phone'],
				"eventStart"		=> $row['Event_Start'],
				"eventEnd"			=> $row['Event_End'],
				"eventActive"		=> $row['active'],
				);
		}
		foreach ($events as $i => $event) {
			$eventId = $event['eventId'];
			$tickets = array();
		    $sqltickets = $eventDb->query("SELECT * FROM eventing_pricing WHERE event_code LIKE '$eventId'")or die(mysqli_error($db));
		    while($rowTicket = mysqli_fetch_array($sqltickets))
		    {
		    	$ticketId		= $rowTicket['pricing_code'];
		    	$sqlTakenSeats	= $eventDb->query("SELECT * FROM transaction WHERE cust_event_seats = '$ticketId'");
		    	$takenSeats  	= mysqli_num_rows($sqlTakenSeats);
		    	$sqlSeats 	= $eventDb->query("SELECT * FROM pricing WHERE pricing_id = '$ticketId' LIMIT 1");
		    	$rowSeat 	= mysqli_fetch_array($sqlSeats);
		    	$allSeats 	= $rowSeat['event_seats'];
		    	$tickets[] 	= array(
			    	"seatPrice"			=> $rowSeat['price'],
					"ticketName"		=> $rowSeat['event_property'],
					"availableSeats"	=> $allSeats-$takenSeats
				);
		    }
		    $events[$i]['eventTickets'] = $tickets;
		}
	
		mysqli_close($db);
		mysqli_close($eventDb);
		header('Content-Type: application/json');
		$events = json_encode($events);
		echo $events;
	}

	function eventList1()
	{
		include("db.php");
		$sqlMembers = $eventDb->query("SELECT * FROM `events`") or die(mysqli_error());
		$events 	= array();
		while($member 	= mysqli_fetch_array($sqlMembers))
		{
			$events[] = array(
				"eventId"			=> $member['id_event'],
				"eventName"			=> $member['Event_Name'],
				"eventDesc"			=> $member['Event_Desc'],
				"eventCover"			=> $member['Event_Cover'],
				"eventLocation"			=> $member['Event_Location'],
				"eventContact"			=> $member['phone'],
				"eventStart"			=> $member['Event_Start'],
				"eventEnd"			=> $member['Event_End']
			);
		}
		mysqli_close($db);
		mysqli_close($eventDb);
		header('Content-Type: application/json');
		$events = json_encode($events);
		echo $events;
	}

	function eventUpdate()
	{
		global $eventDb;
		$url = "http://akokanya.com/api/events/?email=muhirwaclement@gmail.com&password=clement123 ";

		$header = array();
		$header[] = "email:muhirwaclement@gmail.com";
		$header[] = "password:clement123";

		$ch = curl_init();
		curl_setopt_array($ch, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_HTTPHEADER => $header,
		    CURLOPT_USERAGENT => 'Uplus'
		));

       $result = curl_exec($ch);           
       	if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       	}else{
       		$result = json_decode($result, 1);

	       	if($result){

	       		//Cleaning table data //Truncating
	       		$eventDb->query("DELETE FROM akokanya");

	       		//Looping through events
	       		for ($n=0; $n < count($result); $n++) {
	       			$events = $result[$n];
		       		$name = $events['name']??"";
					$code = $events['code']??"";
					$details = $events['details']??"";
					$status = $events['status']??"";
					$from_time = $events['from_time']??"0000-00-00";
					$to_time = $events['to_time']??"0000-00-00";
					$user_id = $events['user_id']??"";
					$available_place = $events['available_place']??"";
					$location = $events['location']??"";
					$event_payment_model_id = $events['event_payment_model_id']??"";
					$event_category_id = $events['event_category_id']??"";
					$file1 = $events['file1']??"";
					$file2 = $events["file2"]??"";
					$file3 = $events["file3"]??"";
					$deleted_at = $events["deleted_at"]??"2017-12-30 00:00:10";
					$created_at = $events["created_at"]??"2017-12-30 00:00:10";
					$updated_at = $events["updated_at"]??"2017-12-30 00:00:10";
					$counting = $events["counting"]??"";

					//Dates formatting
					$from_time = date("Y-m-d", strtotime($from_time));
					$to_time = date("Y-m-d", strtotime($to_time));


					//Putting event in DB
					$sql = "INSERT INTO akokanya
					(name, code, details, status, from_time, to_time, user_id,
					available_place, location, event_payment_model_id, event_category_id,
					file1, file2, file3, deleted_at, created_at, updated_at, counting) 
					VALUES
					(\"$name\", \"$code\", \"$details\", \"$status\", \"$from_time\", \"$to_time\", \"$user_id\",
					\"$available_place\", \"$location\", \"$event_payment_model_id\", \"$event_category_id\",
					\"$file1\", \"$file2\", \"$file3\", \"$deleted_at\", \"$created_at\", \"$updated_at\", \"$counting\")";

					$query = mysqli_query($eventDb, $sql);

					if(!$query) echo("status:0, msg:".mysqli_error($eventDb));
					else echo("status:1, msg: Event inserted\n");
					
				}
	       	}
       }
       curl_close($ch);
       return $result;
	}

	function eventCreate(){}

	function eventEdit(){}

	function eventRemove(){}

	function eventBook()
	{
		include("db.php");
		$phone		= mysqli_real_escape_string($db, $_POST['pullNumber']);
		$event		= mysqli_real_escape_string($db, $_POST['eventId']);
		$property	= mysqli_real_escape_string($db, $_POST['seatCode']);
		$userid		= mysqli_real_escape_string($db, $_POST['userId']);

		//these details are sent by free events
		$userName = $db->real_escape_string($_POST['name']??"");
		$email = $db->real_escape_string($_POST['email']??"");
		
		$selectprice = $eventDb->query("SELECT price  FROM pricing WHERE pricing_id LIKE '$property'");
		$fetchrow = mysqli_fetch_assoc($selectprice);
		$price = $fetchrow['price'];
		$eventDb->query("INSERT INTO transaction (cust_event_choose, cust_pay_phone, cust_name, cust_email, amount, cust_event_seats,user_id, createdBy, paidStatus, status) 
			VALUES('$event','$phone', '$userName', '$email', '$price','$property','$userid','$userid', 'PAID', 'UNUSED')") or die("error please in inserting".mysqli_error($eventDb));
		if($eventDb)
		{
			$ticketId 	= mysqli_insert_id($eventDb);
		}
		$time = time();
		$code = rand(1,1000);
		$passive = $time."".$code;
		$str = md5($passive);
		function String2Stars($string='',$first=0,$last=0,$rep=''){
		  $begin  = substr($string,0,$first);
		  $stars  = $begin;
		  return $stars;
		}
		$str2 = String2Stars($str, 4, -4);
		$ticketCode = $ticketId."".$str2;
		$eventDb->query("UPDATE transaction SET ticketCode = '$ticketCode' WHERE customer_pay_id = '$ticketId'") or die("error please in inserting".mysqli_error());

		set_time_limit(0);
		//File to save the contents to
		$fp = fopen (dirname(__FILE__) . '/qrcodes/1'.$ticketCode.'.png', 'w+');
		$url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$ticketCode."";
		//Here is the file we are downloading, replace spaces with %20
		$ch = curl_init(str_replace(" ","%20",$url));
		curl_setopt($ch, CURLOPT_TIMEOUT, 500); 
		//give curl the file pointer so that it can write to it
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$data = curl_exec($ch);//get curl response
		//done
		curl_close($ch);
		
		$ticket = array();
		$ticket[] = array(
			   	"ticketCode" => $ticketCode
			   );

		mysqli_close($db);
		mysqli_close($outCon);	
		header('Content-Type: application/json');
		echo $ticket = json_encode($ticket);
	}

	function eventChecout()
	{}

	function eventStatus()
	{}

	function eventTransactions()
	{}

	function eventTicketsList()
	{
		include 'db.php';
		$eventId 		= mysqli_real_escape_string($db, $_POST['eventId']);
		$sqlTicketsList = $eventDb->query("
			SELECT T.ticketCode, T.status, T.amount, P.event_property ticketType
			FROM transaction T
			INNER JOIN pricing P
			ON T.cust_event_seats = P.pricing_id
			WHERE cust_event_choose = '$eventId' AND paidStatus IS NOT NULL");
		$ticktes = array();
		while($rowTicketsList = mysqli_fetch_array($sqlTicketsList)) {
			$ticktes[] = array(
			   "ticketCode"		=> $rowTicketsList['ticketCode'],
			   "ticketType"		=> $rowTicketsList['ticketType'],
			   "ticketStatus"	=> $rowTicketsList['status'],
			   "ticketAmount"	=> $rowTicketsList['amount']
			);
		}
		mysqli_close($db);
		mysqli_close($outCon);	
		header('Content-Type: application/json');
		echo $ticktes = json_encode($ticktes);
	}

	function addAgent()
	{
		include 'db.php';
		$eventId			= mysqli_real_escape_string($db, $_POST['eventId']);
		$ticketId			= mysqli_real_escape_string($db, $_POST['ticketId']);
		$invitedPhone		= mysqli_real_escape_string($db, $_POST['phone']);
		$givenTickets		= mysqli_real_escape_string($db, $_POST['givenTickets']);
		$invitorId			= mysqli_real_escape_string($db, $_POST['invitorId']);

		//CLEAN PHONE
		$invitedPhone 	= preg_replace( '/[^0-9]/', '', $invitedPhone );
		$invitedPhone 	= substr($invitedPhone, -10); 

		//CHECK FOR POISON
		$sqlPoison = $eventDb->query("SELECT id_event FROM events WHERE id_event =  '$eventId'") or (mysqli_error($db));
		if(mysqli_num_rows($sqlPoison) > 0)
		{
			$response = array();
			$sql = $db->query("SELECT id FROM users WHERE phone =  $invitedPhone") or (mysqli_error());
			$countUsers = mysqli_num_rows($sql);
			if($countUsers > 0)
			{
				//GET EXISTING USER
				$invitedArray = mysqli_fetch_array($sql);
				$invitedId = $invitedArray['id'];
			}
			else
			{
				//CREATE THE NEW USER
				$code = rand(0000, 9999);
				$db->query("INSERT INTO 
					users (phone,createdBy,createdDate, password, updatedBy, updatedDate) 
					VALUES  ('$invitedPhone', '$invitorId', now(), '$code', '$invitorId', now() )
					");
				if($db)
				{
					$sql 			= $db->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
					$invitedArray 	= mysqli_fetch_array($sql);
					$invitedId 		= $invitedArray['id'];
				}
			}

			// CHECK IF THE USER IS ALREADY IN THE GROUP
			$sql = $eventDb->query("SELECT * FROM agent_tickets WHERE eventId ='$eventId' AND agent='$invitedId'");
			$checkExits = mysqli_num_rows($sql);
			if($checkExits > 0)
			{
				$sql = $eventDb->query("UPDATE agent_tickets SET number = (number+ $givenTickets) WHERE agent = $invitedId AND ticket = $ticketId ");
				// ADD ON WHAT HE HAD
				$response = array('status'=>true, 'msg'=>"We have added $givenTickets tickets to the agent");
			}
			else
			{
				// ADD MEMBER FOR THE FIRST TIME IN THIS GROUP
				$sql = $eventDb->query("INSERT INTO `agent_tickets`(`agent`, `ticket`, `eventId`, `number`) VALUES ('$invitedId', '$ticketId', '$eventId', '$givenTickets' )")or die(mysqli_error($db));

				$response = array('status'=>true, 'msg'=>"We have added agent and gave $givenTickets tickets");
			}
		}
		else
		{
			$response = array('status'=>false, 'msg'=>"Event does not exist :)");
		}
		mysqli_close($db);
		mysqli_close($eventDb);

		echo json_encode($response);
	}

	function eventSeatsList()
	{
		include 'db.php';
		$eventId 		= mysqli_real_escape_string($db, $_POST['eventId']);
		$sqlTicketsList = $eventDb->query("
			SELECT EP.pricing_code ticketCode, P.price, P.event_property ticketsName
			FROM eventing_pricing EP 
			INNER JOIN pricing P 
			ON P.pricing_id = EP.pricing_code
			WHERE EP.event_code like '$eventId'");
		$ticktes = array();
		while ($rowTicketsList = mysqli_fetch_array($sqlTicketsList)) {
			$ticktes[] = array(
			   "eventBack"		=> $eventId,
			   "ticketCode"		=> $rowTicketsList['ticketCode'],
			   "price"		=> $rowTicketsList['price'],
			   "ticketsName"	=> $rowTicketsList['ticketsName']
			);
		}
		mysqli_close($db);
		mysqli_close($outCon);	
		header('Content-Type: application/json');
		echo $ticktes = json_encode($ticktes);
	}
// END EVENT

// START TRANSFERS
	function directtransfer()
	{
		require('db.php');

		$amount 		= mysqli_real_escape_string($db, $_POST['amount']);
		$pushId			= mysqli_real_escape_string($db, $_POST['senderId']);
		$pushName		= mysqli_real_escape_string($db, $_POST['senderName']);
		$pushNumber		= mysqli_real_escape_string($db, $_POST['senderPhone']);
		$pushBank		= mysqli_real_escape_string($db, $_POST['senderBank']);

		$pullName		= mysqli_real_escape_string($db, $_POST['receiverName']);
		$pullNumber		= mysqli_real_escape_string($db, $_POST['receiverPhone']);
		$pullBank		= mysqli_real_escape_string($db, $_POST['receiverBank']);
		
		//CLEAN PHONES
		$pushNumber 	= preg_replace( '/[^0-9]/', '', $pushNumber );
		$pullNumber 	= preg_replace( '/[^0-9]/', '', $pullNumber );
		
		$pushNumber = substr($pushNumber, -10); 
		$pullNumber = substr($pullNumber, -10);

		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100; 

		//GET RECIEVER'S ID IF EXISTS
		$sql = $db->query("SELECT * FROM users WHERE phone = '$pullNumber' LIMIT 1");
		$checkAvailb = mysqli_num_rows($sql);
		if($checkAvailb > 0)
		{
			$row = mysqli_fetch_array($sql);
			$pullId		= $row['id'];
		}
		else
		{
			$pullId = 0;
		}
		// SAVE THE TRANSACTION TO THE UPLUS DATABASE
		$outCon->query("INSERT INTO directtransfers
			(userId, actorName, accountNumber, bank, status, operation, amount) 
			VALUES 
			('$pushId', '$pushName','$pushNumber', '$pushBank', 'CALLED','DEBIT', '$amount'),
			('$pullId', '$pullName','$pullNumber', '$pullBank', 'CALLED','CREDIT', '$amount')
		
			") or mysqli_error($outCon);

		if($outCon)
		{
			$remId = mysqli_insert_id($outCon);
			$pullTransactionId = $remId['id'];
			$pushTransactionId = $pullTransactionId - 1;

			
			//CHECK BALANCE
			$url = 'https://www.intouchpay.co.rw/api/getbalance/';

			$username 		= "muhirwa.clement";
			$var_time 		= time();
			$generate 		= $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
			$generate_hash 	= hash('sha256', $generate);

			$data 				= array();
			$data["username"] 	= $username;
			$data["password"] 	= $generate_hash;
			$data["timestamp"]	= $var_time;
			$options = array(
					'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  	= stream_context_create($options);
			$result 	= file_get_contents($url, false, $context);
			//echo $result;
			if ($result === FALSE) 
			{ 
				$returnedinformation	= array();
				$returnedinformation[] 	= array(
			       		"status" => "NETWORK ERROR",
					    "transactionid" => $pushTransactionId
			    	);
				header('Content-Type: application/json');
				$returnedinformation 	= json_encode($returnedinformation);
				echo $returnedinformation;
			}
			else
			{
				$result 	= json_decode($result);
				//Prepare data for db
				$success 	= $result->{'success'};
				
				if($success == true)
				{
					$balance 	= $result->{'balance'};
					$fee 		= ($amount*2)/100;
					$charge 	= $fee + 120;


					if($balance < $charge)
					{
						$returnedinformation	= array();
						$returnedinformation[] 	= array(
					       		"status" 		=> "Failed",
					       		"transactionid" => $pushTransactionId
					    	);
						header('Content-Type: application/json');
						$returnedinformation = json_encode($returnedinformation);
						echo $returnedinformation;
					}
					else
					{
						$url = 'https://www.intouchpay.co.rw/api/requestpayment/';
						$phone = '25'.$pushNumber;
						$username="muhirwa.clement";
						$var_time = time();
						$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
						$generate_hash =  hash('sha256', $generate);
						$txt_id = md5(time());
						$data = array();
						$data["username"] 				= $username;
						$data["timestamp"] 				= $var_time;
						$data["amount"] 				= $amount;
					    $data["password"] 				= $generate_hash;
						$data["partnerpassword"] 		= '8;b%-#K2$w\J3q{^dwr';
						$data["mobilephone"] 			= $phone;
						$data["requesttransactionid"]	= $txt_id;
						$data["accountid"] 				= '250150000003';
					    $options = array(
							'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'  => 'POST',
								'content' => http_build_query($data)
							)
						);
						$context  = stream_context_create($options);
						$result = file_get_contents($url, false, $context);
						if ($result === FALSE) 
						{ 
							$Update= $outCon->query("UPDATE directtransfers SET status='NETWORK ERROR' WHERE id = '$pushTransactionId'");
							$returnedinformation	= array();
							$returnedinformation[] = array(
						       		"transactionId" => $pushTransactionId,
						       		"status" => "NETWORK ERROR"
						    	);
							header('Content-Type: application/json');
							$returnedinformation = json_encode($returnedinformation);
							echo $returnedinformation;
						}
						else
						{
							$result = json_decode($result);
							$success   				= $result->{'success'};
							if($success === FALSE)
							{
								//Prepare data for db
								$status 				= "Failed";
								$requesttransactionid   = $pushTransactionId;
								$responsecode   		= $result->{'responsecode'};
								$transactionid  		= "no";
								$message   				= $result->{'message'};
							}
							else
							{
								//Prepare data for db
								$status 				= $result->{'status'};
								$requesttransactionid   = $result->{'requesttransactionid'};
								$responsecode   		= $result->{'responsecode'};
								$transactionid  		= $result->{'transactionid'};
								$message   				= $result->{'message'};
							}
							
							//SAVE THE TRANSACTION FROM MTN
							$sql = $outCon->query("INSERT INTO intouchapi(
									status, requesttransactionid, success, 
									responsecode, transactionid, message, 
									amount, pushNumber, pullNumber,  myid, type
								) 
								VALUES (
								'$status', '$requesttransactionid', '$success', 
								'$responsecode', '$transactionid', '$message', 
								'$amount', '$pushNumber', '$pullNumber', $pushTransactionId, 'directtransfer'
								)
							")or die(mysqli_error($outCon));
							// UPDATE MY DB
							$sql2 = $outCon->query("UPDATE directtransfers SET status='$status' WHERE id = '$pushTransactionId'") or die(mysql_error($outCon));

							$returnedinformation    = array();   
							$returnedinformation[] = array(
									"status" => $status,
							        "transactionId" => $pushTransactionId
							    );

							header('Content-Type: application/json');
							$returnedinformation = json_encode($returnedinformation);
							echo $returnedinformation;
						}
					}
				}
			}
		
		}
		else
		{
			echo 'error inserting a transation';
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function checktransferstatus()
	{	
		require('db.php');
		$myId = mysqli_real_escape_string($db, $_POST['transactionId']);
		
		// Get the transfer id
		//sleep(5);
		$sql = $outCon->query("SELECT amount,pullnumber, pushnumber, checkCount, requesttransactionid, status FROM intouchapi WHERE myid = '$myId' AND type= 'directtransfer' ORDER BY id DESC LIMIT 1");
		$transactionIdArray = mysqli_fetch_array($sql);
		$status = $transactionIdArray['status'];
		$requesttransactionid = $transactionIdArray['requesttransactionid'];
		$checkCount = $transactionIdArray['checkCount'];
		$pushNumber = $transactionIdArray['pushnumber'];
		$pullNumber = $transactionIdArray['pullnumber'];
		$amount 	= $transactionIdArray['amount'];
		$newCheckCount = $checkCount + 1;

		$sql1 = $outCon->query("UPDATE grouptransactions SET status= '$status' WHERE id = '$myId'");
		$sql2 = $outCon->query("UPDATE intouchapi SET checkCount= '$newCheckCount' WHERE requesttransactionid = '$requesttransactionid'");

		// Check if the transfer status is back
		$sql3a = $outCon->query("SELECT id, status FROM intouchResponses WHERE requesttransactionid = '$requesttransactionid' LIMIT 1");
		$checkingCounts = mysqli_num_rows($sql3a);
		if($checkingCounts > 0)
		{
			// update the db to seen the answel

			$dataarray = mysqli_fetch_array($sql3a);
			$transId = $dataarray['id'];
			$status = $dataarray['status'];
			// CHECK IF YOU NEVER SAW IT BEFORE
			$checkRep = $outCon->query("SELECT id FROM  intouchResponses WHERE id = '$transId' AND statusStatus= 'seen'");

			if(!mysqli_num_rows($checkRep)>0)
			{
				$outCon->query("UPDATE intouchResponses SET statusStatus= 'seen' WHERE id = '$transId'");
				$sql2 = $outCon->query("UPDATE directtransfers SET status='$status' WHERE id = '$myId'") or die(mysql_error($outCon));
				$sql21 = $outCon->query("UPDATE intouchapi SET status='$status' WHERE myid = '$myId'") or die(mysql_error($outCon));
				if($outCon)
				{
					if($status == 'Successfull')
					{
						
						sleep(3);
						// SENDING PAYMENT REQUEST

						$url = 'https://www.intouchpay.co.rw/api/requestdeposit/';
			
						$phone = '25'.$pullNumber;
						$username="muhirwa.clement";
						$var_time = time();
						$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
						$generate_hash =  hash('sha256', $generate);
						$txt_id = md5(time());
						$data = array();
						
						$data["username"] 				= $username;
						$data["timestamp"] 				= $var_time;
						$data["amount"] 				= $amount;
					   	$data["withdrawcharge"] 		= 0;
						$data["reason"] 				= "Send Money";
						$data["sid"] 					= "1";
						$data["password"] 				= $generate_hash;
						$data["mobilephone"] 			= $phone;
						$data["requesttransactionid"]	= $txt_id;

					    $options = array(
							'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'  => 'POST',
								'content' => http_build_query($data)
							)
						);
						$context  = stream_context_create($options);
						$result = file_get_contents($url, false, $context);
						if ($result == false) 
						{ 
							$returnedinformation	= array();
							$returnedinformation[] = array(
						       		"transactionId" => $myId,
						       		"status" => "2 NETWORK ERROR"
						    	);
							header('Content-Type: application/json');
							$returnedinformation = json_encode($returnedinformation);
							echo $returnedinformation;
						}
						else
						{
							$result = json_decode($result);
								//Prepare data for db
							$success   				= $result->{'success'};
							$requesttransactionid   = $result->{'requesttransactionid'};
							$responsecode   		= $result->{'responsecode'};
							$referenceid   			= $result->{'referenceid'};

							
							// if the user recieved money
							if($success == true)
							{
								// TELL THE SENDER THAT THE MONEY HAS BEEN RECEIVED
								$sql1	=$outCon->query("SELECT actorName FROM directtransfers WHERE accountNumber = '$pushNumber' ORDER BY id desc LIMIT 1");
								$sql2	=$outCon->query("SELECT actorName FROM directtransfers WHERE accountNumber = '$pullNumber' ORDER BY id desc LIMIT 1");
								$row1	= mysqli_fetch_array($sql1);
								$row2	= mysqli_fetch_array($sql2);
								$pushName	= $row1['actorName'];
								$pullName	= $row2['actorName'];
								
								$recipients = '+25'.$pushNumber;
								$message    = 'Hi,'.$pushName.' '.number_format($amount).' Rwf has been received by '.$pullName.' user with '.$pullNumber.', Intouch is the Uplus agent in MTN Mobile Money.';
								$data = array(
									"sender"		=>'UPLUS',
									"recipients"	=>$recipients,
									"message"		=>$message,
								);
								include 'sms.php';
								// TELL THE RECEIVER THAT HE/SHE HAS RECEIVED MONEY
								$recipients = '+25'.$pullNumber;
								$message    = 'Hi, '.$pullName.' You have reived '.number_format($amount).' Rwf from '.$pushName.' a uplus user with '.$pushNumber.', Intouch is the Uplus agent in MTN Mobile Money.';
								$data = array(
									"sender"		=>'UPLUS',
									"recipients"	=>$recipients,
									"message"		=>$message,
								);
								include 'sms.php';

								$returnedinformation    = array();   
								$returnedinformation[] = array(
										"status" => "Successfull",
								        "transactionId" => $myId
								    );
								header('Content-Type: application/json');
								$returnedinformation = json_encode($returnedinformation);
								echo $returnedinformation;
							}
							elseif($success == false)
							{
								
								$url = 'https://www.intouchpay.co.rw/api/requestdeposit/';
			
								$phone 			= '25'.$pushNumber;
								$userName 		= "muhirwa.clement";
								$var_time 		= time();
								$generate 		= $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
								$generate_hash 	= hash('sha256', $generate);
								$txt_id 		= md5(time());
								$data 			= array();
								
								$data["username"] 				= $username;
								$data["timestamp"] 				= $var_time;
								$data["amount"] 				= $amount;
							   	$data["withdrawcharge"] 		= 0;
								$data["reason"] 				= "Refund";
								$data["sid"] 					= "1";
								$data["password"] 				= $generate_hash;
								$data["mobilephone"] 			= $phone;
								$data["requesttransactionid"]	= $txt_id;
								
							    $options = array(
									'http' => array(
										'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
										'method'  => 'POST',
										'content' => http_build_query($data)
									)
								);
								$context  = stream_context_create($options);
								$result = file_get_contents($url, false, $context);
								
								//WAS THE RUFUND SUCCESS IF NOT LET THE ADMIN KNOW
								$result = json_decode($result);
								$success   				= $result->{'success'};
								if($success == true)
								{
									// TELL THE SENDER IT DINT WORK WE REFUNDED YOU
									$recipients = '+25'.$pushNumber;
									$message    = 'Hi, We are very sorry, The transaction did not succeed, we have refunded you your '.$amount.' Rwf. If there is any problem, please call 0784848236, Intouch is the Uplus agent in MTN Mobile Money.';
									$data = array(
										"sender"		=>'UPLUS',
										"recipients"	=>$recipients,
										"message"		=>$message,
									);
									include 'sms.php';
									// Bwiuld the answel
									$returnedinformation    = array();   
									$returnedinformation[] 	= array(
											"status" 		=> "Refund",
									        "transactionId" => $myId
									    );

									header('Content-Type: application/json');
									$returnedinformation = json_encode($returnedinformation);
									echo $returnedinformation;

								}
								else
								{
									// TELL THE ADMIN FIRE FIRE
									$recipients = '+250784848236';
									$message    = 'Hey, '.$amount.' Rwf from '.$pushNumber.' to '.$pullNumber.' is stack. You have 15 min max to solve this issue https:uplus.rw/monitor.';
									$data = array(
										"sender"		=>'UPLUS DOWN',
										"recipients"	=>$recipients,
										"message"		=>$message,
									);
									include 'sms.php';

									// TELL THE SENDER IT DINT WORK WE ARE WORKING ON IT
									$recipients = '+25'.$pushNumber;
									$message    = 'Hi, We are very sorry, The transfer of '.$amount.' to '.$pullNumber.' did not succeed because of a technical issue, we are working on it, It wont take more than 15 min, If there is any problem, please call 0784848236.';
									$data = array(
										"sender"		=>'UPLUS',
										"recipients"	=>$recipients,
										"message"		=>$message,
									);
									include 'sms.php';
									// Bwiuld the answel
									$returnedinformation    = array();   
									$returnedinformation[] 	= array(
											"status" 		=> "Refund Error",
									        "transactionId" => $myId
									    );

									header('Content-Type: application/json');
									$returnedinformation = json_encode($returnedinformation);
									echo $returnedinformation;
								}
							}
						}
					}
					else
					{
						// Bwiuld the answel
						$returnedinformation    = array();   
						$returnedinformation[] 	= array(
								"status" => $status,
						        "transactionId" => $myId
						    );

						header('Content-Type: application/json');
						$returnedinformation = json_encode($returnedinformation);
						echo $returnedinformation;
					}
				}
				else
				{
					// Bwiuld the answel
					$returnedinformation    = array();   
					$returnedinformation[] 	= array(
							"status" => "Server Error",
					        "transactionId" => $myId
					    );

					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
			}
			else
			{
				// You saw it before Bwiuld the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
						"status" => $status,
				        "transactionId" => $myId
				    );

				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
			}
		}
		else
		{
			if($checkCount < 5)
			{
				sleep(7);
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
						"status" => 'Pending',
				        "transactionId" => $myId
				    );
			}
			elseif($checkCount < 10)
			{
				sleep(10);
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
					"status" => 'Pending',
			        "transactionId" => $myId
			    );
			}
			elseif($checkCount < 15)
			{
				sleep(20);
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
						"status" => 'Pending',
			        "transactionId" => $myId
			    );
			}
			elseif($checkCount > 17)
			{
				// Build the answel
				$returnedinformation    = array();   
				$returnedinformation[] 	= array(
					"status" => 'Pending',
			        "transactionId" => $myId
			    );
			}
			
			header('Content-Type: application/json');
			$returnedinformation = json_encode($returnedinformation);
			echo $returnedinformation;
		}
		mysqli_close($db);
		mysqli_close($outCon);	
	}

	/*
	function requestmoney()
	{
		$amount 	= $_POST['amount'];
		$pushId		= $_POST['senderId'];
		$pushName	= $_POST['senderName'];
		$pushNumber	= $_POST['senderPhone'];
		$pushBank	= $_POST['senderBank'];

		$pullName	= $_POST['receiverName'];
		$pullNumber	= $_POST['receiverPhone'];
		$pullBank	= $_POST['receiverBank'];
		
		//CLEAN PHONES
		$pushNumber = substr($pushNumber, -10); 
		$pullNumber = substr($pullNumber, -10); 

		require('db.php');

		//GET RECIEVER'S ID IF EXISTS
		$sql = $db->query("SELECT name, id FROM users WHERE phone = '$pullNumber' LIMIT 1");
		$checkAvailb = mysqli_num_rows($sql);
		if($checkAvailb > 0)
		{
			$row = mysqli_fetch_array($sql);
			$pullName	= $row['name'];
			$pullId		= $row['id'];
		}
		else
		{
			$pullId = 0;
		}

		$sql = $db->query("INSERT INTO requestandpay(
		requestor, responder, amount, account1,
		account2, status, createdDate, createdBy,
		updatedDate, updatedBy) 
		VALUES (
		'$pushId', '$pullId', '$amount', '$pushNumber',
		'$pullNumber', 'PENDING', now(),'$pushId', now(), '$pushId'
			)")or die error($db);

		// SEND AN SMS THAT YOU HAVE A PENDING INVOICE
	}

	function responsmoney()
	{
		$answer		= $_POST['answer'];
		if($answer	= 'yes')
		{
			$amount 	= $_POST['amount'];
			$pushId		= $_POST['senderId'];
			$pushName	= $_POST['senderName'];
			$pushNumber	= $_POST['senderPhone'];
			$pushBank	= $_POST['senderBank'];

			$pullName	= $_POST['receiverName'];
			$pullNumber	= $_POST['receiverPhone'];
			$pullBank	= $_POST['receiverBank'];
			
			//CLEAN PHONES
			$pushNumber = substr($pushNumber, -10); 
			$pullNumber = substr($pullNumber, -10);

			echo '$name, approved your request';
		}
		else
		{
			echo '$name, declined your request';
		}
	}
	*/
// END TRANSFERS

// STAR FINANCE
	function topup()
		{
		require('db.php');
		$amount 		= mysqli_real_escape_string($db, $_POST['amount']);
		$pushId			= "1";
		$pushName		= "me";
		$pushNumber		= mysqli_real_escape_string($db, $_POST['senderPhone']);
		$pushBank		= "1";
		$pullName		= "receiver";
		$pullNumber		= "0784848236";
		$pullBank		= "1";
		
		//CLEAN PHONES
		$pushNumber 	= preg_replace( '/[^0-9]/', '', $pushNumber );
		$pullNumber 	= preg_replace( '/[^0-9]/', '', $pullNumber );
		
		$pushNumber = substr($pushNumber, -10); 
		$pullNumber = substr($pullNumber, -10);
		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100; 
		
		//GET RECIEVER'S ID IF EXISTS
		$sql = $db->query("SELECT name, id FROM users WHERE phone = '$pullNumber' LIMIT 1");
		$checkAvailb = mysqli_num_rows($sql);
		if($checkAvailb > 0)
		{
			$row = mysqli_fetch_array($sql);
			$pullName	= $row['name'];
			$pullId		= $row['id'];
		}
		else
		{
			$pullId = 0;
		}
		// // SAVE THE TRANSACTION TO THE UPLUS DATABASE
		$outCon->query("INSERT INTO directtransfers
			(userId, actorName, accountNumber, bank, status, operation, amount) 
			VALUES 
			('$pushId', '$pushName','$pushNumber', '$pushBank', 'CALLED','DEBIT', '$amount'),
			('$pullId', '$pullName','$pullNumber', '$pullBank', 'CALLED','DEBIT', '$amount')
		
			") or mysqli_error($outCon);
		if($outCon)
		{
			$sqlRemovedId= $outCon->query("SELECT id FROM directtransfers ORDER BY id DESC LIMIT 1");
			$remId = mysqli_fetch_array($sqlRemovedId);
			$pullTransactionId = $remId['id'];
			$pushTransactionId = $pullTransactionId - 1;
			
			$url = 'https://www.intouchpay.co.rw/api/requestpayment/';
		
			$phone = '25'.$pushNumber;
			$username="muhirwa.clement";
			$var_time = time();
			$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
			$generate_hash =  hash('sha256', $generate);
			$txt_id = md5(time());
			$data = array();
			$data["username"] 				= $username;
			$data["timestamp"] 				= $var_time;
			$data["amount"] 				= $amount;
		    $data["password"] 				= $generate_hash;
			$data["partnerpassword"] 		= '8;b%-#K2$w\J3q{^dwr';
			$data["mobilephone"] 			= $phone;
			$data["requesttransactionid"]	= $txt_id;
			$data["accountid"] 				= '250150000003';
		    $options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			if ($result === FALSE) 
			{ 
				$Update= $outCon->query("UPDATE directtransfers SET status='NETWORK ERROR' WHERE id = '$pushTransactionId'");
				
				$returnedinformation	= array();
				
				$returnedinformation[] = array(
			       		"transactionId" => $pushTransactionId,
			       		"status" => "NETWORK ERROR"
			    	);
				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
			}
			else
			{
				$result = json_decode($result);
				//Prepare data for db
				$status 				= $result->{'status'};
				$requesttransactionid   = $result->{'requesttransactionid'};
				$success   				= $result->{'success'};
				$responsecode   		= $result->{'responsecode'};
				$transactionid  		= $result->{'transactionid'};
				$message   				= $result->{'message'};
				
				//SAVE THE TRANSACTION FROM MTN
				$sql = $outCon->query("INSERT INTO intouchapi(
						status, requesttransactionid, success, 
						responsecode, transactionid, message, 
						amount, pushNumber, pullNumber,  myid, type
					) 
					VALUES (
					'$status', '$requesttransactionid', '$success', 
					'$responsecode', '$transactionid', '$message', 
					'$amount', '$pushNumber', '$pullNumber', $pushTransactionId, 'directtransfer'
					)
				")or die(mysqli_error($outCon));
				// UPDATE MY DB
				$sql2 = $outCon->query("UPDATE directtransfers SET status='$status' WHERE id = '$pushTransactionId'") or die(mysql_error($outCon));
				$returnedinformation    = array();   
				$returnedinformation[] = array(
						"status" => $status,
				        "transactionId" => $pushTransactionId
				    );
				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
			}
		}
		else
		{
			echo 'error inserting a transation';
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function liquidate()
	{
		require('db.php');
		$pullNumber 	= mysqli_real_escape_string($db, $_POST['pullNumber']);
		$amount 		= mysqli_real_escape_string($db, $_POST['amount']);

		//CLEAN PHONES
		$pullNumber 	= preg_replace( '/[^0-9]/', '', $pullNumber );
		$pullNumber = substr($pullNumber, -10);

		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100; 


		$url = 'https://www.intouchpay.co.rw/api/requestdeposit/';
		//$amount 			=	100;
		$phone 				=	'25'.$pullNumber;
		$username			=	"muhirwa.clement";
		$var_time 			= time();
		$generate 			=  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
		$generate_hash 		=  hash('sha256', $generate);
		$txt_id 			= md5(time());
		$data 				= array();
		
		$data["username"] 				= $username;
		$data["timestamp"] 				= $var_time;
		$data["amount"] 				= $amount;
	   	$data["withdrawcharge"] 		= 1;
		$data["reason"] 				= "Send Money";
		$data["sid"] 					= "1";
		$data["password"] 				= $generate_hash;
		$data["mobilephone"] 			= $phone;
		$data["requesttransactionid"]	= $txt_id;
		
	    $options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result == false) 
		{ 
			$returnedinformation	= array();
			$returnedinformation[] = array(
		       		"status" => "NETWORK ERROR"
		    	);
			header('Content-Type: application/json');
			$returnedinformation = json_encode($returnedinformation);
			echo $returnedinformation;
		}
		else
		{
			$result = json_decode($result);
				//Prepare data for db
			$success   					= $result->{'success'};
			$requesttransactionid   	= $result->{'requesttransactionid'};
			$responsecode   			= $result->{'responsecode'};

			
			// if the use recieved money
			if($success == true)
			{

					$referenceid   				= $result->{'referenceid'};


				$returnedinformation    = array();   
				$returnedinformation[] = array(
						"success" => $success,
						"requesttransactionid" => $requesttransactionid,
						"responsecode" => $responsecode,
						"referenceid" => $referenceid 
				    );

				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
			}
			elseif($success == false)
			{

				echo 'Refund proccess';
				$returnedinformation    = array();   
				$returnedinformation[] = array(
						"success" => $success,
						"requesttransactionid" => $requesttransactionid,
						"responsecode" => $responsecode
				    );

				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
				
				
			}
		}
	}

	function balance()
	{
		$url = 'https://www.intouchpay.co.rw/api/getbalance/';

		$username="muhirwa.clement";
		$var_time = time();
		$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
		$generate_hash =  hash('sha256', $generate);
		$txt_id = md5(time());
		$data = array();
		$data["username"] 				= $username;
		$data["password"] 				= $generate_hash;
		$data["timestamp"] 				= $var_time;
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		//echo $result;
		if ($result === FALSE) 
		{ 
			$returnedinformation	= array();
			
			$returnedinformation[] = array(
		       		"status" => "NETWORK ERROR"
		    	);
			header('Content-Type: application/json');
			$returnedinformation = json_encode($returnedinformation);
			echo $returnedinformation;
		}
		else
		{
			$result = json_decode($result);
			//Prepare data for db
			$success 				= $result->{'success'};
			$returnedinformation    = array();   
			
			if($success == true)
			{
				$balance = $result->{'balance'};
				$returnedinformation[] = array(
					"balance" => $balance
			    );
			}
			else
			{
				$responsecode = $result->{'responsecode'};
				$message = $result->{'message'};
				$returnedinformation[] = array(
					"responsecode" => $responsecode,
					"message" => $message

			    );
			}
			
			header('Content-Type: application/json');
			$returnedinformation = json_encode($returnedinformation);
			echo $returnedinformation;
		}
	}
// END FINANCE

// START DOS
	function notification()
	{
		require('db.php');
		$selection 		= mysqli_real_escape_string($db, $_POST['selection']);
			
		function send_notification ($tokens, $message)
		{
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array(
				 'registration_ids' => $tokens,
				 'data' => $message
				);

			$headers = array(
				'Authorization:key = AIzaSyCVsbSeN2qkfDfYq-IwKrnt05M1uDuJxjg',
				'Content-Type: application/json'
				);

		   $ch = curl_init();
	       curl_setopt($ch, CURLOPT_URL, $url);
	       curl_setopt($ch, CURLOPT_POST, true);
	       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	       $result = curl_exec($ch);           
	       if ($result === FALSE) {
	           die('Curl failed: ' . curl_error($ch));
	       }
	       curl_close($ch);
	       return $result;
		}	


		if($selection == "single")
		{
			$userId 		= mysqli_real_escape_string($db, $_POST['userId']);
			$sql = $db->query("SELECT token FROM users WHERE id = '$userId'");
		}
		elseif($selection == "all")
		{
			$sql = $db->query("SELECT token FROM users WHERE token IS NOT NULL");
		}
		
		$tokens = array();

		if(mysqli_num_rows($sql) > 0 ){
			while ($row = mysqli_fetch_assoc($sql)) {
				$tokens[] = $row["token"];
			}
		}

		mysqli_close($db);

		$message = array("message" => $_POST['message']);
		$message_status = send_notification($tokens, $message);
		header('Content-Type: application/json');
		echo $message_status;
	}

	function clean()
	{

		function cleangroups()
		{
			include"db.php";
			
			$db->query("TRUNCATE groupuser");
			if($db)
			{
				$outCon->query("TRUNCATE grouptransactions");
				$outCon->query("TRUNCATE requestsdecisions");
				$outCon->query("TRUNCATE withdrowrequests");
				$outCon->query("TRUNCATE groups");
				$db->query("TRUNCATE groups");
				$files = glob('../groupimg/*'); // get all file names
				// foreach($files as $file){ // iterate files
				//   if(is_file($file))
				//     unlink($file); // delete file
				// }

				echo 'GROUPS CLEAN SUCCESSFULLY';
			}
			else
			{
				echo 'GROUS DID NOT CLEAN';
			}
			mysqli_close($db);
			mysqli_close($outCon);
		}
		function cleanusers()
		{
			include"db.php";
			$db->query("TRUNCATE users");
			if($db)
			{
				echo 'USERS CLEAN SUCCESSFULLY';
				$db->query("TRUNCATE groups");
				// $files = glob('../profileimg/*'); // get all file names
				// foreach($files as $file){ // iterate files
				//   if(is_file($file))
				//     unlink($file); // delete file
				// }
				cleangroups();
			}

			else
			{
				echo 'USERS DID NOT CLEAN';
			}
			mysqli_close($db);
			mysqli_close($outCon);
		}
		if(isset($_POST['cleaner']))
		{
			//BACKUP THE INTIRE DATABASE

			$_POST['cleaner']();
		}
	}

	function sms()
	{
		$phone 		= $_POST['phone'];
		$sender 	= $_POST['sender'];
		$message 	= $_POST['message'];
			

		//CLEAN PHONE
		$phone 	= preg_replace( '/[^0-9]/', '', $phone );
		$phone 	= substr($phone, -10); 

		$recipients = '+25'.$phone;
		//$message    = 'Welcome to UPLUS, please use '.$code.' to log into your account.';
		$data = array(
			"sender"		=>$sender,
			"recipients"	=>$recipients,
			"message"		=>$message,
		);
		include 'sms.php';
		if($httpcode == 200)
		{
			echo 'done';
		}
		else
		{
			echo 'System error';
		}
	} 
// END DOS

// START PARTNERS
	function c2b()
	{
		require('db.php');

		$groupId		= mysqli_real_escape_string($db, $_POST['partnerId']);
		$amount 		= mysqli_real_escape_string($db, $_POST['amount']);
		$pushNumber 	= mysqli_real_escape_string($db, $_POST['pushNumber']);
		$pushBank		= mysqli_real_escape_string($db, $_POST['pushBank']);

		//CLEAN PHONE
		$pushNumber 	= preg_replace( '/[^0-9]/', '', $pushNumber );
		$pushNumber 	= substr($pushNumber, -10); 

		//GET THE MEMBER ID
		if (isset($_POST['memberId'])) 
		{
			$memberId	= mysqli_real_escape_string($db, $_POST['memberId']);
		}
		else
		{
			$sqlMId = $db->query("SELECT id FROM users WHERE phone = '$pushNumber' LIMIT 1");
			if(mysqli_num_rows($sqlMId)>0)
			{
				$rowMId = mysqli_fetch_array($sqlMId);
				$memberId = $rowMId['id'];
			}
			else
			{
				$memberId = 1;
			}
		}

		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100;


		// GET USER'S INFROMATION
		$sql = $db->query("SELECT groupName, memberName FROM members WHERE groupId = '$groupId' AND memberId = '$memberId' LIMIT 1");
		
		if($db)
		{
			while($row = mysqli_fetch_array($sql))
			{
				$groupName 		= $row['groupName'];
				$memberName		= $row['memberName'];
			}

			// SAVE THE TRANSACTION TO THE UPLUS DATABASE
			$sql = $outCon->query("INSERT INTO grouptransactions(
				memberId, groupId, amount, fromPhone, 
				bankId, operation, status, updatedBy, updatedDate)
			 	VALUES ('$memberId', '$groupId', '$amount', '$pushNumber', 
			 	'$pushBank', 'DEBIT', 'CALLED', '1', now())")
			 	 or mysqli_error($outCon);

			if($outCon)
			{
				// GET THE TRANSACTION ID
				$sqlRemovedId		= $outCon->query("SELECT id FROM grouptransactions ORDER BY id DESC LIMIT 1");
				$remId 				= mysqli_fetch_array($sqlRemovedId);
				$pushTransactionId 	= $remId['id'];

				//CALL THE API
				$url = 'https://www.intouchpay.co.rw/api/requestpayment/';
		
				$phone = '25'.$pushNumber;
				$username="muhirwa.clement";
				$var_time = time();
				$generate =  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
				$generate_hash =  hash('sha256', $generate);
				$txt_id = md5(time());
				$data = array();
				$data["username"] 				= $username;
				$data["timestamp"] 				= $var_time;
				$data["amount"] 				= $amount;
			    $data["password"] 				= $generate_hash;
				$data["partnerpassword"] 		= '8;b%-#K2$w\J3q{^dwr';
				$data["mobilephone"] 			= $phone;
				$data["requesttransactionid"]	= $txt_id;
				$data["accountid"] 				= '250150000003';

			    $options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data)
					)
				);
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				if ($result === FALSE) 
				{ 
					// API ERROR/ NETWORK ERROR
					$Update= $outCon->query("UPDATE grouptransactions SET status='NETWORK ERROR' WHERE id = '$pushTransactionId'");
					
					$returnedinformation	= array();
					
					$returnedinformation[] = array(
				       		"transactionId" => $pushTransactionId,
				       		"status" => "NETWORK ERROR",
						"memberId"	=> $memberId
				    	);
					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
				else
				{
					$result = json_decode($result);

					$success   				= $result->{'success'};
					if($success === FALSE){
						//Prepare data for db
						$status 				= "Failed";
						$requesttransactionid   = $pushTransactionId;

						$responsecode   		= $result->{'responsecode'};
						$transactionid  		= "no";
						$message   				= $result->{'message'};
					
					}
					else
					{
						//Prepare data for db
						$status 				= $result->{'status'};
						$requesttransactionid   = $result->{'requesttransactionid'};
						$responsecode   		= $result->{'responsecode'};
						$transactionid  		= $result->{'transactionid'};
						$message   				= $result->{'message'};
					}
					
					//SAVE THE TRANSACTION FROM MTN
					$sql = $outCon->query("INSERT INTO intouchapi(
							status, requesttransactionid, success, 
							responsecode, transactionid, message, 
							amount, pushNumber, pullNumber, myid, type
						) 
						VALUES (
						'$status', '$requesttransactionid', '$success', 
						'$responsecode', '$transactionid', '$message', 
						'$amount', '$pushNumber', '$groupId', '$pushTransactionId', 'grouptransaction'
						)
					")or die(mysqli_error($outCon));
					// UPDATE MY DB
					$sql2 = $outCon->query("UPDATE grouptransactions SET status='$status' WHERE id = '$pushTransactionId'") or die(mysql_error($outCon));

					$returnedinformation    = array();   
					$returnedinformation[] = array(
					       	"transactionId" => $pushTransactionId,
							"status" => $status,
							"memberId"	=> $memberId
					    );

					header('Content-Type: application/json');
					$returnedinformation = json_encode($returnedinformation);
					echo $returnedinformation;
				}
			}
			else
			{
				echo 'error inserting a transation';
			}
		}
		mysqli_close($db);
		mysqli_close($outCon);
	}

	function b2c()
	{
		require('db.php');
		$pullNumber 	= mysqli_real_escape_string($db, $_POST['pullNumber']);
		$amount 		= mysqli_real_escape_string($db, $_POST['amount']);

		//CLEAN PHONES
		$pullNumber 	= preg_replace( '/[^0-9]/', '', $pullNumber );
		$pullNumber = substr($pullNumber, -10);

		//CLEAN AMOUNT
		$amount	= floor($amount/100)*100; 


		$url = 'https://www.intouchpay.co.rw/api/requestdeposit/';
		//$amount 			=	100;
		$phone 				=	'25'.$pullNumber;
		$username			=	"muhirwa.clement";
		$var_time 			= time();
		$generate 			=  $username.'250150000003'.'8;b%-#K2$w\J3q{^dwr'.$var_time;
		$generate_hash 		=  hash('sha256', $generate);
		$txt_id 			= md5(time());
		$data 				= array();
		
		$data["username"] 				= $username;
		$data["timestamp"] 				= $var_time;
		$data["amount"] 				= $amount;
	   	$data["withdrawcharge"] 		= 1;
		$data["reason"] 				= "Send Money";
		$data["sid"] 					= "1";
		$data["password"] 				= $generate_hash;
		$data["mobilephone"] 			= $phone;
		$data["requesttransactionid"]	= $txt_id;
		
	    $options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result == false) 
		{ 
			$returnedinformation	= array();
			$returnedinformation[] = array(
		       		"status" => "NETWORK ERROR"
		    	);
			header('Content-Type: application/json');
			$returnedinformation = json_encode($returnedinformation);
			echo $returnedinformation;
		}
		else
		{
			$result = json_decode($result);
				//Prepare data for db
			$success   					= $result->{'success'};
			$requesttransactionid   	= $result->{'requesttransactionid'};
			$responsecode   			= $result->{'responsecode'};

			
			// if the use recieved money
			if($success == true)
			{

					$referenceid   				= $result->{'referenceid'};


				$returnedinformation    = array();   
				$returnedinformation[] = array(
						"success" => $success,
						"requesttransactionid" => $requesttransactionid,
						"responsecode" => $responsecode,
						"referenceid" => $referenceid 
				    );

				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
			}
			elseif($success == false)
			{

				echo 'Refund proccess';
				$returnedinformation    = array();   
				$returnedinformation[] = array(
						"success" => $success,
						"requesttransactionid" => $requesttransactionid,
						"responsecode" => $responsecode
				    );

				header('Content-Type: application/json');
				$returnedinformation = json_encode($returnedinformation);
				echo $returnedinformation;
				
				
			}
		}
	}
// END PARTNERS	

// Just forum
	function list_forums()
	{

		require('db.php');
		$sql = $churchDb->query("SELECT * FROM forums");	
		$forums = array();
		while ($forum = mysqli_fetch_array($sql))
		{
			$forums[] = array(
				"forumtId"			=> $forum['id'],
				"forumTitle"		=> $forum['forumtitle'],
				"forumSubtitle"		=> $forum['intro'],
				"forumlogo"			=> $forum['logo']
			);
		}
		header('Content-Type: application/json');
		$forums = json_encode($forums);
		echo $forums;
	}
// End test forum
?>
