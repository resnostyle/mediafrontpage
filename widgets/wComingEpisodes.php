<?php
//Note this example uses the "stylesheet", and "headerfunction" properties.
$wdgtComingEpisodes = array("name" => "Coming Episodes", "type" => "inline", "function" => "widgetComingEpisodes();", "stylesheet" => "css/comingepisodes.css", "headerfunction" => "widgetComingEpisodesHeader();");
$wIndex["wComingEpisodes"] = $wdgtComingEpisodes;

function widgetComingEpisodes() {
	global $sickbeardcomingepisodes;
	
	//echo "<div class=\"widget-head\">\n";
	//echo "\t<h3>Coming Episodes</h3>\n"; 
	//echo "</div><!-- .widget-head -->\n ";
	echo "<div class=\"widget-content\">\n ";
	echo "\t<div id=\"comingepisodeswrapper\"></div>\n";
	echo "</div><!-- .widget-content -->\n";
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

				var iFrameBody = document.getElementById("comingepisodeswrapper");
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

function stripBody($body) {
	//$body = preg_replace("/.*<body[^>]*>|<\/body>.*/si", "", $html);  //Need a faster way to do this.
	$pos = strpos($body, "<body");
	if ($pos > 0) {
		$body = substr($body, $pos);
		$pos = strpos($body, ">");
		if ($pos > 0) {
			$body = substr($body, $pos + 1);
			$pos = strpos($body, "</body>");
			if ($pos > 0) {
				$body = substr($body, 0, $pos - 1);
			}
		}
	}
	return $body;
}
function stripInnerWrapper($body) {
	$pos = strpos($body, "<div id=\"listingWrapper\">");
	if ($pos > 0) {
		$body = substr($body, $pos);
		$pos = strpos($body, "<script");
		if ($pos > 0) {
			$body = substr($body, 0, $pos - 1);
		}
	}
	return $body;
}
function changeLinks($body) {
	global $sickbeardcomingepisodes;
	
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
	
	return $body;
}
function comingSoonUrl() {
	global $sickbeardcomingepisodes;

	if(!(strpos($sickbeardcomingepisodes, "http") === 0)){
		$url = "http://".$_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']."@".$_SERVER['SERVER_NAME'].((strpos($sickbeardcomingepisodes, "/") === 0)?"":"/").$sickbeardcomingepisodes;
	} else {
		$url = $sickbeardcomingepisodes;
	}
	
	return $url;
}
function getComingSoon() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_URL, comingSoonUrl());

	$html = curl_exec($ch);
	curl_close($ch);
	
	return $html;
}

function displayComingSoon () {
	$html = getComingSoon();
	$body = stripBody($html);
	$body = stripInnerWrapper($body);
	$body = changeLinks($body);
	echo $body;
}

if(!empty($_GET["style"]) && ($_GET["style"] == "s")) {
	include_once "../config.php";
	displayComingSoon();
}
?>
