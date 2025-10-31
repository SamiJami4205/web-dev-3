<?php
require('mysqli_connect.php');
require('login_functions.php');

$page_title = 'Login';
include('includes/header.html');

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = validate_login($dbc, $_POST['username'], $_POST['password']);
    
    if ($result['success']) {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];
        
        if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'yes') {
            set_remember_me($result['user_id']);
        }
        
        $query = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 'i', $result['user_id']);
        mysqli_stmt_execute($stmt);
        
        header("Location: dashboard.php");
        exit();
        
    } else {
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br>';
        foreach ($result['errors'] as $msg) {
            echo " - $msg<br>";
        }
        echo '</p>';
    }
}
?>

<h1>Login</h1>
<form action="login.php" method="post">
    <p>Username: <input type="text" name="username" size="20" maxlength="50"></p>
    <p>Password: <input type="password" name="password" size="20" maxlength="50"></p>
    <p><input type="checkbox" name="remember_me" value="yes"> Remember me</p>
    <p><input type="submit" name="submit" value="Login"></p>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include('includes/footer.html'); ?>