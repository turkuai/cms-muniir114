<?php
// Load .env variables into $_ENV
foreach (parse_ini_file('.env') as $key => $value) {
    $_ENV[$key] = $value;
}
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', $_ENV["DB_USERNAME"]);
define('DB_PASS', $_ENV["DB_PASSWORD"]);
define('DB_NAME', $_ENV["DB_NAME"]);

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

