<?php 

class Database{
	//properties
	private $host="localhost";
	private $user="root";
	private $password="";
	private $dbname="test";

	protected function connect(){
		$con=new mysqli($this->host,$this->user,$this->password,$this->dbname);
		return $con;
	}
}
?>