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
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		<link href="mobile.css" rel="stylesheet" type="text/css" />	
<meta name="viewport" content="initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" charset="utf-8">
	addEventListener('load', function() {
		setTimeout(hideAddressBar, 0);
	}, false);
	function hideAddressBar() {
		window.scrollTo(0, 1);
	}
</script>
	</head>
	<body>
	
<div class="container bg_black">
	<!-- <h1>Media Center</h1> -->
		<div id="header">
			<ul>
<?php
			foreach($mobilelayout as $widgetlabel => $widgetindex) {
				if(empty($current)) {
					$current = $widgetlabel;
				}
				if($widgetindex == $widget) {
					$currentclass = " current";
					$current = $widgetlabel;
				} else {
					$currentclass = "";
				}
				echo "\t\t\t\t<li class=\"menuitem ".$widgetlabel."".$currentclass."\"><a href=\"?w=".$widgetindex."\"><span>".$widgetlabel."</span></a></li>\n";
			}
?>
			</ul>
		</div><!-- #header -->
		<div id="main">
<?php
			echo "<h1 class=\"title\">".$current."</h1>\n";
			eval($mobilefunction[$widget]);
?>
		</div><!-- #main -->
		</div>
	</body>
</html>
