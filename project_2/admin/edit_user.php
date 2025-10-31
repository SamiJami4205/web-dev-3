<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$page_title = "Edit User - Heroics Inc";
include '../includes/header.html';
require_once '../includes/mysqli_connect.php';

function sanitize_input($mysqli, $input) {
    $input = trim($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $mysqli->real_escape_string($input);
}

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = false;

// Get user data
$stmt = $mysqli->prepare("SELECT u.*, ud.address, ud.city, ud.zip_code 
                        FROM users u 
                        LEFT JOIN user_details ud ON u.id = ud.user_id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: dashboard.php");
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = sanitize_input($mysqli, $_POST['address'] ?? '');
    $city = sanitize_input($mysqli, $_POST['city'] ?? '');
    $zip = sanitize_input($mysqli, $_POST['zip'] ?? '');
    
    $mysqli->begin_transaction();
    
    try {
        // Update user details
        $stmt = $mysqli->prepare("UPDATE users SET first_name = ?, last_name = ?, gender = ?, race = ? WHERE id = ?");
        $stmt->bind_param("ssssi", 
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['gender'],
            $_POST['race'],
            $user_id
        );
        $stmt->execute();
        
        // Check if user_details exists
        $check = $mysqli->prepare("SELECT id FROM user_details WHERE user_id = ?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $details_exist = $check->get_result()->num_rows > 0;
        $check->close();
        
        if ($details_exist) {
            $stmt = $mysqli->prepare("UPDATE user_details SET address = ?, city = ?, zip_code = ? WHERE user_id = ?");
        } else {
            $stmt = $mysqli->prepare("INSERT INTO user_details (address, city, zip_code, user_id) VALUES (?, ?, ?, ?)");
        }
        
        $stmt->bind_param("sssi", $address, $city, $zip, $user_id);
        $stmt->execute();
        
        $mysqli->commit();
        $success = true;
        
        // Refresh user data
        $stmt = $mysqli->prepare("SELECT u.*, ud.address, ud.city, ud.zip_code 
                                FROM users u 
                                LEFT JOIN user_details ud ON u.id = ud.user_id 
                                WHERE u.id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
    } catch (Exception $e) {
        $mysqli->rollback();
        $error = "Update failed: " . $e->getMessage();
    }
}
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Edit User</h2>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">User updated successfully!</div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="post" action="edit_user.php?id=<?php echo $user_id; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                        value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                        value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="Male" <?php if($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                                <option value="Prefer not to say" <?php if($user['gender'] == 'Prefer not to say') echo 'selected'; ?>>Prefer not to say</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="race" class="form-label">Race</label>
                            <input type="text" class="form-control" id="race" name="race" 
                                    value="<?php echo htmlspecialchars($user['race']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                    value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                        value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="zip" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="zip" name="zip" 
                                        value="<?php echo htmlspecialchars($user['zip_code'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.html'; ?>