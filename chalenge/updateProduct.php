
<?php
include_once('config.php');

if (!isset($_GET['id'])) {
    header("Location: productDashboard.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found!");
}

if (isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = $_POST['quantity'];

    if ($name !== '' && $description !== '' &&
        $quantity !== '' && ctype_digit($quantity)) {

        $sql = "UPDATE products
                SET name = :name,
                    description = :description,
                    quantity = :quantity
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':quantity' => $quantity,
            ':id' => $id
        ]);

        header("Location: productDashboard.php");
        exit();

    } else {
        $error = "Please fill in all fields correctly!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>

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
            max-width: 90%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ddd;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #087df0;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background: #0565c4;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #087df0;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>My Dashboard</h2>
</div>

<div class="container">

    <h2>Update Product</h2>

    <?php if (isset($error)) { ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <form method="POST">

        <label>Product Name:</label>

        <input type="text" name="name"
               value="<?php echo htmlspecialchars($_POST['name'] ?? $product['name']); ?>"
               required>

        <label>Description:</label>

        <textarea name="description" required><?php
            echo htmlspecialchars($_POST['description'] ?? $product['description']);
        ?></textarea>

        <label>Quantity:</label>

        <input type="number" name="quantity"
               min="0"
               step="1"
               value="<?php echo htmlspecialchars($_POST['quantity'] ?? $product['quantity']); ?>"
               required>

        <button type="submit" name="submit">
            Update Product
        </button>

    </form>

    <a href="productDashboard.php">
        Back to Products Dashboard
    </a>

</div>

</body>
</html>
