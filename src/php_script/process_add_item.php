<?php
session_start(); // Required to get the admin_username from the session
include "../../render/connection.php";

if (isset($_POST['save_bulk'])) {
    // 1. Get Admin Username from Session
    $admin_username = isset($_SESSION['username']) ? mysqli_real_escape_string($conn, $_SESSION['username']) : 'System';

    $prefix           = strtoupper(mysqli_real_escape_string($conn, $_POST['id_prefix'])); 
    $bulk_qty         = (int)$_POST['bulk_qty'];
    $category_id      = mysqli_real_escape_string($conn, $_POST['category_id']);
    $item_name        = mysqli_real_escape_string($conn, $_POST['item_name']);
    $brand            = mysqli_real_escape_string($conn, $_POST['brand']);
    $model            = mysqli_real_escape_string($conn, $_POST['model']);
    $serial_number    = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $assigned_to      = mysqli_real_escape_string($conn, $_POST['assigned_to']);
    $condition_status = mysqli_real_escape_string($conn, $_POST['condition_status']);
    $status           = mysqli_real_escape_string($conn, $_POST['status']);
    $arrival_date     = $_POST['arrival_date'];
    $purchase_date    = !empty($_POST['purchase_date']) ? $_POST['purchase_date'] : NULL;
    $cost             = !empty($_POST['cost']) ? $_POST['cost'] : 0;
    $warranty_cost    = !empty($_POST['warranty_cost']) ? $_POST['warranty_cost'] : 0;
    $specs            = mysqli_real_escape_string($conn, $_POST['specs']);
    $remarks          = mysqli_real_escape_string($conn, $_POST['remarks']);

    // Find current sequence
    $check_query = "SELECT asset_id FROM assets WHERE asset_id LIKE '$prefix-%' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    
    $start_count = 0;
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['asset_id']; 
        $parts = explode('-', $last_id);
        $start_count = (int)end($parts);
    }

    // Detail message for the log
    $change_details = "Bulk added asset: $item_name ($brand $model)";

    for ($i = 1; $i <= $bulk_qty; $i++) {
        $new_number = $start_count + $i;
        $unique_asset_id = $prefix . "-" . $new_number;

        // 1. Insert into assets table
        $sql = "INSERT INTO assets (
            asset_id, category_id, item_name, brand, model, serial_number,
            assigned_to, condition_status, status, 
            arrival_date, purchase_date, cost, warranty_cost, 
            specs, remarks, quantity
        ) VALUES (
            '$unique_asset_id', '$category_id', '$item_name', '$brand', '$model', '$serial_number',
            '$assigned_to', '$condition_status', '$status', 
            '$arrival_date', ".($purchase_date ? "'$purchase_date'" : "NULL").", '$cost', '$warranty_cost', 
            '$specs', '$remarks', 1
        )";
        
        if (mysqli_query($conn, $sql)) {
            // 2. GET THE NUMERIC ID of the asset just inserted
            // This is what the Foreign Key constraint (edit_log_ibfk_1) is looking for
            $last_inserted_id = mysqli_insert_id($conn);

            // 3. Insert into edit_logs table using that numeric ID
            $log_sql = "INSERT INTO edit_log (
                asset_id, admin_username, change_details, date_updated
            ) VALUES (
                '$last_inserted_id', '$admin_username', 'Bulk added asset: $unique_asset_id', NOW()
            )";
            
            mysqli_query($conn, $log_sql);
        }
    }

    header("Location: ../../web_content/inventory.php?success=1");
    exit();
}
?>