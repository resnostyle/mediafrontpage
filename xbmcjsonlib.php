<?php
require_once "config.php";

$COMM_ERROR = "\n<p><strong>XBMC's JSON API did not respond.</strong></p>\n<p>Check your configuration (config.php) and that the JSON service variable is configured correctly and that the <a href=\"".$xbmcjsonservice."\">Service</a> is running.</p>\n";
$JSON_ERROR = "\n<p><strong>XBMC's <a href=\"".$xbmcjsonservice."\">JSON API service</a> returned an Error.</strong></p>\n";
$videodetailfields = '"genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "showtitle", "firstaired", "duration", "season", "episode", "runtime", "year", "playcount", "rating", "writer", "studio", "mpaa", "premiered", "album"';

$xbmcJsonMethods['JSONRPC.Version']                       = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Version", "id": 1}');
$xbmcJsonMethods['JSONRPC.Introspect']                    = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Introspect", "id": 1}');
$xbmcJsonMethods['JSONRPC.Permission']                    = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Permission", "id": 1}');
$xbmcJsonMethods['JSONRPC.Ping']                          = array('call' => '{"jsonrpc": "2.0", "method": "JSONRPC.Ping", "id": 1}');

$xbmcJsonMethods['AudioLibrary.GetArtists']               = array('call' => '{"jsonrpc": "2.0", "method": "AudioLibrary.GetArtists", "params": { "sortmethod": "artist", "sortorder" : "ascending" , "fields": [ "artist", "year" ]}, "id": 1}');
$xbmcJsonMethods['AudioLibrary.GetAlbums']                = array('call' => '{"jsonrpc": "2.0", "method": "AudioLibrary.GetAlbums", "params": { %s "sortmethod": "artist", "sortorder" : "ascending", "fields": [ "artist", "year" ] },"id": 1}', 'args' => array("artistid" => ""), 'optional' => array('artistid' => '"artistid": 1,'));
$xbmcJsonMethods['AudioLibrary.GetSongs']                 = array('call' => '{"jsonrpc": "2.0", "method": "AudioLibrary.GetSongs", "params": { %s %s "fields": [ "artist", "year" ] },"id": 1}', 'args' => array("artistid" => "", "albumid" => ""));

$xbmcJsonMethods['AudioPlayer.PlayPause']                 = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.PlayPause", "id": 1}');
$xbmcJsonMethods['AudioPlayer.Stop']                      = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.Stop", "id": 1}');
$xbmcJsonMethods['AudioPlayer.SkipPrevious']              = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.SkipPrevious", "id": 1}');
$xbmcJsonMethods['AudioPlayer.SkipNext']                  = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.SkipNext", "id": 1}');
$xbmcJsonMethods['AudioPlayer.BigSkipBackward']           = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.BigSkipBackward", "id": 1}');
$xbmcJsonMethods['AudioPlayer.BigSkipForward']            = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.BigSkipForward", "id": 1}');
$xbmcJsonMethods['AudioPlayer.SmallSkipBackward']         = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.SmallSkipBackward", "id": 1}');
$xbmcJsonMethods['AudioPlayer.SmallSkipForward']          = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.SmallSkipForward", "id": 1}');
$xbmcJsonMethods['AudioPlayer.Rewind']                    = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.Rewind", "id": 1}');
$xbmcJsonMethods['AudioPlayer.Forward']                   = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.Forward", "id": 1}');
$xbmcJsonMethods['AudioPlayer.GetPercentage']             = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.GetPercentage", "id": 1}');
$xbmcJsonMethods['AudioPlayer.GetTime']                   = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlayer.GetTime", "id": 1}');

$xbmcJsonMethods['AudioPlaylist.Add']                     = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlaylist.Add", "params": { "songid" : %d }, "id": 1}', 'args' => 0);
$xbmcJsonMethods['AudioPlaylist.GetItems']                = array('call' => '{"jsonrpc": "2.0", "method": "AudioPlaylist.GetItems", "params": { "fields": ["title", "album", "artist", "duration"] }, "id": 1}');

$xbmcJsonMethods['Files.GetSources']                      = array('call' => '{"jsonrpc": "2.0", "method": "Files.GetSources", "params" : { "media" : "%s" }, "id": 1}', 'args' => 'music');
$xbmcJsonMethods['Files.Download']                        = array('call' => '{"jsonrpc": "2.0", "method": "Files.Download", "params": %s, "id": 1}', 'args' => '""');

$xbmcJsonMethods['Player.GetActivePlayers']               = array('call' => '{"jsonrpc": "2.0", "method": "Player.GetActivePlayers", "id": 1}');
$xbmcJsonMethods['Playlist.GetItems']                     = array('call' => '{"jsonrpc": "2.0", "method": "Playlist.GetItems", "id": 1}');

$xbmcJsonMethods['VideoLibrary.GetRecentlyAddedMovies']   = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedMovies", "params" : { "start" : 0 , "end" : %d , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }', 'args' => 50);
$xbmcJsonMethods['VideoLibrary.GetMovies']                = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}');
$xbmcJsonMethods['VideoLibrary.GetRecentlyAddedEpisodes'] = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : %d , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }', 'args' => 50);
$xbmcJsonMethods['VideoLibrary.GetTVShows']               = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetTVShows", "id" : 1 }');
$xbmcJsonMethods['VideoLibrary.GetEpisodes']              = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetEpisodes", "params" : { "tvshowid" : %d, "season" : %d, "fields": [ '.$videodetailfields.' ] }, "id" : 1 }', 'args' => array('tvshowid' => 1, 'season' => 1));
$xbmcJsonMethods['VideoLibrary.GetSeasons']               = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.GetSeasons", "params" : { "tvshowid" : %d, "fields": [ "genre", "title", "showtitle", "duration", "season", "episode", "year", "playcount", "rating", "studio", "mpaa" ] }, "id" : 1 }', 'args' => array('tvshowid' => 1));
$xbmcJsonMethods['VideoLibrary.ScanForContent']           = array('call' => '{"jsonrpc": "2.0", "method": "VideoLibrary.ScanForContent", "id" : 1 }');

$xbmcJsonMethods['VideoPlayer.PlayPause']                 = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.PlayPause", "id": 1}');
$xbmcJsonMethods['VideoPlayer.Stop']                      = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.Stop", "id": 1}');
$xbmcJsonMethods['VideoPlayer.SkipPrevious']              = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.SkipPrevious", "id": 1}');
$xbmcJsonMethods['VideoPlayer.SkipNext']                  = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.SkipNext", "id": 1}');
$xbmcJsonMethods['VideoPlayer.BigSkipBackward']           = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.BigSkipBackward", "id": 1}');
$xbmcJsonMethods['VideoPlayer.BigSkipForward']            = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.BigSkipForward", "id": 1}');
$xbmcJsonMethods['VideoPlayer.SmallSkipBackward']         = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.SmallSkipBackward", "id": 1}');
$xbmcJsonMethods['VideoPlayer.SmallSkipForward']          = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.SmallSkipForward", "id": 1}');
$xbmcJsonMethods['VideoPlayer.Rewind']                    = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.Rewind", "id": 1}');
$xbmcJsonMethods['VideoPlayer.Forward']                   = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.Forward", "id": 1}');

$xbmcJsonMethods['VideoPlayer.GetPercentage']             = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.GetPercentage", "id": 1}');
$xbmcJsonMethods['VideoPlayer.GetTime']                   = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlayer.GetTime", "id": 1}');
$xbmcJsonMethods['VideoPlaylist.GetItems']                = array('call' => '{"jsonrpc": "2.0", "method": "VideoPlaylist.GetItems", "params": { "fields": ["title", "season", "episode", "plot", "duration", "showtitle"] }, "id": 1}');

$xbmcJsonMethods['System.GetInfoLabels']                  = array('call' => '{"jsonrpc": "2.0", "method": "System.GetInfoLabels", "params": ["%s"], "id": 1}', 'args' => 'System.ProfileName');
$xbmcJsonMethods['System.Shutdown']                       = array('call' => '{"jsonrpc": "2.0", "method": "System.Shutdown", "id" : 1 }');

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
	$request = jsonstring($method, $args);
	return jsoncall($request, $service_uri);
}
function jsoncall($request, $service_uri = "") {
	global $xbmcjsonservice;
	global $DEBUG;
	global $JSON_ERROR;
	global $COMM_ERROR;
	global $FORCE_UTF8;
	
	if($service_uri == "") {
		$service_uri = $xbmcjsonservice;
	}

	//json rpc call procedure
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $service_uri);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	$response = curl_exec($ch);
	curl_close($ch);

	if(!empty($FORCE_UTF8) && $FORCE_UTF8) {
		$response = jsonFixEncoding($response);
	}
	
	$arrResult = json_decode($response, true);
	if((!empty($arrResult['error'])) && (!empty($DEBUG) && $DEBUG)) {
		echo $JSON_ERROR;
		echo "<p><strong>Last JSON Error: ".json_last_error()."</strong></p>\n";
		echo "<p><strong>Request:</strong><pre>$request</pre></p>\n";
		echo "<p><strong>Response:</strong><pre>$response</pre></p>\n";
	}
	if((empty($arrResult)) && (!empty($DEBUG) && $DEBUG)) {
		echo $COMM_ERROR;
		echo "<p><strong>".jsonerrorstring(json_last_error())."</strong></p>\n";
		echo "<p><strong>Request:</strong><pre>$request</pre></p>\n";
		echo "<p><strong>Response:</strong><pre>$response</pre></p>\n";
	}
	return $arrResult;
}
function jsonerrorstring($err) {
	switch($err) {
		case JSON_ERROR_DEPTH:
			$error =  ' - Maximum stack depth exceeded';
			break;
		case JSON_ERROR_CTRL_CHAR:
			$error = ' - Unexpected control character found';
			break;
		case JSON_ERROR_SYNTAX:
			$error = ' - Syntax error, malformed JSON';
			break;
		case JSON_ERROR_STATE_MISMATCH:
			$error = ' - Syntax error, Invalid or malformed JSON';
			break;
		//case JSON_ERROR_UTF8:
		//	$error = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		//	break;
		case JSON_ERROR_NONE:
			$error = '';                    
		default:
			$error = ' Error #'.$err;                    
	}
	if (!empty($error)) {
	//	throw new Exception('JSON Error: '.$error);        
		return "JSON Error: ".$error;
    }
}

function jsonFixEncoding($s){ 
    if(empty($s)) {
		return $s; 
	} else {
		$s = preg_match_all("#[\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]#x", $s, $m );
		return implode("",$m[0]); 
	}
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