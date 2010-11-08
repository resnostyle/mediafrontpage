<?php
$wdgtHardDrives = array("name" => "Hard Drives", "type" => "inline", "function" => "widgetHardDrives();");
$wIndex["wHardDrives"] = $wdgtHardDrives;


function widgetHardDrives() {
	global $drive;
	
	$warningthreshold = 90;
	
	if(!empty($drive)) {
		echo "<table border=\"0\" id=\"harddrives\">\n";
		echo "\t<col id=\"col-disk\" />\n";
		echo "\t<col id=\"col-capacity\" />\n";
		echo "\t<col id=\"col-remaining\" />\n";
		echo "\t<col id=\"col-progress\" />\n";
		echo "\t<tr>\n";
		echo "\t\t<th>Disk</th>\n";
		echo "\t\t<th>Capacity</th>\n";
		echo "\t\t<th>Remaining</th>\n";
		echo "\t\t<th>%</th>\n";
		echo "\t</tr>\n";
		foreach( $drive as $drivelabel => $drivepath) {
			echo "\t<tr>\n";
			echo "\t\t<td>".$drivelabel."</td>\n";
			echo "\t\t<td>".to_readable_size(disk_total_space($drivepath))."</td>\n";
			echo "\t\t<td>".to_readable_size(disk_free_space($drivepath))."</td>\n";
			echo "\t\t<td><div class=\"progressbar\"><div class=\"progress".((disk_used_percentage($drivepath) > $warningthreshold) ? " warning" : "")."\" style=\"width:".(disk_used_percentage($drivepath))."%\"></div><div class=\"progresslabel\">".sprintf("%u", disk_used_percentage($drivepath))."%</div></div></td>\n";
			echo "\t</tr>\n";
		}
		echo "</table>\n";
	}
}
?>
