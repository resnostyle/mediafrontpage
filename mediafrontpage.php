<?php
require_once "config.php";
require_once "functions.php";
require_once "widgets.php";

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);

// Turn on warnings
error_reporting($errlevel); 

// Open Database
try {$db = new PDO('sqlite:settings.db');

	//create the database
	$db->exec("CREATE TABLE Widgets (Id TEXT PRIMARY KEY, Type TEXT, Parts TEXT, Block TEXT, Title TEXT, Function TEXT, Call TEXT, Interval INTEGER, HeaderFunction TEXT, Stylesheet TEXT, Script TEXT, Section INTEGER, Position INTEGER)");

	foreach (glob("widgets/*/w*.php") as $filename) {
		include_once $filename;

		//insert some data...
		$db->exec($widget);
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
	
		<!-- START: Dynamic Header Inserts From Widgets -->
<?php
		foreach( $widgets as $widget ) {
			renderWidgetHeaders($widget);	
		}
		if(!empty($settings['customStyleSheet'])) {
			echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$settings['customStyleSheet']."\">\n";
		}
?>

		<!-- END: Dynamic Header Inserts From Widgets -->
		<script type="text/javascript">InitPopupBox();</script>
	</head>

	<body>

		<div id="main">
		
<?php






	$i=1;

	while ($i != 4) {
		$widgets = $db->query('SELECT * FROM Widgets WHERE Section='.$i.'');
		echo "\n\t<ul id=\"section-$i\" class=\"section ui-sortable\">\n";
		
		foreach ($widgets as $widget) {
			echo "\t\t<li id=\"".$widget['Id']."\" class=\"widget";
			if (!empty($widget["Display"])) {
				echo " ".$widget["Display"];
			}
			echo "\">";
			echo "\t\t\t<div class=\"widget-head\">";
			echo "\t\t\t\t<h3>".$widget['Title']."</h3>\n";
			echo "\t\t\t</div><!-- .widget-head -->\n";
			echo "\t\t\t<div class=\"widget-content\">\n";
			
			// Output widget content
			renderWidget($widget);

			echo "\t\t\t</div><!-- .widget-content -->\n";
			echo "\t\t</li><!-- #".$widget['Id']." .widget -->\n";
		}
	echo "\t</ul><!-- #section-$i\" .section -->\n";
	$i++;
	}
	

}	
	catch(PDOException $e) {
		print 'Exception : '.$e->getMessage();	
}

// Close the database connection
$db = NULL;
		
?>
		</div><!-- #main -->
    	<script type="text/javascript" src="js/jquery.js"></script>
    	<script type="text/javascript" src="js/widget.js"></script>
		</body>
</html>
