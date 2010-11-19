<?php
 
  include "config.php";
  echo"<html>";
  echo"  <head>";
  echo"    <title>Navigation</title>";
  echo"    <link rel='stylesheet' type='text/css' href='css/nav.css'>";
  echo"  </head>";
  echo"  <body>";
  echo"    <div id=header>";
  echo"      <div id=home>";
  echo"        <a href='./mediafrontpage.php' target='main'></a>";
  echo"      </div>";
  echo"      <div id=nav-menu>";
  echo"        <ul>";
  foreach( $navlink as $navlinklabel => $navlinkpath) {
    echo"          <li><a href='".$navlinkpath."' target=main>".$navlinklabel."</a></li>";
  }
  echo"        </ul>";
  echo"      </div>";
  echo"    </div>";
  echo"  </body>";
  echo"</html>";
