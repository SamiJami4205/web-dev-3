<?php
//this script retireves all the records from the users table.

$page_title = 'View the Current Users';
include('includes/header.html');

//page header
echo '<h1>Registered Users</h1>';

require('mysqli_connect.php'); //connect to the db

//make the query:
$q = "SELECT CONCAT(last_name, ', ', first_name) AS name, DATE_FORMAT(registration_date,'%m %d, %y') AS dr FROM users ORDER BY registration_date ASC";
$r = @mysqli_query($dbc, $q);//run the query.

//Count the number of returned rows
$num = mysqli_num_rows($r);


if ($num > 0) { // if it ran OK, display the records.
    //print how many users there are:
    echo "<p>There are currently $num registered users.</p>\n";

    //Table header.
    echo '<table width="60%">
    <thead>
    <tr>
        <th align="left">Name</th>
        <th align="left">Date Registered</th>
    </tr>
    </thead>
    <tbody>
    ';

    //fetch and print all the records:
    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        echo '<tr><td align="left">' . $row['name'] . '</td><td align="left">' . $row['dr'] . '</td></tr>';
    }
    echo '</tbody></table>'; //close the table

    mysqli_free_result($r); //free up the resources
} else {
    //public message
    echo '<p class="error">There are currently no registered users.</p>';
}

mysqli_close($dbc);

include('includes/footer.html');
?>