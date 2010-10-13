<?php
// enter hostname and port of the xbmc json service here. By default 8080
$xbmcjsonservice = "http://USER:PASSWORD@localhost:8080/jsonrpc"; //remove 'USER:PASSWORD@' if your xbmc install does not require a password.
$xbmcimgpath = '/vfs/'; //leave as default if unsure

// enter path to sickbeards's coming episodes page
$sickbeardcomingepisodes = '/sickbeard/comingEpisodes/';

// enter SABnzbd+ URL and API key
$saburl = 'http://localhost:8080/sabnzbd/';  // The full URL you use to access SABnzbd.
$sabapikey = '';                             // SABnzbd's API Key found in Config>General.

// enter navigation bar links
$navlink;
$navlink["XBMC"] = "http://localhost:8080";
$navlink["Sickbeard"] = "/sickbeard";
$navlink["Couch Potato"] = "/couchpotato";
$navlink["TV Headend"] = "/tvheadend";
$navlink["Sabnzbd"] = "/sabnzbd";

// enter shortcut links for control section
$shortcut;
$shortcut["Input - XBMC"] = "/input/xbmc";
$shortcut["Input - Pay TV"] = "/input/cable";
$shortcut["Input - Games"] = "/input/games";
$shortcut["Now Playing"] = "/nowplaying";
$shortcut["Turn TV On"] = "/tv/on";
$shortcut["Turn TV Off"] = "/tv/off";
$shortcut["Turn Xbox On"] = "/xbox/on";
$shortcut["Turn Xbox Off"] = "/xbox/off";
$shortcut["Update XBMC"] = "/updatevideolibrary";

// enter directories for hard drive section
$drive;
$drive["/"] = "/";
$drive["Sata 1"] = "/media/sata1/";
$drive["Sata 2"] = "/media/sata2/";
$drive["Sata 3"] = "/media/sata3/";
$drive["Sata 4"] = "/media/sata4/";

?>
