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

    // --- LOGIC: SEARCH & PAGINATION ---
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

    // Expanded Where Clause to check across related tables
    $where_clause = "";
    if (!empty($search)) {
        $where_clause = " WHERE (sl.ip_address LIKE '%$search%' 
                          OR sl.status LIKE '%$search%' 
                          OR sl.browser LIKE '%$search%' 
                          OR u.first_name LIKE '%$search%' 
                          OR u.last_name LIKE '%$search%' 
                          OR u.email LIKE '%$search%') ";
    }

    // Get total rows using JOIN to ensure search counts are accurate
    $total_query = "SELECT COUNT(*) AS total 
                    FROM security_logs sl 
                    LEFT JOIN users u ON sl.user_id = u.user_id 
                    $where_clause";
    $total_result = mysqli_query($conn, $total_query);
    $total_rows = mysqli_fetch_assoc($total_result)['total'];
    $pages = ceil($total_rows / $limit);

    // Main Query: Fetching log and user data together
    $log_query = "SELECT sl.*, u.first_name, u.last_name, u.profile_image 
                  FROM security_logs sl 
                  LEFT JOIN users u ON sl.user_id = u.user_id 
                  $where_clause 
                  ORDER BY sl.login_time DESC 
                  LIMIT $start, $limit";
    $log_result = mysqli_query($conn, $log_query);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Security Logs | Varay Inventory</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">

        <style>
            .edd-card { border: none; border-radius: 8px; overflow: hidden; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                padding: 15px;
                border: none;
                font-weight: 600;
            }
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; }
            .log-row:hover { background-color: #fcfcfc; }
            .user-avatar {
                width: 32px; height: 32px;
                object-fit: cover;
                border-radius: 50%;
                border: 1px solid #dee2e6;
            }
            .ip-tag {
                background-color: #212529;
                color: #fff;
                padding: 4px 10px;
                font-size: 0.7rem;
                font-weight: 700;
                border-radius: 4px;
                font-family: monospace;
            }
            .status-badge {
                font-size: 0.65rem;
                font-weight: 700;
                text-transform: uppercase;
                padding: 4px 8px;
                border-radius: 4px;
                background: #ffffff;
                border: 1px solid #dee2e6;
            }
            .status-success { color: #198754; border-color: #198754; }
            .status-failed { color: #dc3545; border-color: #dc3545; }
            .page-link { border: none !important; color: #212529; }
            .page-item.active .page-link { background-color: #212529 !important; color: white !important; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="row g-0">
            <div class="col-lg-2">
                <?php include "../nav/sidebar_nav.php"; ?>
            </div>
            <div class="col">
                <div class="main-content">
                    <div class="container px-4 mt-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                            <div>
                                <h3 class="section-title text-dark mb-0">
                                    <i class="fa-solid fa-shield-halved me-2 text-dark"></i>Security Logs
                                </h3>
                                <p class="text-muted small">Monitoring system access and authentication attempts.</p>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-white border border-dark-subtle btn-sm">
                                    <i class="fa-solid fa-print me-1 text-muted"></i> Export
                                </button>
                                <a href="dashboard.php" class="btn btn-dark btn-sm px-3">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                                <form action="" method="GET" class="d-flex align-items-center gap-2">
                                    <span class="fw-bold small text-uppercase text-muted">Filter Logs:</span>
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control border-secondary-subtle" placeholder="Search name, IP, device..." value="<?php echo htmlspecialchars($search); ?>">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                    <?php if(!empty($search)): ?>
                                        <a href="security_logs.php" class="btn btn-link btn-sm text-decoration-none text-muted small">Clear</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Timestamp</th>
                                            <th>User Identity</th>
                                            <th>IP Address</th>
                                            <th>Device Details</th>
                                            <th class="text-center">Status</th>
                                            <th class="pe-4 text-center">Session</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <?php if(mysqli_num_rows($log_result) > 0): ?>
                                            <?php while($log = mysqli_fetch_assoc($log_result)): 
                                                // Data handled by SQL JOIN for better performance
                                                $name = ($log['first_name']) ? $log['first_name'].' '.$log['last_name'] : "System/Guest";
                                                $img = (!empty($log['profile_image'])) ? $log['profile_image'] : "../src/image/profiles/default.png";
                                            ?>
                                            <tr class="log-row">
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark"><?php echo date('M d, Y', strtotime($log['login_time'])); ?></div>
                                                    <small class="text-muted"><?php echo date('h:i A', strtotime($log['login_time'])); ?></small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo $img; ?>" class="user-avatar me-2" onerror="this.src='../src/image/profiles/default.png'">
                                                        <div>
                                                            <div class="fw-bold text-dark small"><?php echo htmlspecialchars($name); ?></div>
                                                            <small class="text-muted" style="font-size: 0.65rem;">UID: #<?php echo $log['user_id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="ip-tag"><?php echo $log['ip_address']; ?></span></td>
                                                <td>
                                                    <small class="text-muted d-block text-truncate" style="max-width: 180px;" title="<?php echo htmlspecialchars($log['browser']); ?>">
                                                        <?php echo htmlspecialchars($log['browser']); ?>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <?php $isSuccess = (strtolower($log['status']) == 'success'); ?>
                                                    <span class="status-badge <?php echo $isSuccess ? 'status-success' : 'status-failed'; ?>">
                                                        <?php echo $log['status']; ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-center">
                                                    <span class="badge rounded-pill <?php echo $log['is_active'] ? 'bg-success' : 'bg-light text-muted border'; ?>" style="font-size: 0.6rem;">
                                                        <?php echo $log['is_active'] ? 'Active' : 'Offline'; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center py-5 text-muted">No security records found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                                <div class="small text-muted">
                                    Showing <b class="text-dark"><?php echo mysqli_num_rows($log_result); ?></b> of <b class="text-dark"><?php echo $total_rows; ?></b> logs
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                                        </li>
                                        <?php for($i = 1; $i <= $pages; $i++): ?>
                                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                                        </li>
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