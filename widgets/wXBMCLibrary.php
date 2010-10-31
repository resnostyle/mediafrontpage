<?php
$wdgtXbmcLibrary = array("name" => "XBMC Library", "type" => "ajax", "block" => "xbmclibrarywrapper", "call" => "widgets/wXBMCLibrary.php?w=l&style=w&a=l", "interval" => 0, "headerfunction" => "widgetLibraryHeader(\$params);");
$wIndex["wXBMCLibrary"] = $wdgtXbmcLibrary;

$wdgtRecentMovie = array("name" => "Recent Movies", "type" => "ajax", "block" => "recentmoviewrapper", "call" => "widgets/wXBMCLibrary.php?w=rm&style=w&a=rm&c=15", "interval" => 0);
$wIndex["wRecentMovies"] = $wdgtRecentMovie;

$wdgtRecentTV = array("name" => "Recent Episodes", "type" => "ajax", "block" => "recenttvwrapper", "call" => "widgets/wXBMCLibrary.php?w=re&style=w&a=re&c=15", "interval" => 0);
$wIndex["wRecentTV"] = $wdgtRecentTV;


function widgetLibraryHeader($params = array('count' => 15)) {
	//check the parameter
	echo <<< LIBRARYHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdXbmcLibrary(wrapper, harness, action, breadcrumb, query) {
				var cmdXbmcLibraryRequest = new ajaxRequest();
				var request = harness+"style=w&a="+action+"&bc="+breadcrumb+query;
				cmdXbmcLibraryRequest.open("GET", request, true);

				if(action!="p") {
					cmdXbmcLibraryRequest.onreadystatechange = function() {
						if (cmdXbmcLibraryRequest.readyState==4) {
							if (cmdXbmcLibraryRequest.status==200 || window.location.href.indexOf("http")==-1) {
								document.getElementById(wrapper).innerHTML=cmdXbmcLibraryRequest.responseText;
							} else {
								alert("An error has occured making the request");
							}
						}
					}
				}
				cmdXbmcLibraryRequest.send(null);
			}
		-->
		</script>

LIBRARYHEADER;
}

if (!empty($_GET['style']) && (($_GET['style'] == "w") || ($_GET['style'] == "s"))) {
	require_once "../config.php";
	require_once "../functions.php";
	require_once "libXBMCLibrary.php";

	$action = $_GET['a'];
	$breadcrumb = $_GET['bc'];

	$params = getParameters($_GET);

	if ($_GET['style'] == "w") {
		switch ($_GET['w']) {
			case "re": // Episodes
				$params['onclickcmd'] = "cmdXbmcLibrary";
				if(empty($params['wrapper'])) {
					$params['wrapper'] = $wdgtRecentTV['block'];
				}
				$params['harness'] = "widgets/wXBMCLibrary.php?w=re&";
				break;
			case "rm":   // Movies
				$params['onclickcmd'] = "cmdXbmcLibrary";
				if(empty($params['wrapper'])) {
					$params['wrapper'] = $wdgtRecentMovie['block'];
				}
				$params['harness'] = "widgets/wXBMCLibrary.php?w=rm&";
				break;
			default:
				$params['onclickcmd'] = "cmdXbmcLibrary";
				if(empty($params['href'])) {
					$params['wrapper'] = $wdgtXbmcLibrary['block'];
				}
				$params['harness'] = "widgets/wXBMCLibrary.php?w=l&";
		}
		$params['href'] = "#";
	} else {
		if(empty($params['href'])) {
			$params['href'] = "wXBMCLibrary.php";
		}
	}
	
	executeVideo($_GET['style'], $action, $breadcrumb, $params);
}
?>