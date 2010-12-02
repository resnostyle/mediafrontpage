<?php
require_once "config.php";
require_once "widgets.php";
require_once "functions.php";

foreach (glob("widgets/*/w*.php") as $filename) {
	include_once $filename;
}

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);

// Turn on warnings
error_reporting($errlevel); 

?>
<html>
	<head>
		<title>Media Front Page - Settings</title>
		<link href="settings.css" rel="stylesheet" type="text/css" />	
	</head>
	<body>
		<div id="page">
			<div id="header">
				<ul>
<?php
// Get widgets
$widgets = getAllWidgets();
// Display menu
foreach($widgets as $widget) {
	if (!empty($widget['Title'])) {
		echo "\t\t\t\t<li class=\"menuitem ".$widget['Id']."\"><a href=\"?w=".$widget['Id']."\"><span>".$widget['Title']."</span></a></li>\n";
	}
}
// If there are any updated settings add them to database
if (!empty($_POST)) {

	$updateFunction = $_GET['w']."UpdateSettings";
	$updateFunction($_POST);
}
?>
				</ul>
			</div><!-- #header -->
			<div id="main">
<?php
// If a widget has been selected then show its settings
if (!empty($_GET['w'])) {
	$settingsDB = getAllSettings();
	$settingsFunction = $_GET['w']."Settings";
	$settingsFunction($settingsDB);
} 

?>
			</div><!-- #main -->
		</div><!-- #page -->
	</body>
</html>

<?php

?>
