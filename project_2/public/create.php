<?php
$page_title = "Create User";
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

// Define variables and initialize with empty values
$username = $email = $password = $first_name = $last_name = $salutation = $gender = "";
$username_err = $email_err = $password_err = $first_name_err = $last_name_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Please enter a username.";
    } else {
        // Check if username exists
        $sql = "SELECT id FROM users WHERE username = :username";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $input_username);
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = escape_data($input_username);
                }
            }
        }
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email.";
    } elseif(!validate_email($input_email)){
        $email_err = "Please enter a valid email address.";
    } else {
        // Check if email exists
        $sql = "SELECT id FROM users WHERE email = :email";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $input_email);
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $email_err = "This email is already registered.";
                } else{
                    $email = escape_data($input_email);
                }
            }
        }
    }
    
    // Validate password
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Please enter a password.";     
    } elseif(strlen($input_password) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = password_hash($input_password, PASSWORD_DEFAULT);
    }
    
    // Get optional fields
    $first_name = escape_data(trim($_POST["first_name"] ?? ""));
    $last_name = escape_data(trim($_POST["last_name"] ?? ""));
    $salutation = escape_data(trim($_POST["salutation"] ?? ""));
    $gender = escape_data(trim($_POST["gender"] ?? ""));
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, salutation, gender) 
                VALUES (:username, :email, :password, :first_name, :last_name, :salutation, :gender)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":first_name", $first_name);
            $stmt->bindParam(":last_name", $last_name);
            $stmt->bindParam(":salutation", $salutation);
            $stmt->bindParam(":gender", $gender);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
}
?>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Create User</h2>
                <p>Please fill this form to create a new user record.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err;?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email_err;?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err;?></span>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Salutation</label>
                            <select name="salutation" class="form-control">
                                <option value="">Select...</option>
                                <option value="Mr." <?php echo ($salutation == "Mr.") ? "selected" : ""; ?>>Mr.</option>
                                <option value="Mrs." <?php echo ($salutation == "Mrs.") ? "selected" : ""; ?>>Mrs.</option>
                                <option value="Ms." <?php echo ($salutation == "Ms.") ? "selected" : ""; ?>>Ms.</option>
                                <option value="Dr." <?php echo ($salutation == "Dr.") ? "selected" : ""; ?>>Dr.</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select...</option>
                            <option value="Male" <?php echo ($gender == "Male") ? "selected" : ""; ?>>Male</option>
                            <option value="Female" <?php echo ($gender == "Female") ? "selected" : ""; ?>>Female</option>
                            <option value="Other" <?php echo ($gender == "Other") ? "selected" : ""; ?>>Other</option>
                            <option value="Prefer not to say" <?php echo ($gender == "Prefer not to say") ? "selected" : ""; ?>>Prefer not to say</option>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="users.php" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>        
    </div>
</div>
<?php require_once "../includes/footer.html"; ?>