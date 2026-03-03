<?php
    date_default_timezone_set('Asia/Manila');

    // --- LOGIC: SEARCH & PAGINATION ---
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $total_query = "SELECT COUNT(*) as total 
                    FROM allocation_log al
                    LEFT JOIN assets a ON al.asset_id = a.id 
                    WHERE a.asset_id LIKE '%$search%' 
                       OR a.item_name LIKE '%$search%'
                       OR a.serial_number LIKE '%$search%'
                       OR al.new_assignee LIKE '%$search%'
                       OR al.previous_assignee LIKE '%$search%'";
                       
    $total_res = mysqli_query($conn, $total_query);
    $total_rows = mysqli_fetch_assoc($total_res)['total'];
    $total_pages = ceil($total_rows / $limit);
?>