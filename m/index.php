<?php
require_once "../config.php";
require_once "../functions.php";
require_once "../widgets.php";

$_GET["style"] = "m";
foreach (glob("../widgets/*.php") as $filename) {
	include_once $filename;
}

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);
if (!include ("mobileconfig.php")){
	// file was missing so include default theme 
	require("default-mobileconfig.php");
}
// Turn on warnings
error_reporting($errlevel); 

if (empty ($arrLayout)) {
	require_once("default-mobileconfig.php");
}

if(empty($_GET["a"])) {
	$_GET["a"] = "l";
}
$action = $_GET["a"];

if(!empty($_GET["bc"])) {
	$breadcrumb = $_GET["bc"];
} else {
	$breadcrumb = "";
}

$params = getParameters($_GET);

if(empty($_GET["w"])) {
	$_GET["w"] = "wXBMCLibrary";
}
$widget = $_GET["w"];

if(empty($params['href'])) {
	$params['href'] = "index.php?w=".$widget;
}

?>
<html>
	<head>
		<title>Media Front Page - Mobile</title>
		<link href="mobile.css" rel="stylesheet" type="text/css" />	
	</head>
	<body>
		<div id="header">
			<ul>
<?php
			foreach($mobilelayout as $widgetlabel => $widgetindex) {
				if($widgetindex == $widget) {
					echo "\t\t\t\t<li class=\"selected\">".$widgetlabel."</li>\n";
				} else {
					echo "\t\t\t\t<li><a href=\"?w=".$widgetindex."\">".$widgetlabel."</a></li>\n";
				}
			}
?>
			</ul>
		</div><!-- #header -->
		<div id="main">
<?php
			eval($mobilefunction[$widget]);
?>
		</div><!-- #main -->
	</body>
</html>
