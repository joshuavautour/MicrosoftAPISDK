<?PHP

/*
 *
 * Class for connecting to Microsoft API endpoints
 *
 * @author      Joshua Vautour <joshua@speedoflogic.com>
 * @version     0.1.0
 *
 */

class MicrosoftAPIRequest {
	
	private $microsoftAPIAuthenticationObj = null;
	private $debugLevelInt = null;
	private $requestHandle = null;


	/*
	 * Constructor for Microsoft API Request Class
	 *
	 * 
	 */

	public function __construct($microsoftAPIAuthenticationObj) {
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
		
		//	Configure curl option to return transfer data
		curl_setopt($this->requestHandle, CURLOPT_RETURNTRANSFER, 1);

		$curlResultsMix = curl_exec($this->requestHandle);
		if(!$curlResultsMix) {
			logInfo("Curl request failed with message: ".curl_error($this->requestHandle)."\n", 5);
			throw new Exception('Curl request failed');
		}
		logInfo("Results from curl request: ".serialize(curl_getinfo($this->requestHandle))."\n", 9);
		return $curlResultsMix;
	}


	public function graphAPIGetRequest($graphRequestStr, $graphVersionStr='v1.0'){

		$graphUrl = 'https://graph.microsoft.com/'.$graphVersionStr.'/'.$graphRequestStr;
		logInfo("Graph request URL: ".$graphUrl."\n", 9);

		$graphRequestMethod = 'GET';
		logInfo("Graph request Method: ".$graphRequestMethod."\n", 9);

		$this->requestSetup();

		$graphAuthHeaders = $this->microsoftAPIAuthenticationObj->getAuthHeaders();

		$graphRequestHeaders = array (
			"'Accept': 'application/json'",
			"'Content-Type': 'application/json; charset=utf-8'",
			"'accept-language': en-US'",
			"'User-Agent': 'SOLPHPSDK/0.1'",
			$graphAuthHeaders,
		);
		logInfo("Graph request Headers: ".serialize($graphRequestHeaders)."\n", 9);

		// set curl options for the graph request:

		curl_setopt($this->requestHandle, CURLOPT_URL, $graphUrl);
		curl_setopt($this->requestHandle, CURLOPT_HTTPHEADER, $graphRequestHeaders);



		// Execute the curl request

		$graphRequestResult = $this->requestExecute();

		$this->requestDestroy();

		return $graphRequestResult;
	}
}

?>
