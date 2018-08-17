<?php
//Class to help in user authentication
class user{
	function loginForm(){
		include("scripts/login/login_form.php");
		}
	function authenticate($email, $password){
		//This is the function to check if the user-records exists in the database
		global $conn, $salt;
		$email = mysqli_real_escape_string($conn, $email);
		$password = mysqli_real_escape_string($conn, $password);

		//Hashing
		$password = bin2hex(mhash(MHASH_MD5, $password, $salt));

		$query = mysqli_query($conn , "SELECT id FROM users WHERE email = \"$email\" AND password = \"$password\" LIMIT 1") or die(mysqli_error($conn));
		if(mysqli_num_rows($query)>0){
			//Here the user was found in the database we can move on and return her id
			$data = mysqli_fetch_assoc($query);
			return $data['id'];
		}else return false;

	}
	function getsession(){
		//Function to return current session code
		//we will use session variable in $COOKIES
		global $conn;

		//if(empty($_COOKIE['PHPSESSID'])) session_start();

		if(isset($_COOKIE['PHPSESSID'])){
			$session= mysqli_real_escape_string($conn, $_COOKIE['PHPSESSID']);
			return $session;
		}else{
			//Here the device can not keep cookies
		}

	}
	function checksession(){
		//Checking the DB user associated with the session
		$session = $this->getsession();

		global $conn;

		$query = mysqli_query($conn, "SELECT user.id FROM users as user JOIN user_sessions as session ON session.user = user.id WHERE session_id=\"$session\" AND status = 'active'") or die(mysqli_error($conn));
		if(mysqli_num_rows($query)>0){
			//Here the user has the active session
			$data = mysqli_fetch_assoc($query);
			$id = $data['id'];
			return $id;
		}else return false;
	}
	function id(){
		global $conn, $login_email_hash;

		//Getting user ID of the logged in user wich id returned by login status when user is logged in
		$userID = $this->login_status();

		if($userID){
			return $userID;
			}
		else return false;
		}
	function pfname($id){
		global $conn;
		$uq = mysqli_query($conn, "SELECT fname FROM users WHERE id=$id") or die(mysqli_error($conn));
		$ud = mysqli_fetch_assoc($uq);
		$ud = $ud['fname'];
		return ucwords($ud);
	}
	function sessionlogout(){
		//Loggin out by clearing session
		global $conn;
		$session = $this->getsession();

		$query  = mysqli_query($conn, "UPDATE user_sessions SET status='inactive' WHERE session_id = \"$session\"") or die(mysqli_error($conn));
	}
	function authsession($userid){
		//Function to associate a session with a user
		global $conn;
		$session = $this->getsession();
		if($session){
			//Going to keep the session in the database

			$ip = mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR']);

			$browser = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']);

			$query = mysqli_query($conn, "INSERT INTO user_sessions(session_id, user, status, ip, browser) VALUES(\"$session\", \"$userid\", 'active', \"$ip\", \"$browser\")") or die(mysqli_error($conn));
			if($query) return true;
			else return false;
		}else{
			return false;
		}
	}
	function login_status($prestatus=0){
		//Function to check the status of login
		
		global $conn, $login_email_hash, $login_password_hash;
		//We will first check if logged in cookies are set
		//Cookies names are called login_email_hash and login_password_hash

		
		$userid = $this->checksession();
		if($userid){
			return $userid;
		}else return false;
		
		// if(!empty($_COOKIE[$login_email_hash]) && !empty($_COOKIE[$login_password_hash]) ){

		// 	$email = mysqli_real_escape_string($conn, $_COOKIE[$login_email_hash]);
		// 	$pwd = mysqli_real_escape_string($conn, $_COOKIE[$login_password_hash]);
			
		// 	return $this->authenticate($email, $password);
			
		// }else if($prestatus==$GLOBALS['login_success']){
		// 	return 1;
		// }else return false;
	}

	function age($id){
		//Function to get the age of the user
		global $conn;
		$ageq = mysqli_query($conn, "SELECT birthday FROM users WHERE id=$id") or die(mysqli_error($conn));
		$aged = mysqli_fetch_assoc($ageq);
		$birtday = $aged['birthday'];
		
		$year = getdate();
		$year = $year['year'];
		$age = $year-(int)$birtday;
		
		
		if(!empty($aged)) return $age;
		else return false;
		}
	function info($id){
		global $conn;
		$userq = mysqli_query($conn, "SELECT * FROM users WHERE id=$id") or die(mysqli_error($conn));
		$userd = mysqli_fetch_assoc($userq);
		return $userd;
		
		}
		
	function getparent($userID, $relation){
			global $conn;
		
			$pareq = mysqli_query($conn, "SELECT parentID FROM parents WHERE student=$userID and relationship='$relation' LIMIT 1") or die(mysqli_error($conn));
			//Checking if the user has parent
			if(mysqli_num_rows($pareq)>=1){
				$parentID = mysqli_fetch_assoc($pareq);
				$parentID = $parentID['parentID'];
				
				$parentq = mysqli_query($conn, "SELECT fname, lname FROM users WHERE id=$parentID") or die(mysqli_error($conn));

				if(mysqli_num_rows($parentq)>0){
					$parentname = mysqli_fetch_assoc($parentq);
					$parentname = $parentname['lname'].' '.$parentname['fname'];
					return $parentname;
					}
				else return false;
				
				}
			
			
			}
	function parentID($userID, $relation){
		global $conn;
		$pareq = mysqli_query($conn, "SELECT parentID FROM parents WHERE student=$userID and relationship='$relation'") or die(mysqli_error($conn));
		if(mysqli_num_rows($pareq)>0){
			$parent_data = mysqli_fetch_assoc($pareq);
			return $parent_data['parentID'];			
		}
		else return false;
	}
	function phone($id){
		global $conn;
		if($id){
			$phoneq = mysqli_query($conn, "SELECT tel FROM users WHERE id=$id") or die(mysqli_error($conn));
			if(mysqli_num_rows($phoneq)){
				$phoned = mysqli_fetch_assoc($phoneq);
				return $phoned['tel'];
			}
			else return false;
		}
		else return false;
		
	}
	function email($id){
		global $conn;
		if(!$id) return false;
		
		$mailq = mysqli_query($conn, "SELECT email FROM users WHERE id='$id'");
		if($mailq){
			$maild = mysqli_fetch_assoc($mailq);
			return $maild['email'];
			}
		else return false;
		}
	
		
	}
?>