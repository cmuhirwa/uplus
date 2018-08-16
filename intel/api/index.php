<?php
ob_start();

//specify JSON as content type
header('Content-Type: application/json');

// include_once '../conn.php';

//InventoryDb
include_once '../stock/db.php';
include_once '../functions.php';

include_once '../core/location.php';
include_once '../core/cooperative.php';
include_once '../core/crop.php';
include_once '../core/purchasingOrder.php';
require '../core/measurement.php';
include_once '../core/product.php';
include_once '../core/message.php';
include_once '../core/supplier.php';
include_once '../core/client.php';
include_once '../core/mail.php';

$standard_date = "Y-m-d h:i:s";


//gather all requests together
$request = array_merge($_GET, $_POST);

//cleaning all the post variables against attacks
foreach ($request as $key => $value) {
	// $request[$key] = $conn->real_escape_string($value);
}

//getting requested action
$action = $request['action']??"";

//return wrapper
$response = array();
if($action == 'get_members'){
	//members of coooperative
	$cooperative = $request['cooperative']??"";

	$members = $Cooperative->get_members($cooperative);

	if(is_array($members)){
		$response = array('status'=>true, 'data'=>$members);
	}
}else if($action == 'add_cooperative_member'){
	//adding member to cooperative
	$cooperative = $request['cooperative']??"";

	$name = $request['name']??"";
	$phone = $request['phone']??"";
	$NID = $request['NID']??"";
	$gender = $request['gender']??"";
	$location = $request['location']??"";
	$birth_date = date("Y-m-d", strtotime($request['birth_date']))??false;
	$date = $request['date']??date($standard_date);
	// $date = $request['']??date($standard_date);

	if(!empty($_FILES['picture'])){
		$pic = $_FILES['picture'];
		$ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION)); //extension
		if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
		{
			$filename = "images/farmer/$name".time().".$ext";
			if(move_uploaded_file($pic['tmp_name'], "../$filename")){

			}else{
				//set the default image
				$filename = "images/farmer/default.jpg";

			}
		}
	}else{
		$filename = "images/farmer/default.jpg";
	}
	

	// checking if essential details ares et
	if(!($name && $NID && $gender)){
		$response = array('status'=>false, 'msg'=>"Provide all member details");		
	}else{

		//insert the user in DB
		$userId = $Cooperative->add_user($name , $phone , $NID , $gender, $birth_date, $filename);

		if($userId){
			add_farmer_cooperative($userId, $cooperative);
			$response = array('status'=>true, 'data'=>array('memberId'=>$userId));
		}else{
			$response = array('status'=>false, 'msg'=>"$conn->error");
		}
	}
}else if($action == 'addSupplier'){
	//Adding a supplier
	$organisationName = $request['name']??"";

	$name = $request['name']??"";
	$phone = $request['phone']??"";
	$email = $request['email']??"";
	$workplace = $request['workplace']??"";
	$username = $request['username']??"";
	$password = $request['password']??"";
	$doneBy = $request['doneBy']??"";

	// if(!empty($_FILES['picture'])){
	// 	$pic = $_FILES['picture'];
	// 	$ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION)); //extension
	// 	if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
	// 	{
	// 		$filename = "images/farmer/$name".time().".$ext";
	// 		if(move_uploaded_file($pic['tmp_name'], "../$filename")){

	// 		}else{
	// 			//set the default image
	// 			$filename = "images/farmer/default.jpg";

	// 		}
	// 	}
	// }else{
	// 	$filename = "images/farmer/default.jpg";
	// }

	// checking if essential details are set
	if($name && $phone && $email && $workplace && $username && $password && $doneBy){
		$supplierStatus = $Supplier->add($name, $phone, $email, $workplace, 5, $doneBy);
		$response = form_response(true, "Supplier added successfully");
	}else{
		$response = form_response(false, "Provide all supplier details");
	}
}else if($action == 'addClient'){
	//Adding a supplier
	$organisationName = $request['name']??"";

	$name = $request['name']??"";
	$phone = $request['phone']??"";
	$email = $request['email']??"";
	$workplace = $request['workplace']??"";
	$tin = $request['tin']??"";
	$username = $request['username']??"";
	$password = $request['password']??"";
	$doneBy = $request['doneBy']??"";

	// if(!empty($_FILES['picture'])){
	// 	$pic = $_FILES['picture'];
	// 	$ext = strtolower(pathinfo($pic['name'], PATHINFO_EXTENSION)); //extension
	// 	if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
	// 	{
	// 		$filename = "images/farmer/$name".time().".$ext";
	// 		if(move_uploaded_file($pic['tmp_name'], "../$filename")){

	// 		}else{
	// 			//set the default image
	// 			$filename = "images/farmer/default.jpg";

	// 		}
	// 	}
	// }else{
	// 	$filename = "images/farmer/default.jpg";
	// }

	// checking if essential details are set
	if($name && $phone && $email && $workplace && $tin && $username && $password && $doneBy){
		$clientStatus = $Client->add($name, $phone, $email, $workplace, $tin, 5, $doneBy);
		$response = form_response(true, "Client added successfully");
	}else{
		$response = form_response(false, "Provide all supplier details");
	}
}else if($action == 'addProduct'){
	//add the product
	$name = $request['name'];
	$mainUnit = $request['mainUnit'];
	$otherUnits = $request['otherUnits'];
	$groups = $request['groups'];
	$doneBy = $request['doneBy'];

	if($name && $mainUnit && $otherUnits && $groups && $doneBy){
		//here we can just add the product successfully
		$productId = $Product->add($name, $doneBy);

		//check if the product has been added
		if(is_int($productId)){
			//product was added
			//just add the mainMeasurement
			$Measurements->addProductUnit($productId, $mainUnit, 1, $doneBy);

			//check other units
			if(!is_array($otherUnits) && !empty($otherUnits)){
				//just make it array
				$otherUnits = array($otherUnits);
			}

			//loop through other units
			foreach ($otherUnits as $key => $unit) {
				$Measurements->addProductUnit($productId, $unit, 0, $doneBy);
			}

			//check other units
			if(!is_array($groups) && !empty($groups)){
				//just make it array
				$groups = array($groups);
			}

			//loop through other units
			foreach ($groups as $key => $group) {
				$Product->addGroup2Product($productId, $group, $doneBy);
			}

			$response = form_response(true, "Product created successfully");
		}else{
			//error inputting the product, so returned string error
			$response = form_response(false, "$productId");
		}
	}
	
}else if($action == 'updateProduct'){
	//edit the product
	$product = $request['product'];
	$name = $request['name'];
	$mainUnit = $request['mainUnit'];
	$doneBy = $request['doneBy'];

	if($product && $name && $mainUnit && $doneBy){
		//here we can just update
		$nameStatus = $Product->editName($product, $name, $doneBy);

		//update the main unit
		$unitStatus = $Measurements->changeMainUnit($product, $mainUnit, $doneBy);

		$response = form_response(true, '', array('nameStatus'=>$nameStatus, 'unit'=>$unitStatus));
	}
	
}else if($action == 'deleteProduct'){
	//edit the product
	$product = $request['product'];
	$doneBy = $request['doneBy'];

	if($product && $doneBy){
		$status = $Product->delete($product, $doneBy);
		if(is_numeric($status)){
			$response = form_response(true, '');
		}else{
			$response = form_response(false, 'Error deleting product '.$status);
		}
		
	}
	
}
else if($action == 'add_crop_variety'){
	//Adding crop variety to the system
	$variety = $function->sanitize($request['variety']??"");
	$crop = $function->sanitize($request['crop']??"");

	//check from the DB
	$checkq = $conn->query("SELECT * FROM crop_varieties WHERE cropId = \"$crop\" AND variety = \"$variety\" ");
	if($checkq){
		if(!$checkq->num_rows){
			//here we add crop
			$varid = $Crop->add_variety($crop, $variety);
			$response = form_response(true, "", array('varietyId'=>$varid));
		}else{
			$response = form_response(false, "Can't add crop variety because it might already exists $conn->error");
		}	
	}else{
		$response = form_response(false, "Can't add crop variety $conn->error");
	}
	
}

else if($action == 'add_crop_grade'){
	//Adding crop grade to the system
	$grade = $function->sanitize($request['grade']??"");
	$crop = $function->sanitize($request['crop']??"");

	//check from the DB
	$checkq = $conn->query("SELECT * FROM crop_grades WHERE cropId = \"$crop\" AND grade = \"$grade\" ");
	if($checkq){
		if(!$checkq->num_rows){
			//here we add crop
			$varid = $Crop->add_grade($crop, $grade);
			$response = form_response(true, "", array('gradeId'=>$varid));
		}else{
			$response = form_response(false, "Can't add crop grade because it might already exists $conn->error");
		}	
	}else{
		$response = form_response(false, "Can't add crop grade $conn->error");
	}
	
}
else if($action == 'add_cooperative_crop'){
	//adding member to cooperative
	$cooperative = $request['cooperative']??"";

	$crop = $function->sanitize($request['crop']??"");
	$type = $function->sanitize($request['type']??"");
	// $grading = $function->sanitize($request['grades']??"");

	$addedBy = $request['addedBy']??"";

	// checking if the crop is already created in crops table

	if($Crop->details($crop)){
		$checkq = $conn->query("SELECT * FROM cooperative_crops WHERE cropId = \"$crop\" AND cooperativeId = \"$cooperative\" LIMIT 1 ") or trigger_error("Can't check the crop $conn->error");
		if($checkq->num_rows <= 0){
			// here we can insert
			$Cooperative->attach_crop($cooperative, $crop, $type, $addedBy);
			$response = array('status'=>true, 'data'=>array('id'=>111));
		}else{
			$response = array('status'=>false, 'msg'=>"Igihingwa cyari gisanzwe kirimo");
		}
	}else{
		$response = array('status'=>false, 'msg'=>"$conn->error");
	}
}else if($action == 'addPurchasingOrder'){

	//adding member to cooperative
	//get product order number and delivary note

	$status = $_POST['status']??""; 
	$supplier = $_POST['supplier']??"";
	$currency = $_POST['currency']??"";
	$warehouse = $_POST['warehouse']??"";
	$budgetHolder = $_POST['budgetHolder']??"";
	$shippingMode = $_POST['shippingMode']??"";
	$shipmentDate = $_POST['shipmentDate']??"";
	$doneBy = $request['doneBy']??"";

	if($status && $supplier && $currency && $warehouse && $budgetHolder && $shippingMode && $shipmentDate && $doneBy){
		//we are good here
		$insertStatus = $POrder->add($status, $supplier, $currency , $warehouse , $budgetHolder , $shippingMode , $shipmentDate, $doneBy);
		if($insertStatus){
			$response = form_response(true, 'Purchase order issed successfully', array('id'=>$insertStatus));

			//Mail the suppier
			$supplierData = $Supplier->details($supplier);
			$Mail->send($supplierData['email'], "Purchase order #".$POrder->generateOrderNumber('P', $POrder->nextOrderNumber())." comfirmation", "Dear Supplier, MPPD has assigned the order. Log in to the system with your credentials to comfirm or deny the purchase order. Thank you" );


		}else{
			$response = form_response(false, 'We had problem issuing purchase order');
		}
	}else{
		$response = form_response(false, 'Specifiy who did this');
	}
}else if($action == 'addPurchasingOrderItem'){
	//adding member to cooperative

	$product = $_POST['product']??"";
	$productUnitPrice = $_POST['productUnitPrice']??""; 
	$productUnitMeasure = $_POST['productUnitMeasure']??""; 
	$productQuantity = $_POST['productQuantity']??""; 
	$priceCurrency = $_POST['priceCurrency']??"";
	$status = $_POST['status']??"";
	$doneBy = $request['doneBy']??"";

	if($product&&$productUnitPrice&&$productUnitMeasure&&$productQuantity&&$priceCurrency && $doneBy){
		//we are good here
		$insertStatus = $POrder->add($product, $productUnitPrice, $productUnitMeasure, $productQuantity, $priceCurrency, $doneBy);
		if($insertStatus){


			$response = form_response(true, 'Purchase order issed successfully', array('id'=>$insertStatus));
		}else{
			$response = form_response(false, 'We had problem issuing purchase order');
		}
	}
}else if($action == 'addPurchasingOrderItems'){
	//add several items to a purchasing order
	$order = $_POST['order']??"";
	$doneBy = $request['doneBy']??"";

	$items = $_POST['items'];
	$order = $_POST['order'];
	$doneBy = $_POST['doneBy']??"";

	//where item db input will be stored
	$itemStatus = array();
	foreach ($items as $key => $item) {
		$product = $item['itemId']??"";
		$productUnitPrice = $item['unitPrice']??""; 
		$productUnitMeasure = $item['unit']??""; 
		$productQuantity = $item['quantity']??"";

		if($product&&$productUnitPrice&&$productUnitMeasure&&$productQuantity && $doneBy){
			//we are good here
			$insertStatus = $POrder->addItem($product, $productUnitPrice, $productUnitMeasure, $productQuantity, $order, $doneBy);
			if($insertStatus){
				$itemStatus[$product] = form_response(true, 'Added successfully', array('id'=>$insertStatus));
			}else{
				$itemStatus[$product] = form_response(false, 'We had problem adding item to a purchase order');
			}
		}else{
			echo "Error with that";
		}
	}

	$response = form_response(true, '', $itemStatus);
	
}else if($action == 'add_cooperative_pricing'){
	//adding crop pricing of cooperative
	$cooperative = $request['cooperative']??"";

	$crop_data = explode("-", $function->sanitize($request['crop']??"-"));

	$crop = $crop_data[0];
	$variety = $crop_data[1];
	$price = $function->sanitize($request['price']??"");
	$grade = $function->sanitize($request['crop_grade']??"");

	$addedBy = $request['addedBy']??"";

	// checking if the crop has recent pricing already
	if($Crop->details($crop)){
		if($Cooperative->propose_crop_price($cooperative, $crop, $variety, $grade, $price, $addedBy)){
			$response = array('status'=>true, 'data'=>array('id'=>111));
		}else{
			$response = array('status'=>false);
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Can't find the crop");
	}

}else if($action == 'add_cooperative_harvest'){
	//adding crop harvest of cooperative
	$cooperative = $request['cooperative']??"";

	$crop_data = explode("-", $function->sanitize($request['crop']??"-"));

	$crop = $crop_data[0];
	$variety = $crop_data[1]??$request['variety'];
	$quantity = $function->sanitize($request['quantity']??"");
	$quantity_remained = $function->sanitize($request['quantity_remained']??"");
	$grade = $function->sanitize($request['crop_grade']??"");
	$ownerId = $function->sanitize($request['owner']??""); #id of the user owner if the harvest is of cooeprative member

	$addedBy = $request['addedBy']??"";

	// checking if the crop has recent pricing already
	if($Crop->details($crop)){
		if($harvestId = $Cooperative->add_harvest($cooperative, $crop, $variety, $grade, $quantity, $ownerId, $quantity_remained, $addedBy)){
			$response = array('status'=>true, 'data'=>array('id'=>$harvestId));
		}else{
			$response = array('status'=>false);
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Can't find the crop");
	}
}else if($action == 'sell_cooperative_harvest'){
	//selling cooperative harvest to wholesalers
	$cooperative = $request['cooperative']??"";
	$seller_id = $function->sanitize($request['seller']??"");

	$crop_data = explode("-", $function->sanitize($request['crop']??"-"));

	$crop = $crop_data[0];
	$variety = $crop_data[1]??$request['variety'];
	$quantity = $function->sanitize($request['quantity']??"");
	$grade = $function->sanitize($request['crop_grade']??"");

	$addedBy = $request['addedBy']??"";

	// checking if the crop has recent pricing already
	if($Crop->details($crop)){

		//check if the sufficient quantity of harvest
		$crop_harvest = $Cooperative->harvest_summary($cooperative, $crop, $variety);
		if($crop_harvest['remaining'] >= $quantity){
			if($harvestId = $Cooperative->sell_harvest($cooperative, $crop, $variety, $grade, $quantity, $seller_id, $addedBy)){
				$response = array('status'=>true, 'data'=>array('id'=>$harvestId));
			}else{
				$response = array('status'=>false);
			}
		}else{
			$response = form_response(false, "Umusaruro ntiwagurishwa kuko ingano yawo idafitwe na koperative");
		}

		
	}else{
		$response = array('status'=>false, 'msg'=>"Can't find the crop");
	}
}else if($action == 'create_cooperative'){
	//creating cooperative involves two steps
	$step = $request['step']??"";

	if($step == 1){
		//step one
		$name = $request['name']??"";
		$serial = $request['serial']??""; //normal idenfitication of coop

		//cooperative location capturing
		$province = $function->sanitize($_POST['province']??"");
		$district = $function->sanitize($_POST['district']??"");
		$sector = $function->sanitize($_POST['sector']??"");

		if($name && $province && $district){
			//inserting into databs

			//creating location
			$sql = "INSERT INTO location(province, district, sector) VALUES('$province', '$district', '$sector') ";
			$locQ = $conn->query($sql) or trigger_error($conn->error);
			$locId = $conn->insert_id;
			$query = $conn->query("INSERT INTO cooperatives(name, cooperativeSerial, location) VALUES(\"$name\", \"$serial\", \"$locId\")");
			if($query)
			{	
				$inid = $conn->insert_id;

				//suggesting a username
				if($inid<10){
					$username = "coop_00$inid";
				}else if($inid<100){
					$username = "coop_0$inid";					
				}else{
					$username = "coop_$inid";
				}

				$coop_id = $conn->insert_id;

				//Setting the session
				if(!session_id()){
					session_start();
				}

				//sett the session
				$_SESSION['cooperativeId'] = $coop_id;
				$_SESSION['cooperativeCreatedDate'] = time();
				$_SESSION['cooperativeUsername'] = $username;

				$response = array('status'=>true, 'data'=>array('cooperativeId'=>$coop_id, 'username'=>$username));
			}else{
				$response = array('status'=>false, 'msg'=>"$conn->error");
			}
		}else{
			$response = array('status'=>false, 'msg'=>"Provide name and location");
		}
	}else if($step == 2){
		//second step
		$cooperative = $request['cooperative']??"";
		$phone = $request['phone']??"";

		$name = $request['name']??"";
		$NID = $request['NID']??"";
		$gender = $request['gender']??"";
		$birth_date = date("Y-m-d", strtotime($request['dateOfBirth ']??""))??false;
		$date = $request['date']??date($standard_date);

		//cooperative location capturing
		$province = $function->sanitize($_POST['adminProvince']??"");
		$district = $function->sanitize($_POST['adminDistrict']??"");
		$sector = $function->sanitize($_POST['adminSector']??"");

		$username = $request['adminUsername']??"";
		$password = $request['adminPassword']??"";

		if($cooperative && $NID && $name){

			//Inserting a user
			$userId = $Cooperative->add_user($name, $phone, $NID, $gender, $birth_date, '', $username, $password);

			if($userId){
				//associate the user to the cooperative
				add_farmer_cooperative($userId, $cooperative);

				//add user as leader
				$add_status= $Cooperative->add_leader($cooperative, $userId, 'admin');
				if($add_status){
					$response = array('status'=>true);	
				}else{
					$response = array('status'=>false, 'msg'=>"$conn->error");
				}
			}
		}else{
			$response = array('status'=>false, 'msg'=>"Provide cooperative and NID and names");
		}		
	}
}else if($action == 'getItem'){
	//returns item data
	$cooperative = $request['itemId']??"";

	$query = $conn->query("SELECT * FROM cooperatives WHERE cooperativeId = \"$cooperative\" ");
	if($query){
		$cooperativeData = $query->fetch_assoc();
		$response = array('status'=>true, 'data'=>$cooperativeData);
	}else{
		$response = array('status'=>false, 'msg'=>"Error $conn->error");
	}
}else if($action == 'get_cooperative_crops'){
	//returns cooperativecrops
	$cooperative = $request['cooperative']??"";
	$query = $conn->query("SELECT cr.*, cc.addedDate as addedDate, cc.status, cc.stopedDate FROM crops as cr JOIN cooperative_crops as cc ON cc.cropId = cr.cropId  WHERE cc.cooperativeId = \"$cooperative\" ");
	if($query){
		$cooperativeCrops = array();

		while ($data = $query->fetch_assoc()) {
			$cooperativeCrops[] = $query->fetch_assoc();
		}		
		$response = array('status'=>true, 'data'=>$cooperativeCrops);
	}else{
		$response = array('status'=>false, 'msg'=>"Error $conn->error");
	}
}else if($action == 'get_cooperative_pricing'){
	//returns pricing of cooperative
	$cooperative = $request['cooperative']??"";


	// $query = $conn->query("SELECT * FROM cropspricing WHERE cooperativeId = \"$cooperative\" ")
}else if($action == 'list_cooperatives')
{
	//returns pricing of cooperative
	$cooperative = $request['cooperative']??"";


	$query = $conn->query("SELECT * FROM cooperatives ");
	$coops = array();
	while ($data = $query->fetch_assoc()) {
		$coops[] = $data;
	}
	$response = array('status'=>true, 'data'=>$coops);
	
}else if($action == 'get_cooperative_harvest'){
	//gives the harvest of cooperative
	$cooperative = $request['cooperative']??"";
	if ($cooperative) {
		# code...
		$harvest = $Cooperative->get_harvest($cooperative);
		$response = array('status'=>true, 'data'=>$harvest);
	}else{
		$response = array('status'=>false, 'msg'=>'Specifiy the cooperative');
	}
	
}else if($action == 'crop_grades'){
	//returns crops var*******
	$crop = $request['crop']??"";

	$varieties = $Crop->grades($crop);

	$response = array('status'=>true, 'data'=>$varieties);
}else if($action == 'list_fertilizers'){
	//list the available fertilizers in the system
	$response = array('status'=>true, 'data'=>get_fertilizers());;
}else if($action == 'cooperative_fertilizers'){
	$cooperative = $request['cooperative']??"";

	//shows the fertilizers of the cooperative
	if($cooperative){
		$response = form_response(true, '', $Cooperative->get_fertilizers($cooperative));
	}else{
		$response = form_response(true, 'Specify cooperative to check fertilizers');
	}

}else if($action == 'declare_fertilizer'){
	//adding fertilizer to cooperative
	$cooperative = $request['cooperative']??"";

	$fert = $function->sanitize($request['fertilizerId']??"");
	$quantity = $function->sanitize($request['quantity']??"");

	$season = current_season();

	$addedBy = $request['addedBy']??"";

	if($cooperative && $fert && $quantity){
		//inserting
		$fert = $Cooperative->declare_fertilizers($cooperative, $fert, $quantity, $season, $addedBy);
		if($fert){
			$response = array('status'=>true);
		}else{
			$response = array('status'=>false, 'msg'=>"Twagize ikibazo ku kifuzo cyanyu");
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Shyiramo ibisabwa byose");
	}
}else if($action == 'cooperative_members_with_fertilizers'){
	//returns members with fertilizers in season
	$cooperative = $request['cooperative']??"";
	$season = $request['season']??current_season(); #season is optional otherwise we find current season

	// var_dump($season);
	if($cooperative){
		$coop_ferts = $Cooperative->fertilizers_assigned($cooperative, $season);
		$response = form_response(true, '', $coop_ferts);
	}else{
		$response = form_response(false, 'specify the cooperative');
	}

}else if($action == 'cooperative_members_without_fertilizers'){
	//returns members with outfertilizers in season
	$cooperative = $request['cooperative']??"";
	$season = $request['season']??current_season(); #season is optional otherwise we find current season

	if($cooperative){
		$coop_ferts = $Cooperative->members_notassigned_fertilizers($cooperative, $season);
		$response = form_response(true, '', $coop_ferts);
	}else{
		$response = form_response(false, 'specify the cooperative');
	}

}else if($action == 'cooperative_assign_fertilizer'){
	//assigning fertilizer to cooperative
	$cooperative = $request['cooperative']??"";

	$fert = $function->sanitize($request['fertilizerId']??"");
	$quantity = $function->sanitize($request['quantity']??"");

	$addedBy = $request['addedBy']??"";
	$member = $request['member']??"";

	if($cooperative && $member && $fert && $quantity){
		//inserting
		$fert = $Cooperative->assign_fertilizer($cooperative, $member, $fert, $quantity, current_season(), $addedBy);
		if($fert->status){
			$response = form_response(true, '', $fert->data);
		}else{
			$response = form_response(false, $fert->msg);
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Shyiramo ibisabwa byose");
	}
}else if($action == 'add_cooperative_land'){
	//adding fertilizer to cooperative
	$cooperative = $request['cooperative']??"";

	$name = $function->sanitize($request['name']??"");
	$size = $function->sanitize($request['size']??"");
	$owner = $function->sanitize($request['owner']??"cooperative");

	$onwer_id = $function->sanitize($request['ownerId']??false);

	$addedBy = $request['addedBy']??"";

	if($name && $size && $owner){
		//inserting
		$land = $Cooperative->add_land($cooperative, $name, $size, $owner, $onwer_id, $addedBy);
		if($land){
			$response = array('status'=>true);
		}else{
			$response = array('status'=>false, 'msg'=>"Twagize ikibazo ku kifuzo cyanyu");
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Shyiramo ibisabwa byose");
	}
}else if($action == 'cooperative_land'){
	//adding fertilizer to cooperative
	$cooperative = $request['cooperative']??"";

	if($cooperative){
		$coop_land = $Cooperative->get_land($cooperative);
		$response = form_response(true, '', $coop_land);
	}else{
		$response = form_response(false);
	}
}else if($action == 'list_pesticides'){
	//list the available pesticides in the system
	$response = array('status'=>true, 'data'=>get_pesticides());;
}else if($action == 'declare_pesticide'){
	//adding pesticide to cooperative
	$cooperative = $request['cooperative']??"";

	$pest = $function->sanitize($request['pesticideId']??"");
	$quantity = $function->sanitize($request['quantity']??"");

	$addedBy = $request['addedBy']??"";

	if($cooperative && $pest && $quantity){
		//inserting
		$pestid = $Cooperative->declare_pesticide($cooperative, $pest, $quantity, current_season(), $addedBy);
		if($pestid){
			$response = array('status'=>true);
		}else{
			$response = array('status'=>false, 'msg'=>"Twagize ikibazo ku kifuzo cyanyu");
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Shyiramo ibisabwa byose");
	}
}else if($action == 'cooperative_pesticides'){
	$cooperative = $request['cooperative']??"";
	$season = $request['season']??current_season();

	//shows the pesticides of the cooperative
	if($cooperative){
		$response = form_response(true, '', $Cooperative->get_pesticides($cooperative, $season));
	}else{
		$response = form_response(false, 'Specify cooperative to check fertilizers');
	}

}else if($action == 'cooperative_members_with_pesticides'){
	//returns members with pesticides in season
	$cooperative = $request['cooperative']??"";
	$season = $request['season']??current_season(); #season is optional otherwise we find current season

	if($cooperative){
		$coop_pests = $Cooperative->pesticides_assigned($cooperative, $season);
		$response = form_response(true, '', $coop_pests);
	}else{
		$response = form_response(false, 'specify the cooperative');
	}

}else if($action == 'cooperative_members_without_pesticides'){
	//returns members with outfertilizers in season
	$cooperative = $request['cooperative']??"";
	$season = $request['season']??current_season(); #season is optional otherwise we find current season

	if($cooperative){
		$coop_pests = $Cooperative->members_notassigned_pesticides($cooperative, $season);
		$response = form_response(true, '', $coop_pests);
	}else{
		$response = form_response(false, 'specify the cooperative');
	}

}else if($action == 'cooperative_assign_pesticide'){
	//assigning fertilizer to cooperative
	$cooperative = $request['cooperative']??"";

	$pesticide = $function->sanitize($request['pesticideId']??"");
	$quantity = $function->sanitize($request['quantity']??"");

	$addedBy = $request['addedBy']??"";
	$member = $request['member']??"";

	if($cooperative && $member && $pesticide && $quantity){
		//inserting
		$pesticideIdQ = $Cooperative->assign_pesticide($cooperative, $member, $pesticide, $quantity, '', $addedBy);

		$msg = $pesticideIdQ->msg;
		if($pesticideIdQ->status){
			$response = form_response(true, '', $pesticideIdQ->data);
		}else{
			$response = form_response(false, $msg, '');
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Shyiramo ibisabwa byose");
	}
}else if($action == 'crop_varieties')
{
	//returns crops var*******
	$crop = $request['crop']??"";

	$varieties = $Crop->varieties($crop);

	$response = array('status'=>true, 'data'=>$varieties);
	
}else if($action == 'getProduct')
{
	//returns product information
	$crop = $request['productId']??"";

	$details = $Product->details($crop);

	$response = array('status'=>true, 'data'=>$details);	
}
else if($action == 'login')
{
	//user login
	$username = $request['username']??"";
	$password = $request['password']??'';

	if($username && $password){
		//check user credentials
		$user = login($username, $password);
		if($user){
			//successfully user is found
			//checking if he is cooperative admin
			if($cooperative = is_cooperative_leader($user)){
				$response = array('status'=>true, 'msg'=>array('user'=>$user, 'cooperative'=>$cooperative['cooperative']));
			}else{
				$response = array('status'=>false, 'msg'=>"User is not cooperative admin");
			}
		}else{
			$response = array('status'=>false, 'msg'=>"Invalid username and/or password");	
		}
	}else{
		$response = array('status'=>false, 'msg'=>"Specifiy username and password");
	}	
}
else if($action == 'get_provinces')
{
	//provinces

	$provs = Location::get_provinces();

	if($provs){
		$response = array('status'=>true, 'data'=>$provs);		
	}else{
		$response = array('status'=>false, 'msg'=>"Not found");
	}	
}else if($action == 'get_districts')
{
	//district
	$province = $request['province']??"";

	$districts = Location::get_districts($province);

	if($districts){
		$response = array('status'=>true, 'data'=>$districts);		
	}else{
		$response = array('status'=>false, 'msg'=>"Not found");
	}	
}
else if($action == 'get_sectors')
{
	//district
	$district = $request['district']??"";

	$districts = Location::get_sectors($district);

	if($districts){
		$response = array('status'=>true, 'data'=>$districts);
	}else{
		$response = array('status'=>false, 'msg'=>"Not found");
	}	
}else if($action == 'cooperative_send_message'){
	//communication
	$institution = $request['institution']??"";
	$cooperative = $request['cooperative']??"";
	$thread = $request['thread']??"";
	$subject = $request['subject']??"";
	$message = $request['message'];
	$type = $request['type']??"";
	$writtenBy = $request['writtenBy']??"institution";
	$doneBy = $request['doneBy']??"";

	//if thread is passed we can get cooperativeId and we wont create new thread
	if($thread){
		$threadData = $Cooperative->get_thread($thread);
		// print_r($threadData);
		// var_dump($thread);
		$cooperative = $threadData[0]['cooperativeId'];

		//here we can reply
		$com_id = $Message->reply_thread($thread, $institution, $cooperative, $message, $doneBy);
		if($com_id){
			$response = array('status'=>true);
		}else{
			$response = array('status'=>false);
		}
	}else if($cooperative && $subject){
		//here we create a new thread
		$thread_id = $Message->create_thread($institution, $cooperative, $type, $writtenBy, $subject, $message, $doneBy);
		if($thread_id){
			$response = array('status'=>true);
		}else{
			$response = array('status'=>false);
		}
	}

}else{
	$response = array('status'=>false, 'msg'=>"Specifiy action");
}

echo json_encode($response);

//Utility functions
function form_response($status, $msg='', $data= array()){
	// header("Content-Type: application/html");
	//removing nulls from response dataa
	$data = checknull($data);
	return array('status'=>$status, 'msg'=>$msg, 'data'=>$data);
}

function checknull($array){
	$cool_array  = array();
	//checks array again null

	// $depth = array_depth($array);

	// for($n=0; $n<$depth; $n++){
	// 	foreach ($variable as $key => $value) {
	// 		# code...
	// 	}
	// }

	// if($depth == 1){
	// }

	// if(is_array($value)){
	// 	cool_array[$key] = checknull($value);
	// }else{
	// 	$cool_array[$key] = $array[$key]??"";
	// }

	return $array;
}
function array_depth(array $array) {
	$max_depth = 1;

	foreach ($array as $value) {
		if (is_array($value)) {
			$depth = array_depth($value) + 1;

			if ($depth > $max_depth) {
				$max_depth = $depth;
			}
		}
	}

	return $max_depth;
}
?>