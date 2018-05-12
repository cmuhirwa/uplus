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
	$img 		= $_GET['img'];
	$names 	= $_GET['names'];
	$gender 	= $_GET['gender'];
	$dob 		= '01/01/1990';//$_GET['dob'];
	$nid 		= $_GET['nid'];
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
       
        echo $output = curl_exec($curl);
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

// START RESOLVE
function resolveHandle()
{
	$handleId 	= '25.001/'.$_GET['handleId'];
	$handleCode = $_GET['handleCode'];


	$url = 'http://175.139.242.87:8000/api/handles/'.$handleId.'?pretty';

	$data["timestamp"]	= 'test';
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
		$returnedinformation	= 'Sorry handle not available!';
	}
	else
	{
		$result 	= json_decode($result, true);
		
		
		if ($result['responseCode'] == 1)
		{
			?>
				<table class="table table-striped table-bordered">
	  				<thead>
	  					<tr>
	  						<th>No.</th>
	  						<th>Info</th>
	  						<th>Description</th>
	  					</tr>
	  				</thead>
	  				<tbody>
	  					<tr style="text-align: left;">
	  						<td>1</td>
	  						<td>HandleId</td>
	  						<td><?php echo $result['handle'];?></td>
	  					</tr>
	  					<?php
	  					$n=1;
		  					foreach($result['values'] as $values){
							if ( $values['type']=="type")
									{
											
									}
									$n++;
								foreach($values['data'] as $data){
									if (is_array($data) || is_object($data) || $data=="admin" || $data=="string")
									{

									}else{echo 
										'<tr style="text-align: left;">
	  						<td>'.$n.'</td>
	  						<td>'.$values['type'].'</td>
	  						<td>'.$data.'</td>
	  					</tr>';}
								}
							}
						if ($handleCode == NULL || $handleCode == "") {
							# code...
						}else{
							?>

						<tr style="text-align: left;">
	  						<td>8</td>
	  						<td>Education</td>
	  						<td>Master's Degree</td>
	  					</tr>
						<tr style="text-align: left;">
	  						<td>9</td>
	  						<td>Education</td>
	  						<td>Bachaloret Degree</td>
	  					</tr>
						<tr style="text-align: left;">
	  						<td>10</td>
	  						<td>Education</td>
	  						<td>A'level Degree</td>
	  					</tr>
						<tr style="text-align: left;">
	  						<td>11</td>
	  						<td>Education</td>
	  						<td>O'level Diploma</td>
	  					</tr>
						<tr style="text-align: left;">
	  						<td>12</td>
	  						<td>Education</td>
	  						<td>Primary Diploma</td>
	  					</tr>
						<tr style="text-align: left;">
	  						<td>13</td>
	  						<td>Education</td>
	  						<td>Nursary Diploma</td>
	  					</tr>
							<?php
						}

							?>
	  					
	  				</tbody>
				</table>
		<?php
		}
		elseif($result->{'responseCode'} == 100)
		{
			echo 'The handle Id is unvailable';
		}	
	}		
}
// END RESOLVE
?>
