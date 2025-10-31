<?php

require('mysqli_connect.php');
require('login_functions.php');

$page_title = 'Register';
include('includes/header.html');

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    
    if (empty($_POST['username'])) {
        $errors[] = 'You forgot to enter your username.';
    } else {
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $query = "SELECT user_id FROM users WHERE username=?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = 'That username is already taken.';
        }
    }
    
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
    }
    if (!empty($_POST['password1'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            $errors[] = 'Your passwords did not match.';
        } else {
            $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
        }
    } else {
        $errors[] = 'You forgot to enter your password.';
    }
    
    if (empty($errors)) { 
        $query = "INSERT INTO users (username, email, password, registration_date) 
                VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $password);
        
        if (mysqli_stmt_execute($stmt)) { 
            echo '<h1>Thank you!</h1>
            <p>You are now registered. Please <a href="login.php">login</a> to continue.</p>';
            include('includes/footer.html');
            exit();
        } else { 
            echo '<h1>System Error</h1>
            <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
        }
    } else { 
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo '</p>';
    }
}
?>

<h1>Register</h1>
<form action="register.php" method="post">
    <p>Username: <input type="text" name="username" size="20" maxlength="50" 
        value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?>"></p>
    <p>Email Address: <input type="email" name="email" size="30" maxlength="100" 
        value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>"></p>
    <p>Password: <input type="password" name="password1" size="20" maxlength="50"></p>
    <p>Confirm Password: <input type="password" name="password2" size="20" maxlength="50"></p>
    <p><input type="submit" name="submit" value="Register"></p>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include('includes/footer.html'); ?>