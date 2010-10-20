<?php
$wdgtRecentTV = array("type" => "ajax", "block" => "recenttvwrapper", "call" => "ajax/recentMoviesTV.php?t=t&a=l&c=15", "interval" => 0, "headerfunction" => "widgetRecentTVHeader(\$params);");
$wIndex[wRecentTV] = $wdgtRecentTV;

function widgetRecentTVHeader($params = array('count' => 15)) {
	//check the parameter
	$count = $params['count'];

	if (empty($count)) {
		$count = 15;
	}	
	
	echo <<< RECENTTVHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdRecentTV(action, param) {
				var cmdPlayingRequest = new ajaxRequest();
				
				var request = "ajax/recentMoviesTV.php?t=t&a=l&c="+param;
				switch(action) {
					case "p":
						request = "ajax/recentMoviesTV.php?t=t&a=p&id="+param;
						break;
					case "d":
						request = "ajax/recentMoviesTV.php?t=t&a=d&id="+param;
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
