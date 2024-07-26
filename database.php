<?php
$hostName = "127.0.0.1";  
$dbUser = "root";        
$dbPassword = "";
$dbName = "tpo_management"; 

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
   
    die("Connection failed: " . mysqli_connect_error());
}
?>
