<?php
require_once "config.php";
require_once "functions.php";
require_once "widgetdb.php";

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);

// Turn on warnings
error_reporting($errlevel); 

?>
<html>
	<head>
		<title>Media Front Page</title>
		<link rel="stylesheet" type="text/css" href="css/widget.css" />	
		<link rel="stylesheet" type="text/css" href="css/front.css" />

		<!-- START: Dynamic Header Inserts From Widgets -->
<?php

	foreach (glob("widgets/*/w*.php") as $filename) {
		include_once $filename;

		// Initialise widget
		$$widget_init['Id'] = new widget ($widget_init);

		// Add widget to database
		$$widget_init['Id']->addWidget();
		$$widget_init['Id']->updateWidget('Title', 'Hard Drives');

		// Render Widget Headers 
		$directory = dirname($filename);
		$$widget_init['Id']->renderWidgetHeaders($directory);

		// Retrieve widget from database
		$$widget_init['Id'] = $$widget_init['Id']->getWidget();
	}    	
?>
		<!-- END: Dynamic Header Inserts From Widgets -->
	</head>
	<body>
		<div id="main">
		<!-- START: Dynamic Inserts From Widgets -->
<?php



?>
		<!-- END: Dynamic Inserts From Widgets -->
		</div><!-- #main -->
		</body>
</html>
