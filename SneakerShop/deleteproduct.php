
<?php
include_once '/config.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    if ($id > 0) {

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                header("Location: products.php?message=deleted");
                exit;
            } else {
                echo "Product not found.";
            }

        } else {
            echo "Error deleting product: " .
                 htmlspecialchars(mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);

    } else {
        echo "Invalid product ID.";
    }

} else {
    echo "No product ID provided.";
}
?>
