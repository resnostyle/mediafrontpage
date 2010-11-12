<?php
$wdgtSabnzbd = array("name" => "Sabnzbd", "type" => "ajax", "block" => "sabnzbdwrapper", "call" => "widgets/wSabnzbd.php?style=w&c=15", "interval" => 10000);
$wIndex["wSabnzbd"] = $wdgtSabnzbd;

?>
<?php
function sabQuery($command, $values = array()) {
	global $saburl, $sabapikey;

	$getParameter = "";
	foreach ($values as $key => $value) {
		$getParameter .= "&" . $key . "=" . $value;
	}
	
	$queryurl = $saburl."api?mode=".$command."&output=json"."&apikey=".$sabapikey;
	$queryurl .= $getParameter;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $queryurl);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$results = json_decode(curl_exec($ch), true);
	curl_close($ch);
	
	return $results;
}

function sabStatus($count = 15) {
	echo "<div id=\"sabnzbd\">\n";

	$sabqueue = sabQuery("qstatus");
	//echo "<br/><pre>".print_r($sabqueue)."</pre>";

	echo "\t<p>".$sabqueue["state"];
	if ($sabqueue["state"] == "DOWNLOADING") {
		echo " AT ".$sabqueue["speed"]."</p>\n";
		echo "\t<p>TIMELEFT - ".$sabqueue["timeleft"]."</p>\n";
	}
	$i = 0;
	foreach($sabqueue["jobs"] as $slot) {
		if($i < $count) {
			$total = (int)$slot["mb"];
			$remaining = (int)$slot["mbleft"];
			if($total > 0 && is_numeric($remaining)) {
				$percentage = (int)((($total - $remaining) / $total)*100);
				echo "\t<div class=\"progressbar\"><div class=\"progress\" style=\"width:".$percentage."%\"></div><div class=\"progresslabel\">".$slot["filename"]."</div></div><br/>\n";
			}
		} else {
			break;
		}
		$i += 1;
	}
	echo "</div><!-- sabnzbd -->\n";
}

if (!empty($_GET['style']) && (($_GET['style'] == "w") || ($_GET['style'] == "s"))) {
	require_once "../config.php";

	$count = (!empty($_GET['c'])) ? $_GET['c'] : 15;
	if ($_GET['style'] == "w") {
?>
<html>
	<head>
		<title>Media Front Page - SABnzbd Status</title>
		<link rel='stylesheet' type='text/css' href='css/front.css'>
	</head>
	<body>
<?php
		sabStatus($count);
?>
	</body>
</html>
<?php
	} else {
		sabStatus($count);
	}
}
?>
