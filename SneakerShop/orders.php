<?php
session_start();

include_once '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$orders = $conn->query($sql);

include __DIR__ . '/header.php';
?>

<style>
.orders-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.orders-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #222;
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

.details-btn {
    display: inline-block;
    background: #111;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
}

.details-btn:hover {
    background: #ff4d4d;
}

.no-orders {
    text-align: center;
    padding: 25px;
    color: #777;
}
</style>

<div class="orders-container">

    <h2>Manage Orders</h2>

    <div class="table-wrapper">

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($orders && $orders->num_rows > 0): ?>

                <?php while ($order = $orders->fetch_assoc()): ?>

                    <tr>
                        <td>
                            #<?php echo htmlspecialchars($order['id']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($order['customer_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($order['email']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($order['phone']); ?>
                        </td>

                        <td>
                            €<?php echo number_format((float)$order['total'], 2); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($order['created_at']); ?>
                        </td>

                        <td>
                            <a class="details-btn"
                               href="orderdetails.php?id=<?php echo (int)$order['id']; ?>">
                                Details
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="no-orders">
                        No orders found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>

    </div>

</div>
<?php include __DIR__ . '/footer.php'; ?>