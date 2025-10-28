<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Part 1 - Send an Email</title>
</head>
<body>
    <h1>Send an Email (Part 1)</h1>

    <?php
    $sent = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $to = filter_var($_POST['to'] ?? '', FILTER_VALIDATE_EMAIL);
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($to && $subject && $message) {
            $headers = 'From: webmaster@example.com' . "\r\n" .
                        'Reply-To: webmaster@example.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

            if (mail($to, $subject, wordwrap($message, 70), $headers)) {
                echo '<p><em>Message sent successfully to ' . htmlspecialchars($to) . '</em></p>';
                $sent = true;
            } else {
                echo '<p style="color: red;">Failed to send message (mail() returned false).</p>';
            }
        } else {
            echo '<p style="color: red;">Please provide a valid email, subject and message.</p>';
        }
    }
    ?>

    <?php if (!$sent): ?>
    <form action="send_email.php" method="post">
        <label for="to">To (email):</label><br>
        <input type="email" name="to" id="to" value="<?php if(isset($_POST['to'])) echo htmlspecialchars($_POST['to']); ?>" required><br>

        <label for="subject">Subject:</label><br>
        <input type="text" name="subject" id="subject" size="50" value="<?php if(isset($_POST['subject'])) echo htmlspecialchars($_POST['subject']); ?>" required><br>

        <label for="message">Message:</label><br>
        <textarea name="message" id="message" rows="7" cols="60"><?php if(isset($_POST['message'])) echo htmlspecialchars($_POST['message']); ?></textarea><br>

        <input type="submit" value="Send Email">
    </form>
    <?php endif; ?>

    <p><a href="index.php">Back to Challenge Index</a></p>
</body>
</html>
