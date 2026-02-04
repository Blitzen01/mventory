<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }

    include "../render/connection.php";
    include "../src/cdn/cdn_links.php";
    include "../render/modals.php";

    /* ---------------- PAGINATION ---------------- */
    $limit = 25;
    $page  = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    /* ---------------- FILTERS ---------------- */
    $search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $log_type = isset($_GET['log_type']) ? mysqli_real_escape_string($conn, $_GET['log_type']) : '';

    /* ---------------- UNION QUERY ---------------- */
    $union_sql = "
        SELECT 
            'SYSTEM' AS type,
            `timestamp` AS log_date,
            CAST(IFNULL(changed_by, 'System') AS CHAR) AS user_id,
            IFNULL(action_type, 'ACTION') AS action,
            CONCAT(
                'Table: ', IFNULL(table_name, 'N/A'),
                ' | Record ID: ', IFNULL(record_id, 'N/A')
            ) AS details
        FROM system_audit_logs

        UNION ALL
        ...
    ";


    /* ---------------- WHERE CLAUSE ---------------- */
    $where = " WHERE 1=1 ";
    if ($search) {
        $where .= " AND (details LIKE '%$search%' OR action LIKE '%$search%')";
    }
    if ($log_type) {
        $where .= " AND type = '$log_type'";
    }

    /* ---------------- COUNT QUERY ---------------- */
    $count_sql = "
        SELECT COUNT(*) AS total
        FROM ($union_sql) AS logs
        $where
    ";

    $count_res = mysqli_query($conn, $count_sql) or die(mysqli_error($conn));
    $total_rows = mysqli_fetch_assoc($count_res)['total'];
    $total_pages = ceil($total_rows / $limit);

    /* ---------------- FINAL DATA QUERY ---------------- */
    $data_sql = "
        SELECT *
        FROM ($union_sql) AS logs
        $where
        ORDER BY log_date IS NULL, log_date DESC
        LIMIT $start, $limit
    ";

    $result = mysqli_query($conn, $data_sql) or die(mysqli_error($conn));
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Unified System Logs | Varay Inventory</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <style>
            body { background-color: #f8f9fa; }
            .edd-card { border: none; border-radius: 8px; overflow: hidden; background: #fff; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
            .filter-container { display: flex; align-items: center; gap: 25px; }
            .search-wrapper { position: relative; display: flex; align-items: center; }
            .search-wrapper i { position: absolute; left: 0; color: #adb5bd; font-size: 0.8rem; }
            .edd-input-minimal {
                border: none; border-bottom: 2px solid #dee2e6; border-radius: 0;
                padding: 5px 5px 5px 25px; font-size: 0.85rem; font-weight: 600;
                color: #212529; background: transparent; transition: border-color 0.2s; width: 280px;
            }
            .edd-input-minimal:focus { outline: none; border-bottom: 2px solid #212529; }
            .edd-select-minimal {
                border: 1px solid #dee2e6; border-radius: 4px; padding: 5px 12px;
                font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
                color: #495057; background-color: #f8f9fa; cursor: pointer;
            }
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important; text-transform: uppercase;
                font-size: 0.7rem; letter-spacing: 0.5px; padding: 15px; border: none;
            }
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; }
            .log-row:hover { background-color: #fcfcfc; }
            
            /* Type Badges */
            .type-badge { font-size: 0.65rem; font-weight: 800; padding: 4px 10px; border-radius: 4px; text-transform: uppercase; }
            .type-system { background: #e9ecef; color: #212529; border: 1px solid #212529; }
            .type-allocation { background: #212529; color: #ffffff; }
            .type-security { background: #dc3545; color: #ffffff; }
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
                                <h3 class="section-title text-dark mb-0">System Activity Logs</h3>
                                <p class="text-muted small">Consolidated Audit, Allocation, and Security Trails</p>
                            </div>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-4 border-bottom">
                                <form method="GET" class="filter-container">
                                    <div class="search-wrapper">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="text" name="search" class="edd-input-minimal" placeholder="SEARCH LOG DETAILS..." value="<?php echo htmlspecialchars($search); ?>">
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold small text-muted text-uppercase" style="font-size: 0.65rem;">Log Type:</span>
                                        <select name="log_type" class="edd-select-minimal" onchange="this.form.submit()">
                                            <option value="">All Streams</option>
                                            <option value="SYSTEM" <?php if($log_type == 'SYSTEM') echo 'selected'; ?>>System Audit</option>
                                            <option value="ALLOCATION" <?php if($log_type == 'ALLOCATION') echo 'selected'; ?>>Allocations</option>
                                            <option value="SECURITY" <?php if($log_type == 'SECURITY') echo 'selected'; ?>>Security</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Timestamp</th>
                                            <th>Stream</th>
                                            <th>Actor (User ID)</th>
                                            <th>Action</th>
                                            <th>Activity Details</th>
                                            <th class="pe-4 text-end">Trace</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <?php
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $badge_class = 'type-system';
                                                if($row['type'] == 'ALLOCATION') $badge_class = 'type-allocation';
                                                if($row['type'] == 'SECURITY') $badge_class = 'type-security';
                                                ?>
                                                <tr class="log-row">
                                                    <td class="ps-4">
                                                        <div class="fw-bold text-dark" style="font-size: 0.8rem;"><?php
                                                            echo $row['log_date']
                                                                ? date("M d, Y", strtotime($row['log_date']))
                                                                : 'N/A';
                                                            ?>
                                                        </div>
                                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo date("h:i:s A", strtotime($row['log_date'])); ?></small>
                                                    </td>
                                                    <td><span class="type-badge <?php echo $badge_class; ?>"><?php echo $row['type']; ?></span></td>
                                                    <td><span class="fw-bold text-dark">ID: <?php echo $row['user_id'] ?? 'N/A'; ?></span></td>
                                                    <td><span class="badge bg-light text-dark border fw-bold"><?php echo $row['action']; ?></span></td>
                                                    <td><div class="text-muted" style="font-size: 0.75rem; max-width: 400px;"><?php echo htmlspecialchars($row['details']); ?></div></td>
                                                    <td class="pe-4 text-end">
                                                        <button class="btn btn-link text-dark p-1"><i class="fa-solid fa-circle-info fa-sm"></i></button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center py-5 text-muted fw-bold'>NO LOG ENTRIES FOUND</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white py-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted fw-bold text-uppercase">Showing <?php echo number_format($total_rows); ?> Total Events</span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                                                <a class="page-link border-0 text-dark fw-bold" href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>&log_type=<?php echo $log_type; ?>">PREV</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link border-0 bg-dark text-white fw-bold"><?php echo $page; ?></a></li>
                                            <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                                                <a class="page-link border-0 text-dark fw-bold" href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>&log_type=<?php echo $log_type; ?>">NEXT</a>
                                            </li>
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