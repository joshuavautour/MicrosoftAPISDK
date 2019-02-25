<?PHP

/*
 *
 * Class for authentication against Microsoft API endpoints
 *
 * @author      Joshua Vautour <joshua@speedoflogic.com>
 * @version     0.1.0
 *
 */

class MicrosoftAPIAuthentication {
	
	private $authTokenStr = null;
	private $debugLevelInt = null;
	private $requestHandle = null;


	/*
	 * Constructor for Microsoft API Authentication Class
	 *
	 * 
	 */

	public function __construct() {
		if (!extension_loaded('curl')) {
			throw new Exception('The PHP exention curl must be installed');
		}
		$this->microsoftAPIAuthenticationObj = $microsoftAPIAuthenticationObj;
		$this->debugLevelInt = $debugLevelInt;
	}

	private function requestSetup() {
		$this->requestHandle = curl_init();
		logInfo("Creating curl handle\n", 5);
	}

	private function requestDestroy() {
		curl_close($this->requestHandle); 
		logInfo("Destroying curl handle\n", 5);
	}

	private function requestExecute() {
		logInfo("Executing curl request\n", 5);
		
		//	Configure curl to return the result
		curl_setopt($this->requestHandle, CURLOPT_RETURNTRANSFER, 1);

		$curlResultMix = curl_exec($this->requestHandle);
		if(!$curlResultMix) {
			logInfo("Curl request failed with message: ".curl_error($this->requestHandle)."\n", 5);
			throw new Exception('Curl request failed');
		}
		logInfo("Curl auth request successful: ".serialize(curl_getinfo($this->requestHandle))."\n", 5);
		return $curlResultMix;
	}


	public function oauth2UsingProgramSecret($authTenantIdStr, $authClientIdStr, $authClientSecretStr, $authResourceRequestStr, $authVersionStr='v2.0'){

		logInfo("Calling public method oauth2UsingProgramSecret in class MicrosoftAPIAuthentication\n", 9);
		logInfo("Requestion permission to access: ".$authRequestStr."\n", 9);

		// Build auth token URL
		$authRequestUrl = 'https://login.microsoftonline.com/'.$authTenantIdStr.'/oauth2/'.$authVersionStr.'/token';
		logInfo("Auth request URL: ".$authRequestUrl."\n", 5);

		// Build auth request data
		if ($authVersionStr == 'v2.0') {
			$authRequestData = 'grant_type=client_credentials&client_id='.$authClientIdStr.'&client_secret='.$authClientSecretStr.'&scope=https%3A%2F%2F'.$authResourceRequestStr.'%2F.default';
			logInfo("Auth request Data: ".$authRequestData."\n", 5);
		} else {
			logInfo("Error - unsupported auth version\n", 9);
			throw new Exception('Unsupport auth version');
		}

		// Set Auth request method
		$authRequestMethod = 'POST';

		$authHeaderData = array (
			"'Accept': 'application/json'",
			"'Content-Type': 'application/json; charset=utf-8'",
			"'accept-language': en-US'",
			"'User-Agent': 'SOLPHPSDK/0.1'",
		);

		// Setup the curl request handle
		$this->requestSetup();



		logInfo("Auth request Method: ".$authRequestMethod."\n", 5);
		logInfo("Auth request Headers: ".serialize($authHeaderData)."\n", 5);

		// set curl options for the auth request:
		curl_setopt($this->requestHandle, CURLOPT_URL, $authRequestUrl);
		curl_setopt($this->requestHandle, CURLOPT_HTTPHEADER, $authHeaderData);
		curl_setopt($this->requestHandle, CURLOPT_POST, 1);
		curl_setopt($this->requestHandle, CURLOPT_POSTFIELDS, $authRequestData);

		$authResultsStr = $this->requestExecute();

		$authResultsArr = json_decode($authResultsStr);

		$this->authTokenStr = $authResultsArr->access_token;

		$this->requestDestroy();

		return true;
	}

	//	Public method to get the current auth token

	public function getAuthToken(){

		logInfo("Calling public method getAuthToken in class MicrosoftAPIAuthentication\n", 9);

		if (!$this->authTokenStr) {
			throw new Exception('Authentication has not been completed');
		} 
		return $this->authTokenStr;
	}


	//	Public method to return the curl auth header 

	public function getAuthHeaders(){

		logInfo("Calling public method getAuthHeaders in class MicrosoftAPIAuthentication\n", 9);

		if (!$this->authTokenStr) {
			throw new Exception('Authentication has not been completed');
		} 

		$headerAuth = "Authorization: Bearer ".$this->authTokenStr;

		return $headerAuth;
	}
}

?>
