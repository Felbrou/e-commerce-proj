<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

// Test database connection
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $_ENV['DB_HOST'] ?? 'mysql',
        $_ENV['DB_PORT'] ?? '3306',
        $_ENV['DB_NAME']
    );
    
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
    echo "Successfully connected to MySQL!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>