<?php
$wdgtNowPlayingAjax = array("type" => "ajax", "block" => "nowplayingwrapper", "call" => "ajax/nowplaying.php", "interval" => 1000);
$wdgtNowPlayingControls = array("type" => "inline", "function" => "widgetNowPlayingControls();", "headerfunction" => "widgetNowPlayingHeader();");
$wdgtNowPlaying = array("type" => "mixed", "parts" => array($wdgtNowPlayingAjax, $wdgtNowPlayingControls));
function widgetNowPlayingControls() {
	echo <<< NOWPLAYINGCONTROLS
        <a class='controlbutton' onclick='cmdNowPlaying("PlayPause");' href='#'><img src='media/btnPlayPause.png' alt='Play/Pause'/></a>
		<a class='controlbutton' onclick='cmdNowPlaying("Stop");' href='#'><img src='media/btnStop.png' alt='Stop'/></a>
NOWPLAYINGCONTROLS;
}
function widgetNowPlayingHeader() {
	echo <<< NOWPLAYINGHEADER
		<script type="text/javascript" language="javascript">
		<!--
			function cmdNowPlaying(cmd) {
				var cmdPlayingRequest = new ajaxRequest();
				cmdPlayingRequest.open("GET", "ajax/remoteexec.php?command="+cmd, true);
				cmdPlayingRequest.send(null);
			}
		-->
		</script>

NOWPLAYINGHEADER;
}
?>