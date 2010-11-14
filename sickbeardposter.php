<?php
require_once "config.php";

if(!empty($_GET["show"])) {
	$cachefile = dirname(__FILE__)."/sbpcache/".$_GET["show"].".tbn";
	if(file_exists($cachefile)) {
		$image_out = file_get_contents($cachefile);
		header('Content-type: image/png');
		echo $image_out;
	} else {
		// Display image and write to cache.
		// For the cache to work you need to create a director in the same location as this file:
		//   $ mkdir sbpcache
		//   $ chmod 777 sbpcache
		resizedimage($sickbeardurl."/showPoster/?show=".$_GET["show"], $cachefile);
	}
}

function sickbeardposter($imagesrc){
	global $sickbeardurl;
	$pos = strpos($imagesrc, "/showPoster/?");
	if($pos > 0) {
		resizedimage($sickbeardurl.substr($imagesrc, $pos + 1));
	}
}

function resizedimage($imageurl, $cache = "") {
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
			echo "<pre>".print_r($size, 1)."</pre>";
			return false;
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

	ob_start();
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
		default:
	}
	// Free up memory
	imagedestroy($image);
	imagedestroy($image_p);

	$image_out = ob_get_contents();
	ob_end_clean();

	// Output
	header('Content-type: '.$size["mime"]);
	echo $image_out;

	if(strlen($cache) > 0) {
		// Save to cache.
		if ($handle = fopen($cache, 'w')) {
			if(fwrite($handle, $image_out)) {
				fclose($handle);
			}
		}
	}
	
}

?>