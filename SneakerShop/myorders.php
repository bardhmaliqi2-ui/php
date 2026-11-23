<?php
session_start();

include_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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
    "SELECT * FROM orders WHERE email = ? ORDER BY created_at DESC"
);

$stmt->bind_param("s", $user['email']);
$stmt->execute();

$orders = $stmt->get_result();

include __DIR__ . '/header.php';
?>

<style>
.orders-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.orders-container h2 {
    text-align: center;
    margin-bottom: 25px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 14px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #111;
    color: white;
}

tr:hover {
    background: #f5f5f5;
}

.no-orders {
    text-align: center;
    padding: 25px;
    color: #777;
}

.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background: #111;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
</style>

<div class="orders-container">

    <h2>My Orders</h2>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Date</th>
            </tr>

            <?php if ($orders->num_rows > 0): ?>

                <?php while ($order = $orders->fetch_assoc()): ?>

                    <tr>
                        <td>#<?php echo htmlspecialchars($order['id']); ?></td>

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

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" class="no-orders">
                        You have not placed any orders yet.
                    </td>
                </tr>

            <?php endif; ?>

        </table>
    </div>

    <a href="shop.php" class="back-btn">Continue Shopping</a>

</div>
<?php include __DIR__ . '/footer.php'; ?>