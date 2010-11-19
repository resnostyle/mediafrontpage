<?php
$widget = "INSERT INTO Widgets (Id, Type, Title, Function, Section, Position) VALUES ('wExample', 'inline', 'Example Widget', 'widgetExample();', 2, 3);";

function widgetExample() {
	echo "<p>Hello World</p>";
}

?>
