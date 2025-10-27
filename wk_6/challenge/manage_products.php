<?php
$page_title = 'Manage Products';
include('includes/header.html');
require('mysqli_connect_challenge.php');

$product_name = $price = $description = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['product_name'])) {
        $errors[] = 'Please enter a product name.';
    } else {
        $product_name = mysqli_real_escape_string($dbc, trim($_POST['product_name']));
    }

    if (empty($_POST['price'])) {
        $errors[] = 'Please enter a price.';
    } else {
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 0) {
            $errors[] = 'Please enter a valid price.';
        }
    }

    $description = mysqli_real_escape_string($dbc, trim($_POST['description'] ?? ''));

    if (empty($errors)) {
        if (isset($_POST['id'])) {

            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $q = "UPDATE products SET product_name=?, price=?, description=? WHERE id=?";
            $stmt = mysqli_prepare($dbc, $q);
            mysqli_stmt_bind_param($stmt, 'sdsi', $product_name, $price, $description, $id);
        } else {

            $q = "INSERT INTO products (product_name, price, description) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($dbc, $q);
            mysqli_stmt_bind_param($stmt, 'sds', $product_name, $price, $description);
        }

        if (mysqli_stmt_execute($stmt)) {
            echo '<p class="text-success">Product has been ' . (isset($_POST['id']) ? 'updated' : 'added') . '!</p>';
        } else {
            echo '<p class="text-danger">System Error: ' . mysqli_error($dbc) . '</p>';
        }
        mysqli_stmt_close($stmt);
    }
}


if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo '</ul></div>';
}
?>

<h1>Manage Products</h1>

<form action="manage_products.php" method="post" class="form">
    <div class="form-group">
        <label for="product_name">Product Name:</label>
        <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" class="form-control" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>">
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" name="description"><?php echo htmlspecialchars($description); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php include('includes/footer.html'); ?>