<?php
$host="localhost";
$user="root";
$db="db";
$pass="";

try{
    $conn=new PDO("mysql:host=$host;dbname=$db",$user,$pass);
}catch(Exception $e){
    echo "error";
}
?>