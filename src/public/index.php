<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';


// Load environment variables
$dotenv = Dotenv\Dotenv::CreateImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/../includes/database.php';

//Simple routing system
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

$requestPath = rtrim($requestPath, '/');
if ($requestPath === '') {
    $requestPath = '/';
}

switch ($requestPath) {
    //for testing database connection
    case '/test-db':
        $pdo = getDbConnection();
        echo "Database connection successful!";
        break;
}

echo($requestPath);

?>