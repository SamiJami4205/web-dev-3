<?php 
//this page displays and image

$name = FALSE; //flag variable

//check for an image name in the URL
if (isset($_GET['image'])) {

    //make sure it has an images extension:
    $ext = strtolower( substr($_GET['image'], -4));

    if (($ext == '.jpg') OR ($ext == 'jpeg') OR ($ext == '.png')){

        //Full image path:
        $image = "../uploads/{$_GET['image']}";

        //check that the image exists and is a file:
        if (file_exists($image) && (is_file($image))) {

            //set the name as this image:
            $name = $_GET['image'];
        }
    }
}
//if there was a problem, use the default image:
if (!$name) {
    $image = 'images/unavailable.png';
    $name = 'unavailable.png';
}
//Get the image information
header ("content-Type:{$info['mine']}\n");
header ("content-Disposition: inline; filename=\"$name\"\n");
header ("Content-Length: $fs\n");

//Send the file
readfile($image);