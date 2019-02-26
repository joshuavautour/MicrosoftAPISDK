<?PHP
/**
 * Class file for MicrosoftAPIRequest
 * @package 		MicrosoftAPISDK
 */

/**
 * Class for connecting to Microsoft API endpoints
 *
 * @author      Joshua Vautour <joshua@speedoflogic.com>
 * @version     0.1.0
 * @package			MicrosoftAPISDK
 *
 */
class MicrosoftAPIRequest extends RESTsol{

	/**
	 * A protected varible to that is an instance of {@link MicrosoftAPIAuthentication}
	 * @var object
	 */
	protected $microsoftAPIAuthenticationObj = null;


	/**
	 * Constructor for Microsoft API Request Class
	 * @param object $microsoftAPIAuthenticationObj an instantiated {@link MicrosoftAPIAuthentication} object
	 */
	public function __construct($microsoftAPIAuthenticationObj) {
		if (!extension_loaded('curl')) {
			throw new Exception('The PHP exention curl must be installed');
		}
		$this->microsoftAPIAuthenticationObj = $microsoftAPIAuthenticationObj;
	}

	/**
	 * Perform a GET request against the Microsoft Graph API
	 * @param  string $graphRequestStr 	This is data to be appended to the query string
	 * @param  string $graphVersionStr 	The Graph API version
	 * @return mixed                  	Contains the return data from the REST request
	 */
	public function graphAPIGetRequest($graphRequestStr, $graphVersionStr='v1.0'){

		/**
		 * Contains the URL for the REST request
		 * @var string
		 */
		$this->requestUrl = 'https://graph.microsoft.com/'.$graphVersionStr.'/'.$graphRequestStr;
		logInfo("Graph request URL: ".$this->requestUrl."\n", 9);

		/**
		 * Contains the request method type
		 * @var string
		 */
		$graphRequestMethod = 'GET';
		logInfo("Graph request Method: ".$graphRequestMethod."\n", 9);

		/**
		 * build the curl handle
		 */
		$this->requestSetup();

		/**
		 * add the authentication headers to {@link $requestHeaders}
		 */
		array_push($this->requestHeaders, $this->microsoftAPIAuthenticationObj->getAuthHeaders());
		logInfo("Graph request Headers: ".serialize($this->requestHeaders)."\n", 9);

		/**
		 * Set curl URL and header data
		 */
		curl_setopt($this->requestHandle, CURLOPT_URL, $this->requestUrl);
		curl_setopt($this->requestHandle, CURLOPT_HTTPHEADER, $this->requestHeaders);

		// Execute the curl request

		$graphRequestResult = $this->requestExecute();

		$this->requestDestroy();

		return $graphRequestResult;
	}
}

?>
