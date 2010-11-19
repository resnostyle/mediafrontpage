<?php
$widget = "INSERT INTO Widgets (Id, Type, Title, Parts, Section, Position) VALUES ('wXBMC', mixed', 'XBMC', '\$widgetMediaLibrary, \$widgetRecentTV, \$wRecentMovies', 0, 0);";

$widgetMediaLibrary = "INSERT INTO Widgets (Id, Type, Title, Block, HeaderFunction, Call, Interval, Section, Position) VALUES ('MediaLibrary', 'ajax', 'Media Library', 'medialibrarywrapper', 'widgetMediaLibraryHeader(\$params);', 'widgets/wXBMCLibrary.php?w=l&style=w&a=l', 0, 2, 1);";

$widgetRecentEpisodes = "INSERT INTO Widgets (Id, Type, Title, Block, HeaderFunction, Call, Interval, Loader, Section, Position) VALUES ('RecentEpisodes', 'ajax', 'Media Library', 'recenttvwrapper', 'widgetMediaLibraryHeader(\$params);', 'widgets/wXBMCLibrary.php?w=l&style=w&a=l', 60000, 'cmdXbmcLibrary(\'recenttvwrapper\', \'widgets/wXBMCLibrary.php?w=re&\', \'re\', \'\', \'\', true);', 2, 1);";

$widgetRecentMovies = "INSERT INTO Widgets (Id, Type, Title, Block, HeaderFunction, Call, Interval, Loader, Section, Position) VALUES ('RecentMovies', 'ajax', 'Recent Movies', 'recentmoviewrapper', 'widgetMediaLibraryHeader(\$params);', 'widgets/wXBMCLibrary.php?w=rm&style=w&a=rm&c=15', 60000, 'cmdXbmcLibrary(\'recentmoviewrapper\', \'widgets/wXBMCLibrary.php?w=rm&\', \'rm\', \'\', \'\', true);', 1, 2;";


function widgetMediaLibraryHeader($params = array('count' => 15)) {
	//check the parameter
	echo <<< LIBRARYHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdXbmcLibrary(wrapper, harness, action, breadcrumb, query, refresh) {
				var cmdXbmcLibraryRequest = new ajaxRequest();
				var request = harness+"style=w&a="+action+"&bc="+breadcrumb+query;
				cmdXbmcLibraryRequest.open("GET", request, true);

				if(action!="p") {
					cmdXbmcLibraryRequest.onreadystatechange = function() {
						if (cmdXbmcLibraryRequest.readyState==4) {
							if (cmdXbmcLibraryRequest.status==200 || window.location.href.indexOf("http")==-1) {
								document.getElementById(wrapper).innerHTML=cmdXbmcLibraryRequest.responseText;
							} else {
								//alert("An error has occured making the request (XbmcLibrary)");
							}
						}
					}
				}
				cmdXbmcLibraryRequest.send(null);
				if(refresh) {
					var cmd = "cmdXbmcLibrary('" + wrapper + "', '" + harness + "', '" + action + "', '" + breadcrumb + "', '" + query + "', true)";
					if(action=="rm" && recentmoviewrapper_interval=="") {
						recentmoviewrapper_interval = setInterval(cmd, 60000);
					}
					if(action=="re" && recenttvwrapper_interval=="") {
						recenttvwrapper_interval = setInterval(cmd, 60000);
					}
					if(action=="d") {
						if(breadcrumb == "rm") {
							//alert("bc="+breadcrumb);
							clearInterval(recentmoviewrapper_interval);
							recentmoviewrapper_interval = "";
						}
						if(breadcrumb == "re") {
							clearInterval(recenttvwrapper_interval);
							recenttvwrapper_interval = "";
						}
					}
				}
			}
		-->
		</script>

LIBRARYHEADER;
}
// ajaxPageLoad('widgets/wXBMCLibrary.php?w=re&style=w&a=re&c=15', 'recenttvwrapper');
// cmdXbmcLibrary('recenttvwrapper', 'widgets/wXBMCLibrary.php?w=re&', 'lv', 'l', '', false);
// echo "\t\t\tsetInterval(\"ajaxPageLoad('".$widget["call"]."', '".$widget["block"]."')\", ".$widget["interval"].");\n";

if (!empty($_GET['style']) && (($_GET['style'] == "w") || ($_GET['style'] == "s"))) {
	require_once "../../config.php";
	require_once "../../functions.php";
	require_once "libXBMCLibrary.php";

	$action = $_GET['a'];
	$breadcrumb = (!empty($_GET['bc'])) ? $_GET['bc'] : "";

	$params = getParameters($_GET);

	if ($_GET['style'] == "w") {
		switch ($_GET['w']) {
			case "re": // Recent Episodes
				$params['onclickcmd'] = "cmdXbmcLibrary";
				if(empty($params['wrapper'])) {
					$params['wrapper'] = $widgetRecentEpisodes['Block'];
				}
				$params['harness'] = "widgets/xbmc/wXBMCLibrary.php?w=re&";
				$params['refresh'] = true;
				break;
			case "rm":  // Recent Movies
				$params['onclickcmd'] = "cmdXbmcLibrary";
				if(empty($params['wrapper'])) {
					$params['wrapper'] = $widgetRecentMovie['Block'];
				}
				$params['harness'] = "widgets/xbmc/wXBMCLibrary.php?w=rm&";
				$params['refresh'] = true;
				break;
			default:
				$params['onclickcmd'] = "cmdXbmcLibrary";
				if(empty($params['href'])) {
					$params['wrapper'] = $widgetMediaLibrary['Block'];
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
