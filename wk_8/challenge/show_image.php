<?php
$uploads = __DIR__ . '/uploads';
$name = isset($_GET['img']) ? basename($_GET['img']) : '';
$path = $uploads . '/' . $name;
if (!$name || !file_exists($path)) {
    echo '<p>Image not found.</p><p><a href="upload.php">Back</a></p>';
    exit;
}

$mime = mime_content_type($path);
header('Content-Type: ' . $mime);
readfile($path);
exit;
?>
