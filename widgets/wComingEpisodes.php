<?php
//Note this example uses the "stylesheet", and "headerfunction" properties.
$wdgtComingEpisodes = array("type" => "inline", "function" => "widgetComingEpisodes();", "stylesheet" => "css/comingepisodes.css", "headerfunction" => "widgetComingEpisodesHeader();");
$wIndex[wComingEpisodes] = $wdgtComingEpisodes;

function widgetComingEpisodes() {
	global $sickbeardcomingepisodes;
	
	echo "      <div id='middlecontent' /></div>";
	echo "      <iframe onload='onIFrameLoad(this);' src ='".$sickbeardcomingepisodes."' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
	echo "        <p>Your browser does not support iframes.</p>";
	echo "      </iframe>";
}
function widgetComingEpisodesHeader() {
	echo <<< ComingEpisodesSCRIPT
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

ComingEpisodesSCRIPT;
}
?>
