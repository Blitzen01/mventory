<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    include "../render/connection.php"; 
    include "../src/cdn/cdn_links.php";

    $settings = [];
    if (isset($conn)) {
        $sql_settings = "SELECT setting_key, setting_value FROM system_settings";
        $result_settings = mysqli_query($conn, $sql_settings);
        
        if ($result_settings) {
            while ($row = mysqli_fetch_assoc($result_settings)) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
    }

    // Mapping Database keys to Variables
    $settings_app_name = $settings['app_name'] ?? "M Ventory";
    $settings_app_logo = $settings['app_logo'] ?? "varay_logo.png";
    $date_format       = $settings['date_format'] ?? "d/m/Y";
    
    // Inventory Logic
    $liquidation      = $settings['liquidation_percentage'] ?? "60";
    $critical_stock   = $settings['low_stock_threshold_percent'] ?? "10";
    $primary_unit     = $settings['default_unit_of_measure'] ?? "Pieces";
    
    // Security
    $min_pass         = $settings['password_min_length'] ?? "10";
    $last_backup      = $settings['last_backup_datetime'] ?? "Never Recorded";
    
    // Boolean Switches (Checks if value is "1")
    $dark_mode        = ($settings['dark_mode_default'] ?? "0") == "1" ? "checked" : "";
    $alert_stock      = ($settings['trigger_low_stock'] ?? "0") == "1" ? "checked" : "";
    $alert_reg        = ($settings['trigger_new_user'] ?? "0") == "1" ? "checked" : "";
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>System Settings | <?php echo $settings_app_name; ?></title>

        <link rel="stylesheet" href="../src/style/main_style.css">
        <link rel="icon" type="image/png" href="../src/image/logo/<?php echo $settings_app_logo; ?>">

        <style>
            .settings-card { border: 1px solid var(--border-color); border-radius: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
            .settings-nav { background-color: #fff; height: 100%; }
            .settings-nav .nav-link {
                text-align: left; padding: 1.2rem 1.5rem; color: var(--text-main) !important;
                border-bottom: 1px solid var(--stark-gray); transition: all 0.2s ease;
                font-weight: 600; font-size: 0.85rem; text-transform: uppercase;
                letter-spacing: 0.5px; border-radius: 0; background: transparent;
            }
            .settings-nav .nav-link i { width: 25px; opacity: 0.6; }
            .settings-nav .nav-link:hover { background-color: var(--stark-gray); }
            .settings-nav .nav-link.active {
                color: var(--accent-dark) !important; background-color: #fff !important;
                border-left: 4px solid var(--accent-dark); font-weight: 800;
            }
            .section-title { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
            .form-label { font-weight: 700; font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); margin-top: 15px; }
            .form-control, .form-select { border-radius: 0; border: 1px solid var(--border-color); padding: 0.75rem; }

            .btn-black {
                background: var(--accent-dark); color: #fff; border-radius: 0;
                font-weight: 700; text-transform: uppercase; font-size: 0.8rem;
                padding: 12px 25px; border: none; transition: all 0.2s;
            }
            /* Fixed Hover State */
            .btn-black:hover { 
                opacity: 0.9; 
                color: #ffffff !important; 
                background-color: #000000 !important; 
            }

            .info-status-box { background: var(--stark-gray); border: 1px solid var(--border-color); border-radius: 0; }
            .btn-outline-dark { border: 1px solid var(--accent-dark); border-radius: 0; color: var(--accent-dark); transition: all 0.2s; }
        </style>
    </head>

    <body>
        <div class="row m-0">
            <div class="col-lg-2 p-0">  
                <?php include "../nav/sidebar_nav.php"; ?>
            </div>
            <div class="col">
                <div class="main-content">
                    <div class="container pb-5">
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-7">
                                <h3 class="section-title mb-1"><i class="fa-solid fa-sliders me-2"></i> Control Panel</h3>
                                <p class="text-muted small">MANAGE SYSTEM LOGIC AND SECURITY THRESHOLDS</p>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <?php if(isset($_SESSION['status'])): ?>
                                    <div class="alert alert-success d-inline-block py-2 px-3 mb-0 border-0 rounded-0 shadow-sm" role="alert">
                                        <small class="fw-bold"><i class="fa-solid fa-circle-check me-2"></i> <?php echo $_SESSION['status']; unset($_SESSION['status']); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card settings-card">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-lg-3 border-end">
                                        <div class="nav flex-column nav-pills settings-nav" id="settingsTabs" role="tablist">
                                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab"><i class="fa-solid fa-window-maximize"></i> General</button>
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#inventory" type="button" role="tab"><i class="fa-solid fa-boxes-stacked"></i> Inventory</button>
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#alerts" type="button" role="tab"><i class="fa-solid fa-bell"></i> Alerts</button>
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab"><i class="fa-solid fa-shield-halved"></i> Security</button>
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#backup" type="button" role="tab"><i class="fa-solid fa-database"></i> Database</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-9 bg-white">
                                        <div class="tab-content p-4 p-md-5" id="settingsTabContent">
                                            
                                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                                <form action="update_settings.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="scope" value="general">
                                                    <h5 class="section-title mb-4">Application Identity</h5>
                                                    <div class="mb-4">
                                                        <label class="form-label">Software Name</label>
                                                        <input type="text" name="app_name" class="form-control" value="<?php echo htmlspecialchars($settings_app_name); ?>">
                                                    </div>
                                                    <div class="mb-4">
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-2">
                                                                <img src="../src/image/logo/<?php echo htmlspecialchars($settings_app_logo); ?>" style="width: 80px; height: 80px; object-fit: contain;" class="border p-1">
                                                            </div>
                                                            <div class="col-lg-10">
                                                                <small class="text-muted d-block mb-2">Current Logo: <?php echo $settings_app_logo; ?></small>
                                                                <input type="file" name="app_logo_upload" class="form-control" accept="image/*">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <label class="form-label">System Date Format</label>
                                                            <select name="date_format" class="form-select">
                                                                <option value="Y-m-d" <?php echo $date_format == 'Y-m-d' ? 'selected' : ''; ?>>Standard (YYYY-MM-DD)</option>
                                                                <option value="m/d/Y" <?php echo $date_format == 'm/d/Y' ? 'selected' : ''; ?>>US (MM/DD/YYYY)</option>
                                                                <option value="d/m/Y" <?php echo $date_format == 'd/m/Y' ? 'selected' : ''; ?>>UK (DD/MM/YYYY)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mt-4 pt-2">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="dark_mode_default" <?php echo $dark_mode; ?>>
                                                                <label class="form-check-label small fw-bold">DEFAULT DARK THEME</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-black">Save General Changes</button>
                                                </form>
                                            </div>

                                            <div class="tab-pane fade" id="inventory" role="tabpanel">
                                                <form action="update_settings.php" method="POST">
                                                    <input type="hidden" name="scope" value="inventory">
                                                    <h5 class="section-title mb-4">Inventory Logic</h5>
                                                    <div class="row g-4 mb-4">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Critical Stock Trigger (%)</label>
                                                            <div class="input-group">
                                                                <input type="number" name="low_stock_threshold_percent" class="form-control" value="<?php echo $critical_stock; ?>">
                                                                <span class="input-group-text bg-white">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Liquidation Alert (%)</label>
                                                            <div class="input-group">
                                                                <input type="number" name="liquidation_percentage" class="form-control" value="<?php echo $liquidation; ?>">
                                                                <span class="input-group-text bg-white">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label">Primary Unit Type</label>
                                                        <input type="text" name="default_unit_of_measure" class="form-control" value="<?php echo $primary_unit; ?>">
                                                    </div>
                                                    <button type="submit" class="btn btn-black">Save Inventory Rules</button>
                                                </form>
                                            </div>

                                            <div class="tab-pane fade" id="alerts" role="tabpanel">
                                                <form action="update_settings.php" method="POST">
                                                    <input type="hidden" name="scope" value="notifications">
                                                    <h5 class="section-title mb-4">Alert Configurations</h5>
                                                    <div class="list-group list-group-flush mb-4 border rounded-0">
                                                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <p class="mb-0 fw-bold small">STOCK THRESHOLD ALERTS</p>
                                                                <small class="text-muted">Broadcast warnings when items hit low levels.</small>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="trigger_low_stock" <?php echo $alert_stock; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <p class="mb-0 fw-bold small">ACCOUNT REGISTRATION ALERTS</p>
                                                                <small class="text-muted">Notify admin when new staff are created.</small>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="trigger_new_user" <?php echo $alert_reg; ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-black">Update Preferences</button>
                                                </form>
                                            </div>

                                            <div class="tab-pane fade" id="security" role="tabpanel">
                                                <form action="update_settings.php" method="POST">
                                                    <input type="hidden" name="scope" value="security">
                                                    <h5 class="section-title mb-4">Access & Audit</h5>
                                                    <div class="mb-4">
                                                        <label class="form-label">Min Password Complexity (Length)</label>
                                                        <input type="number" name="password_min_length" class="form-control" value="<?php echo $min_pass; ?>">
                                                    </div>
                                                    <div class="info-status-box p-3 mb-4 border-start border-dark border-4">
                                                        <div class="small fw-bold"><i class="fa-solid fa-circle-exclamation me-2"></i> AUDIT LOGGING ACTIVE</div>
                                                        <div class="small text-muted">Detailed user audit logging is currently enabled.</div>
                                                    </div>
                                                    <button type="submit" class="btn btn-black">Update Security</button>
                                                </form>
                                            </div>

                                            <div class="tab-pane fade" id="backup" role="tabpanel">
                                                <h5 class="section-title mb-4">Maintenance & Recovery</h5>
                                                <div class="info-status-box p-3 mb-4 border-start border-dark border-4">
                                                    <div class="small fw-bold text-uppercase">System Last Recorded Backup</div>
                                                    <div class="h6 mb-0 mt-1 fw-bold"><?php echo $last_backup; ?></div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <form action="backup_handler.php" method="POST">
                                                            <button type="submit" name="export_csv" class="btn btn-outline-dark w-100 py-4 border-1 rounded-0">
                                                                <i class="fa-solid fa-file-export fa-2x mb-2"></i><br>
                                                                <strong class="small">EXPORT INVENTORY CSV</strong>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <form action="backup_handler.php" method="POST">
                                                            <button type="submit" name="download_sql" class="btn btn-outline-dark w-100 py-4 border-1 rounded-0">
                                                                <i class="fa-solid fa-database fa-2x mb-2"></i><br>
                                                                <strong class="small">DOWNLOAD SQL SNAPSHOT</strong>
                                                            </button>
                                                        </form>
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
            </div>
        </div>
    </body>
</html>