<?php
session_start();
// Enable error reporting for debugging - remove this once it works!
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "../../render/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $admin_id = $_SESSION['user_id'] ?? 0; 
    $target_user_id = $_POST['user_id'];
    $admin_password = $_POST['admin_password'];

    // 1. Verify Admin Password
    // Check if we are using 'user_id' or 'id' as the primary key
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ? LIMIT 1");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($admin_password, $admin['password'])) {
        try {
            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
            }

            // 2. Fetch user data
            $userStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $userStmt->execute([$target_user_id]);
            $userData = $userStmt->fetch();

            if (!$userData) {
                throw new Exception("User not found in database.");
            }

            // 3. Archive data 
            // NOTE: Ensure your 'deleted_users' table has EVERY one of these columns
            $archiveSql = "INSERT INTO deleted_users 
                (user_id, role_id, username, first_name, last_name, phone, role, status, email, password, profile_image, last_login, created_at, deleted_at)
                VALUES 
                (:uid, :rid, :uname, :fname, :lname, :phone, :role, 'deleted', :email, :pass, :img, :last_log, :created, CURRENT_TIMESTAMP)";
            
            $archiveStmt = $pdo->prepare($archiveSql);
            $archiveStmt->execute([
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
                'last_log' => $userData['last_login'],
                'created'  => $userData['created_at']
            ]);

            // 4. Handle Foreign Keys (Nullify)
            $tablesToNullify = [
                ['table' => 'branch_logs', 'col' => 'user_id'],
                ['table' => 'damaged_products', 'col' => 'reported_by_user_id'],
                ['table' => 'inventory_log', 'col' => 'user_id'],
                ['table' => 'system_audit_logs', 'col' => 'changed_by']
            ];

            foreach ($tablesToNullify as $t) {
                // We use a try-catch inside the loop just in case some tables don't exist yet
                try {
                    $pdo->prepare("UPDATE {$t['table']} SET {$t['col']} = NULL WHERE {$t['col']} = ?")
                        ->execute([$target_user_id]);
                } catch (PDOException $e) {
                    // Skip if table/column doesn't exist
                    continue; 
                }
            }

            // 5. Log the deletion
            $log_details = "Archived User: " . $userData['username'];
            $logStmt = $pdo->prepare("INSERT INTO system_audit_logs 
                (table_name, record_id, action_type, old_value, new_value, changed_by) 
                VALUES ('users', ?, 'DELETE', ?, 'Moved to archive', ?)");
            $logStmt->execute([$target_user_id, $log_details, $admin_id]);

            // 6. DELETE FROM ACTIVE USERS
            $delStmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $delStmt->execute([$target_user_id]);

            $pdo->commit();
            header("Location: ../../web_content/accounts.php?success=deleted");
            exit;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Log the actual error to a file or screen
            header("Location: ../../web_content/accounts.php?error=" . urlencode($e->getMessage()));
            exit;
        }
    } else {
        header("Location: ../../web_content/accounts.php?error=wrong_password");
        exit;
    }
}