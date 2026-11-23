<?php
include_once "config.php";
include_once "functions.php";
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerShop - Online Shoe Store</title>
    <style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 8%;
        background: #fff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .logo {
        font-size: 28px;
        font-weight: 900;
        letter-spacing: 2px;
        color: #111;
        text-decoration: none;
        text-transform: uppercase;
    }

    .logo span {
        color: #e63946;
    }

    .navbar-links {
        display: flex;
        align-items: center;
        gap: 35px;
    }

    .navbar a {
        text-decoration: none;
        color: #111;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: 0.3s ease;
        position: relative;
    }

    .navbar-links a::after {
        content: "";
        position: absolute;
        width: 0;
        height: 2px;
        background: #e63946;
        bottom: -7px;
        left: 0;
        transition: width 0.3s ease;
    }

    .navbar-links a:hover {
        color: #e63946;
    }

    .navbar-links a:hover::after {
        width: 100%;
    }

    .navbar-icons {
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .navbar-icons a {
        font-size: 18px;
    }

    .navbar-icons a:hover {
        color: #e63946;
    }

    @media (max-width: 768px) {
        .navbar {
            padding: 18px 5%;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo {
            font-size: 23px;
        }

        .navbar-links {
            width: 100%;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .navbar-links a {
            font-size: 12px;
        }
    }

        .hero {
            background: #f2f2f2;
            min-height: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 50px 20px;
        }

        .hero h1 {
            font-size: 55px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            background: #111;
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
        }

        .btn:hover {
            background: #e63946;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">SNEAKERSHOP</div>

        <div>
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <section class="hero">
        <div>
            <h1>STEP INTO STYLE.</h1>
            <p>Discover the latest sneakers for your lifestyle.</p>
            <a href="shop.php" class="btn">SHOP NOW</a>
        </div>
    </section>

</body>
</html>
