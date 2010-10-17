<?php
require_once "config.php";
require_once "functions.php";
require_once "widgets.php";

if(!empty($_REQUEST["value"])) { 

	$value=$_REQUEST["value"];

	$fp = fopen('layout.php', 'w');

	fwrite($fp, $value);
}

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);
if(!include('layout.php'))
{
	// file was missing so include default theme 
	require('default-layout.php');
}
// Turn on warnings
error_reporting($errlevel); 

?>
<html>
	<head>
		<title>Media Front Page</title>
		<script type="text/javascript" language="javascript" src="ajax/ajax.js"></script>
		<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js"></script>
		<link href="css/front.css" rel="stylesheet" type="text/css" />	
		<link href="css/widget.css" rel="stylesheet" type="text/css" />	
		
		<!-- START: Dynamic Header Inserts From Widgets -->
<?php
		foreach( $wIndex as $wId => $widget ) {
			renderWidgetHeaders($widget);	
		}
		if(strlen($customStyleSheet) > 0) {
			echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$customStyleSheet."\">\n";
		}
?>

		<!-- END: Dynamic Header Inserts From Widgets -->

	</head>

	<body>

		<div id="main">
		
<?php
			foreach( $arrLayout as $sectionId => $widgets ) {
				echo "\n\t<ul id=\"".$sectionId."\" class=\"section ui-sortable\">\n";
				foreach( $widgets as $wId => $wAttribute ) {
					echo "\n\t\t<li id=\"".$wId."\" class=\"widget ";
                                	
					echo $wAttribute['color']." ".$wAttribute['display'];
					
					echo "\">";
					echo "<div class=\"widget-head\">";
					echo "<h3>".$wAttribute['title']."</h3>";
					echo "</div>";
					echo "<div class=\"widget-content\">";

						renderWidget($wIndex[$wId], $wAttribute['params']);

					echo "</div>";
					echo "\n\t\t</li><!-- ".$wId." -->\n";
				}
				echo "\n\t</ul><!-- ".$sectionId." -->\n";
			}
?>
		</div><!-- main -->
    	<script type="text/javascript" src="js/jquery.js"></script>
    	<script type="text/javascript" src="js/widget.js"></script>
		</body>
</html>
