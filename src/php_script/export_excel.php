<?php
include '../../render/connection.php';

// Force download headers
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=asset_inventory.csv");
header("Pragma: no-cache");
header("Expires: 0");

// UTF-8 BOM for Excel compatibility (Fixes weird characters)
echo "\xEF\xBB\xBF"; 

$output = fopen("php://output", "w");

// Column headers matching your database fields
fputcsv($output, [
    "ID",
    "Asset ID",
    "Category ID",
    "Item Name",
    "Brand",
    "Model",
    "Serial Number",
    "Specs",
    "Quantity",
    "Assigned To",
    "Status",
    "Condition",
    "Purchase Date",
    "Arrival Date",
    "Cost",
    "Warranty Cost",
    "Remarks",
    "Created At"
]);

// Select ALL data from your table
$sql = "SELECT * FROM assets ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {

    // FORMAT REMARKS:
    // This finds "[MM/DD/YYYY" and adds a line break before it, 
    // unless it's the very start of the text.
    $formatted_remarks = preg_replace('/(?<!^)(\[\d{2}\/\d{2}\/\d{4})/', "\n$1", $row['remarks']);

    // Write the row to the CSV
    fputcsv($output, [
        $row['id'],
        $row['asset_id'],
        $row['category_id'],
        $row['item_name'],
        $row['brand'],
        $row['model'],
        $row['serial_number'],
        $row['specs'],
        $row['quantity'],
        $row['assigned_to'],
        $row['status'],
        $row['condition_status'], // Fixed column name from your structure
        $row['purchase_date'],
        $row['arrival_date'],
        $row['cost'],
        $row['warranty_cost'],
        $formatted_remarks, 
        $row['created_at']
    ]);
}

fclose($output);
exit;
?>