<?php

  include "topline.php";
  include "config.php";
  include "functions.php";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_URL, $xbmcjsonservice);

  //prepare the field values being posted to the service
  $request = '{"jsonrpc": "2.0", "method": "VideoLibrary.GetMovies", "params" : { "sortorder" : "ascending", "fields" : [ "genre", "director", "trailer", "tagline", "plot", "plotoutline", "title", "originaltitle", "lastplayed", "runtime", "year", "playcount", "rating"] }, "id": 1}';
  curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
  $array = json_decode(curl_exec($ch),true);

  //results movies
  $results = $array['result']['movies'];

  if(!empty($_GET['play'])) {

    //get selected video
    $playmovie = $_GET['play'];

    $i = 0;

    //For Each file in the directory
    foreach ($results as $value) {
      $movie10 = $value['label'];
      $year10 = $value['year'];
      $movie11 = $movie10." ".$year10;
      $movie12 = urlencode($movie11);

      //Check if label equals selected video
      if ($movie11 == $playmovie) {
        //Get location of selected video
        $arrayvideos = $results[$i];
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

  if(!empty($_GET['movie']) || !empty($_GET['play'])) {
    $movie = $_GET['movie'].$_GET['play'];
    $i = 0;

    //For Each file in the directory
    foreach ($results as $value) {
      $movielabel = $value['label'];
      $movieyear = $value['year'];
      $movie2 = $movielabel." ".$movieyear;

      //Check if label equals selected video
      if ($movie == $movie2) {

        //Get location of selected video
        $moviearray = $results[$i];
        $videolocation = $moviearray['file'];

        //show movie info
        echo "<div id='movies'>";
        $moviename = $moviearray['label'];
        $movieyear = $moviearray['year'];
        $moviename2 = $moviename." ".$movieyear;
        $movieurl = urlencode($moviename2);
        $movieplot = $moviearray['plot'];
        $moviegenre = $moviearray['genre'];
        $moviefanart = $moviearray['fanart'];
        $moviethumb = $moviearray['thumbnail'];
        echo "<div class='movietitle'><h1>".$moviename." &nbsp;(".$movieyear.")</h1></div>";
        echo "<div class='movieinfo'><img src='".$xbmcimgpath.$moviethumb."'></img>";
        echo "<p>".$movieplot."</p>";
        echo "<div class='movieoptions'><a href=movieinfo.php?play=$movieurl>Play</a></div>";
        echo "</div>";
      } 

      //increase value of i by 1
      $i++;
    }
  } 
  else {
    echo "No movie specified";
  }

  include "downline.php";

?>
