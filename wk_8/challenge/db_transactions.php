<?php
require_once __DIR__ . '/mysqli_connect.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = $mysqli->real_escape_string(trim($_POST['title'] ?? ''));
    $content = $mysqli->real_escape_string(trim($_POST['content'] ?? ''));

    if ($title && $content) {
        $mysqli->begin_transaction();
        try {
            $sql = "INSERT INTO notes (title, content) VALUES ('{$title}', '{$content}')";
            if ($mysqli->query($sql) === false) {
                throw new Exception($mysqli->error);
            }
            $mysqli->commit();
            $msg = 'Note added successfully.';
        } catch (Exception $e) {
            $mysqli->rollback();
            $msg = 'Error adding note: ' . $e->getMessage();
        }
    } else {
        $msg = 'Please provide title and content.';
    }
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->begin_transaction();
    try {
        if ($mysqli->query("DELETE FROM notes WHERE id = {$id}") === false) {
            throw new Exception($mysqli->error);
        }
        $mysqli->commit();
        $msg = 'Note deleted.';
    } catch (Exception $e) {
        $mysqli->rollback();
        $msg = 'Delete failed: ' . $e->getMessage();
    }
}


$result = $mysqli->query("SELECT id, title, content, created_at FROM notes ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Part 6 - DB Transactions</title>
</head>
<body>
    <h1>DB Transactions (Part 6)</h1>
    <?php if ($msg) echo '<p>' . htmlspecialchars($msg) . '</p>'; ?>

    <h2>Add a Note</h2>
    <form method="post" action="db_transactions.php">
        <input type="hidden" name="action" value="add">
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Content:<br><textarea name="content" rows="4" cols="60" required></textarea></label><br>
        <input type="submit" value="Add Note">
    </form>

    <h2>Notes</h2>
    <?php if ($result && $result->num_rows): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                    (<?php echo htmlspecialchars($row['created_at']); ?>)
                    - <a href="db_transactions.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this note?');">Delete</a>
                    <div><?php echo nl2br(htmlspecialchars($row['content'])); ?></div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No notes yet.</p>
    <?php endif; ?>

    <p><a href="index.php">Back to Challenge Index</a></p>
</body>
</html>
