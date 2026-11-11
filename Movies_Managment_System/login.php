
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('config.php');

if (isset($_POST['submit'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = "Please fill in all fields.";

    } else {

        try {

            $sql = "SELECT id, emri, username, email, password, is_admin
                    FROM users
                    WHERE username = :username";

            $stmt = $conn->prepare($sql);
            $stmt->execute([':username' => $username]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {

                $error = "User does not exist.";

            } elseif (!password_verify($password, $data['password'])) {

                $error = "Password is not valid.";

            } else {

                session_regenerate_id(true);

                $_SESSION['id'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['emri'] = $data['emri'];
                $_SESSION['is_admin'] = $data['is_admin'];

                header("Location: dashboard.php");
                exit();
            }

        } catch (PDOException $e) {

            $error = "Database error: " . $e->getMessage();

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .login-form {
            width: 100%;
            max-width: 350px;
            padding: 20px;
            background: white;
            border-radius: 10px;
        }

        .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="login-form">

    <h2 class="text-center mb-4">Sign In</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">

        <input
            type="text"
            class="form-control"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="password"
            class="form-control"
            name="password"
            placeholder="Password"
            required
        >

        <button
            type="submit"
            name="submit"
            class="btn btn-primary w-100">
            Sign In
        </button>

    </form>

    <p class="text-center mt-3">
        Don't have an account?
        <a href="index.php">Register</a>
    </p>

</div>

</body>
</html>
