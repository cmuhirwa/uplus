<?php
$body="";
if(isset($_FILES['contactsFile'])){
$filename = basename($_FILES['contactsFile']['name']);
$path = 'docs/'.$filename;
move_uploaded_file($_FILES['contactsFile']['tmp_name'], $path);
//echo $filename;

include "db.php";
		
// EXCEL BULK INVITATIONS
include ("classes/PHPExcel/IOFactory.php");  
$objPHPExcel = PHPExcel_IOFactory::load($path);  
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
{  
	$sqlemptyTable = $db->query ("TRUNCATE items")or die (mysqli_error());
	$n=0;		
	$highestRow = $worksheet->getHighestRow();
	for ($row=2; $row<=$highestRow; $row++)
	{
		$n++;
		$kode = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
		$itemName = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
		$unityPrice = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
		$unit = mysqli_real_escape_string($db, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
		$sqlsaveexcel = $db->query ("INSERT INTO items (itemName, unityPrice, kode, unit) 
			VALUES ('$itemName', '$unityPrice', '$kode', '$unit')")or die (mysqli_error());
	}
	$body.='Birakozwe. Ibituruzwa Byose bivuyemo, hagiyemo ibikoresho '.$n.' bishya! <a href="index.php">Kanda Hano</a>.';
}
}else{
	$body.='
		<form action="testsms.php" method="post" enctype="multipart/form-data">
		  <input type="file" name="contactsFile">
		  <input type="submit" value="upload">
		</form>';
}
// END EXCEL BULK INVITATIONS
?>



<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<?php echo $body;?>
</body>
</html>