<?php 
	session_start();
	if(isset($_SESSION['username'], $_SESSION['password'])){
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		//checking the login
		$user = login($username, $password, false);
		if($user){
			$user_data = get_user($user);

			$user_data['cooperative'] = is_cooperative_leader($user);
			$user_data['cooperativeId'] = is_cooperative_leader($user)['cooperative'];

			//keeping the userId
			$currentUserId = $thisid = $user;

			$currentUserNames = $user_data['names'];
    		$currentUserType = $user_data['account_type'];

			$currentUser = (object)$user_data;

			//check the default image
			if(!$currentUser->profile_picture){
				$currentUser->profile_picture = '/images/users/default.jpg';
			}
		}else{
			header("location:login");
		}
	}else{
		header("location:login");
	}
?>