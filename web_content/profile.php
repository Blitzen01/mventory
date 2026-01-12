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

    $user = "SELECT * FROM users WHERE user_id = ".$_SESSION['user_id'];
    $user_result = $conn->query($user);
    $user_data = $user_result->fetch_assoc();

    // Fetch Security Logs
    $log_query = "SELECT * FROM security_logs WHERE user_id = ? ORDER BY login_time DESC LIMIT 5";
    $stmt_logs = $conn->prepare($log_query);
    $stmt_logs->bind_param("i", $_SESSION['user_id']);
    $stmt_logs->execute();
    $logs_result = $stmt_logs->get_result();

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>User Profile | Theme Reference</title>
        
        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/varay_logo.png">

        <style>
            /* Profile Specific Styles */
            .avatar-circle-lg {
                width: 100px; height: 100px;
                background: var(--stark-gray);
                display: flex; align-items: center; justify-content: center;
                border-radius: 50%;
                font-weight: 700; color: var(--accent-dark); font-size: 32px;
                border: 1px solid var(--border-color); margin: 0 auto 15px;
            }

            /* Tabs Visibility Fix */
            .nav-tabs { 
                border-bottom: 1px solid var(--border-color); 
                background-color: #ffffff; /* Ensure background is white */
            }
            
            .nav-tabs .nav-link {
                border: none; 
                /* Change this color to something visible like #555 or var(--accent-dark) with opacity */
                color: #555555 !important; 
                font-weight: 700; 
                text-transform: uppercase;
                font-size: 0.75rem; 
                letter-spacing: 1px;
                padding: 1rem 1.5rem;
                transition: all 0.2s ease;
            }

            /* Hover state for better UX */
            .nav-tabs .nav-link:hover {
                color: var(--accent-dark) !important;
                background-color: var(--stark-gray);
            }

            .nav-tabs .nav-link.active {
                color: var(--accent-dark) !important;
                border-bottom: 3px solid var(--accent-dark) !important;
                background: transparent !important;
            }

            .info-label {
                font-size: 0.7rem; text-transform: uppercase;
                color: var(--text-muted); font-weight: 700; 
                letter-spacing: 1px; margin-bottom: 2px;
            }

            .info-value {
                font-size: 0.95rem; color: var(--text-main);
                font-weight: 500; margin-bottom: 15px;
            }

            /* Activity Timeline */
            .activity-timeline { padding: 10px; }
            .activity-item-custom {
                position: relative;
                padding-left: 30px;
                padding-bottom: 20px;
                border-left: 2px solid var(--stark-gray);
            }
            .activity-item-custom:last-child { border-left: 2px solid transparent; }
            .activity-dot {
                position: absolute; left: -7px; top: 5px;
                width: 12px; height: 12px;
                border-radius: 50%; background: var(--accent-dark);
                border: 2px solid var(--bg-card);
            }

            /* Fix for the security table spacing */
            #security-content table {
                width: 100%;
                border-collapse: collapse;
            }

            #security-content th {
                background-color: var(--stark-gray);
                padding: 12px 15px;
                color: var(--accent-dark) !important;
                white-space: nowrap; /* Prevents headers from stacking */
            }

            #security-content td {
                padding: 15px;
                vertical-align: middle;
                border-bottom: 1px solid #eee;
            }

            /* Limit the width of the Browser string so it doesn't push everything over */
            .browser-info {
                max-width: 250px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        </style>

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
                                <h3 class="fw-bold mb-0">
                                    <i class="fa-solid fa-circle-user me-2"></i>MY PROFILE
                                </h3>
                                <p class="text-muted small letter-spacing">MANAGE YOUR ACCOUNT SETTINGS AND LOGS</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card shadow-sm border-0 mb-4 text-center p-4 metric-card-stark">
                                    <div class="avatar-circle-lg bg-dark shadow-sm rounded-circle d-flex align-items-center justify-content-center overflow-hidden border border-2 border-white" 
                                        style="width: 150px; height: 150px;"> <?php 
                                            $f_initial = !empty($user_data['first_name']) ? substr($user_data['first_name'], 0, 1) : '';
                                            $l_initial = !empty($user_data['last_name']) ? substr($user_data['last_name'], 0, 1) : '';
                                            $initials = strtoupper($f_initial . $l_initial) ?: "??";
                                            
                                            $profile_img = $user_data['profile_image'];
                                        ?>

                                        <?php if (!empty($profile_img) && file_exists($profile_img)): ?>
                                            <img src="<?= $profile_img ?>" alt="Profile" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="text-white fw-bold" style="font-size: 1.5rem; letter-spacing: 1px;">
                                                <?= $initials ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="fw-bold mb-0"><?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?></h4>
                                    <p class="text-muted small"><?php echo $user_data['username']; ?></p>
                                    
                                    <div class="mt-2">
                                        <span class="badge bg-dark text-white rounded-0 px-3 py-2 fw-normal small">
                                            <i class="fa-solid fa-user-shield me-1"></i><?php echo $user_data['role']; ?>
                                        </span>
                                    </div>

                                    <hr class="my-4 opacity-10">

                                    <div class="text-start">
                                        <div class="mb-3">
                                            <div class="info-label"><i class="fa-solid fa-calendar-days me-1"></i> Member Since</div>
                                            <div class="info-value"><?php echo $user_data['created_at']; ?></div>
                                        </div>

                                        <div class="mb-1">
                                            <div class="info-label"><i class="fa-solid fa-circle-check me-1"></i> Account Status</div>
                                            <div class="info-value">
                                                <span class="text-success small fw-bold"><?php echo strtoupper($user_data['status']); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-3 opacity-10">

                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <a class="btn btn-outline-dark btn-sm rounded-0 px-3" title="Settings" href="edit_profile.php">
                                            <i class="fa-solid fa-gear"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm rounded-0 px-3" title="Logout" href="logout.php">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <ul class="nav nav-tabs mb-0 bg-white px-3 rounded-top border-bottom-0 shadow-sm" id="profileTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-content" type="button">
                                            PERSONAL DETAILS
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-content" type="button">
                                            RECENT ACTIVITY
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-content" type="button">
                                            SECURITY LOG
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content shadow-sm">
                                    <div class="tab-pane fade show active" id="details-content">
                                        <div class="card border-0 rounded-0 p-4">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <h6 class="fw-bold text-uppercase pb-2 border-bottom" style="font-size: 0.75rem; letter-spacing: 2px;">Account Credentials</h6>
                                                </div>
                                                
                                                <div class="col-md-6 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Username</div>
                                                    <div class="info-value ps-2 mb-0"><?= $user_data['username']; ?></div>
                                                </div>

                                                <div class="col-md-6 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Password</div>
                                                    <div class="info-value ps-2 mb-0 text-muted">••••••••••••</div>
                                                </div>

                                                <div class="col-12 mb-3 mt-2">
                                                    <h6 class="fw-bold text-uppercase pb-2 border-bottom" style="font-size: 0.75rem; letter-spacing: 2px;">Identity Details</h6>
                                                </div>

                                                <div class="col-md-6 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">First Name</div>
                                                    <div class="info-value ps-2 mb-0"><?= !empty($user_data['first_name']) ? $user_data['first_name'] : '<span class="text-muted opacity-50">N/A</span>'; ?></div>
                                                </div>

                                                <div class="col-md-6 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Last Name</div>
                                                    <div class="info-value ps-2 mb-0"><?= !empty($user_data['last_name']) ? $user_data['last_name'] : '<span class="text-muted opacity-50">N/A</span>'; ?></div>
                                                </div>

                                                <div class="col-12 mb-3 mt-2">
                                                    <h6 class="fw-bold text-uppercase pb-2 border-bottom" style="font-size: 0.75rem; letter-spacing: 2px;">Contact Information</h6>
                                                </div>

                                                <div class="col-md-6 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Email Address</div>
                                                    <div class="info-value ps-2 mb-0"><?= !empty($user_data['email']) ? $user_data['email'] : '<span class="text-muted opacity-50">N/A</span>'; ?></div>
                                                </div>

                                                <div class="col-md-6 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Contact Number</div>
                                                    <div class="info-value ps-2 mb-0"><?= !empty($user_data['phone']) ? $user_data['phone'] : '<span class="text-muted opacity-50">N/A</span>'; ?></div>
                                                </div>

                                                <div class="col-12 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Home Address</div>
                                                    <div class="info-value ps-2 mb-0"><?= !empty($user_data['address']) ? $user_data['address'] : '<span class="text-muted opacity-50">N/A</span>'; ?></div>
                                                </div>

                                                <div class="col-12 mb-3 mt-2">
                                                    <h6 class="fw-bold text-uppercase pb-2 border-bottom" style="font-size: 0.75rem; letter-spacing: 2px;">System Metadata</h6>
                                                </div>

                                                <div class="col-md-4 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">User Role</div>
                                                    <div class="info-value ps-2 mb-0 text-uppercase" style="font-size: 0.8rem;"><?= $user_data['role']; ?></div>
                                                </div>

                                                <div class="col-md-4 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Registration Date</div>
                                                    <div class="info-value ps-2 mb-0"><?= date("M d, Y", strtotime($user_data['created_at'])); ?></div>
                                                </div>

                                                <div class="col-md-4 border-start border-4 border-dark mb-4">
                                                    <div class="info-label ps-2">Last Login Detected</div>
                                                    <div class="info-value ps-2 mb-0 text-primary" style="font-size: 0.85rem;">
                                                        <?= !empty($user_data['last_login']) ? date("M d, Y | h:i A", strtotime($user_data['last_login'])) : 'First Session'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="activity-content">
                                        <div class="card border-0 rounded-0 p-0">
                                            <div class="activity-timeline p-4">
                                                <div class="activity-item-custom">
                                                    <div class="activity-dot"></div>
                                                    <div class="fw-bold small">UPDATED PROFILE PICTURE</div>
                                                    <div class="text-muted extra-small" style="font-size: 0.7rem;">Today at 10:45 AM</div>
                                                </div>
                                                <div class="activity-item-custom">
                                                    <div class="activity-dot"></div>
                                                    <div class="fw-bold small">RESOLVED SUPPORT TICKET #4402</div>
                                                    <div class="text-muted extra-small" style="font-size: 0.7rem;">Yesterday at 3:20 PM</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="security-content">
                                        <div class="card border-0 rounded-0 shadow-sm">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="">
                                                        <tr>
                                                            <th class="ps-4 py-3" style="font-size: 0.7rem; letter-spacing: 1px;">DEVICE / BROWSER</th>
                                                            <th class="py-3" style="font-size: 0.7rem; letter-spacing: 1px;">IP ADDRESS</th>
                                                            <th class="py-3" style="font-size: 0.7rem; letter-spacing: 1px;">DATE & TIME</th>
                                                            <th class="text-center pe-4 py-3" style="font-size: 0.7rem; letter-spacing: 1px;">STATUS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white">
                                                        <?php if ($logs_result->num_rows > 0): ?>
                                                            <?php while($log = $logs_result->fetch_assoc()): 
                                                                // --- BROWSER DETECTION LOGIC START ---
                                                                $ua = $log['browser'];
                                                                $platform = "Unknown Device";
                                                                $browser  = "Unknown Browser";

                                                                if (preg_match('/windows|win32/i', $ua)) { $platform = 'Windows'; }
                                                                elseif (preg_match('/macintosh|mac os x/i', $ua)) { $platform = 'Mac'; }
                                                                elseif (preg_match('/android/i', $ua)) { $platform = 'Android'; }
                                                                elseif (preg_match('/iphone/i', $ua)) { $platform = 'iPhone'; }

                                                                if (preg_match('/edge/i', $ua)) { $browser = 'Edge'; }
                                                                elseif (preg_match('/chrome/i', $ua)) { $browser = 'Chrome'; }
                                                                elseif (preg_match('/firefox/i', $ua)) { $browser = 'Firefox'; }
                                                                elseif (preg_match('/safari/i', $ua)) { $browser = 'Safari'; }
                                                                // --- BROWSER DETECTION LOGIC END ---
                                                            ?>
                                                            <tr>
                                                                <td class="ps-4">
                                                                    <div class="fw-bold small text-uppercase">
                                                                        <i class="fa-solid fa-desktop me-2 text-muted"></i>
                                                                        <?= "$browser on $platform" ?>
                                                                    </div>
                                                                    <div class="text-muted extra-small d-block d-md-none" style="font-size: 0.6rem;">
                                                                        <?= substr($ua, 0, 30) ?>...
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-light text-dark border font-monospace fw-normal">
                                                                        <?= $log['ip_address'] == '::1' ? 'Localhost' : $log['ip_address']; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="small fw-bold"><?= date("M d, Y", strtotime($log['login_time'])); ?></div>
                                                                    <div class="text-muted" style="font-size: 0.7rem;"><?= date("h:i A", strtotime($log['login_time'])); ?></div>
                                                                </td>
                                                                <td class="text-center pe-4">
                                                                    <?php if ($log['is_login'] == 1): ?>
                                                                        <span class="badge bg-dark rounded-0 fw-normal px-3">LOG IN</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary opacity-50 rounded-0 fw-normal px-3" style="font-size: 0.65rem;">LOG OUT</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <?php endwhile; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center py-5 text-muted">
                                                                    <i class="fa-solid fa-shield-halved d-block mb-2 opacity-25" style="font-size: 2rem;"></i>
                                                                    No recent security activity found.
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
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
            </div>
        </div>
    </body>
</html>