<?php
session_start();
include '../../render/connection.php';
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_id      = $_POST['asset_db_id'];
    $new_holder = mysqli_real_escape_string($conn, $_POST['assignee']);
    $alloc_date = $_POST['allocation_date'];
    
    $admin_user = $_SESSION['username'] ?? "System Admin"; 

    if (!is_numeric($db_id)) {
        die("Error: Invalid ID received.");
    }

    // Fetch current asset details
    $query = mysqli_query($conn, "SELECT assigned_to, remarks, status FROM assets WHERE id = '$db_id' LIMIT 1");
    
    if ($row = mysqli_fetch_assoc($query)) {
        $old_holder  = $row['assigned_to'] ?? 'Unassigned';
        $old_remarks = $row['remarks'] ?? '';
        $currentDateTime = date('m/d/Y h:i A');
        
        $remark_entry = "[$currentDateTime] ALLOCATED: From '$old_holder' to '$new_holder' by $admin_user.";
        $combined_remarks = $remark_entry . PHP_EOL . $old_remarks;
        $escaped_remarks  = mysqli_real_escape_string($conn, $combined_remarks);

        // --- Logic for Status Change ---
        // If assigned to EDD, keep/set as In Stock, otherwise Deployed
        $new_status = ($new_holder === 'EDD') ? 'In Stock' : 'Deployed';

        mysqli_begin_transaction($conn);

        try {
            /**
             * 1. UPDATE ASSETS TABLE 
             * status is now determined by the $new_status variable
             */
            $update_asset = "UPDATE assets SET 
                             assigned_to = '$new_holder', 
                             remarks = '$escaped_remarks',
                             status = '$new_status' 
                             WHERE id = '$db_id'";
            
            if (!mysqli_query($conn, $update_asset)) {
                throw new Exception("Failed to update asset status.");
            }

            // 2. INSERT INTO ALLOCATION_LOG
            $insert_log = "INSERT INTO allocation_log (asset_id, previous_assignee, new_assignee, allocated_by, allocation_date) 
                           VALUES ('$db_id', '$old_holder', '$new_holder', '$admin_user', '$alloc_date')";
            
            if (!mysqli_query($conn, $insert_log)) {
                throw new Exception("Failed to create allocation log.");
            }
            mysqli_commit($conn);
            $search   = $_POST['search'] ?? '';
            $limit    = $_POST['limit'] ?? '10';
            $category = $_POST['category'] ?? ''; // Only if applicable

            // 2. Build the query string
            $query_params = http_build_query([
                'status'   => 'allocated',
                'search'   => $search,
                'limit'    => $limit,
                'category' => $category
            ]);

            // 3. Redirect back with filters intact
            header("Location: ../../web_content/inventory.php?$query_params");
            exit();

        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Asset not found.";
    }
}