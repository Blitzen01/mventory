<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Database Connection and Logging
    if (isset($_SESSION['user_id'])) {
        include "../render/connection.php"; 
        
        $user_id = $_SESSION['user_id'];
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $ip      = $_SERVER['REMOTE_ADDR'];

        // Update is_login status to 0
        $stmt = $conn->prepare("UPDATE users SET is_login = 0 WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Create a SECURITY LOG for the logout event
        $log_stmt = $conn->prepare("INSERT INTO security_logs (user_id, browser, ip_address, login_time, status, is_login) VALUES (?, ?, ?, NOW(), 'Logout', 0)");
        $log_stmt->bind_param("iss", $user_id, $browser, $ip);
        $log_stmt->execute();
        $log_stmt->close();
    }

    // 2. Clear Session Data
    $_SESSION = array();

    // 3. Destroy Cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // 4. Destroy the Session
    session_destroy();

    // 5. Security Headers and Redirect
    header('Clear-Site-Data: "cache", "cookies", "storage"');
    header("Location: login.php");
    exit();
?>