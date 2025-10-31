<?php # Script for registration
$page_title = 'Register';
include('includes/header.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('includes/mysqli_connect.php');
    
    $errors = array(); // Initialize error array.

    // Check for salutation:
    if (empty($_POST['salutation'])) {
        $errors[] = 'You forgot to select your salutation.';
    } else {
        $s = escape_data($dbc, $_POST['salutation']);
    }

    // Check for a first name:
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter your first name.';
    } else {
        $fn = escape_data($dbc, $_POST['first_name']);
    }

    // Check for a last name:
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $ln = escape_data($dbc, $_POST['last_name']);
    }

    // Check for gender:
    if (empty($_POST['gender'])) {
        $errors[] = 'You forgot to select your gender.';
    } else {
        $g = escape_data($dbc, $_POST['gender']);
    }

    // Check for race:
    if (empty($_POST['race'])) {
        $errors[] = 'You forgot to enter your race.';
    } else {
        $r = escape_data($dbc, $_POST['race']);
    }

    // Check for an email address:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = escape_data($dbc, $_POST['email']);
        if (!validate_email($e)) {
            $errors[] = 'Please enter a valid email address.';
        } elseif (user_exists($dbc, $e)) {
            $errors[] = 'This email is already registered.';
        }
    }

    // Check for a password and match against the confirmed password:
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $p = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
        }
    } else {
        $errors[] = 'You forgot to enter your password.';
    }

    if (empty($errors)) { // If everything's OK.
        // Register the user in the database...
        $q = "INSERT INTO users (salutation, first_name, last_name, gender, race, email, pass, registration_date) 
              VALUES ('$s', '$fn', '$ln', '$g', '$r', '$e', '$p', NOW())";
        $r = @mysqli_query($dbc, $q); // Run the query.
        
        if ($r) { // If it ran OK.
            // Print a message:
            echo '<div class="alert alert-success">
                    <h1>Thank you!</h1>
                    <p>You are now registered. You can now <a href="login.php">log in</a>.</p>
                  </div>';
        } else { // If it did not run OK.
            // Public message:
            echo '<div class="alert alert-danger">
                    <h1>System Error</h1>
                    <p>You could not be registered due to a system error. We apologize for any inconvenience.</p>
                  </div>';

            // Debugging message:
            echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
        }

        mysqli_close($dbc); // Close the database connection.
        include('includes/footer.html'); 
        exit();
    } else { // Report the errors.
        echo '<div class="alert alert-danger">
                <h1>Error!</h1>
                <p>The following error(s) occurred:<br>';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br>\n";
        }
        echo '</p><p>Please try again.</p></div>';
    }

    mysqli_close($dbc); // Close the database connection.
} // End of the main Submit conditional.
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="salutation">Salutation</label>
                <select class="form-control" name="salutation" required>
                    <option value="">Choose...</option>
                    <option value="Mr." <?php if (isset($_POST['salutation']) && $_POST['salutation'] == 'Mr.') echo 'selected'; ?>>Mr.</option>
                    <option value="Ms." <?php if (isset($_POST['salutation']) && $_POST['salutation'] == 'Ms.') echo 'selected'; ?>>Ms.</option>
                    <option value="Mrs." <?php if (isset($_POST['salutation']) && $_POST['salutation'] == 'Mrs.') echo 'selected'; ?>>Mrs.</option>
                    <option value="Dr." <?php if (isset($_POST['salutation']) && $_POST['salutation'] == 'Dr.') echo 'selected'; ?>>Dr.</option>
                </select>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" required>
                    <option value="">Choose...</option>
                    <option value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    <option value="Prefer not to say" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Prefer not to say') echo 'selected'; ?>>Prefer not to say</option>
                </select>
            </div>

            <div class="form-group">
                <label for="race">Race</label>
                <input type="text" class="form-control" name="race" size="20" maxlength="50" value="<?php if (isset($_POST['race'])) echo $_POST['race']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="pass1">Password</label>
                <input type="password" class="form-control" name="pass1" size="10" maxlength="20" required>
                <small class="form-text text-muted">Minimum 8 characters</small>
            </div>

            <div class="form-group">
                <label for="pass2">Confirm Password</label>
                <input type="password" class="form-control" name="pass2" size="10" maxlength="20" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.html'); ?>