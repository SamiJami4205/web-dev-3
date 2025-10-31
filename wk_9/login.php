<?php

//this page processes the login form submission. 
//upon successful login, the user is redirected
//two included files are necessary. 
//send nothing to the web browser prior to the setcookie() lines

//check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //for processing the login:
        require('includes/login_functions.inc.php');

        //Need the database connection
        require('mysqli_connect.php');

        //check the login:
        list($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);

        if ($check) {
            //set the cookies:
            //setcookie('user_id', $data['user_id'], time()+3600, '/', '', 0, 0);
            //setcookie('first_name', $data['first_name'], time()+3600, '/', '', 0, 0);
            //set the session data:
            session_start();
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['first_name'] = $data['first_name'];

            //store the HTTP_USER_AGENT
            $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
            
            //redirect:
            redirect_user('loggedin.php');
        } else {

            //assign data to errors for error reporting in the login_page.inc.php file
            $errors = $data;
        }
        mysqli_close($dbc);
} //end of the main submit conditionqal
//create the page
include('includes/login_page.inc.php');
?>