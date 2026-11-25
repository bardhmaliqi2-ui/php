<?php
session_start();

require_once __DIR__ . '/config.php';

$message = "";
$messageType = "success";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $price = $_POST['price'] ?? '';
    $sale_price = $_POST['sale_price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if (
        $name === '' ||
        $brand === '' ||
        !is_numeric($price) ||
        $price < 0 ||
        !filter_var($category_id, FILTER_VALIDATE_INT)
    ) {
        $message = "Please enter valid product information.";
        $messageType = "error";

    } elseif (
        $sale_price !== '' &&
        (!is_numeric($sale_price) || $sale_price < 0)
    ) {
        $message = "Invalid sale price.";
        $messageType = "error";

    } elseif (
        !isset($_FILES['image']) ||
        $_FILES['image']['error'] !== UPLOAD_ERR_OK
    ) {
        $message = "Please select a product image.";
        $messageType = "error";

    } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        $message = "Image must be smaller than 5MB.";
        $messageType = "error";

    } else {
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);

        $allowedTypes = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp'
        ];

        if (
            !$imageInfo ||
            !isset($allowedTypes[$imageInfo[2]])
        ) {
            $message = "Only JPG, PNG and WEBP images are allowed.";
            $messageType = "error";

        } else {

            $extension = $allowedTypes[$imageInfo[2]];

            $uploadDir = __DIR__ . '/uploads/products/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = bin2hex(random_bytes(16)) . '.' . $extension;

            $imagePath = $uploadDir . $imageName;

            $databasePath = 'uploads/products/' . $imageName;

            try {
                $categoryCheck = $conn->prepare(
                    "SELECT id FROM categories WHERE id = ?"
                );

                $categoryCheck->execute([$category_id]);

                if (!$categoryCheck->fetch()) {

                    $message = "Please select a valid category.";
                    $messageType = "error";

                } elseif (
                    move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        $imagePath
                    )
                ) {
                    if (!file_exists($imagePath)) {

                        $message = "Image file was not saved.";
                        $messageType = "error";

                    } else {
                        $sql = "INSERT INTO products
                        (
                            name,
                            brand,
                            price,
                            sale_price,
                            category_id,
                            description,
                            image,
                            status,
                            featured,
                            created_at
                        )
                        VALUES
                        (
                            :name,
                            :brand,
                            :price,
                            :sale_price,
                            :category_id,
                            :description,
                            :image,
                            'active',
                            0,
                            NOW()
                        )";

                        $stmt = $conn->prepare($sql);

                        $stmt->execute([
                            ':name' => $name,
                            ':brand' => $brand,
                            ':price' => $price,
                            ':sale_price' => $sale_price !== ''
                                ? $sale_price
                                : null,
                            ':category_id' => $category_id,
                            ':description' => $description,
                            ':image' => $databasePath
                        ]);

                        $message = "Product added successfully!";
                        $messageType = "success";
                    }

                } else {

                    $message = "Image upload failed. Check uploads folder.";
                    $messageType = "error";
                }

            } catch (PDOException $e) {
                if (isset($imagePath) && file_exists($imagePath)) {
                    unlink($imagePath);
                }

                $message = "Database error: " . $e->getMessage();
                $messageType = "error";
            }
        }
    }
}
$categoryQuery = $conn->query(
    "SELECT * FROM categories ORDER BY name ASC"
);

$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/header.php'; ?>

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
    box-sizing: border-box;
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
    border-radius: 6px;
    text-align: center;
}

.success {
    background: #e8f5e9;
    color: #256029;
}

.error {
    background: #ffebee;
    color: #b71c1c;
}

</style>

<div class="add-product-container">

    <h2>Add New Sneaker</h2>

    <?php if ($message !== ''): ?>

        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Product Name</label>

            <input type="text"
                   name="name"
                   placeholder="Nike Air Max 90"
                   required>
        </div>

        <div class="form-group">
            <label>Brand</label>

            <input type="text"
                   name="brand"
                   placeholder="Nike, Adidas, Jordan..."
                   required>
        </div>

        <div class="form-group">
            <label>Price (€)</label>

            <input type="number"
                   name="price"
                   step="0.01"
                   min="0"
                   placeholder="120.00"
                   required>
        </div>

        <div class="form-group">
            <label>Sale Price (Optional)</label>

            <input type="number"
                   name="sale_price"
                   step="0.01"
                   min="0"
                   placeholder="99.99">
        </div>

        <div class="form-group">
            <label>Category</label>

            <select name="category_id" required>

                <option value="">Select Category</option>

                <?php foreach ($categories as $category): ?>

                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>

                <?php endforeach; ?>

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

            <input type="file"
                   name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   required>

            <img id="preview"
                 class="image-preview"
                 alt="Image preview">
        </div>

        <button type="submit" class="submit-btn">
            Add Product
        </button>

    </form>

</div>

<script>

const imageInput = document.querySelector('input[name="image"]');
const preview = document.getElementById('preview');

imageInput.addEventListener('change', function () {

    if (this.files && this.files[0]) {

        preview.src = URL.createObjectURL(this.files[0]);
        preview.style.display = 'block';

    } else {

        preview.style.display = 'none';
    }

});
</script>
<?php include 'footer.php'; ?>