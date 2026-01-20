<?php
    session_start();
    include "../../render/connection.php";

    if(isset($_SESSION['user_id'])) {
        $uid = $_SESSION['user_id'];
        // Update last_activity to right now
        $conn->query("UPDATE USERS SET last_activity = NOW(), is_login = 1 WHERE user_id = '$uid'");
    }
?>