<?php
WEB::getInstance('School');
	class club extends School{
	   public function getClubs($scid){
	   	global $conn;

   		//Get schools clubs
   		if(!$this->is_school($scid)){
   			die("Module should be loaded on school only! ");
   		}
   		$query = mysqli_query($conn, "SELECT * FROM school_clubs JOIN clubs ON school_clubs.club = clubs.id WHERE school = '$scid' ") or die(mysqli_error($conn));
   		$clubs = array();

   		while($data = mysqli_fetch_assoc($query)){
   			$clubs[] = $data;
   		}
   		return $clubs;
	   } 
      public function allClubs(){
         global $conn;
         $query = mysqli_query($conn, "SELECT * FROM clubs LIMIT 150");
         $clubs = array();

         while($data = mysqli_fetch_assoc($query)){
            $clubs[] = $data;
         }
         return $clubs;
      }
      public function clubschools($clubID){
         //Finding the schools in which club operates
         global $conn;
         $clubID = mysqli_real_escape_string($conn, $clubID);
         $sql =  "SELECT school FROM school_clubs JOIN clubs ON clubs.id = school_clubs.club WHERE clubs.id = '$clubID'";
         $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

         $schools = array();

         while($data = mysqli_fetch_assoc($query)){
            $schools[] = $data['school'];
         }
         return $schools;

      }
	}
?>