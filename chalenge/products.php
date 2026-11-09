<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge - Products Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f5f5;
        }

        .header {
            background: #30343a;
            color: white;
            padding: 20px;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 200px;
            min-height: 80vh;
            background: #eee;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #087df0;
        }

        .content {
            flex: 1;
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        a {
            color: #087df0;
            text-decoration: none;
        }

        .delete {
            color: red;
        }
    </style>
</head>

<body>

    <?php
    include_once('config.php');

    $sql = "SELECT * FROM products";
    $getUsers = $conn->prepare($sql);
    $getUsers->execute();
    $users = $getUsers->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="header">
        <h3>Hello,</h3>
    </div>

    <div class="container">

        <div class="sidebar">
            <a href="dashboard.php">Dashboard</a>
            <a href="productDashboard.php">Product Dashboard</a>
            <a href="editProfile.php">Edit Profile</a>
        </div>

        <div class="content">

            <h1>Products</h1>

            <a href="addProduct.php">Add Product</a>

            <br><br>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Update</th>
                </tr>

                <?php foreach($users as $user) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>

                        <td><?php echo htmlspecialchars($user['title']); ?></td>

                        <td><?php echo htmlspecialchars($user['description']); ?></td>

                        <td><?php echo htmlspecialchars($user['quantity']); ?></td>

                        <td><?php echo htmlspecialchars($user['price']); ?>$</td>

                        <td>
                            <a class="delete"
                               href="deleteProduct.php?id=<?php echo $user['id']; ?>"
                               onclick="return confirm('Are you sure?')">
                                Delete
                            </a>

                            |

                            <a href="updateProduct.php?id=<?php echo $user['id']; ?>">
                                Update
                            </a>
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</body>
</html>
