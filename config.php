<?php
// enter hostname and port of the xbmc json service here. By default 8080.
$xbmcjsonservice = "http://localhost:8080/jsonrpc";

// enter navigation bar links (add as many as you like)
$navlink;
$navlink["Sickbeard"] = "/sickbeard";
$navlink["Couch Potato"] = "/couchpotato";
$navlink["TV Headend"] = "/tvheadend";
$navlink["Sabnzbd"] = "http://localhost:8081/sabnzbd";

// enter shortcut links for control section (add as many as you like)
$shortcut;
$shortcut["Input - XBMC"] = "/input/xbmc";
$shortcut["Input - Pay TV"] = "/inputpaytv";
$shortcut["Input - Games"] = "/input/games";
$shortcut["Now Playing"] = "/nowplaying";
$shortcut["Turn TV On"] = "/tv/on";
$shortcut["Turn TV Off"] = "/tv/off";
$shortcut["Turn Xbox On"] = "/xbox/on";
$shortcut["Turn Xbox Off"] = "/xbox/off";
$shortcut["Update XBMC"] = "/updatevideolibrary";

// enter directories for hard drive section (add as many as you like)
$drive;
$drive["/"] = "/";
$drive["Sata 1"] = "/media/sata1/";
$drive["Sata 2"] = "/media/sata2/";
$drive["Sata 3"] = "/media/sata3/";
$drive["Sata 4"] = "/media/sata4/";

?>
