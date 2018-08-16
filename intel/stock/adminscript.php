<?php
	require("db.php");
	require_once '../functions.php';
	require_once '../dashboard/auth.php';

// STOCK SETUP
// 1 FIELD TO ADD A NEW ITEM
if(isset($_GET['initItem']))
{
	include '../core/measurement.php';	
?>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>N/O</th>
			<th>Item Name</th>
			<th>Item Code</th>
			<th>Measurement Unit</th>
			<th>Unit Price</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<tr class="gradeX">
			<td>#</td>
			<td><input class="form-control" name="newItemName" id="newItemName"/></td>
			<td><input class="form-control" name="newItemCode" id="newItemCode"/></td>
			<td>
				<!-- <input class="form-control" name="newItemUnit" id="newItemUnit"/> -->
				<select class="form-control" id="newItemUnit" name="newItemUnit">
					<option>Measurement unit</option>
					<?php
						$unitLists = $Measurements->listUnits();
						foreach ($unitLists as $key => $unit) {
							$unitSymb = $unit['symbol'];
							$unitName = $unit['name'];
							echo "<option value=$unitSymb>$unitSymb - $unitName</option>";
						}
					?>
				</select>
			</td>
			<td><input type="number" class="form-control" name="newItemPrice" id="newItemPrice"></td>
			<td class="actions">
				<button onclick="newItem()"  class="btn btn-success  btn-custom waves-effect m-b-5"><i class="fa fa-save success"></i></button>
				&nbsp;&nbsp;&nbsp;
				<button onclick="setuptable()" class="btn btn-danger  btn-custom waves-effect m-b-5"><i class="fa fa-times"></i></button>
				
			</td>
		</tr>
	</tbody>
</table>
<?php
}
// ADD AN ITEM 
if(isset($_GET['newItemName']))
{
	$itemName = $_GET['newItemName'];
	$itemCode = $_GET['newItemCode'];
	$unityPrice = $_GET['newItemPrice'];
	$unit = $_GET['newItemUnit'];
	$sqlcheck = $db->query("SELECT kode FROM items where kode='$itemCode'");
	$countCHeck = mysqli_num_rows($sqlcheck);
	if(!$countCHeck > 0){
		
	$sql = $db->query("INSERT INTO `items`(`itemName`, `kode`, unit, `unityPrice`, `inDate`, `addedBy`) 
	VALUES ('$itemName', '$itemCode', '$unit','$unityPrice',now(),'me')
	")or die(mysqli_error());
	echo'<script>	
(function(){
 setuptable();
})();
	</script>';
	}else{
		echo'This item you are trying to add has the same RRA Code as the anothe in the db';
	}
}

if(isset($_GET['updateItemName']))
{
	$itemName = $_GET['updateItemName'];
	$itemCode = $_GET['updateItemCode'];
	$unityPrice = $_GET['updateItemPrice'];
	$itemId = $_GET['updateItemId'];
	$unit = $_GET['updateItemUnit'];
	$sql = $db->query("UPDATE `items` SET `itemName`='$itemName', `kode`='$itemCode', `unit`='$unit',`unityPrice`='$unityPrice' WHERE `itemId` ='$itemId'") or die(mysqli_error());
	//echo $itemName;
	echo'<script>	(function(){ setuptable();})();	</script>';
}

// 2 FIELD TO EDDIT AN ITEM
if(isset($_GET['editItem']))
{
	$itemId = $_GET['editItem'];
	$sql = $db->query("SELECT * FROM `items` WHERE `itemId`='$itemId'")or die(mysqli_error());
	WHILE($row= mysqli_fetch_array($sql))
		{
			echo'
			<table id="datatable-buttons" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>N/O</th>
							<th>Item Name</th>
							<th>Item Code</th>
							<th>Measurement unit</th>
							<th>Unit Price</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
				<tr class="gradeX">
					<td>#</td>
					<td>
					<input class="form-control" name="updateItemName" id="updateItemName" value="'.$row['itemName'].'"/>
					<input name="updateItemId" id="updateItemId" value="'.$itemId.'" hidden/>
					</td><td>
					<input class="form-control" name="updateItemCode" id="updateItemCode" value="'.$row['kode'].'"/>
					</td>
					<td><input type="text" class="form-control" name="updateItemUnit" id="updateItemUnit" value="'.$row['unit'].'"></td>
					<td><input type="number" class="form-control" name="updateItemPrice" id="updateItemPrice" value="'.$row['unityPrice'].'"></td>
					<td class="actions">
						<button onclick="changeItem()"  class="btn btn-success  btn-custom waves-effect m-b-5"><i class="fa fa-save success"></i></button>
						&nbsp;&nbsp;&nbsp;
						<button onclick="setuptable()" class="btn btn-danger  btn-custom waves-effect m-b-5"><i class="fa fa-times"></i></button>
						
					</td>
				</tr>
					 </tbody>
				</table>';
		}
}
// 3 IF REMOCE ITEM IN THE STOCK THEN REMOVE IT
if(isset($_GET['removeItem']))
{
	$itemId = $_GET['removeItem'];
	$sql = $db->query("DELETE FROM `items` WHERE itemId='$itemId'")or die(mysqli_error());
	echo'<script>	(function(){ setuptable();})();	</script>';
}
// bring the listing table
if(isset($_GET['setuptable']))
{
echo'
<div class="row">
	<div class="col-sm-6">
		<div class="m-b-30">
			<button id="addToTable" onclick="initItem()" class="btn btn-primary waves-effect waves-light">Add <i class="fa fa-plus"></i></button>
		&nbsp;
		&nbsp;
		<a href="list.php" class="btn btn-icon waves-effect waves-light btn-warning"><i class="fa fa-wrench"></i>
		<span>Fix</span>
		</a>

		</div>
	</div>
</div>
<table id="datatable-buttons" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>N/O</th>
			<th>Item Name</th>
			<th>Item Code</th>
			<th>Unity</th>
			<th>Unity Price</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>';
$sql = $db->query("SELECT * FROM items ORDER BY itemId DESC");
$n=0;
WHILE($row= mysqli_fetch_array($sql))
{
	$n++;
	echo'
		<tr class="gradeX">
			<td>'.$n.'</td>
			<td>'.$row['itemName'].'</td>
            <td>'.$row['kode'].'</td>
			<td>'.$row['unit'].'</td>
			<td>'.number_format($row['unityPrice']).' Rwf</td>
			<td class="actions">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:void()" onclick="editItem(itemId='.$row['itemId'].')"><i class="fa fa-pencil text-primary"></i></a>
				&nbsp;&nbsp;&nbsp;
				 <a href="javascript:void()" onclick="vanamo(itemId='.$row['itemId'].')" class="danger"><i class="fa fa-trash-o text-danger"></i></a>
            </td>
		</tr>
	';
}
echo'
</tbody>
</table>';
}

?>

<?PHP // INJECTION OPERATIONS
// BRING INPUTS ON THE INJECT FORM
if(isset($_GET['itemIdtoGet']))
{
	$itemId = $_GET['itemIdtoGet'];
	$sql = $db->query("SELECT * FROM items WHERE itemId ='	$itemId'");
	$countout = mysqli_num_rows($sql);
	if($countout>0)
	{
	WHILE($row= mysqli_fetch_array($sql))
	{
		$unit = $row['unit'];
		$unityPrice = $row['unityPrice'];
	}
	echo'
<div class="col-sm-2">
	<div class="form-group"> 
		<label for="itemCode" class="control-label">Quantity:</label>
		<div class="input-group">
			<input required name="qty" id="qty" onkeyup="purchaseTotal()" class="form-control"/>
			<span class="input-group-addon">'.$unit.'</span>
		</div>
	</div>
</div>

<div class="col-sm-2">
	<div class="form-group"> 
		<label for="itemCode" class="control-label">Unit Price:</label>
		<input required name="unityPrice" id="unityPrice" onkeyup="purchaseTotal()" placeholder=" < '.number_format((int)$unityPrice).'" class="form-control"/>
	</div>
</div>

<div class="col-sm-3">
	<div class="form-group"> 
		<label for="itemCode" class="control-label">Total Price:</label>
		<div class="input-group" id="purchaseTotalPrice" >
			<input class="form-control"  disabled/>
			<span class="input-group-addon">RWF</span>
		</div>
	</div>
</div>

<div class="col-sm-2">
<br/>
	<div class="">
		<button class="btn btn-primary waves-effect waves-light" onclick="insertItem()" id="addPurchaseItem">Add <i class="fa fa-plus"></i></button>
	</div>
	</div>';}else{}	
}
// REVERT A TRANSACTION ON THE INJECT
if(isset($_GET['removeTransaction']))
{
	$transactionID = $_GET['removeTransaction'];
	$sql = $db->query("DELETE FROM `transactions` WHERE transactionID='$transactionID'")or die(mysqli_error());
}
// INSERT AN ITEM
if(isset($_GET['operationNotes']))
{
	$unityPrice = $_GET['unityPrice'];
	$qty = $_GET['qty'];
	$itemCode = $_GET['itemCode1'];
	$operation = 'In';
	$purchaseOrder = $_GET['purchaseOrder'];
	$deliverlyNote = $_GET['deliverlyNote'];
	$docRefNumber = $_GET['docRefNumber'];
	$customerName = $_GET['customerName'];
	$customerRef = $_GET['customerRef'];
	$operationNotes = $_GET['operationNotes'];
	$operationStatus = 1;
	$doneBy = 1;

	$sql = $db->query("INSERT INTO `transactions`
	(`trUnityPrice`, `qty`, 
	`itemCode`, `operation`, `purchaseOrder`,
	`deliverlyNote`, `docRefNumber`, `customerName`, 
	`customerRef`, `operationNotes`, `operationStatus`, `doneOn`, doneBy) 
	VALUES 
	('$unityPrice','$qty',
	'$itemCode','$operation','$purchaseOrder',
	'$deliverlyNote','$docRefNumber','$customerName',
	'$customerRef','$operationNotes','$operationStatus', now(), '$doneBy')
	")or die(mysqli_error());
	$sql2 = $db->query("SELECT `itemName` FROM `items` WHERE `itemId` = '$itemCode' ");
	WHILE($row = mysqli_fetch_array($sql2))
	{
		echo '<div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <a href="#" class="alert-link">'.$row['itemName'].'</a> was Succesfuly added on the list.
                                        </div>';
	}

}
// CHECK IF PURCHASE ORDER EXISTS
if(isset($_GET['purchaseOrder']))
{
	$purchaseOrder = $_GET['purchaseOrder'];
	$deliverlyNote = $_GET['deliverlyNote'];
	$sql = $db->query("
	SELECT 
	T.`transactionID`, T.`itemCode`,I.`itemName`,
	T.`qty`,I.`unit`,  T.`trUnityPrice`, 
	T.`qty` * T.`trUnityPrice` AS Total,`operation`
	FROM `transactions` T INNER JOIN `items` I 
	ON T.`itemCode` = I.`itemId` WHERE `purchaseOrder`='$purchaseOrder' AND `deliverlyNote` = '$deliverlyNote' AND `operation` = 'in'")or die(mysqli_error());

	$countout = mysqli_num_rows($sql);

	if($countout > 0)
	{
		$n=0;
		echo'<table class="table table-hover ">
			<thead>
				<tr>
					<th>#</th>
					<th>Item Name</th>
					<th>Quantity</th>
					<th>Unit Price</th>
					<th>Total Price</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>';
	WHILE($row= mysqli_fetch_array($sql))
		{
			$n++;
			
					$qty1 = number_format($row['qty']);
					$up1 = $row['trUnityPrice'];
					$totalprice = number_format($qty1 * $up1);
					echo'<tr>
							<td>'.$n.'</td>
							<td>'.$row['itemName'].'</td>
							<td>'.number_format($row['qty']).'</td>
							<td>'.number_format($row['trUnityPrice']).' Rwf</td>
							<td>'.number_format($row['Total']).' Rwf</td>
							<td class="actions">
							&nbsp;&nbsp;&nbsp;
							<a href="javascript:void()" onclick="removeOnPo(removeTransaction='.$row['transactionID'].')" class="danger"><i class="fa fa-trash-o text-danger"></i></a>
							</td>
						</tr>';						
		}
	}
}
?>

<?PHP // SELING OPERATIONS
// GENERATE THE INVIOCE ID
if(isset($_GET['generateINV']))
{
//insert new serie
	$sql1 = $db->query("INSERT INTO `serieid` (`userOn`) VALUES ('$username')")or die(mysqli_error());	
//get new SN		
$sql = $db->query("SELECT COUNT(serieID)+1 AS transactionID FROM `serieid` WHERE YEAR(serieDate) = YEAR(CURDATE()) ");
WHILE($row= mysqli_fetch_array($sql))
	{
		$transactionID = $row['transactionID'];
	}
	echo '<input name="InvoinceNo" id="InvoinceNo" onkeyup="bringPrint();checkInvoince();" class="form-control" value="INV'.date("Y").'-'.$transactionID.'">
<script>	
(function(){
 checkInvoince();
})();
	</script>
	';

	}
// ITEMS TO PUT IN THE SELECT
if(isset($_GET['invioceItemIdtoGet']))
{
	$invioceItemIdtoGet = $_GET['invioceItemIdtoGet'];
	$sql = $db->query("SELECT I.`itemId`, I.`itemName`,
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND T.`itemCode` = I.`itemId`),0) -
IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND T.`itemCode` = I.`itemId`),0)  Balance
,I.`unit`, I.`unityPrice`
FROM `items` I

WHERE I.`itemId` = '$invioceItemIdtoGet'");
	$countout = mysqli_num_rows($sql);
	if($countout>0)
	{
	WHILE($row= mysqli_fetch_array($sql))
	{
		$unit = $row['unit'];
		$unityPrice = $row['unityPrice'];
		$limite = number_format($row['Balance'], 0, '', '');
		$limiteShow = number_format($row['Balance'], 2, '.', ',');
	}
	echo'
<div class="col-sm-3">
	<div class="form-group"> 
		<label for="itemCode" class="control-label">Quantity:</label>
		<div class="input-group">
			<span class="input-group-addon">'.$limiteShow.'</span>
			<input required name="InvoiceQty" min="'.$limite.'" onkeyup="invoiceTotal()" id="InvoiceQty" class="form-control"/>
			<input required value="'.$limite.'" id="limiter" name="limiter" hidden/>
			<span class="input-group-addon">'.$unit.'</span>
		</div>
	</div>
</div>

<div class="col-sm-2">
	<div class="form-group"> 
		<label for="itemCode" class="control-label">Unit Price:</label>
		<input required name="InvioceUnityPrice" id="InvioceUnityPrice" value="'.$unityPrice.'" onkeyup="invoiceTotal()" class="form-control"/>
	</div>
</div>

<div class="col-sm-2">
	<div class="form-group"> 
		<label for="itemCode" class="control-label">Total Price:</label>
		<div class="input-group" id="invoiceTotalPrice">
			<input class="form-control" disabled/>
			<span class="input-group-addon">F</span>
		</div>
	</div>
</div>

<div class="col-sm-2">
<br/>
	<div class="">
		<button class="btn btn-primary waves-effect waves-light" onclick="ouItem()">Add <i class="fa fa-plus"></i></button>
	</div>
	</div>';}else{}	
}

if(isset($_GET['thenthis']))
{
	echo'it works';
}
// OUT AN ITEM
if(isset($_GET['InvoiceDeliverlyNote']))
{
	
	$unityPrice = $_GET['InvoiceUnityPrice'];
	$qty = $_GET['InvioceQty'];
	$itemCode = $_GET['itemInvoiceCode'];
	$operation = 'Out';
	$purchaseOrder = $_GET['InvoinceNo'];
	$deliverlyNote = $_GET['InvoiceDeliverlyNote'];
	$docRefNumber = $_GET['DocNo'];
	$customerName = $_GET['InvoiceCustomerName'];
	$customerRef = $_GET['InvioceustomerRef'];
	$operationNotes = $_GET['InvioceOperationNotes'];
	$operationStatus = 1;
	
	$sql = $db->query("INSERT INTO `transactions`
	(`trUnityPrice`, `qty`, 
	`itemCode`, `operation`, `purchaseOrder`,
	`deliverlyNote`, `docRefNumber`, `customerName`, 
	`customerRef`, `operationNotes`, `operationStatus`, `description`, doneBy) 
	VALUES 
	('$unityPrice','$qty',
	'$itemCode','$operation','$purchaseOrder',
	'$deliverlyNote','$docRefNumber','$customerName',
	'$customerRef','$operationNotes','$operationStatus','$operationNotes', '$doneBy')
	")or die(mysqli_error());
$sql2 = $db->query("SELECT `itemName` FROM `items` WHERE `itemId` = '$itemCode' ");
	WHILE($row = mysqli_fetch_array($sql2))
	{
		echo'<div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <a href="#" class="alert-link">'.$row['itemName'].'</a> was Succesfuly added on the list.
                                        </div>';
	}
	echo'<script>	
(function(){
 bringPrint();
})();
	</script>';
}
// CHECK IF INVOICE EXISTS
if(isset($_GET['InvoinceNo']))
{
	$purchaseOrder = $_GET['InvoinceNo'];
	$docRefNumber = $_GET['DocNo'];
	//echo $purchaseOrder;
	$sql = $db->query("SELECT 
T.`transactionID`, T.`itemCode`,I.`itemName`,
T.`qty`,I.`unit`,  T.`trUnityPrice`, 
T.`qty` * T.`trUnityPrice` AS Total,`operation`
FROM `transactions` T INNER JOIN `items` I 
ON T.`itemCode` = I.`itemId`

WHERE `purchaseOrder`='$purchaseOrder' AND `operation` = 'out'
")or die(mysqli_error());
	$countout = mysqli_num_rows($sql);
	if($countout > 0)
	{
		$n=0;
		echo'<table class="table table-hover ">
			<thead>
				<tr>
					<th>#</th>
					<th>Item Name</th>
					<th>Quantity</th>
					<th>Unity Price</th>
					<th>Total Price</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>';
		WHILE($row= mysqli_fetch_array($sql))
		{
			$n++;
			
					$qty1 = number_format($row['qty']);
					$up1 = $row['trUnityPrice'];
					$totalprice = number_format($qty1 * $up1);
					echo'<tr>
							<td>'.$n.'</td>
							<td>'.$row['itemName'].'</td>
							<td>'.number_format($row['qty']).'</td>
							<td>'.number_format($row['trUnityPrice']).' Rwf</td>
							<td>'.number_format($row['Total']).' Rwf</td>
							<td class="actions">
							&nbsp;&nbsp;&nbsp;
							<a href="javascript:void()" onclick="removeOnInv(removeTransaction='.$row['transactionID'].')" class="danger"><i class="fa fa-trash-o text-danger"></i></a>
							</td>
						</tr>';
													
		}
			echo'</tbody>
				</table>';	
	}
}
?>

<?PHP // GENERAL REPORTS (DONE)
// ITEM REPORT
if(isset($_GET['itemInfoId']))
{
	$itemCode= $_GET['itemInfoId'];
	$sqliteminfo = $db->query("SELECT 
	T.`transactionID`,doneOn,`operation`, T.`itemCode`,I.`itemName`,T.purchaseOrder,
	ROUND(T.`qty`, 2) AS Quantity,I.`unit`,  ROUND(T.`trUnityPrice`) U_Price, 
	ROUND(T.`qty` * T.`trUnityPrice` , 2) AS T_Values,IFNULL(doneBy, 'Not Specified') as Done_by
	FROM `transactions` T INNER JOIN `items` I 
	ON T.`itemCode` = I.`itemId`

	WHERE I.`itemId` = '$itemCode'

	ORDER BY T.`transactionID` ASC");
	$giveMeName = $db->query("SELECT * FROM `items` WHERE `itemId` = '$itemCode'");
	while($row = mysqli_fetch_array($giveMeName))
	{
		echo'
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title ">'.$row['itemName'].' ('.$row['unit'].')</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
		';
	}
	$counthisto = mysqli_num_rows($sqliteminfo);
	if($counthisto > 0)
	{
		$n=0;
		echo'
			<table id="datatable-buttons"  class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Done On</th>
						<th>Operantion</th>
						<th>Quantity</th>
						<th>Unit Price</th>
						<th>Total Price</th>
						<th>By</th>
						<th>Referance</th>
					</tr>
				</thead>
				<tbody>
		';
		$operation="";
		WHILE($row= mysqli_fetch_array($sqliteminfo))
		{
			$n++;
			$convOperation = $row['operation'];
			if($convOperation == 'In')
			{
				$operation ='Kurangura';
			}
			elseif($convOperation == 'Out')
			{
				$operation ='Gucuruza';
			}
			echo'
				<tr>
					<td>'.$n.'</td>
					<td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
					<td>'.$operation.'</td>
					<td>'.number_format($row['Quantity']).'</td>
					<td>'.number_format($row['U_Price']).' Rwf</td>
					<td>'.number_format($row['T_Values']).' Rwf</td>
					<td>'.$row['Done_by'].'</td>
					<td>'.$row['purchaseOrder'].'</td>
				</tr>
			';													
		}
		echo'
					</tbody>
				</table> 
			';
	}
	else
	{
		echo'
			no transaction on this item yet!	
								
			';
	}
	echo'
					
				<hr/>			
				<div class="col-md-12">			
					<div class="pull-right">
						<div id="printInvoice">
							<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		';	
}
// SEARCH AN ITEM FOR THE GENERAL REPORT
if(isset($_GET['searchUser']))
{
	$searchUser= $_GET['searchUser'];
	$sqluser = $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
										WHERE I.itemName LIKE '%$searchUser%'
										ORDER BY T.transactionID DESC")or die(mysqli_error());
	$countresults = mysqli_num_rows($sqluser);
	if($countresults > 0){
		echo'<table  id="datatable" class="table table-striped table-bordered table-hover table-editable responsive nicescroll">
			<thead>
				<tr>
					<th>N/O</th>
					<th>Date</th>
					<th>By</th>
					<th>Operation</th>
					<th>Item Name</th>
					<th>Quantity</th>
					<th>Unity Price</th>
					<th>Total</th>
				</tr>
			</thead>


			<tbody>';
		$n=0;
		while($row = mysqli_fetch_array($sqluser))
		{
			$n++;
			echo'<tr class="gradeX">
				<td>'.$n.'</td>
				<td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
				<td>'.$row['doneBy'].'</td>
				<td>'.$row['operation'].'</td>
				<td>'.$row['itemName'].'</td>
				<td>'.number_format($row['Quantity']).' '.$row['unit'].'s</td>
				<td>'.number_format($row['Price']).' Rwf</td>
				<td>'.number_format($row['total']).' Rwf</td>
				
			</tr>';
		}
	
		echo'</tbody>
		</table>';	
	}
	else{
		echo'no item like <b> '.$searchUser.'</b> ...';
		}
}
// FILTER GENERAL REPORT BY DATE INTERVAL
if(isset($_GET['fromDate']))
{
	$fromDate= $_GET['fromDate'];
	$toDate= $_GET['toDate'];
	
	if ($toDate == null || $toDate == "") 
	{
		$kugeza = 'Ubu';
        $sqlrepuser= $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
										
							WHERE (T.doneOn BETWEEN '$fromDate' AND now())
							ORDER BY T.transactionID DESC");
    }
	else
	{
		$kugeza = $toDate;
		$sqlrepuser= $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
										
							WHERE (T.doneOn BETWEEN '$fromDate' AND '$toDate')
							ORDER BY T.transactionID DESC");
	}
	$countTrans = mysqli_num_rows($sqlrepuser);
	$n=0;
	if($countTrans >0){
	echo'<table  id="datatable" class="table table-striped table-bordered table-hover table-editable responsive nicescroll">
			<thead>
                                                <tr>
													<th>N/O</th>
													<th>Date</th>
													<th>By</th>
													<th>Operation</th>
													<th>Item Name</th>
													<th>Quantity</th>
													<th>Unity Price</th>
													<th>Total</th>
												</tr>
                                            </thead>


                                            <tbody>';
	
	while($row = mysqli_fetch_array($sqlrepuser))
	{
		$n++;
										echo'<tr class="gradeX">
                                            <td>'.$n.'</td>
                                            <td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
                                            <td>'.$row['doneBy'].'</td>
                                            <td>'.$row['operation'].'</td>
                                            <td>'.$row['itemName'].'</td>
                                            <td>'.number_format($row['Quantity']).' '.$row['unit'].'s</td>
                                            <td>'.number_format($row['Price']).' Rwf</td>
                                            <td>'.number_format($row['total']).' Rwf</td>
                                            
                                        </tr>';
										}
	
	echo'</tbody>
	</table>';
	}else{
		echo'<tr><td><h3 class="text-danger">No transaction from '.$fromDate.' to '.$kugeza.',</h3></td><tr>';
	}
}

?>

<?PHP // FMCG REPORT (DONE)
// SEARCH AN ITEM FOR THE FMCG REPORT (DONE)
if(isset($_GET['searchfmcg']))
{
	$searchfmcg= $_GET['searchfmcg'];
	$sqluser = $db->query("SELECT T.itemCode, I.itemName, T.operation, sum(T.qty) IGITERANYO
							FROM transactions T
							inner join items I
							on I.itemId = T.itemCode
							WHERE T.operation = 'out' AND I.itemName LIKE '%$searchfmcg%'
							Group by T.itemCode
							ORDER BY IGITERANYO DESC")or die(mysqli_error());
	$countresults = mysqli_num_rows($sqluser);
	if($countresults > 0){
		$n = 0;
		while($row = mysqli_fetch_array($sqluser))
		{
			$n++;
			$Outs_Value = $row['IGITERANYO'];
		echo'<a href="javascript:void()" onclick="fmcg(itemCode='.$row['itemCode'].'); fmcg1(itemid1='.$row['itemCode'].')">
		<div class="inbox-item">
			<div class="inbox-item-img"># </div>
			<p class="inbox-item-author">'.$row['itemName'].'</p>
			<p class="inbox-item-date">('.number_format($Outs_Value).')</p>
		</div>
		</a>';
		}
	
	}
	else{
		echo'no item like <b> '.$searchfmcg.'</b> ...';
		}
}

//FMCG NAME ON THE HEADER (DONE)
if(isset($_GET['itemCode']))
{
	$itemCode= $_GET['itemCode'];
	$_SESSION["FMCGitemCode"] = $itemCode;
	$ItemNameHeader = $db->query("SELECT * FROM items WHERE itemId='$itemCode'");
	while($row = mysqli_fetch_array($ItemNameHeader))
	{
		echo '<div class="row">
				<div class="col-md-3">
					<h3 class="panel-title">'.$row['itemName'].'</h3>
				</div>
				
				<div class="col-md-4">
					<div class="input-group">
						<input type="date" onchange="between()" id="fromDate" class="form-control input-sm" min="" placeholder="mm/dd/yyyy">
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-calendar"></i>
						</span>
					</div>
				</div>									
				<div class="col-md-1">
					<h3 class="panel-title"> <center><i class="ion-arrow-right-c"></i></center></h3>
				</div>
				<div class="col-md-4">
					<div class="input-group">
						<input type="date" onchange="between()" id="toDate" class="form-control input-sm" placeholder="mm/dd/yyyy">
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-calendar"></i>
						</span>
					</div>
				</div>									
			</div>
		';
	}
}

//FMCG FILL THE ITEM IN THE REPORT "NO CONDITION" (DONE)
if(isset($_GET['itemid1']))
{
	$itemId= $_GET['itemid1'];
	$sqlfmcgitem= $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
								WHERE T.itemCode = '$itemId' AND operation = 'Out'
							ORDER BY T.transactionID DESC");
    $countTrans = mysqli_num_rows($sqlfmcgitem);
	$n=0;
	if($countTrans >0){
	echo'<table  id="datatable" class="table table-striped table-hover table-editable responsive nicescroll">
			<thead>
				<tr>
					<th>N/O</th>
					<th>Date</th>
					<th>By</th>
					<th>Quantity</th>
					<th>Unity Price</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>';
	while($row = mysqli_fetch_array($sqlfmcgitem))
	{
		$n++;
		echo'<tr class="gradeX">
			<td>'.$n.'</td>
			<td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
			<td>'.$row['doneBy'].'</td>
			<td>'.number_format($row['Quantity']).' '.$row['unit'].'s</td>
			<td>'.number_format($row['Price']).' Rwf</td>
			<td>'.number_format($row['total']).' Rwf</td>
			
		</tr>';
	}
	
	echo'</tbody>
	</table>';
	}else{
		echo'<tr><td><h3 class="text-danger">No transaction from '.$fromDate.' to '.$kugeza.',</h3></td><tr>';
	}
}

//FMCG FILTER REPORT BY DATE INTERVAL (DONE)
if(isset($_GET['fmcgfromDate']))
{
	$fromDate= $_GET['fmcgfromDate'];
	$toDate= $_GET['fmcgtoDate'];
	$FMCGitemCode = $_SESSION["FMCGitemCode"];
	if ($toDate == null || $toDate == "") 
	{
		$kugeza = 'Ubu';
        $sqlfmcgitem= $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
								WHERE T.itemCode = '$FMCGitemCode' AND operation = 'Out' 
								AND (T.doneOn BETWEEN '$fromDate' AND now())
							ORDER BY T.transactionID DESC");
    }
	else
	{
		$kugeza = $toDate;
		$sqlfmcgitem= $db->query("SELECT T.doneOn , I.itemName, I.unit, T.`qty` Quantity, T.`trUnityPrice` Price,
										(T.`qty`* T.`trUnityPrice`) AS total , T.`operation`,T.customerName Customer , T.`doneBy`
										FROM transactions T 
										INNER JOIN items I ON I.itemId = T.itemCode
								WHERE T.itemCode = '$FMCGitemCode' AND operation = 'Out'
							AND (T.doneOn BETWEEN '$fromDate' AND '$toDate')
							ORDER BY T.transactionID DESC");
	}
	$countTrans = mysqli_num_rows($sqlfmcgitem);
	$n=0;
	if($countTrans >0)
	{
	echo'<table  id="datatable" class="table table-striped table-hover table-editable responsive nicescroll">
			<thead>
				<tr>
					<th>N/O</th>
					<th>Date</th>
					<th>By</th>
					<th>Quantity</th>
					<th>Unity Price</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>';
	while($row = mysqli_fetch_array($sqlfmcgitem))
	{
		$n++;
		echo'<tr class="gradeX">
			<td>'.$n.'</td>
			<td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
			<td>'.$row['doneBy'].'</td>
			<td>'.number_format($row['Quantity']).' '.$row['unit'].'s</td>
			<td>'.number_format($row['Price']).' Rwf</td>
			<td>'.number_format($row['total']).' Rwf</td>
			
		</tr>';
	}
	
	echo'</tbody>
	</table>';
	}
	else{
		echo'<tr><td><h3 class="text-danger">No transaction from '.$fromDate.' to '.$kugeza.',</h3></td><tr>';
	}
}

?>

<?PHP // ROI REPORT ()
// SEARCH AN ITEM FOR THE ROI REPORT (DONE)
if(isset($_GET['searchroi']))
{
	$searchroi= $_GET['searchroi'];
	$sqltotalperUser = $db->query("SELECT itemCode, itemName, sum(`GAIN_PER_OPERATION`) totalgain 
						FROM `returnoninvestment` WHERE itemName like '%$searchroi%'
						GROUP BY `itemCode` 
						ORDER BY totalgain desc")or die(mysqli_error());
	$countresults = mysqli_num_rows($sqltotalperUser);
	if($countresults > 0){
		$totaroi = "";
		
			$n = 0;
			while($row = mysqli_fetch_array($sqltotalperUser))
			{
				$n++;
				$roi = $row['totalgain'];
			echo'<a href="javascript:void()" onclick="fmcg(itemCode='.$row['itemCode'].'); fmcg1(itemid1='.$row['itemCode'].')">
			<div class="inbox-item">
				<div class="inbox-item-img">#'.$n.'</div>
				<p class="inbox-item-author">'.$row['itemName'].'</p>
				<p class="inbox-item-date">'.number_format($row['totalgain']).' Rwf</p>
			</div>
		</a>';
			$totaroi = $roi + $totaroi;
		}
	
	}
	else{
		echo'no item like <b> '.$searchroi.'</b> ...';
		}
}

//ROI NAME ON THE HEADER (DONE)
if(isset($_GET['roiitemCode']))
{
	$roiitemCode= $_GET['roiitemCode'];
	$_SESSION["roiitemCode"] = $roiitemCode;
	$ItemNameHeader = $db->query("SELECT * FROM items WHERE itemId='$roiitemCode'");
	while($row = mysqli_fetch_array($ItemNameHeader))
	{
		echo '<div class="row">
				<div class="col-md-3">
					<h3 class="panel-title">'.$row['itemName'].'</h3>
				</div>
				
				<div class="col-md-4">
					<div class="input-group">
						<input type="date" onchange="between()" id="fromDate" class="form-control input-sm" min="" placeholder="mm/dd/yyyy">
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-calendar"></i>
						</span>
					</div>
				</div>									
				<div class="col-md-1">
					<h3 class="panel-title"> <center><i class="ion-arrow-right-c"></i></center></h3>
				</div>
				<div class="col-md-4">
					<div class="input-group">
						<input type="date" onchange="between()" id="toDate" class="form-control input-sm" placeholder="mm/dd/yyyy">
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-calendar"></i>
						</span>
					</div>
				</div>									
			</div>
		';
	}
}

//ROI FILL THE ITEM IN THE REPORT "NO CONDITION" (DONE)
if(isset($_GET['roiitemid1']))
{
	$itemId= $_GET['roiitemid1'];
	$sqlroiitem= $db->query("SELECT * FROM `returnoninvestment` WHERE `itemCode` = '$itemId'
							ORDER BY transactionID DESC");
    $countTrans = mysqli_num_rows($sqlroiitem);
	$n=0;
	if($countTrans >0){
	echo'<table  id="datatable" class="table table-striped table-hover table-editable responsive nicescroll">
			<thead>
				<tr>
					<th>N/O</th>
					<th>Date</th>
					<th>P Unit Price</th>
					<th>S Unit Price</th>
					<th>Profit Per unit</th>
					<th>qty</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>';
	while($row = mysqli_fetch_array($sqlroiitem))
	{
		$n++;
		echo'<tr class="gradeX">
			<td>'.$n.'</td>
			<td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
			<td>'.number_format($row['PURCHASE_PRICE']).' Rwf</td>
			<td>'.number_format($row['trUnityPrice']).' Rwf</td>
			<td>'.number_format($row['GAIN_UNIT']).' Rwf</td>
			<td>'.number_format($row['qty']).'</td>
			<td>'.number_format($row['GAIN_PER_OPERATION']).' Rwf</td>
			
		</tr>';
	}
	
	echo'</tbody>
	</table>';
	
}
}

//ROI FILTER REPORT BY DATE INTERVAL (DONE)
if(isset($_GET['roifromDate']))
{
	$fromDate= $_GET['roifromDate'];
	$toDate= $_GET['roitoDate'];
	$roiitemCode = $_SESSION["roiitemCode"];
	if ($toDate == null || $toDate == "") 
	{
		$kugeza = 'Ubu';
        $sqlfmcgitem= $db->query("SELECT * FROM `returnoninvestment`
								WHERE `itemCode` = '$roiitemCode'
							
								AND (doneOn BETWEEN '$fromDate' AND now())
								ORDER BY transactionID DESC");
    }
	else
	{
		$kugeza = $toDate;
		$sqlfmcgitem= $db->query("SELECT * FROM `returnoninvestment`
								WHERE `itemCode` = '$roiitemCode'
								AND (doneOn BETWEEN '$fromDate' AND '$toDate')
							ORDER BY transactionID DESC");
	}
	$countTrans = mysqli_num_rows($sqlfmcgitem);
	$n=0;
	if($countTrans >0)
	{
	echo'<table  id="datatable" class="table table-striped table-hover table-editable responsive nicescroll">
			<thead>
				<tr>
					<th>N/O</th>
					<th>Date</th>
					<th>P Unit Price</th>
					<th>S Unit Price</th>
					<th>Profit Per unit</th>
					<th>qty</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>';
	while($row = mysqli_fetch_array($sqlfmcgitem))
	{
		$n++;
		echo'<tr class="gradeX">
			<td>'.$n.'</td>
			<td>'.strftime("%d %b, %Y", strtotime($row['doneOn'])).'</td>
			<td>'.number_format($row['PURCHASE_PRICE']).' Rwf</td>
			<td>'.number_format($row['trUnityPrice']).' Rwf</td>
			<td>'.number_format($row['GAIN_UNIT']).' Rwf</td>
			<td>'.number_format($row['qty']).'</td>
			<td>'.number_format($row['GAIN_PER_OPERATION']).' Rwf</td>
			
		</tr>';
	}
	
	echo'</tbody>
	</table>';
	}
	else{
		echo'<tr><td><h3 class="text-danger">No transaction from '.$fromDate.' to '.$kugeza.',</h3></td><tr>';
	}
}

?>

<?php // INVOICES OPPERATIONS ()
// SEARCH AN INVOICE
if(isset($_GET['searchInvoice']))
{
	$searchInvoice= $_GET['searchInvoice'];
	if($searchInvoice == '')
	{
		$sqlinvoice = $db->query("SELECT `purchaseOrder`, `customerName`, transactionID FROM `transactions`
								WHERE operation = 'out'
								ORDER BY transactionID DESC");
				while($row = mysqli_fetch_array($sqlinvoice))
				{
				echo'<a href="invoices.php?invoiceNo='.$row['purchaseOrder'].'">
						<div class="inbox-item">
							<p class="inbox-item-author">'.$row['purchaseOrder'].'</p>
							<p class="inbox-item-text">'.$row['customerName'].'</p>
						</div>
					</a>';
				}
		
	}else{
	$sqlinvoice = $db->query("SELECT `purchaseOrder`, `customerName`, transactionID 
							FROM `transactions`
							WHERE purchaseOrder LIKE '%$searchInvoice%' OR customerName LIKE '%$searchInvoice%'
							AND operation = 'out' ORDER BY transactionID DESC")or die(mysqli_error());
	$countresults = mysqli_num_rows($sqlinvoice);
	if($countresults > 0 )
	{
		$n=0;
		while($row = mysqli_fetch_array($sqlinvoice))
		{
			echo'<a href="invoices.php?invoiceNo='.$row['purchaseOrder'].'">
					<div class="inbox-item">
						<p class="inbox-item-author">'.$row['purchaseOrder'].'</p>
						<p class="inbox-item-text">'.$row['customerName'].'</p>
					</div>
				</a>';
		}	
	}
	else
	{
		echo'no invoice like <b> '.$searchInvoice.'</b> ...';
	}
	}
}



?>

<?php // REMOVE THE USER
if(isset($_GET['deleteUser']))
{
	$deleteUser = $_GET['deleteUser'];
	$sqlremoveuser = $db->query("DELETE FROM users WHERE id = '$deleteUser'")or mysql_error();
	echo'<table width="100%" class="table table-striped table-bordered">
			<thead >
				<th>#</th>
				<th>names</th>
				<th>phone</th>
				<th>email</th>
				<th>workPlace</th>
				<th>Type</th>
				<th>Actions</th>
			</thead>
			<tbody>';
	include ("db.php");
	$n=0;
	$sql2 = $db->query("SELECT * FROM `users` WHERE account_type='user'");
	$count = mysqli_num_rows($sql2);
	if($count > 0)
	{
		while($row = mysqli_fetch_array($sql2))
		{
			$n++;
			echo'
			<tr href="javascript:void()" onclick ="editUser(userId= '.$row['id'].')">
				<td>'.$n.'</td>
				<td>'.$row['names'].'</td>
				<td>'.$row['phone'].'</td>
				<td>'.$row['email'].'</td>
				<td>'.$row['workPlace'].'</td>
				<td>'.$row['account_type'].'</td>
				<td><a href="javascript:void()" onclick="removeUser(userId='.$row['id'].')">Remove</a></td>
			</tr>';
		}
											
		}else{
			echo'Please add a user';
		}
		
	echo'</tbody></table>';	
}
?>

<?php // ADD A USER
if(isset($_GET['newname']))
{
	$newname = $_GET['newname'];
	$newPhone = $_GET['newPhone'];
	$newEmail = $_GET['newEmail']; 
	$newWorkPlace = $_GET['newWorkPlace'];
	$newusername = $_GET['newusername'];
	$newpassword = $_GET['newpassword'];
	
	$sqladduser = $db->query("INSERT INTO 
	users(`loginId`, `pwd`, `names`, `phone`, `email`, `workPlace`, `account_type`) 
	VALUES('$newusername','$newpassword','$newname','$newPhone','$newEmail','$newWorkPlace','user')");
	$newTable = $db->query("SELECT * FROM `users` WHERE account_type='user'");
	$count = mysqli_num_rows($newTable);
	if($count > 0)
	{
		echo'<table width="100%" class="table table-striped table-bordered">
			<thead >
				<th>#</th>
				<th>names</th>
				<th>phone</th>
				<th>email</th>
				<th>workPlace</th>
				<th>Type</th>
				<th>Actions</th>
			</thead>
			<tbody>';
			$n=0;
		while($row = mysqli_fetch_array($newTable))
		{
			$n++;
			echo'
			<tr href="javascript:void()" onclick ="editUser(userId= '.$row['id'].')">
				<td>'.$n.'</td>
				<td>'.$row['names'].'</td>
				<td>'.$row['phone'].'</td>
				<td>'.$row['email'].'</td>
				<td>'.$row['workPlace'].'</td>
				<td>'.$row['account_type'].'</td>
				<td><a href="javascript:void()" onclick="removeUser(userId='.$row['id'].')">Remove</a></td>
			</tr>';
		}
		echo'</tbody>
		</table>';
											
		}else{
			echo'Error() <a href="po.php">Click Here!</a>';
		}
}
?>

<?php // EDIT THE USER
if(isset($_GET['editUser']))
{
	$editUser = $_GET['editUser'];
	include ("db.php");
	$sqledituser = $db->query("SELECT* FROM users WHERE id = '$editUser'");
	$count = mysqli_num_rows($sqledituser);
	if($count > 0)
	{
		while($row = mysqli_fetch_array($sqledituser))
		{
		echo'
		<div class="panel panel-default">
			<div class="panel-heading">
			<div class="row">
				<div class="col-md-12">
					<h4 class="panel-title">Edit '.$row['names'].'</h4>
				</div>
			</div>
		</div>
			<div class="panel-body">
				<div class="inbox-widget nicescroll mx-box">
					<form action="po.php" method="post">
					Name<br/>
					<input type="text" name="edit_name" id="edit_name" value="'.$row['names'].'" class="form-control input-sm"><br/>
					Phone<br/>
					<input type="text" name="edit_Phone" id="edit_Phone" value="'.$row['phone'].'" class="form-control input-sm"><br/>
					Email<br/>
					<input type="text" name="edit_Email" id="edit_Email" value="'.$row['email'].'" class="form-control input-sm"><br/>
					WorkPlace<br/>
					<input type="text" name="edit_WorkPlace" id="edit_WorkPlace" value="'.$row['workPlace'].'" class="form-control input-sm"><br/>
					Username<br/>
					<input type="text" name="edit_username" id="edit_username" value="'.$row['loginId'].'" class="form-control input-sm"><br/>
					<input type="submit" class="btn btn-success waves-effect waves-light" value="MODIFY" />
				   </form>
				</div>
			</div>
		</div>';
		}
	}else{
			echo'The user Succesfuly Removed';
	}	
}
?>