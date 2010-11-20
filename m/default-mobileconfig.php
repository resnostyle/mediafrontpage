<?php
$mobilelayout = array(
					"<span class=\"library\">Library</span>" => "wXBMCLibrary",
					//"Remote" => "wRemote",
					"<span class=\"playing\">Now Playing</span>" => "wNowPlaying",
					"<span class=\"upcoming\">Coming Soon</span>" => "wComingEpisodes",
					"<span class=\"control\">Control</span>" => "wControl",
					"<span class=\"downloads\">SABnzbd</span>" => "wSabnzbd",
					"<span class=\"drives\">Hard Drives</span>" => "wHardDrives"
				);
$mobilefunction["wXBMCLibrary"] = "executeVideo(\"m\", \$action, \$breadcrumb, \$params);";
$mobilefunction["wNowPlaying"] = "displayNowPlaying(true);";
$mobilefunction["wComingEpisodes"] = "displayComingSoon();";
$mobilefunction["wControl"] = "widgetControl(\"index.php\", true);";
$mobilefunction["wSabnzbd"]	= "sabStatus();";
$mobilefunction["wHardDrives"] = "widgetHardDrives();";
?>