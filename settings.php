<?php
//require_once "config.php";
require_once "widgets.php";
require_once "functions.php";

foreach (glob("widgets/*/w*.php") as $filename) {
	include_once $filename;
}

//turn off warnings
$errlevel = error_reporting();
error_reporting(E_ALL & ~E_WARNING);

// Turn on warnings
error_reporting($errlevel); 

?>
<html>
	<head>
		<title>Media Front Page - Settings</title>
		<!--<link href="settings.css" rel="stylesheet" type="text/css" /> -->
		<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js"></script>
		<link href="style/css/widget.css" rel="stylesheet" type="text/css" />	
		<link href="style/layouts/3col-equal.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="page">
			<div id="header">
				<ul>
<?php
// Get widgets
$widgets = getAllWidgets();
// Display menu
echo "\t\t\t\t<li class=\"menuitem global\"><a href=\"?w=global\"><span>MediaFrontPage Settings</span></a></li>\n";
foreach($widgets as $widget) {
	$function = $widget['Id']."Settings";
	if (function_exists($function)) {
		echo "\t\t\t\t<li class=\"menuitem ".$widget['Id']."\"><a href=\"?w=".$widget['Id']."\"><span>".$widget['Title']."</span></a></li>\n";
	}
}
// If there are any updated settings add them to database
if (!empty($_POST)) {
	$updateFunction = $_GET['w']."UpdateSettings";
	$updateFunction($_POST);
}
?>
				</ul>
			</div><!-- #header -->
			<div id="main">
<?php
// If a widget has been selected then show its settings
if (!empty($_GET['w'])) {
	$settingsDB = getAllSettings();
	$settingsFunction = $_GET['w']."Settings";
	$settingsFunction($settingsDB);
} 

?>
			</div><!-- #main -->
		    	<script type="text/javascript" src="style/js/jquery.js"></script>
			<script type="text/javascript" src="style/js/widget.js"></script>
		</div><!-- #page -->
	</body>
</html>

<?php
function globalSettings($settingsDB) {
	echo "<form action='settings.php?w=global' method='post'>\n";
	foreach ($settingsDB as $setting) {
		if ($setting['Widget'] == 'global' ) {
			if ($setting['Id'] == 'navlinks') {
				$navlinks = unserialize($setting['Value']);
				$i = 1;
				if (!empty($navlinks)) {
					echo "\t<ul id='section-1' class='section ui-sortable'>\n";
					echo "\t<p><strong>Navigation Bar Link:</strong></p>";
					foreach ($navlinks as $navlink){
						echo "\t\t<li class='widget collapsed'>\n";
						echo "\t\t\t<div class='widget-head'>\n";
						echo "\t\t\t\t<h3>".$navlink['label']."</h3>\n";
						echo "\t\t\t</div><!-- .widget-head -->\n";
						echo "\t\t\t<div class='widget-content'>\n";
						echo "\t\t\t\t<p>";
						echo "Name: <input type='text' value='".$navlink['label']."' name='navlink-".$i."-label'  />";
						echo "URL: <input type='text' value='".$navlink['url']."' name='navlink-".$i."-url'  /></p>";
						//echo "Del: <input type='checkbox' name='navlink-".$i."-remove' value='true' /></p>";
						echo "\t\t\t</div><!-- .widget-content -->\n";
						echo "</li><!-- .widget -->";
						$i++;
					}
						echo "\t\t<li class='widget collapsed'>\n";
						echo "\t\t\t<div class='widget-head'>\n";
						echo "\t\t\t\t<h3>Add New Link</h3>\n";
						echo "\t\t\t</div><!-- .widget-head -->\n";
						echo "\t\t\t<div class='widget-content'>\n";
						echo "\t\t\t\t<p>";
						echo "Name: <input type='text' value='' name='addlink-".$i."-label'  /></p>\n";
						echo "\t\t\t</div><!-- .widget-content -->";
						echo "\t\t</li><!-- .widget -->";
						echo "\t</ul><!-- #section-1 -->";
				}

			} elseif ($setting['Id'] == 'customstylesheets') {
				$stylesheets = unserialize($setting['Value']);
				$i = 1;
				if (!empty($stylesheets)) {
					echo "\t<ul id='section-2' class='section ui-sortable'>\n";
					echo "\t<p><strong>Custom Stylesheets:</strong></p>";
					foreach ($stylesheets as $stylesheet){
						echo "\t\t<li class='widget collapsed'>\n";
						echo "\t\t\t<div class='widget-head'>\n";
						echo "\t\t\t\t<h3>".$stylesheet['label']."</h3>\n";
						echo "\t\t\t</div><!-- .widget-head -->\n";
						echo "\t\t\t<div class='widget-content'>\n";
						echo "\t\t\t\t<p>";
						echo "Name <input type='text' value='".$stylesheet['label']."' name='customstylesheet-".$i."-label'  />";
						echo "Path: <input type='text' value='".$stylesheet['path']."' name='customstylesheet-".$i."-path'  />";
						echo "Enabled: <input type='text' value='".$stylesheet['enabled']."' name='customstylesheet-".$i."-enabled'  /></p>";
						echo "\t\t\t</div><!-- .widget-content -->\n";
						echo "</li><!-- .widget -->";
						$i++;
					}
				}
						echo "\t\t<li class='widget collapsed'>\n";
						echo "\t\t\t<div class='widget-head'>\n";
						echo "\t\t\t\t<h3>Add New Stylesheet</h3>\n";
						echo "\t\t\t</div><!-- .widget-head -->\n";
						echo "\t\t\t<div class='widget-content'>\n";
						echo "\t\t\t\t<p>";
						echo "Name: <input type='text' value='' name='addcs-".$i."-label'  /></p>\n";
						echo "\t\t\t</div><!-- .widget-content -->";
						echo "\t\t</li><!-- .widget -->";
						echo "\t</ul><!-- #section-1 -->";
			} else {
				$setting['Value'] = unserialize($setting['Value']);
				echo "\t\t".$setting['Label'].": <input type='text' value='".$setting['Value']."' name='".$setting['Id']."'  /><br />\n";
			}
		} 
	}
	echo "\t\t<input type='submit' value='Save' />\n";
	echo "</form>\n";
}

function globalUpdateSettings($post) {
	$nl = 1;
	$cs = 1;
	$navlinks = "";
	$customstylesheets = "";
	if (!empty($post)) {
		foreach ($post as $id => $value) {
			// Create navlink array
			if (strpos($id, 'navlink') !== false) {				
				if (strpos($id, 'label') !== false) {
					$navlinks["navlink".$nl]['label'] = $value;
				} elseif (strpos($id, 'url') !== false) {
					$navlinks["navlink".$nl]['url'] = $value;
					if (!isset($post['navlink-'.$nl.'-remove'])){
						$nl++;	
					}	
				} elseif (strpos($id, 'remove') !== false) {
					if ($value == 'true') {
						unset($navlinks["navlink".$nl]);
					}
					$nl++;		
				}	
			} elseif (strpos($id, 'addlink') !== false) {				
				if (!empty($value)) {
					$navlinks["navlink".$nl]['label'] = $value;
					$navlinks["navlink".$nl]['url'] = "";
				} else {
					$post['navlink-'.$nl.'-remove'] = 'true';
				}
				$nl++;
			} elseif (strpos($id, 'customstylesheet') !== false) {				
				if (strpos($id, 'label') !== false) {
					$customstylesheets["customstylesheet".$cs]['label'] = $value;
				} elseif (strpos($id, 'path') !== false) {
					$customstylesheets["customstylesheet".$cs]['path'] = $value;
				} elseif (strpos($id, 'enabled') !== false) {
					$customstylesheets["customstylesheet".$cs]['enabled'] = $value;
					if (!isset($post['customstylesheet-'.$cs.'-remove'])){
						$cs++;	
					}	
				} elseif (strpos($id, 'remove') !== false) {
					if ($value == 'true') {
						unset($customstylesheets["customstylesheet".$cs]);
					}
					$cs++;		
				}	
			} elseif (strpos($id, 'addcs') !== false) {				
				if (!empty($value)) {
					$customstylesheets["customstylesheet".$cs]['label'] = $value;
					$customstylesheets["customstylesheet".$cs]['path'] = "";
					$customstylesheets["customstylesheet".$cs]['enabled'] = "";
				} else {
					$post['customstylesheet-'.$cs.'-remove'] = 'true';
				}
				$cs++; 
			} else {
				updateSetting($id,$value); 
			}
		}
		updateSetting('navlinks', $navlinks);
		updateSetting('customstylesheets', $customstylesheets);
	} 
}
?>
