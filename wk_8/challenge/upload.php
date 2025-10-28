<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Part 2 - Upload Image</title>
</head>
<body>
    <h1>Upload an Image (Part 2)</h1>

    <?php
    $upload_dir = __DIR__ . '/uploads';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed)) {
                $message = 'Only JPG, PNG and GIF images are allowed.';
            } elseif ($file['size'] > 2 * 1024 * 1024) { // 2MB limit
                $message = 'File must be smaller than 2MB.';
            } else {
                $name = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
                $target = $upload_dir . '/' . $name;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $message = 'Upload successful!';
                    echo '<p><strong>' . htmlspecialchars($message) . '</strong></p>';
                    echo '<p>View image: <a href="show_image.php?img=' . urlencode($name) . '">Show uploaded image</a></p>';
                } else {
                    $message = 'Failed to move uploaded file.';
                }
            }
        } else {
            $message = 'No file uploaded or upload error.';
        }
    }

    if ($message) echo '<p>' . htmlspecialchars($message) . '</p>';
    ?>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
        <label for="image">Choose an image (JPG/PNG/GIF, max 2MB):</label><br>
        <input type="file" name="image" id="image" accept="image/*"><br><br>
        <input type="submit" value="Upload Image">
    </form>

    <p><a href="index.php">Back to Challenge Index</a></p>
</body>
</html>
