function getDynamicImageSize($remote_file = '', $quality = 100) {
    list($origWidth, $origHeight) = getimagesize($remote_file);
    $maxWidth = $origWidth;
    $maxHeight = $origHeight;
    if (isset($_GET['width'])) {
        $maxWidth = $_GET['width'];
    }
    if ($maxWidth == 0) {
        $maxWidth = $origWidth;
    }
    if (isset($_GET['height'])) {
        $maxHeight = $_GET['height'];
    }
    if ($maxHeight == 0) {
        $maxHeight = $origHeight;
    }
    if (isset($_GET['width']) && !isset($_GET['height'])) {
        // Calculate ratio of desired maximum sizes and original sizes.
        $widthRatio = $maxWidth / $origWidth;
        $heightRatio = $maxHeight / $origHeight;

// Ratio used for calculating new image dimensions.
        $ratio = min($widthRatio, $heightRatio);

// Calculate new image dimensions.
        $newWidth = (int) $origWidth * $ratio;
        $newHeight = (int) $origHeight * $ratio;
    } else {
        $newWidth = (int) $maxWidth;
        $newHeight = (int) $maxHeight;
    }

    list($width, $height) = getimagesize($remote_file);
    $image_p = imagecreatetruecolor($new_width, $new_height);
    $mimiType = get_image_mime_type($remote_file);
    $mimiType = strtolower($mimiType);
    if ($mimiType == 'image/jpg' || $mimiType == 'image/jpeg') {
        $image = imagecreatefromjpeg($remote_file);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        if ($save === null) {
            header('Content-Type: image/jpeg');
        }
        imagejpeg($image_p, $save, $quality);
        imagedestroy($image_p);
    } elseif ($mimiType == 'image/png') {

        $image = ImageCreateFromPNG($remote_file);
        imagealphablending($image_p, false);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        if ($save === null) {
            header('Content-Type: image/png');
        }
        imagesavealpha($image_p, true);
        imagepng($image_p);
    } elseif ($mimiType == 'image/gif') {
        $image = imagecreatefromgif($remote_file);
    }
}
