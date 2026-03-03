<?php
session_start();
include "../../render/connection.php";

/* =========================
   Helper Functions
========================= */

function getBrowserName($user_agent) {
    // 1. Detect Platform/OS
    $os = "Unknown OS";
    if (strpos($user_agent, 'Windows')) $os = "Windows";
    else if (strpos($user_agent, 'Macintosh') || strpos($user_agent, 'Mac OS X')) $os = "MacOS";
    else if (strpos($user_agent, 'Android')) $os = "Android";
    else if (strpos($user_agent, 'iPhone') || strpos($user_agent, 'iPad')) $os = "iOS";
    else if (strpos($user_agent, 'Linux')) $os = "Linux";

    // 2. Detect Browser (Order is critical here)
    $browser = "Unknown Browser";

    if (strpos($user_agent, 'Vivaldi') !== false) {
        $browser = 'Vivaldi';
    } 
    else if (strpos($user_agent, 'Brave') !== false || strpos($user_agent, ' Brave/') !== false) {
        $browser = 'Brave';
    } 
    else if (strpos($user_agent, 'Edg') !== false) {
        $browser = 'Edge';
    } 
    else if (strpos($user_agent, 'OPR') !== false || strpos($user_agent, 'Opera') !== false) {
        $browser = 'Opera';
    } 
    else if (strpos($user_agent, 'Firefox') !== false || strpos($user_agent, 'FxiOS') !== false) {
        $browser = 'Firefox';
    } 
    else if (strpos($user_agent, 'Chrome') !== false || strpos($user_agent, 'CriOS') !== false) {
        $browser = 'Chrome';
    } 
    else if (strpos($user_agent, 'Safari') !== false) {
        $browser = 'Safari';
    } 
    else if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident/7') !== false) {
        $browser = 'Internet Explorer';
    }

    return $browser . " (" . $os . ")";
}

function getClientIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/* =========================
   Login Processing
========================= */

if (isset($_POST['username'], $_POST['password'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Capture Browser and IP
    $raw_ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $browser = getBrowserName($raw_ua); 
    $ip = filter_var(getClientIP(), FILTER_VALIDATE_IP) ?: 'UNKNOWN';

    // Fetch User
    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Update user status
            $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW(), is_login = 1 WHERE user_id = ?");
            $update_stmt->bind_param("i", $user['user_id']);
            $update_stmt->execute();
            $update_stmt->close();

            // Log SUCCESS
            $log_stmt = $conn->prepare("INSERT INTO security_logs (user_id, browser, ip_address, login_time, status, is_login) VALUES (?, ?, ?, NOW(), 'Success', 1)");
            $log_stmt->bind_param("iss", $user['user_id'], $browser, $ip);
            $log_stmt->execute();
            $log_stmt->close();

            // Setup Session
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            header("Location: ../../web_content/dashboard.php");
            exit();

        } else {
            // Log FAILED PASSWORD
            $log_stmt = $conn->prepare("INSERT INTO security_logs (user_id, browser, ip_address, login_time, status) VALUES (?, ?, ?, NOW(), 'Failed Password')");
            $log_stmt->bind_param("iss", $user['user_id'], $browser, $ip);
            $log_stmt->execute();
            $log_stmt->close();
        }
    } else {
        // Log INVALID USERNAME
        $log_stmt = $conn->prepare("INSERT INTO security_logs (user_id, browser, ip_address, login_time, status) VALUES (NULL, ?, ?, NOW(), 'Invalid Username')");
        $log_stmt->bind_param("ss", $browser, $ip);
        $log_stmt->execute();
        $log_stmt->close();
    }

    $stmt->close();
    header("Location: ../../web_content/login.php?error=invalid_credentials");
    exit();
} else {
    header("Location: ../../web_content/login.php");
    exit();
}