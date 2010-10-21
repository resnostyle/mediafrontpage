<?php
$wdgtRecentMovie = array("type" => "ajax", "block" => "recentmoviewrapper", "call" => "ajax/recentMoviesTV.php?t=m&a=l&c=15", "interval" => 0, "headerfunction" => "widgetRecentMovieHeader(\$params);");
$wIndex["wRecentMovies"] = $wdgtRecentMovie;

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
				
				var request = "ajax/recentMoviesTV.php?t=m&a=l&c="+param;
				switch(action) {
					case "p":
						request = "ajax/recentMoviesTV.php?t=m&a=p&id="+param;
						break;
					case "d":
						request = "ajax/recentMoviesTV.php?t=m&a=d&id="+param;
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

?>
