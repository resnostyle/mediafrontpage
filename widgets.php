<?php
require_once "config.php";
require_once "functions.php";

// widgets
$wdgtControl = array("type" => "inline", "function" => "widgetControl();");
$wdgtHardDrive = array("type" => "inline", "function" => "widgetHardDrive();");
$wdgtNowPlayingAjax = array("type" => "ajax", "block" => "nowplayingwrapper", "call" => "nowplaying.php", "interval" => 1000);
$wdgtNowPlayingControls = array("type" => "inline", "function" => "widgetNowPlayingControls();", "headerfunction" => "widgetNowPlayingHeader();");
$wdgtNowPlaying = array("type" => "mixed", "parts" => array($wdgtNowPlayingAjax, $wdgtNowPlayingControls));
$wdgtSabnzbd = array("type" => "ajax", "block" => "sabnzbdwrapper", "call" => "sabnzbd.php", "interval" => 5000);

//Note this example uses the "sytlesheet", and "headerfunction" properties.
$wdgtMiddleBlock = array("type" => "inline", "function" => "widgetMiddleBlock();", "sytlesheet" => "css/comingepisodes.css", "headerfunction" => "widgetMiddleBlockHeader();");

$wdgtRecentTV = array("type" => "inline", "function" => "widgetRecentTV();");
$wdgtRecentMovies = array("type" => "inline", "function" => "widgetRecentMovies();");

function widgetControl() {
	global $shortcut;

	echo "        <h1>Control</h1>";
	echo "        <ul>";
	foreach( $shortcut as $shortcutlabel => $shortcutpath) {
		echo "          <li><a class='shortcut' href='".$shortcutpath."' target=middle>".$shortcutlabel."</a><br/></li>";
	}
	echo "        </ul>";
}
function widgetHardDrive() {
	global $drive;
	
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
}
function widgetNowPlayingControls() {
	echo <<< NOWPLAYINGCONTROLS
        <a class='controlbutton' onclick='cmdNowPlaying("PlayPause");' href='#'><img src='media/btnPlayPause.png' alt='Play/Pause'/></a>
		<a class='controlbutton' onclick='cmdNowPlaying("Stop");' href='#'><img src='media/btnStop.png' alt='Stop'/></a>
NOWPLAYINGCONTROLS;
}
function widgetNowPlayingHeader() {
	echo <<< NOWPLAYINGHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdNowPlaying(cmd) {
				var cmdPlayingRequest = new ajaxRequest();
				cmdPlayingRequest.open("GET", "remoteexec.php?command="+cmd, true);
				cmdPlayingRequest.send(null);
			}
		-->
		</script>

NOWPLAYINGHEADER;
}
function widgetRecentTV() {
	echo "      <h1>Recent TV Shows</h1>";

	//get the recent episodes
	$arrResult = jsoncall('{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : 15 , "fields": [ "showtitle", "season", "episode" ] }, "id" : 1 }');
	
	//query below contains episodes
	$xbmcresults = $arrResult['result'];
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
}
function widgetRecentMovies() {
	echo "      <h1>Recent Movies</h1>";

	//get the results from the directory
	$arrResult = jsoncall('{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedMovies", "params" : { "start" : 0 , "end" : 15 , "fields" : [ "year" ] }, "id" : 1 }');

	//query below contains movies
	$results = $arrResult['result']['movies'];

	if (!empty($results)) {
		foreach ($results as $value) {
			$movie = $value['label'];
			$movie1 = $movie." ".$value['year'];
			$movie2 = urlencode($movie1);
			$display = $movie." &nbsp;(".$value['year'].")";
			echo "<a href=\"movieinfo?movie=$movie2\" class='recent-movie' target='middle'>$display</a><br/>";
		}
	}
}
function widgetMiddleBlock() {
	global $sickbeardcomingepisodes;
	
	echo "      <div id='middlecontent' /></div>";
	echo "      <iframe onload='onIFrameLoad(this);' src ='".$sickbeardcomingepisodes."' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
	echo "        <p>Your browser does not support iframes.</p>";
	echo "      </iframe>";
}
function widgetMiddleBlockHeader() {
	echo <<< MIDDLEBLOCKSCRIPT
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
		-->
		</script>

MIDDLEBLOCKSCRIPT;
}
function headerfunction() {
	global $sickbeardcomingepisodes;
	
	echo "      <div id='middlecontent' /></div>";
	echo "      <iframe onload='onIFrameLoad(this);' src ='".$sickbeardcomingepisodes."' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
	echo "        <p>Your browser does not support iframes.</p>";
	echo "      </iframe>";
}
?>