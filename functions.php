<?php
require_once "config.php";

$COMM_ERROR = "\n<p><strong>XBMC's JSON API did not respond.</strong></p>\n<p>Check your configuration (config.php) and that the JSON service variable is configured correctly and that the <a href=\"".$xbmcjsonservice."\">Service</a> is running.</p>\n";

function jsoncall($request, $service_uri = "") {
	global $xbmcjsonservice;
	
	if($service_uri == "") {
		$service_uri = $xbmcjsonservice;
	}
	//json rpc call procedure
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $service_uri);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	$arrResult = json_decode(curl_exec($ch), true);

	curl_close($ch);
	
	return $arrResult;
}

function to_readable_size($size) {
	switch (true) {
		case ($size > 1000000000000):
			$size /= 1000000000000;
			$suffix = 'Tb';
		break;
		case ($size > 1000000000):  
			$size /= 1000000000;
			$suffix = 'Gb';
		break;
		case ($size > 1000000):
			$size /= 1000000;
			$suffix = 'Mb';   
		break;
		case ($size > 1000):
			$size /= 1000;
			$suffix = 'Kb';
		break;
		default:
		$suffix = 'b';
	}
	return round($size, 0)." ".$suffix;
}
  
function disk_used_space($value) {
	return disk_total_space("$value") - disk_free_space("$value");
}

function disk_used_percentage($value) {
	return round(disk_used_space("$value") / disk_total_space("$value") * 100, 2);
}

function formattimes($input1, $input2) {
	$seconds1 = $input1 % 60;
	$input1 = floor($input1 / 60);
	$minutes1 = $input1 % 60;
	$hours1 = floor($input1 / 60); 

	$seconds2 = $input2 % 60;
	$input2 = floor($input2 / 60);

	$minutes2 = $input2 % 60;
	$hours2 = floor($input2 / 60); 
	
	if($hours1 > 0 || $hours2 > 0) {
		$output1 = str_pad($hours1,2,'0',STR_PAD_LEFT).":";
		$output2 = str_pad($hours2,2,'0',STR_PAD_LEFT).":";
	} else {
		$output1 = "";
		$output2 = "";
	}
	$output1 = $output1.str_pad($minutes1, 2, '0', STR_PAD_LEFT).":".str_pad($seconds1, 2, '0', STR_PAD_LEFT);
	$output2 = $output2.str_pad($minutes2, 2, '0', STR_PAD_LEFT).":".str_pad($seconds2, 2, '0', STR_PAD_LEFT);

	return $output1." - ".$output2;
}

function return_array_code($array) {
	//Example call:
	//$layout_code_string = "$arrLayout = ".return_array_code($arrLayout).";";

	$first = true;

	$output = "array(";

	foreach($array as $key => $value) {
		if($first) {
			$first = false;
		} else {
			$output .= ", ";
		}
		
		if(is_array($value)) {
			$value = return_array_code($value);
			$output .= '"'.$key.'" => '.$value;
		} else {
			$output .= '"'.$key.'" => "'.$value.'"';
		}
	}
	$output .= ")";

	return $output;
}
?>
