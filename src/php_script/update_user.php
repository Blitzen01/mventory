<?php
session_start();
require "../../render/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user_btn'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $status = $_POST['status'];
    $new_password = $_POST['new_password'];
    $admin_id = $_SESSION['user_id'] ?? 0;

    try {
        $pdo->beginTransaction();

        // If password is provided, hash it. Otherwise, build query without it.
        if (!empty($new_password)) {
            $hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = :uname, status = :status, password = :pass WHERE user_id = :uid";
            $params = [
                'uname'  => $username,
                'status' => $status,
                'pass'   => $hashed_pass,
                'uid'    => $user_id
            ];
        } else {
            $sql = "UPDATE users SET username = :uname, status = :status WHERE user_id = :uid";
            $params = [
                'uname'  => $username,
                'status' => $status,
                'uid'    => $user_id
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Audit Log
        $logStmt = $pdo->prepare("INSERT INTO system_audit_logs 
            (table_name, record_id, action_type, old_value, new_value, changed_by) 
            VALUES ('users', :rid, 'UPDATE', :old, 'Updated username/status/pass', :admin)");
        
        $logStmt->execute([
            'rid'   => $user_id,
            'old'   => "User: $username",
            'admin' => $admin_id
        ]);

        $pdo->commit();
        header("Location: ../../web_content/accounts.php?success=updated");

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        header("Location: ../../web_content/accounts.php?error=" . urlencode($e->getMessage()));
    }
    exit;
}