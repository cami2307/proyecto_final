<?php
// Verifica si la sesión ya está iniciada antes de llamarlo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'proyecto_final');

// Connecting to the database with error handling
try {
    $connect = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception for better error handling
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally set charset for better handling of special characters
    $connect->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
