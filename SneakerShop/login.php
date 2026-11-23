<?php
include_once "config.php";

if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {

        $error = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $stmt = $conn->prepare("
            SELECT id, name, email, password, role
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {

                header("Location: admin/dashboard.php");
                exit;

            } else {

                header("Location: index.php");
                exit;

            }

        } else {

            $error = "Incorrect email or password.";

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

    <title>Login - SneakerShop</title>

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

        .login-container {
            min-height: 85vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 45px;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.07);
        }

        .login-logo {
            text-align: center;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .login-logo span {
            color: #e63946;
        }

        .login-box h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 35px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
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
            border: none;
            background: none;
            cursor: pointer;
            font-weight: bold;
            color: #555;
        }

        .error-message {
            background: #ffe5e5;
            color: #c62828;
            padding: 13px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login-btn {
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

        .login-btn:hover {
            background: #e63946;
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #777;
        }

        .register-link a {
            color: #111;
            font-weight: bold;
            text-decoration: none;
        }

        .register-link a:hover {
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

            .login-box {
                padding: 30px 22px;
            }

            .login-box h1 {
                font-size: 24px;
            }

        }

    </style>

</head>

<body>

<?php include "includes/navbar.php"; ?>

<section class="login-container">

    <div class="login-box">

        <div class="login-logo">
            SNEAKER<span>SHOP</span>
        </div>

        <h1>Welcome Back!</h1>

        <p class="subtitle">
            Login to your SneakerShop account.
        </p>

        <?php if (!empty($error)): ?>

            <div class="error-message">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="form-group">

                <label for="email">Email Address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?= e($email) ?>"
                    autocomplete="email"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">Password</label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword()"
                    >
                        Show
                    </button>

                </div>

            </div>


            <button type="submit" class="login-btn">
                LOGIN
            </button>

        </form>


        <div class="register-link">

            Don't have an account?

            <a href="register.php">
                Register Now
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

    function togglePassword() {

        const password = document.getElementById("password");

        const button = document.querySelector(".show-password");

        if (password.type === "password") {

            password.type = "text";

            button.textContent = "Hide";

        } else {

            password.type = "password";

            button.textContent = "Show";

        }

    }

</script>
<?php include 'footer.php'; ?>
</body>
</html>