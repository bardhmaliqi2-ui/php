
<?php
include_once('config.php');

$sql = "SELECT * FROM products";
$getUsers = $conn->prepare($sql);
$getUsers->execute();
$users = $getUsers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f6fa;
        }

        .header {
            background: #30343a;
            color: white;
            padding: 20px 30px;
        }

        .header h2 {
            margin: 0;
        }

        .container {
            display: flex;
            min-height: 90vh;
        }

        .sidebar {
            width: 230px;
            background: #fff;
            padding: 25px 15px;
        }

        .sidebar h3 {
            padding-left: 12px;
            color: #30343a;
        }

        .sidebar a {
            display: block;
            padding: 14px;
            margin: 5px 0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background: #087df0;
            color: white;
        }

        .content {
            flex: 1;
            padding: 30px;
            overflow-x: auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .top h1 {
            color: #30343a;
        }

        .add-btn {
            background: #087df0;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-btn:hover {
            background: #0565c4;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #30343a;
            color: white;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .delete {
            color: red;
            text-decoration: none;
        }

        .update {
            color: #087df0;
            text-decoration: none;
        }

        .actions a {
            margin-right: 8px;
        }

        @media(max-width: 700px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="header">
    <h2>My Dashboard</h2>
</div>

<div class="container">

    <div class="sidebar">
        <h3>Menu</h3>

        <a href="dashboard.php">Dashboard</a>

        <a href="productDashboard.php" class="active">
            Product Dashboard
        </a>

        <a href="editProfile.php">Edit Profile</a>
    </div>

    <div class="content">

        <div class="top">
            <h1>Products Dashboard</h1>

            <a href="addProduct.php" class="add-btn">
                + Add Product
            </a>
        </div>

        <div class="table-box">

            <h3>All Products</h3>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(count($users) > 0) { ?>

                    <?php foreach($users as $user) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($user['id']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($user['name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($user['description']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($user['quantity']); ?>
                            </td>

                            <td class="actions">

                                <a class="delete"
                                   href="deleteProduct.php?id=<?php echo urlencode($user['id']); ?>"
                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                    Delete
                                </a>

                                |

                                <a class="update"
                                   href="updateProduct.php?id=<?php echo urlencode($user['id']); ?>">
                                    Update
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="5">No products found!</td>
                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>