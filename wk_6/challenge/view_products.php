<?php
$page_title = 'View Products';
include('includes/header.html');
require('mysqli_connect_challenge.php');


if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $q = "DELETE FROM products WHERE id = ?";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<p class="text-success">Product has been deleted.</p>';
        } else {
            echo '<p class="text-danger">Could not delete product: ' . mysqli_error($dbc) . '</p>';
        }
        mysqli_stmt_close($stmt);
    }
}

$q = "SELECT COUNT(*) as count FROM products";
$result = mysqli_query($dbc, $q);
$row = mysqli_fetch_assoc($result);
$total_products = $row['count'];

$q = "SELECT id, product_name, price, description FROM products ORDER BY product_name ASC";
$result = mysqli_query($dbc, $q);

if ($result) {
    echo "<h2>Total Products: $total_products</h2>";
    
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                <td>' . htmlspecialchars($row['product_name']) . '</td>
                <td>$' . number_format($row['price'], 2) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>
                    <a href="manage_products.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary">Edit</a>
                    <a href="view_products.php?delete=' . $row['id'] . '" 
                        class="btn btn-sm btn-danger"
                        onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                </td>
            </tr>';
        }
        
        echo '</tbody></table>';
        
    } else {
        echo '<p class="text-info">No products found.</p>';
    }
    
    mysqli_free_result($result);
    
} else {
    echo '<p class="text-danger">Could not retrieve products: ' . mysqli_error($dbc) . '</p>';
}

mysqli_close($dbc);
include('includes/footer.html');
?>