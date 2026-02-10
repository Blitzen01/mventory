<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Adjusted paths based on being inside ../src/php_script/
include "../../render/connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $scope = $_POST['scope'] ?? '';
    $success = false;
    $message = "";

    // 1. Handle Key-Value System Settings (General, Inventory, Alerts, Security)
    $standard_scopes = ['general', 'inventory', 'notifications', 'security'];
    
    if (in_array($scope, $standard_scopes)) {
        foreach ($_POST as $key => $value) {
            if ($key == 'scope') continue;
            
            $clean_value = mysqli_real_escape_string($conn, $value);
            $clean_key = mysqli_real_escape_string($conn, $key);
            
            $sql = "UPDATE system_settings SET setting_value = '$clean_value' WHERE setting_key = '$clean_key'";
            mysqli_query($conn, $sql);
        }

        // Logic for Checkboxes (Switches) - Setting to 0 if they are missing from POST
        if ($scope == 'general' && !isset($_POST['dark_mode_default'])) {
            mysqli_query($conn, "UPDATE system_settings SET setting_value = '0' WHERE setting_key = 'dark_mode_default'");
        }
        if ($scope == 'notifications') {
            $low_stock = isset($_POST['trigger_low_stock']) ? '1' : '0';
            $new_user = isset($_POST['trigger_new_user']) ? '1' : '0';
            mysqli_query($conn, "UPDATE system_settings SET setting_value = '$low_stock' WHERE setting_key = 'trigger_low_stock'");
            mysqli_query($conn, "UPDATE system_settings SET setting_value = '$new_user' WHERE setting_key = 'trigger_new_user'");
        }

        // Handle Image Upload for Logo
        if ($scope == 'general' && isset($_FILES['app_logo_upload']) && $_FILES['app_logo_upload']['error'] == 0) {
            $target_dir = "../image/logo/"; // Path relative to this script
            $file_name = time() . "_" . basename($_FILES["app_logo_upload"]["name"]); // Added timestamp to prevent caching issues
            $target_file = $target_dir . $file_name;
            
            if (move_uploaded_file($_FILES["app_logo_upload"]["tmp_name"], $target_file)) {
                mysqli_query($conn, "UPDATE system_settings SET setting_value = '$file_name' WHERE setting_key = 'app_logo'");
            }
        }

        $success = true;
        $message = "System settings updated.";
    }

    // 2. Add Category
    elseif ($scope == 'add_category') {
        $cat_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        if (mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$cat_name')")) {
            $success = true; $message = "Category added.";
        }
    }

    // 3. Remove Category
    elseif ($scope == 'remove_category') {
        $cat_id = mysqli_real_escape_string($conn, $_POST['category_id']);
        if (mysqli_query($conn, "DELETE FROM categories WHERE id = '$cat_id'")) {
            $success = true; $message = "Category removed.";
        }
    }

    // 4. Add Item Name
    elseif ($scope == 'add_item_name') {
        $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
        if (mysqli_query($conn, "INSERT INTO item_masterlist (item_name) VALUES ('$item_name')")) {
            $success = true; $message = "Masterlist updated.";
        }
    }

    // 5. Remove Item Name
    elseif ($scope == 'remove_item_name') {
        $item_id = mysqli_real_escape_string($conn, $_POST['item_id']);
        if (mysqli_query($conn, "DELETE FROM item_masterlist WHERE id = '$item_id'")) {
            $success = true; $message = "Item removed.";
        }
    }

    $_SESSION['status'] = $success ? $message : "Update failed.";
    
    // Map scopes to Tab IDs (if they differ)
    $tab_map = [
        'general'       => 'general',
        'inventory'     => 'inventory',
        'notifications' => 'alerts', // Your scope is 'notifications', but the Tab ID is 'alerts'
        'security'      => 'security',
        'add_category'  => 'inventory',
        'remove_category' => 'inventory',
        'add_item_name' => 'inventory',
        'remove_item_name' => 'inventory'
    ];

    $target_tab = $tab_map[$scope] ?? 'general';

    // Redirect back and append the hash (e.g., settings.php#alerts)
    header("Location: " . $_SERVER['HTTP_REFERER'] . "#" . $target_tab);
    exit();
}