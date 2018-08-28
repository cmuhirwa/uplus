<?php
class Authentication{

public function authenticate(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require_once('db.php');
    
    if(!empty($_POST["username"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    
        $query = $connection->prepare("SELECT `ID` FROM `users` WHERE `email` = ? and `password` = PASSWORD(?)");
        $query->bind_param("ss", $username, $password);
        $query->execute();
        $query->bind_result($userid);
        $query->fetch();
        $query->close();
        
        if(!empty($userid)) {
            session_start();
            $_SESSION["authenticated"] = 'true';
            header('Location: index.php');
        }
        else {
            header('Location: login.php');
        }
        
    } else {
        header('Location: login.php');
    }
}

}

public function checkAuthentication(){
        session_start();
		 if(empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
		    header('Location: login.php');
		}

     }

     public function logout(){

        session_start();
        session_unset();
        session_destroy();

        header("location: ../login.php");
        exit();


     }


}
