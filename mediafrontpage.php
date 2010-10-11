<?php
 
  include "topline.php";
  include "config.php";
  include "functions.php";

  echo "<html>";
  echo "  <head>";
  echo "      <title>Media Front Page</title>";
  echo "      <link rel='stylesheet' type='text/css' href='css/front.css'>";
  echo "  </head>";
  echo "  <body>";
  echo "    <div id='main'>";
  echo "    <div id='left-sidebar'>";
  echo "      <div id=quick-links>";
  echo "        <h1>Control</h1>";
  echo "        <ul>";
  foreach( $shortcut as $shortcutlabel => $shortcutpath) {
    echo "          <li><a class='shortcut' href='".$shortcutpath."' target=middle>".$shortcutlabel."</a><br/></li>";
  }
  echo "        </ul>";
  echo "      </div>";

  // show drive usage
  echo "      <div id=hdstats>";
  echo "        <h1>Hard Drives</h1>";
  echo "        <table border='0'>";
  echo "          <tr>";
  echo "            <th>Disk</th>";
  echo "            <th>Capacity</th>";
  echo "            <th>Remaining</th>";
  echo "            <th>%</th>";
  echo "          </tr>";
  foreach( $drive as $drivelabel => $drivepath) {
    echo "          <tr>";
    echo "            <td>".$drivelabel."</td>";
    echo "            <td>".to_readable_size(disk_total_space($drivepath))."</td>";
    echo "            <td>".to_readable_size(disk_free_space($drivepath))."</td>";
    echo "            <td><div class='harddrive'><div class='usage' style='width:".(disk_used_percentage($drivepath))."%';</div></div></td>";
    echo "          </tr>";
  }
  echo "        </table>";
  echo "      </div>";

   //json rpc call procedure
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_URL, $xbmcjsonservice);

  //now playing section
  echo "      <div id='nowplaying'>";
  echo "        <h1>Now Playing</h1>";

  //get active players
  $request = '{"jsonrpc": "2.0", "method": "Player.GetActivePlayers", "id": 1}';
  curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
  $results = json_decode(curl_exec($ch),true);

  //video Player
   if (($results['result']['video']) == 1) {
     echo "Video Player active"; echo "<br><br>"; 
     echo "        <tr><td>$activeplayer</td></tr>";
   }

   elseif (($results['result']['audio']) == 1) {

     //get playlist items
     $request = '{"jsonrpc": "2.0", "method": "AudioPlaylist.GetItems", "params": { "fields": ["title", "album", "artist", "duration"] }, "id": 1}';
     curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
     $results = json_decode(curl_exec($ch),true);
     $items = $results['result']['items'];
     $current = $results['result']['current'];
     
     $thumb = $items[$current]['thumbnail'];
     $artist = $items[$current]['artist'];
     $title = $items[$current]['title'];
     $album = $items[$current]['album'];
     echo "        <img src=".$xbmcimgpath.$thumb."></img>";
     echo "        <p>".$artist."</p>";
     echo "        <p>".$title."</p>";
     echo "        <p>".$album."</p>";
     echo "        <p>1:10 - 2:56</p>";

     //progress bar
     $request = '{"jsonrpc": "2.0", "method": "AudioPlayer.GetPercentage", "id": 1}';
     curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
     $results = json_decode(curl_exec($ch),true);
     $percentage = $results['result'];
    echo "        <div class='progressbar'><div class='progress' style='width:".$percentage."%';</div></div>";
   }
   else {
     echo "Nothing Playing";
   } 
  echo "      </div>";
  echo "    </div>";

  //iframe
  echo "    <div id='middle'>";
  echo "      <iframe src ='sickbeardframe' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>";
  echo "        <p>Your browser does not support iframes.</p>";
  echo "      </iframe>";
  echo "    </div>";

  //recent tv section
  echo "    <div id='right-sidebar'>";
  echo "    <div id=recent-tv>";
  echo "      <h1>Recent TV Shows</h1>";

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
      $display = $showtitle." - ".$season."x".$episode." - ".$label;
      echo "<a href=\"recentepisodes.php?episode=$label2\" target='middle'>$display</a><br/>";
    }
  }
  echo "    </div>";
  echo "    <div id=recentmovies>";
  echo "      <h1>Recent Movies</h1>";

  //get the results from the directory
  $request2 = '{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedMovies", "params" : { "start" : 0 , "end" : 10 , "fields" : [ "year" ] }, "id" : 1 }';
  curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
  $array2 = json_decode(curl_exec($ch),true);

  //query below contains movies
  $results = $array2['result']['movies'];

  if (!empty($results)) {

    foreach ($results as $value) {
      $movie = $value['label'];
      $movie1 = $movie." ".$value['year'];
      $movie2 = urlencode($movie1);
      $display = $movie." &nbsp;(".$value['year'].")";
      echo "<a href=\"movieinfo?movie=$movie2\" class='recent-movie' target='middle'>$display</a><br/>";
    }
  }
  echo "    </div>";
  echo "    </div>";
  echo "  </body>";
  echo "</html>";
?>
