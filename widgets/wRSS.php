<?php

$wIndex["wRSS"] = array("name" => "RSS Feed", "type" => "inline", "function" => "widgetRSS();", "headerfunction" => "widgetRSSHeader();"); //Declare widget function

function widgetRSSHeader() {
        echo <<< RSSHEADER
<script type="text/javascript" language="javascript">
function showRSS(str) {
	if (str.length==0) {
		document.getElementById("rssOutput").innerHTML="";
		return;
	}
	if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else
		{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("rssOutput").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","widgets/wRSS.php?feed="+str,true);
	xmlhttp.send();
}
</script>
RSSHEADER;
}

function widgetRSS($feed) {
	echo "<form>\n";
	echo "<select onchange=\"showRSS(this.value)\">\n";
	echo "<option value=\"\">Select an RSS-feed:</option>\n";
	echo "<option value=\"NZBMatrix - TV Shows (DivX)\">NZBMatrix - TV Shows (DivX)</option>\n";
	echo "<option value=\"NZBMatrix - TV Shows (HD x264)\">NZBMatrix - TV Shows (HD x264)</option>\n";
	echo "<option value=\"NZBMatrix - Movies (DivX)\">NZBMatrix - Movies (DivX)</option>\n";
	echo "<option value=\"NZBMatrix - Movies (HD x264)\">NZBMatrix - Movies (HD x264)</option>\n";
	echo "<option value=\"NZBMatrix - Music (MP3)\">NZBMatrix - Music (MP3)</option>\n";
	echo "<option value=\"NZBMatrix - Music (Loseless)\">NZBMatrix - Music (Loseless)</option>\n";
	echo "</select>\n";
	echo "</form>\n";

	$xml = getRSSPath($feed);
	parseRSS($xml);
}

//Use specified feed otherwise use default
function getRSSPath ($feed) {   
	if (!empty($feed)) {
		switch ($feed) {
			case "NZBMatrix - TV Shows (DivX)":
				$xml=("http://rss.nzbmatrix.com/rss.php?subcat=6");
				break;
			case "NZBMatrix - TV Shows (HD x264)":
				$xml=("http://rss.nzbmatrix.com/rss.php?subcat=41");
				break;
			case "NZBMatrix - Movies (DivX)":
				$xml=("http://rss.nzbmatrix.com/rss.php?subcat=2");		
				break;
			case "NZBMatrix - Movies (HD x264)":
				$xml=("http://rss.nzbmatrix.com/rss.php?subcat=42");
				break;
			case "NZBMatrix - Music (MP3)":
				$xml=("http://rss.nzbmatrix.com/rss.php?subcat=22");
				break;
			case "NZBMatrix - Music (Loseless)":
				$xml=("http://rss.nzbmatrix.com/rss.php?subcat=23");
				break;
		}
	} else {
		$xml = ("http://rss.nzbmatrix.com/rss.php?subcat=42");
	}
	return $xml;
}

function parseRSS ($xml) {
 
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($xml);

	//get "<channel>" elements
	//$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
	//$rss['channeltitle'] = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	//$rss['channellink'] = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
	//$rss['channeldesc'] = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

	//Get number of items to return
	if (!empty($_GET['c'])) {
		$count = $_GET['c'];
	} else {
		$count = 10;
	}

	//get "<item>" elements
	$item = $xmlDoc->getElementsByTagName('item');
	for ($i=0; $i<$count; $i++) {
		$rss[$i]['itemtitle'] = $item->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		$rss[$i]['itemlink'] = $item->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$rss[$i]['itemdesc'] = $item->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;	
	}

	//output items
	echo "<div id=\"rssOutput\">\n";
	//output "<channel>" elements
	//echo "<p><a href='" . $rss['channellink'] . "'>" . $rss['channeltitle'] . "</a>";
	//echo "<br />"; 
	//echo "<p>" . $rss['channeldesc'] . "</p>")

	//output "<item>" elements
	for ($i=0; $i<$count; $i++) {
		echo "<p><a target=\"_blank\" href='" . $rss[$i]['itemlink'] . "'>" . $rss[$i]['itemtitle'] . "</a>";
		echo "<br />";
		//echo "<p>" . $rss[$i]['itemdesc'] . "</p>";
	}
	echo "</div>\n";
}

//if called with parameter load specified feed
if (!empty($_GET['feed'])) {
	$feed = $_GET['feed'];
	$xml = getRSSPath($feed);
	parseRSS($xml);
}

?>
