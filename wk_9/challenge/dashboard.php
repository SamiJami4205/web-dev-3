<?php

require('mysqli_connect.php');
require('login_functions.php');

$page_title = 'Dashboard';
include('includes/header.html');

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

echo "<h1>Welcome, {$_SESSION['username']}!</h1>";
echo "<p>You are successfully logged in.</p>";
echo '<p><a href="logout.php">Logout</a></p>';

include('includes/footer.html');
?>