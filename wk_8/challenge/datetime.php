<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Part 5 - Date & Time</title>
</head>
<body>
    <h1>Date & Time (Part 5)</h1>
    <?php
    $zones = ['UTC','America/New_York','Europe/London','Asia/Tokyo','Australia/Sydney'];
    $selected = $_POST['timezone'] ?? 'UTC';
    try {
        $dt = new DateTime('now', new DateTimeZone($selected));
    } catch (Exception $e) {
        $dt = new DateTime('now', new DateTimeZone('UTC'));
        $selected = 'UTC';
    }
    ?>

    <form method="post" action="datetime.php">
        <label for="tz">Select timezone:</label>
        <select id="tz" name="timezone">
            <?php foreach ($zones as $z): ?>
                <option value="<?php echo $z; ?>" <?php if ($z === $selected) echo 'selected'; ?>><?php echo $z; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Show Time">
    </form>

    <h2>Current time in <?php echo htmlspecialchars($selected); ?></h2>
    <p><?php echo $dt->format('Y-m-d H:i:s'); ?></p>

    <p><a href="index.php">Back to Challenge Index</a></p>
</body>
</html>
