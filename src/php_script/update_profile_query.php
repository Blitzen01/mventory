<?php
ob_start();
// 1. Session and Connection Setup
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "../render/connection.php"; 

$user_id = $_SESSION['user_id'];
$msg = "";
$msg_type = "dark";

// 2. Fetch System Settings (Password Length)
$min_password_length = 8; // Default fallback
$stmt_len = $conn->prepare("SELECT `setting_value` FROM system_settings WHERE setting_key = 'password_min_length' LIMIT 1");
$stmt_len->execute();
$res_len = $stmt_len->get_result();
if ($row = $res_len->fetch_assoc()) {
    $min_password_length = (int)$row['setting_value'];
}

// 3. Handle POST Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    
    // Collect and trim inputs
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $address    = trim($_POST['address']);
    
    $error_occurred = false;
    $password_hashed = null;
    $image_path = null;

    // --- Password Logic ---
    if(!empty($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if(strlen($new_password) < $min_password_length) {
            $msg = "PASSWORD MUST BE AT LEAST $min_password_length CHARACTERS";
            $msg_type = "danger";
            $error_occurred = true;
        } elseif($new_password !== $confirm_password) {
            $msg = "PASSWORDS DO NOT MATCH";
            $msg_type = "danger";
            $error_occurred = true;
        } else {
            $password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        }
    }

    // --- Image Upload Logic (Path: ../src/image/profile_picture/) ---
    if(!$error_occurred && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../src/image/profile_picture/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        if(in_array($file_ext, $allowed_ext)) {
            // Using the format from your reference: username_timestamp.extension
            $file_name = $username . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $file_name;

            if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        } else {
            $msg = "INVALID IMAGE FORMAT (JPG, PNG, WEBP ONLY)";
            $msg_type = "danger";
            $error_occurred = true;
        }
    }

    // 4. Final Database Update with Prepared Statements
    if (!$error_occurred) {
        $query = "UPDATE users SET first_name=?, last_name=?, username=?, email=?, phone=?, address=?";
        $params = [$first_name, $last_name, $username, $email, $phone, $address];
        $types = "ssssss";

        if ($password_hashed) {
            $query .= ", password=?";
            $params[] = $password_hashed;
            $types .= "s";
        }

        if ($image_path) {
            $query .= ", profile_image=?";
            $params[] = $image_path;
            $types .= "s";
        }

        $query .= " WHERE user_id=?";
        $params[] = $user_id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if($stmt->execute()) {
            // 1. Set the session message
            $_SESSION['success_msg'] = "ACCOUNT SUCCESSFULLY UPDATED";
            // 4. Terminate PHP to prevent further execution
            exit(); 
        } else {
            $msg = "DATABASE ERROR: " . $conn->error;
            $msg_type = "danger";
        }
    }
}

    // 5. Fetch Final Data for Display
    $stmt_fetch = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt_fetch->bind_param("i", $user_id);
    $stmt_fetch->execute();
    $user_data = $stmt_fetch->get_result()->fetch_assoc();
    ob_end_flush();
?>