<?php
include_once '/config.php';

$product_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$total_products = mysqli_fetch_assoc($product_query)['total'];

$category_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories");
$total_categories = mysqli_fetch_assoc($category_query)['total'];

$order_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$total_orders = mysqli_fetch_assoc($order_query)['total'];

$message_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages");
$total_messages = mysqli_fetch_assoc($message_query)['total'];

$sales_query = mysqli_query($conn, "SELECT SUM(total) AS sales FROM orders");
$total_sales = mysqli_fetch_assoc($sales_query)['sales'] ?? 0;

$recent_orders = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id DESC LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SneakerShop</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .dashboard {
            padding: 30px 5%;
        }

        h1 {
            color: #222;
        }

        .subtitle {
            color: #777;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .stat-card h3 {
            color: #777;
            margin-top: 0;
        }

        .stat-card h2 {
            color: #346beb;
            font-size: 30px;
            margin-bottom: 0;
        }

        .links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 35px 0;
        }

        .links a {
            background: #346beb;
            color: white;
            padding: 13px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .links a:hover {
            background: #2454c5;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            overflow-x: auto;
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

        .sales {
            color: #16a34a !important;
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="dashboard">

    <h1>Admin Dashboard</h1>

    <p class="subtitle">
        Welcome to your SneakerShop administration panel.
    </p>

    <div class="stats">

        <div class="stat-card">
            <h3>Total Products</h3>
            <h2><?php echo $total_products; ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Categories</h3>
            <h2><?php echo $total_categories; ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Orders</h3>
            <h2><?php echo $total_orders; ?></h2>
        </div>

        <div class="stat-card">
            <h3>Customer Messages</h3>
            <h2><?php echo $total_messages; ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Sales</h3>
            <h2 class="sales">
                €<?php echo number_format((float)$total_sales, 2); ?>
            </h2>
        </div>

    </div>

    <div class="links">

        <a href="addproducts.php">+ Add Product</a>

        <a href="addcategory.php">+ Add Category</a>

        <a href="products.php">View Products</a>

        <a href="categories.php">View Categories</a>

        <a href="shop.php">Visit Shop</a>

        <a href="contact.php">Contact Page</a>

    </div>

    <div class="table-container">

        <h2>Recent Orders</h2>

        <table>

            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Date</th>
            </tr>

            <?php if (mysqli_num_rows($recent_orders) > 0) { ?>

                <?php while ($order = mysqli_fetch_assoc($recent_orders)) { ?>

                    <tr>
                        <td>#<?php echo $order['id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($order['customer_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($order['email']); ?>
                        </td>

                        <td>
                            €<?php echo number_format((float)$order['total'], 2); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($order['created_at']); ?>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="5">No orders yet.</td>
                </tr>

            <?php } ?>

        </table>

    </div>

</div>

<?php include '/footer.php'; ?>

</body>
</html>