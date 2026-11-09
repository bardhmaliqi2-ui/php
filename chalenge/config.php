<?php
$user="root";
$pass="";
$server="localhost";
$dbname="db";

try{
    $conn =new PDO("mysql:host=$server;dbname=$dbname",$user,$pass);
    echo "Connected successfully";
}catch(PDOException $e) {
    echo "error: " . $e->getMessage();
}
?>