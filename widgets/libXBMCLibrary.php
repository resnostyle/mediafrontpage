<?php
//require_once "../config.php";
//require_once "../functions.php";

$videodetailfields = '"genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "showtitle", "firstaired", "duration", "season", "episode", "runtime", "year", "playcount", "rating", "writer", "studio", "mpaa", "premiered", "album"';

function executeVideo($style = "w", $action, $breadcrumb, $params = array()) {
	global $COMM_ERROR;
	global $videodetailfields;

	$breadcrumbs = explode("|", $breadcrumb);
	$previousaction = end($breadcrumbs);
	
	switch ($action) {
		case "l":  // Library
			displayLibraryMenu($style, $params);
			break;
		case "lp": // Photo Library
			displayLibraryPhotoMenu($style, $params);
			break;
		case "lv": // Video Library
			displayLibraryVideoMenu($style, $params);
			break;
		case "lm": // Music Library
			displayLibraryMusicMenu($style, $params);
			break;
		case "p":  // Play
			if (($previousaction == "re") || ($previousaction == "e")) {
				if ($previousaction == "re") {
					$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
				} else {
					$showid = $params['showid'];
					$season = $params['season'];
					$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetEpisodes", "params" : { "tvshowid" : '.$showid.', "season" : '.$season.', "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
				}
				$results = jsoncall($request);
				$videos = $results['result']['episodes'];
				$typeId = "episodeid";
			} elseif (($previousaction == "rm") || ($previousaction == "m")) {
				$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}';
				$results = jsoncall($request);
				$videos = $results['result']['movies'];
				$typeId = "movieid";
			}
			if (!empty($videos)) {
				$videoId = $params['videoid'];
				playVideoFromList($videos, $typeId, $videoId); 
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			//break;  // Don't break and flow into display.
		case "d":  // Display
			switch ($previousaction) {
				case "e": //Episodes
					$showid = $params['showid'];
					$season = $params['season'];
					$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetEpisodes", "params" : { "tvshowid" : '.$showid.', "season" : '.$season.', "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
					$results = jsoncall($request);
					$videos = $results['result']['episodes'];
					$params['typeid'] = "episodeid";
					if (!empty($videos)) {
						displayVideoFromList($videos, $style, $action, $breadcrumb, $params);
					} else {
						echo $COMM_ERROR;
						echo "<pre>$request</pre>";
					}
					break;
				case "re": // Recent Episodes
					$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
					$results = jsoncall($request);
					$videos = $results['result']['episodes'];
					$params['typeid'] = "episodeid";
					if (!empty($videos)) {
						displayVideoFromList($videos, $style, $action, $breadcrumb, $params);
					} else {
						echo $COMM_ERROR;
						echo "<pre>$request</pre>";
					}
					break;
				case "m":  // Movies
				case "rm": // Recent Movies
					$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}';
					$results = jsoncall($request);
					$videos = $results['result']['movies'];
					$params['typeid'] = "movieid";
					if (!empty($videos)) {
						displayVideoFromList($videos, $style, $action, $breadcrumb, $params);
					} else {
						echo $COMM_ERROR;
						echo "<pre>$request</pre>";
					}
					break;
			}
			break;
		case "t":  // TV Shows
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetTVShows", "id" : 1 }';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$videos = $results['result']['tvshows'];
				displayVideoListTVShows($videos, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "s":  // Seasons
			$showid = $params['showid'];
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetSeasons", "params" : { "tvshowid" : '.$showid.', "fields": [ "genre", "title", "showtitle", "duration", "season", "episode", "year", "playcount", "rating", "studio", "mpaa" ] }, "id" : 1 }';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$videos = $results['result']['seasons'];
				displayVideoListSeasons($videos, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "e":  // Episodes
			$showid = $params['showid'];
			$season = $params['season'];
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetEpisodes", "params" : { "tvshowid" : '.$showid.', "season" : '.$season.', "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$videos = $results['result']['episodes'];
				displayVideoListEpisodes($videos, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "re": // Recent Episodes
			if(!empty($params['count'])) {
				$count = $params['count'];
			} else {
				$count = 15;
			}
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : '.$count.' , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$videos = $results['result']['episodes'];
				displayVideoListEpisodes($videos, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "m":  // Movies
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$videos = $results['result']['movies'];
				displayVideoListMovie($videos, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "rm": // Recent Movies
			if(!empty($params['count'])) {
				$count = $params['count'];
			} else {
				$count = 15;
			}
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedMovies", "params": { "start" : 0 , "end" : '.$count.' , "fields" : [ '.$videodetailfields.' ] }, "id" : 1 }';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$videos = $results['result']['movies'];
				displayVideoListMovie($videos, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "mv": // Music Videos
			echo "<ul class=\"widget-list\"><li>Not Supported Yet</li></ul>";
			$anchor = buildBackAnchor($style, "l|lv", $params);
			echo "<div class=\"widget-control\">".$anchor."</div>\n";
			break;
		case "ar":  // Artists
			echo "<ul class=\"widget-list\"><li>Under Construction</li></ul>";
			$request = '{"jsonrpc": "2.0", "method": "AudioLibrary.GetArtists", "id": 1}';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$artists = $results['result']['artists'];
				displayMusicListArtist($artists, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "al": // Albums
			echo "<ul class=\"widget-list\"><li>Under Construction</li></ul>";
			$artistid = $params['artistid'];
			if (!empty($artistid)) {
				$request = '{"jsonrpc": "2.0", "method": "AudioLibrary.GetAlbums", "params": { "artistid": '.$artistid.' , "fields": [ "artist", "year" ] },"id": 1}';
			} else {
				$request = '{"jsonrpc": "2.0", "method": "AudioLibrary.GetAlbums", "params": { "fields": [ "artist", "year" ] },"id": 1}';
			}
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$albums = $results['result']['albums'];
				displayMusicListAlbum($albums, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "so": // Songs
			echo "<ul class=\"widget-list\"><li>Under Construction</li></ul>";
			
			$request = '{"jsonrpc": "2.0", "method": "AudioLibrary.GetSongs", "params": { "fields": [ "artist" ] }, "id": 1}';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$songs = $results['result']['songs'];
				displayMusicListSong($songs, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
		case "ms": // Music Source
			echo "<ul class=\"widget-list\"><li>Under Construction</li></ul>";
			$request = '{"jsonrpc": "2.0", "method": "Files.GetSources", "params" : { "media" : "music" }, "id": 1}';
			$results = jsoncall($request);
			if (!empty($results['result'])) {
				$sources = $results['result']['shares'];
				displayMusicListSource($sources, $style, $action, $breadcrumb, $params);
			} else {
				echo $COMM_ERROR;
				echo "<pre>$request</pre>";
			}
			break;
	}
}
function getParameters($request) {
	$params = array();
	if(!empty($request['c'])) {
		$params['count'] = $request['c'];
	}
	if(!empty($request['showid'])) {
		$params['showid'] = $request['showid'];
	}
	if(!empty($request['season'])) {
		$params['season'] = $request['season'];
	}
	if(!empty($request['videoid'])) {
		$params['videoid'] = $request['videoid'];
	}
	if(!empty($request['artistid'])) {
		$params['artistid'] = $request['artistid'];
	}
	if(!empty($request['albumid'])) {
		$params['albumid'] = $request['albumid'];
	}

	return $params;
}

function renderMenu($data) {
	echo "<ul class=\"widget-list\">\n";
	$alt = false;
	foreach ($data as $id => $info) {
		echo "\t<li".(($alt) ? " class=\"alt\"" : "")."><a href=\"".$info['href']."\" id='".$id."' class='menu'".$info['onclick'].">".$info['label']."</a></li>\n";
		$alt = !$alt;
	}
	echo "</ul>\n";
}

function displayLibraryMenu($style, $params) {
	global $COMM_ERROR;

	$arrResult = jsoncall('{"jsonrpc": "2.0", "method": "JSONRPC.Version", "id": 1}');
	if(!is_array($arrResult)) {
		echo $COMM_ERROR;
	} else if ($style == "w") {
			$data = array (
						  "menu-lp" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'lp', 'l', '');\"", "label" => "Photos")
						, "menu-lv" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'lv', 'l', '');\"", "label" => "Videos")
						, "menu-lm" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'lm', 'l', '');\"", "label" => "Music")
					  );
	} else {
			$data = array (
						  "menu-lp" => array( "href" => "?style=".$style."&a=lp&bc=l", "onclick" => "", "label" => "Photos")
						, "menu-lv" => array( "href" => "?style=".$style."&a=lv&bc=l", "onclick" => "", "label" => "Videos")
						, "menu-lm" => array( "href" => "?style=".$style."&a=lm&bc=l", "onclick" => "", "label" => "Music")
					  );
	}

	renderMenu($data);
}
function displayLibraryPhotoMenu($style, $params) {
	echo "<ul class=\"widget-list\"><li>Not Supported Yet</li></ul>";

	$anchor = buildBackAnchor($style, "l", $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}
function displayLibraryVideoMenu($style, $params) {
	if ($style == "w") {
		$data = array (
						  "menu-t" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 't', 'l|lv', '');\"", "label" => "TV Shows")
						, "menu-m" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'm', 'l|lv', '');\"", "label" => "Movies")
						, "menu-re" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 're', 'l|lv', '');\"", "label" => "Recent Episodes")
						, "menu-rm" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'rm', 'l|lv', '');\"", "label" => "Recent Movies")
						, "menu-mv" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'mv', 'l|lv', '');\"", "label" => "Music Videos")
					  );
	} else {
		$data = array (
						  "menu-t" => array( "href" => "?style=".$style."&a=t&bc=l|lv", "onclick" => "", "label" => "TV Shows")
						, "menu-m" => array( "href" => "?style=".$style."&a=m&bc=l|lv", "onclick" => "", "label" => "Movies")
						, "menu-re" => array( "href" => "?style=".$style."&a=re&bc=l|lv", "onclick" => "", "label" => "Recent Episodes")
						, "menu-rm" => array( "href" => "?style=".$style."&a=rm&bc=l|lv", "onclick" => "", "label" => "Recent Movies")
						, "menu-mv" => array( "href" => "?style=".$style."&a=mv&bc=l|lv", "onclick" => "", "label" => "Music")
					  );
	}

	renderMenu($data);

	$anchor = buildBackAnchor($style, "l", $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}
function displayLibraryMusicMenu($style, $params) {
	echo "<ul class=\"widget-list\"><li>Under Construction</li></ul>";
	if ($style == "w") {
		$data = array (
						  "menu-ar" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'ar', 'l|lm', '');\"", "label" => "Artists")
						, "menu-al" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'al', 'l|lm', '');\"", "label" => "Albums")
						, "menu-so" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'so', 'l|lm', '');\"", "label" => "Songs")
						, "menu-ms" => array( "href" => "#", "onclick" => " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', 'ms', 'l|lm', '');\"", "label" => "Files")
					);
	} else {
		$data = array (
						  "menu-ar" => array( "href" => "?style=".$style."&a=ar&bc=l|lm", "onclick" => "", "label" => "Artists")
						, "menu-al" => array( "href" => "?style=".$style."&a=al&bc=l|lm", "onclick" => "", "label" => "Albums")
						, "menu-so" => array( "href" => "?style=".$style."&a=so&bc=l|lm", "onclick" => "", "label" => "Songs")
						, "menu-ms" => array( "href" => "?style=".$style."&a=ms&bc=l|lm", "onclick" => "", "label" => "Files")
					  );
	}

	renderMenu($data);

	$anchor = buildBackAnchor($style, "l", $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}
function getTVShowId($showtitle) {
	$return = -1;

	$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetTVShows", "id" : 1 }';
	$results = jsoncall($request);
	$videos = $results['result']['tvshows'];

	foreach ($videoList as $videoInfo) {
		if(!empty($videoInfo['showtitle']) && ($videoInfo['showtitle'] == $showtitle)) {
			if (!empty($videoInfo['tvshowid'])) {
				$return = $videoInfo['tvshowid'];
			}
			break;
		}
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

function displayVideoFromList($videoList, $style, $action, $breadcrumb, $params) {
	$idType = $params['typeid'];
	foreach ($videoList as $videoInfo) {
		if(!empty($videoInfo[$idType]) && ($videoInfo[$idType] == $params['videoid'])) {
			switch($idType) {
				case "episodeid": // Episodes
					displayVideoEpisode($videoInfo, $style, $action, $breadcrumb, $params);
					break;
				case "movieid":   // Movies
					displayVideoMovie($videoInfo, $style, $action, $breadcrumb, $params);
					break;
			}
			break;
		}
	}
}

function displayVideoEpisode($videoInfo, $style, $action, $breadcrumb, $params) {
	global $xbmcimgpath;
	
	echo "<div id='recentTV'>\n";
	echo "\t<div class='library-title'><h2>".$videoInfo['showtitle']."</h2></div>\n";
	echo "\t<div class='library-info'>\n";
	echo "\t<span class='library-img'>\n";

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
	echo "\t<span class='library-desc'>\n";
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
	
	$query = "&showid=".$params['showid']."&season=".$params['season']."&videoid=".$videoInfo["episodeid"];
	$playanchor = buildAnchor("Play", $style, "", "", "p", $breadcrumb, $params, $query);
	$backanchor = buildBackAnchor($style, $breadcrumb, $params, "&showid=".$params["showid"]."&season=".$videoInfo["season"]);
	echo "\t<div class=\"widget-control\">".$playanchor." | ".$backanchor."</div>\n";
	echo "</div>\n";
}

function displayVideoMovie($videoInfo, $style, $action, $breadcrumb, $params) {
	global $xbmcimgpath;
	
	echo "<div id='movies'>\n";
	echo "\t<div class='library-title'><h2>".$videoInfo['label']." &nbsp;(".$videoInfo['year'].")</h2></div>\n";
	echo "\t<div class='library-info'>\n";
	echo "\t<span class='library-img'>\n";

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
	echo "\t<span class='library-desc'>\n";
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
	$playanchor = buildAnchor("Play", $style, "", "", "p", $breadcrumb, $params, "&videoid=".$videoInfo["movieid"]);
	$backanchor = buildBackAnchor($style, $breadcrumb, $params, "");
	echo "\t<div class=\"widget-control\">".$playanchor." | ".$backanchor."</div>\n";
	echo "</div>\n";
}

function displayVideoListTVShows($videos, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	if (!empty($videos)) {
		$alt = false;
		foreach ($videos as $videoInfo) {
			$label = $videoInfo['label'];
			$id = "tvshow-".$videoInfo["tvshowid"];
			$class = "recent-tv";
			$query = "&showid=".$videoInfo["tvshowid"];
			$anchor = buildAnchor($label, $style, $id, $class, "s", $newbreadcrumb, $params, $query);
			echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
			$alt = !$alt;
		}
	} else {
		echo "<li>[empty]</li>\n";
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayVideoListSeasons($videos, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	if (!empty($videos)) {
		$alt = false;
		foreach ($videos as $videoInfo) {
			$label = $videoInfo['showtitle']." - ".$videoInfo['label'];
			$id = "season-".$videoInfo["season"];
			$class = "recent-tv";
			$query = "&showid=".$params["showid"]."&season=".$videoInfo["season"];
			$anchor = buildAnchor($label, $style, $id, $class, "e", $newbreadcrumb, $params, $query);
			echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
			$alt = !$alt;
		}
	} else {
		echo "<li>[empty]</li>\n";
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params, "&showid=".$params["showid"]);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayVideoListEpisodes($videos, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	if (!empty($videos)) {
		$alt = false;
		foreach ($videos as $videoInfo) {
			if(!empty($videoInfo['label'])) {
				$title = " - ".$videoInfo['label'];
			} else {
				$title = "";
				if(!empty($videoInfo['season'])) {
					$title .= " Season: ".$videoInfo['season'];
				}
				if(!empty($videoInfo['episode'])) {
					$title .= " Episode: ".$videoInfo['episode'];
				}
				if($title != "") {
					$title = " - ".$title;
				}
			}
			$label = $videoInfo['showtitle']." - ".$videoInfo['season']."x".str_pad($videoInfo['episode'], 2, '0', STR_PAD_LEFT).$title;
			$id = "episode-".$videoInfo["episodeid"];
			$class = "recent-tv";
			$query = "&showid=".$params["showid"]."&season=".$videoInfo["season"]."&videoid=".$videoInfo["episodeid"];
			$anchor = buildAnchor($label, $style, $id, $class, "d", $newbreadcrumb, $params, $query);
			echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
			$alt = !$alt;
		}
	} else {
		echo "<li>[empty]</li>\n";
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params, "&showid=".$params["showid"]."&season=".$videoInfo["season"]);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayVideoListMovie($videos, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	$alt = false;
	foreach ($videos as $videoInfo) {
		$label = $videoInfo['label']." &nbsp;(".$videoInfo['year'].")";
		$id = "movie-".$videoInfo["movieid"];
		$class = "recent-movie";
		$query = "&videoid=".$videoInfo["movieid"];
		$anchor = buildAnchor($label, $style, $id, $class, "d", $newbreadcrumb, $params, $query);
		echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
		$alt = !$alt;
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayMusicListArtist($artists, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	$alt = false;
	foreach ($artists as $artistInfo) {
		$label = $artistInfo['label'];
		$id = "music-".$artistInfo["artistid"];
		$class = "music-artist";
		$query = "&artistid=".$artistInfo["artistid"];
		$anchor = buildAnchor($label, $style, $id, $class, "al", $newbreadcrumb, $params, $query);
		echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
		$alt = !$alt;
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayMusicListAlbum($albums, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	$alt = false;
	foreach ($albums as $albumInfo) {
		$label = $albumInfo['artist']." - ".$albumInfo['label']." &nbsp;(".$albumInfo['year'].")";
		$id = "music-".$albumInfo["albumid"];
		$class = "music-album";
		$query = "&albumid=".$albumInfo["albumid"];
		$anchor = buildAnchor($label, $style, $id, $class, "d", $newbreadcrumb, $params, $query);
		echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
		$alt = !$alt;
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayMusicListSong($songs, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	$alt = false;
	foreach ($songs as $songInfo) {
		$label = $songInfo['artist']." - ".$songInfo['label'];
		$id = "music-".$songInfo["songid"];
		$class = "music-song";
		$query = "&songid=".$songInfo["songid"];
		$anchor = buildAnchor($label, $style, $id, $class, "d", $newbreadcrumb, $params, $query);
		echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
		$alt = !$alt;
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function displayMusicListSource($sources, $style, $action, $breadcrumb, $params) {
	$newbreadcrumb = getNewBreadcrumb($action, $breadcrumb);

	echo "<ul class=\"widget-list\">";
	$alt = false;
	foreach ($sources as $source) {
		$label = $source['label'];
		$id = "music-".$source["sourceid"];
		$class = "music-source";
		$query = "&sourceid=".$source["sourceid"];
		$anchor = buildAnchor($label, $style, $id, $class, "d", $newbreadcrumb, $params, $query);
		echo "<li".(($alt) ? " class=\"alt\"" : "").">".$anchor."</li>\n";
		$alt = !$alt;
	}
	echo "</ul>";

	$anchor = buildBackAnchor($style, $breadcrumb, $params);
	echo "<div class=\"widget-control\">".$anchor."</div>\n";
}

function getNewBreadcrumb($action, $breadcrumb) {
	if(strlen($breadcrumb) > 0) {
		$newbreadcrumb = $breadcrumb."|".$action;
	} else {
		$newbreadcrumb = $action;
	}

	return $newbreadcrumb;
}

function buildAnchor($label, $style, $id, $class, $action, $breadcrumb, $params, $query = "") {
	if ($style == "w") {
		$onclick =  " onclick=\"".$params['onclickcmd']."('".$params['wrapper']."', '".$params['harness']."', '".$action."', '".$breadcrumb."', '".$query."');\"";
		$href = "#";
	} else {
		$href = $params['href']."?style=".$style."&a=".$action."&bc=".$breadcrumb.$query;
	}
	if (strlen($id) > 0) {
		$id = " id=\"".$id."\"";
	}
	if (strlen($class) > 0) {
		$class = " class=\"".$class."\"";
	}
	return "<a href=\"".$href."\"".$id.$class.$onclick.">".$label."</a>";
}

function buildBackAnchor($style, $breadcrumb, $params, $query = "") {
	if(strlen($breadcrumb) > 0) {
		$breadcrumbs = explode("|", $breadcrumb);	
		$previousaction = array_pop($breadcrumbs);
		$previousbreadcrumb = implode("|", $breadcrumbs);
		return buildAnchor("Back", $style, "", "", $previousaction, $previousbreadcrumb, $params, $query);
	}
}
?>
