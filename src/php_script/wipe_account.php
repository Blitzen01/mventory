<?php
session_start();
// Include your database connection
require_once '../../render/connection.php'; 

if (isset($_POST['confirm_wipe']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    try {
        // Prepare the permanent delete statement
        $stmt = $pdo->prepare("DELETE FROM deleted_users WHERE user_id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Account permanently deleted.";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete account.";
            $_SESSION['msg_type'] = "danger";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }

    // Redirect back to the archive page
    header("Location: ../../web_content/accounts.php ");
    exit();
}