<?php
$wdgtHardDrives = array("type" => "inline", "function" => "widgetHardDrives();");
$wIndex[wHardDrives] = $wdgtHardDrives;

function widgetHardDrives() {
	global $drive;

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
}
?>
