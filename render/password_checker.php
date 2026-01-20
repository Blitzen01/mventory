<?php
include "../render/connection.php";

if (isset($_POST['save_bulk'])) {
    // Identity
    $prefix           = strtoupper($_POST['id_prefix']); 
    $bulk_qty         = (int)$_POST['bulk_qty'];
    $category_id      = $_POST['category_id'];
    $item_name        = $_POST['item_name'];
    
    // Specs & Remarks
    $specs            = mysqli_real_escape_string($conn, $_POST['specs']);
    $remarks          = mysqli_real_escape_string($conn, $_POST['remarks']);
    
    // Status & Location
    $assigned_to      = mysqli_real_escape_string($conn, $_POST['assigned_to']);
    $condition_status = $_POST['condition_status'];
    $status           = $_POST['status'];
    
    // Financials & Dates
    $arrival_date     = $_POST['arrival_date'];
    $purchase_date    = $_POST['purchase_date'];
    $cost             = !empty($_POST['cost']) ? $_POST['cost'] : 0;
    $warranty_cost    = !empty($_POST['warranty_cost']) ? $_POST['warranty_cost'] : 0;

    // Sequence finding logic
    $check_query = "SELECT asset_id FROM assets WHERE asset_id LIKE '$prefix-%' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    $start_count = 0;
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $parts = explode('-', $row['asset_id']);
        $start_count = (int)end($parts);
    }

    // Loop for Bulk Generation
    for ($i = 1; $i <= $bulk_qty; $i++) {
        $new_number = $start_count + $i;
        $unique_asset_id = $prefix . "-" . $new_number;

        $sql = "INSERT INTO assets (
            asset_id, category_id, item_name, specs, remarks,
            assigned_to, condition_status, status, 
            arrival_date, purchase_date, cost, warranty_cost, quantity
        ) VALUES (
            '$unique_asset_id', '$category_id', '$item_name', '$specs', '$remarks',
            '$assigned_to', '$condition_status', '$status', 
            '$arrival_date', '$purchase_date', '$cost', '$warranty_cost', 1
        )";
        
        mysqli_query($conn, $sql);
    }

    header("Location: ../public/inventory.php?success=1");
    exit();
}
?>