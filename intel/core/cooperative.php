<?php
	class cooperative extends location{
		function list_all(){
			// return all cooperatives
			global $conn;
			$query = $conn->query("SELECT * FROM cooperatives WHERE archived = 'no' ORDER BY dateAdded DESC") or trigger_error("Error with cooperative retrieval$conn->error");

			$coops = array();

			while($data = $query->fetch_assoc()){
				//getting location
				$location = $this->get_location($data['location']);
				$coops[] = array_merge($data, $location);
			};
			return $coops;

		}
		public function n_members($cooperative)
		{
			# returns number of cooperative members
			global $conn;
			$query = $conn->query("SELECT COUNT(*) as num FROM cooperative_members as c JOIN users AS u ON c.userId = u.id WHERE cooperativeId = \"$cooperative\" LIMIT 1 ") or trigger_error("Error with coop details $conn->error");

			$data = $query->fetch_assoc();

			return $data['num'];
		}

		public function get_members($cooperative)
		{
			# returns cooperative members
			global $conn;
			$query = $conn->query("SELECT * FROM cooperative_members as c JOIN users AS u ON u.id = c.userId WHERE cooperativeId = \"$cooperative\" ORDER BY c.id DESC") or trigger_error("Error with coop details $conn->error");

			$coops = array();

			while($data = $query->fetch_assoc()){
				$coops[] = $data;
			};

			return $coops;
		}

		public function get_cooperative($coop_id)
		{
			# returns cooperative details
			global $conn;
			$sql = "SELECT * FROM cooperatives WHERE cooperativeId = \"$coop_id\" ";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative $conn->error");

			// var_dump($sql);

			$coop = $query->fetch_assoc();


			//getting location
			$coop = array_merge($coop, $this->get_location($coop['location']));

			//getting position and leaders
			$coop = array_merge($coop, array('committee'=>$this->committee($coop_id)));

			return $coop;
		}

		public function gender_summary($coop_id)
		{
			# returns cooperative gender summary
			global $conn;
			$query = $conn->query("SELECT COUNT(*) AS count, gender FROM cooperative_members JOIN users U ON U.id = cooperative_members.userId WHERE cooperativeId = \"$coop_id\" GROUP BY gender ") or trigger_error($conn->error);

			$gender_sum = array();
			while ($data = $query->fetch_assoc()) {
				$gender_sum[$data['gender']] = $data['count'];
			}

			//check if no gender is not present in the cooperative
			$av_gender = array('m', 'f');
			foreach ($av_gender as $key => $value) {
				if(empty($gender_sum[$value])){
					$gender_sum[$value] = 0;
				}
			}

			return $gender_sum;
		}



		public function committee($cooperative, $position = ''){
			//returns person all or specific positions' leaders
			global $conn;

			$query = $conn->query("SELECT * FROM cooperative_committee AS C JOIN users AS U ON C.user = U.id ") or trigger_error($conn->error);
			$pos = array();
			while ($data = $query->fetch_assoc()) {
				$pos[$data['committeePosition']] = $data;
			}
			return $pos;
		}

		public function n_crops($cooperative)
		{
			# returns number of cooperative crops
			global $conn;
			$query = $conn->query("SELECT COUNT(*) as num FROM cooperative_crops WHERE cooperativeId = \"$cooperative\" LIMIT 1 ") or trigger_error("Error with coop details $conn->errror");

			$data = $query->fetch_assoc();

			return $data['num'];
		}

		public function get_crops($coop_id)
		{
			# returns cooperative crops details
			global $conn;
			$query = $conn->query("SELECT *, (SELECT amount FROM crops_pricing WHERE cropId = cr.cropId ORDER BY dateEstablished DESC LIMIT 1) AS pricing, (SELECT variety FROM crop_varieties as v WHERE v.id = cr.cropVariety LIMIT 1) AS variety FROM cooperative_crops as cr JOIN crops AS cp ON cr.cropId = cp.cropId WHERE cooperativeId = \"$coop_id\" ORDER BY cr.id DESC ") or trigger_error("Can't get cooperative $conn->error");

			$crops = array();

			while($data = $query->fetch_assoc()){
				$crops[] = $data;
			};

			return $crops;
		}

		public function crop_pricing($crop)
		{
			# returns cooperative crop pricing
			global $conn;

			$query = $conn->query("SELECT * FROM crops_pricing WHERE cropId = \"$crop\" AND ISNULL(archivedDate) ORDER BY dateEstablished DESC ") or trigger_error($conn->error);

			return $query->fetch_assoc()['amount']??0;
		}


		public function add_leader($cooperative, $user_id, $position, $dutyEnds= '')
		{
			//function to add position in the cooperative
			global $conn;

			//check if the position already existed
			$check = $conn->query("SELECT * FROM cooperative_committee WHERE cooperative = \"$cooperative\" AND committeePosition = \"$position\"  ");
			if($check->num_rows){
				//here we have to stop the leader
				$conn->query("UPDATE cooperative_committee SET status = 'stopped' WHERE cooperative = \"$cooperative\" AND committeePosition = \"$position\" ") or trigger_error($conn->error);;
			}

			//Adding the leader
			$q = $conn->query("INSERT INTO cooperative_committee(cooperative, user, committeePosition, dateVoted, dateDutyEnds) VALUES(\"$cooperative\", \"$user_id\", \"$position\", NOW(), \"$dutyEnds\") ") or trigger_error($conn->error);

			if($q){
				return true;
			}
			else{
				return false;
			}
		}

		public function current_crop_price($crop)
		{
			# returns cooperative crop pricing's current
			global $conn;

			$query = $conn->query("SELECT * FROM crops_pricing WHERE cropId = \"$crop\" AND ISNULL(archivedDate) ORDER BY dateEstablished DESC LIMIT 1 ") or trigger_error($conn->error);

			return $query->fetch_assoc();
		}
		
		public function propose_crop_price($cooperative, $crop, $variety, $grade, $price, $doneBy)
		{
			# Insert the crops pricing
			global $conn;
			$sql = "INSERT INTO crops_pricing(cooperativeId, cropId, cropVariety, cropGrade, amount, dateEstablished, establishedBy) VALUES(\"$cooperative\", \"$crop\", \"$variety\",  \"$grade\", \"$price\", NOW(), \"$doneBy\")";
			$query = $conn->query($sql) or trigger_error($conn->error);
			return $conn->insert_id;
		}

		public function add_harvest($cooperative, $crop, $variety, $grade, $quantity, $ownerId = '', $quantity_remained, $doneBy)
		{
			# Insert the cooperative harvest
			global $conn;
			$sql = "INSERT INTO cooperative_harvest(cooperativeId, cropId, cropVariety, cropGrade, quantity, quantity_remained, userId, declaredate, declaredBy) VALUES(\"$cooperative\", \"$crop\", \"$variety\",  \"$grade\", \"$quantity\", \"$quantity_remained\", \"$ownerId\", NOW(), \"$doneBy\")";
			$query = $conn->query($sql) or trigger_error($conn->error);
			return $conn->insert_id;
		}

		public function get_harvest($coop_id)
		{
			# returns cooperative harvest details
			global $conn;
			// $query = $conn->query("SELECT h.*, c.cropName, cv.variety, cg.grade FROM cooperative_harvest as h JOIN crops as c ON h.cropId = c.cropId JOIN crop_varieties as cv ON cv.cropId = h.cropId JOIN crop_grades as cg ON cg.cropId = h.cropId  WHERE h.cooperativeId = \"$coop_id\" ORDER BY harvestDate DESC") or trigger_error("Can't get harvest $conn->error");

			$sql = "SELECT SUM(h.quantity) as quantity, SUM(h.quantity_remained) as quantity_remained, h.cropId, h.cropVariety, h.cropGrade, C.cropName, CV.variety, CG.grade FROM `cooperative_harvest` as h JOIN crops AS C ON C.cropId = h.cropId JOIN crop_varieties AS CV ON CV.id = h.cropVariety JOIN crop_grades AS CG ON CG.id = h.cropGrade WHERE h.cooperativeId = \"$coop_id\" GROUP BY h.cropId, h.cropVariety, h.cropGrade ORDER BY quantity DESC";
			$query = $conn->query($sql);

			$harvest = array();

			while($data = $query->fetch_assoc()){
				$harvest[] = $data;
			};

			return $harvest;
		}

		public function harvest_status($coop_id)
		{
			# returns cooperative harvest details
			global $conn;
			$query = $conn->query("SELECT * FROM cooperative_harvest as h JOIN crops as c ON h.cropId = c.cropId  WHERE h.cooperativeId = \"$coop_id\" ") or trigger_error("Can't get harvest $conn->error");

			$harvest = array();

			while($data = $query->fetch_assoc()){
				$harvest[] = $data;
			};

			return $harvest;
		}

		public function sell_harvest($cooperative, $crop, $variety, $grade, $quantity, $seller, $doneBy){
			#Sell harvest
			global $conn;
			$sql = "INSERT INTO cooperative_market(cooperativeId, cropId, cropVariety, cropGrade, quantity, wholesalerId, createdBy) VALUES(\"$cooperative\", \"$crop\", \"$variety\",  \"$grade\", \"$quantity\", \"$seller\", \"$doneBy\")";
			$query = $conn->query($sql) or trigger_error($conn->error);
			return $conn->insert_id;
		}
		public function get_market($coop_id)
		{
			# returns cooperative harvest details
			global $conn;
			$query = $conn->query("SELECT h.*, c.cropName, cv.variety, U.name as sellerName FROM cooperative_market as h JOIN crops as c ON h.cropId = c.cropId JOIN crop_varieties as cv ON cv.cropId = h.cropId JOIN users AS U ON U.id = h.wholesalerId WHERE h.cooperativeId = \"$coop_id\" ORDER BY h.createdDate DESC") or trigger_error("Can't get harvest $conn->error");

			$harvest = array();

			while($data = $query->fetch_assoc()){
				$harvest[] = $data;
			};

			return $harvest;
		}

		public function harvest_summary($coop_id, $crop_id = '', $variety = '')
		{
			# returns cooperative harvest summary
			// total, sold and remaining harvest
			global $conn;


			$sql = "SELECT SUM(quantity) as total, COALESCE((SELECT SUM(quantity) FROM cooperative_market AS M WHERE M.cooperativeId = \"$coop_id\"), 0) AS sold FROM cooperative_harvest AS H WHERE H.cooperativeId = \"$coop_id\" AND H.cropId LIKE \"%$crop_id%\" and H.cropVariety LIKE \"%$variety%\" AND H.archived = 'no'";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative HARVEST summary $conn->error");

			$summary = $query->fetch_assoc();
			$total = $summary['total']; //total harvest
			$sold = $summary['sold']; //sold harvest
			$remaining = $total - $summary['sold']; //remaining harvest

			$summary['remaining'] = $remaining;


			///generating percentages
			$summary['soldPercentage'] = $this->percentage($sold, $total);
			$summary['remainingPercentage'] = $this->percentage($remaining, $total);

			return $summary;

			$query = $conn->query("SELECT h.*, c.cropName, cv.variety, U.name as sellerName FROM cooperative_market as h JOIN crops as c ON h.cropId = c.cropId JOIN crop_varieties as cv ON cv.id = h.cropVariety JOIN users AS U ON U.id = h.wholesalerId WHERE h.cooperativeId = \"$coop_id\" ORDER BY h.createdDate DESC") or trigger_error("Can't get harvest $conn->error");

			$harvest = array();

			while($data = $query->fetch_assoc()){
				$harvest[] = $data;
			};

			return $harvest;
		}

		private function percentage($i, $j){
			//returns the percentage
			if($j==0){
				$j=1;
			}
			return ($i/$j)*100;
		}

		public function add_crop($name)
		{
			# add crop in the DB
			global $conn;
			$query = $conn->query("INSERT INTO crops(cropName) VALUES(\"$name\") ") or trigger_error($conn->error);
			return $conn->insert_id;
		}


		public function attach_crop($cooperative, $crop_id, $variety, $addedBy)
		{
			# attach crop to the cooperative
			global $conn;
			$query = $conn->query("INSERT INTO cooperative_crops(cooperativeId, cropId, cropVariety, addedBy) VALUES(\"$cooperative\", \"$crop_id\", \"$variety\", \"$addedBy\") ") or trigger_error($conn->error);
			return $conn->insert_id;
		}
		

		public function add_user($name , $phone , $NID , $gender, $birth_date, $profile_picture = 'images/farmer/default.jpg', $username='', $password='')
		{
			# inserts the user generally
			global $conn;

			//checking if the user exists
			$check = $conn->query("SELECT * FROM users WHERE NID = \"$NID\" ") or trigger_error("Error checking ".$conn->error);


			if($check->num_rows){
				//here user exists already so we can only associate with the cooperative
				$userData = $check->fetch_assoc();

				$userId = $userData['id'];
				return $userId;
			}
			else{
				$sql = "INSERT INTO users(NID, name, gender, birth_date, phone_number, profile_picture, username, password) VALUES(\"$NID\", \"$name\", \"$gender\", \"$birth_date\", \"$phone\", \"$profile_picture\", \"$username\", \"$password\") ";
				$query  = $conn->query($sql);
				$userId = $conn->insert_id;

				if($query){
					return $userId;
				}else{
					return false;
				}
			}
		}

		public function get_fertilizers($coop_id, $season)
		{
			# Fertilizers...
			global $conn;
			$query = $conn->query("SELECT CF.*, SUM(quantity) as quantity, F.name FROM cooperative_fertilizers as CF JOIN fertilizers AS F ON F.id = CF.fertilizerId WHERE CF.cooperativeId = \"$coop_id\" AND CF.season = \"$season\" AND CF.archived = 'no' GROUP BY fertilizerId  ") or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert = array();

			while($data = $query->fetch_assoc()){
				$fert[] = $data;
			};

			return $fert;
		}

		public function get_fertilizer($coop_id, $fertilizer, $season)
		{
			# returns fertilizer info in cooperative
			global $conn;
			$query = $conn->query("SELECT CF.*, SUM(quantity) as quantity, COALESCE((SELECT SUM(quantity) FROM cooperative_fertilizers_assignment WHERE fertilizerId = CF.fertilizerId AND cooperativeId = CF.cooperativeId AND season = \"$season\"), 0) AS assigned, F.name FROM cooperative_fertilizers as CF JOIN fertilizers AS F ON F.id = CF.fertilizerId WHERE CF.cooperativeId = \"$coop_id\" AND CF.fertilizerId = \"$fertilizer\" AND CF.season = \"$season\" AND CF.archived = 'no' GROUP BY fertilizerId  ") or trigger_error("Can't get Fertilizers in cooperative $conn->error");
			$data = $query->fetch_assoc();
			$data['remaining'] = $data['quantity'] - $data['assigned'];

			return $data;
		}

		public function get_fertilizers_summary($coop_id)
		{
			# returns summary for ferts usage in cooperative
			global $conn;
			$sql = "SELECT CF.*, SUM(quantity) as quantity, COALESCE((SELECT SUM(quantity) FROM cooperative_fertilizers_assignment AS FA WHERE FA.cooperativeId = \"$coop_id\"), 0) AS assigned, F.name FROM cooperative_fertilizers as CF JOIN fertilizers AS F ON F.id = CF.fertilizerId WHERE CF.cooperativeId = \"$coop_id\" AND CF.archived = 'no' GROUP BY fertilizerId  ";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert = array();

			while($data = $query->fetch_assoc()){
				$data['remaining'] = $data['quantity'] - $data['assigned'];
				$fert[] = $data;
			};

			return $fert;
		}

		public function declare_fertilizers($coop_id, $fertilizer_id, $quantity, $season, $addedBy)
		{
			# Fertilizers...
			global $conn;
			$query = $conn->query("INSERT INTO cooperative_fertilizers(cooperativeId, fertilizerId, quantity, season, createdBy) VALUES(\"$coop_id\", \"$fertilizer_id\", \"$quantity\", \"$season\", \"$addedBy\") ") or trigger_error("Can't get cooperative Fertilizers $conn->error");

			if($query){
				return $conn->insert_id;
			}else return false;
		}

		public function assign_fertilizer($coop_id, $member, $fertilizer_id, $quantity, $season = '', $addedBy)
		{
			# assign fertilizer...
			global $conn;

			if(!$season){
				$season = current_season();
			}

			//check remaining fertilizer in the cooperative
			$fert_summary  = $this->get_fertilizer($coop_id, $fertilizer_id, $season);
			if($fert_summary){
				$remaining = $fert_summary['remaining'];
				if($quantity <= $remaining ){
					//here we can assign
					$query = $conn->query("INSERT INTO cooperative_fertilizers_assignment(cooperativeId, fertilizerId, userId, quantity, season, createdBy) VALUES(\"$coop_id\", \"$fertilizer_id\", \"$member\", \"$quantity\", \"$season\", \"$addedBy\") ") or trigger_error("Can't assign cooperative Fertilizers $conn->error");
					if($query){
						return $this->formal_response(true, '', array('id'=>$conn->insert_id));
					}else return $this->formal_response(false, "Twagize ikibazo mu gutunganya ikifuzo cyanyu, mwongere mukanya $conn->error");
				}else{
					return $this->formal_response(false, 'Ifumbire ntiyahabwa umunyamuryango kuko irengeje iyo cooperative ifite');
				}
			}else{

			}			
		}

		public function members_with_fertilizer($coop_id, $fertilizer, $season)
		{
			# Check cooeprative members assigned $fertilizer
			global $conn;
			// $sql = "SELECT FA.*, U.name as member_name, F.name as fertilizer_name FROM cooperative_fertilizers as CF JOIN fertilizers AS F ON F.id = CF.fertilizerId JOIN cooperative_fertilizers_assignment as FA ON F.id = FA.fertilizerId JOIN users AS U ON U.id = FA.userId WHERE FA.cooperativeId = \"$coop_id\" AND CF.archived = 'no'";
			$sql = "SELECT CF.*, SUM(CF.quantity) as quantity, U.name as member_name, F.name as fertilizer_name FROM cooperative_fertilizers_assignment AS CF JOIN fertilizers AS F ON CF.fertilizerId = F.id JOIN users AS U ON U.id = CF.userId WHERE CF.cooperativeId = \"$coop_id\" AND CF.fertilizerId = \"$fertilizer\" AND CF.archived = 'no' GROUP BY CF.userId";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert_assignment = array();

			while($data = $query->fetch_assoc()){
				$fert_assignment[] = $data;
			};

			return $fert_assignment;
		}

		public function fertilizers_assigned($coop_id, $season)
		{
			# CHecking fewrtilizer assigned to members...
			global $conn;
			// $sql = "SELECT FA.*, U.name as member_name, F.name as fertilizer_name FROM cooperative_fertilizers as CF JOIN fertilizers AS F ON F.id = CF.fertilizerId JOIN cooperative_fertilizers_assignment as FA ON F.id = FA.fertilizerId JOIN users AS U ON U.id = FA.userId WHERE FA.cooperativeId = \"$coop_id\" AND CF.archived = 'no'";
			$sql = "SELECT CF.*, U.name as member_name, F.name as fertilizer_name FROM cooperative_fertilizers_assignment AS CF JOIN fertilizers AS F ON CF.fertilizerId = F.id JOIN users AS U ON U.id = CF.userId WHERE CF.cooperativeId = \"$coop_id\" AND CF.archived = 'no'";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert_assignment = array();

			while($data = $query->fetch_assoc()){
				$fert_assignment[] = $data;
			};

			return $fert_assignment;
		}

		public function fertilizer_summary($coop_id, $fertilizerId, $season)
		{
			# returns summary for of fertilizer usage in cooperative
			//total declared fert
			//total assigned fert
			//percentage assigned and remaninng
			//total cooperative members - fertilizer eligible
			//total cooperative fertilizer assigned
			global $conn;

			// $query = $conn->query("SELECT * FROM cooperative_fertilizers AS CF  ")

			//getting fertilizers
			$coop_ferts = $this->get_fertilizer($coop_id, $fertilizerId, $season);
			return $coop_ferts;

		}

		public function members_notassigned_fertilizers($coop_id, $season)
		{
			# CHecking fewrtilizer assigned to members...
			global $conn;
			$sql = "SELECT M.*, U.name as member_name FROM cooperative_members as M JOIN users AS U ON U.id = M.userId WHERE M.userId NOT IN (SELECT F.userId FROM cooperative_fertilizers_assignment F WHERE F.cooperativeId = \"$coop_id\" AND F.season = \"$season\" AND F.archived = 'no' ) AND M.cooperativeId = \"$coop_id\" AND M.status = 'active'";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert_assignment = array();

			while($data = $query->fetch_assoc()){
				$fert_assignment[] = $data;
			};

			return $fert_assignment;
		}

		public function members_without_fertilizer($coop_id, $fertilizer, $season)
		{
			# members who dont have $fertilizer
			global $conn;
			$sql = "SELECT M.*, U.name as member_name FROM cooperative_members as M JOIN users AS U ON U.id = M.userId WHERE M.userId NOT IN (SELECT F.userId FROM cooperative_fertilizers_assignment F WHERE F.cooperativeId = \"$coop_id\" AND F.season = \"$season\" AND F.fertilizerId = \"$fertilizer\" AND F.archived = 'no') AND M.cooperativeId = \"$coop_id\" AND M.status = 'active'";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert_assignment = array();

			while($data = $query->fetch_assoc()){
				$fert_assignment[] = $data;
			};

			return $fert_assignment;
		}

		public function get_pesticides($coop_id, $season)
		{
			# Pesticides...
			global $conn;
			$query = $conn->query("SELECT CP.*, SUM(CP.quantity) as quantity, COALESCE((SELECT SUM(quantity) FROM cooperative_pesticides_assignment AS PA WHERE PA.cooperativeId = \"$coop_id\" AND PA.pesticideId = CP.pesticideId ), 0) AS assigned, P.name FROM cooperative_pesticides as CP JOIN pesticides AS P ON P.id = CP.pesticideId WHERE CP.cooperativeId = \"$coop_id\" AND CP.archived = 'no' AND CP.season = \"$season\" GROUP BY pesticideId  ") or trigger_error("Can't get cooperative Pesticides $conn->error");

			$pests = array();

			while($data = $query->fetch_assoc()){
				$data['remaining'] = $data['quantity'] - $data['assigned'];
				$pests[] = $data;
			};

			return $pests;
		}

		public function declare_pesticide($coop_id, $pesticide_id, $quantity, $season,  $addedBy)
		{
			# Fertilizers...
			global $conn;
			$query = $conn->query("INSERT INTO cooperative_pesticides(cooperativeId, pesticideId, quantity, season, createdBy) VALUES(\"$coop_id\", \"$pesticide_id\", \"$quantity\", \"$season\", \"$addedBy\") ") or trigger_error("Can't get cooperative Fertilizers $conn->error");

			if($query){
				return $conn->insert_id;
			}else return false;
		}


		public function assign_pesticide($coop_id, $member, $pesticideId, $quantity, $season = '', $addedBy)
		{
			# assign fertilizer...
			global $conn;

			if(!$season){
				$season = current_season();
			}

			//Limiting people to assign available pesticide in cooperative
			$fert_summary = $this->get_pesticide($coop_id, $pesticideId, $season);
			$remaining = $fert_summary['remaining'];

			if($quantity <= $remaining){
				//here pesticide can be assigned
				$query = $conn->query("INSERT INTO cooperative_pesticides_assignment(cooperativeId, pesticideId, userId, quantity, season, createdBy) VALUES(\"$coop_id\", \"$pesticideId\", \"$member\", \"$quantity\", \"$season\", \"$addedBy\") ") or trigger_error("Can't assign cooperative Fertilizers $conn->error");
				if($query){
					return $this->formal_response(true, '', array('id'=>$conn->insert_id));
				}else return $this->formal_response(false, "Twagize ikibazo mu gutunganya ikifuzo cyanyu, mwongere mukanya $conn->error");

			}else return $this->formal_response(false, 'Umuti ntiwahabwa umunyamuryango kuko urengeje ingano cooperative ifite');
		}

		public function get_pesticide($coop_id, $pesticide, $season)
		{
			# returns info on the pesticide in the cooperative
			global $conn;
			$query = $conn->query("SELECT CP.*, SUM(quantity) as quantity, COALESCE((SELECT SUM(quantity) FROM cooperative_pesticides_assignment WHERE pesticideId = CP.pesticideId AND cooperativeId = CP.cooperativeId AND season = \"$season\"), 0) AS assigned, F.name FROM cooperative_pesticides as CP JOIN pesticides AS F ON F.id = CP.pesticideId WHERE CP.cooperativeId = \"$coop_id\" AND CP.pesticideId = \"$pesticide\" AND CP.season = \"$season\" AND CP.archived = 'no' GROUP BY pesticideId  ") or trigger_error("Can't get pesticides in cooperative $conn->error");
			$data = $query->fetch_assoc();
			$data['remaining'] = $data['quantity'] - $data['assigned'];

			return $data;
		}

		public function pesticides_assigned($coop_id, $season)
		{
			# CHecking fewrtilizer assigned to members...
			global $conn;
			$sql = "SELECT FA.*, U.name as member_name, F.name as fertilizer_name FROM cooperative_pesticides as CF JOIN pesticides AS F ON F.id = CF.pesticideId JOIN cooperative_pesticides_assignment as FA ON F.id = FA.pesticideId JOIN users AS U ON U.id = FA.userId WHERE FA.cooperativeId = \"$coop_id\" AND CF.archived = 'no'";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative pesticides $conn->error");

			$fert_assignment = array();

			while($data = $query->fetch_assoc()){
				$fert_assignment[] = $data;
			};

			return $fert_assignment;
		}

		public function members_with_pesticide($coop_id, $pesticide, $season)
		{
			# Check cooeprative members assigned $fertilizer
			global $conn;
			$sql = "SELECT CP.*, SUM(CP.quantity) as quantity, U.name as member_name, P.name as pesticide_name FROM cooperative_pesticides_assignment AS CP JOIN pesticides AS P ON CP.pesticideId = P.id JOIN users AS U ON U.id = CP.userId WHERE CP.cooperativeId = \"$coop_id\" AND CP.pesticideId = \"$pesticide\" AND CP.archived = 'no' GROUP BY CP.userId";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get member cooperative pesticide $conn->error");

			$pest_assignment = array();

			while($data = $query->fetch_assoc()){
				$pest_assignment[] = $data;
			};

			return $pest_assignment;
		}

		public function members_notassigned_pesticides($coop_id, $season)
		{
			# CHecking fewrtilizer assigned to members...
			global $conn;
			$sql = "SELECT M.*, U.name as member_name FROM cooperative_members as M JOIN users AS U ON U.id = M.userId WHERE M.userId NOT IN (SELECT F.userId FROM cooperative_pesticides_assignment F WHERE F.cooperativeId = \"$coop_id\" AND F.season = \"$season\" AND F.archived = 'no' ) AND M.cooperativeId = \"$coop_id\" AND M.status = 'active'";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get cooperative Fertilizers $conn->error");

			$fert_assignment = array();

			while($data = $query->fetch_assoc()){
				$fert_assignment[] = $data;
			};

			return $fert_assignment;
		}

		public function members_without_pesticide($coop_id, $pesticide, $season)
		{
			# Check cooeprative members without $fertilizer
			global $conn;
			$sql = "SELECT M.*, U.name as member_name FROM cooperative_members as M JOIN users AS U ON U.id = M.userId WHERE M.userId NOT IN (SELECT F.userId FROM cooperative_pesticides_assignment P WHERE P.cooperativeId = \"$coop_id\" AND P.season = \"$season\" AND P.pesticideId = \"$pesticide\" AND P.archived = 'no') AND M.cooperativeId = \"$coop_id\" AND M.status = 'active'";
			// echo "$sql";
			$query = $conn->query($sql) or trigger_error("Can't get member cooperative pesticide $conn->error");

			$pest_assignment = array();

			while($data = $query->fetch_assoc()){
				$pest_assignment[] = $data;
			};

			return $pest_assignment;
		}

		public function get_land($coop_id)
		{
			# Land...
			global $conn;
			$query = $conn->query("SELECT CL.*, SUM(CL.size) as size, COALESCE((SELECT name FROM users WHERE id = CL.owner), 'Koperative') AS ownerName FROM cooperative_land AS CL WHERE CL.cooperativeId = \"$coop_id\" AND CL.archived = 'no' GROUP BY CL.owner") or trigger_error("Can't get cooperative land $conn->error");

			$coop_land = array();

			while($data = $query->fetch_assoc()){
				$coop_land[] = $data;
			};

			return $coop_land;
		}
		public function add_land($coop_id, $name, $size, $owner, $ownerId, $addedBy)
		{
			# Cooperative land addition...
			global $conn;
			$query = $conn->query("INSERT INTO cooperative_land(cooperativeId, name, size, owner, createdBy) VALUES(\"$coop_id\", \"$name\", \"$size\", \"$ownerId\", \"$addedBy\") ") or trigger_error("Can't get cooperative Fertilizers $conn->error");

			if($query){
				return $conn->insert_id;
			}else return false;
		}

		public function get_institution_communications($coop_id){
			global $conn;

			$query = $conn->query("SELECT * FROM cooperative_communication WHERE cooperativeId = \"$coop_id\" AND archived = 'no' ORDER BY createdDate DESC ") or trigger_error("Can't get cooperative communications $conn->error");

			$data = array();
			while ($t = $query->fetch_assoc()) {
				$data[] = $t;
			}
			return $data;
		}

		public function get_messages_by_subject($coop_id)
		{
			global $conn;
			$query = $conn->query("SELECT * FROM cooperative_communication WHERE cooperativeId LIKE \"%$coop_id%\" GROUP BY threadId ORDER BY createdDate DESC") or trigger_error($conn->error);
			$messages = array();

			while ($data = $query->fetch_assoc()) {
				$threadId = $data['threadId'];

				//getting message details
				$messages[$threadId] = $data;
				$messages[$threadId]['initial_message'] = $this->get_message($threadId);
				$messages[$threadId]['threadMessages'] = $this->get_thread($threadId);
			}
			return $messages;
		}

		public function get_recent_received_messages($cooperative_id, $count = 3){
			//returns recent messages sent to cooperative
			$messages = $this->get_messages_by_subject($cooperative_id);

			$ret = array();
			$n = 0;
			foreach ($messages as $key => $value) {
				if($n<$count)
					$ret[$key] = $value;
				else
					break;
			}
			return $ret;

		}

		public function get_message($message_id){
			global $conn;

			$query = $conn->query("SELECT * FROM messages WHERE id = \"$message_id\" AND archived = 'no' ") or trigger_error("Can't get cooperative message $conn->error");
			return $query->fetch_assoc();
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

		private function formal_response($status, $msg = '', $data = array()){
			//formats the response where context is needed
			$ret = (object)array('status'=>$status, 'msg'=>$msg, 'data'=>$data);
			return $ret;
		}
	}

	$Cooperative = new cooperative();
?>