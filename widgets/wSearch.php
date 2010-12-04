<?php
$wdgtSearch = array("name" => "Search", "type" => "inline", "function" => "widgetSearch();");
$wIndex["wSearch"] = $wdgtSearch;

function widgetSearch() {
	global $nzbusername, $nzbapi,$saburl,$sabapikey;
	
	if(empty($_POST['search'])){	
	echo "<form method=\"post\"><input type=\"text\" name=\"search\" />
		<select name=\"type\">
		<option value=\"\">ALL</option>
		<option value=\"1\">Movies: DVD</option>
		<option value=\"2\">Movies: DIVX</option>
		<option value=\"54\">Movies: BrRip</option>
		<option value=\"42\">Movies: HD x264</option>
		<option value=\"5\">TV: DVD</option>
		<option value=\"6\">TV: DivX</option>
		<option value=\"41\">TV: HD</option>
		<option value=\"7\">TV: Sports</option>
		<option value=\"9\">Documentaries</option>
		<option value=\"53\">Documentaries: HD</option>
		<option value=\"22\">MP3 Albums</option>
		<option value=\"47\">MP3 Singles</option>
		</select>	
		<input type=\"submit\" name=\"submit\" value=\"Search\" />
		</form>";
	}
	else{
		$item = $_POST['search'];
		echo "<form method=\"post\"><input type=\"text\" name=\"search\" value=\"$item\" />
		<select name=\"type\">
		<option value=\"\">ALL</option>
		<option value=\"1\">Movies: DVD</option>
		<option value=\"2\">Movies: DIVX</option>
		<option value=\"54\">Movies: BrRip</option>
		<option value=\"42\">Movies: HD x264</option>
		<option value=\"5\">TV: DVD</option>
		<option value=\"6\">TV: DivX</option>
		<option value=\"41\">TV: HD</option>
		<option value=\"7\">TV: Sports</option>
		<option value=\"9\">Documentaries</option>
		<option value=\"53\">Documentaries: HD</option>
		<option value=\"22\">MP3 Albums</option>
		<option value=\"47\">MP3 Singles</option>
		</select>	
	    <input type=\"submit\" name=\"submit\" value=\"Search\" />
		</form>";
		$type = "";
		if(!empty($_POST['type'])){
			$type = "&catid=".$_POST['type'];
		}
		$search = "http://api.nzbmatrix.com/v1.1/search.php?search=".urlencode($item).$type."&username=".$nzbusername."&apikey=".$nzbapi;
		$content = file_get_contents($search);
		$itemArray = explode('|',$content);
			foreach($itemArray as &$item){
					$item = explode(';',$item);
/*
					foreach($item as &$value){
					echo $value;
					echo "</br>";
					}
*/					
					$id = (string)$item[0];
					$name = (string)$item[1];
					$link = (string)$item[2];
					$size = 0+substr($item[3], 6);
					$size = to_readable_size($size);
					$cat = $item[6];
					$url=$saburl."api?mode=addurl&name=http://www.".substr($link,6)."&nzbname=".urlencode(substr($name,9))."&apikey=".$sabapikey;
					
					
					// Movies --> $_POST['type']==1||$_POST['type']==2||$_POST['type']==54||$_POST['type']==42||$_POST['type']==9||$_POST['type']==53||
					// TV  --> $_POST['type']==5||$_POST['type']==41||$_POST['type']==7||$_POST['type']==6||
					// Music --> $_POST['type']==22||$_POST['type']==47||
					if(strpos($cat, "Movies")!=false||strpos($cat, "Documentaries")!=false){
						$sabcat="movies";
					}
					elseif(strpos($cat, "TV")!=false){
						$sabcat="tv";
					}
					elseif(strpos($cat, "Music")!=false){
						$sabcat="music";
					}
					else $sabcat="";	
							
					
					if(!empty($sabcat)){
						$url .="&cat=".$sabcat;
					}
					
					
					$popup =(print_r($id,true)."<br>".print_r($name,true));
					$nzblink = "http://www.".substr($link,6);
					if(strlen($name)!=0){
					echo "<a href=$url; target='nothing';><img class=\"sablink\" src=\"../media/sab2_16.png\" alt=\"Download with SABnzdd+\"/></a><a href=\"$nzblink\"; onMouseOver=\"ShowPopupBox('".$popup."');\" onMouseOut=\"HidePopupBox();\">".substr($name,9)."</a><br>$size<br>$cat<br><br>";
					}
				}
	}
	
}
?>
		<html>
			<head>
				<title>Media Front Page - Search Widget</title>
				<link rel='stylesheet' type='text/css' href='css/front.css'>
				<body>
				<iframe name="nothing" height="0" width="0" style="visibility:hidden;display:none"></iframe> 
				</body>
			</head>
		</html>
