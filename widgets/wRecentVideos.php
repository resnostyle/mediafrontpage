<?php
$wdgtRecentMovie = array("type" => "ajax", "block" => "recentmoviewrapper", "call" => "widgets/wRecentVideos.php?ajax=w&t=m&a=m&c=15", "interval" => 0, "headerfunction" => "widgetRecentMovieHeader(\$params);");
$wIndex["wRecentMovies"] = $wdgtRecentMovie;

$wdgtRecentTV = array("type" => "ajax", "block" => "recenttvwrapper", "call" => "widgets/wRecentVideos.php?ajax=w&t=t&a=e&c=15", "interval" => 0, "headerfunction" => "widgetRecentTVHeader(\$params);");
$wIndex["wRecentTV"] = $wdgtRecentTV;

function widgetRecentMovieHeader($params = array('count' => 15)) {
	//check the parameter
	if (empty($params['count'])) {
		$count = 15;
	} else {
		$count = $params['count'];
	}
	
	echo <<< RECENTTMOVIEHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdRecentMovie(action, param) {
				var cmdPlayingRequest = new ajaxRequest();
				
				var request = "widgets/wRecentVideos.php?ajax=w&t=m&a=m&c="+param;
				switch(action) {
					case "p":
						request = "widgets/wRecentVideos.php?ajax=w&t=m&a=p&id="+param;
						break;
					case "d":
						request = "widgets/wRecentVideos.php?ajax=w&t=m&a=d&id="+param;
						break;
				}
				
				cmdPlayingRequest.open("GET", request, true);

				if(action!="p") {
					cmdPlayingRequest.onreadystatechange = function() {
						if (cmdPlayingRequest.readyState==4) {
							if (cmdPlayingRequest.status==200 || window.location.href.indexOf("http")==-1) {
								document.getElementById("recentmoviewrapper").innerHTML=cmdPlayingRequest.responseText;
							} else {
								alert("An error has occured making the request");
							}
						}
					}
				}
				cmdPlayingRequest.send(null);
			}
		-->
		</script>

RECENTTMOVIEHEADER;
}

function widgetRecentTVHeader($params = array('count' => 15)) {
	//check the parameter
	if (empty($params['count'])) {
		$count = 15;
	} else {
		$count = $params['count'];
	}
	
	echo <<< RECENTTVHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdRecentTV(action, param) {
				var cmdPlayingRequest = new ajaxRequest();
				
				var request = "widgets/wRecentVideos.php?ajax=w&t=t&a=e&c="+param;
				switch(action) {
					case "p":
						request = "widgets/wRecentVideos.php?ajax=w&t=t&a=p&id="+param;
						break;
					case "d":
						request = "widgets/wRecentVideos.php?ajax=w&t=t&a=d&id="+param;
						break;
				}

				cmdPlayingRequest.open("GET", request, true);

				if(action!="p") {
					cmdPlayingRequest.onreadystatechange = function() {
						if (cmdPlayingRequest.readyState==4) {
							if (cmdPlayingRequest.status==200 || window.location.href.indexOf("http")==-1) {
								document.getElementById("recenttvwrapper").innerHTML=cmdPlayingRequest.responseText;
							} else {
								alert("An error has occured making the request");
							}
						}
					}
				}
				cmdPlayingRequest.send(null);
			}
		-->
		</script>

RECENTTVHEADER;
}

?>
<?php
if (!empty($_GET['ajax']) && ($_GET['ajax'] == "w")) {
	require_once "../config.php";
	require_once "../functions.php";
	require_once "libVideo.php";

	//Get action type valid types are m - Movies; t - TV
	$type = $_GET["t"];
	if(empty($type)) {
		$type = "m"; //Default to m - Movies
	}

	//Get action type valid types are e - List Episodes; m - List Movies; d - Display; p - Play
	$action = $_GET["a"];

	//Get count for list type
	$count = $_GET["c"];
	if(empty($count) || $count == 0) {
		$count = 15; //Default 15
	}

	if(!empty($_GET['id'])) {
		$videoId = $_GET['id'];
	} else {
		$videoId = -1;
	}
	
	if($type == "t") {
		if(($action == "d") || ($action == "p")) {
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
		} else {
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : '.$count.' , "fields": [ '.$videodetailfields.' ] }, "id" : 1 }';
		}
		$results = jsoncall($request);
		$videos = $results['result']['episodes'];

		$typeId = "episodeid";
	} else {
		if(($action == "d") || ($action == "p")) {
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params": { "sortorder" : "ascending", "fields" : [ '.$videodetailfields.' ] }, "id": 1}';
		} else {
			$request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetRecentlyAddedMovies", "params": { "start" : 0 , "end" : '.$count.' , "fields" : [ '.$videodetailfields.' ] }, "id" : 1 }';
		}
		$results = jsoncall($request);
		$videos = $results['result']['movies'];

		$typeId = "movieid";
	}
//echo "<pre>".print_r($videos,1)."</pre>\n<br/>\n".$action."<br/>\n".$typeId."<br/>\n".$videoId;

	executeVideo($videos, $action, $typeId, $videoId, $count);
}
?>