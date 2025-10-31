<?php
// this page defines two functions used by yhe login/logout process

//this function determines an absolute URL and redirects the user there
//the function takes one argument: the page to be directed to 
//the argument defaults to index.php

function redirect_user($page = 'index.php'){
    //start defining the URL
    //URL is http:// plus the host name plus current directory
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

    //Remove any trailing slashes
    $url = rtrim($url, '/\\');

    //add the page
    $url .= '/' . $page;

    //redirect the user
    header("Location: $url");
    exit();
}

//this function validates the form data (the email address and password)
//if both are present the database is queried 
//the function requires a database connection
//the function returns and array of information, including
// - a TRUE/FALSE variable indicating success
// an array of either errors or the database result 

function check_login($dbc, $email = '', $pass = '') {
    $errors = [];
    //validate the email address
    if (empty($email)) {
        $errors[] ='You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($email));
    }
    //Validate the password
    if (empty($pass)){
        $errors[] = 'You forgot to enter your password.';
    } else {
        $p = trim($pass);
    }

    if (empty($errors)) {
        //retrieve the user_id and first_name for that email/password combo
        $q = "SELECT user_id, first_name FROM users WHERE email='$e'";
        $r = @mysqli_query($dbc, $q);

        //check the result
        if (mysqli_num_rows($r) == 1) {
            //fetch the record
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

            //check the password
            if (password_verify($p, $row['pass'])){
                unset($row['pass']);
                return [true, $row];
            } else {
                $errors[] = 'The email address and password entered do not match those on file.';
            }

        } else {
            $errors[] = 'The email address and password entered do not match those on file.';
        }
    }
    //return dalse and the errors
    return [false, $errors];
}
?>