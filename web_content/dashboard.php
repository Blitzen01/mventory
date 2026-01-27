<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check authentication
    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    include "../render/connection.php"; 
    include "../src/cdn/cdn_links.php";
    include "../render/modals.php";
    include "../src/fetch/count_total_users_querry.php";

    // 1. Fetch Threshold percentage
    $threshold_query = "SELECT setting_value FROM system_settings WHERE setting_key = 'low_stock_threshold_percent' LIMIT 1";
    $threshold_result = $conn->query($threshold_query);
    $threshold_percent = ($threshold_result && $threshold_result->num_rows > 0) ? (int)$threshold_result->fetch_assoc()['setting_value'] : 10;

    // Define damage statuses once for all queries
    $damage_statuses = "'Disposal', 'Replacement', 'Warranty', 'Repairable', 'Damaged'";

    // 2. User Statistics
    $total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
    $online_count = $conn->query("SELECT COUNT(*) as online FROM users WHERE is_login = 1")->fetch_assoc()['online'] ?? 0;

    // 3. Asset Statistics
    $total_items = $conn->query("SELECT COUNT(*) as total_items FROM assets")->fetch_assoc()['total_items'] ?? 0;
    $total_value = $conn->query("SELECT SUM(cost) as total_value FROM assets")->fetch_assoc()['total_value'] ?? 0;

    // 4. Low Stock Logic 
    $low_stock_sql = "SELECT COUNT(*) as low_count FROM (
                        SELECT 
                            SUBSTRING_INDEX(asset_id, '-', 1) as group_name, 
                            COUNT(*) as total_group_count,
                            SUM(CASE WHEN assigned_to = 'EDD' AND condition_status NOT IN ($damage_statuses) THEN 1 ELSE 0 END) as edd_count
                        FROM assets 
                        GROUP BY group_name 
                        HAVING edd_count < (total_group_count * ($threshold_percent / 100))
                      ) as result";
    $low_stock_res = $conn->query($low_stock_sql);
    $low_stock_count = $low_stock_res->fetch_assoc()['low_count'] ?? 0;

    // 5. Damaged Items & Loss
    $damaged_result = $conn->query("SELECT COUNT(*) as damaged, SUM(cost) as loss FROM assets WHERE condition_status IN ($damage_statuses)");
    $damage_data = $damaged_result->fetch_assoc();
    $damaged_count = $damage_data['damaged'] ?? 0;
    $damage_loss = $damage_data['loss'] ?? 0;

    // 6. Critical Reorder Table Query
    $reorder_query = "SELECT 
                        SUBSTRING_INDEX(asset_id, '-', 1) as group_name, 
                        COUNT(*) as total_group_count,
                        SUM(CASE WHEN assigned_to = 'EDD' AND condition_status NOT IN ($damage_statuses) THEN 1 ELSE 0 END) as edd_count,
                        MIN(cost) as price
                      FROM assets 
                      GROUP BY group_name 
                      HAVING edd_count < (total_group_count * ($threshold_percent / 100))
                      ORDER BY edd_count ASC LIMIT 5";
    $reorder_list = $conn->query($reorder_query);

    // 7. Overall Stock Health
    $good_stock_query = "SELECT COUNT(*) as good_total FROM assets WHERE assigned_to = 'EDD' AND condition_status NOT IN ($damage_statuses)";
    $good_stock_count = $conn->query($good_stock_query)->fetch_assoc()['good_total'] ?? 0;
    $stock_health_percent = ($total_items > 0) ? round(($good_stock_count / $total_items) * 100) : 0;
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
        <div class="row m-0">
            <div class="col-lg-2 p-0">
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
                                                <div class="h4 m-0 fw-bold"><?php echo htmlspecialchars(sprintf("%02d", $total_users)); ?></div>
                                                <div class="extra-small text-muted text-uppercase" style="font-size: 0.6rem;">Total Registered</div>
                                            </div>
                                            <div class="col-6 ps-3">
                                                <div class="h4 m-0 fw-bold text-success">
                                                    <span class="status-pulse-dot"></span>
                                                    <?php echo htmlspecialchars(sprintf("%02d", $online_count)); ?>
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
                                            <div class="h4 m-0 fw-bold"><?php echo htmlspecialchars($total_items); ?></div>
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
                                            <div class="h4 m-0 fw-bold">₱<?php echo number_format($total_value, 2); ?></div>
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
                                            <div class="h4 m-0 fw-bold text-danger"><?php echo sprintf("%02d", $low_stock_count); ?></div>
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
                                            <div class="h4 m-0 fw-bold text-danger"><?php echo sprintf("%02d", $damaged_count); ?></div>
                                        </div>
                                        <i class="fa-solid fa-heart-crack fa-2x text-danger opacity-25"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card metric-card-stark h-100 py-2 shadow-sm border-0 rounded-0" style="background: #000;">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-label small text-uppercase fw-bold text-muted  mb-1" style="letter-spacing: 1px;">Damage Loss</div>
                                            <div class="h4 m-0 fw-bold text-danger">₱<?php echo number_format($damage_loss, 2); ?></div>
                                        </div>
                                        <i class="fa-solid fa-arrow-trend-down text-danger fa-2x opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="p-3 border-bottom bg-white d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                                        <div>
                                            <span class="small fw-bold text-uppercase letter-spacing">Overall Stock Health</span>
                                            <p class="text-muted m-0" style="font-size: 0.7rem;">Functional items in EDD vs Total Inventory</p>
                                        </div>
                                        
                                        <div class="d-flex align-items-center w-100 w-sm-50 mt-2 mt-sm-0">
                                            <div class="progress w-100 me-2" style="height: 12px; background-color: #e9ecef;">
                                                <?php 
                                                    $bar_color = "bg-success";
                                                    if ($stock_health_percent < 50) { $bar_color = "bg-danger"; }
                                                    elseif ($stock_health_percent < 80) { $bar_color = "bg-warning"; }
                                                ?>
                                                <div class="progress-bar <?php echo $bar_color; ?> progress-bar-striped progress-bar-animated" 
                                                     role="progressbar" 
                                                     style="width: <?php echo $stock_health_percent; ?>%" 
                                                     aria-valuenow="<?php echo $stock_health_percent; ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="small fw-bold <?php echo str_replace('bg-', 'text-', $bar_color); ?>">
                                                <?php echo $stock_health_percent; ?>%
                                            </span>
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
                                                        <th class="ps-4">Asset Group</th>
                                                        <th class="text-center">Available (EDD)</th>
                                                        <th class="text-center">Threshold</th>
                                                        <th class="text-center">Min Price</th>
                                                        <th class="text-end pe-4">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if($reorder_list && $reorder_list->num_rows > 0): ?>
                                                        <?php while($row = $reorder_list->fetch_assoc()): 
                                                            $required_min = ceil($row['total_group_count'] * ($threshold_percent / 100)); 
                                                        ?>
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="fw-bold text-dark text-uppercase"><?php echo htmlspecialchars($row['group_name']); ?></div>
                                                                <small class="text-muted">Total Stock: <?php echo $row['total_group_count']; ?> units</small>
                                                            </td>
                                                            <td class="text-center fw-bold text-danger"><?php echo $row['edd_count']; ?></td>
                                                            <td class="text-center">
                                                                <span class="text-muted small">Below <?php echo $required_min; ?> units</span><br>
                                                                <strong class="text-dark">(<?php echo $threshold_percent; ?>%)</strong>
                                                            </td>
                                                            <td class="text-center text-muted">₱<?php echo number_format($row['price'], 2); ?></td>
                                                            <td class="text-end pe-4">
                                                                <span class="badge bg-danger rounded-0">CRITICAL</span>
                                                            </td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr><td colspan="5" class="text-center py-3">No low stock detected.</td></tr>
                                                    <?php endif; ?>
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
                                        <div class="activity-item activity-success p-3 border-bottom">
                                            <div class="d-flex justify-content-between">
                                                <span class="small fw-bold">STOCK ADDED</span>
                                                <span class="text-muted" style="font-size: 10px;">2 mins ago</span>
                                            </div>
                                            <div class="text-dark small fw-bold">Keyboard Mechanical RGB</div>
                                            <div class="text-muted" style="font-size: 11px;">By Admin Name</div>
                                        </div>
                                        <div class="activity-item activity-danger p-3 border-bottom">
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
                                <button class="btn-action-stark shadow-sm mb-2 w-100" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="fa-solid fa-plus-circle fa-xl me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-bold small">Add Stock</div>
                                        <div class="text-muted small" style="font-size: 11px;">Restock existing inventory</div>
                                    </div>
                                </button>
                                <button class="btn-action-stark shadow-sm mb-2 w-100" data-bs-toggle="modal" data-bs-target="#adduserModal">
                                    <i class="fa-solid fa-user-plus fa-xl me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-bold small">Add Account</div>
                                        <div class="text-muted small" style="font-size: 11px;">Register new team member</div>
                                    </div>
                                </button>
                                <button class="btn-action-stark shadow-sm border-danger w-100">
                                    <i class="fa-solid fa-circle-exclamation text-danger fa-xl me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-bold text-danger small">Report Damage</div>
                                        <div class="text-muted small" style="font-size: 11px;">Log broken or expired items</div>
                                    </div>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
            // GENERATE USERNAME AND PASSWORD
            document.addEventListener('DOMContentLoaded', function() {
                const lastNameInput = document.getElementById('gen_last_name');
                const usernameDisplay = document.getElementById('display_username');
                const passwordDisplay = document.getElementById('display_password');

                // 1. Function to generate Username (LastName + Random Number)
                function updateUsername() {
                    const lastName = lastNameInput.value.trim().toLowerCase().replace(/\s+/g, '');
                    if (lastName) {
                        const randomNumber = Math.floor(1000 + Math.random() * 9000); // Generates 4-digit number
                        usernameDisplay.value = lastName + randomNumber;
                    } else {
                        usernameDisplay.value = "---";
                    }
                }

                // 2. Function to generate Random Password
                window.generateNewPassword = function() {
                    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                    let password = "";
                    for (let i = 0; i <= 10; i++) {
                        password += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    passwordDisplay.value = password;
                };

                // 3. Helper to copy to clipboard
                window.copyToClipboard = function(elementId, btn) {
                    const copyText = document.getElementById(elementId);
                    if (copyText.value === "---") return;
                    
                    navigator.clipboard.writeText(copyText.value);
                    
                    // Brief UI feedback
                    const icon = btn.querySelector('i');
                    icon.classList.replace('fa-copy', 'fa-check');
                    setTimeout(() => icon.classList.replace('fa-check', 'fa-copy'), 1500);
                };

                // Listen for typing in Last Name field
                lastNameInput.addEventListener('input', updateUsername);

                // Generate initial password when modal is opened or script loads
                generateNewPassword();
            });
        </script>
    </body>
</html>