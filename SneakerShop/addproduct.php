<?php
include_once '/functions.php';

$message = "";

if (isset($_POST['add_product'])) {

    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);

    if ($name !== "" && $price > 0 && $category_id > 0) {

        if (addProduct($name, $price, $category_id, $description)) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product!";
        }

    } else {
        $message = "Please fill in all required fields correctly.";
    }
}

$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product - SneakerShop</title>

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
            resize: vertical;
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
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<?php include '/header.php'; ?>

<div class="container">

    <h2>Add New Sneaker</h2>

    <form method="POST">

        <label>Product Name</label>
        <input type="text" name="name"
               placeholder="Enter sneaker name" required>

        <label>Price (€)</label>
        <input type="number" name="price"
               placeholder="Enter price"
               min="0.01" step="0.01" required>

        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php } ?>

        </select>

        <label>Description</label>
        <textarea name="description"
                  placeholder="Enter product description"></textarea>

        <button type="submit" name="add_product">
            Add Product
        </button>

    </form>

    <div class="message">
        <?php echo htmlspecialchars($message); ?>
    </div>

</div>

<?php include '/footer.php'; ?>

</body>
</html>