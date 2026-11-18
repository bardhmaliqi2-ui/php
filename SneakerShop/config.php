<?php
$host="localhost";
$user="root";
$db="SneakerShop";
$pass="";

try{
    $conn=new PDO("mysql:host=$host;dbname=$db",$user,$pass);
}catch(Exception $e){
    echo "Error";
}
?>