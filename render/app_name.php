<?php
    $app_name = "SELECT setting_value FROM system_settings WHERE setting_key = 'app_name'";
    $result = $conn->query($app_name);
    $display_name = $result->fetch_assoc()['setting_value'] ?? 'Varay Inventory';

    $app_logo = "SELECT setting_value FROM system_settings WHERE setting_key = 'app_logo'";
    $result = $conn->query($app_logo);
    $display_logo = $result->fetch_assoc()['setting_value'] ?? 'varay_logo.png';
?>