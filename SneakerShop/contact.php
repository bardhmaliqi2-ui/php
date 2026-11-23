<?php
include_once '/config.php';

$message = "";

if (isset($_POST['send_message'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_text = trim($_POST['message']);

    if (
        $name !== "" &&
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $subject !== "" &&
        $message_text !== ""
    ) {

        $sql = "INSERT INTO messages
                (name, email, subject, message)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $name,
            $email,
            $subject,
            $message_text
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Your message was sent successfully!";
        } else {
            $message = "Something went wrong. Please try again.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $message = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us - SneakerShop</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .contact-container {
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

        p {
            text-align: center;
            color: #555;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        textarea {
            height: 130px;
            resize: vertical;
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

        .message {
            text-align: center;
            color: green;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="contact-container">

    <h2>Contact Us</h2>

    <p>Have a question? Send us a message!</p>

    <div class="message">
        <?php echo htmlspecialchars($message); ?>
    </div>

    <form method="POST">

        <label>Your Name</label>
        <input type="text" name="name"
               placeholder="Enter your name" required>

        <label>Email Address</label>
        <input type="email" name="email"
               placeholder="Enter your email" required>

        <label>Subject</label>
        <input type="text" name="subject"
               placeholder="Enter subject" required>

        <label>Your Message</label>
        <textarea name="message"
                  placeholder="Write your message..."
                  required></textarea>

        <button type="submit" name="send_message">
            Send Message
        </button>

    </form>

</div>

<?php include '/footer.php'; ?>

</body>
</html>