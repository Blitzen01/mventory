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
        <title>System Activity Log</title>

        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/<?php echo $settings_app_logo; ?>">

        <style>
            /* Professional EDD System Theme (Grayscale) */
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

            /* System Specific Tags */
            .event-tag {
                background-color: #f8f9fa;
                color: #212529;
                font-weight: 700;
                padding: 4px 10px;
                font-size: 0.65rem;
                border: 1px solid #dee2e6;
                text-transform: uppercase;
                border-radius: 4px;
            }

            .user-origin {
                background-color: #212529;
                color: #fff;
                padding: 4px 10px;
                font-size: 0.7rem;
                font-weight: 700;
                border-radius: 4px;
            }

            .ip-pill {
                background: #e9ecef;
                color: #495057;
                padding: 2px 8px;
                font-family: 'Courier New', Courier, monospace;
                font-weight: 700;
                border-radius: 4px;
                font-size: 0.75rem;
            }

            .severity-indicator {
                height: 10px;
                width: 10px;
                border-radius: 2px;
                display: inline-block;
                margin-right: 5px;
                background-color: #ced4da; /* Default */
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
                                    <i class="fa-solid fa-terminal me-2 text-dark"></i>System Activity Log
                                </h3>
                                <p class="text-muted small">Real-time audit trail of user actions, system changes, and authentication events.</p>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-white border border-dark-subtle btn-sm">
                                    <i class="fa-solid fa-download me-1 text-muted"></i> Archive Logs
                                </button>
                                <a href="dashboard.php" class="btn btn-dark btn-sm px-3">
                                    <i class="fa-solid fa-house me-1"></i> Home
                                </a>
                            </div>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold small text-uppercase text-muted">Log Level:</span>
                                    <select class="form-select form-select-sm border-secondary-subtle" style="width: 180px;">
                                        <option>All Activities</option>
                                        <option>Security Events</option>
                                        <option>Inventory Updates</option>
                                        <option>System Errors</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Timestamp</th>
                                            <th>Account / IP</th>
                                            <th>Action Performed</th>
                                            <th>Module Affected</th>
                                            <th class="text-center">Severity</th>
                                            <th class="pe-4">Trace Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="log-row">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">Jan 06, 2026</div>
                                                <small class="text-muted">17:10:45</small>
                                            </td>
                                            <td>
                                                <div class="user-origin d-inline-block mb-1">ADMIN_EDD</div>
                                                <div><span class="ip-pill">192.168.1.45</span></div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">Modified Asset Qty</div>
                                                <small class="text-muted fw-bold small">ID: #INV-9902</small>
                                            </td>
                                            <td>
                                                <span class="event-tag shadow-sm">Inventory_Core</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="severity-indicator" style="background-color: #212529;"></span>
                                                <small class="fw-bold">INFO</small>
                                            </td>
                                            <td class="pe-4 small italic text-muted">Stock adjusted from 10 to 15 (Manual Override).</td>
                                        </tr>

                                        <tr class="log-row">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">Jan 06, 2026</div>
                                                <small class="text-muted">16:55:12</small>
                                            </td>
                                            <td>
                                                <div class="user-origin d-inline-block mb-1">BR_MGR_01</div>
                                                <div><span class="ip-pill">10.22.4.112</span></div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">Login Successful</div>
                                                <small class="text-muted fw-bold small">Session: _auth882</small>
                                            </td>
                                            <td>
                                                <span class="event-tag shadow-sm">Auth_Gateway</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="severity-indicator" style="background-color: #adb5bd;"></span>
                                                <small class="fw-bold">LOW</small>
                                            </td>
                                            <td class="pe-4 small italic text-muted">Standard branch portal access granted.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                                <div class="small text-muted">
                                    Daily Log Count: <b class="text-dark">1,102 Records</b>
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item"><a class="page-link border-0 text-dark" href="#">Previous</a></li>
                                        <li class="page-item active"><a class="page-link border-0 bg-dark text-white shadow-sm" href="#">1</a></li>
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