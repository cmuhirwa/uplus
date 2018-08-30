<?php
// START INITIATE
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
		echo 'CASUAL API V01';
	}
// END INITIATE
// START LOG
    $f = fopen("logs/casual.txt", 'a') or die("Unable to open file!");;
<<<<<<< HEAD
    fwrite($f, json_encode($request)."\n\n");
=======
    fwrite($f, json_encode($_POST)."\n\n");
>>>>>>> dd1729f11bd0b432ff50467cc6e4a6a6330f19c7
    fclose($f);
// END LOG

// START EMPLOYEE
	function registerEmployee()
	{
		require('db.php');
		$empName		= mysqli_real_escape_string($db, $_POST['empName']??"");
		$nid			= mysqli_real_escape_string($db, $_POST['nid']??"");
		$phoneNumber	= mysqli_real_escape_string($db, $_POST['phoneNumber']??"");
			//CLEAN PHONE
		$phoneNumber 	= preg_replace( '/[^0-9]/', '', $phoneNumber );
		$phoneNumber 	= substr($phoneNumber, -10);

		// VERIFY IF THE EMPLOYEE WAS CREATED BEFORE AND UPDATE
		$sqlVerify = $db->query("SELECT * FROM casuals WHERE nid = '$nid'");
		if(mysqli_num_rows($sqlVerify)>0)
		{
			//UPDATE THE EXISTING EMPLOYEE
			$sql = $db->query("UPDATE casuals SET name='$empName', phone='$phoneNumber', updatedBy=1, updatedDate=now() WHERE nid = '$nid'");
			echo '<div style="color: green">'.$empName.' is Updated!</div>';

				$lastId = $db->insert_id;
				createNidHandle($lastId,$nid,$phoneNumber,$empName);
		}
		else
		{

			// ADD THE EMPLOYEE
			$db->query ("INSERT INTO 
				casuals (
				name, nid, phone, img, finger, createdBy) 
				VALUES (
				'$empName', '$nid', '$phoneNumber', 'avatar/1.jpg', '1', '1')");
			if($db){
				echo '<div class="callout callout-success">
                <h4><i class="icon fa fa-check"></i> '.$empName.' is Added!</h4></div>';
				$lastId = $db->insert_id;
				createNidHandle($lastId,$nid,$phoneNumber,$empName);
			}
			else {
				echo 'Error!';
			}
		}
	}

	function loopEmpData()
	{
		sleep(1);
		?>
		<table class="table table-striped table-bordered">
            <thead>
            <tr>
              <th>
                No
              </th>
              <th>
                Names
              </th>
              <th>
                Phone
              </th>
              <th>
                NID
              </th>
            </tr>
            </thead>
            <tbody>
            <?php
             // $n=0;
              include 'db.php';
              $sql = $db->query("SELECT * FROM casuals ORDER BY id DESC");
              $n = mysqli_num_rows($sql);
              while ($row = mysqli_fetch_array($sql)) 
              {
                $nid = $row['nid'];
                echo '<tr onclick="loopHandle('.$nid.')" class="loopEmpHandle">
                    <td>'.$n.'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['phone'].'</td>
                    <td>'.$row['nid'].'</td>
                  </tr>';
                  $n--;
              }
            ?></tbody>
          </table>
		<?php
	}
// END EMPLOYEE 

// START PAYROLL
	function addPayroll()
	{
		require('db.php');
		$fromDate	= mysqli_real_escape_string($db, $_POST['fromDate']??"");
		$toDate		= mysqli_real_escape_string($db, $_POST['toDate']??"");
		$startOn	= mysqli_real_escape_string($db, $_POST['startOn']??"");
		$startOff	= mysqli_real_escape_string($db, $_POST['startOff']??"");
		$endOn		= mysqli_real_escape_string($db, $_POST['endOn']??"");
		$endOff		= mysqli_real_escape_string($db, $_POST['endOff']??"");

		// ADD THE EMPLOYEE
		$db->query ("INSERT INTO payrolls(
			fromDate, toDate, startOn, startOff, stopOn, stopOff, createdBy)
			VALUES (
			'$fromDate', '$toDate', '$startOn','$startOff','$endOn','$endOff', 1)")or die(mysqli_error($db));
		if($db){
			echo '<div class="callout callout-success">
                <h4><i class="icon fa fa-check"></i>'.$fromDate.' Payroll is Added!</h4>
              </div>';
		}
		else {
			echo 'Error!';
		}
	}
	
	function loopPayrollData()
	{
		?>
		
                    <table class="table table-striped table-bordered">
            <tr>
              <th>
                No
              </th>
              <th>
                Duration
              </th>
              <th>
                Amount
              </th>
              <th>
                Paid
              </th>
              <th>
                Unpaid
              </th> 
              <th>
                
              </th>
            </tr>

            <?php
                include 'db.php';
                $sql = $db->query("SELECT P.fromDate,P.toDate, P.id payrollCode,
                  IFNULL((SELECT SUM(T.amount) FROM transactionsview T WHERE T.payrollCode = P.id),0) amount,
                  IFNULL((SELECT COUNT(CP.casualCode) FROM casualpayroll CP WHERE CP.payrollCode = P.id),0) casuals,
                  IFNULL((SELECT SUM(T.amount) FROM transactionsview T WHERE T.payrollCode = P.id AND T.paymentStatus = 'APPROVED'),0) paidAmount,
                  IFNULL((SELECT COUNT(DISTINCT T.casualCode) FROM transactionsview T WHERE T.payrollCode = P.id AND T.paymentStatus = 'APPROVED'),0) paidCasuals,
                  IFNULL((SELECT SUM(T.amount) FROM transactionsview T WHERE T.payrollCode = P.id AND T.paymentStatus <> 'APPROVED'),0) unpaidAmount,
                  IFNULL((SELECT COUNT(DISTINCT T.casualCode) FROM transactionsview T WHERE T.payrollCode = P.id AND T.paymentStatus <> 'APPROVED' LIMIT 1),0) unpaidCasuals
                  FROM payrolls P ORDER BY P.id DESC");
                $n = mysqli_num_rows($sql);
                while ($row = mysqli_fetch_array($sql)) {
                  echo '
                    <tr>
                      <td><a>'.$n.'</a></td>
                      <td>'.strftime("%d %b", strtotime($row['fromDate'])).' - '.strftime("%d %b", strtotime($row['toDate'])).'</td>
                      <td>'.number_format($row['amount']).'Rwf  &nbsp; | '.$row['casuals'].'</td>
                      <td>'.number_format($row['paidAmount']).'Rwf  &nbsp; | '.$row['paidCasuals'].'</td>
                      <td>'.number_format($row['unpaidAmount']).'Rwf  &nbsp; | '.$row['unpaidCasuals'].'</td>
                      <td>
                        <a href="payroll_info.php?payroll='.$row['payrollCode'].'" type="button" class="casual-btn-info">View more <i class="fa fa-unlock"></i></a>
                      </td>
                    </tr>
                  ';
                  $n--;
                }
              ?>
            
            </tr>
          </table>

		<?php
	}
// END PAYROLL

// START EMPLOYEE PAYROLL
	function addEmpOnPayroll()
	{
		require('db.php');
		$catCode		= mysqli_real_escape_string($db, $_POST['catCode']??"");
		$empId			= mysqli_real_escape_string($db, $_POST['empId']??"");
		$payrollCode	= mysqli_real_escape_string($db, $_POST['payrollCode']??"");
		
		if ($catCode == 0) {
			$sql = $db->query ("DELETE FROM casualpayroll WHERE payrollCode = '$payrollCode' AND casualCode = '$empId'")or die(mysqli_error($db));
			echo '<div style="color: green">'.$empId.' Is removed on the payroll!</div>';
		}else
		{
			// CHECK IF THE EMPLOYEE WAS ALREADY ON THE PAYROLL
			$sql = $db->query ("SELECT * FROM casualpayroll WHERE casualCode = '$empId' AND payrollCode = '$payrollCode'");
			if(mysqli_num_rows($sql)>0){
				$db->query ("UPDATE casualpayroll SET 
					categoryCode='$catCode', updatedBy= 1, updatedDate= now() WHERE payrollCode = '$payrollCode' AND casualCode = '$empId'")or die(mysqli_error($db));
				if($db){
					echo '<div style="color: green">'.$empId.' Payroll is updated!</div>';
				}
			}else{


				// ADD THE EMPLOYEE ON PAYROLL
				$db->query ("INSERT INTO casualpayroll(
					casualCode, categoryCode, payrollCode, createdBy)
					VALUES (
					'$empId', '$catCode', '$payrollCode', 1)")or die(mysqli_error($db));
				if($db){
					echo '<div class="callout callout-success">
                <h4><i class="icon fa fa-check"></i>'.$empId.' Payroll is Added!</h4>
              </div>';
				}
				else {
					echo 'Error!';
				}
			}
		}
	}
	

	function loopEmpOnPayroll()
	{
		require('db.php');
		$payrollId		= mysqli_real_escape_string($db, $_POST['payrollId']??"");
		
		?>

		<table class="table table-striped table-bordered">
            <thead>
	            <tr>
	              <th>
	                No
	              </th>
	              <th>
	                Name
	              </th>
	              <th>
	                Category
	              </th>
	              <th>
	                Counts
	              </th>
	              <th>
	                Amount
	              </th>
	              <th>
	                UnpaidAmount
	              </th>
	              <th>
	                Status
	              </th>
	            </tr>
            </thead>
		<tbody id="peopleTable">
           <?php 
                include 'db.php';
                $sql = $db->query("SELECT C.name, C.handleid, CT.catName category, CP.casualCode, C.phone,
						              IFNULL((SELECT SUM(PT.amount) FROM payrolltransactions PT WHERE PT.casualCode = CP.casualCode AND PT.payrollCode = '$payrollId'),0)amount,
						              IFNULL((SELECT SUM(PT.amount) FROM payrolltransactions PT WHERE PT.casualCode = CP.casualCode AND (PT.payrollCode = '$payrollId' AND paymentStatus <> 'APPROVED')),0)unpaidAmount,
						              IFNULL((SELECT COUNT(PT.casualCode) FROM payrolltransactions PT WHERE PT.casualCode = CP.casualCode AND PT.payrollCode = '$payrollId'),0)casuals,
						              IFNULL((SELECT paymentStatus FROM payrolltransactions PT WHERE PT.casualCode = CP.casualCode AND PT.payrollCode = '$payrollId'  ORDER BY id DESC LIMIT 1),0)paymentStatus
						              FROM casualpayroll CP 
						              INNER JOIN casuals C 
						              ON C.id = CP.casualCode
						              INNER JOIN categories CT 
						              ON CT.id = CP.categoryCode
						              WHERE CP.payrollCode = '$payrollId'");
                $n= mysqli_num_rows($sql);
                while ($row = mysqli_fetch_array($sql)) {
                	if ($row['paymentStatus'] == 'APPROVED') {
                		$tr = 'PAID';
                		$row['paymentStatus'] = 'PAID';
                	}else{
                		$tr = 'UNPAID';
                		$row['paymentStatus'] = 'UNPAID';
                	}
                  echo '
                    <tr class="'.$tr.'" data-names="'.$row['casualCode'].'" data-handleId="'.$row['handleid'].'" data-gender="'.$payrollId.'" data-amount="'.$row['amount'].'" data-account="'.$row['phone'].'">
                      <td>'.$n.'</td>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['category'].'</td>
                      <td>'.number_format($row['casuals']).'</td>
                      <td>'.number_format($row['amount']).'Rwf</td>
                      <td>'.number_format($row['unpaidAmount']).'Rwf</td>
                      <td>'.$row['paymentStatus'].'</td>
                    </tr>
                  ';
                  $n--;
                  
                }
              ?>
              </tbody>
          </table>
          

					<?php
				}


	function payrollBalance()
	{
		require('db.php');
		$payrollId		= mysqli_real_escape_string($db, $_POST['payrollId']??"");
		$sql = $db->query("SELECT  SUM(amount) amount FROM payrolltransactions WHERE payrollCode = '$payrollId'");
	    $row = mysqli_fetch_array($sql);
	    echo '&nbsp;&nbsp;'.number_format($row['amount']).'RWF';           
	}
	function paidBalance()
	{
		require('db.php');
		$payrollId		= mysqli_real_escape_string($db, $_POST['payrollId']??"");
		$sql = $db->query("SELECT  SUM(amount) amount FROM payrolltransactions WHERE payrollCode = '$payrollId' AND paymentstatus = 'APPROVED'");
	    $row = mysqli_fetch_array($sql);
	    echo '&nbsp;&nbsp;'.number_format($row['amount']).'RWF';           
	}
	function unpaidBalance()
	{
		require('db.php');
		$payrollId		= mysqli_real_escape_string($db, $_POST['payrollId']??"");
		$sql = $db->query("SELECT  SUM(amount) amount FROM payrolltransactions WHERE payrollCode = '$payrollId' AND paymentstatus <> 'APPROVED'");
	    $row = mysqli_fetch_array($sql);
	    echo '&nbsp;&nbsp;'.number_format($row['amount']).'RWF';           
	}
// END EMPLOYEE PAYROLL

// START ATTENDANCE
	function worked()
	{
		require('db.php');
		$casualCode		= mysqli_real_escape_string($db, $_POST['casualCode']??"");
		$payrollCode	= mysqli_real_escape_string($db, $_POST['payrollCode']??"");
		
		
		$sql = $db->query("SELECT CP.categoryCode, C.catAmount amount, P.startOn, P.startOff, P.stopOn, P.stopOff
		FROM casualpayroll CP
		INNER JOIN categories C 
		ON C.id = CP.categoryCode
        INNER JOIN payrolls P
        ON P.id = CP.payrollCode
		WHERE CP.casualCode = '$casualCode' AND CP.payrollCode = '$payrollCode'");
		$row =mysqli_fetch_array($sql);
		echo $categoryCode = $row['categoryCode'];
		echo $amount = $row['amount'];
		echo '</br>startOn: '.$startOn = $row['startOn'];
		echo '</br>startOff: '.$startOff = $row['startOff'];
		echo 'stopOn: '.$stopOn = $row['stopOn'];
		echo ' </br>stopOff: '.$stopOff = $row['stopOff'];
		echo ' </br>Now: '.$now= date("H:i:s", time());
		
		if($now > $startOn && $now < $startOff){
			echo "CHECKIN";

			$db->query("INSERT INTO payrolltransactions(payrollCode, amount, casualCode, categoryCode)
			VALUES('$payrollCode','$amount','$casualCode','$categoryCode')")or die(mysql_error($db));
			if ($db) {echo "done";		}else{echo "wapi";}
		}
		elseif($now > $stopOn && $now < $stopOff) {
			echo "CHECKOUT";

			$db->query("INSERT INTO payrolltransactions(payrollCode, amount, casualCode, categoryCode)
			VALUES('$payrollCode','$amount','$casualCode','$categoryCode')")or die(mysql_error($db));
			if ($db) {echo "done";		}else{echo "wapi";}	
		}
		else{ 
			echo"OUT OF TIME"; 
		}
	}
// END ATTENDANCE

// START CREATE HANDLE
	function createNidHandle($lastId, $nid, $phoneNumber, $empName)
	{
			require('db.php');
			//	START GENERATE A HANDLE
			$method ='POST';
	        $headers = array();
	        $headers[] = 'Content-Type: application/json';
	        $headers[] = 'Authorization: LBE7YRZPUCOCLQOXBMPJUWKS0EMUZ8MJ';
	         
	        $handleid= "25.001/CREDITSCORE/".$nid;
	        $url ="https://188.166.243.121:8880/".$handleid;
    		//$url ="https://197.243.0.244:8880/".$handleid;
	     	$data = '
	        			{
		                    "authkey": "LGAGZYYRP5SUYPKPHQLFW9NGKUHHZJBC",
		                    "handleid": "25.001\/CREDITSCORE\/'.$nid.'",
		                    "values": 
		                    [
		                    	{
				                    "type": "CITIZEN_INDENTIFIER",
				                    "value":"'.$nid.'",
				                    "adminRead": true,
				                    "adminWrite": true,
				                    "publicRead": true,
				                    "publicWrite": false,
				                    "index": "1001"                
			                    }, 
			                    {
				                   "type":"NAMES",
				                   "value":"'.$empName.'",
				                   "adminRead": true,
				                   "adminWrite": true,
				                   "publicRead": true,
				                    "publicWrite": false,
				                    "index": "1002"      
			                    },
			                    {
				                   "type":"PHONE",
				                   "value":"'.$phoneNumber.'",
				                   "adminRead": true,
				                   "adminWrite": true,
				                   "publicRead": true,
				                    "publicWrite": false,
				                    "index": "1003"      
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
	       
	       	// echo $output = curl_exec($curl);
	       	// echo $handleid;
	        //echo '<div style="color: green">(On DOA)</div>';
				
			//	END GENERATE A HANDLE

			//	START SAVE THE HANDLE ID
			$sql = $db->query("UPDATE casuals SET handleId='$handleid', updatedBy=1, updatedDate=now() WHERE id = '$lastId'");
			//	END SAVE THE HANDLE ID
	}
// END CREATE HANDLE

// PEYMENT
	function payCasual()
	{
		require('db.php');
		$casualCode		= mysqli_real_escape_string($db, $_POST['casualCode']??"");
		$payrollCode	= mysqli_real_escape_string($db, $_POST['payrollCode']??"");
		$account		= mysqli_real_escape_string($db, $_POST['account']??"");
		$handleId		= mysqli_real_escape_string($db, $_POST['handleId']??"");
		
		$sql 	= $db->query("SELECT SUM(amount) amount FROM payrolltransactions WHERE casualCode = '$casualCode' AND (payrollCode = '$payrollCode' AND paymentStatus = 'HOLD')") or die(mysql_error($db));
		$row 	= mysqli_fetch_array($sql);
		$amount = $row['amount'];
		$sql = $db->query("SELECT 				
						IFNULL((SELECT SUM(T.amount) FROM transactions T WHERE operation='IN'),0)-
						IFNULL((SELECT SUM(T.amount) FROM transactions T WHERE operation='OUT'),0) balance
						");
	    $row = mysqli_fetch_array($sql);
	    if($amount > $row['balance'])
	    {
	    	echo "WALLET LOW BALLANCE!";
	    }
	    else
	    {

			$sql 	= $db->query("INSERT INTO 
				transactions (amount, operation, account, createdBy)
				VALUES ('$amount', 'OUT', '$account', '1')
				") or die(mysqli_error($db));
			$sql 			= $db->query("UPDATE payrolltransactions SET paymentStatus = 'APPROVED' WHERE casualCode = '$casualCode' AND payrollCode = '$payrollCode'") or die(mysqli_error($db));
			// CALLTHE LIQUIDATE API
			//action: 'liquidate',
			//amount: '$amount',
			//fromphone: '$account'
			echo "DONE!";
		}
	}
// END PEYMENT


// START SETUP
	function loopCategories()
		{
			require('db.php');
			
			?>

			<table class="table table-striped table-bordered">
	            <thead>
		            <tr>
		              <th>
		                No
		              </th>
		              <th>
		                Name
		              </th>
		              <th>
		                Amount
		              </th>
		              <th>
		                Action
		              </th>
		            </tr>
	            </thead>
			<tbody id="peopleTable">
	           <?php 
	                include 'db.php';
	                $sql = $db->query("SELECT * FROM categories");
	                $n= mysqli_num_rows($sql);
	                while ($row = mysqli_fetch_array($sql)) {
	                  echo '
	                    <tr>
	                      <td>'.$n.'</td>
	                      <td>'.$row['catName'].'</td>
	                      <td>'.number_format($row['catAmount']).'Rwf</td>
	                      <td><a style="cursor: pointer;" onclick="removeCat('.$row['id'].')">X</a></td>
	                    </tr>
	                  ';
	                  $n--;
	                  
	                }
	              ?>
	              </tbody>
	          </table>
	          

						<?php
					}

	function loopWallet()
		{
			require('db.php');
			include 'db.php';
                $sql = $db->query("SELECT 				
								IFNULL((SELECT SUM(T.amount) FROM transactions T WHERE operation='IN'),0)-
								IFNULL((SELECT SUM(T.amount) FROM transactions T WHERE operation='OUT'),0) balance
								");
                $row = mysqli_fetch_array($sql);
			?>

			<table class="table table-striped table-bordered">
	            <thead>
		            <tr>
		              <th>
		                Amount
		              </th>
		            </tr>
	            </thead>
				<tbody>
					<td><?php echo number_format($row['balance']); ?> RWF</td>
	           	</tbody>
	         </table>
	          
			<?php
				
				

		}

	function loadMoney()
	{
		require('db.php');
		$account			= mysqli_real_escape_string($db, $_POST['account']??"");
		$amount			= mysqli_real_escape_string($db, $_POST['amount']??"");
		$sql 			= $db->query("INSERT INTO 
				transactions (amount, operation, account, createdBy)
				VALUES ('$amount', 'IN', '$account', '1')
				") or die(mysqli_error($db));
		
<<<<<<< HEAD
		$url = 'http://www.uplus.rw/api/index.php';
		$data 					= array();
		$data["action"] 		= "topup";
		$data["amount"] 		= $amount;
		$data["senderPhone"] 	= $account;
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		echo $result = file_get_contents($url, false, $context);

=======
>>>>>>> dd1729f11bd0b432ff50467cc6e4a6a6330f19c7
	}

	function addCategory()
	{
		require('db.php');
		$catName		= mysqli_real_escape_string($db, $_POST['catName']??"");
		$catAmount		= mysqli_real_escape_string($db, $_POST['catAmount']??"");
		$sql 			= $db->query("INSERT INTO 
				categories (catName, catAmount, createdBy)
				VALUES ('$catName', '$catAmount', '1')
				") or die(mysqli_error($db));	
	}

	function removeCat()
	{
		require('db.php');
		$catId		= mysqli_real_escape_string($db, $_POST['catId']??"");
		$sql 		= $db->query("DELETE FROM categories WHERE id ='$catId'") or die(mysqli_error($db));	
	}
// END SETUP

function resolveHandle()
{
	require('db.php');
	$handleid1		= mysqli_real_escape_string($db, $_POST['handleId']??"");
	$handleid= "25.001/CREDITSCORE/".$handleid1;
    
	$method ='GET';
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: LBE7YRZPUCOCLQOXBMPJUWKS0EMUZ8MJ';
     
    $url ="https://188.166.243.121:8880/".$handleid;
    //$url ="https://197.243.0.244:8880/".$handleid;
       
   	$curl = curl_init();
    curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER =>false,
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
    ));
   
    $result = curl_exec($curl);
    //$handleid;
    if ($result === FALSE) 
	{ 
		echo 'Sorry Network issue!';
	}
	else
	{
		$result 	= json_decode($result, true);
		
		
		if ($result['responseCode'] == 1)
		{
			?>
			Experiance: <i class="fa fa-trophy">5</i></br>
			Current Job:</br>
			Disciplinebeing: 70% ontime</br>
			Jobs history Ocurrency(chart):</br>
			Average salary: 7,500Rwf</br>
			<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Area Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="areaChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
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

// START ZAMU
	function zamuiot()
	{
		include 'db.php';
		$zamuId = $_POST['zamuId'];
		$sql = $db->query("SELECT state FROM zamu WHERE zamuname = '$zamuId'");
		$row = mysqli_fetch_array($sql);
		if($row['state'] == "RECORD")
		{
			$sql = $db->query("SELECT id FROM casuals ORDER BY id DESC LIMIT 1");
			$row = mysqli_fetch_array($sql);
			$sql = $db->query("UPDATE zamu SET state = 'RECORDED' WHERE zamuname = '$zamuId'");
			$return = array('action' => 'RECORD','casualId' => $row['id']+1 );
			header('Content-Type: application/json');
			$return = json_encode($return);
			echo $return;
		}
		elseif($row['state'] == "ATTEND")
		{
			//$casualId
			//$zamuId
			$return = array('action' => 'ATTEND','message' => 'NAME XYZ attended' );
			echo $return;
		}
		else{$return = array('action' => 'WAITING','message' => 'Waiting for the app to register' );
			echo $return;}
	}

	function checkzamustate()
	{
		include 'db.php';
		$sql = $db->query("SELECT id, state FROM zamu WHERE state = 'RECORDED'");
		if(mysqli_num_rows($sql)>0)
		{
			$zamuId = mysqli_fetch_array($sql)['id'];
			$sql = $db->query("UPDATE zamu SET state = 'ATTEND' WHERE zamuname = '$zamuId'");
			echo "RECORDED";	
		}else{
			$sql = $db->query("UPDATE zamu SET state = 'RECORD'")or die(mysql_error($db));
			echo "WAITING";
		}
		
	}
// END ZAMU