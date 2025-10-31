<?php 
//the user is redirected here for login.php

//if no cookie is present, redirect the user:

//if (!isset($_COOKIE['user_id'])){
session_start();
//if not session value is present, redirect the user
    //if (!isset($_SESSION['user_id'])){
        if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) )){

    //need the functions:
    require('includes/login_functions.inc.php');
    redirect_user();
}

//set the page title and include the HTML eader:
$page_title = 'Logged In!';
include('includes/header.html');

//print a customized message:
//echo "<h1>Logged In!</h1>
    //<p>You are now logged in, {$_COOKIE['first_name']}!</p>
    //<p><a hred=\"logout.php\">Logout</a></p>";

echo "<h1>Logged In!</h1>
    <p>You are now logged in, {$_SESSION['first_name']}!</p>
    <p><a hred=\"logout.php\">Logout</a></p>";

include('includes/footer.html');
?>