<?php

function get_default_quiz_image()
{
    return base64_encode(file_get_contents("img/missing_quiz.png"));
}

function get_default_profile_image()
{
    return base64_encode(file_get_contents("img/missing_profile.png"));
}

/**
 * Resizes a image to 200x200
 * @param $file array The $_FILE["name"] element
 * @return string The binary data of the resized image
 */
function resize_image($file)
{
    $fn = $file['tmp_name'];
    $size = getimagesize($fn);
    $ratio = $size[0] / $size[1]; // width/height
    if ($ratio > 1) {
        $width = 200;
        $height = 200 / $ratio;
    } else {
        $width = 200 * $ratio;
        $height = 200;
    }
    $src = imagecreatefromstring(file_get_contents($fn));
    $dst = imagecreatetruecolor($width, $height);

    imagesavealpha($dst, true);
    $trans_colour = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $trans_colour);

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
    imagedestroy($src);

    ob_start();
    imagepng($dst); // adjust format as needed
    $image_data = ob_get_contents();
    ob_end_clean();
    imagedestroy($dst);
    return $image_data;
}