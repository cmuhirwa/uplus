<?php
// include_o
$fileName = $_FILES["file1"]["name"]; // The file name
$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["file1"]["type"]; // The type of file it is
$fileSize = $_FILES["file1"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
$church = $_POST['church'];

if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
}

if(move_uploaded_file($fileTmpLoc, "../docs/contacts.xls")){
			testupload();
			//echo "$fileName upload is complete <a href='testupload.php'>click here!</a>";
} else {
    echo "move_uploaded_file function failed";
}

function testupload(){
	include '../db.php';
	$n=0;
	global $church;
	// EXCEL BULK INVITATIONS
	include ("../classes/PHPExcel/IOFactory.php");
	$objPHPExcel = PHPExcel_IOFactory::load('../docs/contacts.xls');
	// LOOP BULK CONTACTS: 1.INSERTING NEW, 2.CONNECTING TO THE ACCOUNT, 3.SENDING EMAILS
	$queries = "";
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
	{
		$highestRow = $worksheet->getHighestRow();
		for ($row=2; $row<=$highestRow; $row++)
		{
			$names = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
			$phone = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
			$email = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
			$location = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
			$type = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(4, $row)->getValue());

			//Getting location id like bach
			$loc = $conn->query("SELECT * FROM branches WHERE church = \"$church\" AND name = \"$location\" LIMIT 1") or die("Can't get branch". $conn->error);
			$loc = $loc->fetch_assoc();

			$location = $loc['id'];


			$queries.="INSERT INTO 
			members 
			(name, phone, location, type, createdDate) 
			VALUES ('$names', '$phone', '$location', '$type', NOW());<br/>";
			$sql = $db->query("INSERT INTO 
				members 
				(name, phone, branchId, type, createdDate) 
				VALUES ('$names', '$phone', '$location', '$type', NOW());") or die($conn->error);
		$n++;
		}
		echo '<a href="members.php">'.$n.' uploaded! Click Here.</a><br/>';
	}
}
?>
