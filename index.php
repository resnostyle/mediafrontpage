<?php
  // Redirect if on a mobile browser
  require_once "m/mobile_device_detect.php";
  if( mobile_device_detect(true,true,true,true,true,true,true,false,false) ) {
    header('Location: m/');
    exit();
  }
?>

<html>
  <head>
    <title>Media Center</title>
    <link rel="shortcut icon" href="favicon.ico" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
  </head>

  <frameset rows='38, *' frameborder=0 border=0 framespacing=0>
    <frame src=nav.php name=nav noresize scrolling='no'>
    <frame src=mediafrontpage.php name=main noresize>
  </frameset>
  <noframes>
    <p>Your browser does not support frames</p>
  </noframes>
</html>
