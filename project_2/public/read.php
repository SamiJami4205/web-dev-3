<?php
$page_title = "View User";
require_once "../config.php";
require_once "../includes/header.html";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Prepare a select statement
    $sql = "SELECT * FROM users WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                // Fetch result row as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field values
                $username = $row["username"];
                $email = $row["email"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];
                $salutation = $row["salutation"];
                $gender = $row["gender"];
                $registration_date = $row["registration_date"];
            } else{
                // URL doesn't contain valid id parameter
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    unset($stmt);
    
} else{
    // URL doesn't contain id parameter
    header("location: error.php");
    exit();
}
?>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-5 mb-3">View User Profile</h1>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Username</label>
                                    <p><?php echo htmlspecialchars($username); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Email</label>
                                    <p><?php echo htmlspecialchars($email); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Registration Date</label>
                                    <p><?php echo date('F d, Y', strtotime($registration_date)); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Full Name</label>
                                    <p><?php echo $salutation ? htmlspecialchars($salutation) . ' ' : ''; ?>
                                       <?php echo $first_name ? htmlspecialchars($first_name) . ' ' : ''; ?>
                                       <?php echo htmlspecialchars($last_name); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Gender</label>
                                    <p><?php echo htmlspecialchars($gender); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="index.php" class="btn btn-primary">Back</a>
                    <?php if (is_admin() || $_SESSION['user_id'] == $param_id): ?>
                        <a href="update.php?id=<?php echo $param_id; ?>" class="btn btn-warning">Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php require_once "../includes/footer.html"; ?>