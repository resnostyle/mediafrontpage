<?php
require_once "config.php";
require_once "functions.php";
require_once "layout.php";
	
?>
<html>
	<head>
		<title>Media Front Page</title>
		<link rel='stylesheet' type='text/css' href='css/front.css'>
		<script type="text/javascript" language="javascript">
		<!--
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
			function ajaxPageLoad(page, div) {
				var nowPlayingRequest = new ajaxRequest();
				nowPlayingRequest.open("GET", page, true);
				nowPlayingRequest.onreadystatechange = function() {

					if (nowPlayingRequest.readyState==4) {
						if (nowPlayingRequest.status==200 || window.location.href.indexOf("http")==-1) {
							document.getElementById(div).innerHTML=nowPlayingRequest.responseText;
						} else {
							//alert("An error has occured making the request");
						}
					}
				}
				nowPlayingRequest.send(null);
			}
			
			//-->
		</script>
<?php
		foreach( $arrLayout as $sectionId => $widgets ) {
			foreach( $widgets as $widgetId => $widget ) {
				renderWidgetHeaders($widget);
			}
		}
?>
	</head>
	<body>
		<div id='main'>
<?php
			foreach( $arrLayout as $sectionId => $widgets ) {
				echo "\n<div id=\"".$sectionId."\">\n";
				foreach( $widgets as $widgetId => $widget ) {
					echo "\n<div id=\"".$widgetId."\">\n";
					renderWidget($widget);
					echo "\n</div><!-- ".$widgetId." -->\n";
				}
				echo "\n</div><!-- ".$sectionId." -->\n";
			}
?>
	</body>
</html>
