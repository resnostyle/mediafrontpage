<?php
$mobilelayout = array(
					"Library" => "wXBMCLibrary",
					//"Remote" => "wRemote",
					"Coming Soon" => "wComingEpisodes",
					"Control" => "wControl",
					//"Now Playing" => "wNowPlaying",
					"SABnzbd" => "wSabnzbd",
					"Hard Drives" => "wHardDrives"
				);
$mobilefunction["wXBMCLibrary"] = "executeVideo(\"m\", \$action, \$breadcrumb, \$params);";
$mobilefunction["wComingEpisodes"] = "displayComingSoon();";
$mobilefunction["wControl"] = "widgetControl(\"index.php\", true);";
$mobilefunction["wSabnzbd"]	= "sabStatus();";
$mobilefunction["wHardDrives"] = "widgetHardDrives();";
?>