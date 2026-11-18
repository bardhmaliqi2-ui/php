
<?php
include_once "config.php";
include_once "includes/functions.php";

requireLogin();

$user_id = getUserId();

$success = "";
$error = "";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$stmt = $conn->prepare("
    SELECT id FROM carts WHERE user_id = ?
");
$stmt->execute([$user_id]);

$cart = $stmt->fetch();

if (!$cart) {
    $stmt = $conn->prepare("
        INSERT INTO carts (user_id) VALUES (?)
    ");
    $stmt->execute([$user_id]);

    $cart_id = $conn->lastInsertId();
} else {
    $cart_id = $cart['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $error = "Invalid request. Please try again.";
    } else {

        $action = $_POST['action'] ?? '';

        if ($action === 'remove') {

            $item_id = filter_input(
                INPUT_POST,
                'item_id',
                FILTER_VALIDATE_INT
            );

            if ($item_id && $item_id > 0) {

                $stmt = $conn->prepare("
                    DELETE FROM cart_items
                    WHERE id = ? AND cart_id = ?
                ");

                $stmt->execute([$item_id, $cart_id]);

                $success = "Product removed from cart!";

            } else {
                $error = "Invalid product.";
            }
        }
        elseif ($action === 'update') {

            $item_id = filter_input(
                INPUT_POST,
                'item_id',
                FILTER_VALIDATE_INT
            );

            $quantity = filter_input(
                INPUT_POST,
                'quantity',
                FILTER_VALIDATE_INT
            );

            if (
                $item_id && $item_id > 0 &&
                $quantity !== false &&
                $quantity !== null &&
                $quantity >= 1 &&
                $quantity <= 100
            ) {
                $stmt = $conn->prepare("
                    SELECT ps.quantity
                    FROM cart_items ci
                    JOIN product_sizes ps
                        ON ps.product_id = ci.product_id
                        AND ps.size = ci.size
                    WHERE ci.id = ?
                    AND ci.cart_id = ?
                ");

                $stmt->execute([$item_id, $cart_id]);
                $stock = $stmt->fetch();

                if (!$stock) {

                    $error = "Product or size is no longer available.";

                } elseif ($quantity > $stock['quantity']) {

                    $error = "Only " . $stock['quantity'] .
                             " pairs are available.";

                } else {

                    $stmt = $conn->prepare("
                        UPDATE cart_items
                        SET quantity = ?
                        WHERE id = ? AND cart_id = ?
                    ");

                    $stmt->execute([
                        $quantity,
                        $item_id,
                        $cart_id
                    ]);

                    $success = "Cart updated successfully!";
                }

            } else {
                $error = "Please enter a valid quantity.";
            }
        }

        else {
            $error = "Invalid action.";
        }
    }
}

$stmt = $conn->prepare("
    SELECT
        ci.id AS item_id,
        ci.quantity,
        ci.size,
        p.id AS product_id,
        p.name,
        p.brand,
        p.image,
        p.price,
        p.sale_price,
        ps.quantity AS stock,

        COALESCE(p.sale_price, p.price) AS final_price

    FROM cart_items ci

    INNER JOIN products p
        ON ci.product_id = p.id

    INNER JOIN product_sizes ps
        ON ps.product_id = ci.product_id
        AND ps.size = ci.size

    WHERE ci.cart_id = ?
    AND p.status = 'active'

    ORDER BY ci.id DESC
");

$stmt->execute([$cart_id]);

$cartItems = $stmt->fetchAll();

$subtotal = 0;

foreach ($cartItems as $item) {
    $subtotal += (float)$item['final_price']
               * (int)$item['quantity'];
}

$total = $subtotal;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart - SneakerShop</title>

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

        .page-title {
            padding: 45px 8% 25px;
        }

        .page-title h1 {
            font-size: 36px;
            font-weight: 900;
        }

        .page-title p {
            color: #777;
            margin-top: 10px;
        }

        .container {
            padding: 20px 8% 70px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            align-items: start;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 0 8% 15px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .cart-products {
            background: white;
            padding: 25px;
            border-radius: 12px;
        }

        .cart-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-header h2 {
            font-size: 23px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-image {
            width: 130px;
            height: 120px;
            background: #f3f3f3;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .cart-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }

        .item-info {
            flex: 1;
        }

        .item-info .brand {
            color: #888;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .item-info h3 {
            margin: 8px 0;
            font-size: 19px;
        }

        .item-info p {
            color: #777;
            font-size: 14px;
            margin-top: 6px;
        }

        .item-price {
            font-size: 18px;
            font-weight: bold;
            margin-top: 12px;
        }

        .quantity-form {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }

        .quantity-form input {
            width: 65px;
            padding: 9px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .update-btn {
            padding: 9px 12px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .update-btn:hover {
            background: #e63946;
        }

        .remove-form {
            margin-top: 10px;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #e63946;
            cursor: pointer;
            font-weight: bold;
        }

        .remove-btn:hover {
            text-decoration: underline;
        }

        .summary {
            background: white;
            padding: 25px;
            border-radius: 12px;
            position: sticky;
            top: 100px;
        }

        .summary h2 {
            font-size: 22px;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            color: #555;
        }

        .summary-total {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #111;
        }

        .checkout-btn {
            display: block;
            text-align: center;
            background: #111;
            color: white;
            padding: 16px;
            text-decoration: none;
            border-radius: 30px;
            margin-top: 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        .checkout-btn:hover {
            background: #e63946;
        }

        .continue-shopping {
            display: block;
            text-align: center;
            color: #111;
            margin-top: 18px;
            text-decoration: none;
            font-weight: bold;
        }

        .empty-cart {
            background: white;
            padding: 60px 20px;
            text-align: center;
            border-radius: 12px;
            grid-column: 1 / -1;
        }

        .empty-cart h2 {
            font-size: 27px;
            margin-bottom: 15px;
        }

        .empty-cart p {
            color: #777;
            margin-bottom: 25px;
        }

        .shop-btn {
            display: inline-block;
            padding: 14px 35px;
            background: #111;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
        }

        .shop-btn:hover {
            background: #e63946;
        }

        @media(max-width: 900px) {

            .container {
                grid-template-columns: 1fr;
            }

            .summary {
                position: static;
            }

        }

        @media(max-width: 550px) {

            .cart-item {
                flex-wrap: wrap;
            }

            .cart-image {
                width: 100px;
                height: 100px;
            }

            .page-title h1 {
                font-size: 28px;
            }

            .container {
                padding-left: 5%;
                padding-right: 5%;
            }

        }

    </style>

</head>

<body>

<?php include "includes/navbar.php"; ?>

<section class="page-title">

    <h1>YOUR SHOPPING CART</h1>

    <p>Review your sneakers before checkout.</p>

</section>


<?php if ($success): ?>

    <div class="message success">
        <?= e($success) ?>
    </div>

<?php endif; ?>


<?php if ($error): ?>

    <div class="message error">
        <?= e($error) ?>
    </div>

<?php endif; ?>


<div class="container">


<?php if (count($cartItems) > 0): ?>

    <section class="cart-products">

        <div class="cart-header">

            <h2>
                Shopping Cart (<?= count($cartItems) ?> items)
            </h2>

        </div>


        <?php foreach ($cartItems as $item): ?>

            <div class="cart-item">

                <div class="cart-image">

                    <?php if (!empty($item['image'])): ?>

                        <img
                            src="uploads/products/<?= rawurlencode(basename($item['image'])) ?>"
                            alt="<?= e($item['name']) ?>"
                        >

                    <?php else: ?>

                        <img
                            src="https://placehold.co/300x250?text=Sneaker"
                            alt="No image"
                        >

                    <?php endif; ?>

                </div>

                <div class="item-info">

                    <span class="brand">
                        <?= e($item['brand']) ?>
                    </span>

                    <h3>
                        <?= e($item['name']) ?>
                    </h3>

                    <p>
                        Size: <?= (int)$item['size'] ?>
                    </p>

                    <p>
                        Available stock: <?= (int)$item['stock'] ?>
                    </p>

                    <div class="item-price">

                        <?= formatPrice($item['final_price']) ?>

                    </div>

                    <form method="POST" class="quantity-form">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <input type="hidden"
                               name="action"
                               value="update">

                        <input type="hidden"
                               name="item_id"
                               value="<?= (int)$item['item_id'] ?>">

                        <input
                            type="number"
                            name="quantity"
                            value="<?= (int)$item['quantity'] ?>"
                            min="1"
                            max="<?= (int)$item['stock'] ?>"
                            required
                        >

                        <button type="submit" class="update-btn">
                            Update
                        </button>

                    </form>

                    <form method="POST" class="remove-form">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <input type="hidden"
                               name="action"
                               value="remove">

                        <input type="hidden"
                               name="item_id"
                               value="<?= (int)$item['item_id'] ?>">

                        <button type="submit" class="remove-btn">
                            Remove Product
                        </button>

                    </form>

                </div>

                <div class="item-price">

                    <?= formatPrice(
                        $item['final_price'] * $item['quantity']
                    ) ?>

                </div>

            </div>

        <?php endforeach; ?>

    </section>

    <aside class="summary">

        <h2>Order Summary</h2>

        <div class="summary-row">

            <span>Subtotal</span>

            <strong>
                <?= formatPrice($subtotal) ?>
            </strong>

        </div>

        <div class="summary-row">

            <span>Shipping</span>

            <strong>Calculated at checkout</strong>

        </div>

        <div class="summary-row summary-total">

            <span>Total</span>

            <span><?= formatPrice($total) ?></span>

        </div>


        <a href="checkout.php" class="checkout-btn">
            PROCEED TO CHECKOUT
        </a>

        <a href="shop.php" class="continue-shopping">
            ← Continue Shopping
        </a>

    </aside>


<?php else: ?>

    <div class="empty-cart">

        <h2>Your Cart is Empty!</h2>

        <p>
            You haven't added any sneakers to your shopping cart yet.
        </p>

        <a href="shop.php" class="shop-btn">
            START SHOPPING
        </a>

    </div>

<?php endif; ?>
</div>
</body>
</html>