<?php
include '../helper.php';

if (isset($_GET['cart_id']) && isset($_GET['action'])) {
    $cart_id = intval($_GET['cart_id']);
    $action = $_GET['action'];

    // Fetch current quantity first
    $sql = "SELECT quantity FROM Cart WHERE cart_id = $cart_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantity = $row['quantity'];

        if ($action === 'increase') {
            $quantity++;
        } elseif ($action === 'decrease') {
            $quantity--;
            if ($quantity < 1) {
                $quantity = 1; // Minimum quantity is 1
            }
        }

        // Update quantity
        $update_sql = "UPDATE Cart SET quantity = $quantity WHERE cart_id = $cart_id";
        $conn->query($update_sql);
    }
}

// Redirect back to cart
header('Location: ../cart.php');
exit();
?>
