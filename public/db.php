<?php
// For Railway (live server)
$host    = getenv('MYSQLHOST')     ?: 'localhost';
$db      = getenv('MYSQLDATABASE') ?: 'products_db';
$user    = getenv('MYSQLUSER')     ?: 'root';
$pass    = getenv('MYSQLPASSWORD') ?: '';
$port    = getenv('MYSQLPORT')     ?: '3306';
$charset = 'utf8mb4';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=$charset",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Auto-create table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS products (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        name       VARCHAR(150)  NOT NULL,
        sku        VARCHAR(50)   NOT NULL,
        price      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        stock      INT           NOT NULL DEFAULT 0,
        status     ENUM('Active','Inactive','Draft') NOT NULL DEFAULT 'Active',
        image_url  VARCHAR(255)  DEFAULT '',
        created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");