<?php
session_start();

include_once '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM users ORDER BY id DESC";
$users = $conn->query($sql);

include __DIR__ . '/header.php';
?>

<style>
.users-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.users-container h2 {
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

.no-users {
    text-align: center;
    padding: 25px;
    color: #777;
}

.total-users {
    margin-bottom: 20px;
    font-size: 17px;
    font-weight: bold;
    color: #333;
}
</style>

<div class="users-container">

    <h2>Manage Users</h2>

    <div class="total-users">
        Total Users: <?php echo $users ? $users->num_rows : 0; ?>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($users && $users->num_rows > 0): ?>

                <?php while ($user = $users->fetch_assoc()): ?>

                    <tr>
                        <td>
                            <?php echo (int)$user['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="3" class="no-users">
                        No users found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>
<?php include __DIR__ . '/footer.php'; ?>