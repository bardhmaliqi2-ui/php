<?php
session_start();

include_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

include 'header.php';
?>

<style>
.account-container {
    max-width: 650px;
    margin: 50px auto;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.account-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #222;
}

.account-info {
    margin-bottom: 20px;
    padding: 15px;
    background: #f4f4f4;
    border-radius: 6px;
}

.account-info strong {
    display: block;
    margin-bottom: 7px;
    color: #555;
}

.account-info span {
    color: #222;
}

.account-buttons {
    display: flex;
    gap: 12px;
    margin-top: 25px;
    flex-wrap: wrap;
}

.account-buttons a {
    flex: 1;
    text-align: center;
    padding: 12px;
    background: #111;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.account-buttons a.logout {
    background: #ff4d4d;
}
</style>

<div class="account-container">

    <h2>My Account</h2>

    <div class="account-info">
        <strong>User ID</strong>
        <span><?php echo htmlspecialchars($user['id']); ?></span>
    </div>

    <div class="account-info">
        <strong>Name</strong>
        <span>
            <?php echo htmlspecialchars($user['name'] ?? 'Not available'); ?>
        </span>
    </div>

    <div class="account-info">
        <strong>Email</strong>
        <span>
            <?php echo htmlspecialchars($user['email'] ?? 'Not available'); ?>
        </span>
    </div>

    <div class="account-buttons">
        <a href="index.php">Home</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

</div>
<?php include 'footer.php'; ?>