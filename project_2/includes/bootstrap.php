<?php
// Initialize the session
session_start();

// Set default timezone
date_default_timezone_set('America/New_York');

// Define constants for configuration
define('PROJECT_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', PROJECT_ROOT . '/public');
define('INCLUDE_PATH', PROJECT_ROOT . '/includes');
define('ADMIN_PATH', PROJECT_ROOT . '/admin');

// Include the configuration file
require_once PROJECT_ROOT . '/config.php';

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// Function to redirect with message
function redirect_with_message($location, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $location");
    exit();
}
?>