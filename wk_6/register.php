<?php 
//this script preforms an INSERT query to add a recor to the users table

$page_title = 'Register';
include('includes/header.html');

//check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = []; //initialize an error array.

    //check for a first name:
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter your first name.';
    } else {
        $fn = mysqli_real_escape_string($dbc,trim($_POST['first_name']));
    }

    //check for a last name:
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }

    //check for an email address:
    if (empty($_POST['email'])){
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }
    //checck for a password and match against the confirmed password:
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
        }
    }else {
        $errors[] = 'You forgot to enter your password.';
    }
    if (empty($errors)) { //if everything is okay
        
        //Register the user in the database..
        require('mysqli_connect.php'); //connct to the DB

        //make the query:
        $q = "INSERT INTO users (first_name, last_name, email, pass, registration_date)
            VALUES ('$fn', '$ln', '$e', SHA2('$p', 512), NOW() )";
            $r = @mysqli_query($dbc, $q);
            if ($r) {//if it ran ok
                
                //print a message:
                echo '<h1>Thank you!</h1>
                        <p>You are now registered. In Chaper 12 you will actually be able to log in!</p>
                        <p><br></p>';

            } else {// if it did not run okay
                
                //public message:
                echo '<h1>Sysyem Error</h1><p class="error">You could not be registered due to a system eroor. we apologize for any inconvenience.</p>';

                //debugging message:
                echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
            }//end of if($r) IF.

            mysqli_close($dbc); //close the database connection

            //include the footer and quit the script
            include('includes/footer.html');
            exit();
    } else {
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occured:<br>';
        foreach ($errors as $msg) {
            echo " - #msg<br>\n";
        }
        echo '</p><p>Please try again.</p><p><br></p>';
    }
    
    mysqli_close($dbc);
}
?>
<h1>Register</h1>
<form action="register.php" method="post">
    <p>First Name: <input type="text" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>"></p>
    <p>Last Name: <input type="text" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>"></p>
    <p>Email Address: <input type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"></p>
    <p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>"></p>
    <p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>"></p>
</form>
<?php include('includes/footer.html'); ?>