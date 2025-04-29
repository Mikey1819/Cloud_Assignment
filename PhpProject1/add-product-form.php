<?php
include 'helper.php'; // Assuming this connects to your database
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    if ($price < 0) {
        echo "<script>alert('Price cannot be negative.'); window.history.back();</script>";
        exit();
    }
    if ($stock < 0) {
        echo "<script>alert('Stock quantity cannot be negative.'); window.history.back();</script>";
        exit();
    }

    // Upload image
    $imageName = "";
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $targetDir = "img/product/"; // <- make sure this folder exists
        $imageName = basename($_FILES["product_image"]["name"]);
        $targetFilePath = $targetDir . $imageName;

        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allow only JPG, JPEG, PNG, GIF files
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
                // File uploaded successfully
            } else {
                echo "<script>alert('Failed to upload the image.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
        }
    }

    // Auto-generate new Product ID
    $sql = "SELECT MAX(CAST(SUBSTRING(product_id, 2) AS UNSIGNED)) AS max_id FROM product";
    $result = $conn->query($sql);
    $newIdNumber = 1;

    if ($result && $row = $result->fetch_assoc()) {
        $newIdNumber = $row['max_id'] + 1;
    }
    $newProductId = "P" . str_pad($newIdNumber, 3, "0", STR_PAD_LEFT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO product (product_id, product_name, image_url, category, description, price, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssd", $newProductId, $productName, $imageName, $category, $description, $price, $stock);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href='product-table.php';</script>";
    } else {
        echo "<script>alert('Error adding product.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Product</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/product.css"/>
        <link rel="stylesheet" href="css/form.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
    </head>
    <body>
        <!------------------------- call nav --------------------------->
        <?php include 'nav.php'; ?>
        <div class="container mt-5 mb-5 col-md-6">
            <div class="edit-container">
                <!-- Return Button -->
                <a href="product-table.php" class="return-btn">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                </a>

                <h2 class="heading mb-4">Add Product</h2>

                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Product ID (Readonly) -->

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" value="" required>
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
                            <option value="Souvenirs" selected>Souvenirs</option>
                            <option value="Gifts">Gifts</option>
                            <option value="Graduation Attires">Graduation Attire</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label class="form-label">Price (RM)</label>
                        <input type="number" class="form-control" name="price" value="" step="0.01" required>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" name="stock" value="" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between">
                        <a href="product-table.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!------------------------- call footer --------------------------->
        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
