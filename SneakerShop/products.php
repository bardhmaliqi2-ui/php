<?php
include_once "config.php";
include_once "functions.php";

$stmt = $conn->query("
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories
    ON products.category_id = categories.id
    ORDER BY products.created_at DESC
");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "header.php";
?>

<style>
.products-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

.products-container h2 {
    margin-bottom: 20px;
}

.add-btn {
    display: inline-block;
    background: #111;
    color: white;
    padding: 11px 18px;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 20px;
}

.add-btn:hover {
    background: #ff4d4d;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.products-table th,
.products-table td {
    padding: 13px;
    border: 1px solid #ddd;
    text-align: center;
}

.products-table th {
    background: #111;
    color: white;
}

.products-table img {
    width: 85px;
    height: 85px;
    object-fit: contain;
}

.edit-btn, .delete-btn {
    display: inline-block;
    padding: 7px 11px;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.edit-btn {
    background: #007bff;
}

.delete-btn {
    background: #e60000;
}

@media(max-width: 700px) {
    .products-table {
        font-size: 12px;
    }

    .products-table th,
    .products-table td {
        padding: 6px;
    }
}
</style>

<div class="products-container">

    <h2>Manage Products</h2>

<a href="addproducts.php" class="add-btn">
    + Add Product
</a>

<a href="addcategory.php" class="add-btn">
    + Add Category
</a>

    <a href="addproducts.php" class="add-btn">
        + Add New Product
    </a>

    <table class="products-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Price</th>
                <th>Sale Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php if (count($products) > 0): ?>

            <?php foreach ($products as $product): ?>

                <tr>
                    <td><?php echo e($product['id']); ?></td>

                <td>
                        <?php if (!empty($product['image'])): ?>
                    <img
                        src="<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                        style="width:85px; height:85px; object-fit:contain;"
                        onerror="this.src='https://placehold.co/100x100?text=No+Image';"
                    >

                    <?php else: ?>
                      No photo
                    <?php endif; ?>
                </td>

                    <td><?php echo e($product['name']); ?></td>

                    <td><?php echo e($product['brand']); ?></td>

                    <td>
                        <?php echo e($product['category_name'] ?? 'Uncategorized'); ?>
                    </td>

                    <td>€<?php echo number_format((float)$product['price'], 2); ?></td>

                    <td>
                        <?php
                        echo $product['sale_price'] !== null
                            ? '€' . number_format((float)$product['sale_price'], 2)
                            : '-';
                        ?>
                    </td>

                    <td><?php echo e($product['status']); ?></td>

                    <td>
                        <a
                            href="editproduct.php?id=<?php echo e($product['id']); ?>"
                            class="edit-btn"
                        >Edit</a>

                        <a
                            href="deleteproduct.php?id=<?php echo e($product['id']); ?>"
                            class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this product?')"
                        >Delete</a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="9">No products found.</td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>
<footer style="
    background: #111;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
">
    <p>&copy; 2026 SneakerShop. All Rights Reserved.</p>
</footer>

</body>
</html> 