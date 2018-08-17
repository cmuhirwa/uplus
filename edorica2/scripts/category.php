<?php


class category{
	public static function db2pname($pname){
		echo $pname;
		global $conn;
		$query = mysqli_query($conn, "SELECT name FROM category_def WHERE pname = \"$pname\" LIMIT 1") or die(mysqli_errors($conn));
		$data = mysqli_fetch_assoc($query);

		if(!empty($data['pname'])){
			return $data['pname'];
		}else return false;
		
	}
	function list(){
		//Listing categories
		global $conn;
		$query = mysqli_query($conn, "SELECT name, pname FROM category_def") or die("Cant get categories ".myqli_error($conn));
		$cats = array();

		while ($data = mysqli_fetch_assoc($query)) {
			$cats[$data['name']] = $data['pname'];
		}

		return $cats;
	}
	public static function p2dbname($dbname){
		global $conn;

		$query = mysqli_query($conn, "SELECT name FROM category_def WHERE pname = \"$dbname\" LIMIT 1") or die(mysqli_errors($conn));
		$data = mysqli_fetch_assoc($query);

		if(empty($data['name'])){
			return false;
		}
		else{
			$dbname = $data['name'];
			return $dbname;
		}
	}
	public static function catLetter($catname){
		//This function get just the letter that preffix the classes in the categpry as S1, P5, Y4
		global $conn;

		$catname = str_ireplace(" ", "_", $catname);

		$query = mysqli_query($conn, "SELECT class_letter as letter FROM category_def WHERE name = '$catname' LIMIT 1") or die(mysqli_error($conn));
		$data = mysqli_fetch_assoc($query);
		return $data['letter'];
	}
}

?>
