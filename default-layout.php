<?php
require_once "widgets.php";

// Note: Array indexes are the div tag id attributes.  Important for CSS creation.
$arrLayout = array(
		"left-sidebar"  => array(
								  "quick-links" => $wdgtControl
								, "hdstats" => $wdgtHardDrive
								, "nowplayingblock" => $wdgtNowPlaying
								, "sabnzbdblock" => $wdgtSabnzbd
								)
	,	"middle"        => array( "middleblock" => $wdgtMiddleBlock)
	,	"right-sidebar" => array(
								  "recent-tv" => $wdgtRecentTV
								, "recentmovies" => $wdgtRecentMovies
								)
	);
?>
