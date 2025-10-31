<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$page_title = "Admin Dashboard - Heroics Inc";
include '../includes/header.html';
require_once '../includes/mysqli_connect.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ? AND id != ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

// Pagination setup
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Get total records for pagination
$total_query = "SELECT COUNT(*) FROM users WHERE id != {$_SESSION['user_id']}";
$total_result = $mysqli->query($total_query);
$total_records = $total_result->fetch_row()[0];
$total_pages = ceil($total_records / $per_page);

// Get users for current page
$query = "SELECT u.*, ud.address, ud.city, ud.zip_code, ud.date_registered 
            FROM users u 
            LEFT JOIN user_details ud ON u.id = ud.user_id 
            WHERE u.id != {$_SESSION['user_id']}
            ORDER BY u.created_at DESC 
            LIMIT ?, ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $start, $per_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="admin-content">
    <h2>User Management</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Zip</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['salutation'] . ' ' . $row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['city'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['zip_code'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['date_registered'] ?? $row['created_at']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="dashboard.php?delete=<?php echo $row['id']; ?>" 
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page - 1); ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>

<?php 
$stmt->close();
include '../includes/footer.html'; 
?>