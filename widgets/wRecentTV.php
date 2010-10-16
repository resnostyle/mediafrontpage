<?php
$wdgtRecentTV = array("type" => "inline", "function" => "widgetRecentTV();");
$wIndex[wRecentTV] = $wdgtRecentTV;

function widgetRecentTV() {

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
?>
