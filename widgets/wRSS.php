<?php

$wIndex["wRSS"] = array("name" => "RSS Feed", "type" => "inline", "function" => "widgetRSS();", "headerfunction" => "widgetRSSHeader();"); //Declare widget function

function widgetRSSHeader() {
	echo <<< RSSHEADER
<script type="text/javascript" language="javascript">
	<!--
		function showRSS(str) {
			if (str.length==0) {
				document.getElementById("rssOutput").innerHTML="";
				return;
			}
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("rssOutput").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","widgets/wRSS.php?style=s&rss="+str,true);
			xmlhttp.send();
		}
	-->
</script>

RSSHEADER;
}

function widgetRSS() {
	global $rssfeeds;
	echo "<form>\n";
	echo "\t<select onchange=\"showRSS(this.value)\">\n";
	echo "\t\t<option value=\"\">Select an RSS-feed:</option>\n";
	foreach($rssfeeds as $name => $feed) {
		echo "\t\t<option value=\"".$name."\">".$name."</option>\n";
	}
	echo "\t</select>\n";
	echo "</form>\n";
	echo "<div id=\"rssOutput\">RSS-feed will take a few seconds to load...</div>\n";
}

function displayRSS($url, $count = 10, $returnonly = false) {
	$return = "";
	if(!empty($url)) {
		$xmlDoc = new DOMDocument();
		$xmlDoc->load($url);

		//get elements from "<channel>"
		//$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
		//$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		//$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		//$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

		//output elements from "<channel>"
		//$return .= "<p><a href='".$channel_link."'>".$channel_title."</a></p>");

		//get and output "<item>" elements
		$x = $xmlDoc->getElementsByTagName('item');
		$alt = false;
		for ($i=0; $i<$count; $i++){
			$item_title = $x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
			$item_link = $x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
			$item_desc = $x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
			$item_desc = str_replace("'", "", str_replace("\"", "'", $item_desc));
			$return .= ("<p class=\"".($alt ? " alt" : "")."\"><a href=\"".$item_link."\" target=\"_blank\" onMouseOver=\"ShowPopupBox('".$item_desc."');\" onMouseOut=\"HidePopupBox();\">".$item_title."</a></p>");
			$alt = !$alt;
		}
	} else {
		$return = "<p>No RSS feed supplied.</p>";
	}

	if(!$returnonly) {
		echo $return;
	}
	return $return;
}

if(!empty($_GET['style']) && (($_GET['style'] == "w") || ($_GET['style'] == "s"))) {
	require_once "../config.php";
	global $rssfeeds;

	$count = (!empty($_GET['c'])) ? $_GET['c'] : 10;
	if(!empty($_GET['rss']) || !empty($_GET['rssurl'])) {
		if(!empty($_GET['rssurl'])) {
			$url = $_GET['rssurl'];
		} else {
		$url = (!empty($rssfeeds[$_GET['rss']]) ? $rssfeeds[$_GET['rss']] : "");
	}
} else {
	$url = reset($rssfeeds);
}
if($_GET['style'] == "w") {
?>
<html>
	<head>
		<title>Media Front Page - RSS Feed</title>
		<link rel='stylesheet' type='text/css' href='css/front.css'>
	</head>
	<body>
<?php
	displayRSS($url);
?>
	</body>
</html>
<?php
	} else {
		displayRSS($url);
	}
}
  
?>
