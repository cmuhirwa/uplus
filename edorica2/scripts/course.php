<?php
	class course{
		function list(){
			//Lists all courses
			global $conn;
			$query = mysqli_query($conn, "SELECT name FROM courses_def ") or die("Can't get courses: ".mysqli_error($conn));
			$t = $courses = array();

			while ($data = mysqli_fetch_assoc($query)) {
				$course = $data['name'];
				$courses[$course] =  ucfirst(str_ireplace("_", " ", $data['name']));
			}
			return $courses;
		}
	}
?>