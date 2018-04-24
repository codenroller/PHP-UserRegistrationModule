<?php
include '../../lib/common/functions.php';

// set session
if (!isset($_SESSION))
{
    session_start();
    header('Cache-control: private'); //do not cache!
}

// create a 75x30 pixel image
$width = 100;
$height = 30; 
$image = imagecreate($width, $height);

// fill the image background color
$bg_color = imagecolorallocate($image, 0x33, 0x66, 0xFF);
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// fetch random text
$text = random_text(5, true);

// determine x and y coordinates for centering text
$font = 5;
$x = imagesx($image) / 2 - strlen($text) * imagefontwidth($font) / 2;
$y = imagesy($image) / 2 - imagefontheight($font) / 2;

// write text on image
$fg_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
imagestring($image, $font, $x, $y, $text, $fg_color);

// save the CAPTCHA string for later comparison
$_SESSION['captcha'] = $text;

// output the image
header('Content-type: image/png');
imagepng($image);

// destroy image to free memory
imagedestroy($image);
?>