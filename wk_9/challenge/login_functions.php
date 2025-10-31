<?php

session_start();


function is_logged_in() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        return true;
    }
    

    if (isset($_COOKIE['remember_me'])) {
        return validate_remember_me_cookie();
    }
    
    return false;
}


function validate_login($dbc, $username, $password) {
    $errors = [];
    

    $username = mysqli_real_escape_string($dbc, trim($username));
    if (empty($username)) {
        $errors[] = 'You forgot to enter your username.';
    }

    if (empty($password)) {
        $errors[] = 'You forgot to enter your password.';
    }
    
    if (empty($errors)) { 

        $query = "SELECT user_id, username, password FROM users WHERE username=?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if (password_verify($password, $row['password'])) {
                return ['success' => true, 'user_id' => $row['user_id'], 'username' => $row['username']];
            } else {
                $errors[] = 'The username and password do not match our records.';
            }
        } else {
            $errors[] = 'The username and password do not match our records.';
        }
    }
    
    return ['success' => false, 'errors' => $errors];
}


function set_remember_me($user_id) {
    $token = bin2hex(random_bytes(32));
    $session_id = session_id();
    $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
    

    global $dbc;
    $query = "INSERT INTO user_sessions (session_id, user_id, token, expiry) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'siss', $session_id, $user_id, $token, $expiry);
    mysqli_stmt_execute($stmt);
    

    setcookie('remember_me', $token, strtotime('+30 days'), '/', '', true, true);
}


function validate_remember_me_cookie() {
    global $dbc;
    if (!isset($_COOKIE['remember_me'])) return false;
    
    $token = mysqli_real_escape_string($dbc, $_COOKIE['remember_me']);
    $session_id = session_id();
    
    $query = "SELECT u.user_id, u.username FROM users u 
            INNER JOIN user_sessions s ON u.user_id = s.user_id 
            WHERE s.token=? AND s.session_id=? AND s.expiry > NOW()";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $token, $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        return true;
    }
    
    return false;
}
?>