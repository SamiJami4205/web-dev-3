<?php 

$page_title = 'Delete a user';
include('includes/header.html');
echo '<h1>Delete a User</h1>';

//Check for a valid user ID, THROUGH get or post
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ){ //from view_users.php
    $id = $_GET['id'];    
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ){//form submission
    $id = $_POST['id'];    
} else {//no valid id, kill the script
    echo '<p class="error">This page has been accessed in error.</p>';
    include('includes/footer.html');
    exit();
}

require('mysqli_connect.php');

//check if the form has been submitted:
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['sure'] == 'Yes') {//Delete the record
            //Make the query
            $q = "DELETE FROM users WHERE user_id=$id LIMIT 1";
            $r = @mysqli_query($dbc, $q);
            if (mysqli_affected_rows($dbc) == 1){
                //print a message
                echo '<p>The user has been deleted.</p>';
            } else {
                echo '<p class="error">The user could not be deleted due to a system error.</p>';
                //public message
                echo '<p>' . mysqli_error($dbc) . '<br>Query: ' . $q . '</p>'; //debugging message
            }
        } else {
            echo '<p>The user has NOT been deleted.</p>';
        }
    } else {

        //Retrieve the user's information:
        $q = "SELECT CONCAT(last_name, ', ', first_name) FROM users WHERE user_id=$id";
        $r = @mysqli_query($dbc, $q);

        if (mysqli_num_rows($r) == 1) {

            //get the user's information
            $row = mysqli_fetch_array($r, MYSQLI_NUM);

            //Display the record being deleted
            echo "<h3>Name: $row[0]</h3><p>Are you sure you want to delete this user?</p>";

            //create the form:
                echo '<form action="delete_user.php" method="post">
                    <input type="radio" name="sure" value="Yes"> Yes
                    <input type="radio" name="sure" value="No" checked="chceked"> No
                    <input type="sumbit" name="submit" Value="submit">
                    <input type="hidden" name="id" value="' . $id . '">
                </form>';
        } else {
            echo '<p class="error">This page has been accessed in error.</p>';
        }
    }

    mysqli_close($dbc);
    include('includes/footer.html');
    ?>