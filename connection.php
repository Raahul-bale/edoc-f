<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "edoc";

// Enable MySQLi exceptions for proper error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $database = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Set charset to prevent injection issues
    $database->set_charset("utf8mb4");

    // Set SQL mode for better compatibility
    $database->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

} catch (Exception $e) {
    // Log error securely (in production, use proper logging)
    error_log("Database connection error: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}