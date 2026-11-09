<?php
include_once('config.php');

if (isset($_POST['submit'])) {

    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if ($name == '' || $description == '' || $quantity == '') {
        die("Please fill in all fields!");
    }

    $sql = "INSERT INTO products (name, description, quantity)
            VALUES (:name, :description, :quantity)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':quantity' => $quantity
    ]);

    header("Location: productDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            margin: 0;
        }

        .header {
            background: #30343a;
            color: white;
            padding: 20px;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #087df0;
            color: white;
            border: none;
            cursor: pointer;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #087df0;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>My Dashboard</h2>
</div>

<div class="container">

    <h2>Add New Product</h2>

<form method="POST" action="">

    <input type="text" name="name"
           placeholder="Product Name" required>

    <textarea name="description"
              placeholder="Description" required></textarea>

    <input type="number" name="quantity"
           placeholder="Quantity" required>

    <button type="submit" name="submit">
        Create Product
    </button>

</form>
    <a href="productDashboard.php">
        Back to Products Dashboard
    </a>

</div>

</body>
</html>
