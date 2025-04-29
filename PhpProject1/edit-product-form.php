<?php
include 'helper.php'; // Assuming this connects to your database

// Check if the product ID is provided via GET
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details from the database
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit;
    }

    $stmt->close();
} else {
    echo "Product ID is missing!";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Edit Product</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
        <link rel="stylesheet" href="css/form.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
    </head>
    <body>
        <?php include 'nav.php'; ?>
        <div class="container mt-5 mb-5 col-md-6">
            <div class="edit-container">
                <a href="../product-table.php" class="return-btn">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                </a>

                <h2 class="heading mb-4">Edit Product</h2>

                <form action="crud/update_product.php" method="POST" enctype="multipart/form-data">
                    <!-- Product ID (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Product ID</label>
                        <input type="text" class="form-control" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>" readonly>
                    </div>

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                    </div>

                    <!-- Product Image Upload -->
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" class="form-control custom-file-input" name="product_image" accept="image/*">
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category" required>
                            <option value="Souvenirs" <?php echo ($product['category'] == 'Souvenirs') ? 'selected' : ''; ?>>Souvenirs</option>
                            <option value="Gifts" <?php echo ($product['category'] == 'Gifts') ? 'selected' : ''; ?>>Gifts</option>
                            <option value="Graduation Attire" <?php echo ($product['category'] == 'Graduation Attire') ? 'selected' : ''; ?>>Graduation Attire</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label class="form-label">Price (RM)</label>
                        <input type="number" class="form-control" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" name="stock" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between">
                        <a href="product-table.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>

        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
