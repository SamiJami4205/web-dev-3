<?php
$page_title = 'View Students';
include('../includes/header.html');
require('mysqli_connect.php');


$display = 10;

if (isset($_GET['p']) && is_numeric($_GET['p'])) {
    $pages = $_GET['p'];
} else {
    $q = "SELECT COUNT(student_id) FROM students";
    $r = @mysqli_query($dbc, $q);
    $row = @mysqli_fetch_array($r, MYSQLI_NUM);
    $records = $row[0];
    
    if ($records > $display) {
        $pages = ceil($records/$display);
    } else {
        $pages = 1;
    }
}

if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

$where = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($dbc, trim($_GET['search']));
    $where = "WHERE CONCAT(last_name, ' ', first_name) LIKE '%$search%' 
            OR student_number LIKE '%$search%'";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'last_name';
$sort_options = array('last_name', 'first_name', 'student_number', 'gpa');
if (!in_array($sort, $sort_options)) {
    $sort = 'last_name';
}

$q = "SELECT student_id, CONCAT(last_name, ', ', first_name) AS name, 
        student_number, gpa, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr 
        FROM students $where 
        ORDER BY $sort ASC LIMIT $start, $display";
$r = @mysqli_query($dbc, $q);

echo '<form method="get" action="view_students.php" class="form-inline mb-4">
    <div class="form-group mx-sm-3 mb-2">
        <input type="text" name="search" class="form-control" 
            placeholder="Search students..." value="' . (isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '') . '">
    </div>
    <button type="submit" class="btn btn-primary mb-2">Search</button>
</form>';

echo '<table class="table table-striped">
    <thead>
        <tr>
            <th><a href="?sort=name">Name</a></th>
            <th><a href="?sort=student_number">Student ID</a></th>
            <th><a href="?sort=gpa">GPA</a></th>
            <th>Registration Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';

if ($r) {
    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        echo '<tr>
            <td>' . $row['name'] . '</td>
            <td>' . $row['student_number'] . '</td>
            <td>' . $row['gpa'] . '</td>
            <td>' . $row['dr'] . '</td>
            <td>
                <form action="edit_student.php" method="post" style="display:inline">
                    <input type="hidden" name="id" value="' . $row['student_id'] . '">
                    <button type="submit" class="btn btn-primary btn-sm">Edit</button>
                </form>
            </td>
        </tr>';
    }
    echo '</tbody></table>';
    
    mysqli_free_result($r);
    
    if ($pages > 1) {
        echo '<nav><ul class="pagination">';
        if ($start > 0) {
            echo '<li class="page-item"><a class="page-link" href="?s=' . ($start - $display) . 
                '&p=' . $pages . '&sort=' . $sort . '">Previous</a></li>';
        }
        
        for ($i = 1; $i <= $pages; $i++) {
            if ($i != $current_page) {
                echo '<li class="page-item"><a class="page-link" href="?s=' . (($display * ($i - 1))) . 
                    '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a></li>';
            } else {
                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            }
        }
        
        if ($start + $display < $records) {
            echo '<li class="page-item"><a class="page-link" href="?s=' . ($start + $display) . 
                '&p=' . $pages . '&sort=' . $sort . '">Next</a></li>';
        }
        echo '</ul></nav>';
    }
    
} else {
    echo '<p class="text-danger">Could not retrieve the data because:<br>' . 
        mysqli_error($dbc) . '.</p><p>The query being run was: ' . $q . '</p>';
}

mysqli_close($dbc);
include('../includes/footer.html');
?>