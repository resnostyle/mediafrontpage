<?php

$widget_init = array(	'Id' 			=> "wHardDrives", 
			'Child'			=> "false",	
			'Type' 			=> "inline", 
			'Title' 		=> "Hard Drives", 
			'Function' 		=> "widgetHardDrives();",
			'Stylesheet' 		=> "harddrives.css",
			'Section' 		=> 1, 
			'Position' 		=> 1,
			'HeaderFunction' 	=> "", 
			'Parts'			=> "",
			'Block' 		=> "",  
			'Call'			=> "",
			'Loader'		=> "",
			'Interval'		=> "",
			'Script'		=> ""
		    );

$settings_init['wHardDrives'] =	array(	'id'	=>	'drives',
					'label'	=>	'Drives',
					'value' =>	array(	'drivepath1' =>	array(	'label' 	=> '/',
											'location'	=> '/'),
								'drivepath2' =>	array(	'label' 	=> 'TV Shows',
											'location'	=> '/path/to/tvshows'),
								'drivepath3' =>	array(	'label' 	=> 'Movies',
											'location'	=> '/path/to/movies'),
								'drivepath4' =>	array(	'label' 	=> 'Music',
											'location'	=> '/path/to/music'),
								'drivepath5' =>	array(	'label' 	=> 'Downloads',
											'location'	=> '/path/to/downloads')
								)
					);

function widgetHardDrives() {
	global $settings;
	
	$warningthreshold = 90;
	
	if(!empty($settings['drives'])) {
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
		$drives = $settings['drives'];
		foreach( $drives as $drive) {
			echo "\t<tr>\n";
			echo "\t\t<td>".$drive['label']."</td>\n";
			echo "\t\t<td>".to_readable_size(disk_total_space($drive['location']))."</td>\n";
			echo "\t\t<td>".to_readable_size(disk_free_space($drive['location']))."</td>\n";
			echo "\t\t<td><div class=\"progressbar\"><div class=\"progress".((disk_used_percentage($drive['location']) > $warningthreshold) ? " warning" : "")."\" style=\"width:".(disk_used_percentage($drive['location']))."%\"></div><div class=\"progresslabel\">".sprintf("%u", disk_used_percentage($drive['location']))."%</div></div></td>\n";
			echo "\t</tr>\n";
		}
		echo "</table>\n";
	}
}
function wHardDrivesSettings($settingsDB) {
	//echo print_r($settingsDB,1);
	echo "<form action='settings.php?w=wHardDrives' method='post'>\n";
	foreach ($settingsDB as $setting) {
		if ($setting['Id'] == 'drives') {
			$drives = unserialize($setting['Value']);
			$i = 1;
//			echo print_r($drives,1);
			foreach ($drives as $drive){
				echo "\t<strong>Drive ".$i.":</strong>";
				echo "\t\tLabel: <input type='text' value='".$drive['label']."' name='drivepath-".$i."-label'  />";
				echo "\t\tLocation: <input type='text' value='".$drive['location']."' name='drivepath-".$i."-location'  /><br /><br />\n";
				$i++;
			}
		} 
	}
	echo "\t\t<input type='submit' value='Update' />\n";
	echo "</form>\n";
}

function wHardDrivesUpdateSettings($post) {
	$i = 1;
	foreach ($post as $id => $value) {
		// Create drives array
		if (strpos($id, 'drivepath') !== false) {				
			if (strpos($id, 'label') !== false) {
				$drivepaths["drivepath".$i]['label'] = $value;
			} elseif (strpos($id, 'location') !== false) {
				$drivepaths["drivepath".$i]['location'] = $value;
				$i++;
			}
		}	 
	}
	//echo print_r($drivepaths,1);
	updateSetting('drives', $drivepaths);
} 
?>
