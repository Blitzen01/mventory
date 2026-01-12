<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "../render/connection.php"; 
    include "../src/cdn/cdn_links.php";
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Unified Master Log | Varay Inventory</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <style>
            /* PROFESSIONAL UI OVERRIDES */
            .edd-card { border: none; border-radius: 8px; overflow: hidden; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

            /* Modern Professional Search & Filter */
            .filter-container {
                display: flex;
                align-items: center;
                gap: 25px;
            }

            .search-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .search-wrapper i {
                position: absolute;
                left: 0;
                color: #adb5bd;
                font-size: 0.8rem;
            }

            .edd-input-minimal {
                border: none;
                border-bottom: 2px solid #dee2e6;
                border-radius: 0;
                padding: 5px 5px 5px 25px;
                font-size: 0.85rem;
                font-weight: 600;
                color: #212529;
                background: transparent;
                transition: border-color 0.2s;
                width: 250px;
            }

            .edd-input-minimal:focus {
                outline: none;
                border-bottom: 2px solid #212529;
            }

            .edd-select-minimal {
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 5px 12px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                color: #495057;
                background-color: #f8f9fa;
                cursor: pointer;
            }

            .edd-select-minimal:hover { background-color: #f1f3f5; }

            /* Table Styles */
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important;
                text-transform: uppercase;
                font-size: 0.7rem;
                letter-spacing: 0.5px;
                padding: 15px;
                border: none;
            }

            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; }
            .log-row:hover { background-color: #fcfcfc; }

            /* Category Tags */
            .cat-badge {
                font-size: 0.65rem;
                font-weight: 800;
                padding: 4px 10px;
                border-radius: 4px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .cat-branch { background: #f1f3f5; color: #495057; border: 1px solid #dee2e6; }
            .cat-ho { background: #ffffff; color: #212529; border: 1px solid #212529; }
            .cat-system { background: #212529; color: #ffffff; }

            .qty-pill { background: #e9ecef; color: #212529; padding: 3px 10px; font-weight: 800; border-radius: 20px; font-size: 0.75rem; }
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
                                <h3 class="section-title text-dark mb-0">Unified Master Log</h3>
                                <p class="text-muted small">Centralized Audit Trail | Varay Inventory Systems</p>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-dark btn-sm fw-bold border-2">
                                    <i class="fa-solid fa-file-export me-1"></i> EXPORT REPORT
                                </button>
                            </div>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-4 border-bottom">
                                <div class="filter-container">
                                    <div class="search-wrapper">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="text" class="edd-input-minimal" placeholder="SEARCH REFERENCE OR ASSET...">
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold small text-muted text-uppercase" style="font-size: 0.65rem;">Log Category:</span>
                                        <select class="edd-select-minimal">
                                            <option>All Activity</option>
                                            <option>Branch Deployment</option>
                                            <option>H.O. Issuance</option>
                                            <option>Inventory</option>
                                            <option>System Events</option>
                                        </select>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 border-start ps-4">
                                        <span class="fw-bold small text-muted text-uppercase" style="font-size: 0.65rem;">Sort By:</span>
                                        <select class="edd-select-minimal">
                                            <option>Newest First</option>
                                            <option>Oldest First</option>
                                            <option>Quantity (High)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Timestamp</th>
                                            <th>Category</th>
                                            <th>Action By</th>
                                            <th>Asset Description</th>
                                            <th>Allocation Target</th>
                                            <th class="text-center">Qty</th>
                                            <th class="pe-4">Summary</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="log-row">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">06-JAN-2026</div>
                                                <small class="text-muted">17:10:02</small>
                                            </td>
                                            <td><span class="cat-badge cat-branch">Branch</span></td>
                                            <td><span class="fw-bold text-secondary small">EDD_STAFF_A</span></td>
                                            <td>
                                                <div class="fw-bold text-dark">Zebra Thermal Printer</div>
                                                <code class="text-dark small">REF-BR-901</code>
                                            </td>
                                            <td><span class="text-dark fw-bold small">BRANCH A (DOWNTOWN)</span></td>
                                            <td class="text-center"><span class="qty-pill">01</span></td>
                                            <td class="pe-4 small text-muted">Deployed for store label updates.</td>
                                        </tr>
                                        <tr class="log-row">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">06-JAN-2026</div>
                                                <small class="text-muted">16:45:12</small>
                                            </td>
                                            <td><span class="cat-badge cat-system">System</span></td>
                                            <td><span class="fw-bold text-danger small">SYSTEM_AUTO</span></td>
                                            <td>
                                                <div class="fw-bold text-dark">Database Sync</div>
                                                <code class="text-dark small">TASK-ID-8821</code>
                                            </td>
                                            <td><span class="text-muted fw-bold small">CORE_ENGINE</span></td>
                                            <td class="text-center"><span class="text-muted">--</span></td>
                                            <td class="pe-4 small text-muted">Routine index maintenance completed.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted fw-bold text-uppercase">Showing 1-50 of 12,450 Records</span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item"><a class="page-link border-0 text-dark fw-bold" href="#">PREV</a></li>
                                            <li class="page-item active"><a class="page-link border-0 bg-dark text-white" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link border-0 text-dark fw-bold" href="#">NEXT</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>