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
        <title>Asset Distribution | Inventory System</title>

        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">
    </head>
    <body>
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
                                    <i class="fa-solid fa-map-location-dot me-2 text-dark"></i>Asset Distribution
                                </h3>
                                <p class="text-muted small">Track and manage items currently deployed to Head Office and Store locations.</p>
                            </div>
                            <div class="btn-group shadow-sm">
                                <a href="inventory.php" class="btn btn-white border border-dark-subtle">
                                    <i class="fa-solid fa-arrow-left me-1 text-muted"></i> Back to Inventory
                                </a>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="ps-3 text-muted">PRODUCT & SKU</th>
                                            <th class="text-muted">TYPE</th>
                                            <th class="text-muted">CURRENT LOCATION</th>
                                            <th class="text-center text-muted">QTY DEPLOYED</th>
                                            <th class="text-muted">DATE DEPLOYED</th>
                                            <th class="text-center pe-3 text-muted">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark">Logitech G-Pro Wireless</div>
                                                <small class="text-muted fw-bold">PROD-1001</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fw-normal">Peripherals</span>
                                            </td>
                                            <td>
                                                <i class="fa-solid fa-location-dot text-danger me-1"></i> 
                                                <span class="fw-semibold">Head Office - IT Dept</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-primary px-3">5</span>
                                            </td>
                                            <td class="small text-muted">Jan 02, 2026</td>
                                            <td class="text-center pe-3">
                                                <button class="btn btn-sm btn-outline-danger px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#returnModal">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Return to Stock
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark">HDMI Cable 2.1 (2m)</div>
                                                <small class="text-muted fw-bold">PROD-1024</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fw-normal">Accessories</span>
                                            </td>
                                            <td>
                                                <i class="fa-solid fa-location-dot text-danger me-1"></i> 
                                                <span class="fw-semibold">Retail Store - Branch A</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-primary px-3">12</span>
                                            </td>
                                            <td class="small text-muted">Dec 20, 2025</td>
                                            <td class="text-center pe-3">
                                                <button class="btn btn-sm btn-outline-danger px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#returnModal">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Return to Stock
                                                </button>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-white border-top py-3 text-muted small">
                                Total active allocations: <b>2</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>