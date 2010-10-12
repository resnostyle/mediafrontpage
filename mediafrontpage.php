<?php
	include "config.php";
	include "functions.php";
?>
<html>
	<head>
		<title>Media Front Page</title>
		<link rel='stylesheet' type='text/css' href='css/front.css'>
		<link rel="stylesheet" type="text/css" href="css/comingepisodes.css">

		<script type="text/javascript" language="javascript">
		<!--
			function extractIFrameBody(iFrameEl) {
				var doc = null;
				if (iFrameEl.contentDocument) { // For NS6
					doc = iFrameEl.contentDocument; 
				} else if (iFrameEl.contentWindow) { // For IE5.5 and IE6
					doc = iFrameEl.contentWindow.document;
				} else if (iFrameEl.document) { // For IE5
					doc = iFrameEl.document;
				} else {
					alert("Error: could not find sumiFrame document");
					return null;
				}
				return doc.body;
			}
			function onIFrameLoad(iFrameElement) {
				var serverResponse = extractIFrameBody(iFrameElement).innerHTML;

				var iFrameBody = document.getElementById("middlecontent");
				iFrameBody.innerHTML = serverResponse;

				//adjustHeight();
			}

			function adjustHeight() {
				var windowSizeAdjustment = 100;
				var windowHeight = (window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight) - windowSizeAdjustment;
				if (windowHeight > 0) { 
					var objWrapper = document.getElementById("listingWrapper");
					objWrapper.style.height = windowHeight + 'px';
				}
			}

			function ajaxRequest() {
				var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //activeX versions to check for in IE
				if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
					for (var i=0; i<activexmodes.length; i++) {
						try {
							return new ActiveXObject(activexmodes[i]);
						}
						catch(e){
							//suppress error
						}
					}
				} else if (window.XMLHttpRequest) {// if Mozilla, Safari etc
					return new XMLHttpRequest();
				} else {
					return false;
				}
			}
			function loadNowPlaying() {
				var nowPlayingRequest = new ajaxRequest();
				nowPlayingRequest.open("GET", "nowplaying.php", true);
				nowPlayingRequest.onreadystatechange = function() {

					if (nowPlayingRequest.readyState==4) {
						if (nowPlayingRequest.status==200 || window.location.href.indexOf("http")==-1) {
							document.getElementById("nowplayingwrapper").innerHTML=nowPlayingRequest.responseText;
						} else {
							//alert("An error has occured making the request");
						}
					}
				}
				nowPlayingRequest.send(null);
			}
			
			function cmdNowPlaying(cmd) {
				var cmdPlayingRequest = new ajaxRequest();
				cmdPlayingRequest.open("GET", "remoteexec.php?command="+cmd, true);
				cmdPlayingRequest.send(null);
			}

			setInterval("loadNowPlaying()", 1000);  ///////// 1 second
			//-->
		</script>
	</head>
<?php
	echo "  <body>";
	echo "    <div id='main'>";
	echo "    <div id='left-sidebar'>";
	echo "      <div id=quick-links>";
	echo "        <h1>Control</h1>";
	echo "        <ul>";
	foreach( $shortcut as $shortcutlabel => $shortcutpath) {
		echo "          <li><a class='shortcut' href='".$shortcutpath."' target=middle>".$shortcutlabel."</a><br/></li>";
	}
	echo "        </ul>";
	echo "      </div>";

	// show drive usage
	echo "      <div id=hdstats>";
	echo "        <h1>Hard Drives</h1>";
	echo "        <table border='0'>";
	echo "          <tr>";
	echo "            <th>Disk</th>";
	echo "            <th>Capacity</th>";
	echo "            <th>Remaining</th>";
	echo "            <th>%</th>";
	echo "          </tr>";
	foreach( $drive as $drivelabel => $drivepath) {
		echo "          <tr>";
		echo "            <td>".$drivelabel."</td>";
		echo "            <td>".to_readable_size(disk_total_space($drivepath))."</td>";
		echo "            <td>".to_readable_size(disk_free_space($drivepath))."</td>";
		echo "            <td><div class='harddrive'><div class='usage' style='width:".(disk_used_percentage($drivepath))."%';</div></div></td>";
		echo "          </tr>";
	}
	echo "        </table>";
	echo "      </div>";

	//json rpc call procedure
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $xbmcjsonservice);

	//now playing section
	echo "      <div id='nowplayingblock'>";
	echo "        <div id='nowplayingwrapper'>";
	echo "        </div>";
	echo "        <a class='controlbutton' onclick='cmdNowPlaying(\"PlayPause\");' href='#'><img src='media/btnPlayPause.png' alt='Play/Pause'/></a>";
	echo "        <a class='controlbutton' onclick='cmdNowPlaying(\"Stop\");' href='#'><img src='media/btnStop.png' alt='Stop'/></a>";
	echo "      </div>";
	echo "    </div>";

	//iframe
	echo "    <div id='middle'>";
	echo "      <div id='middlecontent'></div>";
	echo "      <iframe onload='onIFrameLoad(this);' src ='".$sickbeardcomingepisodes."' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
	echo "        <p>Your browser does not support iframes.</p>";
	echo "      </iframe>";
	echo "    </div>";

	//recent tv section
	echo "    <div id='right-sidebar'>";
	echo "    <div id=recent-tv>";
	echo "      <h1>Recent TV Shows</h1>";

	//get the recent episodes
	$request2 = '{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : 15 , "fields": [ "showtitle", "season", "episode" ] }, "id" : 1 }';

	curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
	$array2 = json_decode(curl_exec($ch),true);

	//query below contains episodes
	$xbmcresults = $array2['result'];
	if (array_key_exists('episodes', $xbmcresults)) {
		$episodes = $xbmcresults['episodes'];
		foreach ($episodes as $value) {
			$label = $value['label'];
			$label2 = urlencode($label);
			$showtitle = $value['showtitle'];
			$season = $value['season'];
			$episode = $value['episode'];
			$display = $showtitle." - ".$season."x".$episode." - ".$label;
			echo "<a href=\"recentepisodes.php?episode=$label2\" target='middle'>$display</a><br/>";
		}
	}
	echo "    </div>";
	echo "    <div id=recentmovies>";
	echo "      <h1>Recent Movies</h1>";

	//get the results from the directory
	$request2 = '{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedMovies", "params" : { "start" : 0 , "end" : 15 , "fields" : [ "year" ] }, "id" : 1 }';
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
	$array2 = json_decode(curl_exec($ch),true);

	//query below contains movies
	$results = $array2['result']['movies'];

	if (!empty($results)) {
		foreach ($results as $value) {
			$movie = $value['label'];
			$movie1 = $movie." ".$value['year'];
			$movie2 = urlencode($movie1);
			$display = $movie." &nbsp;(".$value['year'].")";
			echo "<a href=\"movieinfo?movie=$movie2\" class='recent-movie' target='middle'>$display</a><br/>";
		}
	}
	echo "    </div>";
	echo "    </div>";
	echo "  </body>";
	echo "</html>";
?>
