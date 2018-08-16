<?php
	class message{
		public function reply_thread($thread_id, $institution = '', $cooperative = '', $message, $doneBy){
			#Reply thread
			global $conn;
			$threadData = $this->get_thread($thread_id);

			// print_r($threadData);

			if(!$institution){
				//cooperative is replying
				$institution = $threadData[0]['institutionId'];
				$writtenBy = 'cooperative';
			}else{
				//institution is replying
				$cooperative = $threadData[0]['cooperativeId'];
				$writtenBy = 'institution';
			}

			$type = $threadData[0]['type'];

			//creating a message
			$message_id  = $this->create_message("", $message, $doneBy);

			if($message){
				$sql = "INSERT INTO cooperative_communication(type, writtenBy, threadId, messageId, cooperativeId, institutionId, createdBy) VALUES(\"$type\", \"$writtenBy\", \"$thread_id\",  \"$message_id\", \"$cooperative\", \"$institution\", \"$doneBy\")";
				$query = $conn->query($sql) or trigger_error($conn->error);
				return $conn->insert_id;	
			}else{
				return false;
			}
		}

		public function create_thread($institution, $cooperative, $type = 'message', $writtenBy='institution', $subject, $message, $doneBy){
			#Reply thread
			global $conn;

			//creating a message
			$message_id  = $this->create_message($subject, $message, $doneBy);

			if($message){
				$sql = "INSERT INTO cooperative_communication(type, writtenBy, threadId, messageId, cooperativeId, institutionId, createdBy) VALUES(\"$type\", \"$writtenBy\", \"$message_id\",  \"$message_id\", \"$cooperative\", \"$institution\", \"$doneBy\")";

				$query = $conn->query($sql) or trigger_error($conn->error);
				return $conn->insert_id;	
			}else{
				return false;
			}
		}


		public function create_message($subject, $message, $doneBy)
		{
			//creates a message instance
			global $conn;
			$query = $conn->query("INSERT INTO messages(subject, message, createdBY) VALUES(\"$subject\", \"$message\", \"$doneBy\")") or trigger_error($conn->error);
			return $conn->insert_id;
		}

		public function get_thread($thread_id){
			global $conn;

			$query = $conn->query("SELECT * FROM cooperative_communication as C JOIN messages AS M ON C.messageId = M.id WHERE C.threadId = \"$thread_id\" AND M.archived = 'no' ") or trigger_error("Can't get cooperative message $conn->error");

			$messages = array();
			while ($data = $query->fetch_assoc()) {
				$messages[] = $data;
			}

			return $messages;
		}

		public function get_message($messageId){
			global $conn;

			$query = $conn->query("SELECT * FROM messages WHERE id = \"$messageId\" ") or trigger_error($conn->error);
			return $query->fetch_assoc();
		}

		public function get_notifications($institution){
			//returns sent notficatoins
			global $conn;

			$query = $conn->query("SELECT * FROM cooperative_communication WHERE type = 'notification' AND archived = 'no' ") or trigger_error($conn->error);
			return $query->fetch_all(MYSQLI_ASSOC);
		}
	}

	$Message = new message();
?>