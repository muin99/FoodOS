<?php
/**
 * DATABASE CONFIG
 * ---------------
 * Provide mysqli connection for the shared `foodos` database.
 * Technical requirement: all queries elsewhere must use mysqli prepared statements.
 * Each member must configure this locally for XAMPP (do not rely on another member).
 */

// TODO: Set your local XAMPP MySQL credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodos');

function getDbConnection(): mysqli
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die('Database connection failed.');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
