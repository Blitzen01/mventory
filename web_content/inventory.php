<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "../render/connection.php";
    include "../src/cdn/cdn_links.php";
    include "../render/modals.php";

    // --- LOGIC: SEARCH & FILTERS ---
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $cat_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
    
    $where = ["1=1"];
    if($search) $where[] = "(a.asset_id LIKE '%$search%' OR a.item_name LIKE '%$search%' OR a.serial_number LIKE '%$search%')";
    if($cat_filter) $where[] = "c.category_name = '$cat_filter'";
    $where_sql = implode(" AND ", $where);

    // --- LOGIC: PAGINATION & LIMIT ---
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10; 
    $db_limit = ($limit == 'all') ? 9999 : (int)$limit;

    // FIX: If limit is 'all', always force page to 1 to avoid offset errors
    $page = (isset($_GET['page']) && $limit !== 'all') ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $db_limit;

    // Get Total Rows
    $total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM assets a LEFT JOIN categories c ON a.category_id = c.category_id WHERE $where_sql");
    $total_rows = mysqli_fetch_assoc($total_res)['total'] ?? 0;
    
    // FIX: Prevent division by zero and ensure at least 1 page exists
    $total_pages = ($db_limit > 0) ? ceil($total_rows / $db_limit) : 1;
    if($total_pages < 1) $total_pages = 1; 
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Registry | Professional Compact</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <style>
            body { background-color: #fbfbfb; font-size: 0.8rem; color: #212529; font-family: 'Inter', sans-serif; }
            .main-registry-container { max-width: 1200px; margin: 0 auto; }
            .edd-card { border: 1px solid #e9ecef; border-radius: 4px; background: #fff; }
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
            
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; }
            .log-row:hover { background-color: #fcfcfc; }
            
            .btn-expand { color: #adb5bd; cursor: pointer; transition: 0.3s; font-size: 0.7rem; padding: 5px; }
            .log-row[aria-expanded="true"] .btn-expand { transform: rotate(90deg); color: #212529; }

            .detail-label { font-size: 0.6rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 2px; }
            .asset-id-cell { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; }

            .collapse {
                transition: height 0.15s ease-out !important;
            }

            .collapsing {
                transition: height 0.15s ease-out !important;
            }
        </style>
    </head>
    <body>
        <div class="row g-0">
            <div class="col-lg-2"><?php include "../nav/sidebar_nav.php"; ?></div>
            <div class="col">
                <div class="container-fluid px-4 mt-5">
                    <div class="main-registry-container">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="section-title mb-0">Asset Registry</h3>
                                <p class="text-muted small mb-0">Management & Infrastructure Log</p>
                            </div>
                            <button class="btn btn-dark btn-sm fw-bold px-4" style="font-size: 0.65rem; border-radius: 2px;" data-bs-toggle="modal" data-bs-target="#addProductModal">+ ADD ASSET</button>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-3 border-bottom">
                                <form method="GET" class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="position-relative">
                                            <i class="fa-solid fa-magnifying-glass position-absolute" style="left:0; top:8px; color:#adb5bd; font-size:0.7rem;"></i>
                                            <input type="text" name="search" class="edd-input-minimal" placeholder="SEARCH ASSET OR SERIAL..." value="<?= htmlspecialchars($search) ?>">
                                        </div>
                                        <div class="d-flex gap-2 align-items-center border-start ps-3">
                                            <span class="detail-label mb-0">Show:</span>
                                            <select name="limit" class="border-0 fw-bold text-uppercase" style="font-size: 0.65rem; cursor: pointer; background: transparent;" onchange="this.form.submit()">
                                                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 Rows</option>
                                                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20 Rows</option>
                                                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 Rows</option>
                                                <option value="all" <?= $limit == 'all' ? 'selected' : '' ?>>All Records</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-4 align-items-center">
                                        <span class="detail-label mb-0">Filter by category:</span>
                                        <select name="category" class="border-0 fw-bold text-uppercase" style="font-size: 0.65rem; cursor: pointer; background: transparent;" onchange="this.form.submit()">
                                            <option value="">ALL CATEGORIES</option>
                                            <?php
                                                $cats = mysqli_query($conn, "SELECT category_name FROM categories ORDER BY category_name ASC");
                                                while($c = mysqli_fetch_assoc($cats)):
                                                    $sel = ($cat_filter == $c['category_name']) ? 'selected' : '';
                                                    echo "<option value='".$c['category_name']."' $sel>".$c['category_name']."</option>";
                                                endwhile;
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center m-3">
                                <h6 class="text-muted small fw-bold text-uppercase mb-0">Asset Inventory</h6>
                                <button onclick="downloadAllBarcodes()" class="btn btn-sm btn-dark shadow-sm px-3" style="font-size: 0.65rem;">
                                    <i class="fa-solid fa-file-zipper me-2"></i> DOWNLOAD ZIP (<?= strtoupper($limit) ?>)
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th width="30"></th>
                                            <th width="120">Asset ID</th>
                                            <th>Item Details</th>
                                            <th>Category</th>
                                            <th class="text-center">Assigned To</th>
                                            <th class="text-center">Condition</th>
                                            <th class="text-center">Status</th>
                                            <th width="150" class="text-center pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT a.*, c.category_name FROM assets a 
                                                  LEFT JOIN categories c ON a.category_id = c.category_id 
                                                  WHERE $where_sql ORDER BY a.asset_id DESC LIMIT $start, $db_limit";
                                        $result = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($result) > 0):
                                            while ($row = mysqli_fetch_assoc($result)):
                                                $drawer_id = "row_detail_" . $row['id'];
                                                $cond = strtoupper($row['condition_status'] ?? 'N/A');
                                                $cond_class = ($cond == 'NEW') ? 'text-success' : (($cond == 'USED') ? 'text-warning' : 'text-info');
                                        ?>
                                        <tr class="log-row">
                                            <td class="text-center">
                                                <i class="fa-solid fa-angle-right btn-expand" data-bs-toggle="collapse" data-bs-target="#<?= $drawer_id ?>" role="button"></i>
                                            </td>
                                            <td class="fw-bold asset-id-cell text-dark"><?= $row['asset_id'] ?></td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['item_name']) ?></div>
                                                <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($row['brand']) ?> / <?= htmlspecialchars($row['model']) ?></div>
                                            </td>
                                            <td class="text-muted" style="font-size: 0.75rem;"><?= strtoupper($row['category_name'] ?? 'Uncategorized') ?></td>
                                            <td class="text-center fw-bold text-primary" style="font-size: 0.75rem;">
                                                <?= !empty($row['assigned_to']) ? strtoupper(htmlspecialchars($row['assigned_to'])) : '<span class="text-muted fw-normal">—</span>' ?>
                                            </td>
                                            <td class="text-center fw-bold <?= $cond_class ?>" style="font-size: 0.65rem;"><?= $cond ?></td>
                                            <td class="text-center small fw-bold">
                                                <i class="fa-solid fa-circle me-1" style="font-size: 0.35rem; color: <?= ($row['status'] == 'In Stock') ? '#28a745' : '#17a2b8' ?>;"></i> 
                                                <?= strtoupper($row['status']) ?>
                                            </td>
                                            <td class="text-center pe-4">
                                                    <div class="d-flex justify-content-end gap-1" onclick="event.stopPropagation();">
                                                        
                                                        <button type="button" class="btn btn-sm btn-light border-0" 
                                                                onclick="saveAsPng('<?= $row['asset_id'] ?>')" title="Barcode">
                                                            <i class="fa-solid fa-barcode"></i>
                                                        </button>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-light btn-outline-dark border-0 text-muted" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editModal" 
                                                                data-id="<?= $row['id'] ?>"
                                                                data-assetid="<?= $row['asset_id'] ?>"
                                                                data-itemname="<?= htmlspecialchars($row['item_name']) ?>"
                                                                data-brand="<?= htmlspecialchars($row['brand']) ?>"
                                                                data-category="<?= $row['category_id'] ?>" 
                                                                data-condition="<?= $row['condition_status'] ?>"
                                                                title="Edit">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-light btn-outline-dark border-0 shadow-none" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#allocateModal" 
                                                                data-id="<?= $rid ?>" 
                                                                data-assetid="<?= $row['asset_id'] ?>"
                                                                data-itemname="<?= htmlspecialchars($row['item_name']) ?>"
                                                                data-currentholder="<?= htmlspecialchars($row['assigned_to']) ?>" 
                                                                title="Allocate">
                                                            <i class="fa-solid fa-user-tag"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-light border-0 text-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#damageModal" 
                                                                data-assetid="<?= $row['asset_id'] ?>"
                                                                data-id="<?= $row['id'] ?>" title="Report Damage">
                                                            <i class="fa-solid fa-burst"></i>
                                                        </button>
                                                        
                                                    </div>
                                            </td>
                                        </tr>

                                        <tr id="<?= $drawer_id ?>" class="collapse">
                                            <td colspan="8" class="p-0 border-0"> 
                                                <div class="collapse" id="<?= $drawer_id ?>">
                                                    <div class="bg-light p-4 border-start border-3 border-dark mx-2 my-1 rounded-end shadow-sm">
                                                        <div class="row g-4">
                                                            <div class="col-md-3">
                                                                <label class="text-muted small fw-bold d-block mb-2 text-uppercase" style="font-size: 0.6rem;">Technical Info</label>
                                                                <div class="mb-1 small">Serial: <span class="font-monospace fw-bold text-dark"><?= $row['serial_number'] ?: 'N/A' ?></span></div>
                                                                <div class="mb-1 small">Quantity: <span class="fw-bold"><?= sprintf("%02d", $row['quantity']) ?></span></div>
                                                                <div class="small">Asset ID: <span class="text-muted"><?= $row['asset_id'] ?></span></div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="text-muted small fw-bold d-block mb-2 text-uppercase" style="font-size: 0.6rem;">Timeline & Costs</label>
                                                                <div class="mb-1 small">Purchase Cost: <span class="fw-bold text-dark">₱<?= number_format($row['cost'] ?? 0, 2) ?></span></div>
                                                                <div class="mb-1 small">Warranty Cost: <span class="fw-bold text-dark">₱<?= number_format($row['warranty_cost'] ?? 0, 2) ?></span></div>
                                                                <hr class="my-1 opacity-25">
                                                                <div class="text-muted" style="font-size: 0.7rem;">
                                                                    Purchased: <span class="text-dark"><?= $row['purchase_date'] ?: 'N/A' ?></span><br>
                                                                    Arrival: <span class="text-dark fw-bold"><?= $row['arrival_date'] ?: 'N/A' ?></span><br>
                                                                    Encoded: <?= date("d M Y", strtotime($row['created_at'])) ?>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="text-muted small fw-bold d-block mb-2 text-uppercase" style="font-size: 0.6rem;">Item Specifications</label>
                                                                <div class="bg-white p-2 border rounded small text-muted" style="white-space: pre-line; font-size: 0.7rem; max-height: 100px; overflow-y: auto;">
                                                                    <?= !empty($row['specs']) ? htmlspecialchars($row['specs']) : 'No specifications provided.' ?>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="text-primary small fw-bold d-block mb-2 text-uppercase" style="font-size: 0.6rem;">Remarks & History</label>
                                                                <div class="bg-white p-2 border-start border-primary border-3 rounded-end small text-dark" style="white-space: pre-line; font-size: 0.7rem; min-height: 60px;">
                                                                    <?= !empty($row['remarks']) ? htmlspecialchars($row['remarks']) : 'No history logs found for this item.' ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                        <tr><td colspan="8" class="text-center py-5 text-muted small fw-bold">NO RECORDS FOUND</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white py-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted fw-bold" style="font-size: 0.55rem; text-uppercase;">
                                        PAGE <?= $page ?> OF <?= max(1, $total_pages) ?> | TOTAL: <?= $total_rows ?> ASSETS
                                    </span>
                                    <div class="btn-group shadow-sm">
                                        <a href="?page=<?= max(1, $page-1) ?>&search=<?= $search ?>&category=<?= $cat_filter ?>&limit=<?= $limit ?>" 
                                           class="btn btn-outline-dark btn-sm fw-bold <?= ($page <= 1) ? 'disabled' : '' ?>" style="font-size: 0.65rem;">PREV</a>
                                        <a href="?page=<?= min($total_pages, $page+1) ?>&search=<?= $search ?>&category=<?= $cat_filter ?>&limit=<?= $limit ?>" 
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
            async function saveAsPng(id) {
                const url = `https://bwipjs-api.metafloor.com/?bcid=code128&text=${encodeURIComponent(id)}&scale=4&includetext&backgroundcolor=ffffff`;
                try {
                    const res = await fetch(url);
                    const blob = await res.blob();
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = `Barcode-${id}.png`;
                    link.click();
                } catch (e) { alert("Download failed"); }
            }

            async function downloadAllBarcodes() {
                if (typeof JSZip === 'undefined') return alert("Loading libraries...");
                const zip = new JSZip();
                const ids = Array.from(document.querySelectorAll('.asset-id-cell')).map(el => el.innerText.trim()).filter(id => id.length > 0);
                if (ids.length === 0) return alert("No assets found.");

                const btn = document.querySelector('[onclick="downloadAllBarcodes()"]');
                const oldText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Zipping...';

                try {
                    const folder = zip.folder("barcodes");
                    for (const id of ids) {
                        const res = await fetch(`https://bwipjs-api.metafloor.com/?bcid=code128&text=${encodeURIComponent(id)}&scale=4&includetext&backgroundcolor=ffffff`);
                        const blob = await res.blob();
                        folder.file(`Barcode-${id.replace(/[/\\?%*:|"<>]/g, '-')}.png`, blob);
                    }
                    const content = await zip.generateAsync({ type: "blob" });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(content);
                    link.download = `Asset_Barcodes_${ids.length}.zip`;
                    link.click();
                } catch (e) { alert("Error generating ZIP"); } 
                finally { btn.disabled = false; btn.innerHTML = oldText; }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('editModal');
                if (editModal) {
                    editModal.addEventListener('show.bs.modal', function (event) {
                        // Button that triggered the modal
                        const button = event.relatedTarget;
                        
                        // Extract info from data-* attributes
                        const id = button.getAttribute('data-id');
                        const assetId = button.getAttribute('data-assetid');
                        const itemName = button.getAttribute('data-itemname');
                        const category = button.getAttribute('data-category');
                        const condition = button.getAttribute('data-condition');

                        // 1. Populate Hidden ID and Inputs
                        document.getElementById('edit-db-id').value = id;
                        document.getElementById('edit-item-name').value = itemName;
                        
                        // 2. Update the Display Headers (The "Asset Identifier" box)
                        document.getElementById('edit-display-name').textContent = itemName;
                        document.getElementById('edit-display-assetid').textContent = assetId;
                        
                        // 3. Set Dropdown values
                        // Note: If your data-condition is "NEW" (all caps) and your option value is "New", 
                        // you might need .toLowerCase() or .toUpperCase() to match.
                        document.getElementById('edit-category').value = category;
                        document.getElementById('edit-condition').value = condition;
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const allocateModal = document.getElementById('allocateModal');
                if (allocateModal) {
                    allocateModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        
                        // Get data from your table row
                        const id = button.getAttribute('data-id');
                        const itemName = button.getAttribute('data-itemname');
                        const assetId = button.getAttribute('data-assetid');
                        const assignedTo = button.getAttribute('data-currentholder'); // This is your assigned_to column

                        // Map to Modal Elements
                        document.getElementById('allocate-db-id').value = id;
                        document.getElementById('modal-item-name').textContent = itemName;
                        document.getElementById('modal-asset-id').textContent = assetId;
                        
                        // Display the current person from the 'assigned_to' column
                        // If the column is empty in DB, it shows 'Unassigned'
                        document.getElementById('modal-current-holder').textContent = assignedTo ? assignedTo : "Unassigned";
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const damageModal = document.getElementById('damageModal');
                if (damageModal) {
                    damageModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        
                        // Extract info from data attributes
                        const id = button.getAttribute('data-id');
                        const itemName = button.getAttribute('data-itemname');
                        const assetId = button.getAttribute('data-assetid');

                        // Populate Modal
                        document.getElementById('damage-db-id').value = id;
                        document.getElementById('damage-display-name').textContent = itemName;
                        document.getElementById('damage-display-id').textContent = assetId;
                    });
                }
            });
        </script>
    </body>
</html>