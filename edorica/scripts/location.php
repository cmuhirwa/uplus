<?php
//Location class will carry out tasks related with schools and their locations
class location{
	public $conn;
	function __construct(){
		global $conn;
		$this->conn = $conn;
	}
	public static function dprovince($district){
		//Return district's province
		global $conn;
		$query = mysqli_query($conn, "SELECT prov.name FROM dor JOIN por as prov ON dor.province = prov.name AND dor.name = \"$district\"") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);

		if(!empty($data) && !empty($data['name'])){
			return $data['name'];
		}
		else return false;
	}
	public static function provinces(){
		global $conn;
		//Function to give out all provinces
		$query = mysqli_query($conn, "SELECT * FROM por") or die(mysqli_error($conn));

		$provinces = array(); //Array to store all provincxes
		while ($data = mysqli_fetch_assoc($query)) {
			# code...
			$provinces = array_merge($provinces, array($data['name']));
		}
		return $provinces;
	}
	public static function units(){
		//This return location units we are using and of which schools are presented into
		return array("province", 'district', 'sector');
	}
	public static function districtLink($district, $absolute=1){
		global $conn, $slocation;
		$district = strtolower(str_ireplace(" ", '-', $district)); //Lowering chars for link and removing spaces

		//Going to get district's province to use it as part of link
		$prov = location::dprovince($district);

		if(!empty($prov)){
			$prov = strtolower($prov);
			$provLink = location::provLink($prov);
		}else $provLink = '/'._NOT_FOUND;


		$dislink = ($absolute==1)? "$provLink/$district" : "$prov/$district";
		return $dislink;
	}
	public static function provLink($province){
		//Function to generate the link of province
		global $conn, $slocation;
		$prov = strtolower(str_ireplace(" ", '-', $province)); //Lowering chars for link and removing spaces
		$provlink = $slocation."/$prov";
		return $provlink;
	}
	public static function districts($province){
		global $conn;
		//Here we count number of districts in province
		$query = mysqli_query($conn, "SELECT * FROM dor WHERE province=\"$province\"") or die(mysqli_error($conn));

		$var = array();
		$n=0;
		while ($row = mysqli_fetch_assoc($query)) {			
			$var[$n] = $row;
			$n++;
		}
		return $var;
	}

	public static function count_districts($province){
		global $conn;
		//Here we count number of districts in province
		$query = mysqli_query($conn, "SELECT COUNT(*) as sum FROM dor WHERE province=\"$province\"") or die(mysqli_error($conn));
		$sum = mysqli_fetch_assoc($query);
		if(!empty($sum)) return $sum['sum'];
		else return false;
	}
}


?>