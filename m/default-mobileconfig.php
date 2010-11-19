<?php
$mobilelayout = array(
					"Library" => "wXBMCLibrary",
					//"Remote" => "wRemote",
					"Now Playing" => "wNowPlaying",
					"Coming Soon" => "wComingEpisodes",
					"Control" => "wControl",
					"SABnzbd" => "wSabnzbd",
					"Hard Drives" => "wHardDrives"
				);
$mobilefunction["wXBMCLibrary"] = "executeVideo(\"m\", \$action, \$breadcrumb, \$params);";
$mobilefunction["wNowPlaying"] = "displayNowPlaying(true);";
$mobilefunction["wComingEpisodes"] = "displayComingSoon();";
$mobilefunction["wControl"] = "widgetControl(\"index.php\", true);";
$mobilefunction["wSabnzbd"]	= "sabStatus();";
$mobilefunction["wHardDrives"] = "widgetHardDrives();";
?>