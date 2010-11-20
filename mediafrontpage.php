<?php
require_once "config.php";
require_once "functions.php";
require_once "widgets.php";

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);

// Turn on warnings
error_reporting($errlevel); 

// Add Widgets
foreach (glob("widgets/*/w*.php") as $widgetfile) {
	include_once $widgetfile;

	// Initialise widget
	$$widget_init['Id'] = new widget ($widget_init, $widgetfile);

	// Add widget to database
	$$widget_init['Id']->addWidget();

	// Get widgets
	$widgets = getAllWidgets();
}
	
?>
<html>
	<head>
		<title>Media Front Page</title>
		<script type="text/javascript" language="javascript" src="js/ajax.js"></script>
		<script type="text/javascript" language="javascript" src="js/popuptext.js"></script>
		<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js"></script>
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
		<link href="css/widget.css" rel="stylesheet" type="text/css" />	
		<link href="css/front.css" rel="stylesheet" type="text/css" />
		<link href="layouts/3col.css" rel="stylesheet" type="text/css" />

		<!-- START: Dynamic Header Inserts From Widgets -->
<?php
		// Render widget headers 
		foreach ($widgets as $widget) {
			$directory = dirname($widget['File']);
			$$widget['Id']->renderWidgetHeaders($directory);
		}    	
?>
		<!-- END: Dynamic Header Inserts From Widgets -->
		<script type="text/javascript">InitPopupBox();</script>
	</head>
	<body>
		<div id="main">
		<!-- START: Dynamic Inserts From Widgets -->
<?php
		//While ( $i < $settings['sections'] ) { //needs work

			echo "\n\t<ul id=\"section-1\" class=\"section ui-sortable\">\n";

			foreach ($widgets as $widget) {
				echo "\t\t<li id=\"".$widget['Id']."\" class=\"widget";
	
				// Is widget collapsed
				if (!empty($widget["Display"])) {
					echo " ".$widget["Display"];
				}
				echo "\">";
				echo "\t\t\t<div class=\"widget-head\">";
				echo "\t\t\t\t<h3>".$widget['Title']."</h3>\n";
				echo "\t\t\t</div><!-- .widget-head -->\n";
				echo "\t\t\t<div class=\"widget-content\">\n";
				
				// Output widget content
				$$widget['Id']->renderWidget($widget);
					
				echo "\t\t\t</div><!-- .widget-content -->\n";
				echo "\t\t</li><!-- #".$widget['Id']." .widget -->\n";
			}
			echo "\t</ul><!-- #section-1 .section -->\n";    	
		//} END of Layout
 ?>
		<!-- END: Dynamic Inserts From Widgets -->
		</div><!-- #main -->
	    	<script type="text/javascript" src="js/jquery.js"></script>
    		<script type="text/javascript" src="js/widget.js"></script>
	</body>
</html>
