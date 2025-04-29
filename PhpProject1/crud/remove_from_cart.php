<?php
include '../helper.php'; // include your database connection file

if (isset($_GET['cart_id'])) {
    $cart_id = intval($_GET['cart_id']);

    // Delete the item from Cart table
    $sql = "DELETE FROM Cart WHERE cart_id = $cart_id";

    if ($conn->query($sql) === TRUE) {
        // Successfully deleted
        header("Location: ../cart.php"); // Redirect back to cart page
        exit();
    } else {
        echo "Error removing item: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>

