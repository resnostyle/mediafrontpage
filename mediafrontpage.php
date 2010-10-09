<?php
 
  include "config.php";
  
  function to_readable_size($size) {
    switch (true) {
      case ($size > 1000000000000):
            $size /= 1000000000000;
            $suffix = 'Tb';
      break;
      case ($size > 1000000000):  
            $size /= 1000000000;
            $suffix = 'Gb';
      break;
      case ($size > 1000000):
            $size /= 1000000;
            $suffix = 'Mb';   
      break;
      case ($size > 1000):
            $size /= 1000;
            $suffix = 'Kb';
      break;
      default:
        $suffix = 'b';
    }
    return round($size, 0)." ".$suffix;
  }
  
  function disk_used_space($value) {
    return disk_total_space("$value") - disk_free_space("$value");
  }

  function disk_used_percentage($value) {
    return round(disk_used_space("$value") / disk_total_space("$value") * 100, 2);
  }

  echo "<html>";
  echo "  <head>";
  echo "      <title>Media Front Page</title>";
  echo "      <link rel='stylesheet' type='text/css' href='css/front.css'>";
  echo "  </head>";
  echo "  <body>";
  echo "    <div id=quick-links>";
  echo "      <h1>Control</h1>";
  echo "        <ul>";
  foreach( $shortcut as $shortcutlabel => $shortcutpath) {
    echo "          <li><a class='shortcut' href='".$shortcutpath."' target=middle>".$shortcutlabel."</a><br/></li>";
  }
  echo "      </ul>";
  echo "    </div>";

  // show drive usage
  echo "    <div id=hdstats>";
  echo "      <h1>Hard Drives</h1>";
  echo "      <table border='0' width='300px'>";
  echo "        <tr>";
  echo "          <th>Disk</th>";
  echo "          <th>Capacity</th>";
  echo "          <th>Remaining</th>";
  echo "          <th>%</th>";
  echo "        </tr>";

  foreach( $drive as $drivelabel => $drivepath) {
    echo "        <tr>";
    echo "          <td>".$drivelabel."</td>";
    echo "          <td>".to_readable_size(disk_total_space($drivepath))."</td>";
    echo "          <td>".to_readable_size(disk_free_space($drivepath))."</td>";
    echo "          <td><div class='dd'><div class='blue' style='width:".(disk_used_percentage($drivepath))."%';</div></div></td>";
    echo "        </tr>";
  }
  echo "      </table>";
  echo "    </div>";
  echo "    <div id=recent-tv>";
  echo "      <h1>Recent TV Shows</h1>";
  echo "      <table border='0' width='300px'>";

   //json rpc call procedure
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_URL, $xbmcjsonservice);

  //get the recent episodes
  $request2 = '{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : 0 , "end" : 10 , "fields": [ "showtitle", "season", "episode" ] }, "id" : 1 }';

  curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
  $array2 = json_decode(curl_exec($ch),true);

  //query below contains episodes
  $xbmcresults = $array2['result'];
  if (array_key_exists('episodes', $xbmcresults)) {
    $episodes = $xbmcresults['episodes'];
    foreach ($episodes as $value) {
      $label = $value['label'];
      $label2 = urlencode($label);
      $showtitle = $value['showtitle'];
      $season = $value['season'];
      $episode = $value['episode'];
      echo "<tr><td><a href=\"recentepisodes.php?episode=$label2\" target='middle'>$showtitle - ".$season."x".$episode." - $label</a></td></tr>";
    }
  }
  echo "      </table>";
  echo "    </div>";
  echo "    <div id=recentmovies>";
  echo "      <h1>Recent Movies</h1>";
  echo "      <table border='0' width='250px'>";

  //get the results from the directory
  $request2 = '{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedMovies", "params" : { "start" : 0 , "end" : 10 }, "id" : 1 }';
  curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
  $array2 = json_decode(curl_exec($ch),true);

  //query below contains movies
  $xbmcresults = $array2['result'];

  if (array_key_exists('movies', $xbmcresults)) {
    $movies = $xbmcresults['movies'];
    foreach ($movies as $value) {
      $label = $value['label'];
      $display = urlencode($value['file']);
      $label2 = urlencode($label);
      echo "<tr><td><a href=\"recentmovies.php?movie=$label2\" class='recent-movie' target='middle'>$label</a></td></tr>";
    }
  }

  echo "      </table>";
  echo "    </div>";
  echo "    <div id='upcoming-frame'>";
  echo "      <iframe src ='/sickbeard/comingEpisodes' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
  echo "        <p>Your browser does not support iframes.</p>";
  echo "      </iframe>";
  echo "    </div>";
  echo "  </body>";
  echo "</html>";
?>
