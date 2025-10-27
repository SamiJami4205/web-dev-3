<?php
$page_title = 'Edit Student';
include('../includes/header.html');
require('mysqli_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();
    
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = $_POST['id'];
    } else {
        $errors[] = 'Invalid student ID.';
    }
    
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter the first name.';
    } else {
        $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    }
    
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter the last name.';
    } else {
        $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }
    
    if (empty($_POST['student_number'])) {
        $errors[] = 'You forgot to enter the student number.';
    } else {
        $sn = mysqli_real_escape_string($dbc, trim($_POST['student_number']));
    }

    if (empty($_POST['gpa'])) {
        $errors[] = 'You forgot to enter the GPA.';
    } else {
        $gpa = filter_var($_POST['gpa'], FILTER_VALIDATE_FLOAT);
        if ($gpa === false || $gpa < 0 || $gpa > 4.0) {
            $errors[] = 'Please enter a valid GPA between 0 and 4.0.';
        }
    }
    
    if (empty($errors)) {
        $q = "UPDATE students SET first_name=?, last_name=?, student_number=?, gpa=? WHERE student_id=?";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_bind_param($stmt, 'sssdi', $fn, $ln, $sn, $gpa, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="alert alert-success">The student record has been updated.</div>';
        } else {
            echo '<div class="alert alert-danger">The student could not be updated due to a system error.</div>';
        }
        mysqli_stmt_close($stmt);
        
    } else {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errors as $msg) {
            echo "<li>$msg</li>";
        }
        echo '</ul></div>';
    }
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
    
    $q = "SELECT first_name, last_name, student_number, gpa FROM students WHERE student_id=?";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fn, $ln, $sn, $gpa);
    
    if (mysqli_stmt_fetch($stmt)) {
?>
<form action="edit_student.php" method="post" class="form">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    
    <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($fn); ?>">
    </div>
    
    <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($ln); ?>">
    </div>
    
    <div class="form-group">
        <label for="student_number">Student Number:</label>
        <input type="text" name="student_number" class="form-control" value="<?php echo htmlspecialchars($sn); ?>">
    </div>
    
    <div class="form-group">
        <label for="gpa">GPA:</label>
        <input type="number" name="gpa" class="form-control" step="0.01" min="0" max="4.0" value="<?php echo htmlspecialchars($gpa); ?>">
    </div>
    
    <button type="submit" class="btn btn-primary">Update Student</button>
    <a href="view_students.php" class="btn btn-secondary">Cancel</a>
</form>
<?php
    } else {
        echo '<div class="alert alert-danger">This student could not be found.</div>';
    }
    mysqli_stmt_close($stmt);
    
} else {
    echo '<div class="alert alert-danger">This page has been accessed in error.</div>';
}

mysqli_close($dbc);
include('../includes/footer.html');
?>