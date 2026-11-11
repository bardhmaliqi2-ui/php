<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('config.php');

if (isset($_POST['submit'])) {
	echo "Registration form submitted!";

    $emri = trim($_POST['emri'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $tempPass = $_POST['password'] ?? '';
    $tempConfirm = $_POST['confirm_password'] ?? '';

    if ($emri === '' || $username === '' || $email === '' ||
        $tempPass === '' || $tempConfirm === '') {

        exit("Please fill in all fields.");

    }

    if ($tempPass !== $tempConfirm) {
        exit("Passwords do not match.");
    }

    $password = password_hash($tempPass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (emri, username, email, password)
            VALUES (:emri, :username, :email, :password)";

    try {
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':emri' => $emri,
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);

        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        exit("Registration error: " . $e->getMessage());
    }
}
?>
