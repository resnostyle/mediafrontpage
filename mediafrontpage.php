<?php
require_once "config.php";
require_once "functions.php";
require_once "widgets.php";

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);
if (!include ("layout.php")){
	// file was missing so include default theme 
	require("default-layout.php");
}
// Turn on warnings
error_reporting($errlevel); 

if (empty ($arrLayout)) {
	require_once("default-layout.php");
}
?>
<html>
	<head>
		<title>Media Front Page</title>
		<script type="text/javascript" language="javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js"></script>
		<link href="css/front.css" rel="stylesheet" type="text/css" />	
		<link href="css/widget.css" rel="stylesheet" type="text/css" />	
		<script type="text/javascript" src="js/highslide/highslide.js"></script>
		<link rel="stylesheet" type="text/css" href="js/highslide/highslide.css" />
		<script type="text/javascript">
			//<![CDATA[
			// override Highslide settings here
			// instead of editing the highslide.js file
			hs.registerOverlay({
				html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
				position: 'top right',
				fade: 2 // fading the semi-transparent overlay looks bad in IE
			});
			
			hs.showCredits = false; 
			hs.graphicsDir = 'js/highslide/graphics/';
			hs.wrapperClassName = 'borderless';
			//hs.outlineType = 'outer-glow';
			//hs.outlineType = 'borderless';
			//hs.outlineType = 'rounded-white';
			hs.outlineType = null;
			//hs.wrapperClassName = 'outer-glow';
			hs.dimmingOpacity = 0.75;
			//]]>
		</script>
		<style type="text/css">
			.highslide-dimming {
				background: black;
			}
			a.highslide {
				border: 0;
			}
		</style>		
		<!-- START: Dynamic Header Inserts From Widgets -->
<?php
		foreach( $wIndex as $wId => $widget ) {
			renderWidgetHeaders($widget);	
		}
		if(!empty($customStyleSheet)) {
			echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$customStyleSheet."\">\n";
		}
?>

		<!-- END: Dynamic Header Inserts From Widgets -->

	</head>

	<body>

		<div id="main">
		
<?php
		$arrResult = jsoncall('{"jsonrpc": "2.0", "method": "JSONRPC.Version", "id": 1}');
		if(!is_array($arrResult)) {
			echo $COMM_ERROR;
		} else {
			foreach ($arrLayout as $sectionId => $widgets) {
				echo "\n\t<ul id=\"".$sectionId."\" class=\"section ui-sortable\">\n";
				foreach ($widgets as $wId => $wAttribute) {
					echo "\t\t<li id=\"".$wId."\" class=\"widget";
                    if (strlen($wAttribute["color"]) > 0) {
						echo " ".$wAttribute["color"];
					}
                    if (strlen($wAttribute["display"]) > 0) {
						echo " ".$wAttribute["display"];
					}
					echo "\">";
					echo "\t\t\t<div class=\"widget-head\">";
					echo "\t\t\t\t<h3>".$wAttribute['title']."</h3>\n";
					echo "\t\t\t</div><!-- .widget-head -->\n";
					echo "\t\t\t<div class=\"widget-content\">\n";
					if(empty($wAttribute['params'])) {
						renderWidget($wIndex[$wId]);
					} else {
						renderWidget($wIndex[$wId], $wAttribute['params']);
					}
					echo "\t\t\t</div><!-- .widget-content -->\n";
					echo "\t\t</li><!-- #".$wId." .widget -->\n";
				}
				echo "\t</ul><!-- #".$sectionId." .section -->\n";
			}
		}
?>
		</div><!-- #main -->
    	<script type="text/javascript" src="js/jquery.js"></script>
    	<script type="text/javascript" src="js/widget.js"></script>
		</body>
</html>
