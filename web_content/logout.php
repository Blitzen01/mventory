<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Helper Functions (Keep consistent with login.php)
    function getBrowserName($user_agent) {
        $os = "Unknown OS";
        if (strpos($user_agent, 'Windows')) $os = "Windows";
        else if (strpos($user_agent, 'Macintosh') || strpos($user_agent, 'Mac OS X')) $os = "MacOS";
        else if (strpos($user_agent, 'Android')) $os = "Android";
        else if (strpos($user_agent, 'iPhone') || strpos($user_agent, 'iPad')) $os = "iOS";
        else if (strpos($user_agent, 'Linux')) $os = "Linux";

        $browser = "Unknown Browser";
        if (strpos($user_agent, 'Vivaldi') !== false) $browser = 'Vivaldi';
        else if (strpos($user_agent, 'Brave') !== false || strpos($user_agent, ' Brave/') !== false) $browser = 'Brave';
        else if (strpos($user_agent, 'Edg') !== false) $browser = 'Edge';
        else if (strpos($user_agent, 'OPR') !== false || strpos($user_agent, 'Opera') !== false) $browser = 'Opera';
        else if (strpos($user_agent, 'Firefox') !== false || strpos($user_agent, 'FxiOS') !== false) $browser = 'Firefox';
        else if (strpos($user_agent, 'Chrome') !== false || strpos($user_agent, 'CriOS') !== false) $browser = 'Chrome';
        else if (strpos($user_agent, 'Safari') !== false) $browser = 'Safari';
        else if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident/7') !== false) $browser = 'Internet Explorer';

        return $browser . " (" . $os . ")";
    }

    function getClientIP() {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) return $_SERVER['HTTP_CF_CONNECTING_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        return $_SERVER['REMOTE_ADDR'];
    }

    // 2. Database Connection and Logging
    if (isset($_SESSION['user_id'])) {
        include "../render/connection.php"; 
        
        $user_id = $_SESSION['user_id'];
        $raw_ua  = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $browser = getBrowserName($raw_ua); // Clean name
        $ip      = getClientIP();           // Accurate IP

        // Update is_login status to 0
        $stmt = $conn->prepare("UPDATE users SET is_login = 0 WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Create a SECURITY LOG for the logout event
        // Note: is_login is set to 0 here to match your table's 'Deactivated' logic
        $log_stmt = $conn->prepare("INSERT INTO security_logs (user_id, browser, ip_address, login_time, status, is_login) VALUES (?, ?, ?, NOW(), 'Logout', 0)");
        $log_stmt->bind_param("iss", $user_id, $browser, $ip);
        $log_stmt->execute();
        $log_stmt->close();
    }

    // 3. Clear Session Data
    $_SESSION = array();

    // 4. Destroy Cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // 5. Destroy the Session
    session_destroy();

    // 6. Security Headers and Redirect
    header('Clear-Site-Data: "cache", "cookies", "storage"');
    header("Location: login.php");
    exit();
?>