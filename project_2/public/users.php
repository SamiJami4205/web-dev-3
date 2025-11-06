<?php
$page_title = "User Management";
require_once "../config.php";
require_once "../includes/header.html";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Initialize success/error message
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<div class="container">
    <?php if (isset($message)): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">User Management</h2>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> Add New User</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
                <?php
                // Attempt select query execution
                $sql = "SELECT * FROM users";
                if($result = $pdo->query($sql)){
                    if($result->rowCount() > 0){
                        echo '<table class="table table-hover">';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Username</th>";
                                    echo "<th>Email</th>";
                                    echo "<th>Action</th>";
                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = $result->fetch()){
                                echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>";
                                        echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                        echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                        echo '<a href="delete.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";                            
                        echo "</table>";
                        unset($result);
                    } else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                ?>
            </div>
        </div>        
    </div>
</div>
<?php include "includes/footer.html"; ?>