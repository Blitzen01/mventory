<?php
session_start();
include '../../render/connection.php';
date_default_timezone_set('Asia/Manila');

// Enable MySQLi exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_id          = $_POST['asset_db_id'];
    $scope          = $_POST['damage_scope']; 
    $recommendation = $_POST['recommendation']; // Values like "FOR REPAIR (Internal)", etc.
    $description    = $_POST['damage_description'];
    $admin_user     = $_SESSION['username'] ?? "System Admin";
    $currentDateTime = date('m/d/Y h:i A');

    try {
        // 1. Fetch current asset details (Using correct column: condition_status)
        $stmt = $conn->prepare("SELECT item_name, remarks, condition_status FROM assets WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $db_id);
        $stmt->execute();
        $asset = $stmt->get_result()->fetch_assoc();

        if (!$asset) {
            throw new Exception("Asset not found.");
        }

        // 2. Determine the New Condition Status
        // Instead of keeping the old status, we use the Recommendation chosen in the dropdown.
        // If it's the 'entire' unit, we can prefix it with 'Defective' or just use the recommendation.
        $new_status = $recommendation; 

        // 3. Prepare the Remarks (History Log)
        $scope_label = ($scope === 'entire') ? "ENTIRE UNIT" : "COMPONENT/PART";
        $new_log_entry = "[$currentDateTime] DAMAGE: ($scope_label) $description. Action: $recommendation by $admin_user";
        
        // Append new entry to the top of old remarks
        $combined_remarks = $new_log_entry . PHP_EOL . ($asset['remarks'] ?? '');

        // 4. Update Database using a Transaction
        mysqli_begin_transaction($conn);

        // ACTION A: Update 'condition_status' and 'remarks'
        $update_stmt = $conn->prepare("UPDATE assets SET condition_status = ?, remarks = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $new_status, $combined_remarks, $db_id);
        $update_stmt->execute();

        // ACTION B: Add to the Edit Log Table
        $log_details = "Damage Reported [$scope_label]: $description. Recommendation: $recommendation";
        $insert_log = $conn->prepare("INSERT INTO edit_log (asset_id, admin_username, change_details, date_updated) VALUES (?, ?, ?, NOW())");
        $insert_log->bind_param("iss", $db_id, $admin_user, $log_details);
        $insert_log->execute();

        mysqli_commit($conn);
        header("Location: ../../web_content/inventory.php?status=damage_reported");
        exit();

    } catch (Exception $e) {
        if ($conn) mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}