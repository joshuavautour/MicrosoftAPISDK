<?PHP
/**
 * Class file for MicrosoftAPIAuthentication
 * @package 		MicrosoftAPISDK
 */

/**
 * Class for authenticating against Microsoft APIs
 *
 * @author      Joshua Vautour <joshua@speedoflogic.com>
 * @version     0.1.0
 * @package			MicrosoftAPISDK
 *
 */
class MicrosoftAPIAuthentication extends RESTsol {

	/**
	 * Store the authorization token
	 * @var string
	 */
	protected $authTokenStr = null;

	/**
	 * Used to authenticate against the Microsoft Login API using OAuth2
	 * @param  string $authTenantIdStr        Azure Tenant Id
	 * @param  string $authClientIdStr        Azure Client Id
	 * @param  string $authClientSecretStr    Azure Client Secret
	 * @param  string $authResourceRequestStr Resource being requested with the authentication
	 * @param  string $authVersionStr         The version of the OAuth2 Login API
	 * @throws Exception 											Exception is thrown if an unsupported API version is provided
	 * @return boolean                        Returns true on sucessful login
	 */
	public function oauth2UsingProgramSecret($authTenantIdStr, $authClientIdStr, $authClientSecretStr, $authResourceRequestStr, $authVersionStr='v2.0'){

		logInfo("Calling public method oauth2UsingProgramSecret in class MicrosoftAPIAuthentication\n", 9);
		logInfo("Requestion permission to access: ".$authResourceRequestStr."\n", 9);

		$this->requestUrl = 'https://login.microsoftonline.com/'.$authTenantIdStr.'/oauth2/'.$authVersionStr.'/token';
		logInfo("Auth request URL: ".$this->requestUrl."\n", 5);

		/**
		 * At this time only v2.0 authtencitatoion is suppoted
		 */
		if ($authVersionStr == 'v2.0') {
			$authRequestData = 'grant_type=client_credentials&client_id='.$authClientIdStr.'&client_secret='.$authClientSecretStr.'&scope=https%3A%2F%2F'.$authResourceRequestStr.'%2F.default';
			logInfo("Auth request Data: ".$authRequestData."\n", 5);
		} else {
			logInfo("Error - unsupported auth version\n", 9);
			throw new Exception('Unsupported auth version');
		}

		/**
		 * build the curl handle
		 */
		$this->requestSetup();

		/**
		 * add the authentication headers to {@link $requestHeaders}
		 */
		logInfo("REST request Headers: ".serialize($this->requestHeaders)."\n", 9);

		/**
		 * Set the curl option for the quth request
		 */
		curl_setopt($this->requestHandle, CURLOPT_POST, 1);
		curl_setopt($this->requestHandle, CURLOPT_POSTFIELDS, $authRequestData);

		/**
		 * configure the standard curl options
		 */
		$this->requestConfigure();

		/**
		 * Execute the curl request
		 */
		$this->requestExecute();

		/**
		 * Decode the json results in {@link $requestResult} and save it to an array
		 * @var array
		 */
		$authResultsArr = json_decode($this->requestResult);

		/**
		 * Set {@link $authTokenStr} from the results of the successful authentication
		 * @var [type]
		 */
		$this->authTokenStr = $authResultsArr->access_token;

		/**
		 * Destroy the curl handle
		 */
		$this->requestDestroy();

		return true;
	}


	/**
	 * Public method to access the {@link $authTokenStr)
	 * @throws Exception 		It's trown if authentication has not yet been successfully completed
	 * @return string 		Microsoft API authorization token
	 */
	public function getAuthToken(){
		logInfo("Calling public method getAuthToken in class MicrosoftAPIAuthentication\n", 9);

		if (!$this->authTokenStr) {
			throw new Exception('Authentication has not been completed');
		}
		return $this->authTokenStr;
	}


	//	Public method to return the curl auth header

	/**
	 * Public method to get REST authentciation HTTP headers
	 * @throws Exception 		It's trown if authentication has not yet been successfully completed
	 * @return string 			The authorization header for a HTTP request
	 */
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
