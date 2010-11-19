<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once "config.php";
require_once "functions.php";

try {$db = new PDO('sqlite:settings.db');

	//create the database
	$db->exec("CREATE TABLE Widgets (Id TEXT PRIMARY KEY, Type TEXT, Parts TEXT, Block TEXT, Title TEXT, Header TEXT, Function TEXT, Call TEXT, Interval INTEGER, Section INTEGER, Position INTEGER)");    
	


	foreach (glob("widgets/*/w*.php") as $filename) {
		include_once $filename;

		//insert some data...
		$db->exec($widget);
	}


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
	
	// Close the database connection
	$db = NULL;
}	
	catch(PDOException $e) {
		print 'Exception : '.$e->getMessage();	
}


function renderWidget($widget, $params = "") {
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
			foreach( $widget["Parts"] as $widgetsub ) {
				renderWidget($widgetsub);
			}
			break;
		default:	
			if(!empty($widget)) {
				echo "\n\n<strong>INVALID WIDGET SPECIFIED (".$widget["Block"].")</strong>\n<pre>".print_r($widget)."</pre>\n";
			}
	}
}

?>
