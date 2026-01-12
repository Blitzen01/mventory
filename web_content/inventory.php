<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }
    
    include "../render/connection.php"; 
    include "../src/cdn/cdn_links.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventory Display</title>

        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">

    </head>
    
    <body class="">
        <div class="row">
            <div class="col-lg-2">
                <?php include "../nav/sidebar_nav.php"; ?>
            </div>
            <div class="col">
                <div class="main-content">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                            <div>
                                <h3 class="fw-bold text-dark mb-0">
                                    <i class="fa-solid fa-boxes-stacked me-2 text-dark"></i>Product Inventory
                                </h3>
                                <p class="text-muted small">Viewing all current stock levels</p>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-outline-dark border-dark shadow-sm ms-1" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="fa-solid fa-plus-circle"></i> Add New Item
                                </button>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-body bg-white rounded">
                                <form class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                            <input type="text" class="form-control border-start-0 bg-light" placeholder="Search SKU or Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select bg-light">
                                            <option value="">All Categories</option>
                                            <?php
                                                $category_query = "SELECT category_name FROM categories ORDER BY category_name ASC";
                                                $category_result = mysqli_query($conn, $category_query);
                                                if ($category_result) {
                                                    while ($category_row = mysqli_fetch_assoc($category_result)) {
                                                        ?>
                                                        <option><?php echo htmlspecialchars($category_row['category_name']); ?></option>
                                                        <?php
                                                    }
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select bg-light">
                                            <option value="">All Status</option>
                                            <option>In Stock</option>
                                            <option>Low Stock</option>
                                            <option>Out of Stock</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-dark w-100">Apply Filters</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-muted">SKU</th>
                                            <th class="text-muted">PRODUCT NAME</th>
                                            <th class="text-muted">CATEGORY</th>
                                            <th class="text-center text-muted">STOCK</th>
                                            <th class="text-center text-primary small">MIN.</th>
                                            <th class="text-muted">STATUS</th>
                                            <th class="text-muted">COST</th>
                                            <th class="text-muted">LOCATION</th>
                                            <th class="text-muted">CONDITION</th>
                                            <th class="pe-3 text-muted">REMARKS</th>
                                            <th class="ps-3 text-muted">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        
                                        <tr>
                                            <td><span class="text-muted small fw-bold">PROD-1001</span></td>
                                            <td class="fw-semibold text-dark">Logitech G-Pro Wireless</td>
                                            <td><span class="badge bg-light text-dark border fw-normal">Peripherals</span></td>
                                            <td class="text-center fw-bold text-success">52</td>
                                            <td class="text-center text-muted small">10</td>
                                            <td><span class="badge bg-success-subtle text-success border border-success-subtle fw-normal">In Stock</span></td>
                                            <td class="text-dark small fw-medium">₱4,500.00</td>
                                            <td class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i>Shelf A-12</td>
                                            <td><span class="text-primary small fw-bold">New</span></td>
                                            <td class="text-truncate text-muted small pe-3" style="max-width: 150px;">Standard Stock</td>
                                            <td class="ps-3">
                                                <div class="btn-group btn-group-sm border rounded">
                                                    <button class="btn btn-link text-dark py-1" title="View"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="btn btn-link text-dark py-1 border-start" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                                    <button class="btn btn-link text-primary py-1 border-start" title="Allocate"><i class="fa-solid fa-share-nodes"></i></button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><span class="text-muted small fw-bold">PROD-1024</span></td>
                                            <td class="fw-semibold text-dark">HDMI Cable 2.1 (2m)</td>
                                            <td><span class="badge bg-light text-dark border fw-normal">Accessories</span></td>
                                            <td class="text-center fw-bold text-danger">4</td>
                                            <td class="text-center text-muted small">15</td>
                                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal">Low Stock</span></td>
                                            <td class="text-dark small fw-medium">₱450.00</td>
                                            <td class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i>Bin 04</td>
                                            <td><span class="text-primary small fw-bold">New</span></td>
                                            <td class="text-truncate text-muted small pe-3" style="max-width: 150px;">Reorder requested</td>
                                            <td class="ps-3">
                                                <div class="btn-group btn-group-sm border rounded shadow-sm">
                                                    <button class="btn btn-link text-dark py-1" title="View"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="btn btn-link text-dark py-1 border-start" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                                    <button class="btn btn-link text-primary py-1 border-start" title="Allocate"><i class="fa-solid fa-share-nodes"></i></button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><span class="text-muted small fw-bold">PROD-2005</span></td>
                                            <td class="fw-semibold text-dark">Dell 24" Monitor SE2422H</td>
                                            <td><span class="badge bg-light text-dark border fw-normal">Monitors</span></td>
                                            <td class="text-center fw-bold text-muted">0</td>
                                            <td class="text-center text-muted small">5</td>
                                            <td><span class="badge bg-dark-subtle text-secondary border border-secondary-subtle fw-normal">Out of Stock</span></td>
                                            <td class="text-dark small fw-medium">₱7,800.00</td>
                                            <td class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i>Warehouse B</td>
                                            <td><span class="text-info small fw-bold">Refurbished</span></td>
                                            <td class="text-truncate text-muted small pe-3" style="max-width: 150px;">Awaiting supplier</td>
                                            <td class="ps-3">
                                                <div class="btn-group btn-group-sm border rounded">
                                                    <button class="btn btn-link text-dark py-1" title="View"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="btn btn-link text-dark py-1 border-start" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                                    <button class="btn btn-link text-primary py-1 border-start" title="Allocate"><i class="fa-solid fa-share-nodes"></i></button>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    
    </body>
</html>