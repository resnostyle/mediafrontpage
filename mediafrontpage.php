<?php
require_once "config.php";
require_once "functions.php";
require_once "layout.php";
	
?>
<html>
	<head>
		<title>Media Front Page</title>
		<link rel='stylesheet' type='text/css' href='css/front.css' />
		<script type="text/javascript" language="javascript" src="ajax.js" />
		
		<!-- START: Dynamic Header Inserts From Widgets -->
<?php
		foreach( $arrLayout as $sectionId => $widgets ) {
			foreach( $widgets as $widgetId => $widget ) {
				renderWidgetHeaders($widget);
			}
		}
?>

		<!-- END: Dynamic Header Inserts From Widgets -->
	</head>
	<body>
		<div id='main'>
<?php
			foreach( $arrLayout as $sectionId => $widgets ) {
				echo "\n\t<div id=\"".$sectionId."\">\n";
				foreach( $widgets as $widgetId => $widget ) {
					echo "\n\t\t<div id=\"".$widgetId."\">\n";
					renderWidget($widget);
					echo "\n\t\t</div><!-- ".$widgetId." -->\n";
				}
				echo "\n\t</div><!-- ".$sectionId." -->\n";
			}
?>
	</body>
</html>
