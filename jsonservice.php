<?php
require_once "config.php";
require_once "functions.php";

function error_array($code, $message) {
	return array ( "error" => array ( "code" => $code, "message" => $message));
}

$jsonRequest = urldecode(file_get_contents("php://input"));

$arrRequest = json_decode($jsonRequest, true);
if(!empty($DEBUG) && $DEBUG && !empty($arrRequest['jsonrpc']) && ($arrRequest['jsonrpc'] == "2.0") && !empty($xbmcjsonserviceoverride)) {
	// Use XBMC test harness
	if(!empty($arrRequest['method']) && file_exists($arrRequest['method'])) {
		$response = file_get_contents($arrRequest['method']);
	} else {
		//json rpc call procedure
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $xbmcjsonserviceoverride);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);
		$response = curl_exec($ch);
		curl_close($ch);
	}
	echo $response;
} else {
	if(!empty($arrRequest)) {
		switch ($arrRequest["method"]) {
			case "SaveLayout":
				$arrResult = saveLayout($arrRequest);
				break;
			case "GetWidgets":
				$arrResult = getWidgets($arrRequest);
				break;
			case "XBMCPassthough":
				$arrResult = xbmcPassthough($arrRequest);
				break;
			default:
				$arrResult = error_array(-32601, "Method not found.");
		}
	} else {
		$arrResult = error_array(-32700, "Parse error.");
	}
	echo json_encode($arrResult);
}

function saveLayout($arrRequest) {
	if(!empty($arrRequest["params"]) && is_array($arrRequest["params"])) {
				// Open the database
			try {   $db = new PDO('sqlite:settings.db');
				
				$s = 1;
				foreach ($arrRequest["params"] as $section) {
					$p = 1;
					foreach ($section as $widget) {
						
						// Prepare the SQL Statement
						$sql = "UPDATE Widgets SET Title='".$widget['title']."', Section='".$s."', Position='".$p."', Display='".$widget['display']."' WHERE Id='".$widget['id']."';";

						$q = $db->prepare($sql);
		
						// Add widget to database
						$q->execute();
						$p++ ;
					}
					$s++ ;
				}				
			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}

			// Close the database connection
			$db = NULL;
	} else {
		$arrResult = error_array(-32602, "Invalid parameters.");
	}
	if(!empty($arrResult)) {
		return $arrResult;
	}
}

function xbmcPassthough($arrRequest) {
	if(!empty($arrRequest["params"]) && is_array($arrRequest["params"])) {
		$request = json_encode($arrRequest["params"]);
		$arrResult = jsoncall($request);
		if(empty($arrResult) || !is_array($arrResult)) {
			$arrResult = error_array(-32503, "Invalid response.");
		}
	} else {
		$arrResult = error_array(-32602, "Invalid parameters.");
	}
	return $arrResult;
}
?>
