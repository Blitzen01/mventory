<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once "../render/connection.php";
    include "../src/cdn/cdn_links.php";
    include "../render/modals.php";

    date_default_timezone_set('Asia/Manila');

    // --- LOGIC: SEARCH & PAGINATION ---
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $total_query = "SELECT COUNT(*) as total 
                    FROM allocation_log al
                    LEFT JOIN assets a ON al.asset_id = a.id 
                    WHERE a.asset_id LIKE '%$search%' 
                       OR a.item_name LIKE '%$search%'
                       OR a.serial_number LIKE '%$search%'
                       OR al.new_assignee LIKE '%$search%'
                       OR al.previous_assignee LIKE '%$search%'";
                       
    $total_res = mysqli_query($conn, $total_query);
    $total_rows = mysqli_fetch_assoc($total_res)['total'];
    $total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Allocation Log | MVentory</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" href="../src/image/logo/varay_logo.png" type="image/png">
        <style>
            body { background-color: #fbfbfb; font-size: 0.8rem; color: #212529; font-family: 'Inter', sans-serif; margin: 0; }
            .main-registry-container {max-width: 1200px; margin: 0 auto; }
            .edd-card { border: 1px solid #e9ecef; border-radius: 4px; background: #fff; overflow: hidden; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
            
            /* Input Styling */
            .edd-input-minimal {
                border: none; border-bottom: 2px solid #dee2e6;
                padding: 4px 5px 4px 25px; font-size: 0.75rem; font-weight: 600;
                background: transparent; width: 250px; transition: 0.2s;
            }
            .edd-input-minimal:focus { outline: none; border-bottom: 2px solid #212529; }

            /* Table Styling */
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important; text-transform: uppercase;
                font-size: 0.6rem; letter-spacing: 1px; padding: 15px 12px; border: none;
            }
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; cursor: pointer; }
            .log-row:hover { background-color: #fcfcfc; }
            
            /* Drawer (Collapse) Styling */
            .detail-drawer { background-color: #f8f9fa; border-left: 4px solid #212529; padding: 1.5rem; margin: 10px; border-radius: 4px; }
            .detail-label { font-size: 0.6rem; font-weight: 800; color: #6c757d; text-transform: uppercase; margin-bottom: 2px; }
            .detail-value { font-size: 0.75rem; font-weight: 600; color: #212529; }
            
            /* Icons & Buttons */
            .btn-expand { transition: transform 0.3s ease; color: #adb5bd; }
            .log-row[aria-expanded="true"] .btn-expand { transform: rotate(90deg); color: #212529; }
            
            .btn-reallocate-icon {
                display: inline-flex; align-items: center; justify-content: center;
                width: 32px; height: 32px; background-color: transparent;
                color: #212529; border: 1.5px solid #212529; border-radius: 50%;
                text-decoration: none; transition: all 0.2s; font-size: 0.8rem;
            }
            .btn-reallocate-icon:hover { background-color: #212529; color: #ffffff; transform: scale(1.1); }
            
            .asset-id-cell { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; font-weight: 700; }
        </style>
    </head>

    <body>
        <div class="row g-0">
            <div class="col-lg-2">
                <?php include "../nav/sidebar_nav.php"; ?>
            </div>

            <div class="col">
                <div class="container-fluid px-4 mt-5">
                    <div class="main-registry-container">
                        
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <div>
                                <h3 class="section-title mb-0">Allocation Log</h3>
                                <p class="text-muted small mb-0">Audit Trail of Asset Assignments</p>
                            </div>
                            <a href="inventory.php" class="btn btn-dark btn-sm fw-bold px-4" style="font-size: 0.65rem; border-radius: 2px;">
                                <i class="fa-solid fa-arrow-left me-2"></i>RETURN TO INVENTORY
                            </a>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-3 border-bottom">
                                <form method="GET" class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="position-relative">
                                            <i class="fa-solid fa-magnifying-glass position-absolute" style="left:0; top:8px; color:#adb5bd; font-size:0.7rem;"></i>
                                            <input type="text" name="search" class="edd-input-minimal" placeholder="SEARCH ID, NAME, OR ASSIGNEE..." value="<?= htmlspecialchars($search) ?>">
                                        </div>
                                        <div class="d-flex gap-2 align-items-center border-start ps-3">
                                            <span class="detail-label mb-0">Show:</span>
                                            <select name="limit" class="border-0 fw-bold text-uppercase" style="font-size: 0.65rem; cursor: pointer; background: transparent;" onchange="this.form.submit()">
                                                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 Rows</option>
                                                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20 Rows</option>
                                                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 Rows</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th width="40"></th>
                                            <th width="180">Asset Identifier</th>
                                            <th>Movement Path (From > To)</th>
                                            <th>Authorized By</th>
                                            <th>Log At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT al.*, a.item_name, a.brand, a.model, a.asset_id as asset_tag
                                                FROM allocation_log al
                                                LEFT JOIN assets a ON al.asset_id = a.id 
                                                WHERE a.asset_id LIKE '%$search%' 
                                                   OR a.item_name LIKE '%$search%'
                                                   OR a.serial_number LIKE '%$search%'
                                                   OR al.new_assignee LIKE '%$search%'
                                                   OR al.previous_assignee LIKE '%$search%'
                                                ORDER BY al.allocation_date DESC 
                                                LIMIT $start, $limit";
                                        
                                        $result = mysqli_query($conn, $sql);
                                        
                                        if(mysqli_num_rows($result) > 0):
                                            while($row = mysqli_fetch_assoc($result)): 
                                                $drawer_id = "log_drawer_" . $row['id'];
                                        ?>
                                        
                                            <tr class="log-row" data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">
                                                <td class="text-center">
                                                    <i class="fa-solid fa-chevron-right btn-expand"></i>
                                                </td>
                                                <td class="asset-id-cell">
                                                    <span class="d-block text-dark"><?= htmlspecialchars($row['asset_tag'] ?? 'N/A') ?></span>
                                                    <small class="text-muted fw-normal" style="font-size: 0.6rem;">
                                                        <?= htmlspecialchars($row['brand'] . ' ' . $row['item_name']) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="text-muted"><?= htmlspecialchars($row['previous_assignee']) ?></span>
                                                        <i class="fa-solid fa-arrow-right-long" style="font-size: 0.6rem; color: #adb5bd;"></i>
                                                        <span class="fw-bold text-uppercase"><?= htmlspecialchars($row['new_assignee']) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size: 0.7rem;"><?= htmlspecialchars($row['allocated_by']) ?></div>
                                                    <div class="text-muted" style="font-size: 0.6rem;">ADMINISTRATOR</div>
                                                </td>
                                                <td class="text-muted" style="font-size: 0.7rem;">
                                                    <span class="text-dark fw-bold"><?= date('M d, Y', strtotime($row['allocation_date'])) ?></span><br>
                                                </td>
                                            </tr>
                                            
                                            <tr class="collapse-row">
                                                <td colspan="6" class="p-0 border-0">
                                                    <div class="collapse" id="<?= $drawer_id ?>">
                                                        <div class="detail-drawer">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-3 border-end">
                                                                    <div class="detail-label">Model Reference</div>
                                                                    <div class="detail-value"><?= htmlspecialchars($row['model'] ?? 'N/A') ?></div>
                                                                </div>
                                                                <div class="col-md-6 border-end px-4">
                                                                    <div class="detail-label">System Audit Trace</div>
                                                                    <div class="detail-value text-muted" style="font-family: 'JetBrains Mono', monospace; font-size: 0.65rem;">
                                                                        LOG_ID: <?= $row['id'] ?> | ASSET_UID: <?= $row['asset_id'] ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="detail-label">Verification Status</div>
                                                                    <div class="detail-value text-success"><i class="fa-solid fa-circle-check me-1"></i> Log Verified</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; else: ?>
                                            <tr><td colspan="6" class="text-center py-5 text-muted fw-bold">NO RECORDS MATCHING "<?= htmlspecialchars($search) ?>"</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white py-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="detail-label mb-0" style="color: #212529;">
                                        Showing <?= ($start + 1) ?> to <?= min($start + $limit, $total_rows) ?> of <?= $total_rows ?> entries
                                    </span>
                                    <div class="btn-group shadow-sm">
                                        <a href="?page=<?= max(1, $page-1) ?>&search=<?= $search ?>&limit=<?= $limit ?>" 
                                        class="btn btn-outline-dark btn-sm fw-bold <?= ($page <= 1) ? 'disabled' : '' ?>" style="font-size: 0.65rem;">PREV</a>
                                        <a href="?page=<?= min($total_pages, $page+1) ?>&search=<?= $search ?>&limit=<?= $limit ?>" 
                                        class="btn btn-outline-dark btn-sm fw-bold <?= ($page >= $total_pages) ? 'disabled' : '' ?>" style="font-size: 0.65rem;">NEXT</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle Reallocate Modal Data
                const reallocateModal = document.getElementById('reallocateModal');
                if (reallocateModal) {
                    reallocateModal.addEventListener('show.bs.modal', event => {
                        const button = event.relatedTarget;
                        const dbId = button.getAttribute('data-bs-asset');
                        const currentLoc = button.getAttribute('data-bs-current');
                        const authBy = button.getAttribute('data-bs-auth');

                        document.getElementById('display-asset-id').textContent = 'Asset ID: ' + dbId;
                        document.getElementById('display-current-loc').textContent = currentLoc;
                        document.getElementById('display-auth').textContent = authBy;

                        document.getElementById('input-asset-id').value = dbId;
                        document.getElementById('input-old-location').value = currentLoc;
                    });
                }

                // Prevent Drawer from opening when clicking the Action button
                const stopPropBtns = document.querySelectorAll('.stop-prop');
                stopPropBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

                // Visual feedback for search
                const searchInput = document.querySelector('input[name="search"]');
                searchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        document.querySelector('.table-responsive').style.opacity = '0.5';
                    }
                });
            });
        </script>
    </body>
</html>