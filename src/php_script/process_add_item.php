<?php
include "../../render/connection.php";

if (isset($_POST['save_bulk'])) {
    $prefix           = strtoupper($_POST['id_prefix']); 
    $bulk_qty         = (int)$_POST['bulk_qty'];
    $category_id      = $_POST['category_id'];
    $item_name        = $_POST['item_name'];
    $brand            = $_POST['brand'];
    $assigned_to      = $_POST['assigned_to']; // Used as Location
    $condition_status = $_POST['condition_status']; // The new column
    $status           = $_POST['status'];
    $arrival_date     = $_POST['arrival_date'];
    $cost             = $_POST['cost'];
    $specs            = $_POST['specs'];

    // Find the current sequence for this prefix
    $check_query = "SELECT asset_id FROM assets WHERE asset_id LIKE '$prefix-%' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    
    $start_count = 0;
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['asset_id']; 
        $parts = explode('-', $last_id);
        $start_count = (int)end($parts);
    }

    // Loop to create individual rows
    for ($i = 1; $i <= $bulk_qty; $i++) {
        $new_number = $start_count + $i;
        $unique_asset_id = $prefix . "-" . $new_number;

        $sql = "INSERT INTO assets (
            asset_id, category_id, item_name, brand, 
            assigned_to, condition_status, status, 
            arrival_date, cost, specs, quantity
        ) VALUES (
            '$unique_asset_id', '$category_id', '$item_name', '$brand', 
            '$assigned_to', '$condition_status', '$status', 
            '$arrival_date', '$cost', '$specs', 1
        )";
        
        mysqli_query($conn, $sql);
    }

    header("Location: ../../web_content/inventory.php?success=1");
    exit();
}
?>