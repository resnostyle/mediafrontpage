<?php
//require_once "../config.php";
//require_once "../functions.php";

$videodetailfields = '"genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "showtitle", "firstaired", "duration", "season", "episode", "runtime", "year", "playcount", "rating", "writer", "studio", "mpaa", "premiered", "album"';

function executeVideo($videos, $action, $typeId, $videoId, $count = 0) {
	global $COMM_ERROR;
	
	if (!empty($videos)) {
		switch ($action) {
			case "d":  // Display
				displayVideoFromList($videos, $typeId, $videoId, $count); 
				break;
			case "p":  // Play
				playVideoFromList($videos, $typeId, $videoId); 
				break;
			case "m":  // Movies
				displayVideoListMovie($videos);
				break;
			case "t":  // TV Shows
				displayVideoListTVShows($videos);
				break;
			case "s":  // Seasons
				displayVideoListSeasons($videos);
				break;
			case "e":  // Episodes
				displayVideoListEpisodes($videos);
				break;
		}
	} else {
		echo $COMM_ERROR;
		echo "<pre>$request</pre>";
	}
}

function playVideoFromList($videoList, $idType = "episodeid", $videoId = -1) {
	foreach ($videoList as $videoInfo) {
		if(!empty($videoInfo[$idType]) && ($videoInfo[$idType] == $videoId) && !empty($videoInfo['file'])) {
			$videoLocation = $videoInfo['file'];
			$request = '{"jsonrpc" : "2.0", "method": "XBMC.Play", "params" : { "file" : "' . $videoLocation . '"}, "id": 1}';
			jsoncall($request);
			break;
		}
	}
}
function displayVideoFromList($videoList, $idType = "episodeid", $videoId = -1, $count = 0) {
	foreach ($videoList as $videoInfo) {
		if(!empty($videoInfo[$idType]) && ($videoInfo[$idType] == $videoId)) {
			switch($idType) {
				case "episodeid": // Episodes
					displayVideoEpisode($videoInfo, $count);
					break;
				case "movieid":   // Movies
					displayVideoMovie($videoInfo, $count);
					break;
			}
			break;
		}
	}
}

function displayVideoEpisode($videoInfo, $count = 0) {
	global $xbmcimgpath;
	
	echo "<div id='recentTV'>\n";
	echo "\t<div class='tvtitle'><h1>".$videoInfo['showtitle']."</h1></div>\n";
	echo "\t<div class='tvinfo'>\n";
	echo "\t<span class='tvimg'>\n";

	if(!empty($videoInfo['thumbnail'])) {
		echo "\t\t<a href=\"".$xbmcimgpath.$videoInfo['thumbnail']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
		echo "\t\t\t<img src='".$xbmcimgpath.$videoInfo['thumbnail']."' title='Click to enlarge'/>\n";
		echo "\t\t<a>\n";
	} elseif(!empty($videoInfo['fanart'])) {
		echo "\t\t<a href=\"".$xbmcimgpath.$videoInfo['fanart']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
		echo "\t\t\t<img src='".$xbmcimgpath.$videoInfo['fanart']."' title='Click to enlarge'/>\n";
		echo "\t\t<a>";
	}
	echo "<div class=\"highslide-caption\">"; 
	echo $videoInfo['showtitle']." - ".$videoInfo['season']."x".str_pad($videoInfo['episode'], 2, '0', STR_PAD_LEFT)." - ".$videoInfo['label']."<br />\n";
	echo "\t\t".$videoInfo['plot']."\n";
	echo "\t\t</div>\n"; 

	echo "\t</span>\n";
	echo "\t<span class='tvdesc'>\n";
	echo "\t\t<p>";
	echo "\t\t\t<strong>Season: ".$videoInfo['season']." Episode: ".$videoInfo['episode']."<br />".$videoInfo['label']."</strong>";
	//echo "\t\t\t<strong>".$videoInfo['season']."x".str_pad($videoInfo['episode'], 2, '0', STR_PAD_LEFT)."<br />".$videoInfo['label']."</strong>";
	echo "\t\t</p>\n";
	echo "\t\t<p class=\"plot\">".$videoInfo['plot']."</p>\n";
	if(!empty($videoInfo['firstaired'])) {
		echo "\t\t<p>Aired: ".$videoInfo['firstaired']."</p>\n";
	}
	
	if(!empty($videoInfo['duration'])) {
		echo "\t\t<p>Runtime: ".(int)($videoInfo['duration']/60)." min.</p>\n";
	} elseif(!empty($videoInfo['runtime'])) {
		echo "\t\t<p>Runtime: ".$videoInfo['runtime']." min.</p>\n";
	}
	if(!empty($videoInfo['rating'])) {
		echo "\t\t<p>Rating: ".number_format($videoInfo['rating'], 1)."</p>\n";
	}
	echo "\t</span>\n";
	echo "\t</div>\n";
	echo "\t<div class='tvoptions'><a href=\"#\" onclick=\"cmdRecentTV('p', ".$videoInfo["episodeid"].");\">Play</a> | <a href=\"#\" onclick=\"cmdRecentTV('e', ".$count.");\">Back</a></div>\n";
	echo "</div>\n";
}

function displayVideoMovie($videoInfo, $count = 0) {
	global $xbmcimgpath;
	
	echo "<div id='movies'>\n";
	echo "\t<div class='movietitle'><h1>".$videoInfo['label']." &nbsp;(".$videoInfo['year'].")</h1></div>\n";
	echo "\t<div class='movieinfo'>\n";
	echo "\t<span class='movieimg'>\n";

	if(!empty($videoInfo['thumbnail'])) {
		echo "\t\t<a href=\"".$xbmcimgpath.$videoInfo['thumbnail']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
		echo "\t\t\t<img src='".$xbmcimgpath.$videoInfo['thumbnail']."' title='Click to enlarge'/>\n";
		echo "\t\t<a>\n";
	} elseif(!empty($videoInfo['fanart'])) {
		echo "\t\t<a href=\"".$xbmcimgpath.$videoInfo['thumbnail']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
		echo "\t\t\t<img src='".$xbmcimgpath.$videoInfo['fanart']."' title='Click to enlarge'/>\n";
		echo "\t\t<a>\n";
	}
	echo "\t\t<div class=\"highslide-caption\">\n"; 
	echo "\t\t".$videoInfo['label']." &nbsp;(".$videoInfo['year'].")<br />\n";
	echo "\t\t".$videoInfo['plot']."\n";
	echo "\t\t</div>\n"; 

	echo "\t</span>\n";
	echo "\t<span class='moviedesc'>\n";
	if($videoInfo['originaltitle'] != $videoInfo['title']) {
		echo "\t\t<p>".$videoInfo['originaltitle']."</p>\n";
	}
	echo "\t\t<p>".$videoInfo['genre']."</p>\n";
	echo "\t\t<p class=\"plot\">".$videoInfo['plot']."</p>\n";
	if(!empty($videoInfo['premiered'])) {
		echo "\t\t<p>Premiered: ".$videoInfo['premiered']."</p>\n";
	}
	if(!empty($videoInfo['director'])) {
		echo "\t\t<p>Director: ".$videoInfo['director']."</p>\n";
	}
	if(!empty($videoInfo['runtime'])) {
		echo "\t\t<p>Runtime: ".$videoInfo['runtime']." min.</p>\n";
	}
	if(!empty($videoInfo['rating'])) {
		echo "\t\t<p>Rating: ".number_format($videoInfo['rating'], 1)."</p>\n";
	}
	echo "\t</span>\n";
	echo "\t</div>\n";
	echo "\t<div class='movieoptions'><a href=\"#\" onclick=\"cmdRecentMovie('p', ".$videoInfo["movieid"].");\">Play</a> | <a href=\"#\" onclick=\"cmdRecentMovie('m', ".$count.");\">Back</a></div>\n";
	echo "</div>\n";
}

function displayVideoListTVShows($videos) {
	echo "<ul>";
	foreach ($videos as $videoInfo) {
		echo "<li><a href=\"#\" id=\"tvshow-".$videoInfo["tvshowid"]."\" class='recent-tv' onclick=\"cmdRecentTV('s', ".$videoInfo["tvshowid"].");\">".$videoInfo['label']."</a></li>\n";
	}
	echo "</ul>";
}
function displayVideoListSeasons($videos) {
	echo "<ul>";
	foreach ($videos as $videoInfo) {
		$display = $videoInfo['showtitle']." - ".$videoInfo['label'];
		echo "<li><a href=\"#\" id=\"season-".$videoInfo["season"]."\" class='recent-tv' onclick=\"cmdRecentTV('e', ".$videoInfo["season"].");\">".$display."</a></li>\n";
	}
	echo "</ul>";
}
function displayVideoListEpisodes($videos) {
	echo "<ul>";
	foreach ($videos as $videoInfo) {
		$display = $videoInfo['showtitle']." - ".$videoInfo['season']."x".str_pad($videoInfo['episode'], 2, '0', STR_PAD_LEFT)." - ".$videoInfo['label'];
		echo "<li><a href=\"#\" id=\"episode-".$videoInfo["episodeid"]."\" class='recent-tv' onclick=\"cmdRecentTV('d', ".$videoInfo["episodeid"].");\">".$display."</a></li>\n";
	}
	echo "</ul>";
}

function displayVideoListMovie($videos) {
	echo "<ul>";
	foreach ($videos as $videoInfo) {
		$display = $videoInfo['label']." &nbsp;(".$videoInfo['year'].")";
		echo "<li><a href=\"#\" id=\"movie-".$videoInfo["movieid"]."\" class='recent-movie' onclick=\"cmdRecentMovie('d', ".$videoInfo["movieid"].");\">".$display."</a></li>\n";
	}
	echo "</ul>";
}

?>