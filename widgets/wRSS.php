<?php

$wIndex["wRSS"] = array("name" => "RSS Feed", "type" => "inline", "function" => "widgetRSS();"); //Declare widget function

function widgetRSS() { 
	$xml = parseRSS("http://rss.nzbmatrix.com/rss.php?subcat=42");

	foreach($xml['RSS']['CHANNEL']['ITEM'] as $item) {
	        echo("<p class=\"indexBoxNews\"><a href=\"{$item['LINK']}\" target=\"_blank\" class=\"indexBoxNews\">{$item['TITLE']}{$link}</a></p>");
	}
}
function parseRSS($url) { 
 
        $feedeed = implode('', file($url));
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $feedeed, $valueals, $index);
        xml_parser_free($parser);
 
        foreach($valueals as $keyey => $valueal){
            if($valueal['type'] != 'cdata') {
                $item[$keyey] = $valueal;
			}
        }
 
        $i = 0;
 
        foreach($item as $key => $value){
 
            if($value['type'] == 'open') {
 
                $i++;
                $itemame[$i] = $value['tag'];
 
            } elseif($value['type'] == 'close') {
 
                $feed = $values[$i];
                $item = $itemame[$i];
                $i--;
 
                if(count($values[$i])>1){
                    $values[$i][$item][] = $feed;
                } else {
                    $values[$i][$item] = $feed;
                }
 
            } else {
                $values[$i][$value['tag']] = $value['value'];  
            }
        }
 
        return $values[0];
} 


 

?>
