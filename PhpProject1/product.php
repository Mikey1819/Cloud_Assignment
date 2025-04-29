<?php
require_once 'helper.php';
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Product</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="css/default.css"/>
        <link rel="stylesheet" href="css/product.css"/>
        <link rel="stylesheet" href="css/nav.css"/>
    </head>

    <body>
        <!------------------------- call nav --------------------------->
        <?php include 'nav.php'; ?>

        <!------------------------------------- Banner START ---------------------------------------------->
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active position-relative">
                    <img src="img/banner.jpg" class="d-block w-100" alt="Product Banner">

                    <!-- Overlay -->
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);"></div>

                    <!-- Caption -->
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="page_title" style="color: #d8f9ff;">Celebrate Your Achievement in Style</h2>
                        <p class="text-light">Shop official TARUMT graduation gowns, gifts, and memorabilia. Easy. Secure. Fast — even during peak season.</p>
                    </div>
                </div>
            </div>
        </div>

        <!------------------------------------- Banner END ---------------------------------------------->

        <!------------------------------------- Product START ---------------------------------------------->
        <div class="container mt-4 product-section">

            <!----------------- Tab-Navigation -------------------->
            <div class="category-nav">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">All</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fitness-tab" data-bs-toggle="tab" data-bs-target="#graduationAttire" type="button" role="tab">Graduation Attire</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sports-tab" data-bs-toggle="tab" data-bs-target="#gifts" type="button" role="tab">Gifts</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accessories-tab" data-bs-toggle="tab" data-bs-target="#souvenirs" type="button" role="tab">Souvenirs</button>
                    </li>
                </ul>

                <!-------------- Search box -------------------->
                <div class="search-wrapper">
                    <form method="get" class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control search-input" placeholder="Type to search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </form>


                </div>
            </div>

            <!---------------- Tab Content(Product) --------------->
            <div class="tab-content mt-3" id="productTabsContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <section class="section-products">
                        <div class="container">
                            <div class="row">
                                <?php
                                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                                if (!empty($search)) {
                                    $sql = "SELECT * FROM Product WHERE product_name LIKE '%$search%' OR category LIKE '%$search%'";
                                } else {
                                    $sql = "SELECT * FROM Product";
                                }

                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="col-md-6 col-lg-4 col-xl-3">';
                                        echo '  <div class="single-product">';
                                        echo '    <div class="part-1">';
                                        echo '      <img src="img/product/' . htmlspecialchars($row['image_url']) . '" class="img-fluid" alt="">';
                                        echo '      <ul>';
                                        echo '        <li><a href="crud/add_to_cart.php?product_id=' . $row['product_id'] . '"><i class="fas fa-shopping-cart"></i></a></li>';
                                        echo '        <li><a href="product_details.php?product_id=' . $row['product_id'] . '"><i class="fas fa-info"></i></a></li>';
                                        echo '      </ul>';
                                        echo '    </div>';
                                        echo '    <div class="part-2">';
                                        echo '      <h3 class="product-title">' . htmlspecialchars($row['product_name']) . '</h3>';
                                        echo '      <h4 class="product-category">' . htmlspecialchars($row['category']) . '</h4><br>';
                                        echo '      <h4 class="product-price">RM' . number_format($row['price'], 2) . '</h4>';
                                        echo '    </div>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "<p>No products found.</p>";
                                }
                                ?>


                            </div>
                        </div>
                    </section>
                </div>

                <!---------- Second tab ---------------->
                <div class="tab-pane fade" id="graduationAttire" role="tabpanel">
                    <section class="section-products">
                        <div class="container">
                            <div class="row">
                                <?php
                                $sql = "SELECT * FROM Product WHERE category = 'Graduation Attire'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="col-md-6 col-lg-4 col-xl-3">';
                                        echo '  <div class="single-product">';
                                        echo '    <div class="part-1">';
                                        echo '      <img src="img/product/' . htmlspecialchars($row['image_url']) . '" class="img-fluid" alt="">';
                                        echo '      <ul>';
                                        echo '        <li><a href="crud/add_to_cart.php?product_id=' . $row['product_id'] . '"><i class="fas fa-shopping-cart"></i></a></li>';
                                        echo '        <li><a href="product_details.php?product_id=' . $row['product_id'] . '"><i class="fas fa-info"></i></a></li>';
                                        echo '      </ul>';
                                        echo '    </div>';
                                        echo '    <div class="part-2">';
                                        echo '      <h3 class="product-title">' . htmlspecialchars($row['product_name']) . '</h3>';
                                        echo '      <h4 class="product-category">' . htmlspecialchars($row['category']) . '</h4><br>';
                                        echo '      <h4 class="product-price">RM' . number_format($row['price'], 2) . '</h4>';
                                        echo '    </div>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "<p>No products found.</p>";
                                }
                                ?>

                            </div>
                        </div>
                    </section>
                </div>

                <!---------- Third tab ---------------->
                <div class="tab-pane fade" id="gifts" role="tabpanel">
                    <section class="section-products">
                        <div class="container">
                            <div class="row">
                                <?php
                                $sql = "SELECT * FROM Product WHERE category = 'Gifts'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="col-md-6 col-lg-4 col-xl-3">';
                                        echo '  <div class="single-product">';
                                        echo '    <div class="part-1">';
                                        echo '      <img src="img/product/' . htmlspecialchars($row['image_url']) . '" class="img-fluid" alt="">';
                                        echo '      <ul>';
                                        echo '        <li><a href="crud/add_to_cart.php?product_id=' . $row['product_id'] . '"><i class="fas fa-shopping-cart"></i></a></li>';
                                        echo '        <li><a href="product_details.php?product_id=' . $row['product_id'] . '"><i class="fas fa-info"></i></a></li>';
                                        echo '      </ul>';
                                        echo '    </div>';
                                        echo '    <div class="part-2">';
                                        echo '      <h3 class="product-title">' . htmlspecialchars($row['product_name']) . '</h3>';
                                        echo '      <h4 class="product-category">' . htmlspecialchars($row['category']) . '</h4><br>';
                                        echo '      <h4 class="product-price">RM' . number_format($row['price'], 2) . '</h4>';
                                        echo '    </div>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "<p>No products found in Gifts category.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </section>
                </div>


                <!---------- Fourth tab ---------------->
                <div class="tab-pane fade" id="souvenirs" role="tabpanel">
                    <section class="section-products">
                        <div class="container">
                            <div class="row">
                                <?php
                                $sql = "SELECT * FROM Product WHERE category = 'Souvenirs'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="col-md-6 col-lg-4 col-xl-3">';
                                        echo '  <div class="single-product">';
                                        echo '    <div class="part-1">';
                                        echo '      <img src="img/product/' . htmlspecialchars($row['image_url']) . '" class="img-fluid" alt="">';
                                        echo '      <ul>';
                                        echo '        <li><a href="crud/add_to_cart.php?product_id=' . $row['product_id'] . '"><i class="fas fa-shopping-cart"></i></a></li>';
                                        echo '        <li><a href="product_details.php?product_id=' . $row['product_id'] . '"><i class="fas fa-info"></i></a></li>';
                                        echo '      </ul>';
                                        echo '    </div>';
                                        echo '    <div class="part-2">';
                                        echo '      <h3 class="product-title">' . htmlspecialchars($row['product_name']) . '</h3>';
                                        echo '      <h4 class="product-category">' . htmlspecialchars($row['category']) . '</h4><br>';
                                        echo '      <h4 class="product-price">RM' . number_format($row['price'], 2) . '</h4>';
                                        echo '    </div>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "<p>No products found in Souvenirs category.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
        </div>
        <!------------------------------------- Product END ---------------------------------------------->

        <!------------------------- call footer --------------------------->
        <?php include 'footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/default.js"></script>
    </body>

</html>
