<?php
include "../config.php";
require_once "../functions.php";

$command = $_GET["command"];
/*
	// Commands
	PlayPause,            Pauses or unpause playback
	Stop,                 Stops playback
	SkipPrevious,         Skips to previous item on the playlist
	SkipNext,             Skips to next item on the playlist
	BigSkipBackward,      
	BigSkipForward,       
	SmallSkipBackward,    
	SmallSkipForward,     
	Rewind,               Rewind current playback
	Forward,              Forward current playback
*/


//get active players
$request = '{"jsonrpc": "2.0", "method": "Player.GetActivePlayers", "id": 1}';
$results = jsoncall($request);

//Video Player
if (($results['result']['video']) == 1) {
	//get playlist items
	$player = "VideoPlayer";
//Music Player
} elseif (($results['result']['audio']) == 1) {
	//get playlist items
	$player = "AudioPlayer";
} else {
	// Nothing Playing
}

$request = '{"jsonrpc": "2.0", "method": "'.$player.'.'.$command.'", "id": 1}';
$result = jsoncall($request);

// debugging
if($_GET["debug"] == "y") {
	echo "<br/>Call: <pre>";
	echo print_r($request,1);
	echo "</pre><br/>";
	echo "<br/>Result: <pre>";
	echo print_r($result,1);
	echo "</pre><br/>";
}
?>
