<?php
// Include database connection
include '../helper.php';

// Check if the order_id is passed
if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Start a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Delete the order items from Order_Item table
        $deleteOrderItemsQuery = "DELETE FROM Order_Item WHERE order_id = ?";
        $stmt = $conn->prepare($deleteOrderItemsQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        // Delete the order from Order_re table
        $deleteOrderQuery = "DELETE FROM Order_re WHERE order_id = ?";
        $stmt = $conn->prepare($deleteOrderQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect to the order table page with a success message
        header("Location: ../order-table.php?message=Order Deleted Successfully");
    } catch (Exception $e) {
        // Rollback the transaction if there was an error
        $conn->rollback();
        // Redirect to the order table page with an error message
        header("Location: ../order-table.php?message=Error Deleting Order");
    }
} else {
    // If no order_id is passed, redirect to order table with an error message
    header("Location: ../order-table.php?message=Invalid Order ID");
}

$conn->close();
?>
