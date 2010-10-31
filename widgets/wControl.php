<?php
$wdgtControl = array("name" => "Control", "type" => "inline", "function" => "widgetControl();");
$wIndex["wControl"] = $wdgtControl;

function widgetControl() {
	global $shortcut;

	echo "\t\t<ul class=\"widget-list\">";
	$alt = false;
	foreach( $shortcut as $shortcutlabel => $shortcutpath) {
		echo "\t\t\t<li".(($alt) ? " class=\"alt\"" : "")."><a class=\"shortcut\" href='".$shortcutpath."' target=middle>".$shortcutlabel."</a><br/></li>";
		$alt = !$alt;
	}
	echo "\t\t</ul>";
}
?>
