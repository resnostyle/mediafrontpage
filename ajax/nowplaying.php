<?php
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
			echo "        <div id='thumbblock'><img src=\"".$xbmcimgpath.$thumb."\" alt=\"".htmlentities($items[$current]['plot'], ENT_QUOTES)."\" /></div>";
		}
		echo "        <p>".$show."</p>";
		echo "        <p>".$title."</p>";
		//progress time
		$results = jsoncall('{"jsonrpc": "2.0", "method": "VideoPlayer.GetTime", "id": 1}');
		$time = $results['result']['time'];
		$total = $results['result']['total'];
		echo "        <p>".formattimes($time, $total)."</p>";
		if($results['result']['paused']) {
			echo "        <p>Paused</p>";
		}

		//progress bar
		$results = jsoncall('{"jsonrpc": "2.0", "method": "VideoPlayer.GetPercentage", "id": 1}');
		$percentage = $results['result'];
		echo "        <div class='progressbar'><div class='progress' style='width:".$percentage."%';</div></div>";

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
		echo "        <div class='progressbar'><div class='progress' style='width:".$percentage."%';</div></div>";
	} else {
		echo "Nothing Playing";
	} 
?>
		</div>
	</body>
</html>
