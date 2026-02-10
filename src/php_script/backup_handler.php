<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../render/connection.php"; 

$backup_dir = "../../data_backups/";
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

// --- 1. HANDLE SQL SNAPSHOT DOWNLOAD ---
if (isset($_POST['download_sql'])) {
    
    $filename = "mventory " . date("mdy") . ".sql";
    $file_path = $backup_dir . $filename;

    $host = "localhost"; 
    $user = "root";      
    $pass = "";          
    $name = "mventory"; 

    $dump_path = "C:\\xampp\\mysql\\bin\\mysqldump.exe"; 
    if (!file_exists($dump_path)) {
        $dump_path = "mysqldump"; 
    }

    $command = "\"$dump_path\" --opt -h $host -u $user " . ($pass ? "-p$pass" : "") . " $name > " . escapeshellarg($file_path) . " 2>&1";
    
    exec($command, $output, $return_var);

    if ($return_var === 0 && file_exists($file_path) && filesize($file_path) > 0) {
        $timestamp = date("Y-m-d H:i:s");
        mysqli_query($conn, "UPDATE system_settings SET setting_value = '$timestamp' WHERE setting_key = 'last_backup_datetime'");
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        
        ob_clean();
        flush();
        readfile($file_path);
        exit();
    } else {
        $error_msg = isset($output[0]) ? $output[0] : "Unknown shell error.";
        $_SESSION['status'] = "Backup failed: " . $error_msg;
        header("Location: ../../web_content/settings.php#backup");
        exit();
    }
}

// --- 2. HANDLE MULTI-SHEET EXCEL EXPORT ---
if (isset($_POST['export_csv'])) {
    $filename = "mventory " . date("mdy") . ".xls";

    // Set headers for Excel XML format
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Excel XML Header
    echo '<?xml version="1.0"?>';
    echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
    echo ' xmlns:o="urn:schemas-microsoft-com:office:office"';
    echo ' xmlns:x="urn:schemas-microsoft-com:office:excel"';
    echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"';
    echo ' xmlns:html="http://www.w3.org/TR/REC-html40">';
    
    // Get every table in the database
    $tables_res = mysqli_query($conn, "SHOW TABLES");
    
    while ($table_row = mysqli_fetch_row($tables_res)) {
        $tableName = $table_row[0];
        
        // Start a new sheet named after the table
        echo '<Worksheet ss:Name="' . htmlspecialchars($tableName) . '"><Table>';
        
        $result = mysqli_query($conn, "SELECT * FROM `$tableName`");
        $fields = mysqli_fetch_fields($result);

        // Header Row (Column names)
        echo '<Row>';
        foreach ($fields as $field) {
            echo '<Cell><Data ss:Type="String">' . htmlspecialchars($field->name) . '</Data></Cell>';
        }
        echo '</Row>';

        // Data Rows
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<Row>';
            foreach ($row as $value) {
                // If value is null, set to empty string
                $displayValue = $value ?? '';
                $type = (is_numeric($displayValue) && strlen($displayValue) < 12) ? 'Number' : 'String';
                echo '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars($displayValue) . '</Data></Cell>';
            }
            echo '</Row>';
        }
        
        echo '</Table></Worksheet>';
    }

    echo '</Workbook>';
    exit();
}