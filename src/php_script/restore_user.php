<?php
session_start();
require "../../render/connection.php"; // Ensure this file creates a $pdo object

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_btn'])) {
    $admin_id = $_SESSION['user_id'] ?? 0;
    $target_user_id = $_POST['user_id'];

    if (empty($target_user_id)) {
        header("Location: ../../web_content/accounts.php?error=invalid_id");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Fetch the archived data
        $userStmt = $pdo->prepare("SELECT * FROM deleted_users WHERE user_id = ?");
        $userStmt->execute([$target_user_id]);
        $userData = $userStmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            $pdo->rollBack();
            header("Location: ../../web_content/accounts.php?error=user_not_found");
            exit;
        }

        // 2. Insert back into 'users' table 
        // NOTE: Ensure these column names match your actual 'users' table schema
        $restoreSql = "INSERT INTO users 
            (user_id, username, first_name, last_name, email, password, role, phone, profile_image, status, created_at)
            VALUES 
            (:uid, :uname, :fname, :lname, :email, :pass, :role, :phone, :img, 'active', NOW())";
        
        $restoreStmt = $pdo->prepare($restoreSql);
        $restoreStmt->execute([
            'uid'   => $userData['user_id'],
            'uname' => $userData['username'],
            'fname' => $userData['first_name'],
            'lname' => $userData['last_name'],
            'email' => $userData['email'],
            'pass'  => $userData['password'],
            'role'  => $userData['role'],
            'phone' => $userData['phone'],
            'img'   => $userData['profile_image']
        ]);

        // 3. Log the action
        $log_details = "Restored User: " . $userData['username'];
        $logStmt = $pdo->prepare("INSERT INTO system_audit_logs 
                                    (table_name, record_id, action_type, old_value, new_value, changed_by) 
                                    VALUES ('deleted_users', :record_id, 'RESTORED', :old_v, 'Moved to active', :admin)");
        $logStmt->execute([
            'record_id' => $target_user_id,
            'old_v'     => $log_details,
            'admin'     => $admin_id
        ]);

        // 4. Remove from archive
        $delete = $pdo->prepare("DELETE FROM deleted_users WHERE user_id = ?");
        $delete->execute([$target_user_id]);

        $pdo->commit();
        header("Location: ../../web_content/accounts.php?success=restored");

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        // Log error and redirect
        header("Location: ../../web_content/accounts.php?error=" . urlencode($e->getMessage()));
    }
    exit;
} else {
    header("Location: ../../web_content/accounts.php");
    exit;
}