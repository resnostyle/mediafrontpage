<?php

  include "topline.php";
  include "config.php";

  echo "<html>";
  echo "  <head>";
  echo "    <title>Media Center</title>";
  echo "    <link rel='stylesheet' type='text/css' href='css/front.css'>";
  echo "  </head>";
  echo "  <body>";
  echo "    <div id='sickbeard-iframe'>";
  echo "      <iframe src=".$sickbeardcomingepisodes." name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
  echo "        <p>Your browser does not support iframes.</p>";
  echo "      </iframe>";
  echo "    </div>";
  echo "  </body>";
  echo "</html>";
?>
