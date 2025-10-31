<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload an RTF Document</title>
</head>
<body>
    <?php
    //Check if the form has been submitted:
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //check for an upload file:
        if (isset($_FILES['upload']) && file_exists($_FILES['upload']['tmp_name'])) {
            //Validate the type. Should be RTF
            //create the resource:
            $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
            //check the file
            if (Finfo_file($fileinfo, $_FILES['upload']['tmp_name']) == 'text/rtf') {
                //indicate its okay
                echo '<p><em>The file would be acceptanble</em></p>';
                //in theory, move the file over. in reality, delete the file:
                unlink($_FILES['upload']['tmp_name']);
            } else {
                echo '<p style="font-weight: bold; color: #C00>Please upload an RTF document.</p>';
            }
            //Close the resource:
            finfo_close($fileinfo);
        }
        //add file upload error handling, if desired

    }
    ?>
    <form enctype="multipart/form-data" action="upload_rtf.php" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="524288">
        <fieldset><legend>Select an RTF document of 512KB or smaller to be uploaded:</legend>
        <p><strong>File:</strong><input type="file" name="upload"></p>
        </fieldset>
        <div align="center"><input type="submit" name="submit" value="Submit"></div>
    </form>
</body>
</html>