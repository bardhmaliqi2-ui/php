<?php

include_once "config.php";
include_once "includes/functions.php";

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

$name = "";
$email = "";
$phone = "";
$city = "";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {

        $error = "Invalid request. Please try again.";

    }

    elseif (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        $error = "Please fill in all required fields.";

    }

    elseif (strlen($name) < 3 || strlen($name) > 100) {

        $error = "Name must contain between 3 and 100 characters.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    elseif (strlen($password) < 8) {

        $error = "Password must contain at least 8 characters.";

    }

    elseif ($password !== $confirmPassword) {

        $error = "Passwords do not match.";

    }

    else {

        $stmt = $conn->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $error = "This email is already registered.";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare("
                INSERT INTO users
                (name, email, password, phone, city, role)
                VALUES (?, ?, ?, ?, ?, 'customer')
            ");

            $stmt->execute([
                $name,
                $email,
                $hashedPassword,
                $phone,
                $city
            ]);

            session_regenerate_id(true);

            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['role'] = 'customer';

            header("Location: index.php");
            exit;

        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register - SneakerShop</title>

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

        .register-container {
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .register-box {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.07);
        }

        .register-logo {
            text-align: center;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .register-logo span {
            color: #e63946;
        }

        .register-box h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 9px;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #111;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.06);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 65px;
        }

        .show-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
            color: #555;
        }

        .register-btn {
            width: 100%;
            padding: 16px;
            background: #111;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .register-btn:hover {
            background: #e63946;
            transform: translateY(-2px);
        }

        .error-message {
            background: #ffe5e5;
            color: #c62828;
            padding: 13px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #777;
            font-size: 14px;
        }

        .login-link a {
            color: #111;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link a:hover {
            color: #e63946;
        }

        footer {
            background: #111;
            color: white;
            text-align: center;
            padding: 25px;
            font-size: 13px;
        }

        @media(max-width: 500px) {

            .register-box {
                padding: 30px 22px;
            }

            .register-box h1 {
                font-size: 24px;
            }

        }

    </style>

</head>

<body>

<?php include "includes/navbar.php"; ?>

<section class="register-container">

    <div class="register-box">

        <div class="register-logo">
            SNEAKER<span>SHOP</span>
        </div>

        <h1>Create Account</h1>

        <p class="subtitle">
            Join SneakerShop and discover your style.
        </p>

        <?php if (!empty($error)): ?>

            <div class="error-message">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" action="register.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e($_SESSION['csrf_token']) ?>"
            >

            <div class="form-group">

                <label for="name">Full Name *</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your full name"
                    value="<?= e($name) ?>"
                    maxlength="100"
                    autocomplete="name"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">Email Address *</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?= e($email) ?>"
                    maxlength="150"
                    autocomplete="email"
                    required
                >

            </div>

            <div class="form-group">

                <label for="phone">Phone Number</label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="Enter your phone number"
                    value="<?= e($phone) ?>"
                    maxlength="30"
                    autocomplete="tel"
                >

            </div>

            <div class="form-group">

                <label for="city">City</label>

                <input
                    type="text"
                    id="city"
                    name="city"
                    placeholder="Enter your city"
                    value="<?= e($city) ?>"
                    maxlength="100"
                    autocomplete="address-level2"
                >

            </div>

            <div class="form-group">

                <label for="password">Password *</label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimum 8 characters"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword('password', this)"
                    >
                        Show
                    </button>

                </div>

        </div>

            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password *
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Repeat your password"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword('confirm_password', this)"
                    >
                        Show
                    </button>

                </div>
            </div>

            <button type="submit" class="register-btn">
                CREATE ACCOUNT
            </button>

        </form>

        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Login Now
            </a>

        </div>

    </div>

</section>


<footer>

    <p>
        &copy; <?= date('Y') ?> SneakerShop.
        All rights reserved.
    </p>

</footer>


<script>

    function togglePassword(id, button) {

        const password = document.getElementById(id);

        if (password.type === "password") {

            password.type = "text";

            button.textContent = "Hide";

        } else {

            password.type = "password";

            button.textContent = "Show";

        }

    }

</script>
</body>
</html>