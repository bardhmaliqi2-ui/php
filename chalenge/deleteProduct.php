<?php
include_once('config.php');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);
}

header("Location: productDashboard.php");
exit();
?>
