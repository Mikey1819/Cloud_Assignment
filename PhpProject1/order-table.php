<?php
// Include database connection
include 'helper.php';

// Start the session if needed
session_start();

// Capture the search term (if provided)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Set number of records per page
$recordsPerPage = 10;

// Get the current page, default to 1 if not set
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

// Modify the SQL query to include a search filter if a search term is provided
$orderQuery = "SELECT * FROM order_re";
if ($searchTerm) {
    $searchTerm = $conn->real_escape_string($searchTerm);  // Escape user input for security
    $orderQuery .= " WHERE order_id LIKE '%$searchTerm%'";  // Search by Order ID
}

// Temporarily remove pagination for debugging
//$orderQuery .= " LIMIT $recordsPerPage OFFSET $offset";

// Get the orders for the current page
$orderResult = $conn->query($orderQuery);
if (!$orderResult) {
    die("Order query failed: " . $conn->error);
}
echo '<div style="color:red;">Debug: Number of orders found: ' . $orderResult->num_rows . '</div>';

// Query to get the total number of records for pagination calculation
$totalQuery = "SELECT COUNT(*) AS total FROM order_re";
if ($searchTerm) {
    $totalQuery .= " WHERE order_id LIKE '%$searchTerm%'";
}
$totalResult = $conn->query($totalQuery);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $recordsPerPage);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Order Table</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
        <link rel="stylesheet" href="css/dashboard.css"/>
    </head>
    <body>
        <!------------------------- call nav --------------------------->
        <?php include 'nav.php'; ?>

        <!------------------------------- Sidebar Section START ----------------------------------->
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
        <!---------------------------------- Sidebar Section END ----------------------------------->

        <!----------------------------------- Sales Record START ----------------------------------->
        <div class="container mt-4 table-section">
            <h2>Order Record</h2>

            <!-- Search Bar -->
            <div class="d-flex justify-content-start mb-3">
                <div class="search-container">
                    <form method="GET" action="">
                        <input type="text" name="search" class="form-control search-input" placeholder="Search by Order ID..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </form>
                    <i class="bi bi-search search-icon" style="margin-left: 10px;"></i>
                </div>
            </div>
            <?php
            // Display success or error message
            if (isset($_GET['message'])) {
                echo '<div class="alert alert-info">' . htmlspecialchars($_GET['message']) . '</div>';
            }
            ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Total Price (RM)</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Check if there are any orders
                        if ($orderResult->num_rows > 0) {
                            while ($order = $orderResult->fetch_assoc()) {
                                echo '<tr><td colspan="4"><pre>';
                                print_r($order);
                                echo '</pre></td></tr>';
                            }
                        } else {
                            echo "<tr><td colspan='4'>No orders found</td></tr>";
                        }
                        ?>

                    </tbody>
                </table>
            </div>

            <div class="pagination-container d-flex justify-content-center">
                <nav>
                    <ul class="pagination">
                        <li class="page-item <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=1&search=<?php echo urlencode($searchTerm); ?>">First</a>
                        </li>
                        <li class="page-item <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Next</a>
                        </li>
                        <li class="page-item <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($searchTerm); ?>">Last</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!----------------------------------- Sales Record END ----------------------------------->

        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/default.js"></script>

        <!-- Toggle Sales Details Script -->
        <script>
            document.querySelectorAll('.toggle-details').forEach(button => {
                button.addEventListener('click', function () {
                    // Hide all details rows
                    document.querySelectorAll('.details-row').forEach(row => {
                        if (row.id !== this.dataset.target) {
                            row.style.display = 'none';
                        }
                    });

                    // Toggle the selected details row
                    let target = document.getElementById(this.dataset.target);
                    target.style.display = target.style.display === 'none' ? 'table-row' : 'none';
                });
            });
        </script>

    </body>
</html>

<?php $conn->close(); ?>
