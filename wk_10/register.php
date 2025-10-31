<?php
//this script performs an INSERT query to ad a record to the users tble
$page_title = 'Register';
include('includes/header.html');

//check for form submission:
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require('mysqli_connect.php'); 
        $errors = [];
        //check for a first name
        if (empty($_POST['first_name'])) {
            $errors[] = 'You forgot to enter your first name.';
        } else {
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        }
        //check for a last name
        if ($empty($_POST['last_name'])) {
            $erros[] = 'You forgot to enter your last name.';
        } else {
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        }
        //check for an email address:
        if (empty($_POST['email'])) {
            $errors[] = 'You forgot to enter your email address';
        } else {
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        }
        if (!empty($_POST['pass1'])){
            if ($_POST['pass1'] != $_POST['pass2']) {
                $errors[] = 'Your password did not match the confirmed password.';
            } else {
                $p = password_hash(trim($_POST['pass1']), PASSWORD_DEFAULT);
            }
        } else {
        $errors[] = 'You forgot to enter yout password';
        }

        if (empty($errors)) {
            //register the user in the databse
            //make the query:
            $q = "INSERT INTO user (firsdt_name, last_name, email, pass, registration_date) VALUES ('$fn', '$ln', '$e', '$p', NOW() )";
            $r = @mysqli_query($dbc,$q);
            if ($r) {
                //print a message
                echo '<h1>Thank You!</h1>
                <p> You are now registered. In chapter 12 you will be able to log in</p><p><br></p>';

            } else {
                //public message 
                echo '<h1>System Error</h1>
                <p class="error">you could not be registered due to a system error. We apologize for any inconvenience.</p>';

                //Debugging message
                echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
            }
            mysqli_close($dbc);

            //include the footer and wuit the script
            include('includes/footer.html');
            exit();
        } else {
            echo '<h1>Error!</h1>
            <p class="error">The following error(s) occured:<br>';
            foreach ($error as $msg) {
                //print each error
                echo "- $msg<br>\n";
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
    <p><input type="submit" value="Register"></p>
</form>
<?php include('includes/footer.html'); ?>