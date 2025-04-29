<?php
include '../helper.php'; // Assuming this connects to your database

// Start a transaction
$conn->begin_transaction();

try {
    // Loop through cart items and check stock
    $cartSql = "SELECT Cart.cart_id, Cart.product_id, Cart.quantity, Product.price, Product.stock_quantity, Product.product_name
                FROM Cart
                JOIN Product ON Cart.product_id = Product.product_id";
    $cartResult = $conn->query($cartSql);

    // Check if any product quantity exceeds the available stock
    while ($cartRow = $cartResult->fetch_assoc()) {
        $productName = $cartRow['product_name'];
        $productId = $cartRow['product_id'];
        $quantity = $cartRow['quantity'];
        $stockQuantity = $cartRow['stock_quantity'];

        if ($quantity > $stockQuantity) {
            // If stock is insufficient, throw an exception with an error message
            throw new Exception("Error: Product (Name: $productName) has insufficient stock. Available: $stockQuantity, Requested: $quantity.");
        }
    }

    // Proceed to create the order if stock is sufficient
    // Insert into Order_re table
    $totalAmount = 0;
    $sql = "INSERT INTO Order_re (total_amount) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $totalAmount);
    $stmt->execute();

    // Get the last inserted order_id
    $orderId = $stmt->insert_id;

    // Loop through cart items and insert into Order_Item
    $cartResult->data_seek(0); // Reset the result pointer
    while ($cartRow = $cartResult->fetch_assoc()) {
        $productId = $cartRow['product_id'];
        $quantity = $cartRow['quantity'];
        $price = $cartRow['price'];
        $totalAmount += $price * $quantity; // Calculate total amount for the order

        // Insert into Order_Item table
        $orderItemSql = "INSERT INTO Order_Item (order_id, product_id, quantity, price) 
                         VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($orderItemSql);
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
        $stmt->execute();

        // Decrease the stock in the Product table
        $updateStockSql = "UPDATE Product SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
        $stmt = $conn->prepare($updateStockSql);
        $stmt->bind_param("ii", $quantity, $productId);
        $stmt->execute();
    }

    // Update the total amount in the Order_re table
    $updateOrderTotalSql = "UPDATE Order_re SET total_amount = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateOrderTotalSql);
    $stmt->bind_param("di", $totalAmount, $orderId);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Clear the cart after the order is placed
    $clearCartSql = "DELETE FROM Cart";
    $conn->query($clearCartSql);

    // Redirect to the success page
    echo "<script>alert('Order placed successfully!'); window.location.href='../payment-success.php';</script>";

} catch (Exception $e) {
    // If any error occurs, roll back the transaction, show the error message and redirect back to cart
    $conn->rollback();
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='../cart.php';</script>";
}

$conn->close();
?>
