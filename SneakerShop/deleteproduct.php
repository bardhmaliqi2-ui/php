<?php
session_start();

require_once __DIR__ . '/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: products.php");
    exit;
}

try {
    $stmt = $conn->prepare(
        "SELECT image FROM products WHERE id = ?"
    );

    $stmt->execute([$id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: products.php");
        exit;
    }
    $deleteStmt = $conn->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $deleteStmt->execute([$id]);

    if (!empty($product['image'])) {

        $imagePath = __DIR__ . '/' . $product['image'];

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    header("Location: products.php?deleted=1");
    exit;

} catch (PDOException $e) {

    die("Database error: " . $e->getMessage());
}
?>