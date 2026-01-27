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

    // --- LOGIC: SEARCH & FILTERS ---
    $search = (isset($_GET['search'])) ? mysqli_real_escape_string($conn, (string)$_GET['search']) : '';
    $where_sql = "1=1";
    if($search !== '') {
        $where_sql = "(asset_id LIKE '%$search%' OR admin_username LIKE '%$search%' OR change_details LIKE '%$search%')";
    }

    // --- LOGIC: PAGINATION ---
    $limit = 15; 
    $count_query = "SELECT COUNT(*) as total FROM edit_log WHERE $where_sql";
    $total_res = mysqli_query($conn, $count_query);
    $total_rows = mysqli_fetch_assoc($total_res)['total'] ?? 0;
    
    $total_pages = max(1, ceil($total_rows / $limit));
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $start = ($page - 1) * $limit;

    // --- DATA FETCH ---
    $log_query = "SELECT * FROM edit_log WHERE $where_sql ORDER BY date_updated DESC LIMIT $start, $limit";
    $result_logs = mysqli_query($conn, $log_query);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Audit Logs | MVentory</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono&display=swap" rel="stylesheet">
        <style>
            :root { --mono-100: #ffffff; --mono-200: #f8f9fa; --mono-300: #dee2e6; --mono-400: #adb5bd; --mono-800: #212529; }
            body { background-color: #f4f4f4; font-size: 0.75rem; color: var(--mono-800); font-family: 'Inter', sans-serif; }
            
            .log-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
            .compact-card { background: #fff; border: 1px solid var(--mono-300); border-radius: 2px; }
            
            /* Typography */
            .header-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; }
            .mono-text { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; }
            
            /* Buttons */
            .btn-mono-outline { border: 1px solid var(--mono-800); color: var(--mono-800); background: transparent; font-weight: 700; font-size: 0.65rem; padding: 6px 12px; border-radius: 0; text-transform: uppercase; transition: 0.2s; text-decoration: none; }
            .btn-mono-outline:hover { background: var(--mono-800); color: #fff; }
            .btn-mono-dark { background: var(--mono-800); color: #fff; border: 1px solid var(--mono-800); font-weight: 700; font-size: 0.65rem; padding: 6px 12px; border-radius: 0; text-transform: uppercase; text-decoration: none; }

            /* Table Style */
            .table-compact thead th { background: var(--mono-800); color: var(--mono-400); font-size: 0.6rem; text-transform: uppercase; letter-spacing: 1px; padding: 12px; border: none; }
            .table-compact tbody td { padding: 10px 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
            .log-row:hover { background-color: #fafafa; }
            
            .badge-admin { border: 1px solid var(--mono-800); padding: 2px 6px; font-weight: 700; font-size: 0.6rem; color: var(--mono-800); }
            
            /* Custom Search */
            .search-box { border: none; border-bottom: 2px solid var(--mono-300); border-radius: 0; font-size: 0.75rem; font-weight: 600; padding: 5px 0; }
            .search-box:focus { box-shadow: none; border-color: var(--mono-800); outline: none; }
        </style>
    </head>
    <body>
        <div class="row g-0">
            <div class="col-lg-2"><?php include "../nav/sidebar_nav.php"; ?></div>
            <div class="col">
                <div class="log-container">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="header-title mb-1">Audit Logs</h3>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted small text-uppercase fw-bold">System Modification History</span>
                                <span class="badge bg-dark rounded-0 mono-text"><?= $total_rows ?> ENTRIES</span>
                            </div>
                        </div>
                        <a href="inventory.php" class="btn-mono-outline">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to Inventory
                        </a>
                    </div>

                    <div class="compact-card shadow-sm">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <form method="GET" class="d-flex align-items-center">
                                <i class="fa-solid fa-magnifying-glass text-muted me-2" style="font-size: 0.7rem;"></i>
                                <input type="text" name="search" class="search-box" placeholder="SEARCH LOGS..." value="<?= htmlspecialchars($search) ?>" style="width: 300px;">
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-compact mb-0">
                                <thead>
                                    <tr>
                                        <th width="60" class="text-center">ID</th>
                                        <th width="180">Date Updated</th>
                                        <th width="120">Administrator</th>
                                        <th width="100">Asset ID</th>
                                        <th>Activity Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($result_logs) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($result_logs)): ?>
                                        <tr class="log-row">
                                            <td class="text-center text-muted mono-text">#<?= $row['id'] ?></td>
                                            <td class="mono-text fw-bold"><?= $row['date_updated'] ?></td>
                                            <td><span class="badge-admin"><?= strtoupper(htmlspecialchars($row['admin_username'])) ?></span></td>
                                            <td class="fw-800 text-dark">ID:<?= $row['asset_id'] ?></td>
                                            <td class="text-muted"><?= htmlspecialchars($row['change_details']) ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-5 text-muted uppercase fw-bold">No Records Found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 bg-white border-top d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing page <strong><?= $page ?></strong> of <strong><?= $total_pages ?></strong>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if($page > 1): ?>
                                    <a href="?page=1&search=<?= $search ?>" class="btn-mono-outline" title="First"><i class="fa-solid fa-angles-left"></i></a>
                                    <a href="?page=<?= $page-1 ?>&search=<?= $search ?>" class="btn-mono-outline">Prev</a>
                                <?php endif; ?>

                                <?php 
                                    // Logic to show limited page numbers
                                    $start_loop = max(1, $page - 2);
                                    $end_loop = min($total_pages, $page + 2);
                                    for($i=$start_loop; $i<=$end_loop; $i++): 
                                ?>
                                    <a href="?page=<?= $i ?>&search=<?= $search ?>" class="<?= ($page == $i) ? 'btn-mono-dark' : 'btn-mono-outline' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if($page < $total_pages): ?>
                                    <a href="?page=<?= $page+1 ?>&search=<?= $search ?>" class="btn-mono-outline">Next</a>
                                    <a href="?page=<?= $total_pages ?>&search=<?= $search ?>" class="btn-mono-outline" title="Last"><i class="fa-solid fa-angles-right"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>