<?php
$wdgtControl = array("name" => "Control", "type" => "inline", "function" => "widgetControl(\"widgets/wControl.php\", true);", "headerfunction" => "widgetControlHeader();");
$wIndex["wControl"] = $wdgtControl;

function widgetControlHeader() {
	echo <<< CONTROLHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdControl(requesturl) {
				var cmdPlayingRequest = new ajaxRequest();
				cmdPlayingRequest.open("GET", requesturl, true);
				cmdPlayingRequest.send(null);
			}
		-->
		</script>

CONTROLHEADER;
}
function widgetMenu($baseurl) {
	global $shortcut;

	if(empty($_GET["style"])) {
		$style = "w";
	} else {
		$style = $_GET["style"];
	}

	echo "<ul class=\"widget-list\">\n";
	$alt = false;
	foreach( $shortcut as $shortcutlabel => $shortcutmixed) {
		if(is_array($shortcutmixed)) {
			if(!empty($shortcutmixed["json"])) {
				$href = $baseurl."?w=wControl&style=".$style."&json=".urlencode($shortcutmixed["json"]);
			}
			if(!empty($shortcutmixed["cmd"])) {
				$href = $baseurl."?w=wControl&style=".$style."&cmd=".$shortcutmixed["cmd"];
			}
			if($style == "m") {
				echo "\t<li".(($alt) ? " class=\"alt\"" : "")."><a class=\"shortcut\" href=\"".$href."\">".$shortcutlabel."</a><br/></li>\n";
			} elseif($style == "w") {
				echo "\t<li".(($alt) ? " class=\"alt\"" : "")."><a class=\"shortcut\" onclick=\"cmdControl('".$href."');\" href=\"#\">".$shortcutlabel."</a><br/></li>\n";
			}
		} else {
			echo "\t<li".(($alt) ? " class=\"alt\"" : "")."><a class=\"shortcut\" href=\"".$shortcutmixed."\">".$shortcutlabel."</a><br/></li>\n";
		}
		$alt = !$alt;
	}
	echo "</ul>\n";
}
function widgetControl($baseurl = "widgets/wControl.php", $forcemenu = false) {
	if(!empty($_GET["style"]) && (($_GET["style"] == "w") || ($_GET["style"] == "m"))) {
		$displayMenu = false;
		if(!empty($_GET["cmd"])) {
			switch ($_GET["cmd"]) {
				case "shutdown":  // Shutdown
					$results = jsonmethodcall("System.Shutdown");
					$displayMenu = ($_GET["style"] == "m");
					break;
				case "vidscan":  // Video Library ScanForContent
					$results = jsonmethodcall("VideoLibrary.ScanForContent");
					$displayMenu = ($_GET["style"] == "m");
					break;
				default:
					echo "Invalid Command";
			}
		} elseif(!empty($_GET["json"])) {
			$request = stripslashes(urldecode($_GET["json"]));
			$results = jsoncall($request);
			$displayMenu = ($_GET["style"] == "m");
		} else {
			$displayMenu = $forcemenu;
		}
	} else {
		$displayMenu = ((!empty($_GET["style"]) && ($_GET["style"] == "m")) || $forcemenu);
	}
	if($displayMenu) {
		widgetMenu($baseurl);
	}
}

if(!empty($_GET["style"]) && ($_GET["style"] == "w")) {
	require_once "../config.php";
	require_once "../functions.php";

	widgetControl();
}

?>
