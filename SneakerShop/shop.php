<?php
include_once "config.php";
include_once "functions.php";

$search = $_GET['search'] ?? '';

$category = $_GET['category'] ?? '';

$maxPrice = $_GET['max_price'] ?? '';

$categoryQuery = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $categoryQuery->fetchAll();

$sql = "SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories
        ON products.category_id = categories.id
        WHERE products.status = 'active'";

$params = [];
if (!empty($search)) {
    $sql .= " AND (products.name LIKE :search OR products.brand LIKE :search)";
    $params['search'] = "%$search%";
}
if (!empty($category)) {
    $sql .= " AND products.category_id = :category";
    $params['category'] = $category;
}
if (!empty($maxPrice) && is_numeric($maxPrice)) {
    $sql .= " AND COALESCE(products.sale_price, products.price) <= :max_price";
    $params['max_price'] = $maxPrice;
}

$sql .= " ORDER BY products.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shop - SneakerShop</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #111;
        }

        .navbar {
            background: #fff;
            padding: 22px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .logo {
            font-size: 27px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .navbar a {
            text-decoration: none;
            color: #111;
            margin-left: 25px;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #e63946;
        }

        .page-title {
            padding: 45px 7% 25px;
        }

        .page-title h1 {
            font-size: 38px;
            margin-bottom: 10px;
        }

        .page-title p {
            color: #666;
        }

        .container {
            display: flex;
            gap: 35px;
            padding: 20px 7% 60px;
        }

        .sidebar {
            width: 240px;
            min-width: 240px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            height: fit-content;
        }

        .sidebar h3 {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .filter-group h4 {
            margin-bottom: 12px;
        }

        .filter-group a {
            display: block;
            text-decoration: none;
            color: #555;
            padding: 9px 0;
        }

        .filter-group a:hover {
            color: #e63946;
        }

        .filter-group input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        .filter-btn {
            width: 100%;
            padding: 12px;
            background: #111;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .filter-btn:hover {
            background: #e63946;
        }

        .shop-content {
            flex: 1;
            min-width: 0;
        }

        .shop-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .shop-top h2 {
            font-size: 23px;
        }

        .search-form {
            display: flex;
            gap: 8px;
        }

        .search-form input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            width: 230px;
        }

        .search-form button {
            padding: 12px 20px;
            border: none;
            background: #111;
            color: white;
            border-radius: 7px;
            cursor: pointer;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.10);
        }

        .product-image {
            height: 220px;
            background: #eee;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 12px;
        }

        .product-info {
            padding: 18px;
        }

        .brand {
            color: #888;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .product-info h3 {
            margin: 9px 0;
            font-size: 18px;
        }

        .category {
            color: #777;
            font-size: 13px;
        }

        .price {
            margin: 15px 0;
            font-size: 19px;
            font-weight: bold;
        }

        .old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 14px;
            margin-left: 7px;
            font-weight: normal;
        }

        .view-btn {
            display: block;
            text-align: center;
            text-decoration: none;
            background: #111;
            color: white;
            padding: 12px;
            border-radius: 7px;
            font-weight: bold;
        }

        .view-btn:hover {
            background: #e63946;
        }

        .no-products {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            grid-column: 1 / -1;
        }

        footer {
            background: #111;
            color: white;
            text-align: center;
            padding: 30px;
        }

        @media(max-width: 1000px) {
            .products {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 700px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .products {
                grid-template-columns: repeat(2, 1fr);
            }

            .shop-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-form {
                width: 100%;
            }

            .search-form input {
                width: 100%;
            }

            .navbar {
                flex-wrap: wrap;
                gap: 15px;
            }

            .navbar a {
                margin-left: 10px;
            }
        }

        @media(max-width: 450px) {
            .products {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>
<body>

<nav class="navbar">

    <div class="logo">
        SNEAKERSHOP
    </div>

    <div>
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="cart.php">Cart</a>
        <a href="login.php">Login</a>
    </div>

</nav>

<section class="page-title">

    <h1>OUR SNEAKERS</h1>

    <p>Discover the latest sneakers and find your perfect style.</p>

</section>

<div class="container">

    <aside class="sidebar">

        <h3>Filters</h3>

        <form method="GET" action="shop.php">

            <div class="filter-group">

                <h4>Categories</h4>

                <a href="shop.php">All Sneakers</a>

                <?php foreach ($categories as $cat): ?>

                    <a href="shop.php?category=<?= (int)$cat['id'] ?>">
                        <?= e($cat['name']) ?>
                    </a>

                <?php endforeach; ?>

            </div>


            <div class="filter-group">

                <h4>Maximum Price (€)</h4>

                <input
                    type="number"
                    name="max_price"
                    placeholder="Enter maximum price"
                    min="0"
                    step="0.01"
                    value="<?= e($maxPrice) ?>"
                >

            </div>

            <button type="submit" class="filter-btn">
                Apply Filters
            </button>

        </form>

    </aside>

    <main class="shop-content">

        <div class="shop-top">

            <h2>All Sneakers</h2>

            <form method="GET" action="shop.php" class="search-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search sneakers..."
                    value="<?= e($search) ?>"
                >

                <button type="submit">Search</button>

            </form>

        </div>


        <div class="products">

            <?php if (count($products) > 0): ?>

                <?php foreach ($products as $product): ?>

                    <div class="product-card">

                        <div class="product-image">

                            <?php if (!empty($product['image'])): ?>

                                <img
                                    src="uploads/products/<?= rawurlencode(basename($product['image'])) ?>"
                                    alt="<?= e($product['name']) ?>"
                                >

                            <?php else: ?>

                                <img
                                    src="https://placehold.co/400x300?text=Sneaker"
                                    alt="No product image"
                                >

                            <?php endif; ?>

                        </div>


                        <div class="product-info">

                            <span class="brand">
                                <?= e($product['brand']) ?>
                            </span>

                            <h3>
                                <?= e($product['name']) ?>
                            </h3>

                            <p class="category">
                                <?= e($product['category_name'] ?? 'Uncategorized') ?>
                            </p>


                            <div class="price">

                                €<?= number_format(
                                    (float)($product['sale_price'] ?? $product['price']),
                                    2
                                ) ?>

                                <?php if ($product['sale_price'] !== null): ?>

                                    <span class="old-price">
                                        €<?= number_format((float)$product['price'], 2) ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <a
                                href="product.php?id=<?= (int)$product['id'] ?>"
                                class="view-btn"
                            >
                                View Product
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="no-products">

                    <h2>No sneakers found!</h2>

                    <p>Try searching for another product.</p>

                </div>

            <?php endif; ?>
        </div>
    </main>
</div>

<footer>

    <h3>SNEAKERSHOP</h3>

    <p>Find your style. Step into comfort.</p>

    <p>&copy; <?= date('Y') ?> SneakerShop. All rights reserved.</p>

</footer>
</body>
</html>