<?php
$wdgtNowPlayingAjax = array("type" => "ajax", "block" => "nowplayingwrapper", "call" => "widgets/wNowPlaying.php?ajax=w", "interval" => 1000);
$wdgtNowPlayingControls = array("type" => "inline", "function" => "widgetNowPlayingControls();", "headerfunction" => "widgetNowPlayingHeader();");
$wdgtNowPlaying = array("name" => "Now Playing", "type" => "mixed", "parts" => array($wdgtNowPlayingAjax, $wdgtNowPlayingControls));
$wIndex["wNowPlaying"] = $wdgtNowPlaying;

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
				cmdPlayingRequest.open("GET", "widgets/wNowPlaying.php?ajax=c&command="+cmd, true);
				cmdPlayingRequest.send(null);
			}
		-->
		</script>

NOWPLAYINGHEADER;
}
?>
<?php
if (!empty($_GET['ajax']) && ($_GET['ajax'] == "w")) {
	require_once "../config.php";
	require_once "../functions.php";

	?>
	<html>
		<head>
			<title>Media Front Page - Now Playing</title>
			<link rel='stylesheet' type='text/css' href='css/front.css'>
		</head>
		<body>
			<div id='nowplaying'>
			<?php
			//json rpc call procedure
			$results = jsoncall('{"jsonrpc": "2.0", "method": "Player.GetActivePlayers", "id": 1}');

			//video Player
			if (($results['result']['video']) == 1) {
				//get playlist items
				$results = jsoncall('{"jsonrpc": "2.0", "method": "VideoPlaylist.GetItems", "params": { "fields": ["title", "season", "episode", "plot", "duration", "showtitle"] }, "id": 1}');

				$items = $results['result']['items'];
				$current = $results['result']['current'];
				if(strlen($current) == 0) {
					$current=0;
				}
				
				$thumb = $items[$current]['thumbnail'];
				if(strlen($thumb) == 0) {
					$thumb = $items[$current]['fanart'];
				}
				$show  = $items[$current]['showtitle'];
				$title = $items[$current]['title'];
				if(strlen($title) == 0) {
					$title = $items[$current]['label'];
				}
				$season = $items[$current]['season'];
				$episode = $items[$current]['episode'];
				if((strlen($season) > 0) && (strlen($episode) > 0)) {
					$title = $season."x".str_pad($episode, 2, '0', STR_PAD_LEFT)." ".$title;
				}
				
				if(strlen($show) == 0) {
					$show = $title;
					$title = "";
				}
				if(strlen($show) == 0) {
					$info = pathinfo($items[$current]['file']);
					$show = $info['filename'];
				}

				if(strlen($thumb) > 0) {
					echo "\t\t<div id='thumbblock'>\n";
					echo "\t\t\t<a href=\"".$xbmcimgpath.$thumb."\" class=\"highslide\" onclick=\"return hs.expand(this)\">\n";
					echo "\t\t\t\t<img src=\"".$xbmcimgpath.$thumb."\" title='Click to enlarge' alt=\"".htmlentities($items[$current]['plot'], ENT_QUOTES)."\" />";
					echo "\t\t\t<a>\n";
					echo "\t\t</div>\n";
				}
				echo "\t\t\t<p>".$show."</p>\n";
				echo "\t\t\t<p>".$title."</p>\n";
				//progress time
				$results = jsoncall('{"jsonrpc": "2.0", "method": "VideoPlayer.GetTime", "id": 1}');
				$time = $results['result']['time'];
				$total = $results['result']['total'];
				echo "\t\t\t<p>".formattimes($time, $total)."</p>\n";
				if($results['result']['paused']) {
					echo "\t\t\t<p>Paused</p>\n";
				}

				//progress bar
				$results = jsoncall('{"jsonrpc": "2.0", "method": "VideoPlayer.GetPercentage", "id": 1}');
				$percentage = $results['result'];
				echo "\t\t\t<div class='progressbar'><div class='progress' style='width:".$percentage."%'></div></div>";

			} elseif (($results['result']['audio']) == 1) {
				//get playlist items
				$results = jsoncall('{"jsonrpc": "2.0", "method": "AudioPlaylist.GetItems", "params": { "fields": ["title", "album", "artist", "duration"] }, "id": 1}');
				$items = $results['result']['items'];
				$current = $results['result']['current'];

				$thumb = $items[$current]['thumbnail'];
				$artist = $items[$current]['artist'];
				$title = $items[$current]['title'];
				$album = $items[$current]['album'];
				if(strlen($thumb) > 0) {
					echo "        <img src=".$xbmcimgpath.$thumb."></img>";
				}
				echo "        <p>".$artist."</p>";
				echo "        <p>".$title."</p>";
				echo "        <p>".$album."</p>";

				//progress time
				$results = jsoncall('{"jsonrpc": "2.0", "method": "AudioPlayer.GetTime", "id": 1}');
				$time = $results['result']['time'];
				$total = $results['result']['total'];
				echo "        <p>".formattimes($time, $total)."</p>";
				if($results['result']['paused']) {
					echo "        <p>Paused</p>";
				}

				//progress bar
				$results = jsoncall('{"jsonrpc": "2.0", "method": "AudioPlayer.GetPercentage", "id": 1}');
				$percentage = $results['result'];
				echo "        <div class='progressbar'><div class='progress' style='width:".$percentage."%'></div></div>";
			} else {
				echo "Nothing Playing";
			} 
			?>
			</div>
		</body>
	</html>
	<?php
}
?>
<?php
if (!empty($_GET['ajax']) && ($_GET['ajax'] == "c")) {
	require_once "../config.php";
	require_once "../functions.php";

	if (!empty($_GET['command'])) {
		$command = $_GET["command"];
		/*
			// Commands
			PlayPause,            Pauses or unpause playback
			Stop,                 Stops playback
			SkipPrevious,         Skips to previous item on the playlist
			SkipNext,             Skips to next item on the playlist
			BigSkipBackward,      
			BigSkipForward,       
			SmallSkipBackward,    
			SmallSkipForward,     
			Rewind,               Rewind current playback
			Forward,              Forward current playback
		*/


		//get active players
		$request = '{"jsonrpc": "2.0", "method": "Player.GetActivePlayers", "id": 1}';
		$results = jsoncall($request);

		//Video Player
		if (($results['result']['video']) == 1) {
			//get playlist items
			$player = "VideoPlayer";
		//Music Player
		} elseif (($results['result']['audio']) == 1) {
			//get playlist items
			$player = "AudioPlayer";
		} else {
			// Nothing Playing
		}

		$request = '{"jsonrpc": "2.0", "method": "'.$player.'.'.$command.'", "id": 1}';
		$result = jsoncall($request);

		// debugging
		if($_GET["debug"] == "y") {
			echo "<br/>Call: <pre>";
			echo print_r($request,1);
			echo "</pre><br/>";
			echo "<br/>Result: <pre>";
			echo print_r($result,1);
			echo "</pre><br/>";
		}
	} else {
		echo "<br/>\n<p><strong>Invalid Request<strong></p>\n<p>Call: <pre>\n";
		echo print_r($_GET,1);
		echo "</pre>\n</p>\n";
	}
}
?>