<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require_once('../db.php');
    
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
       $email = $_POST["email"];
        $password = $_POST["password"];
        if(isset($_POST['submit'])){ 	
          $sql= "SELECT ID, first_name, last_name FROM users WHERE password='".MD5($password)."' and email='".$email."'";

        	$result=mysqli_query($db,$sql);


           $row =mysqli_fetch_assoc($result);

       if($row){

          	session_start();
       	    $_SESSION["authenticated"] = 'true';
       		

			$first_name= $row['first_name']; 
			$last_name= $row['last_name'];

			$_SESSION["first_name"] =$first_name; 
			$_SESSION["last_name"] =$last_name ; 
			$_SESSION["ID"] =$row['ID'] ; 

			header("location: ../index.php"); 

       }else{

       	header('Location: ../login.php');
        
       }

       
		}else{
			 	header('Location: ../login.php');
			 	//SUBMIT MAN
		}



		}else{
			echo 'hey no data!';
		}   
   
   }
?>