<?php
// enter hostname and port of the xbmc json service here. By default 8080
$xbmcjsonservice = "http://USER:PASSWORD@localhost:8080/jsonrpc"; //remove 'USER:PASSWORD@' if your xbmc install does not require a password.
$xbmcimgpath = '/vfs/'; //leave as default if unsure

$xbmcdbconn = array(
		'video' => array('dns' => 'sqlite:/home/xbmc/.xbmc/userdata/Database/MyVideos34.db', 'username' => '', 'password' => '', 'options' => array()),
		'music' => array('dns' => 'sqlite:/home/xbmc/.xbmc/userdata/Database/MyMusic7.db', 'username' => '', 'password' => '', 'options' => array()),
	);
//Example of mysql connections
/*
$xbmcdbconn = array(
		'video' => array(
			'dns' => 'mysql:host=hostname;dbname=videos',
			'username' => '',
			'password' => '',
			'options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
		),
		'music' => array(
			'dns' => 'mysql:host=hostname;dbname=music',
			'username' => 'username',
			'password' => 'password',
			'options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
		),
		
	);
*/

// enter path to sickbeards's coming episodes page
$sickbeardcomingepisodes = 'http://user:password@COMPUTER:PORT/sickbeard/comingEpisodes/';
$sickbeardurl = "http://user:password@COMPUTER:PORT/sickbeard/";

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
$shortcut["Shutdown XBMC"] = array("cmd" => 'shutdown');
$shortcut["Update XBMC Video Library"] = array("cmd" => 'vidscan');
$shortcut["Update XBMC Audio Library"] = array("json" => '{"jsonrpc": "2.0", "method": "AudioLibrary.ScanForContent", "id" : 1 }');
$shortcut["Google"] = "http://www.google.com/";
/*
$shortcut["Input - XBMC"] = "/input/xbmc";
$shortcut["Input - Pay TV"] = "/input/cable";
$shortcut["Input - Games"] = "/input/games";
$shortcut["Now Playing"] = "/nowplaying";
$shortcut["Turn TV On"] = "/tv/on";
$shortcut["Turn TV Off"] = "/tv/off";
$shortcut["Turn Xbox On"] = "/xbox/on";
$shortcut["Turn Xbox Off"] = "/xbox/off";
*/

// enter directories for hard drive section
$drive;
$drive["/"] = "/";
$drive["Sata 1"] = "/media/sata1/";
$drive["Sata 2"] = "/media/sata2/";
$drive["Sata 3"] = "/media/sata3/";
$drive["Sata 4"] = "/media/sata4/";

$customStyleSheet = "";
//Example of how to use this
//$customStyleSheet = "css/lighttheme.css";

?>
