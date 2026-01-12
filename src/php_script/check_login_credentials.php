<?php
session_start();
include "../../render/connection.php";

/* =========================
   Get Client IP (Safe)
========================= */
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

if (isset($_POST['username'], $_POST['password'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $ip = filter_var(getClientIP(), FILTER_VALIDATE_IP) ?: 'UNKNOWN';

    /* =========================
       Fetch User
    ========================= */
    $stmt = $conn->prepare(
        "SELECT user_id, username, password, role 
         FROM users 
         WHERE username = ? 
         LIMIT 1"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        /* =========================
           Password Verification
        ========================= */
        if (password_verify($password, $user['password'])) {

            // Update user login status
            $update_stmt = $conn->prepare(
                "UPDATE users 
                 SET last_login = NOW(), is_login = 1 
                 WHERE user_id = ?"
            );
            $update_stmt->bind_param("i", $user['user_id']);
            $update_stmt->execute();
            $update_stmt->close();

            // Log success
            $log_stmt = $conn->prepare(
                "INSERT INTO security_logs 
                 (user_id, browser, ip_address, login_time, status, is_login)
                 VALUES (?, ?, ?, NOW(), 'Success', 1)"
            );
            $log_stmt->bind_param(
                "iss",
                $user['user_id'],
                $browser,
                $ip
            );
            $log_stmt->execute();
            $log_stmt->close();

            // Session
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            header("Location: ../../web_content/dashboard.php");
            exit();

        } else {

            // Wrong password log
            $log_stmt = $conn->prepare(
                "INSERT INTO security_logs 
                 (user_id, browser, ip_address, login_time, status)
                 VALUES (?, ?, ?, NOW(), 'Failed Password')"
            );
            $log_stmt->bind_param(
                "iss",
                $user['user_id'],
                $browser,
                $ip
            );
            $log_stmt->execute();
            $log_stmt->close();
        }

    } else {

        // Invalid username log
        $log_stmt = $conn->prepare(
            "INSERT INTO security_logs 
             (user_id, browser, ip_address, login_time, status)
             VALUES (NULL, ?, ?, NOW(), 'Invalid Username')"
        );
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
?>
