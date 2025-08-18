<?php
// src/includes/database.php

function getDbConnection(): PDO 
{
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'] ?? 'mysql',
                $_ENV['DB_PORT'] ?? '3306',
                $_ENV['DB_NAME']
            );
            
            $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            // In production, log this error instead of showing it
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}