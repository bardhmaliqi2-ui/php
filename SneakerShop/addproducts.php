<?php
session_start();

include_once 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || !is_numeric($price) || $price < 0 || 
        !filter_var($category_id, FILTER_VALIDATE_INT)) {

        $message = "Please enter valid product information.";

    } elseif (!isset($_FILES['image']) ||
              $_FILES['image']['error'] !== UPLOAD_ERR_OK) {

        $message = "Please select a product image.";

    } else {

        $uploadDir ='/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        $allowedTypes = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp'
        ];

        if (!$imageInfo || !isset($allowedTypes[$imageInfo[2]])) {

            $message = "Only JPG, PNG and WEBP images are allowed.";

        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {

            $message = "Image must be smaller than 5MB.";

        } else {

            $extension = $allowedTypes[$imageInfo[2]];
            $imageName = bin2hex(random_bytes(16)) . '.' . $extension;
            $imagePath = $uploadDir . $imageName;
            $databasePath = 'uploads/' . $imageName;

            $categoryCheck = $conn->prepare(
                "SELECT id FROM categories WHERE id = ?"
            );

            $categoryCheck->bind_param("i", $category_id);
            $categoryCheck->execute();
            $categoryResult = $categoryCheck->get_result();

            if ($categoryResult->num_rows === 0) {

                $message = "Please select a valid category.";

            } elseif (move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $imagePath
            )) {

               $sql = "INSERT INTO products
                       (name, brand, price, sale_price, category_id,
                       description, image, status, featured, created_at)
                       VALUES
                       (:name, :brand, :price, :sale_price, :category_id,
                       :description, :image, 'active', 0, NOW())";

                $stmt = $conn->prepare($sql);

                $stmt->execute([
                "name" => $name,
                "brand" => $brand,
                "price" => $price,
                "sale_price" => $sale_price !== "" ? $sale_price : null,
                "category_id" => $category_id,
                "description" => $description,
                "image" => $image
                ]);

                $price = (float)$price;
                $category_id = (int)$category_id;

                $stmt->bind_param(
                    "sdiss",
                    $name,
                    $price,
                    $category_id,
                    $description,
                    $databasePath
                );

                if ($stmt->execute()) {
                    $message = "Product added successfully!";
                } else {
                    unlink($imagePath);
                    $message = "Database error: product could not be added.";
                }

                $stmt->close();

            } else {
                $message = "Image upload failed.";
            }

            $categoryCheck->close();
        }
    }
}
$categoryQuery = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/header.php';
?>

<style>
.add-product-container {
    max-width: 650px;
    margin: 40px auto;
    padding: 30px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.add-product-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #111;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

.form-group textarea {
    resize: vertical;
    min-height: 110px;
}

.image-preview {
    width: 100%;
    max-height: 250px;
    object-fit: contain;
    margin-top: 12px;
    display: none;
    border-radius: 8px;
    background: #f4f4f4;
}

.submit-btn {
    width: 100%;
    padding: 14px;
    background: #111;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn:hover {
    background: #ff4d4d;
}

.message {
    padding: 12px;
    margin-bottom: 20px;
    background: #e8f5e9;
    color: #256029;
    border-radius: 6px;
    text-align: center;
}
</style>

<div class="add-product-container">

    <h2>Add New Sneaker</h2>

    <?php if ($message !== ''): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name"
                   placeholder="Nike Air Max 90" required>
        </div>

        <div class="form-group">
            <label>Price (€)</label>
            <input type="number" name="price"
                   step="0.01" min="0" placeholder="120.00" required>
        </div>

        <div class="form-group">
            <label>Category</label>

            <select name="category_id" required>
                <option value="">Select Category</option>

                <?php if ($categories): ?>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo (int)$category['id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>

            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description"
                      placeholder="Enter sneaker description..."
                      required></textarea>
        </div>

        <div class="form-group">
            <label>Product Image</label>

            <input type="file" name="image"
                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                   required>

            <img id="preview" class="image-preview" alt="Image preview">
        </div>

        <button type="submit" class="submit-btn">
            Add Product
        </button>

    </form>

</div>

<script>
document.querySelector('input[name="image"]').addEventListener('change', function() {
    const preview = document.getElementById('preview');

    if (this.files && this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>