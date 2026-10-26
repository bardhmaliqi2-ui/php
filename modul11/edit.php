<?php
include_once("config.php");

$id=$_GET['id'];
$sql="SELECT * from user WHERE id=:id";
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
        <input type="text">
        <input type="text">
        <input type="text">
        <input type="text">
    </form>
</body>
</html>