<?php
include_once("config.php");
$id=$_GET['id'];

$sql="DELETE from user WHERE id=:id";
$getusers=$conn->prepare($sql);
$getusers->bindParam('id',$id);
$getusers->execute();
header('Location:index.php');
?>