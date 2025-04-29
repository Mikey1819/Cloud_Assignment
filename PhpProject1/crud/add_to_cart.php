<?php
include '../helper.php';

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Check if the product already exists in cart
    $check_sql = "SELECT * FROM Cart WHERE product_id = $product_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If exists, update the quantity +1
        $update_sql = "UPDATE Cart SET quantity = quantity + 1 WHERE product_id = $product_id";
        $conn->query($update_sql);
    } else {
        // If not exists, insert with quantity = 1
        $insert_sql = "INSERT INTO Cart (product_id, quantity) VALUES ($product_id, 1)";
        $conn->query($insert_sql);
    }

    // Prompt message for success (optional)
    echo "<script>
        alert('Add to cart success!');
        window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
    </script>";

    exit();
} else {
    echo "No product selected.";
}
?>
