<?php
// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'heroics_db');

// Create connection - using the style from wk_6
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Could not connect to MySQL: ' . mysqli_connect_error());

// Set charset
mysqli_set_charset($dbc, 'utf8');

// Function to sanitize user inputs
function escape_data($dbc, $data) {
    return mysqli_real_escape_string($dbc, trim($data));
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check if user exists
function user_exists($dbc, $email) {
    $email = escape_data($dbc, $email);
    $q = "SELECT id FROM users WHERE email='$email'";
    $r = @mysqli_query($dbc, $q);
    return (mysqli_num_rows($r) > 0);
}
?>