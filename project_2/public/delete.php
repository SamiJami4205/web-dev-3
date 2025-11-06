<?php
$page_title = "Delete User";
require_once "../config.php";
require_once "../includes/header.html";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
} elseif (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: users.php");
    exit();
}

// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    
    // Prepare a delete statement
    $sql = "DELETE FROM users WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Records deleted successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    unset($stmt);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter
        header("location: error.php");
        exit();
    }
}
?>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5 mb-3">Delete Record</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger">
                        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                        <p>Are you sure you want to delete this user record?</p>
                        <p>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="users.php" class="btn btn-secondary">No</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>
<?php require_once "../includes/footer.html"; ?>