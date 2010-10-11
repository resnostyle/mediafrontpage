<?php

  include "topline.php";
  include "config.php";
  include "functions.php";

  //get arguments
  if(empty($_GET['start'])) {
    $start = 0;
  } elseif( $_GET['start'] >= 0) {
    $start = $_GET['start'];
  }
  if (empty($_GET['end'])) {
    $end = $start + 15;
  } elseif( $_GET['end'] > 0) {
    $end = $_GET['end'];
  }

  //json rpc call procedure
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_URL, $xbmcjsonservice);

  //get the results from the directory
  $request2 = '{"jsonrpc" : "2.0", "method" : "VideoLibrary.GetRecentlyAddedEpisodes", "params" : { "start" : ' . $start . ', "end" : ' . $end . ', "fields": [ "showtitle", "season", "episode" ] }, "id" : 1 }';

  curl_setopt($ch, CURLOPT_POSTFIELDS, $request2);
  $array2 = json_decode(curl_exec($ch),true);

  //query below contains episodes
  $xbmcresults = $array2['result'];

  //play selected video
  if(!empty($_GET['episode'])) {

    //get selected video
    $playvideo = $_GET['episode'];

    //get filenames from results
    $episodes = $xbmcresults['episodes'];

    //set i at 0
    $i = 0;

    //For Each file in the directory
    foreach ($episodes as $value) {
      $video = $value['label'];

      //Check if label equals selected video
      if ($video == $playvideo) {

        //Get location of selected video
        $arrayvideos = $episodes[$i];
        $videolocation = $arrayvideos['file'];

        //Play video
        $request = '{"jsonrpc" : "2.0", "method": "XBMC.Play", "params" : { "file" : "' . $videolocation . '"}, "id": 1}';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $array = json_decode(curl_exec($ch),true);
      }

      //increase value of i by 1
      $i++;
    }
  }
  
  echo "<div id=\"utility\"><ul>";

  if (array_key_exists('episodes', $xbmcresults)) {
    $episodes = $xbmcresults['episodes'];
    foreach ($episodes as $value) {
      $label = $value['label'];
      $label2 = urlencode($label);
      $showtitle = $value['showtitle'];
      $season = $value['season'];
      $episode = $value['episode'];
      echo "<li><a href=getrecentepisodes.php?episode=$label2&start=$start&end=$end>$showtitle - ".$season."x".$episode." - $label</a></li>";
    }
  }

  echo "</ul></div>";

include "downline.php";
?>
