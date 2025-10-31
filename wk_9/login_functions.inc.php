<?php 
//this page defines two functions used by the login/logout process

//this function determines an absolute url and redirects the user there
//The function takes one argument: the page to be redirectd to 
//the argument defaults to index.php

function redirect_user($page = 'index.php') {

    //start defining the URL. URL is http:// plus the host name plus the current directory:
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

    //remove any trailing slashes:
    $url = rtrim($url, '/\\');

    //add the page:
    $url .= '/' . $page;

    //redirect the user:
    header("location: $url");
    exit(); //quit the script
} // End of redirect_user() function
//This function validates the form data (the email address and password)
//the function requires a database connection
//the function returns an array of information, including 
//a true/false variable indicating success
//an array of either error or the database result

function check_login($dbc, $email = '', $pass = '') {
    $errors = []; //intialize error array

    //validate the email address:
    if (empty($email)) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($pass));
    }
    if (empty($errors)) {
        //Retrieve the user_id and first_name for that email/password combination
        $q = "SELECT user_id, first_name FROM users WHERE email='$e' AND pass=SHA2('$p', 512)";
        $r = @mysqli_query($dbc, $q); //run the query

        //check the result
        if (mysqli_num_rows($r) == 1) {

            //fetch the record
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

            //Return true and the record:
            return [true, $row];
        } else {
            $error[] = 'The email address and password entered do not match those on file.';
        }
    } // end of empty($errors) IF
    //return false and the errors
    return [false, $errors];
}// end of check_login() function
?>