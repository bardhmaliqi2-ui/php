<?php
session_start();

include_once __DIR__ . '/config.php';

$message = "";

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    $message = "Your shopping cart is empty.";
}

$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if (isset($_POST['place_order']) && !empty($cart)) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL)
        && $phone && $address) {

        $sql = "INSERT INTO orders
                (customer_name, email, phone, address, total)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssd",
            $name,
            $email,
            $phone,
            $address,
            $total
        );

        if (mysqli_stmt_execute($stmt)) {

            $order_id = mysqli_insert_id($conn);

            unset($_SESSION['cart']);

            $message = "Order placed successfully! Order ID: " . $order_id;

            $cart = [];

        } else {
            $message = "Error placing order.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $message = "Please enter valid information in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - SneakerShop</title>

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

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #346beb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #2454c5;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .message {
            text-align: center;
            color: #346beb;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="container">

    <h2>Checkout</h2>

    <div class="message">
        <?php echo htmlspecialchars($message); ?>
    </div>

    <?php if (!empty($cart)) { ?>

        <h3>Order Summary</h3>

        <?php foreach ($cart as $item) { ?>

            <p>
                <?php echo htmlspecialchars($item['name']); ?>
                x <?php echo (int)$item['quantity']; ?>

                - €
                <?php echo number_format(
                    $item['price'] * $item['quantity'], 2
                ); ?>
            </p>

        <?php } ?>

        <div class="total">
            Total: €<?php echo number_format($total, 2); ?>
        </div>

        <form method="POST">

            <label>Full Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone Number</label>
            <input type="tel" name="phone" required>

            <label>Delivery Address</label>
            <textarea name="address" required></textarea>

            <button type="submit" name="place_order">
                Place Order
            </button>

        </form>

    <?php } ?>

</div>

<?php include '/footer.php'; ?>

</body>
</html>