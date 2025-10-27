<?php

$page_title = 'Change your Password';

include('includes/header.html');

//check for form subbmission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('mysqli_connect.php');
    $errors = []; //initialize an error array.

    //check for an email address:
    if (empty($_POST['email'])){
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }

    //check for the current password:
    if (empty($_POST['pass'])){
        $errors[] = 'You forgot to enter your current password.';
    } else {
            $p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
    }

    //check for the current password and match the confirmed password
        if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $np = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
        }
    }else {
        $errors[] = 'You forgot to enter your new password.';
    }

    if(empty($errors)) {
        //check that they've entered the right email address/password combination:
        $q = "SELECT user_id FROM users WHERE (email='$e' AND pass=SHA2('$p', 512) )";
        $r = @mysqli_query($dbc, $q);
        $num = @mysqli_num_rows($r);
        if ($num == 1) {
            // get the user_id
            $row = mysqli_fetch_array($r, MYSQLI_NUM);

            //make the UPDATE query
            $q = "UPDATE users SET pass=SHA2('$np',512) WHERE user_id=$row[0]";
            $r = @mysqli_query($dbc, $q);

            if (mysqli_affected_rows($dbc) == 1) {

                //print a message 
                echo '<h1>Thank you!</h1><p>Your password has been updated.</p><p><br></p>';
            } else {
                //public message:
                echo '<h1>System Error</h1><p class="error">Your password could not be changes due to a system eroor. we apologize for any inconvenience.</p>';

                //debugging message
                echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
            }

            mysqli_close($dbc); //close db conn

            //include the footer and quit the script (to not show the form).
            include('includes/footer.html');
            exit();
        } else {
            //invalid password and email combo
            echo '<h1>Error!</h1><p class="error">The email address and password do not match those on file.</p>';
        }
    } else {
        //invalid email/password combo
        echo '<h1>Error!</h1><p class="error">The following error(s) occured:<br>';
        foreach ($error as $msg) {
            echo " - $msg<br>\n";
        }
        echo '</p><p>Please try again.</p><p><br></p>';
    }
    mysqli_close($dbc);//close db
}
?>
<h1>Change your password</h1>
<form action="password.php" method="post">
    <p>Email Address: <input type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"></p>
    <p>Current password: <input type="password" name="pass" size="10" maxlength="20" value="<?php if (isset($_POST['pass'])) echo $_POST['pass']; ?>"></p>    <p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>"></p>
    <p>Confirm New Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>"></p>
    <p><input type="submit" name="submit" value="Change Password"></p>
</form>
<?php include('includes/footer.html'); ?>