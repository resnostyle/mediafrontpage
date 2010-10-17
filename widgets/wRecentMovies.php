<?php
$wdgtRecentMovies = array("type" => "inline", "function" => "widgetRecentMovies(\$params);");
$wIndex["wRecentMovies"] = $wdgtRecentMovies;

function widgetRecentMovies($params = array('count' => 10)) {

	//check the parameter
	$count = $params['count'];
	
	//get the results from the directory
	$arrResult = jsoncall('{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedMovies", "params" : { "start" : 0 , "end" : '.$count.' , "fields" : [ "year" ] }, "id" : 1 }');

	//query below contains movies
	$results = $arrResult['result']['movies'];

	if (!empty($results)) {
		foreach ($results as $value) {
			$movie = $value['label'];
			$movie1 = $movie." ".$value['year'];
			$movie2 = urlencode($movie1);
			$display = $movie." &nbsp;(".$value['year'].")";
			echo "\t\t\t\t<a href=\"movieinfo?movie=$movie2\" class='recent-movie' target='middle'>$display</a><br/>\n";
		}
	}
}
?>
