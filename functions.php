<?php
require_once "config.php";

function renderWidget($widget) {
	switch ($widget["type"]) {
		case "inline":
			if($DEBUG) { echo "\n<!-- Calling Function:".$widget["function"]." -->\n"; }
			eval($widget["function"]);
			echo "\n";
			break;
		case "ajax":
			echo "\n\t\t\t<div id=\"".$widget["block"]."\"></div>\n";
			break;
		case "mixed":
			foreach( $widget["parts"] as $widgetsub ) {
				renderWidget($widgetsub);
			}
			break;
		default:
			echo "\n\n<strong>INVALID WIDGET SPECIFIED (".$widgetId.") in section ".$sectionId."</strong>\n<pre>".print_r($widget)."</pre>\n";
	}
}
//Support the Widget "sytlesheet", "headerfunction", "headerinclude", "script" properties
function renderWidgetHeaders($widget) {
	switch ($widget["type"]) {
		case "ajax":
			echo "\t\t<script type=\"text/javascript\" language=\"javascript\">\n";
			if((int)$widget["interval"] > 0) {
				echo "\t\t\tsetInterval(\"ajaxPageLoad('".$widget["call"]."', '".$widget["block"]."')\", ".$widget["interval"].");\n";
			}
			echo "\t\t\tajaxPageLoad('".$widget["call"]."', '".$widget["block"]."');\n";
			echo "\t\t</script>\n";
			break;
		case "mixed":
			foreach( $widget["parts"] as $widgetsub ) {
				renderWidgetHeaders($widgetsub);
			}
			break;
	}
	if(strlen($widget["sytlesheet"]) > 0) {
		echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$widget["sytlesheet"]."\">\n";
	}
	if(strlen($widget["script"]) > 0) {
		echo "\t\t<link type=\"text/javascript\" language=\"javascript\ src=\"".$widget["script"]."\">\n";
	}
	if(strlen($widget["headerinclude"]) > 0) {
		echo "\t\t".$widget["headerinclude"]."\n";
	}
	if(strlen($widget["headerfunction"]) > 0) {
		if($DEBUG) { echo "\n<!-- Calling Function:".$widget["headerfunction"]." -->\n"; }
		eval($widget["headerfunction"]);
	}
}

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
	$arrResult = json_decode(curl_exec($ch),true);

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

?>
