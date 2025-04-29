<?php
include 'helper.php'; // Assuming this connects to your database

// Set the number of products per page
$productsPerPage = 10;

// Check the current page number from the URL (default to 1 if not set)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculate the starting product for the current page (OFFSET)
$offset = ($page - 1) * $productsPerPage;

// Check if a search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// SQL query to get the products with pagination
$sql = "SELECT * FROM product WHERE product_id LIKE ? OR product_name LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$searchTermWildcard = "%" . $searchTerm . "%";  // Prepare the search term with wildcards
$stmt->bind_param("ssii", $searchTermWildcard, $searchTermWildcard, $productsPerPage, $offset);  // Bind parameters
$stmt->execute();
$result = $stmt->get_result();

// Query to count the total number of products for pagination
$countSql = "SELECT COUNT(*) AS total FROM product WHERE product_id LIKE ? OR product_name LIKE ?";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("ss", $searchTermWildcard, $searchTermWildcard);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$totalProducts = $countRow['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Add Product</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
        <link rel="stylesheet" href="css/dashboard.css"/>
        <link rel="icon" type="image/jpeg" href="/PhpProject1/img/logo.jpg"/>
    </head>
    <body>
        <!-- Include nav -->
        <?php include 'nav.php'; ?>

        <!-- Sidebar Section -->
        <div class="sidebar d-flex flex-column flex-shrink-0 text-white">
            <div class="dashboard-text">Dashboard</div>
            <div class="sidebar-content d-none flex-column p-3">
                <h5 class="text-center">Dashboard</h5>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="product-table.php" class="nav-link text-white sidebar-link">
                            <i class="bi bi-box-seam me-2"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="order-table.php" class="nav-link text-white sidebar-link">
                            <i class="bi bi-cash me-2"></i> Order Record
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Product Table Section -->
        <div class="container col-10 table-section">
            <h2 class="mb-3">Product Management</h2>

            <!-- Search Bar -->
            <div class="d-flex justify-content-between mb-3">
                <div class="search-container">
                    <form method="GET" action="">
                        <input type="text" name="search" class="form-control search-input" placeholder="Search by ID or Name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </form>
                    <i class="bi bi-search search-icon" style="margin-left: 10px;"></i>
                </div>
                <a href="add-product-form.php" class="btn edit-btn">
                    <i class="bi bi-plus-lg"></i> Add Product
                </a>
            </div>

            <!-- Product Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price (RM)</th>
                            <th>Stock Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>P' . str_pad($row['product_id'], 3, '0', STR_PAD_LEFT) . '</td>';
                                echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                                echo '<td><img src="img/product/' . htmlspecialchars($row['image_url']) . '" style="width: 100px; height: auto;"></td>';
                                echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                echo '<td>' . number_format($row['price'], 2) . '</td>';
                                echo '<td>' . intval($row['stock_quantity']) . '</td>';
                                echo '<td>
                                        <a href="edit-product-form.php?id=' . urlencode($row['product_id']) . '" class="btn edit-btn btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="crud/delete_product.php?id=' . urlencode($row['product_id']) . '" class="btn delete-btn btn-sm" 
                                           onclick="return confirm(\'Are you sure you want to delete this product?\')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                     </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8">No products found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination-container d-flex justify-content-center">
                <nav>
                    <ul class="pagination">
                        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=1&search=<?php echo urlencode($searchTerm); ?>">First</a>
                        </li>
                        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Previous</a>
                        </li>
                        <?php
                        // Display page numbers
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                                    <a class="page-link" href="?page=' . $i . '&search=' . urlencode($searchTerm) . '">' . $i . '</a>
                                  </li>';
                        }
                        ?>
                        <li class="page-item <?php echo ($page == $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Next</a>
                        </li>
                        <li class="page-item <?php echo ($page == $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($searchTerm); ?>">Last</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Footer Section -->
        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/default.js"></script>
    </body>
</html>

<?php
// Close the prepared statement and the database connection
$stmt->close();
$countStmt->close();
$conn->close();
?>
