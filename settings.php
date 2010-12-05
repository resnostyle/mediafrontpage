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
		<link href="style/css/front.css" rel="stylesheet" type="text/css" />	
		<link href="style/css/settings.css" rel="stylesheet" type="text/css" />	
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
			<script type="text/javascript" src="style/js/settings.js"></script>
		</div><!-- #page -->
	</body>
</html>

<?php
function globalSettings($settingsDB) {
	echo "\t\t\t\t<h1>MediaFrontPage Settings</h1><br/>\n";
	echo "\t\t\t\t<form action='settings.php?w=global' method='post'>\n";
	foreach ($settingsDB as $setting) {
		if ($setting['Widget'] == 'global' ) {
			if ($setting['Id'] == 'navlinks') {
				$navlinks = unserialize($setting['Value']);
				$i = 1;
				if (!empty($navlinks)) {
					echo "\t\t\t\t\t<ul class='section ui-sortable'>\n";
					echo "\t\t\t\t\t\t<h3>Navigation Bar Links</h3>\n";

					foreach ($navlinks as $navlink){
						echo "\t\t\t\t\t\t<li class='setting collapsed'>\n";
						echo "\t\t\t\t\t\t\t<div class='setting-head'>\n";
						echo "\t\t\t\t\t\t\t\t<h3>".$navlink['label']."</h3>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-head -->\n";
						echo "\t\t\t\t\t\t\t<div class='setting-content'>\n";
						echo "\t\t\t\t\t\t\t\t<p> Name: <input type='text' value='".$navlink['label']."' name='navlink-".$i."-label'  /> URL: <input type='text' value='".$navlink['url']."' name='navlink-".$i."-url'  /></p>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-content -->\n";
						echo "\t\t\t\t\t\t</li><!-- .setting -->\n";
						$i++;
					}
						echo "\t\t\t\t\t\t<div class='add'>\n";
						echo "\t\t\t\t\t\t\t<div class='setting-head'>\n";
						echo "\t\t\t\t\t\t\t\t<h3>Add New Link</h3>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-head -->\n";
						echo "\t\t\t\t\t\t\t<div class='setting-content'>\n";
						echo "\t\t\t\t\t\t\t\t<p> Name: <input type='text' value='' name='addlink-".$i."-label'  /></p>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-content -->\n";
						echo "\t\t\t\t\t\t</div><!-- .add -->\n";
						echo "\t\t\t\t\t</ul><!-- .section -->\n";
				}
			} elseif ($setting['Id'] == 'customstylesheets') {
				$stylesheets = unserialize($setting['Value']);
				$i = 1;
				if (!empty($stylesheets)) {
					echo "\t\t\t\t\t<ul class='section ui-sortable'>\n";
					echo "\t\t\t\t\t\t<h3>Custom Stylesheets</h3>\n";
					foreach ($stylesheets as $stylesheet){
						echo "\t\t\t\t\t\t<li class='setting collapsed'>\n";
						echo "\t\t\t\t\t\t\t<div class='setting-head'>\n";
						echo "\t\t\t\t\t\t\t\t<h3>".$stylesheet['label']."</h3>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-head -->\n";
						echo "\t\t\t\t\t\t\t<div class='setting-content'>\n";
						echo "\t\t\t\t\t\t\t\t<p>Name <input type='text' value='".$stylesheet['label']."' name='customstylesheet-".$i."-label'  /> Path: <input type='text' value='".$stylesheet['path']."' name='customstylesheet-".$i."-path'  /> Enabled: <input type='text' value='".$stylesheet['enabled']."' name='customstylesheet-".$i."-enabled'  /></p>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-content -->\n";
						echo "\t\t\t\t\t\t</li><!-- .setting -->\n";
						$i++;
					}
				}
						echo "\t\t\t\t\t\t<div class='add'>\n";
						echo "\t\t\t\t\t\t\t<div class='setting-head'>\n";
						echo "\t\t\t\t\t\t\t\t<h3>Add New Stylesheet</h3>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-head -->\n";
						echo "\t\t\t\t\t\t\t<div class='setting-content'>\n";
						echo "\t\t\t\t\t\t\t\t<p> Name: <input type='text' value='' name='addcs-".$i."-label'  /></p>\n";
						echo "\t\t\t\t\t\t\t</div><!-- .setting-content -->\n";
						echo "\t\t\t\t\t\t</div><!-- .add -->\n";
						echo "\t\t\t\t\t</ul><!-- .section -->\n";
			} else {
				echo" \t\t\t\t\t<div  class='section'>\n";
				$setting['Value'] = unserialize($setting['Value']);
				echo "\t\t\t\t\t\t<h3>".$setting['Label']."</h3>\n";
				echo "\t\t\t\t\t\t<input type='text' value='".$setting['Value']."' name='".$setting['Id']."'  />\n";
				echo" \t\t\t\t\t</div><!--  .section -->\n";
			}
		} 
	}
	echo "\t\t\t\t\t<div id='save' class='save'>\n";
	echo "\t\t\t\t\t\t<input type='submit' value='Save' />\n";
	echo "\t\t\t\t\t</div>\n";
	echo "\t\t\t\t</form>\n";
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
