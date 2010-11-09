<?php
require_once "config.php";

$COMM_ERROR = "\n<p><strong>XBMC's JSON API did not respond.</strong></p>\n<p>Check your configuration (config.php) and that the JSON service variable is configured correctly and that the <a href=\"".$xbmcjsonservice."\">Service</a> is running.</p>\n";
$videodetailfields = '"genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "showtitle", "firstaired", "duration", "season", "episode", "runtime", "year", "playcount", "rating", "writer", "studio", "mpaa", "premiered", "album"';

$xbmcJsonMethods['JSONRPC.Version']                       = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Version", "id": 1}');
$xbmcJsonMethods['JSONRPC.Introspect']                    = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Introspect", "id": 1}');
$xbmcJsonMethods['JSONRPC.Permission']                    = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Permission", "id": 1}');

$xbmcJsonMethods['AudioLibrary.GetArtists']               = array('call' => '{"jsonrpc": "2.0", "method": "AudioLibrary.GetArtists", "params": { "sortmethod": "artist", "sortorder" : "ascending" , "fields": [ "artist", "year" ]}, "id": 1}');
$xbmcJsonMethods['AudioLibrary.GetAlbums']                = array('call' => '{"jsonrpc": "2.0", "method": "AudioLibrary.GetAlbums", "params": { %s "sortmethod": "artist", "sortorder" : "ascending", "fields": [ "artist", "year" ] },"id": 1}', 'args' => array("artistid" => ""), 'optional' => array('artistid' => '"artistid": 1,'));
$xbmcJsonMethods['AudioLibrary.GetSongs']                 = array('call' => '{"jsonrpc": "2.0", "method": "AudioLibrary.GetSongs", "params": { %s %s "fields": [ "artist", "year" ] },"id": 1}', 'args' => array("artistid" => "", "albumid" => ""));

$xbmcJsonMethods['AudioPlaylist.Add']                     = array('call' => '{"jsonrpc" : "2.0", "method": "AudioPlaylist.Add", "params": { "songid" : %d }, "id": 1}', 'args' => 0);
$xbmcJsonMethods['AudioPlaylist.GetItems']                = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlaylist.GetItems", "params": { "fields": ["title", "album", "artist", "duration"] }, "id": 1}');

$xbmcJsonMethods['Files.GetSources']                      = array('call' => '{"jsonrpc": "2.0", "method": "Files.GetSources", "params" : { "media" : "%s" }, "id": 1}', 'args' => 'music');
$xbmcJsonMethods['Files.Download']                        = array('call' => '{"jsonrpc": "2.0", "method": "Files.Download", "params": %s, "id": 1}', 'args' => '""');

$xbmcJsonMethods['Player.GetActivePlayers']               = array('call' => '{"jsonrpc": "2.0", "method": "Player.GetActivePlayers", "id": 1}');
$xbmcJsonMethods['Playlist.GetItems']                     = array('call' => '{"jsonrpc": "2.0", "method": "Playlist.GetItems", "id": 1}');

$xbmcJsonMethods['VideoLibrary.GetRecentlyAddedMovies']   = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedMovies", "args" : { "start" : 0 , "end" : %d , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }', 'args' => 50);
$xbmcJsonMethods['VideoLibrary.GetMovies']                = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}');
$xbmcJsonMethods['VideoLibrary.GetRecentlyAddedEpisodes'] = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "args" : { "start" : 0 , "end" : %d , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }', 'args' => 50);
$xbmcJsonMethods['VideoLibrary.GetTVShows']               = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetTVShows", "id" : 1 }');
$xbmcJsonMethods['VideoLibrary.GetEpisodes']              = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetEpisodes", "params" : { "tvshowid" : %d, "season" : %d, "fields": [ '.$videodetailfields.' ] }, "id" : 1 }', 'args' => array('tvshowid' => 1, 'season' => 1));
$xbmcJsonMethods['VideoLibrary.GetSeasons']               = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetSeasons", "params" : { "tvshowid" : %d, "fields": [ "genre", "title", "showtitle", "duration", "season", "episode", "year", "playcount", "rating", "studio", "mpaa" ] }, "id" : 1 }', 'args' => array('tvshowid' => 1));
$xbmcJsonMethods['VideoPlayer.GetPercentage']             = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.GetPercentage", "id": 1}');
$xbmcJsonMethods['VideoPlayer.GetTime']                   = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.GetTime", "id": 1}');
$xbmcJsonMethods['VideoPlayer.PlayPause']                 = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.PlayPause", "id": 1}');
$xbmcJsonMethods['VideoPlaylist.GetItems']                = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlaylist.GetItems", "params": { "fields": ["title", "season", "episode", "plot", "duration", "showtitle"] }, "id": 1}');

$xbmcJsonMethods['System.GetInfoLabels']                  = array('call' => '{"jsonrpc": "2.0", "method": "System.GetInfoLabels", "params": ["%s"], "id": 1}', 'args' => 'System.ProfileName');

$xbmcJsonMethods['XBMC.Play']                             = array('call' => '{"jsonrpc": "2.0", "method": "XBMC.Play", "params": { %s }, "id": 1}', 'optional' => array('songid' => '"songid": 1', 'file' => '"file": "path_to_file"'));

function msprintf($format, $args) {
	if(is_array($args)) {
		return vsprintf($format, $args);
	} else {
		return sprintf($format, $args);
	}
}

function jsonstring($method, $args = array()) {
	global $xbmcJsonMethods;
	
	if(!empty($xbmcJsonMethods[$method])) {
		if(!empty($args)) {
			return msprintf($xbmcJsonMethods[$method]['call'], $args);
		} else {
			if(!empty($xbmcJsonMethods[$method]['args'])) {
				return msprintf($xbmcJsonMethods[$method]['call'], $xbmcJsonMethods[$method]['args']);
			} else {
				return $xbmcJsonMethods[$method]['call'];
			}
		}
	} else {
		return false;
	}
}
function jsonmethodcall($method, $args = array(), $service_uri = "") {
	$request = jsonstring($method);
	jsoncall($request, $service_uri);
}
function jsoncall($request, $service_uri = "") {
	global $xbmcjsonservice;
	
	if($service_uri == "") {
		$service_uri = $xbmcjsonservice;
	}
	//json rpc call procedure
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $service_uri);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	$arrResult = json_decode(curl_exec($ch), true);

	curl_close($ch);
	
	return $arrResult;
}

?>
<?php
if(!empty($_REQUEST['tester']) && $_REQUEST['tester']='y') {
	if(!empty($_POST['request'])) {
		$request = str_replace('\"', '"', $_POST['request']);
	} else {
		$request = "";
	}
?>
<html>
	<head>
		<title>XBMC JSON Tester</title>
		<script type="text/javascript" language="javascript">
		<!--
			function setRequest(lstCalls) {
				var txtRequest = document.getElementById("txtRequest");
				txtRequest.value = lstCalls.value;
			}
		-->
		</script>
	</head>
	<body>
		<div id="form">
			<form action="" method="post">
				<input type="hidden" name="tester" value="yes"/>
				<select onchange="setRequest(this);">
					<option value="">[Predefined Queries]</option>
					<!--
					// Video Details: 
					// "genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "showtitle", "firstaired", "duration", "season", "episode", "runtime", "year", "playcount", "rating", "writer", "studio", "mpaa", "premiered", "album" 

					// Music Details:
					// "title", "album", "artist", "albumartist", "genre", "tracknumber", "discnumber", "trackanddiscnumber", "duration", "year", "musicbrainztrackid", "musicbrainzartistid", "musicbrainzalbumid", "musicbrainzalbumartistid", "musicbrainztrmidid", "comment", "lyrics", "rating"
					-->
<?php
					foreach($xbmcJsonMethods as $method => $arrmethod) {
						echo "<option value='".jsonstring($method)."'>".$method."</option>\n";
						if(!empty($arrmethod['optional'])) {
							foreach($arrmethod['optional'] as $key => $option) {
								echo "<option value='".jsonstring($method, $option)."'>".$method." (".$key.")</option>\n";
							}
						}
					}
?>
				</select>
				<input id="txtRequest" name="request" type="text" size="100" value='<?php echo $request; ?>' />
				<input type="submit" value="Submit" />
			</form>
		</div>
		<div id="query">
<?php
			if(!empty($_POST['request'])) {
				$result = jsoncall($request);

				echo "<br/>Call: <pre>";
				echo print_r($request,1);   // debugging
				echo "</pre><br/>";
				echo "<br/>Result: <pre>";
				echo print_r($result,1); // debugging
				echo "</pre><br/>";
			}
?>
		</div>
	</body>
</html>
<?php
}
?>