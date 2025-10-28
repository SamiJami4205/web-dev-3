//make a pop up window function
function create_window(image, width, height) {

    //add some pixels to the width and heights:
    width = width + 10;
    height = height + 10;

    //if the window is already open
    //resize it to the new dimensions:
    if (window.popup && !window.popup.closed) {
        window.popup.resizeTo(width, height);
    }

    //Set the window properties:
    var specs = "location=no,scrollbars=no,menubar=no,toolbar=no,resizable=yes,left=0,top=0,width=" + width + ",heigt=" + height;

    // Set the URL:
    var url = "show_image.php?image=" + image;

    // Create the pop-up window:
    popup = window.open(url, "ImageWindow", specs);
    popup.focus();
} //End of function