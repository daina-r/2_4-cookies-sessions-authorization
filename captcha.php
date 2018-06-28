<?php
session_start();

$code = mt_rand(10000, 99999);
$_SESSION['code'] = $code;

$pic = imagecreatefrompng(__DIR__.'/img/captcha.png');
$ColorBlue = imagecolorallocate($pic, 0, 188, 255);
$fontMontserrat = __DIR__.'/fonts/Montserrat-Regular.ttf';
imagettftext($pic, 50, 0, 110, 80, $ColorBlue, $fontMontserrat, $code);

header('Content-Type: image/png');
imagepng($pic);
imagedestroy($pic);