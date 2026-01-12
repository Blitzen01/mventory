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
    include "../src/fetch/count_total_users_querry.php";

    // Count total users
    $total_query = "SELECT COUNT(*) as total FROM users";
    $total_result = $conn->query($total_query);
    $total_users = $total_result->fetch_assoc()['total'];

    // Count currently online users
    $online_query = "SELECT COUNT(*) as online FROM users WHERE is_login = 1";
    $online_result = $conn->query($online_query);
    $online_count = $online_result->fetch_assoc()['online'];
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Dashboard | Stock Focus</title>
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
                    <div class="container px-4 mt-5"> 
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <h3 class="fw-bold m-0 letter-spacing">SYSTEM OVERVIEW</h3>
                                <p class="text-muted small text-uppercase">Real-time inventory & activity status.</p>
                            </div>
                            
                            <div class="col-md-6">
                                <form action="search_results.php" method="GET" class="search-container-stark">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="text" name="query" id="liveSearch" class="search-input" placeholder="Search anything...">
                                    <div class="search-shortcut">/</div>
                                    <button type="submit" class="search-btn">Find</button>
                                </form>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 border-start border-dark border-4 rounded-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="metric-label small text-uppercase fw-bold text-muted" style="letter-spacing: 1px;">User Statistics</div>
                                            <i class="fa-solid fa-users-gear text-muted opacity-25"></i>
                                        </div>
                                        
                                        <div class="row align-items-center">
                                            <div class="col-6 border-end">
                                                <div class="h4 m-0 fw-bold"><?php echo htmlspecialchars($total_users); ?></div>
                                                <div class="extra-small text-muted text-uppercase" style="font-size: 0.6rem;">Total Registered</div>
                                            </div>
                                            
                                            <div class="col-6 ps-3">
                                                <div class="h4 m-0 fw-bold text-success">
                                                    <span class="status-pulse-dot"></span>
                                                    <?php echo htmlspecialchars($online_count); ?>
                                                </div>
                                                <div class="extra-small text-muted text-uppercase" style="font-size: 0.6rem;">Active Now</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 border-start border-dark border-4 rounded-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-label small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Total Items</div>
                                            <div class="h4 m-0 fw-bold">125</div>
                                        </div>
                                        <i class="fa-solid fa-boxes-stacked fa-2x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 border-start border-dark border-4 rounded-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-label small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Total Value</div>
                                            <div class="h4 m-0 fw-bold">₱1,250,000</div>
                                        </div>
                                        <i class="fa-solid fa-file-invoice-dollar fa-2x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 border-start border-danger border-4 rounded-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-label small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Low Stock Alerts</div>
                                            <div class="h4 m-0 fw-bold text-danger">08</div>
                                        </div>
                                        <i class="fa-solid fa-triangle-exclamation fa-2x text-danger opacity-25"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 border-start border-danger border-4 rounded-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-label small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Damaged Items</div>
                                            <div class="h4 m-0 fw-bold text-danger">45</div>
                                        </div>
                                        <i class="fa-solid fa-heart-crack fa-2x text-danger opacity-25"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 rounded-0" style="background: #000;">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-label small text-uppercase fw-bold text-dark opacity-50 mb-1" style="letter-spacing: 1px;">Damage Loss</div>
                                            <div class="h4 m-0 fw-bold text-danger">₱12,400</div>
                                        </div>
                                        <i class="fa-solid fa-chart-line-down fa-2x text-danger opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="p-3 border-bottom bg-white d-flex align-items-center justify-content-between">
                                        <span class="small fw-bold text-uppercase letter-spacing">Overall Stock Health</span>
                                        <div class="d-flex align-items-center w-50">
                                            <div class="progress w-100 me-2">
                                                <div class="progress-bar" style="width: 85%"></div>
                                            </div>
                                            <span class="small fw-bold">85%</span>
                                        </div>
                                    </div>
                                    <div class="card-header bg-white py-3 border-0">
                                        <h6 class="m-0 fw-bold text-uppercase small letter-spacing"><i class="fa-solid fa-bell me-2"></i>Critical Reorder List</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class="ps-4">Product Info</th>
                                                        <th class="text-center">Current</th>
                                                        <th class="text-center">Min.</th>
                                                        <th class="text-center">Est. Price</th>
                                                        <th class="text-end pe-4">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="fw-bold text-dark">Laptop Screen Replacement</div>
                                                            <small class="text-muted">SKU-99021</small>
                                                        </td>
                                                        <td class="text-center fw-bold text-danger">3</td>
                                                        <td class="text-center">10</td>
                                                        <td class="text-center text-muted">₱4,500.00</td>
                                                        <td class="text-end pe-4">
                                                            <span class="badge bg-dark rounded-0">REORDER NOW</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white py-3 border-bottom">
                                        <h6 class="m-0 fw-bold text-uppercase small letter-spacing">Recent Activity</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="activity-item activity-success">
                                            <div class="d-flex justify-content-between">
                                                <span class="small fw-bold">STOCK ADDED</span>
                                                <span class="text-muted" style="font-size: 10px;">2 mins ago</span>
                                            </div>
                                            <div class="text-dark small fw-bold">Keyboard Mechanical RGB</div>
                                            <div class="text-muted" style="font-size: 11px;">By Admin Name</div>
                                        </div>
                                        <div class="activity-item activity-danger">
                                            <div class="d-flex justify-content-between">
                                                <span class="small fw-bold text-danger">DAMAGE REPORTED</span>
                                                <span class="text-muted" style="font-size: 10px;">1 hour ago</span>
                                            </div>
                                            <div class="text-dark small fw-bold">Internal SSD 500GB</div>
                                            <div class="text-muted" style="font-size: 11px;">By Warehouse Staff</div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0 py-3 text-center">
                                        <a href="full_logs.php" class="small text-decoration-none fw-bold text-dark">VIEW ALL SYSTEM LOGS →</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-4">
                                <h6 class="fw-bold text-dark mb-3 text-uppercase small letter-spacing">Quick Operations</h6>
                                
                                <a href="add_stock.php" class="btn-action-stark shadow-sm">
                                    <i class="fa-solid fa-plus-circle fa-xl me-3"></i>
                                    <div>
                                        <div class="fw-bold small">Add Stock</div>
                                        <div class="text-muted small" style="font-size: 11px;">Restock existing inventory</div>
                                    </div>
                                </a>

                                <a href="add_user.php" class="btn-action-stark shadow-sm">
                                    <i class="fa-solid fa-user-plus fa-xl me-3"></i>
                                    <div>
                                        <div class="fw-bold small">Add Account</div>
                                        <div class="text-muted small" style="font-size: 11px;">Register new team member</div>
                                    </div>
                                </a>

                                <a href="report_damage.php" class="btn-action-stark shadow-sm border-danger">
                                    <i class="fa-solid fa-circle-exclamation text-danger fa-xl me-3"></i>
                                    <div>
                                        <div class="fw-bold text-danger small">Report Damage</div>
                                        <div class="text-muted small" style="font-size: 11px;">Log broken or expired items</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>