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

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EDD Asset Issuance | Varay Inventory</title>

        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">

        <style>
            /* Custom EDD Theme Overrides */
            .edd-card { 
                border: none; 
                border-radius: 8px; 
                overflow: hidden;
            }

            .section-title { 
                font-weight: 800; 
                text-transform: uppercase; 
                letter-spacing: 1px; 
            }

            /* Table Styling from Reference */
            .table thead {
                background-color: #212529;
            }

            .table thead th {
                color: #adb5bd !important;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                padding: 15px;
                border: none;
                font-weight: 600;
            }

            .log-row { 
                border-bottom: 1px solid #f8f9fa;
                transition: background 0.2s;
            }
            
            .log-row:hover { background-color: #fcfcfc; }

            /* Departmental Tags */
            .dept-tag {
                background-color: #f8f9fa;
                color: #212529;
                font-weight: 700;
                padding: 4px 10px;
                font-size: 0.7rem;
                border: 1px solid #dee2e6;
                text-transform: uppercase;
                border-radius: 4px;
            }

            .edd-origin {
                background-color: #212529;
                color: #fff;
                padding: 4px 10px;
                font-size: 0.7rem;
                font-weight: 700;
                border-radius: 4px;
            }

            .qty-pill {
                background: #e9ecef;
                color: #212529;
                padding: 4px 12px;
                font-weight: 800;
                border-radius: 20px;
                font-size: 0.8rem;
            }

            .italic { font-style: italic; }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-lg-2">
                <?php include "../nav/sidebar_nav.php"; ?>
            </div>
            <div class="col">
                <div class="main-content">
                    <div class="container px-4 mt-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                            <div>
                                <h3 class="section-title text-dark mb-0">
                                    <i class="fa-solid fa-file-invoice me-2 text-dark"></i>EDD Asset Issuance
                                </h3>
                                <p class="text-muted small">Internal tracking of assets issued by the Electronic Data Department to Head Office units.</p>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-white border border-dark-subtle btn-sm">
                                    <i class="fa-solid fa-print me-1 text-muted"></i> Export Log
                                </button>
                                <a href="inventory.php" class="btn btn-dark btn-sm px-3">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold small text-uppercase text-muted">Filter By Dept:</span>
                                    <select class="form-select form-select-sm border-secondary-subtle" style="width: 180px;">
                                        <option>All Departments</option>
                                        <option>Accounting</option>
                                        <option>Human Resources</option>
                                        <option>Executive Office</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Date & Time</th>
                                            <th>Issuing Officer</th>
                                            <th>Item Specification</th>
                                            <th>Allocation Path</th>
                                            <th class="text-center">Qty</th>
                                            <th class="pe-4">Issuance Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="log-row">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">Jan 06, 2026</div>
                                                <small class="text-muted">10:30 AM</small>
                                            </td>
                                            <td>
                                                <span class="small fw-bold text-secondary">
                                                    <i class="fa-solid fa-user-gear me-1"></i> EDD_STAFF_01
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">Logitech Business Mouse B100</div>
                                                <small class="text-muted fw-bold">EDD-ITM-992</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="edd-origin">EDD</span>
                                                    <i class="fa-solid fa-chevron-right mx-2 text-muted" style="font-size: 0.7rem;"></i>
                                                    <span class="dept-tag shadow-sm">Accounting</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="qty-pill">05</span>
                                            </td>
                                            <td class="pe-4 small italic text-muted">Regular peripheral replacement for audit team.</td>
                                        </tr>

                                        <tr class="log-row">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">Jan 05, 2026</div>
                                                <small class="text-muted">02:15 PM</small>
                                            </td>
                                            <td>
                                                <span class="small fw-bold text-secondary">
                                                    <i class="fa-solid fa-user-gear me-1"></i> EDD_SUPVR
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">HP LaserJet Toner 85A</div>
                                                <small class="text-muted fw-bold">EDD-CON-411</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="edd-origin">EDD</span>
                                                    <i class="fa-solid fa-chevron-right mx-2 text-muted" style="font-size: 0.7rem;"></i>
                                                    <span class="dept-tag shadow-sm">Human Resources</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="qty-pill">02</span>
                                            </td>
                                            <td class="pe-4 small italic text-muted">Urgent request for recruitment documentation.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                                <div class="small text-muted">
                                    Total EDD Issuances: <b class="text-dark">482</b>
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item"><a class="page-link border-0 text-dark" href="#">Previous</a></li>
                                        <li class="page-item active"><a class="page-link border-0 bg-dark text-white shadow-sm" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link border-0 text-dark" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link border-0 text-dark" href="#">Next</a></li>
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