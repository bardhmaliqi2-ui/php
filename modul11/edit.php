<?php
include_once("config.php");

$id=$_GET['id'];
$sql="SELECT * from users1 WHERE id=:id";
$prep=$conn->prepare($sql);
$prep->bindParam(':id',$id);
$prep->execute();
$data=$prep->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?php echo $data['id']?>"><br>
        <input type="text" name="username" value="<?php echo $data['username']?>"><br>
        <input type="text" name="name" value="<?php echo $data['name']?>"><br>
        <input type="text" name="surname" value="<?php echo $data['surname']?>"><br>
        <input type="text" name="password" value="<?php echo $data['password']?>"><br>
        <input type="text" name="email" value="<?php echo $data['email']?>"><br>
        
        <br>
        <input type="submit" name="submit" value="Update">
    </form>
    <a href="index.php">Back to List</a>
</body>
</html>