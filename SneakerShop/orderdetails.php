<?php
session_start();

include_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid order ID.");
}

$order_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

$user_stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();

$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$stmt = $conn->prepare(
    "SELECT * FROM orders WHERE id = ? AND email = ?"
);

$stmt->bind_param("is", $order_id, $user['email']);
$stmt->execute();

$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

include __DIR__ . '/header.php';
?>

<style>
.order-container {
    max-width: 750px;
    margin: 50px auto;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.order-container h2 {
    text-align: center;
    margin-bottom: 25px;
}

.order-info {
    padding: 15px;
    margin-bottom: 12px;
    background: #f4f4f4;
    border-radius: 6px;
}

.order-info strong {
    display: inline-block;
    width: 130px;
}

.total {
    margin-top: 20px;
    padding: 18px;
    background: #111;
    color: white;
    text-align: center;
    font-size: 22px;
    border-radius: 6px;
}

.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 11px 18px;
    background: #ff4d4d;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
</style>

<div class="order-container">

    <h2>Order Details</h2>

    <div class="order-info">
        <strong>Order ID:</strong>
        #<?php echo htmlspecialchars($order['id']); ?>
    </div>

    <div class="order-info">
        <strong>Customer:</strong>
        <?php echo htmlspecialchars($order['customer_name']); ?>
    </div>

    <div class="order-info">
        <strong>Email:</strong>
        <?php echo htmlspecialchars($order['email']); ?>
    </div>

    <div class="order-info">
        <strong>Phone:</strong>
        <?php echo htmlspecialchars($order['phone']); ?>
    </div>

    <div class="order-info">
        <strong>Address:</strong>
        <?php echo htmlspecialchars($order['address']); ?>
    </div>

    <div class="order-info">
        <strong>Order Date:</strong>
        <?php echo htmlspecialchars($order['created_at']); ?>
    </div>

    <div class="total">
        Total: €<?php echo number_format((float)$order['total'], 2); ?>
    </div>

    <a href="myorders.php" class="back-btn">Back to My Orders</a>

</div>
<?php include __DIR__ . '/footer.php'; ?>