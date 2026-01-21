<?php
include '../../render/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_id      = $_POST['asset_db_id'];
    $name       = mysqli_real_escape_string($conn, $_POST['item_name']);
    $cat        = $_POST['category_id'];
    $cond       = $_POST['condition_status'];
    $new_remark = mysqli_real_escape_string($conn, $_POST['new_remark']);

    // 1. Get the current date in your requested format
    $currentDate = date('m/d/Y');

    // 2. Fetch existing remarks first
    $query = mysqli_query($conn, "SELECT remarks FROM assets WHERE id = '$db_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);
    $old_remarks = $row['remarks'];

    // 3. Construct the new remarks string
    // Format: Date: MM/DD/YYYY | Remark content
    $formatted_new_remark = "Date: $currentDate | $new_remark";
    
    // Append the old remarks below the new one
    $updated_remarks = $formatted_new_remark . "\n" . $old_remarks;

    // 4. Update the database
    $sql = "UPDATE assets SET 
            item_name = '$name', 
            category_id = '$cat', 
            condition_status = '$cond',
            remarks = '$updated_remarks' 
            WHERE id = '$db_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../public/index.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>