<?php
function check_file_is_image($fileName,$extendsion_array=''){
    //you need to send $_FILES["fileToUpload"]["name"]
    $imageFileType = pathinfo($fileName,PATHINFO_EXTENSION);
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        set_flash_msg("Sorry, only JPG, JPEG, PNG & GIF files are allowed.","myred");
        return false;
    }return TRUE;
}

function check_file_size($fileSize, $maxSize=200000){
    //you need to send $_FILES["fileToUpload"]["size"]
    if ($fileSize > $maxSize) {
        set_flash_msg("Sorry, your file is too large.","myred");
        return false;
    }return true;
}

function check_file_exists($target_file){
    if (file_exists($target_file)) {
        set_flash_msg("Sorry, file already exists.","myred");
        return false;
    }return true;
}
?>