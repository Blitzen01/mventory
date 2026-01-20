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

<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="../src/php_script/process_add_item.php" method="POST">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Add Hardware Assets</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="fw-bold small">Asset ID Prefix</label>
                            <input type="text" name="id_prefix" class="form-control" placeholder="e.g. PC-SET" required>
                        </div>
                        <div class="col-md-2">
                            <label class="fw-bold small">Qty</label>
                            <input type="number" name="bulk_qty" class="form-control" value="1" min="1">
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

                        <div class="col-md-6">
                            <label class="fw-bold small">Item Name</label>
                            <input type="text" name="item_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-danger">Specifications (Fixed)</label>
                            <input type="text" name="specs" class="form-control" placeholder="CPU, RAM, Storage">
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold small">Location (Assigned To)</label>
                            <input type="text" name="assigned_to" class="form-control">
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
                            <label class="fw-bold small">Initial Status</label>
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
                            <label class="fw-bold small text-primary">Initial Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Maintenance notes or extra details..."></textarea>
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