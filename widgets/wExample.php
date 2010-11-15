<?php
$wdgtExample = array("name" => "Example Widget", "type" => "inline", "function" => "widgetExample();");
$wIndex["wExample"] = $wdgtExample;

function widgetExample() {
	echo "<p>Hello World</p>";
}

?>
