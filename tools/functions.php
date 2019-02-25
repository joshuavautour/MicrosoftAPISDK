<?PHP

/*
 *
 * 	Functions available to this SDK
 *
 *
 */

// 	Autoload classes by name

function classAutoloader($classNameStr) {
	require_once(APP_ROOT_PATH.'classes/' . $classNameStr . '.php');
}


// 	Create a formated timestamp with microseconds

function getFormatedTimestamp ($formatStr = 'Y-m-d\TH:i:s.u', $timestampStr = 'NOW') {
	// Create new DateTime object based on timestamp provided
	$curDateTime = new DateTime($timestampStr);

	// return the formatted timestamp
	return $curDateTime->format($formatStr);
}

// 	Log data to the globals debugInfo variable based on the sytem setting

function logInfo ($logDataStr, $infoSeverityLevelInt) {
	if ($infoSeverityLevelInt <= SYSTEM_DEBUG_LEVEL) {

		//	Initialize the debugLog array
		if (!is_array($GLOBALS['debugLog'])){
			$GLOBALS['debugLog'] = array();
		}

		array_push($GLOBALS['debugLog'], array(getFormatedTimestamp(), $infoSeverityLevelInt, $logDataStr));
	}
}
?>
