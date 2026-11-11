<?php 
	/* 
	 We will include config.php for connection with database.
	 We will get the booking id and update the database based on that booking id
	*/
	include_once('config.php');

	$id = $_GET['id'];
	$sql = "UPDATE `bookings` SET `is_approved` = 'true' WHERE id=:id";
	$prep = $conn->prepare($sql);
	$prep->bindParam(':id',$id);
	$prep->execute();

	header("Location: list_movies.php");
 ?>
 


