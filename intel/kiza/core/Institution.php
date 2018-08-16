<?php 
include_once 'Query.php';
class Minagri extends Query{
	private $result=array();
	private $rowCount=0;
	//function to return all cooperative
	public function getAllCooperatives(){
		$query="SELECT * FROM cooperatives ORDER BY cooperativeId DESC LIMIT 20";
		$result=$this->select($query);
		return $result;
	}
	//cooperative actions
	public function getCooperative($option,$cooperative,$status,$location){
		$allowed=array(1,2,3);
		$query="";
		if(in_array($option,$allowed)){
			if($option==1){
				//all cooperative information based on id
				$query="SELECT * FROM cooperatives
						WHERE cooperativeId=\"$cooperative\"
						LIMIT 1";

			}elseif($option==2){
				//get cooperatives according to location passed
				$query="SELECT * FROM cooperatives
						WHERE location=\"$location\"";

			}elseif($option==3){
				//get cooperative according to status
				$query="SELECT * FROM cooperatives
						WHERE status=\"$status\"";
			}
			$result=$this->select($query);
		}else{
			$result[0]="error";
		}

		return $result;
	}

	//function to count records
	public function recordCounter($table){
		$allowed=array("cooperatives","crops","crop_grades","cooperative_harvest");//add all tables here
		if(in_array($table,$allowed)){
			$query="SELECT DISTINCT * FROM ".$table;
			$rowCount=$this->rows($query);
		}else{
			$rowCount=0;
		}

		return $rowCount;
	}
}
?>