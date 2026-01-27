<?php
// 1. ABSOLUTE TOP: Session and Redirect Logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

// 2. Database and Timezone
include "../render/connection.php"; 
date_default_timezone_set('Asia/Manila');

// 3. PROCESS UPDATE (Must be before ANY HTML or includes)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_asset'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    $admin_user = $_SESSION['username'] ?? "System Admin";
    $currentDateTime = date('m/d/Y h:i A');

    // Fetch current remarks to append new log (Reference logic)
    $fetch_query = mysqli_query($conn, "SELECT condition_status, remarks FROM assets WHERE id = '$id' LIMIT 1");
    $row = mysqli_fetch_assoc($fetch_query);
    
    $old_status = $row['condition_status'] ?? 'UNKNOWN';
    $existing_logs = $row['remarks'] ?? '';
    
    // Create the history entry
    $log_entry = "[$currentDateTime] RESOLVED: Changed from '$old_status' to '$new_status' by $admin_user.";
    $combined_remarks = mysqli_real_escape_string($conn, $log_entry . PHP_EOL . $existing_logs);

    // Update the database
    $update_sql = "UPDATE assets SET 
                   condition_status = '$new_status', 
                   remarks = '$combined_remarks' 
                   WHERE id = '$id'";
    
    if (mysqli_query($conn, $update_sql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=updated");
        exit();
    }
}

// 4. INCLUDES THAT MIGHT CONTAIN HTML
include "../src/cdn/cdn_links.php";
include "../render/modals.php";

// 5. Pagination & Search Logic
$search = $_GET['search'] ?? '';
$limit  = $_GET['limit'] ?? 10;
$page   = $_GET['page'] ?? 1;
$start  = ($page - 1) * $limit;

$where_clause = "(condition_status LIKE '%DEFECTIVE%' OR condition_status LIKE '%REPAIR%' OR condition_status LIKE '%REPLACEMENT%' OR condition_status LIKE '%DISPOSAL%')
    AND (asset_id LIKE '%$search%' OR item_name LIKE '%$search%' OR assigned_to LIKE '%$search%')";

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM assets WHERE $where_clause");
$total_rows = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Damage Management</title>
    <link rel="stylesheet" href="../src/style/main_style.css">
    <link rel="stylesheet" href="../src/style/custom_styles.css">
    <link rel="icon" type="image/png" href="../src/assets/mventory_favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        body { background-color: #fbfbfb; font-size: 0.8rem; color: #212529; font-family: 'Inter', sans-serif; margin: 0; }
        .main-registry-container { max-width: 1200px; margin: 0 auto; }
        .edd-card { border: 1px solid #e9ecef; border-radius: 4px; background: #fff; overflow: hidden; }
        .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

        .edd-input-minimal {
            border: none; border-bottom: 2px solid #dee2e6;
            padding: 4px 5px 4px 25px; font-size: 0.75rem; font-weight: 600;
            background: transparent; width: 220px; transition: 0.2s;
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

        .btn-expand { color: #adb5bd; transition: 0.3s; font-size: 0.7rem; }
        .log-row:not(.collapsed) .btn-expand { transform: rotate(90deg); color: #212529; }

        .badge-status { font-size: 0.55rem; padding: 3px 8px; border-radius: 2px; font-weight: 800; }

        .btn-resolve-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; background-color: transparent;
            color: #212529; border: 1.5px solid #212529; border-radius: 50%;
            transition: all 0.2s; font-size: 0.8rem; cursor: pointer;
        }
        .btn-resolve-icon:hover { background-color: #212529; color: #fff; transform: scale(1.1); }
    </style>
</head>

<body>
    <div class="modal fade" id="resolveModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST">
                    <div class="modal-header py-2 px-3 bg-dark text-white">
                        <h5 class="modal-title" style="font-size: 0.75rem; font-weight: 800;">RESOLVE ASSET ISSUE</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id" id="modal_asset_id_hidden">
                        <p class="mb-3" style="font-size: 0.75rem;">Updating status for: <strong id="modal_asset_display"></strong></p>
                        
                        <label class="detail-label">Update Condition To:</label>
                        <select name="new_status" class="form-select form-select-sm fw-bold border-2">
                            <option value="GOOD">GOOD (Fixed / Returned to Stock)</option>
                            <option value="REPAIR">REPAIR (Still in maintenance)</option>
                            <option value="DISPOSAL">DISPOSAL (Final Write-off)</option>
                        </select>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal" style="font-size:0.65rem;">CANCEL</button>
                        <button type="submit" name="resolve_asset" class="btn btn-dark btn-sm fw-bold px-4" style="font-size:0.65rem;">UPDATE STATUS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-0">
        <div class="col-lg-2"><?php include "../nav/sidebar_nav.php"; ?></div>

        <div class="col">
            <div class="container-fluid px-4 mt-5">
                <div class="main-registry-container">
                    
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div>
                            <h3 class="section-title mb-0">Damage Management</h3>
                            <p class="text-muted small mb-0">Audit and Resolve Hardware Discrepancies</p>
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
                                        <input type="text" name="search" class="edd-input-minimal" placeholder="SEARCH ASSET OR HOLDER..." value="<?= htmlspecialchars($search) ?>">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th width="40"></th>
                                        <th width="140">Asset ID</th>
                                        <th>Asset Description</th>
                                        <th>Condition</th>
                                        <th>Current Holder</th>
                                        <th class="text-center" width="100">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sql = "SELECT * FROM assets WHERE $where_clause ORDER BY id DESC LIMIT $start, $limit";
                                    $result = mysqli_query($conn, $sql);
                                    
                                    if(mysqli_num_rows($result) > 0):
                                        while($row = mysqli_fetch_assoc($result)): 
                                            $drawer_id = "dmg_" . $row['id'];
                                    ?>
                                        <tr class="log-row collapsed">
                                            <td class="text-center" data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">
                                                <i class="fa-solid fa-chevron-right btn-expand"></i>
                                            </td>
                                            <td class="asset-id-cell" data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">#<?= htmlspecialchars($row['asset_id']) ?></td>
                                            <td data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">
                                                <div class="fw-bold text-uppercase" style="font-size: 0.7rem;"><?= htmlspecialchars($row['item_name']) ?></div>
                                                <div class="text-muted" style="font-size: 0.6rem;"><?= htmlspecialchars($row['brand']) ?> / <?= htmlspecialchars($row['model']) ?></div>
                                            </td>
                                            <td data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">
                                                <span class="badge-status bg-danger-subtle text-danger border border-danger-subtle">
                                                    <?= strtoupper($row['condition_status']) ?>
                                                </span>
                                            </td>
                                            <td class="fw-bold text-uppercase" style="font-size: 0.7rem;" data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>">
                                                <?= htmlspecialchars($row['assigned_to'] ?? 'NOT ASSIGNED') ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (strpos(strtoupper($row['condition_status']), 'DISPOSAL') === false): ?>
                                                    <button class="btn-resolve-icon" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#resolveModal" 
                                                            data-id="<?= $row['id'] ?>" 
                                                            data-asset="<?= $row['asset_id'] ?>" 
                                                            data-name="<?= $row['item_name'] ?>">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <i class="fa-solid fa-ban text-muted" style="opacity: 0.3;" title="Finalized for Disposal"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        
                                        <tr class="collapse" id="<?= $drawer_id ?>">
                                            <td colspan="6" class="p-0 border-0">
                                                <div class="detail-drawer">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="detail-label">Issue Remarks</div>
                                                            <div class="detail-value"><?= nl2br(htmlspecialchars($row['remarks'] ?? 'No remarks.')) ?></div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="detail-label">Serial Number</div>
                                                            <div class="detail-value"><?= htmlspecialchars($row['serial_number'] ?? 'N/A') ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; else: ?>
                                        <tr><td colspan="6" class="text-center py-5 text-muted fw-bold">NO DAMAGED ASSETS FOUND</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const resolveModal = document.getElementById('resolveModal');
        resolveModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const asset = button.getAttribute('data-asset');
            const name = button.getAttribute('data-name');
            
            document.getElementById('modal_asset_id_hidden').value = id;
            document.getElementById('modal_asset_display').textContent = asset + ' - ' + name;
        });
    </script>
</body>
</html>