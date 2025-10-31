<?php 
session_start();//access the existing session.


//if no cookie is present, redirect the user:
//if (!isset($_COOKIE['user_id'])) {
if (!isset($_SESSION['user_id'])) {

    //need the function
    require('login_functions.inc.php');
    redirect_user();
} else {
    //setcookie('user_id', '', time()-3600, '/', '', 0, 0);
    //setcookie('first_name', '', time()-3600, '/', '', 0, 0);
    $_SESSION = []; //clear the variables
    session_destroy();//destroy the session itself
    setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0); //Destroy the cookie
}
//set the page title and include the HTML header:
$page_title = 'Logged Out!';
include('includes/header.html');

//print a customized message:
echo "<h1>Logged OUT!</h1>
<p> You are now logged out!</p>";//{$_COOKIE['first_name']}

include('includes/footer.html');
?>