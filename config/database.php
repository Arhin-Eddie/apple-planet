<?php
// config/database.php
require_once __DIR__ . '/constants.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Fetch global settings
$global_settings = ['currency_symbol' => '$'];
try {
    $res = $conn->query("SELECT setting_key, setting_value FROM settings");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $global_settings[$row['setting_key']] = $row['setting_value'];
        }
    }
} catch (Exception $e) {
    // Ignore if table doesn't exist yet
}
