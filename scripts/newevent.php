<?php ob_start(); include('../db.php');?>
<?php 
 
if(isset($_GET['page'])){
	$_GET['page']();
	}
?>

<?php
	function finance()
	{ 
		
?>
You are going to recieve a call from our team 
for manual check so that the event can go public
<?php
	}
?>
<?php
if (isset($_POST['eventTitle'])) {
	$eventTitle 		= $_POST['eventTitle'];
	$eventLocation 		= $_POST['eventLocation'];
	$eventStarting 		= $_POST['eventStarting'];
	$eventEnding 		= $_POST['eventEnding'];

	$ticketName1 		= $_POST['ticketName1'];
	$ticketPrice1 		= $_POST['ticketPrice1'];
	$ticketSeats1 		= $_POST['ticketSeats1'];

	$withdrawAccount 	= $_POST['withdrawAccount'];
	$withdrawAccountNo 	= $_POST['withdrawAccountNo'];
	$event_cover 		= $_POST['event_cover']??"";


	//checking file
	/*
	$file_input_name = 'event_cover';
	if($_FILES[$file_input_name]['size']>0){

		$target_dir = "assets/images/events/";
		$tmp_file = basename($_FILES[$file_input_name]['tmp_name']);
		$target_file = $target_dir.basename($_FILES[$file_input_name]['name']);
		$uploadOk = 1;
		$FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		move_uploaded_file(basename($_FILES[$file_input_name]['tmp_name']), $target_file);
	}else{
		echo "No file uploaded";
	}
*/
	//mysql_query for inserting of the events
	$eventDb->query("INSERT INTO 
		events (Event_Name, Event_Cover, Event_Desc, Event_Location, phone, Event_Start, Event_End, user_id, createdBy, Event_organizer) 
		VALUES('$eventTitle', \"$event_cover\", 'testDesc', '$eventLocation', '0788556677', '$eventStarting', '$eventEnding', '1', '1', '1')")or die(mysqli_error($eventDb));
	if($eventDb){
	 $event_last_id =mysqli_insert_id($eventDb);
	}
	for($i = 0; $i < count($ticketName1); $i++)
	{
		// mysql_query for inserting of the pricing 
		$eventDb->query("INSERT INTO pricing (price, event_property, event_seats)
		 			VALUES('$ticketPrice1[$i]','$ticketName1[$i]','$ticketSeats1[$i]')")or die(mysqli_error($eventDb));
		if($eventDb){
			$pricing_last_id 	=mysqli_insert_id($eventDb);
		}
		
		$eventDb->query("INSERT INTO eventing_pricing (event_code, pricing_code) VALUES($event_last_id,$pricing_last_id)") or die("error please".mysqli_error());
	} 
	header('location:../events');	
}
?>
