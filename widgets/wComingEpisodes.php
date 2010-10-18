<?php
//Note this example uses the "stylesheet", and "headerfunction" properties.
$wdgtComingEpisodes = array("type" => "inline", "function" => "widgetComingEpisodes();", "stylesheet" => "css/comingepisodes.css", "headerfunction" => "widgetComingEpisodesHeader();");
$wIndex[wComingEpisodes] = $wdgtComingEpisodes;

function widgetComingEpisodes() {
	global $sickbeardcomingepisodes;
	
	echo "      <div id='middlecontent' /></div>";
	if(strpos($sickbeardcomingepisodes, "http://")===false) {
		$iFrameSource = $sickbeardcomingepisodes;
	} else {
		$iFrameSource= 'widgets/wComingEpisodes.php?display=yes';
	}
	echo "      <iframe onload='onIFrameLoad(this);' src ='".$iFrameSource."' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
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
if(!empty($_GET["display"])) {
	include_once "../config.php";

	//$html = file_get_contents("http://www.google.com");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_URL, $sickbeardcomingepisodes);

	$html = curl_exec($ch);
	
	curl_close($ch);

	//$body = preg_replace("/.*<body[^>]*>|<\/body>.*/si", "", $html);  //Need a faster way to do this.
	$body = $html;

	$urldata = parse_url($sickbeardcomingepisodes);
	$pos = strrpos($sickbeardcomingepisodes, "/");
	if($pos < strlen($sickbeardcomingepisodes)) {
		$uri_full = substr($sickbeardcomingepisodes, 0, $pos + 1);
	} else {
		$uri_full = $sickbeardcomingepisodes;
	}
	$uri_domain = str_replace($urldata["path"], "", $sickbeardcomingepisodes);
	
	$regex  = '/(<[(img)|(a)]\s*(.*?)\s*[(src)|(href)]=(?P<link>[\'"]+?\s*\S+\s*[\'"])+?\s*(.*?)\s*>)/i';

	preg_match_all($regex, $body, $matches);
	
	foreach($matches['link'] as $link) {
		$pos = strpos($link, "/");
		if($pos && strpos($link, "//")===false) {
			if($pos==1) {
				$newlink = substr($link , 0, 1).$uri_domain.substr($link , 1);
			} else {
				$newlink = substr($link , 0, 1).$uri_full.substr($link , 1);
			}
		}
		$body = str_replace($link, $newlink, $body);
	}

	echo $body;
}
?>
