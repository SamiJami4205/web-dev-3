<?php

if (isset($_GET['action']) && $_GET['action'] === 'download') {

    $filename = 'sample_' . date('Ymd_His') . '.txt';
    $content = "This is a sample file generated at " . date('c') . "\n";
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($content));
    echo $content;
    exit;
}


if (isset($_GET['action']) && $_GET['action'] === 'cache') {

    header('Cache-Control: public, max-age=60');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 60) . ' GMT');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Part 4 - HTTP Headers</title>
    <style>p.notice{background:#efefef;padding:8px;border-left:3px solid #666}</style>
</head>
<body>
    <h1>HTTP Headers (Part 4)</h1>
    <p class="notice">Use the links below to test different header behaviors.</p>
    <ul>
        <li><a href="headers.php?action=download">Force download a sample file</a></li>
        <li><a href="headers.php?action=cache">Show page with cache headers (cache for 60s)</a></li>
        <li><a href="headers.php">Show page with default headers</a></li>
    </ul>
    <p><a href="index.php">Back to Challenge Index</a></p>
</body>
</html>
