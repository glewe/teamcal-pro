<?php
/**
 * randomimage_helper.php
 * 
 * Displays a security image on the registration dialog
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**
 * Start a session since we are storing the secret number there
 */
session_start();

/**
 * Create a random string out of these characters
 */
$alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$rand = substr(str_shuffle($alphanum), 0, 5);

/**
 * Create a background image and write the string on top
 */
$image = imagecreatefromjpeg("randombg.jpg");
$textColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 5, 8, $rand, $textColor);

/**
 * Create the hash for the verification code and put it in the session
 */
$_SESSION['image_random_value'] = md5($rand);

/**
 * Send several headers to make sure the image is not cached
 */    
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header('Content-type: image/jpeg');

/** 
 * Send the image to the browser, then free the memory.
 */
imagejpeg($image);
imagedestroy($image);
?>
