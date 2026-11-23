<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerShop</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        header {
            background: #111;
            color: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .logo span {
            color: #ff4d4d;
        }

        nav {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s;
        }

        nav a:hover {
            color: #ff4d4d;
        }

        .logout {
            background: #ff4d4d;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .logout:hover {
            background: #e60000;
            color: white;
        }

        @media (max-width: 600px) {
            header {
                flex-direction: column;
            }

            nav {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="index.php" class="logo">
        Sneaker<span>Shop</span>
    </a>

    <nav>
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="products.php">Products</a>
        <a href="categories.php">Categories</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>

</body>
</html>