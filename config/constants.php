<?php
// config/constants.php

// Dynamically detect the base URL to prevent 404 errors when running in XAMPP subfolders
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
$dir = str_replace('\\', '/', __DIR__);
$project_root = str_replace('/config', '', $dir);
$base_path = str_replace($doc_root, '', $project_root);
if (!str_starts_with($base_path, '/')) $base_path = '/' . $base_path;
define('BASE_URL', $protocol . '://' . $host . $base_path . '/');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'apple_planet');
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/products/');
