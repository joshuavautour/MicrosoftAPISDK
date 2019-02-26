<?PHP
/**
 * Contains the class RESTsol
 *
 * @author      Joshua Vautour <joshua@speedoflogic.com>
 * @version     0.1.0
 * @package     MicrosoftAPISDK
 */

/**
 * Base class REST requests
 *
 * @package     MicrosoftAPISDK
 *
 */

class RESTsol {

        /*
         * @var resource $requestHandle         Curl Handle
         */
        protected $requestHandle = null;

        /*
         * @var array $authHeaderData           Array of strings containing curl request headers
         */
        protected $authHeaderData = array (
                "'Accept': 'application/json'",
                "'Content-Type': 'application/json; charset=utf-8'",
                "'accept-language': en-US'",
                "'User-Agent': 'SOLPHPSDK/0.1'",
        );

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
         * Destroys the curl handle {@link $requestHandle}
         * @return void
         */
        protected function requestDestroy() {
                curl_close($this->requestHandle);
                logInfo("Destroying curl handle\n", 5);
        }

        /**
         * Peforms a curl_exec on handle {@link $requestHandle}
         * @throws Exception if the curl request fails to execute successfully
         * @return mixed Returns the transfer data on success and false on failure
         */
        protected function requestExecute() {
                logInfo("Executing curl request\n", 5);

                //      Configure curl to return the result
                curl_setopt($this->requestHandle, CURLOPT_RETURNTRANSFER, 1);

                $curlResultMix = curl_exec($this->requestHandle);
                if(!$curlResultMix) {
                        logInfo("Curl request failed with message: ".curl_error($this->requestHandle)."\n", 5);
                        throw new Exception('Curl request failed');
                }
                logInfo("Curl auth request successful: ".serialize(curl_getinfo($this->requestHandle))."\n", 5);
                return $curlResultMix;
        }
}
