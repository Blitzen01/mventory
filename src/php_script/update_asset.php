<?php
session_start();
include '../../render/connection.php';
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_id = $_POST['asset_db_id'];
    $admin_user = $_SESSION['username'] ?? "System Admin";
    $currentDateTime = date('m/d/Y h:i A');

    // 1. Fetch CURRENT data (including the Category Name via JOIN)
    $fetch_old = "SELECT a.*, c.category_name 
                  FROM assets a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  WHERE a.id = '$db_id' LIMIT 1";
    $old_res = mysqli_query($conn, $fetch_old);
    $old = mysqli_fetch_assoc($old_res);

    $new_name = $_POST['item_name'];
    $new_cat_id  = $_POST['category_id'];
    $new_cond = $_POST['condition_status'];

    $changes = [];

    // --- Check Name Change ---
    if ($old['item_name'] != $new_name) {
        $changes[] = "Name ('{$old['item_name']}' → '$new_name')";
    }

    // --- Check Category Change (Fetching the New Name) ---
    if ($old['category_id'] != $new_cat_id) {
        // Look up the name of the NEW category ID
        $cat_query = mysqli_query($conn, "SELECT category_name FROM categories WHERE category_id = '$new_cat_id'");
        $cat_row = mysqli_fetch_assoc($cat_query);
        $new_cat_name = $cat_row['category_name'] ?? 'Unknown';

        $changes[] = "Category Name ('{$old['category_name']}' → '$new_cat_name')";
    }

    // --- Check Condition Change ---
    if ($old['condition_status'] != $new_cond) {
        $changes[] = "Condition ('{$old['condition_status']}' → '$new_cond')";
    }

    // 2. Construct the Remark
    if (!empty($changes)) {
        $change_log = implode(", ", $changes);
        $formatted_new_remark = "[$currentDateTime] EDITED: Changed $change_log. Note by $admin_user";
    } else {
        $formatted_new_remark = "[$currentDateTime] EDITED: No data changes made by $admin_user";
    }

    // 3. Combine and Final Escape
    $combined_remarks = $formatted_new_remark . PHP_EOL . $old['remarks'];
    $final_remarks = mysqli_real_escape_string($conn, $combined_remarks);
    
    $s_name = mysqli_real_escape_string($conn, $new_name);

    $sql = "UPDATE assets SET 
            item_name = '$s_name', 
            category_id = '$new_cat_id', 
            condition_status = '$new_cond',
            remarks = '$final_remarks' 
            WHERE id = '$db_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../web_content/inventory.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}