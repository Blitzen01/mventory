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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Damage Management | Display</title>
        
        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">

        <style>
            /* Modern accent for rows */
            .pending-row { border-left: 4px solid #ffc107 !important; }
            .completed-row { border-left: 4px solid #198754 !important; }
            
            .main-content { background-color: #f8f9fa; min-height: 100vh; }
            .table thead th { font-size: 11px; letter-spacing: 0.5px; }
        </style>
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
                                    <i class="fa-solid fa-screwdriver-wrench me-2 text-dark"></i>Damage Management
                                </h3>
                                <p class="text-muted small">Viewing logged damages and repair status</p>
                            </div>
                            <div>
                                <button class="btn btn-primary shadow-sm">
                                    <i class="fa-solid fa-print"></i> Generate Report
                                </button>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-body bg-white rounded">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label class="small fw-bold text-muted mb-1">PRODUCT</label>
                                        <select class="form-select bg-light">
                                            <option>Select product...</option>
                                            <option>Logitech G-Pro Wireless</option>
                                            <option>Dell 24" Monitor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="small fw-bold text-muted mb-1">QTY</label>
                                        <input type="number" class="form-control bg-light" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small fw-bold text-muted mb-1">ACTION</label>
                                        <select class="form-select bg-light">
                                            <option>Repair</option>
                                            <option>Replace</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold text-muted mb-1">REASON</label>
                                        <input type="text" class="form-control bg-light" placeholder="Describe the damage...">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-dark w-100">Log Damage</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="ps-3 text-muted">PRODUCT DETAILS</th>
                                            <th class="text-muted text-center">QTY</th>
                                            <th class="text-muted">TYPE</th>
                                            <th class="text-muted">STATUS</th>
                                            <th class="text-muted">REMARKS</th>
                                            <th class="pe-3 text-muted">MANAGE</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="pending-row">
                                            <td class="ps-3">
                                                <div class="fw-semibold text-dark">Logitech G-Pro Wireless</div>
                                                <small class="text-muted fw-bold" style="font-size: 10px;">PROD-1001</small>
                                            </td>
                                            <td class="text-center fw-bold">2</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle fw-normal">
                                                    <i class="fa-solid fa-screwdriver-wrench me-1"></i>REPAIR
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small fw-bold text-warning">
                                                    <i class="fa-solid fa-clock me-1"></i>PENDING
                                                </div>
                                            </td>
                                            <td class="text-muted small">Left click double clicking</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success rounded-pill px-3">Resolve</button>
                                            </td>
                                        </tr>

                                        <tr class="completed-row">
                                            <td class="ps-3">
                                                <div class="fw-semibold text-dark">HDMI Cable 2.1</div>
                                                <small class="text-muted fw-bold" style="font-size: 10px;">PROD-1024</small>
                                            </td>
                                            <td class="text-center fw-bold">1</td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fw-normal">
                                                    <i class="fa-solid fa-arrows-rotate me-1"></i>REPLACE
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small fw-bold text-success">
                                                    <i class="fa-solid fa-check-circle me-1"></i>COMPLETED
                                                </div>
                                            </td>
                                            <td class="text-muted small">Defective pin upon arrival</td>
                                            <td>
                                                <span class="badge bg-light text-success border"><i class="fa-solid fa-check"></i> Fixed</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 border-0">
                                <div class="text-muted small">Showing 1 to 2 of 2 records</div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled"><a class="page-link">Previous</a></li>
                                        <li class="page-item active"><a class="page-link">1</a></li>
                                        <li class="page-item disabled"><a class="page-link">Next</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>