<?php
    $limit = 10;

    // Pagination Helper Function
    function get_offset($param, $limit) {
        $page = isset($_GET[$param]) && is_numeric($_GET[$param]) ? (int)$_GET[$param] : 1;
        return ($page < 1) ? 0 : ($page - 1) * $limit;
    }

    $offset = get_offset('page', $limit);
    $arc_offset = get_offset('arc_page', $limit);
    $page = (int)($offset / $limit) + 1;
    $arc_page = (int)($arc_offset / $limit) + 1;

    // Fetch Counts
    $total_active = $conn->query("SELECT COUNT(*) as total FROM USERS")->fetch_assoc()['total'];
    $total_archived = $conn->query("SELECT COUNT(*) as total FROM deleted_users")->fetch_assoc()['total'];

    $total_pages = ceil($total_active / $limit);
    $total_arc_pages = ceil($total_archived / $limit);

    // Fetch Data (Using Template Literals for clarity)
    $user_result = $conn->query("SELECT * FROM USERS LIMIT $limit OFFSET $offset");
    $archived_result = $conn->query("SELECT * FROM deleted_users LIMIT $limit OFFSET $arc_offset");
?>