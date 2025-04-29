<?php

include '../helper.php'; // Assuming this connects to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Validate price and stock to ensure they are not negative
    if ($price < 0) {
        echo "<script>alert('Price cannot be negative.'); window.history.back();</script>";
        exit();
    }

    if ($stock < 0) {
        echo "<script>alert('Stock quantity cannot be negative.'); window.history.back();</script>";
        exit();
    }

    // Check if a new image is uploaded
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $targetDir = "../img/product/";
        $imageName = basename($_FILES["product_image"]["name"]);
        $targetFilePath = $targetDir . $imageName;

        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
                $imageUrl = $imageName;
            } else {
                echo "<script>alert('Failed to upload the image.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG, and GIF files are allowed.'); window.history.back();</script>";
            exit();
        }
    } else {
        // If no new image is uploaded, keep the existing one
        $stmt = $conn->prepare("SELECT image_url FROM product WHERE product_id = ?");
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $imageUrl = $row['image_url'];
        $stmt->close();
    }

    // Update product details in the database
    $stmt = $conn->prepare("UPDATE product SET product_name = ?, category = ?, description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE product_id = ?");
    $stmt->bind_param("sssdiss", $productName, $category, $description, $price, $stock, $imageUrl, $productId);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='../product-table.php';</script>";
    } else {
        echo "<script>alert('Error updating product.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>

