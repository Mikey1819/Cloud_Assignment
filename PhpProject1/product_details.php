<?php
require_once 'helper.php';

// Check if product_id is provided
if (!isset($_GET['product_id'])) {
    echo "No product selected.";
    exit;
}

$product_id = intval($_GET['product_id']);
$sql = "SELECT * FROM Product WHERE product_id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Details</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
        <style>
            .product-details-card {
                border: none;
                border-radius: 1rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .product-image {
                width: 100%;
                max-height: 400px; /* limit the image height */
                object-fit: contain; /* no crop, fit nicely */
                background: #f8f9fa; /* soft background if image is small */
                padding: 1rem;
            }
            .product-info {
                padding: 2rem;
            }
            .stock-available {
                font-weight: bold;
                color: green;
            }
            .stock-unavailable {
                font-weight: bold;
                color: red;
            }
            .btn-add-cart {
                padding: 0.75rem 2rem;
                font-size: 1.1rem;
            }
        </style>

    </head>
    <body>

        <?php include 'nav.php'; ?>

        <div class="container my-5">
            <div class="card product-details-card">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="img/product/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="img-fluid product-image">
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <div class="product-info">
                            <h1 class="mb-3"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                            <h5 class="text-muted mb-3"><?php echo htmlspecialchars($product['category']); ?></h5>

                            <h3 class="text-primary mb-4">RM<?php echo number_format($product['price'], 2); ?></h3>

                            <p class="mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                            <p class="mb-4">
                                <?php if ($product['stock_quantity'] > 0): ?>
                                    <span class="stock-available"><?php echo $product['stock_quantity']; ?> Available in Stock</span>
                                <?php else: ?>
                                    <span class="stock-unavailable">Out of Stock</span>
                                <?php endif; ?>
                            </p>

                            <?php if ($product['stock_quantity'] > 0): ?>
                                <a href="crud/add_to_cart.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success btn-add-cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-add-cart" disabled>
                                    <i class="fas fa-ban"></i> Out of Stock
                                </button>
                            <?php endif; ?>

                            <a href="product.php" class="btn btn-outline-secondary btn-add-cart ms-3">
                                <i class="fas fa-arrow-left"></i> Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
