<?php
include_once '/functions.php';

$message = "";

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    if (deleteCategory($id)) {
        header("Location: categories.php?deleted=1");
        exit;
    } else {
        $message = "Error deleting category!";
    }
}

if (isset($_GET['deleted'])) {
    $message = "Category deleted successfully!";
}

$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categories - SneakerShop</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .container {
            width: 85%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            color: #346beb;
            text-align: center;
        }

        .add-btn {
            display: inline-block;
            background: #346beb;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #222;
            color: white;
        }

        .delete-btn {
            background: #e63946;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .message {
            color: green;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<?php include '/header.php'; ?>

<div class="container">

    <h2>Product Categories</h2>

    <a href="addcategory.php" class="add-btn">
        + Add New Category
    </a>

    <p class="message">
        <?php echo htmlspecialchars($message); ?>
    </p>

    <table>

        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>

        <?php if (mysqli_num_rows($categories) > 0) { ?>

            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>

                <tr>
                    <td>
                        <?php echo $category['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </td>

                    <td>
                        <a class="delete-btn"
                           href="categories.php?delete=<?php echo $category['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this category?')">
                            Delete
                        </a>
                    </td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="3">No categories found.</td>
            </tr>

        <?php } ?>

    </table>

</div>

<?php include '/footer.php'; ?>

</body>
</html>