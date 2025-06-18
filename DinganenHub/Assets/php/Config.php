<?php
/**
 * Database Configuration File
 * Contains all database connection settings
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Change to your database username
define('DB_PASS', '');              // Change to your database password
define('DB_NAME', 'dinganenhub');       // Change to your database name

// Create database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8");
    
    return $conn;
}

// Test connection function
function testConnection() {
    try {
        $conn = getDBConnection();
        $conn->close();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>