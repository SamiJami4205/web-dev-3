<?php

require('mysqli_connect.php');


session_start();


if (isset($_COOKIE['remember_me'])) {
    $token = mysqli_real_escape_string($dbc, $_COOKIE['remember_me']);
    $query = "DELETE FROM user_sessions WHERE token = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    
    setcookie('remember_me', '', time() - 3600, '/');
}


$_SESSION = array();
session_destroy();


$page_title = 'Logged Out!';
include('includes/header.html');


echo '<h1>Logged Out!</h1>
<p>You are now logged out.</p>
<p><a href="login.php">Login again</a></p>';

include('includes/footer.html');
?>