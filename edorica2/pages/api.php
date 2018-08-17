<?php
	//prohibit direct access
	if(!$edorica){
		echo json_encode("Bye");
	}
	$get_requests = $page->path['query_vars']??array();
	$request = array_merge($_POST, $get_requests);
	
	$action = $request['action']??"";

	$Examres = WEB::getInstance("Examres");

	$response = array();
	if($action == 'get_result'){
		$username = $request['username']??"";
		$password = $request['password']??"";
		$code = $request['code']??"";

		if($ap_id = authenticate($username, $password)){
			//LOGGINT THE REQUEST
			$conn->query("INSERT INTO api_requests(api_user, code) VALUES($ap_id, \"$code\")") or trigger_error("Error logging API"+$conn->error);
			if($code){
				$marks = $Examres->check($code);
				$Examres->results($code);
				set_return(true, $marks);
			}else{
				set_return(false, array('msg'=>"Provide student code"));
			}

		}else{
			set_return(false, array('msg'=>"Incorrect username and/or password"));
		}
	}else{
		set_return(false, array('msg'=>"Provide correct action"));
	}
	
	function set_return($status, $return_data){
		global $response;
		$response = array('status'=>$status, $return_data);
		return $response;
	}
	function authenticate($username, $password){
		global $conn;
		$query = $conn->query("SELECT * FROM api_user WHERE username =\"$username\" AND password = \"$password\" LIMIT 1 ") ;
		if($query){
			$data = $query->fetch_assoc();
			return $data['id'];
		}else{
			$ret = "Connection error $conn->error";
			return false;
		}
	}

	echo json_encode($response);
?>