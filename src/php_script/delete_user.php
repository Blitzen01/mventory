<?php
session_start();
require "../../render/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $admin_id = $_SESSION['user_id'];
    $target_user_id = $_POST['user_id'];
    $admin_password = $_POST['admin_password'];

    // 1. Verify Admin Password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($admin_password, $admin['password'])) {
        try {
            // Check for active transaction to prevent "Active Transaction" error
            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
            }

            // 2. Fetch user data for archiving
            $userStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $userStmt->execute([$target_user_id]);
            $userData = $userStmt->fetch();

            if (!$userData) {
                throw new Exception("User not found.");
            }

            // 3. Archive data into deleted_users table
            $archiveSql = "INSERT INTO deleted_users 
                (user_id, role_id, username, first_name, last_name, phone, role, status, email, password, profile_image, is_active, last_login, created_at, deleted_at)
                VALUES 
                (:uid, :rid, :uname, :fname, :lname, :phone, :role, 'deleted', :email, :pass, :img, :active, :last_log, :created, CURRENT_TIMESTAMP)";
            
            $pdo->prepare($archiveSql)->execute([
                'uid'      => $userData['user_id'],
                'rid'      => $userData['role_id'],
                'uname'    => $userData['username'],
                'fname'    => $userData['first_name'],
                'lname'    => $userData['last_name'],
                'phone'    => $userData['phone'],
                'role'     => $userData['role'],
                'email'    => $userData['email'],
                'pass'     => $userData['password'],
                'img'      => $userData['profile_image'],
                'active'   => $userData['is_active'],
                'last_log' => $userData['last_login'],
                'created'  => $userData['created_at']
            ]);

            // 4. Dissociate user from all related records (Nullify Foreign Keys)
            $tablesToNullify = [
                ['table' => 'branch_logs', 'col' => 'user_id'],
                ['table' => 'damaged_products', 'col' => 'reported_by_user_id'],
                ['table' => 'inventory_log', 'col' => 'user_id'],
                ['table' => 'system_audit_logs', 'col' => 'changed_by']
            ];

            foreach ($tablesToNullify as $t) {
                $pdo->prepare("UPDATE {$t['table']} SET {$t['col']} = NULL WHERE {$t['col']} = ?")
                    ->execute([$target_user_id]);
            }

            // 5. Log the deletion in Audit Logs
            $log_details = "Archived and Permanently Deleted User: " . $userData['username'];
            $logStmt = $pdo->prepare("INSERT INTO system_audit_logs 
                (table_name, record_id, action_type, old_value, new_value, changed_by) 
                VALUES ('users', ?, 'DELETE', ?, 'Moved to archive and removed', ?)");
            $logStmt->execute([$target_user_id, $log_details, $admin_id]);

            // 6. Permanently remove from active users
            $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$target_user_id]);

            $pdo->commit();
            header("Location: ../../web_content/accounts.php?success=deleted");

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Redirect with specific error message
            header("Location: ../../web_content/accounts.php?error=" . urlencode($e->getMessage()));
        }
    } else {
        header("Location: ../../web_content/accounts.php?error=wrong_password");
    }
    exit;
}

header("Location: ../../web_content/accounts.php");
exit;