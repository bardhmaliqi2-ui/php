<?php

require_once "config.php";
require_once "functions.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base = "/php1/SneakerShop";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$product_id || $product_id < 1) {
    die("Invalid product.");
}
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    AND status = 'active'
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}
$stmt = $conn->prepare("
    SELECT size, quantity
    FROM product_sizes
    WHERE product_id = ?
    ORDER BY size ASC
");

$stmt->execute([$product_id]);

$sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        )
    ) {
        $error = "Invalid request.";

    } elseif (!isLoggedIn()) {

        header("Location: login.php");
        exit;

    } else {

        $size = filter_input(
            INPUT_POST,
            'size',
            FILTER_VALIDATE_INT
        );

        $quantity = filter_input(
            INPUT_POST,
            'quantity',
            FILTER_VALIDATE_INT
        );

        if (!$size || !$quantity || $quantity < 1 || $quantity > 100) {

            $error = "Please select a valid size and quantity.";

        } else {
            $stmt = $conn->prepare("
                SELECT quantity
                FROM product_sizes
                WHERE product_id = ?
                AND size = ?
            ");

            $stmt->execute([$product_id, $size]);

            $stock = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$stock || $stock['quantity'] < 1) {

                $error = "This size is unavailable.";

            } elseif ($quantity > $stock['quantity']) {

                $error = "Only " . $stock['quantity'] .
                         " pairs are available.";

            } else {
                $user_id = getUserId();

                $stmt = $conn->prepare("
                    SELECT id
                    FROM carts
                    WHERE user_id = ?
                ");

                $stmt->execute([$user_id]);

                $cart = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$cart) {

                    $stmt = $conn->prepare("
                        INSERT INTO carts (user_id)
                        VALUES (?)
                    ");

                    $stmt->execute([$user_id]);

                    $cart_id = $conn->lastInsertId();

                } else {

                    $cart_id = $cart['id'];
                }
                $stmt = $conn->prepare("
                    SELECT id, quantity
                    FROM cart_items
                    WHERE cart_id = ?
                    AND product_id = ?
                    AND size = ?
                ");

                $stmt->execute([
                    $cart_id,
                    $product_id,
                    $size
                ]);

                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {

                    $new_quantity =
                        (int)$existing['quantity'] + $quantity;

                    if ($new_quantity > $stock['quantity']) {

                        $error = "You cannot add more than " .
                                 $stock['quantity'] .
                                 " pairs of this size.";

                    } else {

                        $stmt = $conn->prepare("
                            UPDATE cart_items
                            SET quantity = ?
                            WHERE id = ?
                        ");

                        $stmt->execute([
                            $new_quantity,
                            $existing['id']
                        ]);

                        $_SESSION['cart_message'] =
                            "Product added to cart successfully!";

                        header("Location: cart.php");
                        exit;
                    }

                } else {

                    $stmt = $conn->prepare("
                        INSERT INTO cart_items
                        (cart_id, product_id, size, quantity)
                        VALUES (?, ?, ?, ?)
                    ");

                    $stmt->execute([
                        $cart_id,
                        $product_id,
                        $size,
                        $quantity
                    ]);

                    $_SESSION['cart_message'] =
                        "Product added to cart successfully!";

                    header("Location: cart.php");
                    exit;
                }
            }
        }
    }
}

$price = $product['sale_price'] !== null &&
         $product['sale_price'] > 0
         ? $product['sale_price']
         : $product['price'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= e($product['name']) ?> - SneakerShop</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f5f5f5;
    font-family: Arial, sans-serif;
    color: #111;
}

.product-container {
    width: 90%;
    max-width: 1200px;
    margin: 50px auto;

    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
}

.product-image {
    background: white;
    border-radius: 15px;
    padding: 30px;
    min-height: 450px;
}

.product-image img {
    width: 100%;
    height: 430px;
    object-fit: contain;
}

.product-info {
    padding: 20px 0;
}

.product-brand {
    color: #777;
    text-transform: uppercase;
    font-size: 14px;
    font-weight: bold;
}

.product-info h1 {
    font-size: 38px;
    margin: 12px 0;
}

.product-price {
    font-size: 28px;
    font-weight: bold;
    margin: 20px 0;
}

.old-price {
    color: #999;
    text-decoration: line-through;
    font-size: 18px;
    margin-left: 10px;
}

.product-description {
    color: #666;
    line-height: 1.7;
    margin: 25px 0;
}

.product-form {
    background: white;
    padding: 25px;
    border-radius: 12px;
}

.product-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 10px;
}

.product-form select,
.product-form input {
    width: 100%;
    padding: 13px;
    border: 1px solid #ddd;
    border-radius: 7px;
    margin-bottom: 20px;
    font-size: 16px;
}

.add-cart-btn {
    width: 100%;
    background: #111;
    color: white;
    border: none;
    padding: 16px;
    border-radius: 30px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.add-cart-btn:hover {
    background: #e63946;
}

.add-cart-btn:disabled {
    background: #999;
    cursor: not-allowed;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.back-link {
    display: inline-block;
    margin-top: 25px;
    color: #111;
    text-decoration: none;
    font-weight: bold;
}

.back-link:hover {
    color: #e63946;
}

@media(max-width: 800px) {

    .product-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .product-image {
        min-height: 300px;
    }

    .product-image img {
        height: 300px;
    }

    .product-info h1 {
        font-size: 30px;
    }

}

</style>

</head>

<body>

<?php include "navbar.php"; ?>

<div class="product-container">

    <div class="product-image">

        <?php if (!empty($product['image'])): ?>

            <img
                src="uploads/products/<?= e(basename($product['image'])) ?>"
                alt="<?= e($product['name']) ?>"
            >

        <?php else: ?>

            <img
                src="https://placehold.co/500x500?text=Sneaker"
                alt="No product image"
            >

        <?php endif; ?>

    </div>

    <div class="product-info">

        <span class="product-brand">
            <?= e($product['brand'] ?? '') ?>
        </span>

        <h1>
            <?= e($product['name']) ?>
        </h1>

        <div class="product-price">

            <?= formatPrice($price) ?>

            <?php if (
                !empty($product['sale_price']) &&
                $product['sale_price'] > 0 &&
                $product['sale_price'] < $product['price']
            ): ?>

                <span class="old-price">
                    <?= formatPrice($product['price']) ?>
                </span>

            <?php endif; ?>

        </div>

        <div class="product-description">

            <?= nl2br(e($product['description'] ?? '')) ?>

        </div>

        <?php if ($error): ?>

            <div class="error-message">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="product-form">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e($_SESSION['csrf_token']) ?>"
            >

            <label for="size">
                Select Size
            </label>

            <select name="size" id="size" required>

                <option value="">
                    Choose a size
                </option>

                <?php foreach ($sizes as $s): ?>

                    <?php if ((int)$s['quantity'] > 0): ?>

                        <option value="<?= (int)$s['size'] ?>">
                            EU <?= (int)$s['size'] ?>
                            (<?= (int)$s['quantity'] ?> available)
                        </option>

                    <?php endif; ?>

                <?php endforeach; ?>

            </select>

            <label for="quantity">
                Quantity
            </label>

            <input
                type="number"
                name="quantity"
                id="quantity"
                value="1"
                min="1"
                max="100"
                required
            >

            <?php if (count($sizes) > 0): ?>

                <button type="submit" class="add-cart-btn">
                    ADD TO CART
                </button>

            <?php else: ?>

                <button type="button" class="add-cart-btn" disabled>
                    OUT OF STOCK
                </button>

            <?php endif; ?>

        </form>

        <a href="shop.php" class="back-link">
            ← Back to Shop
        </a>

    </div>

</div>
<?php include "footer.php"; ?>
</body>
</html>