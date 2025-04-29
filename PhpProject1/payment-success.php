<!DOCTYPE html>
<html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>Payment Success</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
            <link rel="stylesheet" href="css/default.css"/>
            <link rel="stylesheet" href="css/nav.css"/>
        </head>
        <body>
            <!------------------------- call nav --------------------------->
            <?php include 'nav.php'; ?>

            <div class="container text-center" style="margin-bottom: 100px; margin-top: 100px;">
                <div class="card shadow p-4">
                    <div class="card-body">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h2 class="mt-3">Payment Successful!</h2>
                        <p class="lead">Thank you for your purchase. Your transaction has been completed successfully.</p>
                        <a href="product.php" class="btn btn-success mt-3">
                            <i class="bi bi-house-door-fill"></i> Return to Home
                        </a>
                    </div>
                </div>
            </div>

            <!------------------------- call footer --------------------------->
            <?php include 'footer.php'; ?>
        </body>
    </html>