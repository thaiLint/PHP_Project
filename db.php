<?php
$host = 'localhost';
$db   = 'products_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('<div style="font-family:monospace;padding:20px;color:#ef4444;background:#1a1a2e;">
        <strong>DB Connection Failed:</strong> ' . $e->getMessage() . '<br><br>
        Make sure XAMPP MySQL is running and the database <strong>shodai_db</strong> exists.<br>
        Run this SQL first: <code>CREATE DATABASE shodai_db;</code>
    </div>');
}

// Auto-create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(150) NOT NULL,
    sku       VARCHAR(50)  NOT NULL,
    price     DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock     INT          NOT NULL DEFAULT 0,
    status    ENUM('Active','Inactive','Draft') NOT NULL DEFAULT 'Active',
    image_url VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Seed 3 demo products if empty
$count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
if ($count == 0) {
    $pdo->exec("INSERT INTO products (name, sku, price, stock, status, image_url) VALUES
        ('Gabriela Cashmere Blazer', 'SKU-001', 113.99, 1113, 'Active', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop'),
        ('Loewe Blend Jacket – Blue', 'SKU-002', 89.99,  721,  'Active', 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=400&fit=crop'),
        ('Sandro Jacket – Black',    'SKU-003', 134.50, 407,  'Active', 'https://images.unsplash.com/photo-1548126032-079a0fb0099d?w=400&h=400&fit=crop')
    ");
}
