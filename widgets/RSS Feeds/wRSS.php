<?php

$widget_init = array(	'Id' 			=> "wRSS", 
			'Child'			=> "false",
			'Type' 			=> "inline", 
			'Title' 		=> "RSS Feeds", 
			'Function' 		=> "widgetRSS();",
			'HeaderFunction' 	=> "widgetRSSHeader();", 
			'Stylesheet' 		=> "",
			'Section' 		=> 3, 
			'Position' 		=> 4,
			'Parts'			=> "",
			'Block' 		=> "",  
			'Call'			=> "",
			'Loader'		=> "",
			'Interval'		=> "",
			'Script'		=> ""
		    );

function widgetRSSHeader() {
	echo <<< RSSHEADER
<script type="text/javascript" language="javascript">
	<!--
		function showRSS(str) {
			if (str.length==0) {
				document.getElementById("rssList").innerHTML="";
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
					document.getElementById("rssList").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","widgets/RSS Feeds/wRSS.php?style=s&rss="+str,true);
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
			xmlhttp.open("POST", "widgets/RSS Feeds/wRSS.php?style=a", true);

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
function widgetRSS() {
	global $rssfeeds;

	echo "<form>\n";
	echo "\t<select onchange=\"showRSS(this.value);\">\n";
	foreach($rssfeeds as $name => $feed) {
		echo "\t\t<option value=\"".$name."\">".$name."</option>\n";
	}
	echo "\t</select>\n";
	echo "</form>\n";
	echo "<div id=\"rssList\">";
	displayRSS(reset($rssfeeds));
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
function displayRSS($rssfeed, $count = 10, $returnonly = false) {
	$return = "";
	if(!empty($rssfeed['url'])) {
		$xmlDoc = new DOMDocument();
		$xmlDoc->load($rssfeed['url']);

		//get elements from "<channel>"
		//$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
		//$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		//$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		//$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

		//output elements from "<channel>"
		//$return .= "<p><a href='".$channel_link."'>".$channel_title."</a></p>");

		if(!empty($rssfeed['type']) && ($rssfeed['type'] == 'atom')) {
			//get and output "<item>" elements
			$x = $xmlDoc->getElementsByTagName('entry');
		} else {
			//get and output "<item>" elements
			$x = $xmlDoc->getElementsByTagName('item');
		}
		$alt = false;
		for ($i=0; $i<$count; $i++){
			if(!empty($rssfeed['type']) && ($rssfeed['type'] == 'atom')) {
				$item_title = $x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
				$item_desc = $x->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue;
				$item_desc = str_replace("  ", " ", $item_desc);
				$item_desc = str_replace("\n\n", "\n", $item_desc);
				$item_desc = str_replace("\n", " <br/>", $item_desc);
				$item_link = $x->item($i)->getElementsByTagName('link')->item(0)->getAttribute('href');
			} else {
				$item_title = $x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
				$item_link = $x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
				$item_desc = $x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
			}
			$item_desc = str_replace("'", "", str_replace("\"", "'", $item_desc));
			$return .= "<p class=\"".($alt ? " alt" : "")."\">";

			if(!empty($rssfeed['cat']) || !empty($rssfeed['script']) || !empty($rssfeed['pp']) || !empty($rssfeed['priority'])) {
				$return .= "<a href=\"#\" class=\"sablink\" onclick=\"sabAddUrl('".htmlentities(sab_addurl($item_link, $item_title, $rssfeed))."'); return false;\">";
				$return .= "<img class=\"sablink\" src=\"media/sab2_16.png\" alt=\"Download with SABnzdd+\"/>";
				$return .= "</a>";
			}

			$return .= "<a href=\"".$item_link."\" target=\"_blank\" onMouseOver=\"ShowPopupBox('".$item_desc."');\" onMouseOut=\"HidePopupBox();\">".$item_title."</a>";
			$return .= "</p>";

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
if(!empty($_GET['style']) && ($_GET['style'] == "a")){
	$sablink = file_get_contents("php://input");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_URL, $sablink);

	$response = curl_exec($ch);
	curl_close($ch);

	echo $response;
}

if(!empty($_GET['style']) && (($_GET['style'] == "w") || ($_GET['style'] == "s"))) {
	require_once "../../config.php";
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
