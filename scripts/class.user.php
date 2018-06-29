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

		public function checkEmail($email)
		{
			global $db;
			//checks if the email belongs to someone or not
			$email = $db->real_escape_string($email);

			$sql = "SELECT * FROM users WHERE email = \"$email\" LIMIT 1";
			$query = $db->query($sql) or trigger_error("Can't get user data $db->error");
			if($query->num_rows){
				$data = $query->fetch_assoc();
				return $data['id'];
			}else return false;
			
		}

		public function changePassword($userId, $password)
		{
			//changes password
			global $db;
			$userId = $db->real_escape_string($userId);
			$password = password_hash($db->real_escape_string($password), PASSWORD_DEFAULT);

			$query = $db->query("UPDATE users SET loginPassword = \"$password\" WHERE id = \"$userId\" ") or trigger_error($db->error);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function create($name, $image, $email, $password, $loginPassword = '', $gender = '', $phone, $createdBy=1)
		{
			//creates the user in uplus
			global $db;

			$phone 	= preg_replace( '/[^0-9]/', '', $phone );
			$phone 	= substr($phone, -12);

			$loginPassword = password_hash($db->real_escape_string($loginPassword), PASSWORD_DEFAULT);

			//check if the phone or email or already exists to avoid duplicates
			$sql = "SELECT * FROM users WHERE (email = \"$email\" AND email != '') OR (phone = \"$phone\" AND phone !='') ";
			// echo "$sql";
			$c = $db->query($sql) or trigger_error($db->error);
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