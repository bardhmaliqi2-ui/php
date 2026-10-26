<?php
include_once("config.php");

if(isset($_POST['update'])){
    $id=$_POST['id'];
    $username=$_POST['username'];
    $name=$_POST['name'];
    $email=$_POST['email'];

    $sql="UPDATE user SET username=:username,name=:name,email=:email WHERE id=:id";

    $prep=$conn->prepare($sql);
    $prep->bindParam(':id',$id);
    $prep->bindParam(':username',$username);
    $prep->bindParam(':name',$name);
    $prep->bindParam(':email',$email);
    $prep->execute();

    header("Location:index.php");
} 
?>