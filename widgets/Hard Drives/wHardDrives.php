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

$settings_init['wHardDrives'] =	array(  'drives' =>	array(	'label'	=>	'Drives',
								'value' =>	array(	'drivepath1' =>	array(	'label' 	=> '/',
														'location'	=> '/')
											)
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
	echo "<form action='settings.php?w=wHardDrives' method='post'>\n";
	foreach ($settingsDB as $setting) {
		if ($setting['Widget'] == 'wHardDrives' ) {
			if ($setting['Id'] == 'drives') {
				$drives = unserialize($setting['Value']);
				$i = 1;
				if (!empty($drives)) {
					echo "\t<strong>Hard Drives:</strong><br />";
					foreach ($drives as $drive){
						echo "\t<strong>".$drive['label'].":</strong><br />";
						echo "\t\tName:<input type='text' value='".$drive['label']."' name='drivepath-".$i."-label'  />";
						echo "\t\tLocation: <input type='text' value='".$drive['location']."' name='drivepath-".$i."-location'  />";
						echo "\t\tDel: <input type='checkbox' name='drivepath-".$i."-remove' value='true' /><br /><br />\n";
						$i++;
					}
				}
				echo "\t<strong>Add New Drive:</strong><br />";
				echo "\t\tName: <input type='text' value='' name='adddrive-".$i."-label'  /><br /><br />";
			}
		} 
	}
	echo "\t\t<input type='submit' value='Save' />\n";
	echo "</form>\n";
}

function wHardDrivesUpdateSettings($post) {
	$i = 1;
	$drivepaths = "";
	if (!empty($post)) {
		foreach ($post as $id => $value) {
			// Create drives array
			if (strpos($id, 'drivepath') !== false) {				
				if (strpos($id, 'label') !== false) {
					$drivepaths["drivepath".$i]['label'] = $value;
				} elseif (strpos($id, 'location') !== false) {
					$drivepaths["drivepath".$i]['location'] = $value;
					if (!isset($post['drivepath-'.$i.'-remove'])){
						$i++;	
					}	
				} elseif (strpos($id, 'remove') !== false) {
					if ($value == 'true') {
						unset($drivepaths["drivepath".$i]);
					}
					$i++;		
				}	
			} elseif (strpos($id, 'adddrive') !== false) {				
				if (!empty($value)) {
					$drivepaths["drivepath".$i]['label'] = $value;
					$drivepaths["drivepath".$i]['location'] = "";
				} else {
					$post['drivepath-'.$i.'-remove'] = 'true';
				}
				$i++;
			}	 
		}
		updateSetting('drives', $drivepaths);
	}

} 
?>
