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

    // 1. FIXED TOTAL COUNT QUERY
    // We search the 'assets' table (a) for the Tag/Name and 'allocation_log' (al) for Assignees
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
        <style>
            body { background-color: #fbfbfb; font-size: 0.8rem; color: #212529; font-family: 'Inter', sans-serif; margin: 0; }
            .main-registry-container {max-width: 1200px; margin: 0 auto; }
            .edd-card { border: 1px solid #e9ecef; border-radius: 4px; background: #fff; overflow: hidden; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
            .edd-input-minimal {
                border: none; border-bottom: 2px solid #dee2e6;
                padding: 4px 5px 4px 25px; font-size: 0.75rem; font-weight: 600;
                background: transparent; width: 250px; transition: 0.2s;
            }
            .edd-input-minimal:focus { outline: none; border-bottom: 2px solid #212529; }
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important; text-transform: uppercase;
                font-size: 0.6rem; letter-spacing: 1px; padding: 15px 12px; border: none;
            }
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; cursor: pointer; }
            .log-row:hover { background-color: #fcfcfc; }
            .detail-drawer { background-color: #fdfdfd; border-left: 4px solid #212529; padding: 20px; }
            .detail-label { font-size: 0.6rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 2px; }
            .detail-value { font-size: 0.75rem; font-weight: 600; color: #212529; }
            .asset-id-cell { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; font-weight: 700; color: #212529; }
            .btn-reallocate-icon {
                display: inline-flex; align-items: center; justify-content: center;
                width: 32px; height: 32px; background-color: transparent;
                color: #212529; border: 1.5px solid #212529; border-radius: 50%;
                text-decoration: none; transition: all 0.2s ease-in-out; font-size: 0.8rem;
            }
            .btn-reallocate-icon:hover { background-color: #212529; color: #ffffff; transform: scale(1.1); }
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
                                            <th>Log Timestamp</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // 2. FIXED DATA FETCHING QUERY
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
                                                $drawer_id = "log_" . $row['id'];
                                        ?>
                                            <tr class="log-row">
                                                <td class="text-center" data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">
                                                    <i class="fa-solid fa-chevron-right btn-expand"></i>
                                                </td>
                                                <td class="asset-id-cell">
                                                    <span class="d-block"><?= htmlspecialchars($row['asset_tag'] ?? 'N/A') ?></span>
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
                                                    <div class="fw-bold" style="font-size: 0.7rem;"><?= htmlspecialchars($row['allocated_by']) ?></div>
                                                    <div class="text-muted" style="font-size: 0.6rem;">ADMINISTRATOR</div>
                                                </td>
                                                <td class="text-muted" style="font-size: 0.7rem;">
                                                    <span class="text-dark fw-bold"><?= date('M d, Y', strtotime($row['allocation_date'])) ?></span><br>
                                                    <small><?= date('h:i A', strtotime($row['allocation_date'])) ?></small>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <button type="button" class="btn-reallocate-icon" data-bs-toggle="modal" data-bs-target="#reallocateModal" 
                                                            data-bs-asset="<?= $row['asset_id'] ?>" data-bs-current="<?= $row['new_assignee'] ?>" data-bs-auth="<?= $row['allocated_by'] ?>">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            
                                            <tr class="collapse" id="<?= $drawer_id ?>">
                                                <td colspan="6" class="p-0 border-0">
                                                    <div class="detail-drawer">
                                                        <div class="row">
                                                            <div class="col-md-3 border-end">
                                                                <div class="detail-label">Model Ref</div>
                                                                <div class="detail-value"><?= htmlspecialchars($row['model'] ?? 'N/A') ?></div>
                                                            </div>
                                                            <div class="col-md-6 border-end">
                                                                <div class="detail-label">Database Audit</div>
                                                                <div class="detail-value text-muted" style="font-family: monospace; font-size: 0.65rem;">
                                                                    ID: <?= $row['id'] ?> | ASSET_ID_KEY: <?= $row['asset_id'] ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="detail-label">Log Integrity</div>
                                                                <div class="detail-value text-success"><i class="fa-solid fa-circle-check"></i> System Verified</div>
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
                const reallocateModal = document.getElementById('reallocateModal');
                if (reallocateModal) {
                    reallocateModal.addEventListener('show.bs.modal', event => {
                        const button = event.relatedTarget;
                        
                        // Extract info from data-bs-* attributes
                        const dbId = button.getAttribute('data-bs-asset'); // This is the numeric ID
                        const currentLoc = button.getAttribute('data-bs-current');
                        const authBy = button.getAttribute('data-bs-auth');

                        // Update the Modal's visual text
                        document.getElementById('display-asset-id').textContent = 'Asset ID: ' + dbId;
                        document.getElementById('display-current-loc').textContent = currentLoc;
                        document.getElementById('display-auth').textContent = authBy;

                        // CRITICAL: Fill the hidden input for the PHP script
                        document.getElementById('input-asset-id').value = dbId;
                        document.getElementById('input-old-location').value = currentLoc;
                    });
                }
            });
        </script>
    </body>
</html>