<?php
require_once "config.php";

if(!empty($_GET["show"])) {
	resizedimage($sickbeardurl."/showPoster/?show=".$_GET["show"]);
}

function sickbeardposter($imagesrc){
	global $sickbeardurl;
	$pos = strpos($imagesrc, "/showPoster/?");
	if($pos > 0) {
		resizedimage($sickbeardurl.substr($imagesrc, $pos + 1));
	}
}

function resizedimage($imageurl) {
	// actual script begins here
	$size = getimagesize($imageurl);
	switch($size["mime"]){
		case "image/jpeg":
			$image = imagecreatefromjpeg($imageurl); //jpeg file
			break;
		case "image/gif":
			$image = imagecreatefromgif($imageurl); //gif file
			break;
		case "image/png":
			$image = imagecreatefrompng($imageurl); //png file
			break;
		default: 
			$image = false;
			break;
	}
	$width = $size[0];
	$height = $size[1];


	// Get new dimensions
	$percent = 100 / $width;
	$new_width = $width * $percent;
	$new_height = $height * $percent;

	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	// Output
	header('Content-type: '.$size["mime"]);
	switch($size["mime"]){
		case "image/jpeg":
			imagejpeg($image_p, null, 100); //jpeg file
			break;
		case "image/gif":
			imagegif($image_p); //gif file
			break;
		case "image/png":
			imagepng($image_p, null, 100); //png file
			break;
	}

	// Free up memory
	imagedestroy($image);
	imagedestroy($image_p);
}

?>