<?php
    $count_users = "SELECT COUNT(*) AS total_users FROM users WHERE status = 'active'";
    $result_users = $conn->query($count_users);
    $total_users = $result_users->fetch_assoc()['total_users'];
?>