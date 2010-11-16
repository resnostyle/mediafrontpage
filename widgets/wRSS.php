<?php

$wIndex["wRSS"] = array("name" => "RSS Feed", "type" => "inline", "function" => "widgetRSS();"); //Declare widget function

function widgetRSS() {

echo "<script type=\"text/javascript\">\n"; 
echo "function showRSS(str)\n"; 
echo "{\n"; 
echo "if (str.length==0)\n"; 
echo "  { \n"; 
echo "  document.getElementById(\"rssOutput\").innerHTML=\"\";\n"; 
echo "  return;\n"; 
echo "  }\n"; 
echo "if (window.XMLHttpRequest)\n"; 
echo "  {// code for IE7+, Firefox, Chrome, Opera, Safari\n"; 
echo "  xmlhttp=new XMLHttpRequest();\n"; 
echo "  }\n"; 
echo "else\n"; 
echo "  {// code for IE6, IE5\n"; 
echo "  xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");\n"; 
echo "  }\n"; 
echo "xmlhttp.onreadystatechange=function()\n"; 
echo "  {\n"; 
echo "  if (xmlhttp.readyState==4 && xmlhttp.status==200)\n"; 
echo "    {\n"; 
echo "    document.getElementById(\"rssOutput\").innerHTML=xmlhttp.responseText;\n"; 
echo "    }\n"; 
echo "  }\n"; 
echo "xmlhttp.open(\"GET\",\"getrss.php?q=\"+str,true);\n"; 
echo "xmlhttp.send();\n"; 
echo "}\n"; 
echo "</script>\n"; 
echo "</head>\n"; 
echo "<body>\n"; 
echo "\n"; 
echo "10 Most Recent - <form>\n"; 
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
echo "<br />\n"; 
echo "<div id=\"rssOutput\">RSS-feed will take a few seconds to load...</div>\n";
}

?>
