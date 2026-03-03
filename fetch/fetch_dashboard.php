<?php
    // 1. Fetch Threshold percentage
    $threshold_query = "SELECT setting_value FROM system_settings WHERE setting_key = 'low_stock_threshold_percent' LIMIT 1";
    $threshold_result = $conn->query($threshold_query);
    $threshold_percent = ($threshold_result && $threshold_result->num_rows > 0) ? (int)$threshold_result->fetch_assoc()['setting_value'] : 10;

    // Define damage statuses once for all queries
    $damage_statuses = "'Disposal', 'Replacement', 'Warranty', 'Repairable', 'Damaged'";

    // 2. User Statistics
    $total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
    $online_count = $conn->query("SELECT COUNT(*) as online FROM users WHERE is_login = 1")->fetch_assoc()['online'] ?? 0;

    // 3. Asset Statistics
    $total_items = $conn->query("SELECT COUNT(*) as total_items FROM assets")->fetch_assoc()['total_items'] ?? 0;
    $total_value = $conn->query("SELECT SUM(cost) as total_value FROM assets")->fetch_assoc()['total_value'] ?? 0;

    // 4. Low Stock Logic 
    $low_stock_sql = "SELECT COUNT(*) as low_count FROM (
                        SELECT 
                            SUBSTRING_INDEX(asset_id, '-', 1) as group_name, 
                            COUNT(*) as total_group_count,
                            SUM(CASE WHEN assigned_to = 'EDD' AND condition_status NOT IN ($damage_statuses) THEN 1 ELSE 0 END) as edd_count
                        FROM assets 
                        GROUP BY group_name 
                        HAVING edd_count < (total_group_count * ($threshold_percent / 100))
                      ) as result";
    $low_stock_res = $conn->query($low_stock_sql);
    $low_stock_count = $low_stock_res->fetch_assoc()['low_count'] ?? 0;

    // 5. Damaged Items & Loss (Filtered by EDD stock)
    $damaged_result = $conn->query("SELECT COUNT(*) as damaged, SUM(cost) as loss FROM assets WHERE condition_status IN ($damage_statuses) AND assigned_to = 'EDD'");
    $damage_data = $damaged_result->fetch_assoc();

    // We define these clearly so the HTML below can find them
    $damaged_count = $damage_data['damaged'] ?? 0;
    $damage_loss = $damage_data['loss'] ?? 0;

    // 6. Critical Reorder Table Query
    $reorder_query = "SELECT 
                        SUBSTRING_INDEX(asset_id, '-', 1) as group_name, 
                        COUNT(*) as total_group_count,
                        SUM(CASE WHEN assigned_to = 'EDD' AND condition_status NOT IN ($damage_statuses) THEN 1 ELSE 0 END) as edd_count,
                        MIN(cost) as price
                      FROM assets 
                      GROUP BY group_name 
                      HAVING edd_count < (total_group_count * ($threshold_percent / 100))
                      ORDER BY edd_count ASC LIMIT 5";
    $reorder_list = $conn->query($reorder_query);

    // 7. Overall Stock Health
    $good_stock_query = "SELECT COUNT(*) as good_total FROM assets WHERE assigned_to = 'EDD' AND condition_status NOT IN ($damage_statuses)";
    $good_stock_count = $conn->query($good_stock_query)->fetch_assoc()['good_total'] ?? 0;
    $stock_health_percent = ($total_items > 0) ? round(($good_stock_count / $total_items) * 100) : 0;
?>