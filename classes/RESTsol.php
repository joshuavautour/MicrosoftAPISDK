<?PHP
/**
 * Contains the class RESTsol
 * @package     MicrosoftAPISDK
 */

/**
 * Base class REST requests
 * @author      Joshua Vautour <joshua@speedoflogic.com>
 * @version     0.1.0
 * @package     MicrosoftAPISDK
 */

class RESTsol {

        /**
         * @var resource $requestHandle         Curl Handle
         */
        protected $requestHandle = null;

        /**
         * @var array       Array of strings containing curl request headers
         */
        protected $requestHeaders = array (
                "'Accept': 'application/json'",
                "'Content-Type': 'application/json; charset=utf-8'",
                "'accept-language': en-US'",
                "'User-Agent': 'SOLPHPSDK/0.1'",
        );

        /**
         * REST request URL
         * @var string
         */
        protected $requestUrl = '';

        /**
         * REST request result
         * @var mixed
         */
        protected $requestResult = '';

        /**
         * Initializes the curl resource $requestHandle
         * @throws Exception if the PHP curl extension is not installed
         * @return void
         */
        protected function requestSetup() {
                if (!extension_loaded('curl')) {
                        throw new Exception('The PHP exention curl must be installed');
                }
                $this->requestHandle = curl_init();
                logInfo("Creating curl handle\n", 5);
        }

        /**
         * Set the standard request options
         * @throws Exception if the curl handle {@link $requestHandle} has not been initialized
         * @return void
         */
        protected function requestConfigure() {

                /**
                 * Make sure the curl handle is valid
                 */
                if (!is_resource($this->requestHandle)) {
                          logInfo("Attempting to configure a curl handle that is not initialized\n", 1);
                          throw new Exception('The curl handle has not been initialized');
                }

                /**
                 * Set curl URL and header data
                 */
                logInfo("Configuring standard curl parameters\n", 5);
                curl_setopt($this->requestHandle, CURLOPT_URL, $this->requestUrl);
                curl_setopt($this->requestHandle, CURLOPT_HTTPHEADER, $this->requestHeaders);
                curl_setopt($this->requestHandle, CURLOPT_RETURNTRANSFER, 1);
        }

        /**
         * Destroys the curl handle {@link $requestHandle}
         * @throws Exception if the curl handle {@link $requestHandle} has not been initialized
         * @return void
         */
        protected function requestDestroy() {
                /**
                 * Make sure the curl handle is valid
                 */
                if (!is_resource($this->requestHandle)) {
                          throw new Exception('The curl handle has not been initialized');
                }

                curl_close($this->requestHandle);
                logInfo("Destroying curl handle\n", 5);
        }

        /**
         * Peforms a curl_exec on handle {@link $requestHandle}
         * @throws Exception if the curl handle {@link $requestHandle} has not been initialized
         * @throws Exception if the curl request fails to execute successfully
         * @return boolean  Returns true on success or false on a failure
         */
        protected function requestExecute() {
                logInfo("Executing curl request\n", 5);

                /**
                 * Make sure the curl handle is valid
                 */
                if (!is_resource($this->requestHandle)) {
                          throw new Exception('The curl handle has not been initialized');
                }

                $this->requestResult = curl_exec($this->requestHandle);
                if(!$this->requestResult) {
                        logInfo("Curl request failed with message: ".curl_error($this->requestHandle)."\n", 5);
                        throw new Exception('Curl request failed');
                        return false;
                }
                logInfo("Curl request successful: ".serialize(curl_getinfo($this->requestHandle))."\n", 5);
                return true;
        }
}
