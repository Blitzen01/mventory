<?php
session_start();
// Check if you are using PDO (from your previous code) or mysqli
// Based on your delete script, you are using PDO ($pdo)
include "../render/connection.php"; 

if (isset($_POST['password']) && isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];
    $entered_password = $_POST['password'];

    try {
        // 1. Get the hashed password for the logged-in admin
        $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ? LIMIT 1");
        $stmt->execute([$admin_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($entered_password, $user['password'])) {
            // Password matches!
            echo json_encode(['success' => true]);
        } else {
            // Password wrong
            echo json_encode(['success' => false]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// If accessed incorrectly
echo json_encode(['success' => false, 'message' => 'Invalid Request']);