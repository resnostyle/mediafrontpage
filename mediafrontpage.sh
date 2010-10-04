#!/bin/sh

#Settings
webroot="/var/www"     				# eg "/var/www"
tvdir="/media/sata4/TV Shows"  				# will probably only work with sickbeard
moviedir="/media/sata3/Movies/"				# only tested with "/movies/title (year)"
recordingsdir="/media/sata2/Videos/Recordings"		# searches for mkv's (only tested with tvheadend)

navlink1="/xbmc"					# must be path to xbmc if you want recent movie links to work
navlinkname1="XBMC"
navlink2="/sickbeard"
navlinkname2="TV Shows"
navlink3="/movies"
navlinkname3="Movies"
navlink4="/tvheadend"
navlinkname4="PVR"
navlink5="/sabnzbd"
navlinkname5="Downloads"
navlink6="/transmission/web"
navlinkname6="Torrents"
navlink7="xbmc/db"
navlinkname7="XBMC DB"
navlink8="/mythweb"
navlinkname8="Myth Web"


quicklink1="http://192.168.1.9:8089"
quicklinkname1="Quick Link Example 1"
quicklink2="http://192.168.1.9:8089/sickbeard/home/updateXBMC/"
quicklinkname2="Update XBMC"
quicklink3="http://mydomain.com/tv/on"
quicklinkname3="Turn TV On"
quicklink4="http://mydomain.com/tv/off"
quicklinkname4="Turn TV Off"
quicklink5="http://forum.xbmc.org"
quicklinkname5="XBMC Forum"
quicklink6="http://192.168.1.9:8089"
quicklinkname6="Quick Link Example 6"
quicklink7="http://192.168.1.9:8089"
quicklinkname7="Quick Link Example 7"
quicklink8="http://192.168.1.9:8089"
quicklinkname8="Quick Link Example 8"


fs="/dev/sda1"
fsname="/"
hd1="/dev/sda6"
hdname1="Sata 1"
hd2="/dev/sdc"
hdname2="Sata 2"
hd3="/dev/sdb"
hdname3="Sata 3"
hd4="/dev/sdd"
hdname4="Sata 4"


#Start index.html
echo "<html>" >  $webroot/index.html
echo "  <head>" >>  $webroot/index.html
echo "    <title>Media Center</title>" >>  $webroot/index.html
echo "  </head>" >>  $webroot/index.html
echo "  <frameset rows='6%, 94%' frameborder="0" border="0" framespacing="0">" >>  $webroot/index.html
echo "    <frame src="/nav.html" name="nav" noresize scrolling='no'>" >>  $webroot/index.html
echo "    <frame src="/front.html" name="main" noresize>" >>  $webroot/index.html
echo "  </frameset>" >>  $webroot/index.html
echo "  <noframes>" >>  $webroot/index.html
echo "    <a href="$navlink1">$navlinkname1</a><br/>" >>  $webroot/index.html
echo "    <a href="$navlink2">$navlinkname2</a><br/>" >>  $webroot/index.html
echo "    <a href="$navlink3">$navlinkname3</a><br/>" >>  $webroot/index.html
echo "    <a href="$navlink4">$navlinkname4</a><br/>" >>  $webroot/index.html
echo "    <a href="$navlink5">$navlinkname5</a><br/>" >>  $webroot/index.html
echo "    <a href="$navlink6">$navlinkname6</a><br/>" >>  $webroot/index.html
echo "  </noframes>" >>  $webroot/index.html
echo "</html>" >>  $webroot/index.html

#Start nav.html
echo "<html>" >  $webroot/nav.html
echo "  <head>" >>  $webroot/nav.html
echo "    <title>Navigation</title>" >>  $webroot/nav.html
echo "    <link rel="stylesheet" type="text/css" href="/css/nav.css">" >>  $webroot/nav.html
echo "  </head>" >>  $webroot/nav.html
echo "  <body>" >>  $webroot/nav.html
echo "    <div id="header">" >>  $webroot/nav.html
echo "      <div id="home">" >>  $webroot/nav.html
echo "        <a href="/front.html" target="main">Home</a>" >>  $webroot/nav.html
echo "      </div>" >>  $webroot/nav.html
echo "      <div id="nav-menu">" >>  $webroot/nav.html
echo "        <ul>" >>  $webroot/nav.html
echo "          <li><a href="$navlink1" target="main">$navlinkname1</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink2" target="main">$navlinkname2</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink3" target="main">$navlinkname3</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink4" target="main">$navlinkname4</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink5" target="main">$navlinkname5</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink6" target="main">$navlinkname6</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink7" target="main">$navlinkname7</a></li>" >>  $webroot/nav.html
echo "          <li><a href="$navlink8" target="main">$navlinkname8</a></li>" >>  $webroot/nav.html
echo "        </ul>" >>  $webroot/nav.html
echo "      </div>" >>  $webroot/nav.html
echo "    </div>" >>  $webroot/nav.html
echo "  </body>" >>  $webroot/nav.html
echo "</html> " >>  $webroot/nav.html

#Start front.html
echo "<html>" >  $webroot/front.html
echo "  <head>" >>  $webroot/front.html
echo "    <title>" >>  $webroot/front.html
echo "      Media Front Page" >>  $webroot/front.html
echo "    </title>" >>  $webroot/front.html
echo "<link rel='stylesheet' type='text/css' href='css/front.css'>" >>  $webroot/front.html
echo "  </head>" >>  $webroot/front.html
echo "  <body>" >>  $webroot/front.html


#Quick Links
echo "  <div id="quick-links">" >>  $webroot/front.html
echo "    <h1>Quick Links</h1>" >>  $webroot/front.html
echo "    <ul>" >>  $webroot/front.html

echo "      <li><a class='quick-links' href='$quicklink1'>$quicklinkname1</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink2'>$quicklinkname2</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink3'>$quicklinkname3</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink4'>$quicklinkname4</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink5'>$quicklinkname5</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink6'>$quicklinkname6</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink7'>$quicklinkname7</a><br/></li>" >>  $webroot/front.html
echo "      <li><a class='quick-links' href='$quicklink8'>$quicklinkname8</a><br/></li>" >>  $webroot/front.html

echo "    </ul>" >>  $webroot/front.html
echo "  </div>" >>  $webroot/front.html

#HD Stats
echo "  <div id="hdstats">" >>  $webroot/front.html
echo "<h1>Hard Drives</h1>" >>  $webroot/front.html
echo "    <table border='0' width='300px'>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <th>" >>  $webroot/front.html
echo "Disk" >>  $webroot/front.html
echo "        </th>" >>  $webroot/front.html
echo "        <th>" >>  $webroot/front.html
echo "Capacity" >>  $webroot/front.html
echo "        </th>" >>  $webroot/front.html
echo "        <th>" >>  $webroot/front.html
echo "Remaining" >>  $webroot/front.html
echo "        </th>" >>  $webroot/front.html
echo "        <th>" >>  $webroot/front.html
echo "%" >>  $webroot/front.html
echo "        </th>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

#File System
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "/" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$fs" | awk '{ print $2 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$fs" | awk '{ print $4 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
echo "<div class='dd'><div class='blue' style='width:" >>  $webroot/front.html
df -H | grep "$fs" | awk '{ print $5 }' >>  $webroot/front.html
echo ";'> </div></div>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "      </tr>" >>  $webroot/front.html

#HD1 Stats
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "$hdname1" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd1" | awk '{ print $2 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd1" | awk '{ print $4 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
echo "<div class='dd'><div class='blue' style='width:" >>  $webroot/front.html
df -H | grep "$hd1" | awk '{ print $5 }' >>  $webroot/front.html
echo ";'> </div></div>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "      </tr>" >>  $webroot/front.html

#HD2 Stats
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "$hdname2" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd2" | awk '{ print $2 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd2" | awk '{ print $4 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
echo "<div class='dd'><div class='blue' style='width:" >>  $webroot/front.html
df -H | grep "$hd2" | awk '{ print $5 }' >>  $webroot/front.html
echo ";'> </div></div>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "      </tr>" >>  $webroot/front.html

#HD3 Stats
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "$hdname3" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd3" | awk '{ print $2 }' | cut -d'T' -f1 >>  $webroot/front.html
echo "Tb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd3" | awk '{ print $4 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
echo "<div class='dd'><div class='blue' style='width:" >>  $webroot/front.html
df -H | grep "$hd3" | awk '{ print $5 }' >>  $webroot/front.html
echo ";'> </div></div>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "      </tr>" >>  $webroot/front.html

   #HD4 Stats
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "$hdname4" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd4" | awk '{ print $2 }' | cut -d'T' -f1 >>  $webroot/front.html
echo "Tb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
df -H | grep "$hd4" | awk '{ print $4 }' | cut -d'G' -f1 >>  $webroot/front.html
echo "Gb" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "        <td>" >>  $webroot/front.html
echo "<div class='dd'><div class='blue' style='width:" >>  $webroot/front.html
df -H | grep "$hd4" | awk '{ print $5 }' >>  $webroot/front.html
echo ";'> </div></div>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html

echo "      </tr>" >>  $webroot/front.html
echo "    </table>" >>  $webroot/front.html
echo "  </div>" >>  $webroot/front.html



#Recent TV Shows
find "$tvdir" -type f -printf "%TY-%Tm-%Td %TT + %f \n" | sort -rb | grep [0-9]x[0-9] | grep .nfo | head -10 | cut -d "+" -f 2  > $webroot/tvshows.html 

echo "  <div id="recent-tv">" >>  $webroot/front.html
echo "<h1>Recent TV Shows</h1>" >>  $webroot/front.html
echo "    <table border='0' width='300px'>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#1 target='middle'>" >>  $webroot/front.html
tv1=$(awk "NR==1{print}" $webroot/tvshows.html)
tv1a=${tv1%\.*}

echo "$tv1a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#2 target='middle'>" >>  $webroot/front.html
tv2=$(awk "NR==2{print}" $webroot/tvshows.html)
tv2a=${tv2%\.*}
echo "$tv2a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#3 target='middle'>" >>  $webroot/front.html
tv3=$(awk "NR==3{print}" $webroot/tvshows.html)
tv3a=${tv3%\.*}
echo "$tv3a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#4 target='middle'>" >>  $webroot/front.html
tv4=$(awk "NR==4{print}" $webroot/tvshows.html)
tv4a=${tv4%\.*}
echo "$tv4a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#5 target='middle'>" >>  $webroot/front.html
tv5=$(awk "NR==5{print}" $webroot/tvshows.html)
tv5a=${tv5%\.*}
echo "$tv5a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#6 target='middle'>" >>  $webroot/front.html
tv6=$(awk "NR==6{print}" $webroot/tvshows.html)
tv6a=${tv6%\.*}
echo "$tv6a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#7 target='middle'>" >>  $webroot/front.html
tv7=$(awk "NR==7{print}" $webroot/tvshows.html)
tv7a=${tv7%\.*}
echo "$tv7a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#8 target='middle'>" >>  $webroot/front.html
tv8=$(awk "NR==8{print}" $webroot/tvshows.html)
tv8a=${tv8%\.*}
echo "$tv8a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#9 target='middle'>" >>  $webroot/front.html
tv9=$(awk "NR==9{print}" $webroot/tvshows.html)
tv9a=${tv9%\.*}
echo "$tv9a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
echo "          <a href=/xbmc/recentepisodeframe.html#10 target='middle'>" >>  $webroot/front.html
tv10=$(awk "NR==10{print}" $webroot/tvshows.html)
tv10a=${tv10%\.*}
echo "$tv10a" >>  $webroot/front.html
echo "          </a>" >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "    </table>" >>  $webroot/front.html
echo "  </div>" >>  $webroot/front.html

#Recent Movies
ls -t "$moviedir" > $webroot/movielist.html 
echo "  <div id="recentmovies">" >>  $webroot/front.html
echo "<h1>Recent Movies</h1>" >>  $webroot/front.html
echo "    <table border='0' width='250px'>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie1=$(awk "NR==1{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie1"' target='middle'>" >>  $webroot/front.html
awk "NR==1{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie2=$(awk "NR==2{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie2"' target='middle'>" >>  $webroot/front.html
awk "NR==2{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie3=$(awk "NR==3{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie3"' target='middle'>" >>  $webroot/front.html
awk "NR==3{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie4=$(awk "NR==4{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie4"' target='middle'>" >>  $webroot/front.html
awk "NR==4{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie5=$(awk "NR==5{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie5"' target='middle'>" >>  $webroot/front.html
awk "NR==5{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie6=$(awk "NR==6{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie6"' target='middle'>" >>  $webroot/front.html
awk "NR==6{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie7=$(awk "NR==7{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie7"' target='middle'>" >>  $webroot/front.html
awk "NR==7{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie8=$(awk "NR==8{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie8"' target='middle'>" >>  $webroot/front.html
awk "NR==8{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie9=$(awk "NR==9{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie9"' target='middle'>" >>  $webroot/front.html
awk "NR==9{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
movie10=$(awk "NR==10{print}" $webroot/movielist.html | cut -d'(' -f1 | tr -s "\'" ' '| sed 's/ //g')
echo "        <td><a class='recent-movies' href='$navlink1/moviesframe.html#"$movie10"' target='middle'>" >>  $webroot/front.html
awk "NR==10{print}" $webroot/movielist.html >>  $webroot/front.html
echo "</a>" >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "    </table>" >>  $webroot/front.html
echo "  </div>" >>  $webroot/front.html


#TV Recordings
ls -t -R "$recordingsdir" | grep mkv | sed "s/...............$//" > $webroot/recordings.html 
echo "  <div id="recordings">" >>  $webroot/front.html
echo "<h1>TV Recordings</h1>" >>  $webroot/front.html
echo "    <table border='0' width='250px'>" >>  $webroot/front.html

echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==1{print}" $webroot/recordings.html >>  $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==2{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==3{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==4{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==5{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==6{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==7{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==8{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==9{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "      <tr>" >>  $webroot/front.html
echo "        <td>" >>  $webroot/front.html
awk "NR==10{print}" $webroot/recordings.html >> $webroot/front.html
echo "        </td>" >>  $webroot/front.html
echo "      </tr>" >>  $webroot/front.html
echo "    </table>" >>  $webroot/front.html
echo "  </div>" >>  $webroot/front.html

#Coming Episodes Iframe

echo "  <div id='upcoming-frame'>" >>  $webroot/front.html
echo "    <iframe src ='/sickbeard/comingEpisodes' name='middle' scrolling='no' frameborder='0' border='0' framespacing='0'>" >>  $webroot/front.html
echo "      <p>Your browser does not support iframes.</p>" >>  $webroot/front.html
echo "    </iframe>" >>  $webroot/front.html
echo "  </div>" >>  $webroot/front.html
echo "  </body>" >>  $webroot/front.html
echo "</html>" >>  $webroot/front.html


