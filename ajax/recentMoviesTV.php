<?php
require_once "../config.php";
require_once "../functions.php";

//Get action type valid types are m - Movies; t - TV
$type = $_GET["t"];
if(empty($type)) {
	$type = "m"; //Default to m - Movies
}

//Get action type valid types are l - List; d - Display; p - Play
$action = $_GET["a"];

//Get count for list type
$count = $_GET["c"];
if(empty($count)) {
	$count = 15; //Default 15
}

$videoId = $_GET['id'];

$videodetailfields = '"genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "showtitle", "firstaired", "duration", "season", "episode", "runtime", "year", "playcount", "rating", "writer", "studio", "mpaa", "premiered", "album"';

if($type=="t") {

	if(($action=="d") || ($action=="p")) {
		$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
	} else {
		$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : '.$count.' , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
	}
	$results = jsoncall($request);
	$videos = $results['result']['episodes'];

	if (!empty($videos)) {
		switch ($action) {
			case "d":  // Display
				foreach ($videos as $episodeInfo) {
					if($videoId == $episodeInfo['episodeid']) {
						echo "<div id='recentTV'>\n";
						echo "\t<div class='tvtitle'><h1>".$episodeInfo['showtitle']."</h1></div>\n";
						echo "\t<div class='tvinfo'>\n";
						echo "\t<span class='tvimg'>\n";

						if(!empty($episodeInfo['thumbnail'])) {
							echo "\t\t<a href=\"".$xbmcimgpath.$episodeInfo['thumbnail']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
							echo "\t\t\t<img src='".$xbmcimgpath.$episodeInfo['thumbnail']."' title='Click to enlarge'/>\n";
							echo "\t\t<a>\n";
						} elseif(!empty($episodeInfo['fanart'])) {
							echo "\t\t<a href=\"".$xbmcimgpath.$episodeInfo['fanart']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
							echo "\t\t\t<img src='".$xbmcimgpath.$episodeInfo['fanart']."' title='Click to enlarge'/>\n";
							echo "\t\t<a>";
						}
						echo "<div class=\"highslide-caption\">"; 
						echo $episodeInfo['showtitle']." - ".$episodeInfo['season']."x".str_pad($episodeInfo['episode'], 2, '0', STR_PAD_LEFT)." - ".$episodeInfo['label']."<br />\n";
						echo "\t\t".$episodeInfo['plot']."\n";
 						echo "\t\t</div>\n"; 

						echo "\t</span>\n";
						echo "\t<span class='tvdesc'>\n";
						echo "\t\t<p>";
						echo "\t\t\t<strong>Season: ".$episodeInfo['season']." Episode: ".$episodeInfo['episode']."<br />".$episodeInfo['label']."</strong>";
						//echo "\t\t\t<strong>".$episodeInfo['season']."x".str_pad($episodeInfo['episode'], 2, '0', STR_PAD_LEFT)."<br />".$episodeInfo['label']."</strong>";
						echo "\t\t</p>\n";
						echo "\t\t<p class=\"plot\">".$episodeInfo['plot']."</p>\n";
						if(!empty($episodeInfo['firstaired'])) {
							echo "\t\t<p>Aired: ".$episodeInfo['firstaired']."</p>\n";
						}
						
						if(!empty($episodeInfo['duration'])) {
							echo "\t\t<p>Runtime: ".(int)($episodeInfo['duration']/60)." min.</p>\n";
						} elseif(!empty($episodeInfo['runtime'])) {
							echo "\t\t<p>Runtime: ".$episodeInfo['runtime']." min.</p>\n";
						}
						if(!empty($episodeInfo['rating'])) {
							echo "\t\t<p>Rating: ".number_format($episodeInfo['rating'], 1)."</p>\n";
						}
						echo "\t</span>\n";
						echo "\t</div>\n";
						echo "\t<div class='tvoptions'><a href=\"#\" onclick=\"cmdRecentTV('p', ".$episodeInfo["episodeid"].");\">Play</a> | <a href=\"#\" onclick=\"cmdRecentTV('l', ".$count.");\">Back</a></div>\n";
						echo "</div>\n";
					}
				}
				break;
			case "p":  // Play
				foreach ($videos as $episodeInfo) {
					if($videoId == $episodeInfo['episodeid']) {
						$videolocation = $episodeInfo['file'];
						$request = '{"jsonrpc" : "2.0", "method": "XBMC.Play", "params" : { "file" : "' . $videolocation . '"}, "id": 1}';
						jsoncall($request);
						break;
					}
				}
				break;
			default: //Default to l - List
				echo "<ul>";
				foreach ($videos as $episodeInfo) {
					$label = $episodeInfo['label'];
					$showtitle = $episodeInfo['showtitle'];
					$season = $episodeInfo['season'];
					$episode = $episodeInfo['episode'];

					$display = $showtitle." - ".$season."x".str_pad($episode, 2, '0', STR_PAD_LEFT)." - ".$label;

					echo "<li><a href=\"#\" id=\"episode-".$movieInfo["episodeid"]."\" class='recent-tv' onclick=\"cmdRecentTV('d', ".$episodeInfo["episodeid"].");\">$display</a></li>\n";
				}
				echo "</ul>";
		}
	} else {
		echo $COMM_ERROR;
		echo "<pre>$request</pre>";
	}
} else {
	if(($action=="d") || ($action=="p")) {
		$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}';
	} else {
		$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedMovies", "params": { "start" : 0 , "end" : '.$count.' , "fields" : [ '.$videodetailfields.' ] }, "id" : 1 }';
	}
	$results = jsoncall($request);
	$videos = $results['result']['movies'];

	if (!empty($videos)) {
		switch($action) {
			case "d":  // Display
				foreach ($videos as $movieInfo) {
					if($videoId == $movieInfo['movieid']) {
						echo "<div id='movies'>\n";
						echo "\t<div class='movietitle'><h1>".$movieInfo['label']." &nbsp;(".$movieInfo['year'].")</h1></div>\n";
						echo "\t<div class='movieinfo'>\n";
						echo "\t<span class='movieimg'>\n";

						if(!empty($movieInfo['thumbnail'])) {
							echo "\t\t<a href=\"".$xbmcimgpath.$movieInfo['thumbnail']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
							echo "\t\t\t<img src='".$xbmcimgpath.$movieInfo['thumbnail']."' title='Click to enlarge'/>\n";
							echo "\t\t<a>\n";
						} elseif(!empty($movieInfo['fanart'])) {
							echo "\t\t<a href=\"".$xbmcimgpath.$movieInfo['thumbnail']."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
							echo "\t\t\t<img src='".$xbmcimgpath.$movieInfo['fanart']."' title='Click to enlarge'/>\n";
							echo "\t\t<a>\n";
						}
						echo "\t\t<div class=\"highslide-caption\">\n"; 
						echo "\t\t".$movieInfo['label']." &nbsp;(".$movieInfo['year'].")<br />\n";
						echo "\t\t".$movieInfo['plot']."\n";
 						echo "\t\t</div>\n"; 

						echo "\t</span>\n";
						echo "\t<span class='moviedesc'>\n";
						if($movieInfo['originaltitle'] != $movieInfo['title']) {
							echo "\t\t<p>".$movieInfo['originaltitle']."</p>\n";
						}
						echo "\t\t<p>".$movieInfo['genre']."</p>\n";
						echo "\t\t<p class=\"plot\">".$movieInfo['plot']."</p>\n";
						if(!empty($movieInfo['premiered'])) {
							echo "\t\t<p>Premiered: ".$movieInfo['premiered']."</p>\n";
						}
						if(!empty($movieInfo['director'])) {
							echo "\t\t<p>Director: ".$movieInfo['director']."</p>\n";
						}
						if(!empty($movieInfo['runtime'])) {
							echo "\t\t<p>Runtime: ".$movieInfo['runtime']." min.</p>\n";
						}
						if(!empty($movieInfo['rating'])) {
							echo "\t\t<p>Rating: ".number_format($movieInfo['rating'], 1)."</p>\n";
						}
						echo "\t</span>\n";
						echo "\t</div>\n";
						echo "\t<div class='movieoptions'><a href=\"#\" onclick=\"cmdRecentMovie('p', ".$movieInfo["movieid"].");\">Play</a> | <a href=\"#\" onclick=\"cmdRecentMovie('l', ".$count.");\">Back</a></div>\n";
						echo "</div>\n";
						break;
					}
				}
				break;
			case "p":  // Play
				foreach ($videos as $movieInfo) {
					if($videoId == $movieInfo['movieid']) {
						$videolocation = $movieInfo['file'];
						$request = '{"jsonrpc" : "2.0", "method": "XBMC.Play", "params" : { "file" : "' . $videolocation . '"}, "id": 1}';
						jsoncall($request);
						break;
					}
				}
				break;
			default: //Default to l - List
				foreach ($videos as $movieInfo) {
					$movie = $movieInfo['label'];
					$display = $movie." &nbsp;(".$movieInfo['year'].")";
					echo "<li><a href=\"#\" id=\"movie-".$movieInfo["movieid"]."\" class='recent-movie' onclick=\"cmdRecentMovie('d', ".$movieInfo["movieid"].");\">$display</a></li>\n";
				}
		}
	} else {
		echo $COMM_ERROR;
		echo "<pre>$request</pre>";
	}
	
}
?>
