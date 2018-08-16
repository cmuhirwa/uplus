<?php
$dbname="site";
$name=$_POST['name'];
$email=$_POST['email'];
$subject=$_POST['subject'];
$message=$_POST['message'];
require('conn.php');
mysqli_select_db($conn, $dbname);
$sql="INSERT messages(name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
$tmp=mysqli_query($conn, $sql);
header('location:localhost/edorica/contact.php');
?>