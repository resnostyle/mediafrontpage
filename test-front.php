<?php
require_once "config.php";
require_once "functions.php";
require_once "widgetdb.php";

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);

// Turn on warnings
error_reporting($errlevel); 

	foreach (glob("widgets/*/w*.php") as $filename) {
		include_once $filename;

		// Create widget
		//$$widget_init['Id'] = new widget ($widget_init['Id'], $widget_init['Type'], $widget_init['Block'], $widget_init['Title'], $widget_init['Function'], $widget_init['HeaderFunction'], $widget_init['Section'], $widget_init['Position']);

		$$widget_init['Id'] = new widget ($widget_init);
		// Add widget to database
		$$widget_init['Id']->addWidget();
		$$widget_init['Id']->updateWidget('Title', 'Hard Drives');
		// Retrieve widget from database
		$wdgt = $$widget_init['Id']->getWidget();
		echo $wdgt['Id'];
		echo $wdgt['Function'];
		echo $wdgt['Title'];
	}    	


?>
<html>
	<head>
		<title>Media Front Page</title>
		<link href="css/widget.css" rel="stylesheet" type="text/css" />	
		<link href="css/front.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="main">
		</div><!-- #main -->
		</body>
</html>
