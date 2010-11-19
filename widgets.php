<?php
require_once "config.php";
require_once "functions.php";

function renderWidget($widget) {
	global $DEBUG;
	
	switch ($widget["Type"]) {
		case "inline":
			if($DEBUG) { echo "\n<!-- Calling Function:".$widget["Function"]." -->\n"; }
			eval($widget["Function"]);
			echo "\n";
			break;
		case "ajax":
			echo "\n\t\t\t<div id=\"".$widget["Block"]."\"></div>\n";
			break;
		case "header":
			//Support header only widgets.
			break;
		case "mixed":
			echo $widget["Parts"];
			break;
		default:	
			if(!empty($widget)) {
				echo "\n\n<strong>INVALID WIDGET SPECIFIED (".$widget["Block"].")</strong>\n<pre>".print_r($widget)."</pre>\n";
			}
	}
}

//Support the Widget "sytlesheet", "headerfunction", "headerinclude", "script" properties
function renderWidgetHeaders($widget) {
	global $DEBUG;
	
	switch ($widget["Type"]) {
		case "ajax":
			echo "\t\t<script type=\"text/javascript\" language=\"javascript\">\n";
			
			$loader = (!empty($widget["Loader"])) ? $widget["Loader"] : "ajaxPageLoad('".$widget["Call"]."', '".$widget["Block"]."');"; 
			if((int)$widget["interval"] > 0) {
				echo "\t\t\tvar ".$widget["Block"]."_interval = setInterval(\"".$loader."\", ".$widget["Interval"].");\n";
			}
			echo "\t\t\t".$loader."\n";
			echo "\t\t</script>\n";
			break;
		case "mixed":
			foreach( $widget["Parts"] as $widgetsub ) {
				renderWidgetHeaders($widgetsub);
			}
			break;
	}
	if(!empty($widget['Stylesheet']) && (strlen($widget['Stylesheet']) > 0)) {
		echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$widget['Stylesheet']."\">\n";
	}
	if(!empty($widget['Script']) && (strlen($widget['script']) > 0)) {
		echo "\t\t<link type=\"text/javascript\" language=\"javascript\ src=\"".$widget['Script']."\">\n";
	}
	if(!empty($widget['HeaderInclude']) && (strlen($widget['HeaderInclude']) > 0)) {
		echo "\t\t".$widget["HeaderInclude"]."\n";
	}
	if(!empty($widget['HeaderFunction']) && (strlen($widget['HeaderFunction']) > 0)) {
		if($DEBUG) { echo "\n<!-- Calling Function:".$widget['HeaderFunction']." -->\n"; }
		eval($widget['HeaderFunction']);
	}
}

?>
