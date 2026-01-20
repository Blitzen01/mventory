<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "../render/connection.php"; 
    include "../src/cdn/cdn_links.php";

    // --- LOGIC: SEARCH & FILTERS ---
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $cat_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
    
    $where = ["1=1"];
    if($search) $where[] = "(a.asset_id LIKE '%$search%' OR a.item_name LIKE '%$search%' OR a.serial_number LIKE '%$search%')";
    if($cat_filter) $where[] = "c.category_name = '$cat_filter'";
    $where_sql = implode(" AND ", $where);

    // --- LOGIC: PAGINATION ---
    $limit = 10; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM assets a LEFT JOIN categories c ON a.category_id = c.category_id WHERE $where_sql");
    $total_rows = mysqli_fetch_assoc($total_res)['total'] ?? 0;
    $total_pages = ceil($total_rows / $limit);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Registry | Professional Compact</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <style>
            body { background-color: #fbfbfb; font-size: 0.8rem; color: #212529; font-family: 'Inter', sans-serif; }
            .main-registry-container { max-width: 1200px; margin: 0 auto; }
            
            .edd-card { border: 1px solid #e9ecef; border-radius: 4px; background: #fff; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

            /* Modern Inputs */
            .edd-input-minimal {
                border: none; border-bottom: 2px solid #dee2e6;
                padding: 4px 5px 4px 25px; font-size: 0.75rem; font-weight: 600;
                background: transparent; width: 220px; transition: 0.2s;
            }
            .edd-input-minimal:focus { outline: none; border-bottom: 2px solid #212529; }

            /* Table Styling */
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important; text-transform: uppercase;
                font-size: 0.6rem; letter-spacing: 1px; padding: 15px 12px; border: none;
            }
            
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; }
            .log-row:hover { background-color: #fcfcfc; }
            
            /* Expandable Toggle */
            .btn-expand { color: #adb5bd; cursor: pointer; transition: 0.3s; font-size: 0.7rem; padding: 5px; }
            .log-row[aria-expanded="true"] .btn-expand { transform: rotate(90deg); color: #212529; }

            /* Accordion Detail Style */
            .detail-drawer { background-color: #fdfdfd; border-left: 4px solid #212529; padding: 20px; }
            .detail-label { font-size: 0.6rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 2px; }
            .detail-value { font-size: 0.75rem; font-weight: 600; color: #212529; }

            .qty-pill { background: #212529; color: #fff; padding: 2px 8px; font-weight: 800; border-radius: 2px; font-size: 0.7rem; }
            
            /* Visible Action Buttons */
            .action-btn { 
                padding: 4px 8px; font-size: 0.7rem; font-weight: 700; 
                border-radius: 3px; border: 1px solid #dee2e6; background: #fff; color: #212529;
                text-decoration: none; transition: 0.2s;
            }
            .action-btn:hover { background: #212529; color: #fff; border-color: #212529; }
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
                            <button class="btn btn-dark btn-sm fw-bold px-4" style="font-size: 0.65rem; border-radius: 2px;">+ ADD ASSET</button>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-3 border-bottom">
                                <form method="GET" class="d-flex justify-content-between align-items-center">
                                    <div class="position-relative">
                                        <i class="fa-solid fa-magnifying-glass position-absolute" style="left:0; top:8px; color:#adb5bd; font-size:0.7rem;"></i>
                                        <input type="text" name="search" class="edd-input-minimal" placeholder="SEARCH ASSET OR SERIAL..." value="<?= htmlspecialchars($search) ?>">
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

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th width="40"></th>
                                            <th width="140">Asset ID</th>
                                            <th>Product Information</th>
                                            <th>Category</th>
                                            <th class="text-center">Stock</th>
                                            <th class="text-center">Status</th>
                                            <th width="160" class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT a.*, c.category_name FROM assets a 
                                                  LEFT JOIN categories c ON a.category_id = c.category_id 
                                                  WHERE $where_sql ORDER BY a.asset_id DESC LIMIT $start, $limit";
                                        $result = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($result) > 0):
                                            while ($row = mysqli_fetch_assoc($result)):
                                                $drawer_id = "row_detail_" . $row['id'];
                                        ?>
                                        <tr class="log-row">
                                            <td class="text-center">
                                                <i class="fa-solid fa-chevron-right btn-expand" 
                                                   data-bs-toggle="collapse" 
                                                   data-bs-target="#<?= $drawer_id ?>" 
                                                   role="button"></i>
                                            </td>
                                            <td class="fw-bold" style="font-family: monospace; letter-spacing: 0.5px;"><?= $row['asset_id'] ?></td>
                                            <td>
                                                <div class="fw-bold text-uppercase" style="font-size: 0.75rem;"><?= htmlspecialchars($row['item_name']) ?></div>
                                                <div class="text-muted" style="font-size: 0.65rem;"><?= htmlspecialchars($row['brand']) ?> | <?= htmlspecialchars($row['model']) ?></div>
                                            </td>
                                            <td><span class="fw-bold text-secondary" style="font-size: 0.65rem;"><?= strtoupper($row['category_name']) ?></span></td>
                                            <td class="text-center"><span class="qty-pill"><?= sprintf("%02d", $row['quantity']) ?></span></td>
                                            <td class="text-center">
                                                <span class="fw-bold" style="font-size: 0.6rem; color: #212529;">
                                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.35rem;"></i> <?= strtoupper($row['status']) ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="edit_asset.php?id=<?= $row['id'] ?>" class="action-btn me-1">EDIT</a>
                                                <a href="view_asset.php?id=<?= $row['id'] ?>" class="action-btn">VIEW</a>
                                            </td>
                                        </tr>

                                        <tr class="collapse" id="<?= $drawer_id ?>">
                                            <td colspan="7" class="p-0 border-0">
                                                <div class="detail-drawer">
                                                    <div class="row g-4">
                                                        <div class="col-md-3">
                                                            <div class="detail-label">Serial Number</div>
                                                            <div class="detail-value" style="font-family: monospace;"><?= !empty($row['serial_number']) ? $row['serial_number'] : 'N/A' ?></div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="detail-label">Unit Cost</div>
                                                            <div class="detail-value">₱<?= number_format($row['cost'], 2) ?></div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="detail-label">Warranty Valuation</div>
                                                            <div class="detail-value">₱<?= number_format($row['warranty_cost'], 2) ?></div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="detail-label">Date Added</div>
                                                            <div class="detail-value"><?= date("d-M-Y", strtotime($row['created_at'])) ?></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="detail-label">Full Technical Specifications</div>
                                                            <div class="detail-value text-muted" style="font-weight: normal; line-height: 1.5;">
                                                                <?= !empty($row['specs']) ? nl2br(htmlspecialchars($row['specs'])) : 'No technical specifications provided for this asset.' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                        <tr><td colspan="7" class="text-center py-5 text-muted fw-bold">NO RECORDS FOUND</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white py-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.55rem;">
                                        PAGE <?= $page ?> OF <?= max(1, $total_pages) ?> | TOTAL: <?= $total_rows ?> ASSETS
                                    </span>
                                    <div class="btn-group shadow-sm">
                                        <a href="?page=<?= max(1, $page-1) ?>&search=<?= $search ?>&category=<?= $cat_filter ?>" 
                                           class="btn btn-outline-dark btn-sm fw-bold <?= ($page <= 1) ? 'disabled' : '' ?>" style="font-size: 0.65rem;">PREV</a>
                                        <a href="?page=<?= min($total_pages, $page+1) ?>&search=<?= $search ?>&category=<?= $cat_filter ?>" 
                                           class="btn btn-outline-dark btn-sm fw-bold <?= ($page >= $total_pages) ? 'disabled' : '' ?>" style="font-size: 0.65rem;">NEXT</a>
                                    </div>
                                </div>
                            </div>
                        </div> </div> </div>
            </div>
        </div>
    </body>
</html>