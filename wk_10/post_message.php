<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a message</title>
</head>
<body>
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //validate the data (omitted)!
        //Connect to the database:
        $dbc = mysqli_connect('localhost', 'username', 'password', 'forum');

        //Make the query
        $q = 'INSERT INTO messages (forum_id, parent_id, subject, body, date_entered) VALUES (?,?,?,?,?, NOW())';

        //prepare the statement
        $stmt = mysqli_prepare($dbc, $q);

        //bind the variables
        mysqli_stmt_execute($stmt);

        //print a message based upon the result:
        if (mysqli_stmt_affected_rows($stmt) == 1){
            echo '<p>Your message has been posted.</p>';
        } else {
            echo '<p style="font-weight: bold; color: #C00">Your message could not be posted.</p>';
            echo '<p>' . mysqli_stmt_error($stmt) . '</p>';
        }
        //close the statement
        mysqli_stmt_close($stmt);

        //close the connection
        mysqli_close($dbc);
    }
    //Display the form
    ?>
    <form action="post_message.php" method="post">
        <fieldset>
            <legend>Post a message:</legend>
            <p><strong>Subject</strong>: <input type="text" name="subject" size="30" maxlength="100"></p>
            <p><strong>Body</strong>: <textarea name="body" rows="3" cols="40"></textarea></p>
        </fieldset>
        <div align="center"><input type="submit" value="Submit"></div>
        <input type="hidden" name="forum_id" value="1">
        <input type="hidden" name="parent_id" value="0">
    </form>
</body>
</html>