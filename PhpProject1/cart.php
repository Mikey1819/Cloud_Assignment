<?php
include 'helper.php'; // Make sure you have db_connection.php that connects to $conn
// Fetch all cart items with product information
$sql = "SELECT Cart.cart_id, Cart.quantity, Product.product_name, Product.price, Product.image_url, Product.description
        FROM Cart
        JOIN Product ON Cart.product_id = Product.product_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Cart</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/cart.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
    </head>
    <body>
        <!------------------------- call nav --------------------------->
        <?php include 'nav.php'; ?>

        <!------------------------------------- Cart START ---------------------------------------------->
        <section class="h-100">
            <div class="container pb-5">
                <div class="row d-flex justify-content-center my-4">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header py-3 mb-3">
                                <h5 class="mb-0">Cart - 2 items</h5>
                            </div>
                            <div class="card-body">
                                <!-- Single item -->
                                <div class="row">
                                    <?php
                                    $subtotal = 0; // For summary calculation

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $product_total_price = $row['price'] * $row['quantity'];
                                            $subtotal += $product_total_price;
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                                    <div class="rounded" data-mdb-ripple-color="light">
                                                        <img src="img/product/<?php echo $row['image_url']; ?>" class="w-100" alt="<?php echo $row['product_name']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                                    <p><strong><?php echo $row['product_name']; ?></strong></p>
                                                    <p>Description: <?php echo $row['description']; ?></p>
                                                    <p>Price: RM<?php echo number_format($row['price'], 2); ?></p>

                                                    <a href="crud/remove_from_cart.php?cart_id=<?php echo $row['cart_id']; ?>" 
                                                       class="btn btn-danger btn-sm me-1 mb-2" 
                                                       onclick="return confirm('Are you sure you want to remove this item?')">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </a>

                                                </div>

                                                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                                                    <div class="d-flex mb-4" style="max-width: 300px">
                                                        <button class="btn btn-primary px-3 me-2"
                                                                onclick="updateQuantity(<?php echo $row['cart_id']; ?>, -1)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>

                                                        <div class="form-outline">
                                                            <input min="1" name="quantity" value="<?php echo $row['quantity']; ?>" type="number" class="form-control text-center" readonly />
                                                        </div>

                                                        <button class="btn btn-primary px-3 ms-2"
                                                                onclick="updateQuantity(<?php echo $row['cart_id']; ?>, 1)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>

                                                    <p class="price text-md-center">
                                                        <strong>RM<?php echo number_format($product_total_price, 2); ?></strong>
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-4" />
                                            <?php
                                        }
                                    } else {
                                        echo "<p>Your cart is empty!</p>";
                                    }
                                    ?>

                                </div>
                                <!-- Single item -->
                                <hr class="my-4" />

                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p><strong>Expected shipping delivery</strong></p>
                                <p class="mb-0"> 5 - 7 days</p>
                            </div>
                        </div>
                        <div class="card mb-4 mb-lg-0">
                            <div class="card-body">
                                <p><strong>We accept</strong></p>
                                <img class="me-2" width="45px" height="30px"
                                     src="img/payment-m1.png"
                                     alt="Visa" />
                                <img class="me-2" width="45px" height="30px"
                                     src="img/payment-m2.png"
                                     alt="TNG "/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Summary</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                        Subtotal
                                        <span>RM<?php echo number_format($subtotal, 2); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        Shipping
                                        <span>RM5.00</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                        Tax (SST 6%)
                                        <span>RM<?php echo number_format($subtotal * 0.06, 2); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 mb-3">
                                        <strong>Total (with Tax)</strong>
                                        <span><strong>RM<?php echo number_format($subtotal + 5.00 + ($subtotal * 0.06), 2); ?></strong></span>
                                    </li>
                                </ul>


                                <!-- Button to Open Modal -->
                                <button type="button" class="w-100 c-btn btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutConfirmModal">
                                    Go to checkout
                                </button>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Checkout Confirmation Modal -->
        <div class="modal fade" id="checkoutConfirmModal" tabindex="-1" aria-labelledby="checkoutConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutConfirmModalLabel">Confirm Checkout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to proceed to checkout and finalize your payment?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="crud/place_order.php" class="btn btn-primary">Confirm</a>
                    </div>
                </div>
            </div>
        </div>

        <!------------------------------------- Cart END ---------------------------------------------->
        <!------------------------- call footer --------------------------->
        <?php include 'footer.php'; ?>

        <!--------------------------- Java Script link ----------------------------------->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/default.js"></script>
        <script>
            function updateQuantity(cartId, change) {
                let action = (change > 0) ? 'increase' : 'decrease';
                window.location.href = `crud/update_cart_quantity.php?cart_id=${cartId}&action=${action}`;
            }
        </script>



    </body>
</html>

