<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "../render/connection.php";
    include "../src/cdn/cdn_links.php";
    include "../render/app_name.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $display_name; ?> | Asset Inventory</title>
    
    
    <link rel="stylesheet" href="../src/style/main_style.css">
    <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">
    
    <?php include "../render/modals.php";?>

    <style>
        body { background-color: #fbfbfb; font-size: 0.85rem; color: #212529; font-family: 'Inter', sans-serif; }
        .main-registry-container { max-width: 1400px; margin: 0 auto; padding-top: 30px; }
        .table.dataTable thead th { 
            background-color: #212529 !important; 
            color: #adb5bd !important; 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            padding: 15px;
            border: none;
        }
        td.dt-control { text-align: center; cursor: pointer; color: #adb5bd; }
        tr.dt-hasChild td.dt-control i { transform: rotate(90deg); color: #212529; }
        .drawer-inner { background: #f8f9fa; padding: 20px; border-left: 4px solid #212529; border-radius: 4px; margin: 5px; }
        .detail-label { font-size: 0.6rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 5px; }
        
        /* Condition Colors */
        .cond-new { color: #198754; font-weight: 700; }      /* Green */
        .cond-used { color: #fd7e14; font-weight: 700; }     /* Orange */
        .cond-damaged { color: #dc3545; font-weight: 700; }  /* Red */
        .cond-repair { color: #0d6efd; font-weight: 700; }   /* Blue */
    </style>
</head>
<body>

<div class="row g-0">
    <div class="col-lg-2"><?php include "../nav/sidebar_nav.php"; ?></div>
    <div class="col">
        <div class="container-fluid px-4 mt-4">
            <div class="main-registry-container">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-uppercase mb-0">Asset Inventory</h3>
                        <p class="text-muted small">Manage infrastructure and generate barcodes</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark btn-sm fw-bold" onclick="downloadAllBarcodes()">
                            <i class="fa-solid fa-file-zipper me-2"></i>ZIP BARCODES
                        </button>
                        <button class="btn btn-dark btn-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            + ADD ASSET
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded border">
                        <div class="d-flex align-items-center gap-3">
                            <span class="detail-label mb-0">Bulk Actions:</span>
                            <div class="btn-group shadow-sm">
                                <button type="button" class="btn btn-sm btn-white border fw-bold text-primary" onclick="handleBulkAction('allocate')">
                                    <i class="fa-solid fa-user-tag me-1"></i> ALLOCATE
                                </button>
                                <button type="button" class="btn btn-sm btn-white border fw-bold text-danger" onclick="handleBulkAction('damage')">
                                    <i class="fa-solid fa-burst me-1"></i> REPORT DAMAGE
                                </button>
                            </div>
                        </div>
                        <div class="text-muted" style="font-size: 0.65rem;">Select multiple items to perform actions</div>
                    </div>

                    <form id="bulkForm" action="process_bulk.php" method="POST">
                        <table id="assetTable" class="table table-hover table-sm align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="no-sort"><input type="checkbox" id="selectAll"></th>
                                    <th class="no-sort"></th> 
                                    <th>Asset ID</th>
                                    <th>Item Details</th>
                                    <th>Category</th>
                                    <th>Assigned To</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                    <th class="no-sort text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT a.*, c.category_name FROM assets a LEFT JOIN categories c ON a.category_id = c.category_id ORDER BY a.id DESC";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)):
                                    // Condition Color Logic
                                    $cond = strtoupper($row['condition_status']);
                                    $cond_class = 'text-secondary';
                                    if ($cond == 'NEW') $cond_class = 'cond-new';
                                    elseif ($cond == 'USED') $cond_class = 'cond-used';
                                    elseif (preg_match('/(DEFECTIVE|REPLACEMENT|DISPOSAL)/', $cond)) $cond_class = 'cond-damaged';
                                    elseif (preg_match('/(REPAIR|WARRANTY)/', $cond)) $cond_class = 'cond-repair';
                                ?>
                                <tr class="asset-row" 
                                    data-serial="<?= htmlspecialchars($row['serial_number']) ?>"
                                    data-qty="<?= $row['quantity'] ?>"
                                    data-cost="<?= number_format($row['cost'] ?? 0, 2) ?>"
                                    data-purchased="<?= $row['purchase_date'] ?>"
                                    data-specs="<?= htmlspecialchars($row['specs'] ?? '') ?>"
                                    data-remarks="<?= htmlspecialchars($row['remarks'] ?? '') ?>">
                                    
                                    <td><input type="checkbox" name="ids[]" value="<?= $row['id']; ?>"></td>
                                    <td class="dt-control"><i class="fa-solid fa-angle-right"></i></td>
                                    <td class="fw-bold asset-id-cell"><?= $row['asset_id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['item_name']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($row['brand']) ?> / <?= htmlspecialchars($row['model']) ?></div>
                                    </td>
                                    <td class="small"><?= strtoupper($row['category_name'] ?? 'Uncategorized') ?></td>
                                    <td class="fw-bold text-primary small"><?= !empty($row['assigned_to']) ? strtoupper($row['assigned_to']) : '—' ?></td>
                                    <td class="small <?= $cond_class ?>"><?= $cond ?></td>
                                    <td><span class="badge rounded-pill <?= ($row['status'] == 'In Stock') ? 'bg-success' : 'bg-info' ?>"><?= strtoupper($row['status']) ?></span></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-light" onclick="saveAsPng('<?= $row['asset_id'] ?>')" title="Barcode">
                                                <i class="fa-solid fa-barcode"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                data-id="<?= $row['id'] ?>" data-assetid="<?= $row['asset_id'] ?>" data-itemname="<?= htmlspecialchars($row['item_name']) ?>" 
                                                data-category="<?= $row['category_id'] ?>" data-condition="<?= $row['condition_status'] ?>">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#allocateModal" 
                                                data-id="<?= $row['id'] ?>" data-assetid="<?= htmlspecialchars($row['asset_id']) ?>" 
                                                data-itemname="<?= htmlspecialchars($row['item_name']) ?>" data-currentholder="<?= htmlspecialchars($row['assigned_to']) ?>">
                                                <i class="fa-solid fa-user-tag text-dark"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light text-danger" data-bs-toggle="modal" data-bs-target="#damageModal" 
                                                data-id="<?= $row['id'] ?>" data-assetid="<?= $row['asset_id'] ?>" data-itemname="<?= htmlspecialchars($row['item_name']) ?>">
                                                <i class="fa-solid fa-burst"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- 1. BARCODE FUNCTIONS ---
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
        const zip = new JSZip();
        const ids = Array.from(document.querySelectorAll('.asset-id-cell')).map(el => el.innerText.trim());
        if (ids.length === 0) return alert("No assets found.");

        const btn = document.querySelector('[onclick="downloadAllBarcodes()"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Zipping...';

        try {
            const folder = zip.folder("barcodes");
            for (const id of ids) {
                const res = await fetch(`https://bwipjs-api.metafloor.com/?bcid=code128&text=${encodeURIComponent(id)}&scale=4&includetext&backgroundcolor=ffffff`);
                const blob = await res.blob();
                folder.file(`Barcode-${id}.png`, blob);
            }
            const content = await zip.generateAsync({ type: "blob" });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(content);
            link.download = `Inventory_Barcodes.zip`;
            link.click();
        } catch (e) { alert("Error generating ZIP"); } 
        finally { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-file-zipper me-2"></i>ZIP BARCODES'; }
    }

    // --- 2. DATATABLES INITIALIZATION ---
    function formatDrawer(d) {
        return `
            <div class="drawer-inner shadow-sm">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="detail-label">Technical Info</label>
                        <div class="small">Serial: <b>${d.serial || 'N/A'}</b></div>
                        <div class="small">Quantity: <b>${d.qty}</b></div>
                    </div>
                    <div class="col-md-3">
                        <label class="detail-label">Finance</label>
                        <div class="small">Cost: <b>₱${d.cost}</b></div>
                        <div class="small">Purchased: <b>${d.purchased || 'N/A'}</b></div>
                    </div>
                    <div class="col-md-3">
                        <label class="detail-label">Specs</label>
                        <div class="bg-white p-2 border rounded small" style="max-height:80px; overflow-y:auto;">${d.specs || 'None'}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="detail-label text-primary">History</label>
                        <div class="bg-white p-2 border-start border-primary border-3 rounded small" style="max-height:80px; overflow-y:auto;">${d.remarks || 'No logs.'}</div>
                    </div>
                </div>
            </div>`;
    }

    $(document).ready(function() {
        var table = $('#assetTable').DataTable({
            "order": [[2, "desc"]],
            "columnDefs": [{ "targets": "no-sort", "orderable": false }]
        });

        // Drawer Toggle
        $('#assetTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('dt-hasChild');
            } else {
                row.child(formatDrawer(tr.data())).show();
                tr.addClass('dt-hasChild');
            }
        });

        // Select All Logic (Applies to all pages)
        $('#selectAll').on('click', function() {
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        // Ensure bulk form sends checkboxes from all DataTables pages
        $('#bulkForm').on('submit', function(e) {
            var form = this;
            var checkedCount = table.$('input[name="ids[]"]:checked').length;
            
            if (checkedCount === 0) {
                alert("Please select at least one item.");
                return false;
            }

            // Append checkboxes from other pages to the form as hidden inputs
            table.$('input[name="ids[]"]:checked').each(function() {
                if(!$.contains(document, this)){
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'ids[]')
                            .val(this.value)
                    );
                }
            });
        });
    });

    // --- 3. MODAL & BULK SCRIPTS ---
    function handleBulkAction(action) {
        var table = $('#assetTable').DataTable();
        var checkedCount = table.$('input[name="ids[]"]:checked').length;

        if (checkedCount === 0) {
            alert("Please select at least one item from the table.");
            return;
        }

        let modalId = (action === 'allocate') ? '#allocateModal' : '#damageModal';
        let modalEl = document.querySelector(modalId);
        let form = modalEl.querySelector('form');

        // 1. Point form to the bulk processor
        form.action = 'process_bulk.php'; 

        // 2. Clear previous bulk data and add the current action
        $(form).find('input[name="bulk_action"]').remove(); 
        $(form).append(`<input type="hidden" name="bulk_action" value="${action}">`);

        // 3. Mark the main ID field as BULK so the submit interceptor knows what to do
        // Check if your modal uses 'asset_db_id' or 'id'
        let idInput = form.querySelector('input[name="asset_db_id"]') || form.querySelector('input[name="id"]');
        if(idInput) idInput.value = 'BULK';

        // 4. Update UI labels
        if(action === 'allocate') {
            document.getElementById('modal-item-name').textContent = checkedCount + " Selected Assets";
        } else {
            document.getElementById('damage-display-name').textContent = checkedCount + " Selected Assets";
        }

        $(modalId).modal('show');
    }

    // THE INTERCEPTOR: This is the most important part
    $(document).on('submit', '#allocateModal form, #damageModal form', function(e) {
        var form = this;
        var table = $('#assetTable').DataTable();
        
        // Look for the "BULK" flag we set in handleBulkAction
        let idInput = $(form).find('input[name="asset_db_id"]').val() || $(form).find('input[name="id"]').val();

        if (idInput === 'BULK') {
            // Remove any previously appended hidden IDs to prevent duplicates
            $(form).find('.temp-bulk-id').remove();

            // Grab every checked ID from every page of the DataTable
            table.$('input[name="ids[]"]:checked').each(function() {
                $(form).append(`<input type="hidden" class="temp-bulk-id" name="ids[]" value="${this.value}">`);
            });
        }
    });

    function handleBulkAction(action) {
    var table = $('#assetTable').DataTable();
    var checkedCount = table.$('input[name="ids[]"]:checked').length;

    if (checkedCount === 0) {
        alert("Please select at least one item from the table.");
        return;
    }

    let modalId = (action === 'allocate') ? '#allocateModal' : '#damageModal';
    let modalEl = document.querySelector(modalId);
    let form = modalEl.querySelector('form');

    // 1. Change Form Destination to Bulk Processor
    form.action = '../src/php_script/process_bulk.php'; 

    // 2. Add/Update the 'bulk_action' hidden field
    $(form).find('input[name="bulk_action"]').remove(); 
    $(form).append(`<input type="hidden" name="bulk_action" value="${action}">`);

    // 3. Mark the ID field as 'BULK'
    if(action === 'allocate') {
        document.getElementById('allocate-db-id').value = 'BULK';
        document.getElementById('modal-item-name').textContent = checkedCount + " Selected Assets";
    } else {
        document.getElementById('damage-db-id').value = 'BULK';
        document.getElementById('damage-display-name').textContent = checkedCount + " Selected Assets";
    }

    $(modalId).modal('show');
}

// CRITICAL: Intercept the Modal Form Submission
$(document).on('submit', '#allocateModal form, #damageModal form', function(e) {
    var form = this;
    var table = $('#assetTable').DataTable();
    
    // Check if we are in bulk mode
    var dbIdValue = $(form).find('#allocate-db-id, #damage-db-id').val();

    if (dbIdValue === 'BULK') {
        // Remove any old temp IDs
        $(form).find('.temp-bulk-id').remove();

        // Inject all checked IDs from the DataTables instance
        table.$('input[name="ids[]"]:checked').each(function() {
            $(form).append(`<input type="hidden" class="temp-bulk-id" name="ids[]" value="${this.value}">`);
        });
        
        // Ensure bulk_action exists (fail-safe)
        if($(form).find('input[name="bulk_action"]').length === 0) {
            let action = (form.id.includes('allocate')) ? 'allocate' : 'damage';
            $(form).append(`<input type="hidden" name="bulk_action" value="${action}">`);
        }
    }
});
</script>

</body>
</html>