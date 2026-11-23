<?php
include_once '/config.php';

$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid product ID.");
}

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$product) {
    die("Product not found.");
}

if (isset($_POST['update_product'])) {

    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);

    if ($name !== "" && $price > 0 && $category_id > 0) {

        $sql = "UPDATE products
                SET name = ?, price = ?, category_id = ?, description = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sdisi",
            $name,
            $price,
            $category_id,
            $description,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: products.php?message=updated");
            exit;
        } else {
            $message = "Error updating product.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $message = "Please fill in all required fields correctly.";
    }
}

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name ASC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product - SneakerShop</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .container {
            width: 450px;
            max-width: 85%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #346beb;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 18px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #346beb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #2454c5;
        }

        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="container">

    <h2>Edit Sneaker</h2>

    <p class="message">
        <?php echo htmlspecialchars($message); ?>
    </p>

    <form method="POST">

        <label>Product Name</label>

        <input type="text" name="name"
               value="<?php echo htmlspecialchars($product['name']); ?>"
               required>

        <label>Price (€)</label>

        <input type="number" name="price"
               value="<?php echo htmlspecialchars($product['price']); ?>"
               min="0.01" step="0.01" required>

        <label>Category</label>

        <select name="category_id" required>

            <option value="">Select Category</option>

            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>

                <option value="<?php echo $category['id']; ?>"
                    <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>

                    <?php echo htmlspecialchars($category['category_name']); ?>

                </option>

            <?php } ?>

        </select>

        <label>Description</label>

        <textarea name="description"><?php
            echo htmlspecialchars($product['description']);
        ?></textarea>

        <button type="submit" name="update_product">
            Update Product
        </button>

    </form>

</div>

<?php '/footer.php'; ?>

</body>
</html>