<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Images</title>
</head>
<body>
    <p>Click on an image to view it in a separate window.</p>
    <ul>
        <?php
        // this script lists the images in the uplaods directory

        //set the default timezone
        date_default_timezone_set('America/New_York');

        $dir = '../uploads'; //Define the directory to view

        $files = scandir($dir); //Read all the images into an array

        //dispalay each image caption as a link to the JavaScript function:
        foreach ($file as $image) {
            if (substr($image, 0, 1) != '.') {// Ignore anything starting with a period.
                
                //get the images size in pixels:
                $image_size = getimagesize("$dir/$image");

                //calculate the image's size in kilobytes:
                $file_size = round( (filesize("$dir/$image")) / 1024) . "kb";

                //determine the image's upload date and time
                $image_data = date(" F d, Y H:i:s", filemtime("$dir/$image"));

                //make the image's name URL-safe:
                $image_name = urlencode($image);

                //print the information:
                    echo "<li><a href=\"javascript:create_window('$image_name',$image_size[0],$image_size[1])\">$image</a> $file_size ($image_date)</li>\n";
            } //end of the IF
        }//end of the foreach loop
        ?>
    </ul>
</body>
</html>