<?php
// Only set the $mfpsecured variable to true if you have secured
// MediaFrontPage with a password via .htaccess or some other method
// use at your own risk as this can create a security vulnerability in
// the wControl widget.
$mfpsecured = false;

// Alternativly you can set a unique key here.
$mfpapikey = '';  //

// enter hostname and port of the xbmc json service here. By default 8080
$xbmcjsonservice = "http://USER:PASSWORD@localhost:8080/jsonrpc"; //remove 'USER:PASSWORD@' if your xbmc install does not require a password.
$xbmcimgpath = 'http://localhost:8080/vfs/'; //leave as default if unsure

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
$navlink["Settings"] = "settings.php";

// enter rss feeds. Ensure sabnzbd > config > index sites is set. Supports cat, pp, script, priority as per the sabnzbd api.
$rssfeeds["NZBMatrix - TV Shows (DivX)"]    = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=6", "cat" => "tv");
$rssfeeds["NZBMatrix - TV Shows (HD x264)"] = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=41", "cat" => "tv");
$rssfeeds["NZBMatrix - Movies (DivX)"]      = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=2", "cat" => "movies");
$rssfeeds["NZBMatrix - Movies (HD x264)"]   = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=42", "cat" => "movies");
$rssfeeds["NZBMatrix - Music (MP3)"]        = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=22", "cat" => "music");
$rssfeeds["NZBMatrix - Music (Loseless)"]   = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=23", "cat" => "music");
$rssfeeds["NZBMatrix - Sports"]             = array("url" => "http://rss.nzbmatrix.com/rss.php?subcat=7", "cat" => "sports");
$rssfeeds["MediaFrontPage on Github"]       = array("url" => "https://github.com/nick8888/mediafrontpage/commits/master.atom", "type" => "atom");

$customStyleSheet = "";
//Example of how to use this
//$customStyleSheet = "css/lighttheme.css";

?>
