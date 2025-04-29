<?php
include '../helper.php'; // Assuming this connects to your database

// Check if the product ID is provided via GET
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Prepare the SQL statement to delete the product
    $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param("s", $productId);

    if ($stmt->execute()) {
        // Redirect back to the product table with a success message
        echo "<script>alert('Product deleted successfully!'); window.location.href='../product-table.php';</script>";
    } else {
        // If an error occurred, display an error message
        echo "<script>alert('Error deleting product.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Product ID is missing.'); window.history.back();</script>";
}

$conn->close();
?>
