<?php
require_once "../config.php";
require_once "admin_functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = trim($_POST["id"]);
    
    // Prevent admin from deleting their own account
    if ($id == $_SESSION['user_id']) {
        $_SESSION['message'] = "You cannot delete your own admin account.";
        $_SESSION['message_type'] = "danger";
        header("location: dashboard.php");
        exit();
    }
    
    // Prepare delete statement
    $sql = "DELETE FROM users WHERE id = :id";
    
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "User successfully deleted.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting user.";
            $_SESSION['message_type'] = "danger";
        }
    }
    
    header("location: dashboard.php");
    exit();
} else {
    header("location: dashboard.php");
    exit();
}