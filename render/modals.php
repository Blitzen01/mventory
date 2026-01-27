<!-- view user modal end -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4"> <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="text-center mb-4">
                    <div id="view_profile_img_container" class="position-relative d-inline-block">
                        </div>
                    <h4 class="fw-bold text-dark mt-3 mb-0" id="view_full_name"></h4>
                    <p class="text-muted small mb-0" id="view_username_display"></p>
                    <span class="badge bg-light text-dark border mt-2 fw-normal text-uppercase" id="view_role" style="font-size: 0.7rem;"></span>
                </div>
                
                <div class="p-3 rounded-3 bg-light">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">USER ID</label>
                            <div class="text-dark small font-monospace" id="view_user_id"></div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">ACCOUNT STATUS</label>
                            <div class="small fw-bold text-dark" id="view_status"></div>
                        </div>
                        <div class="col-12 border-top pt-2">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">EMAIL ADDRESS</label>
                            <div class="text-dark small" id="view_email"></div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PHONE</label>
                            <div class="text-dark small" id="view_phone"></div>
                        </div>
                        <div class="col-12 border-top pt-2">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">ADDRESS</label>
                            <div class="text-dark small" id="view_address"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3 px-1">
                    <div class="small text-muted" style="font-size: 0.65rem;">Created: <span id="view_created_at"></span></div>
                    <div class="small text-muted" style="font-size: 0.65rem;">Last Visit: <span id="view_last_login"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- view user modal end -->


<!-- view archieve modal start -->
<div class="modal fade" id="viewArchiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <span class="badge rounded-pill bg-danger-subtle text-danger fw-bold border border-danger-subtle px-3" style="font-size: 0.65rem;">
                    <i class="fa-solid fa-trash-can me-1"></i> ARCHIVED RECORD
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="text-center mb-4">
                    <div id="arc_profile_img_container" class="position-relative d-inline-block">
                        </div>
                    <h4 class="fw-bold text-dark mt-3 mb-0" id="arc_full_name"></h4>
                    <p class="text-muted small mb-0" id="arc_username_display"></p>
                    <span class="badge bg-light text-dark border mt-2 fw-normal text-uppercase" id="arc_role_display" style="font-size: 0.7rem;"></span>
                </div>
                
                <div class="p-3 rounded-3 bg-light border border-dashed">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">ORIGINAL ID</label>
                            <div class="text-dark small font-monospace" id="arc_user_id"></div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PREVIOUS STATUS</label>
                            <div class="small fw-bold text-muted text-uppercase">Inactive</div>
                        </div>
                        <div class="col-12 border-top pt-2">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">EMAIL ADDRESS</label>
                            <div class="text-dark small" id="arc_email"></div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PHONE</label>
                            <div class="text-dark small" id="arc_phone"></div>
                        </div>
                        <div class="col-12 border-top pt-2">
                            <label class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">LAST KNOWN ADDRESS</label>
                            <div class="text-dark small" id="arc_address"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-2 bg-danger-subtle rounded-3 text-center border border-danger border-opacity-10">
                    <div class="small text-danger fw-bold" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> 
                        DELETED ON: <span id="arc_deleted_at"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- view archieve modal end -->


<!-- restore user modal start -->
<div class="modal fade" id="restoreUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left me-2"></i>Restore User Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../src/php_script/restore_user.php" method="POST">
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fa-solid fa-user-check text-success" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="fw-bold">Confirm Restoration</h5>
                    <p class="text-muted">You are about to move <span id="restore_name" class="fw-bold text-dark"></span> back to the Active Accounts list.</p>
                    
                    <input type="hidden" name="user_id" id="restore_user_id">
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="restore_btn" class="btn btn-success px-4">Restore Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- restore user modal end -->


<!-- edit user modal start -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-sm" style="border-radius: 8px; border: 1px solid #dee2e6;">
            
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-user-pen me-2 opacity-75"></i>Edit User Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="../src/php_script/update_user.php" method="POST">
                <div class="modal-body py-4 px-4">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-4">
                        <label for="edit_username" class="form-label fw-bold text-dark small text-uppercase ls-1">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="username" id="edit_username" class="form-control border-start-0 ps-0 shadow-none" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="edit_status" class="form-label fw-bold text-dark small text-uppercase ls-1">Account Status</label>
                        <div class="input-group">
                             <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-toggle-on"></i></span>
                            <select name="status" id="edit_status" class="form-select border-start-0 ps-0 shadow-none">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <hr class="bg-secondary opacity-25 my-4">
                    
                    <div class="mb-2">
                         <label for="new_password" class="form-label fw-bold text-dark small text-uppercase ls-1">
                            <i class="fa-solid fa-lock me-1"></i> Reset Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-key"></i></span>
                            <input type="password" name="new_password" id="new_password" class="form-control border-start-0 ps-0 shadow-none" placeholder="Isulan123!@#" autocomplete="new-password">
                        </div>
                        <small class="text-muted fst-italic mt-2 d-block"> Leave blank unless you want to change the current password.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 py-3">
                    <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_user_btn" class="btn btn-dark fw-bold px-4 shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- edit user modal end -->

<!-- delete user modal start --> <!-- not finish yet -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa-solid fa-user-slash me-2"></i>Archive User Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../src/php_script/delete_user.php" method="POST">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div id="del_profile_img_container" class="mb-3 d-inline-block"></div>
                        <h4 class="fw-bold mb-0" id="del_full_name"></h4>
                        <span class="text-muted" id="del_username_display"></span>
                    </div>

                    <div class="row g-3 px-3">
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Role</label>
                            <div id="del_role" class="fw-semibold"></div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">User ID</label>
                            <div id="del_user_id_text" class="fw-semibold text-primary"></div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Account Status</label>
                            <div><span id="del_status" class="badge bg-success-subtle text-success border border-success-subtle"></span></div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Email Address</label>
                            <div id="del_email" class="small text-truncate"></div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Phone</label>
                            <div id="del_phone" class="small"></div>
                        </div>
                        <div class="col-6 text-end">
                             <div class="small text-muted mt-2">Created: <span id="del_created_at"></span></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="bg-light p-3 rounded border">
                        <label class="form-label fw-bold text-danger small text-uppercase">Admin Confirmation</label>
                        <p class="text-muted small mb-2">Enter your password to unlock the archive button.</p>
                        
                        <div class="position-relative">
                            <input type="password" id="admin_pwd_check" name="admin_password" class="form-control" placeholder="Enter password" required>
                            <div id="pwd_feedback" class="small mt-1"></div>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" id="del_user_id_input">
                    <input type="hidden" name="confirm_delete" value="1">

                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="btn_confirm_archive" class="btn btn-danger px-4 shadow-sm" disabled>Confirm Archive</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- add user account modal start -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-user-plus me-2"></i> Create Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" method="POST" action="../src/php_script/add_account.php">
                <div class="modal-body px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">First Name</label>
                            <input type="text" class="form-control bg-light border-0" id="gen_first_name" name="first_name" placeholder="John" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Last Name</label>
                            <input type="text" class="form-control bg-light border-0" id="gen_last_name" name="last_name" placeholder="Doe" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" class="form-control bg-light border-0" name="email" placeholder="john@example.com" required>
                        </div>
                        <div class="col-12 border-bottom pb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Assign Role</label>
                            <select class="form-select bg-light border-0" id="gen_role" name="role" required>
                                <option value="" selected disabled>Select Role...</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Inventory Manager">Inventory Manager</option>
                                <option value="Stock Handler">Stock Handler</option>
                                <option value="Viewer">Viewer</option>
                            </select>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <div class="p-3 border-0 rounded-4" style="background-color: #f0f7ff; border: 1px dashed #0d6efd !important;">
                                <label class="d-block small fw-bold text-primary text-uppercase mb-3">Login Credentials</label>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Username:</small>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="username" id="display_username" class="form-control border-0 bg-white fw-bold" readonly value="---">
                                        <button class="btn btn-outline-primary border-0 bg-white" type="button" onclick="copyToClipboard('display_username', this)">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div>
                                    <small class="text-muted d-block mb-1">Temporary Password:</small>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="password" id="display_password" class="form-control border-0 bg-white fw-bold" readonly value="---">
                                        <button class="btn btn-outline-primary border-0 bg-white" type="button" onclick="copyToClipboard('display_password', this)">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary border-0 bg-white" type="button" onclick="generateNewPassword()">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add user account modal end -->


<!-- add assets modal start -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="../src/php_script/process_add_item.php" method="POST">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Add Hardware Assets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="fw-bold small">Asset ID Prefix</label>
                            <input type="text" name="id_prefix" class="form-control" list="assetBaseList" placeholder="e.g., LAPTOP" required autocomplete="off">
                            <datalist id="assetBaseList">
                                <?php
                                $bases = mysqli_query($conn, "SELECT DISTINCT TRIM(SUBSTRING(asset_id, 1, LENGTH(asset_id) - LENGTH(SUBSTRING_INDEX(asset_id, '-', -1)) - 1)) AS base_asset FROM assets ORDER BY base_asset ASC");
                                while ($b = mysqli_fetch_assoc($bases)) { echo '<option value="'.$b['base_asset'].'">'; }
                                ?>
                            </datalist>
                        </div>
                        <div class="col-md-2">
                            <label class="fw-bold small">Qty</label>
                            <input type="number" name="bulk_qty" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small">Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php
                                $cat_query = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
                                while($c = mysqli_fetch_assoc($cat_query)) {
                                    echo "<option value='".$c['category_id']."'>".$c['category_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold small">Brand</label>
                            <input type="text" name="brand" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small">Model</label>
                            <input type="text" name="model" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" placeholder="Optional for bulk">
                        </div>

                        <div class="col-12">
                            <label class="fw-bold small">Item Name</label>
                            <div class="input-group">
                                <input type="text" name="item_name" class="form-control" placeholder="e.g. Dell Workstation Set" required>
                                <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#specsCollapse">
                                    <i class="fa-solid fa-gears"></i> Toggle Specs
                                </button>
                            </div>
                        </div>

                        <div class="collapse col-12" id="specsCollapse">
                            <div class="card card-body bg-light border-danger-subtle">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="fw-bold small text-danger mb-0">Specifications</label>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" onclick="applyPcTemplate()">Apply PC Template</button>
                                </div>
                                <textarea id="specsField" name="specs" class="form-control" rows="5" placeholder="Enter hardware details here..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold small">Location</label>
                            <input type="text" name="assigned_to" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small">Condition</label>
                            <select name="condition_status" class="form-select">
                                <option>New</option>
                                <option>Used</option>
                                <option>Refurbished</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small">Status</label>
                            <select name="status" class="form-select">
                                <option>In Stock</option>
                                <option>Deployed</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="fw-bold small">Arrival Date</label>
                            <input type="date" name="arrival_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold small">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold small">Unit Cost</label>
                            <input type="number" step="0.01" name="cost" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold small">Warranty Cost</label>
                            <input type="number" step="0.01" name="warranty_cost" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="fw-bold small text-primary">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" name="save_bulk" class="btn btn-dark px-5">Create Records</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
function applyPcTemplate() {
    const template = "CPU: \nRAM: \nMOTHERBOARD: \nSTORAGE: \nGPU: \nPSU: \nOS: ";
    document.getElementById('specsField').value = template;
    document.getElementById('specsField').focus();
}
</script>
<!-- add assets modal end -->


<!-- allocate assets modal start -->
<div class="modal fade" id="allocateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-0">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-uppercase tracking-wider">Asset Allocation</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../src/php_script/process_allocate.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Currently Modifying:</small>
                        <h5 class="mb-0 fw-bold" id="modal-item-name">---</h5>
                        <code class="text-dark fw-bold" id="modal-asset-id">---</code>
                    </div>

                    <div class="row g-0 align-items-center mb-4 border bg-light">
                        <div class="col-5 p-3">
                            <small class="d-block text-muted text-uppercase fw-bold mb-1" style="font-size: 0.55rem;">Current Assignment</small>
                            <div class="fw-bold text-dark text-truncate" id="modal-current-holder">---</div>
                        </div>
                        <div class="col-2 text-center">
                            <i class="fa-solid fa-arrow-right text-muted"></i>
                        </div>
                        <div class="col-5 p-3 border-start bg-white text-center">
                            <small class="d-block text-muted text-uppercase fw-bold mb-1" style="font-size: 0.55rem;">Action</small>
                            <span class="badge bg-dark rounded-0">REPLACE</span>
                        </div>
                    </div>

                    <input type="hidden" name="asset_db_id" id="allocate-db-id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">New Assigned User / Dept</label>
                        <input type="text" name="assignee" class="form-control rounded-0 border-dark shadow-none form-control-lg" placeholder="Type new assignee here..." required>
                        <div class="form-text" style="font-size: 0.65rem;">This will overwrite the current assignment in the database.</div>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase">Transfer Date</label>
                        <input type="date" name="allocation_date" class="form-control rounded-0 border-dark shadow-none" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-0 px-4 text-uppercase fw-bold small" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark rounded-0 px-4 text-uppercase fw-bold small">Update Allocation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- allocate assets modal start -->


<!-- damage assets modal start -->
<div class="modal fade" id="damageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-0">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-uppercase">
                    <i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i>Damage Incident Report
                </h6>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../src/php_script/process_damage.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-4 pb-3 border-bottom">
                        <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Currently Reporting For:</small>
                        <h6 class="mb-0 fw-bold text-dark" id="damage-display-name">---</h6>
                        <code class="text-danger fw-bold" id="damage-display-id" style="font-size: 0.75rem;">---</code>
                    </div>

                    <input type="hidden" name="asset_db_id" id="damage-db-id">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Scope of Damage</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="damage_scope" id="scope_part" value="component" checked>
                            <label class="btn btn-sm btn-outline-dark rounded-0 w-100 fw-bold" for="scope_part">PART/COMPONENT</label>

                            <input type="radio" class="btn-check" name="damage_scope" id="scope_all" value="entire">
                            <label class="btn btn-sm btn-outline-danger rounded-0 w-100 fw-bold" for="scope_all">ENTIRE UNIT</label>
                        </div>
                        <div class="form-text" style="font-size: 0.65rem;">Choose 'Entire Unit' to mark the item as non-functional.</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-uppercase">Initial Recommendation</label>
                            <select name="recommendation" class="form-select form-select-sm rounded-0 border-dark shadow-none" required>
                                <option value="" selected disabled>-- Select Action --</option>
                                <option value="Repairable">FOR REPAIR (Internal)</option>
                                <option value="Warranty">WARRANTY CLAIM (External)</option>
                                <option value="Replacement">FOR REPLACEMENT</option>
                                <option value="Disposal">FOR DISPOSAL</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Incident Description</label>
                        <textarea name="damage_description" class="form-control form-control-sm rounded-0 border-dark shadow-none" rows="3" placeholder="Identify specific part (e.g. Keyboard) and nature of damage..." required></textarea>
                    </div>

                    <div class="bg-light p-3 border-start border-3 border-danger">
                        <div class="form-check small">
                            <input class="form-check-input" type="checkbox" id="confirmDamage" required>
                            <label class="form-check-label fw-bold text-dark" for="confirmDamage" style="font-size: 0.7rem;">
                                I confirm that this report is accurate and will be logged under my account (<?= $_SESSION['username'] ?>).
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-0 px-4 fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-sm btn-danger rounded-0 px-4 fw-bold">SUBMIT INCIDENT</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- damage assets modal end -->


<!-- edit assets modal start -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-0">
            <div class="modal-header py-3 border-bottom">
                <h6 class="modal-title text-uppercase fw-bold text-dark">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Correction Mode
                </h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../src/php_script/update_asset.php" method="POST">
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-0 mb-4 border-start border-4 border-dark">
                        <small class="text-muted d-block fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 1px;">ASSET IDENTIFIER</small>
                        <h5 class="mb-0 fw-bold text-dark" id="edit-display-name">---</h5>
                        <code class="text-muted fw-bold" id="edit-display-assetid">---</code>
                    </div>

                    <input type="hidden" name="asset_db_id" id="edit-db-id">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase">Item Name</label>
                            <input type="text" name="item_name" id="edit-item-name" class="form-control form-control-sm rounded-0 border-dark shadow-none">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark text-uppercase">Correct Category</label>
                            <select name="category_id" id="edit-category" class="form-select form-select-sm rounded-0 border-dark shadow-none" required>
                                <option value="" disabled selected>-- Select --</option>
                                <?php 
                                    $cat_list = mysqli_query($conn, "SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
                                    while($c = mysqli_fetch_assoc($cat_list)): 
                                ?>
                                    <option value="<?= $c['category_id'] ?>"><?= strtoupper($c['category_name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted text-uppercase">Current Condition</label>
                            <select name="condition_status" id="edit-condition" class="form-select form-select-sm rounded-0 border-dark shadow-none">
                                <option value="New">NEW</option>
                                <option value="Used">USED</option>
                                <option value="Repaired">REPAIRED</option>
                                <option value="Defective">DEFECTIVE</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-2 bg-light text-center border">
                        <small class="text-muted italic" style="font-size: 0.65rem;">
                            <i class="fa-solid fa-circle-info me-1"></i> Changes will be logged automatically by <strong><?= $_SESSION['username'] ?></strong>
                        </small>
                    </div>
                </div>

                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-0 fw-bold px-3 shadow-none" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-sm btn-dark rounded-0 fw-bold px-4 shadow-none">APPLY CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- edit assets modal end -->


<!-- reallocate modal start -->
<div class="modal fade" id="reallocateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-0">
            <div class="modal-header py-3 border-bottom">
                <h6 class="modal-title text-uppercase fw-bold text-dark">
                    <i class="fa-solid fa-arrows-rotate me-2"></i> Asset Re-allocation
                </h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../src/php_script/process_reallocation.php" method="POST">
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-0 mb-4 border-start border-4 border-dark">
                        <small class="text-muted d-block fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 1px;">TARGET ASSET</small>
                        <h5 class="mb-0 fw-bold text-dark" id="display-asset-id">---</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-dark rounded-0" id="display-current-loc" style="font-size: 0.6rem;">CURRENT LOCATION</span>
                            <small class="text-muted fw-bold" style="font-size: 0.6rem;">Auth by: <span id="display-auth">---</span></small>
                        </div>
                    </div>

                    <input type="hidden" name="asset_id" id="input-asset-id">
                    <input type="hidden" name="old_location" id="input-old-location">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark text-uppercase">New Destination / Assignee</label>
                            <input type="text" name="new_assignee" class="form-control form-control-sm rounded-0 border-dark shadow-none" placeholder="ENTER NEW LOCATION..." required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase">Notes / Reason for Transfer</label>
                            <textarea name="remarks" class="form-control form-control-sm rounded-0 border-dark shadow-none" rows="2" placeholder="OPTIONAL..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-2 bg-light text-center border">
                        <small class="text-muted italic" style="font-size: 0.65rem;">
                            <i class="fa-solid fa-circle-info me-1"></i> This movement will be logged under <strong><?= $_SESSION['username'] ?></strong>
                        </small>
                    </div>
                </div>

                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-0 fw-bold px-3 shadow-none" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-sm btn-dark rounded-0 fw-bold px-4 shadow-none">CONFIRM TRANSFER</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- reallocate modal start -->


<!-- resolve modal start -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="../src/php_script/resolve_damage.php" method="POST">
                <div class="modal-header py-2 px-3 bg-dark text-white">
                    <h5 class="modal-title" style="font-size: 0.75rem; font-weight: 800; letter-spacing: 1px;">RESOLVE ASSET ISSUE</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="modal_asset_id_hidden">
                    <p class="mb-3" style="font-size: 0.75rem;">Updating status for: <strong id="modal_asset_display"></strong></p>
                    
                    <label class="detail-label">Update Condition To:</label>
                    <select name="new_status" class="form-select form-select-sm fw-bold border-2">
                        <option value="GOOD">GOOD (Fixed / Returned to Stock)</option>
                        <option value="REPAIR">REPAIR (Still in maintenance)</option>
                        <option value="DISPOSAL">DISPOSAL (Final Write-off)</option>
                    </select>
                    <div class="mt-2 text-muted" style="font-size: 0.6rem;">This action will be logged in the asset remarks.</div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" name="resolve_asset" class="btn btn-dark btn-sm fw-bold px-4">UPDATE STATUS</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- resolve modal end -->