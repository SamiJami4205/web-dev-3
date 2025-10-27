<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
</head>
<body>
    <?php 
    //check form for submission:
        if($_SERVER['REQUEST_METHOD'] ==  'POST') {

            //minimal form validation:
            if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['comments']) ) {

                //create the body:
                $body = "Name: {$_POST['name']}\n\nComments: {$_POST['comments']}";

                //Make it no longer than 70 characters long:
                $body = wordwrap($body, 70);

                //send the email:
                mail('your_email@example.com', 'Contact Form Sumission', $body, "From: {$_POST['email']}");

                //Print a message
                echo '<p><em>Thank you for contacting me. I will reply some day.</em></p>';

                // clear $_POST 
                $_POST = [];
            } else {
                echo '<p style="font-weight: bold; color: #c00">Please fill out the form completely.</p>';
            }
        }
    ?>
    <p>Please fill out this form to contact me.</p>
    
</body>
</html>