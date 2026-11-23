<?php
include_once "config.php";
include_once "functions.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $message = "Enter a category name.";
    } else {

        $check = $conn->prepare(
            "SELECT id FROM categories WHERE name = :name"
        );
        $check->execute(["name" => $name]);

        if ($check->fetch()) {
            $message = "This category already exists.";
        } else {

            $stmt = $conn->prepare(
                "INSERT INTO categories (name) VALUES (:name)"
            );

            $stmt->execute(["name" => $name]);

            $message = "Category added successfully!";
        }
    }
}

include "header.php";
?>

<style>
.category-container {
    max-width: 500px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
}

.category-container input {
    width: 100%;
    padding: 12px;
    margin: 15px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.category-container button {
    background: #111;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>

<div class="category-container">

    <h2>Add Category</h2>

    <?php if ($message): ?>
        <p><?php echo e($message); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Category Name</label>

        <input
            type="text"
            name="name"
            placeholder="Example: Nike"
            required
        >

        <button type="submit">Add Category</button>

    </form>

</div>

<?php include "footer.php"; ?>