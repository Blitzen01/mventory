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
    include "../src/fetch/count_total_users_querry.php";

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
                                <h3 class="fw-bold text-dark mb-0">
                                    <i class="fa-solid fa-users-gear me-2 text-dark"></i>User Management
                                </h3>
                                <p class="text-muted small">Manage active users and archived records</p>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fa-solid fa-user-plus"></i> New User
                                </button>
                            </div>
                        </div>

                        <ul class="nav nav-tabs mb-0 bg-white px-3 rounded-top border-bottom-0 shadow-sm" id="userTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bolder" 
                                        id="active-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#active-content" 
                                        type="button" 
                                        style="color: #000000 !important; font-size: 1rem;">
                                    <i class="fa-solid fa-user-check me-2 text-primary"></i>Active Accounts 
                                    <span class="badge bg-primary ms-1"><?php echo htmlspecialchars($total_users); ?></span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bolder" 
                                        id="deleted-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#deleted-content" 
                                        type="button" 
                                        style="color: #000000 !important; font-size: 1rem;">
                                    <i class="fa-solid fa-box-archive me-2 text-danger"></i>Archive Log 
                                    <span class="badge bg-danger ms-1">3</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="active-content">
                                <div class="card shadow-sm border-0 rounded-0 rounded-bottom">
                                    
                                    <div class="card-header bg-white border-bottom p-3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                                    <input type="text" class="form-control border-start-0 bg-light" placeholder="Search user or email...">
                                                </div>
                                            </div>
                                            <div class="col-md-2 ms-auto text-end">
                                                <select class="form-select form-select-sm bg-light">
                                                    <option>Show 10</option>
                                                    <option>Show 20</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

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
                                            <tbody class="bg-white">
                                                <?php
                                                    $user = "SELECT * FROM USERS";
                                                    $user_result = $conn->query($user);
                                                    if ($user_result->num_rows > 0) {
                                                        while($user_row = $user_result->fetch_assoc()) {
                                                            // 1. Logic to get Initials
                                                            $f_initial = !empty($user_row['first_name']) ? substr($user_row['first_name'], 0, 1) : '';
                                                            $l_initial = !empty($user_row['last_name']) ? substr($user_row['last_name'], 0, 1) : '';
                                                            $initials = strtoupper($f_initial . $l_initial);
                                                            
                                                            // 2. Profile Image Check
                                                            $profile_img = $user_row['profile_image']; 
                                                ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center py-1">
                                                                <?php if (!empty($profile_img) && file_exists($profile_img)): ?>
                                                                    <img src="<?= $profile_img ?>" class="me-3 shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5rem;">
                                                                <?php else: ?>
                                                                    <div class="me-3 d-flex align-items-center justify-content-center bg-dark text-white fw-bold shadow-sm rounded-circle" 
                                                                        style="width: 40px; height: 40px; font-size: 0.85rem; border: 1px solid #000; border-radius: 0;">
                                                                        <?= $initials ?>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <div>
                                                                    <div class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">
                                                                        <?php echo $user_row['first_name'] . ' ' . $user_row['last_name']; ?>
                                                                    </div>
                                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                                        <?php echo $user_row['email']; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted font-monospace small">#<?php echo $user_row['role_id']; ?></span>
                                                        </td>
                                                        <td>
                                                            <span class="badge border text-dark fw-normal text-uppercase" style="font-size: 0.65rem; border-radius: 0; letter-spacing: 0.5px;">
                                                                <i class="fa-solid fa-user-shield me-1 opacity-50"></i><?php echo $user_row['role']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php 
                                                                $status = strtoupper($user_row['status']); 
                                                                $status_class = ($status == 'ACTIVE') ? 'text-success' : 'text-danger';
                                                            ?>
                                                            <span class="small fw-bold <?= $status_class ?>" style="font-size: 0.7rem;">
                                                                    <?php 
                                                                        $status = strtoupper($user_row['status']); 
                                                                        // Determine the text and color style
                                                                        $display_text = ($status == 'ACTIVE') ? 'ACTIVATED' : 'DEACTIVATED';
                                                                        $status_color = ($status == 'ACTIVE') ? 'text-dark' : 'text-muted';
                                                                        $icon_type    = ($status == 'ACTIVE') ? 'fa-solid' : 'fa-regular';
                                                                    ?>
                                                                    
                                                                    <span class="fw-bold <?= $status_color ?>" style="font-size: 0.65rem; letter-spacing: 1px;">
                                                                        <i class="<?= $icon_type ?> fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                                        <?= $display_text ?>
                                                                    </span>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php 
                                                                // Use the actual row variable from your loop (e.g., $user_data or $row)
                                                                $status_val = $user_row['is_login']; 
                                                                
                                                                // Define styles based on numeric value
                                                                $display_text = ($status_val == 1) ? 'ONLINE' : 'OFFLINE';
                                                                $status_color = ($status_val == 1) ? 'text-success' : 'text-muted';
                                                                $icon_style   = ($status_val == 1) ? 'fa-solid' : 'fa-regular';
                                                                $pulse_class  = ($status_val == 1) ? 'status-pulse' : '';
                                                            ?>
                                                            
                                                            <span class="fw-bold <?= $status_color ?> <?= $pulse_class ?>" style="font-size: 0.65rem; letter-spacing: 1px;">
                                                                <i class="<?= $icon_style ?> fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                                <?= $display_text ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center pe-4">
                                                            <div class="btn-group btn-group-sm border rounded-0 shadow-sm">
                                                                <button class="btn btn-link text-dark py-1" title="View Profile">
                                                                    <i class="fa-solid fa-expand"></i>
                                                                </button>

                                                                <button class="btn btn-link text-dark py-1 border-start" title="Edit Details">
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </button>

                                                                <?php if (strtoupper($user_row['status']) == 'ACTIVE'): ?>
                                                                    <button class="btn btn-link text-muted py-1 border-start" title="Deactivate User">
                                                                        <i class="fa-solid fa-toggle-on text-dark"></i>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-link text-muted py-1 border-start" title="Activate User">
                                                                        <i class="fa-solid fa-toggle-off"></i>
                                                                    </button>
                                                                <?php endif; ?>

                                                                <button class="btn btn-link text-danger py-1 border-start" title="Archive">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                                </tbody>
                                        </table>
                                    </div>

                                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 border-0 rounded-bottom">
                                        <div class="text-muted small">Showing 1 to 10 of 12 users</div>
                                        <nav>
                                            <ul class="pagination pagination-sm mb-0">
                                                <li class="page-item disabled"><a class="page-link">Previous</a></li>
                                                <li class="page-item active"><a class="page-link bg-dark border-dark">1</a></li>
                                                <li class="page-item"><a class="page-link text-dark">2</a></li>
                                                <li class="page-item"><a class="page-link text-dark">Next</a></li>
                                            </ul>
                                        </nav>
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
                                                    <th class="text-center text-muted small pe-4">RESTORE / WIPE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="bg-light">
                                                    <td class="ps-4 opacity-75 text-muted">
                                                        <div class="fw-bold small">Former Employee</div>
                                                        <div style="font-size: 0.75rem;">ex-staff@email.com</div>
                                                    </td>
                                                    <td><span class="text-muted font-monospace small">#99</span></td>
                                                    <td class="small text-danger fw-bold">Oct 12, 2025</td>
                                                    <td class="text-center pe-4">
                                                        <div class="btn-group btn-group-sm shadow-sm border rounded bg-white">
                                                            <button class="btn btn-link text-success py-1" title="Restore"><i class="fa-solid fa-rotate-left"></i></button>
                                                            <button class="btn btn-link text-danger py-1 border-start" title="Delete Permanently"><i class="fa-solid fa-circle-xmark"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>