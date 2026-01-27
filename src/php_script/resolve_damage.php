<?php
    // 1. SESSION AND CORE LOGIC FIRST (No HTML output before this)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }

    include "../../render/connection.php"; 
    date_default_timezone_set('Asia/Manila');

    // 2. PROCESS UPDATE (BEFORE ANY INCLUDES OR HTML)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_asset'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
        $admin_user = $_SESSION['username'] ?? "System Admin";
        $currentDateTime = date('m/d/Y h:i A');

        // Fetch existing remarks to append new log
        $fetch = mysqli_query($conn, "SELECT condition_status, remarks FROM assets WHERE id = '$id' LIMIT 1");
        $current_data = mysqli_fetch_assoc($fetch);
        
        $old_status = $current_data['condition_status'] ?? 'N/A';
        $existing_remarks = $current_data['remarks'] ?? '';

        // Create log entry
        $log_entry = "[$currentDateTime] RESOLVED: Status changed from '$old_status' to '$new_status' by $admin_user.";
        $combined_remarks = mysqli_real_escape_string($conn, $log_entry . PHP_EOL . $existing_remarks);

        // Update Query
        $update_sql = "UPDATE assets SET 
                    condition_status = '$new_status', 
                    remarks = '$combined_remarks' 
                    WHERE id = '$id'";
                    
        if(mysqli_query($conn, $update_sql)) {
            header("Location: " . "../../web_content/damage.php" . "?status=success");
            exit();
        }
    }
?>