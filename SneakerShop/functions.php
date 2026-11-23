<?php
include_once 'config.php';

function addCategory($category_name) {
    global $conn;

    $sql = "INSERT INTO categories (category_name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $category_name);

    return mysqli_stmt_execute($stmt);
}
function getCategories() {
    global $conn;

    $sql = "SELECT * FROM categories ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    return $result;
}
function deleteCategory($id) {
    global $conn;

    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
}
function addProduct($name, $price, $category_id, $description) {
    global $conn;

    $sql = "INSERT INTO products
            (name, price, category_id, description)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sdis",
        $name,
        $price,
        $category_id,
        $description
    );

    return mysqli_stmt_execute($stmt);
}
function getProducts() {
    global $conn;

    $sql = "SELECT * FROM products ORDER BY id DESC";

    return mysqli_query($conn, $sql);
}
function deleteProduct($id) {
    global $conn;

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
}
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>