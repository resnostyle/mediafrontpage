<?php

$wIndex["wRSS"] = array("name" => "RSS Feed", "type" => "inline", "function" => "widgetRSS();"); //Declare widget function

function widgetRSS() {

	$xml = "http://rss.nzbmatrix.com/rss.php?subcat=42";
	$count = 10;

	$rss = parseRSS($xml, $count); 

	//output "<channel>" elements
	echo "<p><a href='" . $rss['channellink'] . "'>" . $rss['channeltitle'] . "</a>";
	echo "<br />"; 
	//echo "<p>" . $rss['channeldesc'] . "</p>")

	//output "<item>" elements
	for ($i=0; $i<$count; $i++) {
		echo "<p><a target=\"_blank\" href='" . $rss[$i]['itemlink'] . "'>" . $rss[$i]['itemtitle'] . "</a>";
		echo "<br />";
		//echo "<p>" . $rss[$i]['itemdesc'] . "</p>";
	}

}

function parseRSS ($xml, $count) {
 
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($xml);

	//get "<channel>" elements
	$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
	$rss['channeltitle'] = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	$rss['channellink'] = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
	$rss['channeldesc'] = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;


	//get "<item>" elements
	$item = $xmlDoc->getElementsByTagName('item');
	for ($i=0; $i<$count; $i++) {
		$rss[$i]['itemtitle'] = $item->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		$rss[$i]['itemlink'] = $item->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$rss[$i]['itemdesc'] = $item->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;	
	}
	return $rss;
}
?>
