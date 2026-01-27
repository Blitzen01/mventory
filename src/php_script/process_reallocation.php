<?php
session_start();
include '../../render/connection.php';

date_default_timezone_set('Asia/Manila');
$conn->query("SET time_zone = '+08:00'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use the raw POST value first to check if it exists
    $db_id = isset($_POST['asset_id']) ? intval($_POST['asset_id']) : 0; 
    $new_assignee = mysqli_real_escape_string($conn, $_POST['new_assignee']);
    $user_remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $admin_user   = $_SESSION['username'] ?? "System Admin"; 

    if ($db_id === 0) {
        die("Error: No Asset ID provided.");
    }

    // Fetch current data using the Numeric ID
    $stmt = $conn->prepare("SELECT assigned_to, remarks FROM assets WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $db_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) { 
        // If this dies, it means the ID sent from the modal doesn't exist in the 'assets' table
        die("Error: Asset with ID " . htmlspecialchars($db_id) . " not found in database."); 
    }

    $old_holder    = !empty($row['assigned_to']) ? $row['assigned_to'] : 'Unassigned';
    $existing_logs = $row['remarks'] ?? '';
    
    $currentDateTimeLog = date('m/d/Y h:i A'); 
    $dbTimestamp        = date('Y-m-d H:i:s'); 

    $remark_entry = "[$currentDateTimeLog] ALLOCATED: From '$old_holder' to '$new_assignee' by $admin_user.";
    if (!empty($user_remarks)) { $remark_entry .= " Note: $user_remarks"; }
    
    $combined_remarks = $remark_entry . PHP_EOL . $existing_logs;

    mysqli_begin_transaction($conn);

    try {
        // Update Asset
        $stmt_upd = $conn->prepare("UPDATE assets SET assigned_to = ?, remarks = ?, status = 'Deployed' WHERE id = ?");
        $stmt_upd->bind_param("ssi", $new_assignee, $combined_remarks, $db_id);
        $stmt_upd->execute();

        // Update Log
        $insert_log = "INSERT INTO allocation_log (asset_id, previous_assignee, new_assignee, allocated_by, allocation_date) 
                       VALUES (?, ?, ?, ?, ?)";
        
        $stmt_log = $conn->prepare($insert_log);
        $stmt_log->bind_param("issss", $db_id, $old_holder, $new_assignee, $admin_user, $dbTimestamp);
        $stmt_log->execute();

        mysqli_commit($conn);
        header("Location: ../../web_content/inventory.php?status=reallocated");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Transaction failed: " . $e->getMessage());
    }
}