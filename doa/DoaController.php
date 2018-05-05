<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
	if(isset($_GET['action']))
	{
		$_GET['action']();
	}
	else
	{
		echo 'Please read the API documentation';
	}
}
else
{
	echo 'DOA API V 0.1.0';
}


// START NID
function createNidHandle()
{
	require('db.php');
	echo $img 		= $_GET['img'];
	echo $names 	= $_GET['names'];
	echo $gender 	= $_GET['gender'];
	echo $dob 		= $_GET['dob'];
	echo $nid 		= $_GET['nid'];
	$location = $gender;
	$personal_id = $nid;

	//	START GENERATE A HANDLE
		$method ='POST';
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: LBE7YRZPUCOCLQOXBMPJUWKS0EMUZ8MJ';
         
        $handleid= "25.001/CREDITSCORE/".$personal_id;
        $url ="https://197.243.0.244:8880/".$handleid;
     	$data = '
        			{
	                    "authkey": "LGAGZYYRP5SUYPKPHQLFW9NGKUHHZJBC",
	                    "handleid": "25.001\/CREDITSCORE\/'.$personal_id.'",
	                    "values": 
	                    [
	                    	{
			                    "type": "CITIZEN_INDENTIFIER",
			                    "value":"'.$personal_id.'",
			                    "adminRead": true,
			                    "adminWrite": true,
			                    "publicRead": true,
			                    "publicWrite": false,
			                    "index": "1001"                
		                    }, 
		                    {
			                   "type":"NAMES",
			                   "value":"'.$names.'",
			                   "adminRead": true,
			                   "adminWrite": true,
			                   "publicRead": true,
			                    "publicWrite": false,
			                    "index": "1002"      
		                    },
		                    {
			                   "type":"LOCATION",
			                   "value":"'.$location.'",
			                   "adminRead": true,
			                   "adminWrite": true,
			                   "publicRead": true,
			                    "publicWrite": false,
			                    "index": "1003"      
		                    },
		                    {
			                   "type":"DATE_OF_BIRTH",
			                   "value":"'.$dob.'",
			                   "adminRead": true,
			                   "adminWrite": true,
			                   "publicRead": true,
			                    "publicWrite": false,
			                    "index": "1004"      
		                    }
						]
              		}
              	';
               
             
        
       $curl = curl_init();
        curl_setopt_array($curl, array(
	        	CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER =>false,
	       		CURLOPT_URL => $url,
	        	CURLOPT_CUSTOMREQUEST => $method,
	        	CURLOPT_HTTPHEADER => $headers,
				CURLOPT_POSTFIELDS => ($data)
	    ));
       
        $output = curl_exec($curl);
        echo $handleid;
        
	//	END GENERATE A HANDLE

	//	START SAVE THE HANDLE ID
		$sql = $db->query("UPDATE nida SET handleId = '$handleid' WHERE nid = '$nid'");
	//	END SAVE THE HANDLE ID
}

function loopHandles()
{
	?>
	<table class="table table-striped ">
		<thead>
			<tr>
				<th>Handle ID</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				include 'db.php';
				$sqlDoa = $db->query("SELECT IF(handleId IS NULL OR handleId ='','-', handleId) handleId FROM nida")or die(mysqli_error($db));
					
				while($rowDoa = mysqli_fetch_array($sqlDoa))
				{
					echo '<tr><td>'.$rowDoa['handleId'].'</td></tr>';
				}
			?>
		</tbody>
	</table>
	<?php
}
// END NID
?>
