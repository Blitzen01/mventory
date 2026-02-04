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

    $user_id = $_SESSION['user_id'];
$msg = "";
$msg_type = "dark";

// --- UPDATE LOGIC ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name  = $conn->real_escape_string($_POST['last_name']);
    $username   = $conn->real_escape_string($_POST['username']);
    $email      = $conn->real_escape_string($_POST['email']);
    $phone      = $conn->real_escape_string($_POST['phone']);
    $address    = $conn->real_escape_string($_POST['address']);
    
    $password_update_sql = "";
    if(!empty($_POST['new_password'])) {
        if($_POST['new_password'] === $_POST['confirm_password']) {
            $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $password_update_sql = ", password = '$new_pass'";
        } else {
            $msg = "PASSWORDS DO NOT MATCH";
            $msg_type = "danger";
        }
    }

    $image_update_sql = "";
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../src/image/profiles/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $file_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $image_update_sql = ", profile_image = '$target_file'";
        }
    }

    if ($msg_type !== "danger") {
        $sql = "UPDATE users SET 
                first_name = '$first_name', 
                last_name = '$last_name', 
                username = '$username', 
                email = '$email', 
                phone = '$phone', 
                address = '$address' 
                $password_update_sql 
                $image_update_sql
                WHERE user_id = $user_id";

        if($conn->query($sql)) {
            $msg = "ACCOUNT SUCCESSFULLY UPDATED";
            $msg_type = "dark";
        } else {
            $msg = "DATABASE ERROR: " . $conn->error;
            $msg_type = "danger";
        }
    }
}

// --- FETCH USER DATA ---
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

// --- FORMATTED USER ID LOGIC ---
// This creates the ID like 202401003
if ($user_data) {
    $date_part = date("Ym", strtotime($user_data['created_at']));
    $padded_id = str_pad($user_data['user_id'], 3, "0", STR_PAD_LEFT);
    $display_user_id = $date_part . $padded_id;
} else {
    $display_user_id = "N/A";
}

$password_length_query = "SELECT * FROM system_settings WHERE setting_key = 'password_min_length'";
$length_result = mysqli_query($conn, $password_length_query);
$min_password_length = ($length_result && $length_result->num_rows > 0) ? (int)trim($length_result->fetch_assoc()['setting_value']) : 8;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings | Modern Monochrome</title>
    <link rel="stylesheet" href="../src/style/main_style.css">
    <style>
        :root {
            --bg-main: #ffffff;
            --accent: #000000;
            --muted-gray: #f8f9fa;
            --border-gray: #e0e0e0;
            --readonly-bg: #f2f2f2;
        }

        body { background-color: var(--bg-main); font-family: 'Inter', sans-serif; color: #000; }
        .profile-card { border: 1px solid var(--border-gray); border-radius: 0; background: #fff; }
        
        .image-overlay-container {
            position: relative; width: 140px; height: 140px;
            overflow: hidden; background: #000; margin: 0 auto; cursor: pointer;
        }
        .image-overlay-container img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
        
        .upload-btn-wrapper {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); color: #fff; display: flex; align-items: center; 
            justify-content: center; font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px; opacity: 0; transition: 0.3s;
        }
        .image-overlay-container:hover .upload-btn-wrapper { opacity: 1; }

        .form-label-mono {
            font-size: 10px; font-weight: 900; color: #000;
            letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px; display: block;
        }

        .input-mono {
            border: 1px solid var(--border-gray); border-radius: 0; padding: 12px;
            font-size: 14px; transition: 0.2s; background: transparent; width: 100%;
        }
        .input-mono:focus { border-color: var(--accent); box-shadow: none; background-color: var(--muted-gray); outline: none; }
        
        /* Readonly Styling */
        .input-readonly { background-color: var(--readonly-bg) !important; color: #666; cursor: not-allowed; border-style: dashed; }

        .btn-black {
            background: #000; 
            color: #fff; 
            border-radius: 0;
            padding: 14px 35px; 
            font-weight: 800; 
            font-size: 11px;
            letter-spacing: 2px; 
            border: 1px solid #000; 
            transition: 0.3s; 
            cursor: pointer;
        }
        .btn-black:hover { 
            background: #ffffffff; 
            color: #000000ff;
            border: 1px solid #000;
        }

        .btn-outline-dark {
            background: transparent; 
            color: #000; 
            border-radius: 0;
            padding: 14px 35px; 
            font-weight: 800; 
            font-size: 11px;
            letter-spacing: 2px; 
            border: 1px solid #000; 
            transition: 0.3s; 
            cursor: pointer;
        }

        .btn-outline-dark:hover { 
            background: #000; 
            color: #fff;
        }

        .section-divider {
            border-left: 5px solid #000; padding-left: 15px; margin: 40px 0 20px 0;
            font-weight: 900; letter-spacing: 1px; font-size: 13px; text-transform: uppercase;
        }
        .pass-container { position: relative; }
        .toggle-password { position: absolute; right: 15px; top: 38px; cursor: pointer; color: #666; }
    </style>
</head>

<body>
    <div class="row g-0">
        <div class="col-lg-2">
            <?php include "../nav/sidebar_nav.php"; ?>
        </div>
        <div class="col">
            <div class="main-content p-5">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex justify-content-between align-items-end mb-5 border-bottom pb-4">
                        <div>
                            <h1 class="fw-black m-0" style="font-size: 2rem; letter-spacing: -1px;">ACCOUNT SETTINGS</h1>
                            <p class="text-muted small m-0 uppercase-tracking">Manage your profile database records and security</p>
                        </div>
                        <div>
                            <a class="btn btn-outline-dark" href="profile.php">RETURN</a>
                            <button type="submit" name="update_profile" class="btn btn-black">SAVE ALL CHANGES</button>
                        </div>
                    </div>

                    <?php if($msg): ?>
                        <div class="alert alert-<?= $msg_type ?> rounded-0 border-0 py-3 small fw-bold mb-4"><?= $msg ?></div>
                    <?php endif; ?>

                    <div class="row g-5">
                        <div class="col-md-4">
                            <div class="profile-card p-4 text-center">
                                <div class="image-overlay-container shadow-sm mb-3" onclick="document.getElementById('file-upload').click();">
                                    <?php if(!empty($user_data['profile_image'])): ?>
                                        <img src="<?= $user_data['profile_image'] ?>" id="profile-display">
                                    <?php else: ?>
                                        <div id="placeholder-icon" class="d-flex align-items-center justify-content-center h-100 bg-dark text-white">
                                            <i class="fa-solid fa-user fa-3x"></i>
                                        </div>
                                        <img id="profile-display" style="display:none; width:100%; height:100%; object-fit:cover;">
                                    <?php endif; ?>
                                    <div class="upload-btn-wrapper">Change Photo</div>
                                </div>
                                <input id="file-upload" type="file" name="profile_image" style="display:none;" accept="image/*" onchange="previewImage(this)">
                                
                                <h5 class="fw-bold mb-1 mt-3"><?= strtoupper($user_data['username']) ?></h5>
                                <p class="text-muted small mb-0">Member since: <?= date('M Y', strtotime($user_data['created_at'])) ?></p>
                            </div>

                            <div class="section-divider">System Metadata</div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label-mono">User ID</label>
                                    <input type="text" class="input-mono input-readonly" value="<?= $display_user_id ?>" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="form-label-mono">Status</label>
                                    <input type="text" class="input-mono input-readonly" value="<?= strtoupper($user_data['status']) ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-mono">Internal Role ID</label>
                                    <input type="text" class="input-mono input-readonly" value="<?= $user_data['role_id'] ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-mono">Last Login</label>
                                    <input type="text" class="input-mono input-readonly" value="<?= $user_data['last_login'] ?? 'NEVER' ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="section-divider">General Information</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-mono">Username</label>
                                    <input type="text" name="username" class="input-mono" value="<?= htmlspecialchars($user_data['username']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-mono">Email Address</label>
                                    <input type="email" name="email" class="input-mono" value="<?= htmlspecialchars($user_data['email']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-mono">First Name</label>
                                    <input type="text" name="first_name" class="input-mono" value="<?= htmlspecialchars($user_data['first_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-mono">Last Name</label>
                                    <input type="text" name="last_name" class="input-mono" value="<?= htmlspecialchars($user_data['last_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-mono">Phone (11 Digits)</label>
                                    <input type="text" name="phone" class="input-mono" value="<?= htmlspecialchars($user_data['phone']) ?>" maxlength="11">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-mono">Public Role</label>
                                    <input type="text" class="input-mono input-readonly" value="<?= strtoupper($user_data['role']) ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-mono">Residential Address</label>
                                    <textarea name="address" class="input-mono" rows="2"><?= htmlspecialchars($user_data['address']) ?></textarea>
                                </div>
                            </div>

                            <div class="section-divider">Security & Access</div>
                            <div class="row g-3">
                                <div class="col-md-6 pass-container">
                                    <label class="form-label-mono">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="input-mono" placeholder="••••••••">
                                    <i class="fa-solid fa-eye toggle-password" onclick="togglePass('new_password', this)"></i>
                                    
                                    <div class="mt-2 d-flex flex-wrap gap-2" style="font-size: 9px; letter-spacing: 0.5px; font-weight: 800;">
                                        <span id="req-length" class="text-muted"><i class="fa-solid fa-circle-dot me-1"></i>MIN <?= $min_password_length ?> CHARS</span>
                                        <span id="req-upper" class="text-muted"><i class="fa-solid fa-circle-dot me-1"></i>UPPERCASE</span>
                                        <span id="req-lower" class="text-muted"><i class="fa-solid fa-circle-dot me-1"></i>LOWERCASE</span>
                                        <span id="req-number" class="text-muted"><i class="fa-solid fa-circle-dot me-1"></i>NUMBER</span>
                                        <span id="req-special" class="text-muted"><i class="fa-solid fa-circle-dot me-1"></i>SPECIAL</span>
                                    </div>
                                </div>
                                <div class="col-md-6 pass-container">
                                    <label class="form-label-mono">Confirm New Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="input-mono" placeholder="••••••••">
                                    <i class="fa-solid fa-eye toggle-password" onclick="togglePass('confirm_password', this)"></i>
                                    
                                    <div class="mt-2" style="font-size: 9px; letter-spacing: 0.5px; font-weight: 800;">
                                        <span id="req-match" class="text-muted"><i class="fa-solid fa-circle-dot me-1"></i>PASSWORDS MATCH</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 border bg-light d-flex align-items-center">
                                        <i class="fa-solid fa-clock-rotate-left me-3"></i>
                                        <p class="m-0 small fw-bold text-uppercase" style="font-size: 9px;">
                                            Account Created: <?= $user_data['created_at'] ?> | 
                                            Account Active: <?= $user_data['is_active'] ? 'YES' : 'NO' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePass(inputId, icon) {
            const field = document.getElementById(inputId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile-display');
                    const placeholder = document.getElementById('placeholder-icon');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('new_password').addEventListener('input', function() {
            const val = this.value;
            const minLength = <?= $min_password_length ?>;

            // Validation Logic
            const criteria = {
                'req-length': val.length >= minLength,
                'req-upper': /[A-Z]/.test(val),
                'req-lower': /[a-z]/.test(val),
                'req-number': /[0-9]/.test(val),
                'req-special': /[^A-Za-z0-9]/.test(val)
            };

            // Update UI for each requirement
            for (const [id, met] of Object.entries(criteria)) {
                const element = document.getElementById(id);
                if (met) {
                    element.classList.remove('text-muted');
                    element.style.color = '#198754'; // Professional Green
                    element.querySelector('i').classList.replace('fa-circle-dot', 'fa-check-circle');
                } else {
                    element.classList.add('text-muted');
                    element.style.color = ''; 
                    element.querySelector('i').classList.replace('fa-check-circle', 'fa-circle-dot');
                }
            }
        });

        const newPass = document.getElementById('new_password');
        const confirmPass = document.getElementById('confirm_password');
        const matchLabel = document.getElementById('req-match');
        const saveBtn = document.querySelector('button[name="update_profile"]');

        function validatePasswords() {
            const p1 = newPass.value;
            const p2 = confirmPass.value;

            // 1. Primary Requirements Logic (Same as before)
            const minLength = <?= $min_password_length ?>;
            const criteria = {
                'req-length': p1.length >= minLength,
                'req-upper': /[A-Z]/.test(p1),
                'req-lower': /[a-z]/.test(p1),
                'req-number': /[0-9]/.test(p1),
                'req-special': /[^A-Za-z0-9]/.test(p1)
            };

            let allMet = true;
            for (const [id, met] of Object.entries(criteria)) {
                const el = document.getElementById(id);
                if (met) {
                    el.style.color = '#198754';
                    el.querySelector('i').classList.replace('fa-circle-dot', 'fa-check-circle');
                } else {
                    el.style.color = '';
                    el.querySelector('i').classList.replace('fa-check-circle', 'fa-circle-dot');
                    allMet = false;
                }
            }

            // 2. Confirm Match Logic
            // Only show green if the match is perfect AND p1 is not empty
            if (p1 === p2 && p1 !== "") {
                matchLabel.style.color = '#198754';
                matchLabel.querySelector('i').classList.replace('fa-circle-dot', 'fa-check-circle');
            } else {
                matchLabel.style.color = '';
                matchLabel.querySelector('i').classList.replace('fa-check-circle', 'fa-circle-dot');
            }

            // 3. Optional: Disable Save Button if password fields are used but invalid
            if (p1 !== "" || p2 !== "") {
                const passwordsMatch = (p1 === p2);
                saveBtn.disabled = !(allMet && passwordsMatch);
                saveBtn.style.opacity = (allMet && passwordsMatch) ? "1" : "0.5";
            } else {
                saveBtn.disabled = false; // Allow saving if user is just updating profile info, not password
                saveBtn.style.opacity = "1";
            }
        }

        // Attach listeners to both fields
        newPass.addEventListener('input', validatePasswords);
        confirmPass.addEventListener('input', validatePasswords);
    </script>
</body>
</html>