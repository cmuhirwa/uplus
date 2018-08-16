<?php
	// EXCEL BULK INVITATIONS
	include ("../db.php");
	include ("PHPExcel/IOFactory.php");
	$objPHPExcel = PHPExcel_IOFactory::load('../docs/items.xls');
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
	{
		$sqlemptyTable = $db->query ("TRUNCATE items")or die (mysqli_error());
		$n=0;		
		$highestRow = $worksheet->getHighestRow();
		for ($row=2; $row<=$highestRow; $row++)
		{
			$n++;
			$itemName = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
			$unityPrice = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
			$kode = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
			$sqlsaveexcel = $db->query ("INSERT INTO items (itemName, unityPrice, kode) 
				VALUES ('$itemName', '$unityPrice', '$kode')")or die (mysqli_error());
		}
	echo 'Birakozwe, Ibikoresho Byose bivuyemo hagiyemo ibikoresho '.$n.' bishya!';
	}
?>