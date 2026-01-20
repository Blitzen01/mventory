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
    include "../render/modals.php";

    // --- LOGIC: PAGINATION ---
    $limit = 25; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // --- LOGIC: SEARCH & FILTERS ---
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $cat_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
    
    $where = ["1=1"];
    if($search) $where[] = "(a.asset_id LIKE '%$search%' OR a.item_name LIKE '%$search%' OR a.serial_number LIKE '%$search%')";
    if($cat_filter) $where[] = "c.category_name = '$cat_filter'";
    $where_sql = implode(" AND ", $where);

    // Get Total Count for Pagination
    $total_res = mysqli_query($conn, "SELECT COUNT(*) as id FROM assets a LEFT JOIN categories c ON a.category_id = c.category_id WHERE $where_sql");
    $total_rows = mysqli_fetch_assoc($total_res)['id'];
    $total_pages = ceil($total_rows / $limit);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Asset Registry | Varay Inventory</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <style>
            /* PROFESSIONAL UI OVERRIDES */
            body { background-color: #f8f9fa; }
            .edd-card { border: none; border-radius: 8px; overflow: hidden; background: #fff; }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

            /* Modern Professional Search & Filter */
            .filter-container { display: flex; align-items: center; gap: 25px; }
            .search-wrapper { position: relative; display: flex; align-items: center; }
            .search-wrapper i { position: absolute; left: 0; color: #adb5bd; font-size: 0.8rem; }

            .edd-input-minimal {
                border: none;
                border-bottom: 2px solid #dee2e6;
                border-radius: 0;
                padding: 5px 5px 5px 25px;
                font-size: 0.85rem;
                font-weight: 600;
                color: #212529;
                background: transparent;
                transition: border-color 0.2s;
                width: 280px;
            }
            .edd-input-minimal:focus { outline: none; border-bottom: 2px solid #212529; }

            .edd-select-minimal {
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 5px 12px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                color: #495057;
                background-color: #f8f9fa;
                cursor: pointer;
            }
            .edd-select-minimal:hover { background-color: #f1f3f5; }

            /* Table Styles */
            .table thead { background-color: #212529; }
            .table thead th {
                color: #adb5bd !important;
                text-transform: uppercase;
                font-size: 0.7rem;
                letter-spacing: 0.5px;
                padding: 15px;
                border: none;
            }
            .log-row { border-bottom: 1px solid #f8f9fa; transition: background 0.2s; }
            .log-row:hover { background-color: #fcfcfc; }

            /* Category Tags */
            .cat-badge {
                font-size: 0.65rem;
                font-weight: 800;
                padding: 4px 10px;
                border-radius: 4px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: inline-block;
            }
            .cat-ho { background: #ffffff; color: #212529; border: 1px solid #212529; }
            .cat-system { background: #212529; color: #ffffff; }

            .qty-pill { background: #e9ecef; color: #212529; padding: 3px 10px; font-weight: 800; border-radius: 20px; font-size: 0.75rem; }
            .specs-text { font-size: 0.72rem; color: #6c757d; line-height: 1.4; margin-top: 4px; }
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
                                <h3 class="section-title text-dark mb-0">Asset Registry</h3>
                                <p class="text-muted small">Infrastructure & Equipment Master List | Varay Inventory</p>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-dark btn-sm fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="fa-solid fa-plus me-1"></i> ADD NEW ASSET
                                </button>
                            </div>
                        </div>

                        <div class="card edd-card shadow-sm">
                            <div class="card-header bg-white p-4 border-bottom">
                                <form method="GET" class="filter-container">
                                    <div class="search-wrapper">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="text" name="search" class="edd-input-minimal" placeholder="SEARCH ASSET ID, NAME, SERIAL..." value="<?php echo htmlspecialchars($search); ?>">
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold small text-muted text-uppercase" style="font-size: 0.65rem;">Category:</span>
                                        <select name="category" class="edd-select-minimal" onchange="this.form.submit()">
                                            <option value="">All Categories</option>
                                            <?php
                                                $cat_res = mysqli_query($conn, "SELECT category_name FROM categories ORDER BY category_name ASC");
                                                while($c = mysqli_fetch_assoc($cat_res)){
                                                    $sel = ($cat_filter == $c['category_name']) ? 'selected' : '';
                                                    echo "<option value='".htmlspecialchars($c['category_name'])."' $sel>".htmlspecialchars($c['category_name'])."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 border-start ps-4">
                                        <span class="fw-bold small text-muted text-uppercase" style="font-size: 0.65rem;">View Mode:</span>
                                        <select class="edd-select-minimal">
                                            <option>Full Registry</option>
                                            <option>Low Stock Only</option>
                                            <option>Archived</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Asset ID</th>
                                            <th>Product Details</th>
                                            <th>Category</th>
                                            <th class="text-center">Stock</th>
                                            <th>Valuation</th>
                                            <th>Status</th>
                                            <th class="pe-4 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <?php
                                        $query = "SELECT a.*, c.category_name FROM assets a 
                                                  LEFT JOIN categories c ON a.category_id = c.category_id 
                                                  WHERE $where_sql 
                                                  ORDER BY a.created_at DESC LIMIT $start, $limit";
                                        $result = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                <tr class="log-row">
                                                    <td class="ps-4">
                                                        <div class="fw-bold text-dark" style="letter-spacing: 0.5px;"><?php echo $row['asset_id']; ?></div>
                                                        <small class="text-muted text-uppercase" style="font-size: 0.6rem;">Registered Code</small>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark text-uppercase" style="font-size: 0.8rem;"><?php echo htmlspecialchars($row['item_name']); ?></div>
                                                        <div class="specs-text">
                                                            <strong><?php echo htmlspecialchars($row['brand'] . " " . $row['model']); ?></strong><br>
                                                            <span class="text-muted">SN: <?php echo htmlspecialchars($row['serial_number']); ?></span><br>
                                                            <span><?php echo htmlspecialchars($row['specs']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td><span class="cat-badge cat-ho"><?php echo strtoupper($row['category_name']); ?></span></td>
                                                    <td class="text-center"><span class="qty-pill"><?php echo $row['quantity']; ?></span></td>
                                                    <td>
                                                        <div class="fw-bold text-dark" style="font-size: 0.8rem;">₱<?php echo number_format($row['cost'], 2); ?></div>
                                                        <small class="text-muted" style="font-size: 0.6rem;">WARRANTY: ₱<?php echo number_format($row['warranty_cost'], 2); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-uppercase" style="font-size: 0.7rem;">
                                                            <i class="fa-solid fa-circle me-1" style="font-size: 0.4rem; color: <?php echo ($row['quantity'] > 0) ? '#212529' : '#adb5bd'; ?>;"></i>
                                                            <?php echo $row['status']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <div class="btn-group border rounded-1">
                                                            <button class="btn btn-link text-dark p-1 px-2 border-end" title="Edit"><i class="fa-solid fa-pen-to-square fa-sm"></i></button>
                                                            <button class="btn btn-link text-dark p-1 px-2" title="Details"><i class="fa-solid fa-arrow-right fa-sm"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center py-5 text-muted fw-bold'>NO ASSETS FOUND IN DATABASE</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white py-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted fw-bold text-uppercase">Showing <?php echo ($start+1); ?>-<?php echo min($start + $limit, $total_rows); ?> of <?php echo number_format($total_rows); ?> Assets</span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                                                <a class="page-link border-0 text-dark fw-bold" href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>&category=<?php echo $cat_filter; ?>">PREV</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link border-0 bg-dark text-white fw-bold" href="#"><?php echo $page; ?></a></li>
                                            <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                                                <a class="page-link border-0 text-dark fw-bold" href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>&category=<?php echo $cat_filter; ?>">NEXT</a>
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