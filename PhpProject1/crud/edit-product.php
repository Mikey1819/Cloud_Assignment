<?php
include '../helper.php'; // Assuming this connects to your database

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
