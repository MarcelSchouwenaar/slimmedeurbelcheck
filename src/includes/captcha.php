<?php
 
// We start a session to access
// the captcha externally!
session_start();
 
// Generate a random number
// from 1000-9999
$captcha = rand(1000, 9999);
 
// The captcha will be stored
// for the session
$_SESSION["captcha"] = $captcha;  

// Create image
$im = imagecreatetruecolor(160, 48);  

// Colors
$bg = imagecolorallocate($im, 0, 72, 53);
$fg = imagecolorallocate($im, 255, 255, 255);
$noise1 = imagecolorallocate($im, 215, 242, 0);
$noise2 = imagecolorallocate($im, 0, 20, 10);

// Fill background
imagefill($im, 0, 0, $bg);

// Add random lines for noise
for ($i = 0; $i < 8; $i++) {
    imageline(
        $im,
        rand(0, 160), rand(0, 48),
        rand(0, 160), rand(0, 48),
        $noise1
    );
}

// Add random dots for noise
for ($i = 0; $i < 800; $i++) {
    imagesetpixel($im, rand(0, 159), rand(0, 47), $noise2);
}

// Use a TTF font for better visibility
$font = __DIR__ . '/../assets/Bebas.ttf'; // Make sure this font file exists!

// if (file_exists($font)) {
//     for ($i = 0; $i < strlen($captcha); $i++) {
//         $angle = rand(-20, 20);
//         $x = 20 + $i * 30 + rand(-2, 2);
//         $y = rand(30, 40);
//         imagettftext($im, 36, $angle, $x, $y, $fg, $font, $captcha[$i]);
//     }
// } else {
    // Fallback to imagestring if font is missing
    imagestring($im, 5, 40, 15, $captcha, $fg);
// }
 
// VERY IMPORTANT: Prevent any Browser Cache!!
header("Cache-Control: no-store,
            no-cache, must-revalidate"); 
 
// The PHP-file will be rendered as image
header('Content-type: image/png');
 
// Finally output the captcha as
// PNG image the browser
imagepng($im); 

// Free memory
imagedestroy($im);
?>