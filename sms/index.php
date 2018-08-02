<?php
$amount = Rand(2000, 5000);
	$amount	= floor($amount/1000)*1000; 
echo $amount;
/*
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include 'db.php';
// EXCEL BULK INVITATIONS
	include ("classes/PHPExcel/IOFactory.php");
	$objPHPExcel = PHPExcel_IOFactory::load('contacts.xlsx');
	// LOOP BULK CONTACTS: 1.INSERTING NEW, 2.CONNECTING TO THE ACCOUNT, 3.SENDING EMAILS 
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
	{
		$highestRow = $worksheet->getHighestRow();
		for ($row=2; $row<=$highestRow; $row++)
		{
			$names = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
			$phone = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
			$phone 	= preg_replace( '/[^0-9]/', '', $phone );
			$phone 	= '0'.substr($phone, -9); 

		$sql = $db->query("INSERT INTO 
			users 
			(name, phone) 
			VALUES ('$names', '$phone');");
		
		}echo "<a href='company.php'>Click here</a>";
	*/
?>