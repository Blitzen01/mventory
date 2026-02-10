<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "../render/connection.php"; 
include "../src/cdn/cdn_links.php";
include "../render/modals.php";

$limit = 10;

// Pagination Helper Function
function get_offset($param, $limit) {
    $page = isset($_GET[$param]) && is_numeric($_GET[$param]) ? (int)$_GET[$param] : 1;
    return ($page < 1) ? 0 : ($page - 1) * $limit;
}

$offset = get_offset('page', $limit);
$arc_offset = get_offset('arc_page', $limit);
$page = (int)($offset / $limit) + 1;
$arc_page = (int)($arc_offset / $limit) + 1;

// Fetch Counts
$total_active = $conn->query("SELECT COUNT(*) as total FROM USERS")->fetch_assoc()['total'];
$total_archived = $conn->query("SELECT COUNT(*) as total FROM deleted_users")->fetch_assoc()['total'];

$total_pages = ceil($total_active / $limit);
$total_arc_pages = ceil($total_archived / $limit);

// Fetch Data (Using Template Literals for clarity)
$user_result = $conn->query("SELECT * FROM USERS LIMIT $limit OFFSET $offset");
$archived_result = $conn->query("SELECT * FROM deleted_users LIMIT $limit OFFSET $arc_offset");
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>User Management</title>
        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="stylesheet" href="../src/style/accounts_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">

        <style>
            .btn-monochrome-outline {
                background-color: transparent;
                border: 1px solid #d1d1d1;
                color: #333333;
                font-weight: 500;
            }

            .btn-monochrome-outline:hover {
                background-color: #f8f9fa;
                border-color: #1a1a1a;
                color: #000000;
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-lg-2">
                <?php include "../nav/sidebar_nav.php"; ?>
            </div>
            <div class="col">
                <div class="main-content">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                            <div>
                                <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users-gear me-2"></i>User Management</h3>
                                <p class="text-muted small">Manage active users and archived records</p>
                            </div>
                            <button class="btn btn-monochrome-outline" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fa-solid fa-user-plus me-2"></i>New User
                            </button>
                        </div>

                        <ul class="nav nav-tabs mb-0 bg-white px-3 rounded-top border-bottom-0 shadow-sm" id="userTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bolder" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-content" type="button" style="color: #000 !important;">
                                    <i class="fa-solid fa-user-check me-2 text-primary"></i>Active Accounts <span class="badge bg-primary ms-1"><?= $total_active ?></span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bolder" id="deleted-tab" data-bs-toggle="tab" data-bs-target="#deleted-content" type="button" style="color: #000 !important;">
                                    <i class="fa-solid fa-box-archive me-2 text-danger"></i>Archive Log <span class="badge bg-danger ms-1"><?= $total_archived ?></span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="active-content">
                                <div class="card shadow-sm border-0 rounded-0 rounded-bottom">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="ps-4 text-muted small">USER DETAILS</th>
                                                    <th class="text-muted small">ID</th>
                                                    <th class="text-muted small">ROLE</th>
                                                    <th class="text-muted small text-center">ACCOUNT STATUS</th>
                                                    <th class="text-muted small text-center">LOGIN STATUS</th>
                                                    <th class="text-center text-muted small pe-4">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($row = $user_result->fetch_assoc()): 
                                                    $initials = strtoupper(substr($row['first_name'],0,1).substr($row['last_name'],0,1));
                                                ?>
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center py-1">
                                                            <?php if (!empty($row['profile_image']) && file_exists($row['profile_image'])): ?>
                                                                <img src="<?= $row['profile_image'] ?>" class="me-3 shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5rem;">
                                                            <?php else: ?>
                                                                <div class="me-3 d-flex align-items-center justify-content-center bg-dark text-white fw-bold shadow-sm rounded-circle" style="width: 40px; height: 40px; font-size: 0.85rem; border: 1px solid #000;"><?= $initials ?></div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="fw-bold text-dark mb-0 small text-uppercase"><?= $row['first_name'].' '.$row['last_name'] ?></div>
                                                                <div class="text-muted" style="font-size: 0.75rem;"><?= $row['email'] ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted font-monospace small">#<?= $row['user_id'] ?></span></td>
                                                    <td><span class="badge border text-dark fw-normal text-uppercase" style="font-size: 0.65rem;"><?= $row['role'] ?></span></td>
                                                    <td class="text-center">
                                                        <span class="fw-bold <?= ($row['status'] == 'ACTIVE') ? 'text-dark' : 'text-muted' ?>" style="font-size: 0.65rem; letter-spacing: 1px;">
                                                            <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i><?= ($row['status'] == 'active') ? 'ACTIVATED' : 'DEACTIVATED' ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="fw-bold <?= ($row['is_login'] == 1) ? 'text-success status-pulse' : 'text-muted' ?>" style="font-size: 0.65rem; letter-spacing: 1px;">
                                                            <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i><?= ($row['is_login'] == 1) ? 'ONLINE' : 'OFFLINE' ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center pe-4">
                                                        <div class="btn-group btn-group-sm border rounded-0 shadow-sm">
                                                            <button class="btn btn-link text-dark py-1" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#viewUserModal"
                                                                    data-id="<?= $row['user_id'] ?>"
                                                                    data-username="<?= $row['username'] ?>"
                                                                    data-fname="<?= $row['first_name'] ?>"
                                                                    data-lname="<?= $row['last_name'] ?>"
                                                                    data-email="<?= $row['email'] ?>"
                                                                    data-role="<?= $row['role'] ?>"
                                                                    data-status="<?= $row['status'] ?>"
                                                                    data-phone="<?= $row['phone'] ?>" 
                                                                    data-address="<?= $row['address'] ?? 'No address provided' ?>"
                                                                    data-created="<?= date('M d, Y', strtotime($row['created_at'])) ?>"
                                                                    data-lastlogin="<?= $row['last_login'] ? date('M d, Y h:i A', strtotime($row['last_login'])) : 'Never' ?>"
                                                                    data-img="<?= $row['profile_image'] ?>"
                                                                    data-initials="<?= $initials ?>">
                                                                <i class="fa-solid fa-expand"></i>
                                                            </button>
                                                            <button type="button" 
                                                                class="btn btn-sm btn-link " 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editUserModal"
                                                                data-id="<?= $row['user_id']; ?>"
                                                                data-username="<?= $row['username']; ?>"
                                                                data-status="<?= $row['status']; ?>">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <button type="button" 
                                                                class="btn btn-sm btn-link text-danger border-start" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteUserModal"
                                                                data-id="<?= $row['user_id'] ?>"
                                                                data-username="<?= $row['username'] ?>"
                                                                data-fname="<?= $row['first_name'] ?>"
                                                                data-lname="<?= $row['last_name'] ?>"
                                                                data-email="<?= $row['email'] ?>"
                                                                data-role="<?= $row['role'] ?>"
                                                                data-status="<?= $row['status'] ?>"
                                                                data-phone="<?= $row['phone'] ?>" 
                                                                data-address="<?= $row['address'] ?? 'No address provided' ?>"
                                                                data-created="<?= date('M d, Y', strtotime($row['created_at'])) ?>"
                                                                data-lastlogin="<?= $row['last_login'] ? date('M d, Y h:i A', strtotime($row['last_login'])) : 'Never' ?>"
                                                                data-img="<?= $row['profile_image'] ?>"
                                                                data-initials="<?= $initials ?>">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 border-0">
                                        <div class="text-muted small">Showing <?= $offset+1 ?> to <?= min($offset+$limit, $total_active) ?> of <?= $total_active ?> users</div>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $page-1 ?>">Previous</a></li>
                                            <li class="page-item active"><a class="page-link bg-dark border-dark"><?= $page ?></a></li>
                                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>"><a class="page-link text-dark" href="?page=<?= $page+1 ?>">Next</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="deleted-content">
                                <div class="card shadow-sm border-0 rounded-0 rounded-bottom border-top border-danger border-4">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="ps-4 text-muted small">ARCHIVED USER</th>
                                                    <th class="text-muted small">ORIGINAL ID</th>
                                                    <th class="text-muted small">DATE DELETED</th>
                                                    <th class="text-center text-muted small pe-4">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($arc = $archived_result->fetch_assoc()): 
                                                     $arc_initials = strtoupper(substr($arc['first_name'],0,1).substr($arc['last_name'],0,1));
                                                ?>
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center py-1 opacity-75">
                                                            <?php if (!empty($arc['profile_image']) && file_exists($arc['profile_image'])): ?>
                                                                <img src="<?= $arc['profile_image'] ?>" class="me-3 grayscale shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5rem; filter: grayscale(100%);">
                                                            <?php else: ?>
                                                                <div class="me-3 d-flex align-items-center justify-content-center bg-secondary text-white fw-bold rounded-circle shadow-sm" style="width: 40px; height: 40px; font-size: 0.85rem; border: 1px solid #6c757d;"><?= $arc_initials ?></div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="fw-bold text-dark mb-0 small text-uppercase"><?= $arc['first_name'].' '.$arc['last_name'] ?></div>
                                                                <div class="text-muted" style="font-size: 0.75rem;"><?= $arc['email'] ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted font-monospace small">#<?= $arc['user_id'] ?></span></td>
                                                    <td class="small text-danger fw-bold"><?= date("M d, Y", strtotime($arc['deleted_at'])) ?></td>
                                                    <td class="text-center pe-4">
                                                        <div class="btn-group btn-group-sm border rounded-0 shadow-sm bg-white">
                                                            <button class="btn btn-link text-dark py-1" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#viewArchiveModal"
                                                                    data-id="<?= $arc['user_id'] ?>"
                                                                    data-username="<?= $arc['username'] ?>"
                                                                    data-fname="<?= $arc['first_name'] ?>"
                                                                    data-lname="<?= $arc['last_name'] ?>"
                                                                    data-email="<?= $arc['email'] ?>"
                                                                    data-role="<?= $arc['role'] ?>"
                                                                    data-phone="<?= $arc['phone'] ?>"
                                                                    data-address="<?= $arc['address'] ?? 'No address recorded' ?>"
                                                                    data-deleted="<?= date('M d, Y h:i A', strtotime($arc['deleted_at'])) ?>"
                                                                    data-img="<?= $arc['profile_image'] ?>"
                                                                    data-initials="<?= $arc_initials ?>">
                                                                <i class="fa-solid fa-expand"></i>
                                                            </button>
                                                            <button class="btn btn-link text-success py-1 border-start" 
                                                                    title="Restore"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#restoreUserModal"
                                                                    data-id="<?= $arc['user_id'] ?>"
                                                                    data-name="<?= $arc['first_name'].' '.$arc['last_name'] ?>">
                                                                <i class="fa-solid fa-rotate-left"></i>
                                                            </button>
                                                            <button class="btn btn-link text-danger py-1 border-start" 
                                                                    title="Wipe"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#wipeAccountModal"
                                                                    data-id="<?= $arc['user_id'] ?>"
                                                                    data-name="<?= $arc['first_name'].' '.$arc['last_name'] ?>">
                                                                <i class="fa-solid fa-circle-xmark"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 border-0">
                                        <div class="text-muted small">Showing <?= $arc_offset+1 ?> to <?= min($arc_offset+$limit, $total_archived) ?> of <?= $total_archived ?> archives</div>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item <?= ($arc_page <= 1) ? 'disabled' : '' ?>"><a class="page-link" href="?arc_page=<?= $arc_page-1 ?>">Previous</a></li>
                                            <li class="page-item active"><a class="page-link bg-danger border-danger"><?= $arc_page ?></a></li>
                                            <li class="page-item <?= ($arc_page >= $total_arc_pages) ? 'disabled' : '' ?>"><a class="page-link text-dark" href="?arc_page=<?= $arc_page+1 ?>">Next</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script src="../src/script/main_script.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // --- 1. VIEW USER MODAL ---
                const viewUserModal = document.getElementById('viewUserModal');
                if (viewUserModal) {
                    viewUserModal.addEventListener('show.bs.modal', function (event) {
                        const btn = event.relatedTarget;
                        document.getElementById('view_full_name').textContent = btn.getAttribute('data-fname') + ' ' + btn.getAttribute('data-lname');
                        document.getElementById('view_username_display').textContent = '@' + btn.getAttribute('data-username');
                        document.getElementById('view_user_id').textContent = '#' + btn.getAttribute('data-id');
                        document.getElementById('view_email').textContent = btn.getAttribute('data-email');
                        document.getElementById('view_role').textContent = btn.getAttribute('data-role');
                        document.getElementById('view_status').textContent = btn.getAttribute('data-status').toUpperCase();
                        document.getElementById('view_phone').textContent = btn.getAttribute('data-phone') || 'N/A';
                        document.getElementById('view_address').textContent = btn.getAttribute('data-address');
                        document.getElementById('view_created_at').textContent = btn.getAttribute('data-created');
                        document.getElementById('view_last_login').textContent = btn.getAttribute('data-lastlogin');

                        const img = btn.getAttribute('data-img');
                        const initials = btn.getAttribute('data-initials');
                        const container = document.getElementById('view_profile_img_container');
                        if (img && img !== '' && img !== 'None') {
                            container.innerHTML = `<img src="${img}" class="shadow" style="width: 110px; height: 110px; object-fit: cover; border-radius: 50%; border: 4px solid #fff;">`;
                        } else {
                            container.innerHTML = `<div class="d-flex align-items-center justify-content-center bg-dark text-white fw-bold shadow rounded-circle" style="width: 110px; height: 110px; font-size: 2rem; border: 4px solid #fff;">${initials}</div>`;
                        }
                    });
                }

                // --- 2. EDIT USER MODAL ---
                const editModal = document.getElementById('editUserModal');
                if (editModal) {
                    editModal.addEventListener('show.bs.modal', function (event) {
                        const btn = event.relatedTarget;
                        document.getElementById('edit_user_id').value = btn.getAttribute('data-id');
                        document.getElementById('edit_username').value = btn.getAttribute('data-username');
                        document.getElementById('edit_status').value = btn.getAttribute('data-status');
                    });
                }

                // --- 3. DELETE (ARCHIVE) MODAL with PASSWORD CHECK ---
                const deleteModal = document.getElementById('deleteUserModal');
                if (deleteModal) {
                    // Data Mapping (the "View Style" look)
                    deleteModal.addEventListener('show.bs.modal', function (event) {
                        const btn = event.relatedTarget;
                        document.getElementById('del_full_name').textContent = btn.getAttribute('data-fname') + ' ' + btn.getAttribute('data-lname');
                        document.getElementById('del_username_display').textContent = '@' + btn.getAttribute('data-username');
                        document.getElementById('del_user_id_text').textContent = '#' + btn.getAttribute('data-id');
                        document.getElementById('del_user_id_input').value = btn.getAttribute('data-id');
                        document.getElementById('del_email').textContent = btn.getAttribute('data-email');
                        document.getElementById('del_role').textContent = btn.getAttribute('data-role');
                        document.getElementById('del_status').textContent = btn.getAttribute('data-status');
                        document.getElementById('del_phone').textContent = btn.getAttribute('data-phone') || 'N/A';
                        document.getElementById('del_created_at').textContent = btn.getAttribute('data-created');

                        const img = btn.getAttribute('data-img');
                        const initials = btn.getAttribute('data-initials');
                        const imgContainer = document.getElementById('del_profile_img_container');
                        if (img && img !== '' && img !== 'None') {
                            imgContainer.innerHTML = `<img src="${img}" class="rounded-circle shadow border border-3 border-white" style="width: 80px; height: 80px; object-fit: cover;">`;
                        } else {
                            imgContainer.innerHTML = `<div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center shadow border border-3 border-white fw-bold" style="width: 80px; height: 80px; font-size: 1.5rem;">${initials}</div>`;
                        }

                        // Reset password field and button when opening
                        document.getElementById('admin_pwd_check').value = '';
                        document.getElementById('btn_confirm_archive').disabled = true;
                        document.getElementById('pwd_feedback').textContent = '';
                    });

                    // Real-time Password Check
                    let timeout = null;
                    document.getElementById('admin_pwd_check').addEventListener('input', function () {
                        const pwd = this.value;
                        const feedback = document.getElementById('pwd_feedback');
                        const submitBtn = document.getElementById('btn_confirm_archive');

                        clearTimeout(timeout);
                        if (pwd.length < 1) { feedback.textContent = ""; submitBtn.disabled = true; return; }

                        timeout = setTimeout(() => {
                            feedback.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
                            fetch('../render/password_checker.php', { // Updated to match your filename
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: 'password=' + encodeURIComponent(pwd)
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    feedback.innerHTML = '<span class="text-success small"><i class="fa-solid fa-check"></i> Verified</span>';
                                    submitBtn.disabled = false;
                                } else {
                                    feedback.innerHTML = '<span class="text-danger small"><i class="fa-solid fa-xmark"></i> Incorrect</span>';
                                    submitBtn.disabled = true;
                                }
                            });
                        }, 500);
                    });
                }

                // --- 4. RESTORE USER MODAL ---
                const restoreModal = document.getElementById('restoreUserModal');
                if (restoreModal) {
                    restoreModal.addEventListener('show.bs.modal', function (event) {
                        const btn = event.relatedTarget;
                        document.getElementById('restore_user_id').value = btn.getAttribute('data-id');
                        document.getElementById('restore_name').textContent = btn.getAttribute('data-name');
                    });
                }

                // --- 5. VIEW ARCHIVE MODAL ---
                const viewArchiveModal = document.getElementById('viewArchiveModal');
                if (viewArchiveModal) {
                    viewArchiveModal.addEventListener('show.bs.modal', function (event) {
                        const btn = event.relatedTarget;
                        document.getElementById('arc_full_name').textContent = btn.getAttribute('data-fname') + ' ' + btn.getAttribute('data-lname');
                        document.getElementById('arc_username_display').textContent = '@' + btn.getAttribute('data-username');
                        document.getElementById('arc_user_id').textContent = '#' + btn.getAttribute('data-id');
                        document.getElementById('arc_email').textContent = btn.getAttribute('data-email');
                        document.getElementById('arc_role_display').textContent = btn.getAttribute('data-role');
                        document.getElementById('arc_phone').textContent = btn.getAttribute('data-phone') || 'N/A';
                        document.getElementById('arc_address').textContent = btn.getAttribute('data-address');
                        document.getElementById('arc_deleted_at').textContent = btn.getAttribute('data-deleted');

                        const img = btn.getAttribute('data-img');
                        const initials = btn.getAttribute('data-initials');
                        const container = document.getElementById('arc_profile_img_container');
                        let imageHtml = (img && img !== '' && img !== 'None') 
                            ? `<img src="${img}" class="shadow" style="width: 110px; height: 110px; object-fit: cover; border-radius: 50%; border: 4px solid #fff;">`
                            : `<div class="d-flex align-items-center justify-content-center bg-secondary text-white fw-bold shadow rounded-circle" style="width: 110px; height: 110px; font-size: 2rem; border: 4px solid #fff;">${initials}</div>`;
                        
                        container.innerHTML = `${imageHtml}<div class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 2px solid #fff;"><i class="fa-solid fa-box-archive" style="font-size: 0.8rem;"></i></div>`;
                    });
                }
            });
            
            // delete user modal (separate to avoid conflicts)
            let timeout = null;
            document.getElementById('admin_pwd_check').addEventListener('input', function () {
                const pwd = this.value;
                const feedback = document.getElementById('pwd_feedback');
                const submitBtn = document.getElementById('btn_confirm_archive');

                clearTimeout(timeout);
                
                // Don't check if it's too short (most passwords are 8+ chars)
                if (pwd.length < 4) { 
                    feedback.textContent = ""; 
                    submitBtn.disabled = true; 
                    return; 
                }

                // Reduced delay from 500ms to 200ms
                timeout = setTimeout(() => {
                    feedback.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
                    
                    fetch('../render/password_checker.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'password=' + encodeURIComponent(pwd)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            feedback.innerHTML = '<span class="text-success small"><i class="fa-solid fa-check"></i> Verified</span>';
                            submitBtn.disabled = false;
                        } else {
                            feedback.innerHTML = '<span class="text-danger small"><i class="fa-solid fa-xmark"></i> Incorrect</span>';
                            submitBtn.disabled = true;
                        }
                    })
                    .catch(err => {
                        feedback.innerHTML = '<span class="text-warning small">Error connecting...</span>';
                    });
                }, 200); // Faster response
            });

            // GENERATE USERNAME AND PASSWORD
            document.addEventListener('DOMContentLoaded', function() {
                const lastNameInput = document.getElementById('gen_last_name');
                const usernameDisplay = document.getElementById('display_username');
                const passwordDisplay = document.getElementById('display_password');

                // 1. Function to generate Username (LastName + Random Number)
                function updateUsername() {
                    const lastName = lastNameInput.value.trim().toLowerCase().replace(/\s+/g, '');
                    if (lastName) {
                        const randomNumber = Math.floor(1000 + Math.random() * 9000); // Generates 4-digit number
                        usernameDisplay.value = lastName + randomNumber;
                    } else {
                        usernameDisplay.value = "---";
                    }
                }

                // 2. Function to generate Random Password
                window.generateNewPassword = function() {
                    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                    let password = "";
                    for (let i = 0; i < 10; i++) {
                        password += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    passwordDisplay.value = password;
                };

                // 3. Helper to copy to clipboard
                window.copyToClipboard = function(elementId, btn) {
                    const copyText = document.getElementById(elementId);
                    if (copyText.value === "---") return;
                    
                    navigator.clipboard.writeText(copyText.value);
                    
                    // Brief UI feedback
                    const icon = btn.querySelector('i');
                    icon.classList.replace('fa-copy', 'fa-check');
                    setTimeout(() => icon.classList.replace('fa-check', 'fa-copy'), 1500);
                };

                // Listen for typing in Last Name field
                lastNameInput.addEventListener('input', updateUsername);

                // Generate initial password when modal is opened or script loads
                generateNewPassword();
            });

            // Script to pass data to the modal
            const wipeModal = document.getElementById('wipeAccountModal');
            wipeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-id');
                const userName = button.getAttribute('data-name');

                wipeModal.querySelector('#wipeUserId').value = userId;
                wipeModal.querySelector('#wipeUserName').textContent = userName;
            });
        </script>
    </body>
</html>