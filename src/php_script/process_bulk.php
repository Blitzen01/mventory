<?php
session_start();
include '../../render/connection.php';
date_default_timezone_set('Asia/Manila');

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ids']) && is_array($_POST['ids'])) {

    $ids = $_POST['ids'];
    $action = $_POST['bulk_action'] ?? '';
    $admin_user = $_SESSION['username'] ?? "System Admin";
    $currentDateTime = date('m/d/Y h:i A'); // Display format for remarks
    $mysqlDateTime = date('Y-m-d H:i:s');   // Database format

    try {
        mysqli_begin_transaction($conn);

        foreach ($ids as $db_id) {
            if (!is_numeric($db_id)) continue;

            // 1. Fetch current details
            $stmt = $conn->prepare("SELECT item_name, assigned_to, remarks FROM assets WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $db_id);
            $stmt->execute();
            $asset = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$asset) continue;

            $old_remarks = $asset['remarks'] ?? '';
            $item_name = $asset['item_name'];
            $old_holder = $asset['assigned_to'] ?? 'Unassigned';

            // --- ALLOCATE ACTION ---
            if ($action === 'allocate') {
                $new_holder = $_POST['assignee'] ?? 'EDD';
                $alloc_date = $_POST['allocation_date'] ?? date('Y-m-d');
                $new_status = ($new_holder === 'EDD') ? 'In Stock' : 'Deployed';

                $log_entry = "[$currentDateTime] BULK ALLOCATED: From '$old_holder' to '$new_holder' by $admin_user.";
                $combined_remarks = $log_entry . PHP_EOL . $old_remarks;

                // Update Asset
                $stmt = $conn->prepare("UPDATE assets SET assigned_to = ?, remarks = ?, status = ? WHERE id = ?");
                $stmt->bind_param("sssi", $new_holder, $combined_remarks, $new_status, $db_id);
                $stmt->execute();
                $stmt->close();

                // Insert Allocation Log
                $stmt = $conn->prepare("INSERT INTO allocation_log (asset_id, previous_assignee, new_assignee, allocated_by, allocation_date) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $db_id, $old_holder, $new_holder, $admin_user, $alloc_date);
                $stmt->execute();
                $stmt->close();
            }

            // --- DAMAGE ACTION ---
            elseif ($action === 'damage') {
                $recommendation = $_POST['recommendation'] ?? 'DEFECTIVE';
                $description = $_POST['damage_description'] ?? 'Bulk Report';

                $log_entry = "[$currentDateTime] BULK DAMAGE: $description. Action: $recommendation by $admin_user";
                $combined_remarks = $log_entry . PHP_EOL . $old_remarks;

                // 1. Update Asset Condition
                $stmt = $conn->prepare("UPDATE assets SET condition_status = ?, remarks = ? WHERE id = ?");
                $stmt->bind_param("ssi", $recommendation, $combined_remarks, $db_id);
                $stmt->execute();
                $stmt->close();

                // 2. Insert into edit_log
                $log_details = "Bulk Damage: $description. Recommendation: $recommendation. Item: $item_name";

                // We use NULL for 'id' because it is AUTO_INCREMENT
                // Structure: id, asset_id, admin_username, change_details, date_updated
                $stmt = $conn->prepare("INSERT INTO edit_log (asset_id, admin_username, change_details, date_updated) VALUES (?, ?, ?, NOW())");
                
                // "iss" matches: asset_id (int), admin_username (string), change_details (string)
                $stmt->bind_param("iss", $db_id, $admin_user, $log_details);
                
                if (!$stmt->execute()) {
                    throw new Exception("Edit Log Insert Failed: " . $stmt->error);
                }
                $stmt->close();
            }
        }

        mysqli_commit($conn);
        header("Location: ../../web_content/inventory.php?status=bulk_success");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        // Error reporting for troubleshooting
        die("Bulk Error: " . $e->getMessage());
    }
} else {
    header("Location: ../../web_content/inventory.php?error=no_selection");
    exit();
}