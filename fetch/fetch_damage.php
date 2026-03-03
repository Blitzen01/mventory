<?php
    // 2. Database and Timezone
    date_default_timezone_set('Asia/Manila');


    // 5. Pagination & Search Logic
    $search = isset($_GET['search']) ? mysqli_real_escape_with_like($conn, $_GET['search']) : '';
    $limit  = $_GET['limit'] ?? 10;
    $page   = $_GET['page'] ?? 1;
    $start  = ($page - 1) * $limit;

    // Define damage statuses
    $damage_conditions = "('DEFECTIVE', 'REPAIR', 'REPLACEMENT', 'DISPOSAL')";

    // Build Where Clause
    $where_clause = "condition_status IN $damage_conditions";

    if (!empty($search)) {
        $where_clause .= " AND (asset_id LIKE '%$search%' 
                            OR item_name LIKE '%$search%' 
                            OR assigned_to LIKE '%$search%' 
                            OR serial_number LIKE '%$search%')";
    }

    // Helper to prevent SQL injection & handle special characters
    function mysqli_real_escape_with_like($conn, $str) {
        return mysqli_real_escape_string($conn, $str);
    }

    $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM assets WHERE $where_clause");
    if (!$total_query) { die("Query Failed: " . mysqli_error($conn)); } // Debugging line

    $total_rows = mysqli_fetch_assoc($total_query)['total'];
    $total_pages = ceil($total_rows / $limit);
?>