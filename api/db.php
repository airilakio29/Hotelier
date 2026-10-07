<?php
// api/db.php

// Prevent direct access to db.php via URL
$currentScript = basename($_SERVER['SCRIPT_FILENAME'] ?? '');
$currentSelf   = basename($_SERVER['PHP_SELF'] ?? '');
if ($currentScript === 'db.php' || $currentSelf === 'db.php') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Direct access forbidden']);
    exit;
}

// Read database configuration from environment variables
$host     = getenv('DB_HOST')     ?: ($_ENV['DB_HOST']     ?? ($_SERVER['DB_HOST']     ?? '127.0.0.1'));
$port     = getenv('DB_PORT')     ?: ($_ENV['DB_PORT']     ?? ($_SERVER['DB_PORT']     ?? '3306'));
$dbname   = getenv('DB_NAME')     ?: ($_ENV['DB_NAME']     ?? ($_SERVER['DB_NAME']     ?? ''));
$username = getenv('DB_USER')     ?: ($_ENV['DB_USER']     ?? ($_SERVER['DB_USER']     ?? ''));
$password = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? ($_SERVER['DB_PASSWORD'] ?? ''));
$charset  = getenv('DB_CHARSET')  ?: ($_ENV['DB_CHARSET']  ?? ($_SERVER['DB_CHARSET']  ?? 'utf8mb4'));
$ssl      = getenv('DB_SSL')      ?: ($_ENV['DB_SSL']      ?? ($_SERVER['DB_SSL']      ?? ''));
$sslCA    = getenv('DB_SSL_CA')   ?: ($_ENV['DB_SSL_CA']   ?? ($_SERVER['DB_SSL_CA']   ?? ''));

$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Configure SSL for cloud databases (e.g., TiDB, Aiven)
$isSSL = in_array(strtolower((string)$ssl), ['true', '1', 'yes', 'required'], true);
if ($isSSL) {
    if (!empty($sslCA) && file_exists($sslCA)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCA;
    } elseif (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
}

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Log internal error without exposing sensitive credentials or stack traces to client
    error_log('Database connection error: ' . $e->getMessage());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json');
    }
    echo json_encode([
        'success' => false,
        'error'   => 'Database connection failed. Please check server configuration.'
    ]);
    exit;
}
?>