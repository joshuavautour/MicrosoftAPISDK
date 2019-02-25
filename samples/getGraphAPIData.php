<?PHP

//	Include config file and tools
require_once('../config/config.php');
require_once('../tools/functions.php');

//	Register class autoloader
spl_autoload_register('classAutoloader');


//	Initialize a new MS Auth object
$msAuthObj = new MicrosoftAPIAuthentication();

//	Authenticate using oauth2 and program secrets
$msAuthObj->oauth2UsingProgramSecret(AZURE_CONFIG_TENANT_ID, AZURE_CONFIG_CLIENT_ID, AZURE_CONFIG_CLIENT_SECRET, 'graph.microsoft.com');


//	Initialize a new MS API request object
$msRequestObj = new MicrosoftAPIRequest($msAuthObj);

//	Get info from the MS graph API
$graphRequestedDataStr = 'auditLogs/directoryAudits';

//	Get json audit log data from MS graph API
$graphAuditLogDataJso = $msRequestObj->graphAPIGetRequest($graphRequestedDataStr, 'beta');

//do something with the data

//	uncomment the line below to print out the log data
//print_r($GLOBALS['debugLog']);
?>
