<?php
	//class to handle uplus user details and tasks ..
	class user{
		public function details($userId)
		{
			global $db;
			$query = $db->query("SELECT * FROM uplus.users WHERE id = \"$userId\" ") or trigger_error("Can't get user data $db->error");

			if($query->num_rows){
				return $query->fetch_assoc();
			}else return false;
			
		}

		public function checkUserLogin($email, $password)
		{
			global $db;
			//checks if the login credentials are correct
			$email = $db->real_escape_string($email);
			$password = $db->real_escape_string($password);
			$sql = "SELECT * FROM users WHERE email = \"$email\" LIMIT 1 ";
			$query = $db->query($sql) or trigger_error("Can't get user data $db->error");
			if($query->num_rows){
				$data = $query->fetch_assoc();
				if(password_verify($password, $data['loginPassword'])){
					return $data;
				}else{
					return "incorrect";
				}
			}else return false;
			
		}

		public function create($name, $image, $email, $password, $loginPassword = '', $gender = '', $phone, $createdBy=1)
		{
			//creates the user in uplus
			global $db;

			$phone 	= preg_replace( '/[^0-9]/', '', $phone );
			$phone 	= substr($phone, -12);

			$loginPassword = password_hash($db->real_escape_string($loginPassword), PASSWORD_DEFAULT);

			//check if the phone or email or already exists to avoid duplicates
			$c = $db->query("SELECT * FROM users WHERE email = \"$email\" OR phone = \"$phone\") ") or trigger_error($db->error);
			if($c->num_rows < 1){
				$query = $db->query("INSERT INTO users(name, userImage, phone, email, password, loginPassword, gender, createdBy) VALUES(\"$name\", \"$image\", \"$phone\", \"$email\", \"$password\", \"$loginPassword\", \"$gender\", \"$createdBy\")") or trigger_error($db->error);
				if($query){
					return $db->insert_id;
				}else return false;
			}else{
				return false;
			}

			
			
		}

		function listAll()
		{
			global $db;
			$result = $db->query("SELECT * FROM users WHERE archived = 'no' ORDER BY createdDate DESC") or trigger_error($db->error);
			return $result->fetch_all(MYSQLI_ASSOC);
		}
	}
	
	//instating new class
	$User = new user();
?>