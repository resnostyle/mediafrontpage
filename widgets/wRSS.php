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
		function sabAddUrl(sablink) {
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("POST", "widgets/wRSS.php?style=a", true);

			//Send the proper header information along with the request
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.setRequestHeader("Content-length", sablink.length);
			xmlhttp.setRequestHeader("Connection", "close");

			xmlhttp.onreadystatechange = function() {//Call a function when the state changes.
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					try {
						var sabjson = eval("("+xmlhttp.responseText+")");
						if(sabjson.status) {
							alert('Item successfully added to SABnzdd+.');
						} else {
							alert("Problem adding link to SABnzdd+.\\r\\n\\r\\nError: " + sabjson.error + "\\r\\n\\r\\n\\r\\n" + xmlhttp.responseText);
						}
					}
					catch(e) {
						alert("Problem calling SABnzdd+.\\r\\n\\r\\n"+xmlhttp.responseText);
					}
				}
			}
			xmlhttp.send(sablink);
		}
	-->
</script>

RSSHEADER;
}
function widgetRSS($formaction = "") {
	global $rssfeeds;

	$response = sabadd();
	if(!empty($response)) {
		$responsejson = json_decode($response, true);
		if(!empty($responsejson)) {
			if($responsejson['status']) {
				echo "<p>Item successfully added to SABnzdd+.</p>\n";
			} else {
				echo "<p>Problem adding link to SABnzdd+.</p>\n<p>Error: ".$responsejson["error"]."</p>\n";
			}
		} else {
			echo "<p>Problem calling SABnzdd+.</p>\n<pre>\n".$response."\n</pre>\n";
		}
	}
	
	if(!empty($formaction)) {
		echo "<form action=\"".$formaction."\" method=\"get\">\n";
		echo "<input type=\"hidden\" name=\"style\" value=\"".(!empty($_GET['style']) ? $_GET['style'] : "m")."\" />";
		echo "<input type=\"hidden\" name=\"w\" value=\"wRSS\" />";
		echo "\t<select name=\"feed\">\n";
	} else {
		echo "<form>\n";
		echo "\t<select onchange=\"showRSS(this.value);\">\n";
	}
	foreach($rssfeeds as $name => $feed) {
		echo "\t\t<option value=\"".$name."\"".((!empty($_GET['feed']) && ($_GET['feed'] == $name)) ? " selected=\"selected\"" : "").">".$name."</option>\n";
	}
	echo "\t</select>\n";
	if(!empty($formaction)) {
		echo "\t<input type=\"submit\" value=\"Go\" />\n";
	}
	echo "</form>\n";
	echo "<div id=\"rssOutput\">";
	if(!empty($_GET['feed']) && !empty($rssfeeds[$_GET['feed']])) {
		displayRSS($rssfeeds[$_GET['feed']]);
	} else {
		displayRSS(reset($rssfeeds));
	}
	echo "</div>\n";
}
function sab_addurl($link, $name, $rssfeed){
	global $saburl, $sabapikey;
	global $rssfeeds;

	$queryurl = $saburl."api?mode=addurl&name=".urlencode($link)."&nzbname=".urlencode($name);
	$queryurl .= (!empty($rssfeed['cat']) ? "&cat=".urlencode($rssfeed['cat']) : "");
	$queryurl .= (!empty($rssfeed['script']) ? "&script=".$rssfeed['script'] : "");
	$queryurl .= (!empty($rssfeed['pp']) ? "&pp=".$rssfeed['pp'] : "");
	$queryurl .= (!empty($rssfeed['priority']) ? "&priority=".$rssfeed['priority'] : "");
	$queryurl .= "&output=json&apikey=".$sabapikey;
	return $queryurl;
}
function sabadd($sabaddurl = "") {
	$response = "";
	
	if(!empty($_GET['sabadd']) && empty($sabaddurl)) {
		$sabaddurl = ($_GET['sabadd']);
	}
	if(!empty($sabaddurl)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_URL, $sabaddurl);

		$response = curl_exec($ch);
		curl_close($ch);
	}

	return $response;
}
function displayRSS($rssfeed, $count = 10, $returnonly = false) {
	$return = "";
	if(!empty($rssfeed['url'])) {
		$xmlDoc = new DOMDocument();
		$xmlDoc->load($rssfeed['url']);

		if(is_object($xmlDoc->getElementsByTagName('channel')) && ($xmlDoc->getElementsByTagName('channel')->length > 0)) {
			$type = "rss";
		} elseif(is_object($xmlDoc->getElementsByTagName('entry')) && ($xmlDoc->getElementsByTagName('entry')->length > 0)) {
			$type = "atom";
		} else {
			$type = "unknown";
		}

		if($type == "unknown") {
			$return = "<p>Error: Unable to determine feed type.</p>";
		} else {
			switch($type) {
				case 'atom':
					$items = $xmlDoc->getElementsByTagName('entry');
					break;
				case 'rss':
					$items = $xmlDoc->getElementsByTagName('item');
					break;
			}
			if(is_object($items)) {
				if($items->length < $count) {
					$itemsListLength = $items->length;
				} else {
					$itemsListLength = $count;
				}
			} else {
				$itemsListLength = 0;
			}
			
			$alt = false;
			for ($i=0; $i<$itemsListLength; $i++){
				switch($type) {
					case 'atom':
						$item_title = $items->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
						$item_link = $items->item($i)->getElementsByTagName('link')->item(0)->getAttribute('href');
						$item_desc = $items->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue;
						$item_desc = str_replace("  ", " ", $item_desc);
						$item_desc = str_replace("\n\n", "\n", $item_desc);
						$item_desc = str_replace("\n", " <br/>", $item_desc);
						break;
					case 'rss':
						$item_title = $items->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
						$item_link = $items->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
						$item_desc = $items->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
						break;
				}
				$item_desc = str_replace("'", "", str_replace("\"", "'", $item_desc));
				$return .= "<p class=\"".($alt ? " alt" : "")."\">";

				if(!empty($rssfeed['cat']) || !empty($rssfeed['script']) || !empty($rssfeed['pp']) || !empty($rssfeed['priority'])) {
					if(!empty($_GET['style']) && ($_GET['style'] == 'm')) {
						$sabaddlink = "?style=m";
						$sabaddlink .= "&w=".(!empty($_GET['w']) ? $_GET['w'] : "wRSS");
						$sabaddlink .= (!empty($_GET['feed']) ? "&feed=".$_GET['feed'] : "");
						$sabaddlink .= "&sabadd=".urlencode(sab_addurl($item_link, $item_title, $rssfeed));
						$return .= "<a href=\"".$sabaddlink."\" class=\"sablink\">";
						$return .= "<img class=\"sablink\" src=\"../media/sab2_16.png\" alt=\"Download with SABnzdd+\"/>";
						$return .= "</a>";
					} else {
						$return .= "<a href=\"#\" class=\"sablink\" onclick=\"sabAddUrl('".htmlentities(sab_addurl($item_link, $item_title, $rssfeed))."'); return false;\">";
						$return .= "<img class=\"sablink\" src=\"media/sab2_16.png\" alt=\"Download with SABnzdd+\"/>";
						$return .= "</a>";
					}
				}

				$return .= "<a href=\"".$item_link."\" target=\"_blank\" onMouseOver=\"ShowPopupBox('".$item_desc."');\" onMouseOut=\"HidePopupBox();\">".$item_title."</a>";
				$return .= "</p>";

				$alt = !$alt;
			}
		}
	} else {
		$return = "<p>Error: No RSS feed supplied.</p>";
	}

	if(!$returnonly) {
		echo $return;
	}
	return $return;
}
if(!empty($_GET['style']) && ($_GET['style'] == "a")){
	$sablink = file_get_contents("php://input");
	echo sabadd($sablink);
}

if(!empty($_GET['style']) && (($_GET['style'] == "w") || ($_GET['style'] == "s"))) {
	require_once "../config.php";
	global $rssfeeds;

	$count = (!empty($_GET['c'])) ? $_GET['c'] : 10;
	if(!empty($_GET['rss']) || !empty($_GET['rssurl'])) {
		if(!empty($_GET['rssurl'])) {
			$rssfeed = array('url' => $_GET['rssurl']);
		} else {
			$rssfeed = (!empty($rssfeeds[$_GET['rss']]) ? $rssfeeds[$_GET['rss']] : array());
		}
	} else {
		$rssfeed = reset($rssfeeds);
	}

	if($_GET['style'] == "w") {
		?>
		<html>
			<head>
				<title>Media Front Page - RSS Feed</title>
				<link rel='stylesheet' type='text/css' href='css/front.css'>
			</head>
			<body>
				<?php displayRSS($rssfeed, $count); ?>
			</body>
		</html>
		<?php
	} else {
		displayRSS($rssfeed, $count);
	}
}
  
?>
