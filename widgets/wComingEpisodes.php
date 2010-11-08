<?php
//Note this example uses the "stylesheet", and "headerfunction" properties.
$wdgtComingEpisodes = array("name" => "Coming Episodes", "type" => "inline", "function" => "widgetComingEpisodes();", "stylesheet" => "css/comingepisodes.css", "headerfunction" => "widgetComingEpisodesHeader();");
$wIndex["wComingEpisodes"] = $wdgtComingEpisodes;

function widgetComingEpisodes() {
	global $sickbeardcomingepisodes;
	
	echo "\t<div id=\"comingepisodeswrapper\"></div>\n";

	if(strpos($sickbeardcomingepisodes, "http://")===false) {
		$iFrameSource = $sickbeardcomingepisodes;
	} else {
		if(strpos($sickbeardcomingepisodes, "/sickbeard/")===false) {
			$iFrameSource= 'widgets/wComingEpisodes.php?display=yes';
		} else {
			$iFrameSource= 'widgets/wComingEpisodes.php?style=w';
		}
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
				addAltClass();
				addHighSlide();
				//adjustHeight();
			}

			function addAltClass() {
				var allHTMLTags = document.getElementsByTagName("*");
				var alt;
				alt = false;

				for (i=0; i < allHTMLTags.length; i++) {
					if (allHTMLTags[i].className == 'listing') {
						if(alt) {
							allHTMLTags[i].className = 'listing alt';
						}
						alt = !alt;
					}
				}
			}

			function addHighSlide() {
				var allHTMLTags = document.getElementsByTagName("img");

				for (i=0; i < allHTMLTags.length; i++) {
					if (allHTMLTags[i].className == 'listingThumb') {
						//Set parent node <a> tag to have correct
						allHTMLTags[i].parentNode.setAttribute('href',allHTMLTags[i].src);
						allHTMLTags[i].parentNode.className = 'highslide';
						allHTMLTags[i].parentNode.setAttribute('onclick','return hs.expand(this)');
						allHTMLTags[i].parentNode.onclick = function() { return hs.expand(allHTMLTags[i]) }; 
						
						//Wrap with span and reset.
						var newHTML = '<span class="sbposter-img">'+allHTMLTags[i].parentNode.outerHTML+'<a><div class="highslide-caption"><br></div></a></span>';
						allHTMLTags[i].parentNode.outerHTML = newHTML;
					}
				}
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

function stripBody($body) {
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
		//$body = str_replace($link, "\"".sickbeardposter(str_replace("\"", "", $newlink))."\"", $body);
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
	global $sickbeardurl;

	if(strrpos($sickbeardurl, "/") < strlen($sickbeardurl)) {
		$sickbeardurl .= "/";
	}
echo "<pre>".$sickbeardurl."</pre>";	
	$html = getComingSoon();
	$body = stripBody($html);
	$body = stripInnerWrapper($body);
	//$body = changeLinks($body);
	
	if(!empty($_GET["style"]) && (($_GET["style"] == "s") || ($_GET["style"] == "m"))) {
		$body = str_replace("src=\"".$sickbeardurl."showPoster/", "src=\"../sickbeardposter.php", $body);
		$body = str_replace("src=\"/sickbeard/showPoster/", "src=\"../sickbeardposter.php", $body);
		$body = str_replace("src=\"/showPoster/", "src=\"../sickbeardposter.php", $body);
	}
	$body = str_replace("src=\"/sickbeard/", "src=\"".$sickbeardurl, $body);
	$body = str_replace("href=\"/sickbeard/", "href=\"".$sickbeardurl, $body);
	$body = str_replace("src=\"/home/", "src=\"".$sickbeardurl."home/", $body);
	$body = str_replace("href=\"/home/", "href=\"".$sickbeardurl."home/", $body);
	$body = str_replace("src=\"home/", "src=\"".$sickbeardurl."home/", $body);
	$body = str_replace("href=\"home/", "href=\"".$sickbeardurl."home/", $body);
	$body = str_replace("src=\"/images/", "src=\"".$sickbeardurl."images/", $body);
	$body = str_replace("href=\"/images/", "href=\"".$sickbeardurl."images/", $body);
	$body = str_replace("src=\"images/", "src=\"".$sickbeardurl."images/", $body);
	$body = str_replace("href=\"images/", "href=\"".$sickbeardurl."images/", $body);
	echo $body;
}


//Zarquon's newer coming episodes widget - (problems with http:// addresses)
if(!empty($_GET["style"]) && (($_GET["style"] == "s") || ($_GET["style"] == "w"))) {
	include_once "../config.php";
	displayComingSoon();
}

?>
