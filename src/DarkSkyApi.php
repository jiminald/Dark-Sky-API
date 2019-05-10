<?php

    /**
     *  Dark Sky API
     *
     *  This package allows you to call the Dark Sky API and parse the returned data
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi;

    /**
     * Class DarkSkyApi
     *
     * The DarkSkyApi class provides access to the Dark Sky API
     *
     * @package DarkSkyApi\DarkSkyApi
     */
    class DarkSkyApi {

        const FORECAST_API_ENDPOINT = 'https://api.darksky.net/forecast/';

        /**
         * API Key for Dark Sky API
         * @var string
         */
        private static $api_key;

        /**
         * The number of API calls made today, from Dark Sky
         * @var int
         */
        private $api_calls = 0;

        /**
         * The adapter used to connect to the Dark Sky API.
         * @var Adapters\AdaptersInterface
         */
        private $adapter;

        /**
         * Construct the DarkSkyApi class.
         *
         * Provide your Dark Sky API key, and the preferred
         * adapter to connect to the Dark Sky API. By default,
         * the Guzzle adapter is used
         *
         * @param string $api_key
         * @param string $adapter
         */

        public function __construct($api_key, $adapter = 'Guzzle') {
            // Store API Key
            self::$api_key = $api_key;

            // Load adapter
            $adapter = 'DarkSkyApi\\Adapters\\'.$adapter.'Adapter';
            $this->adapter = new $adapter;
        } // End of function "__construct"

        /**
         * Retrieve the forecast for the specified latitude and longitude and
         * optionally the specified date/time and any other parameters.
         *
         * @param $latitude
         * @param $longitude
         * @param \DateTime $time
         * @param array $parameters
         *
         * @return Data\Forecast
         */
        public function get_forecast($latitude, $longitude, \DateTime $time = null, array $parameters = null) {
            try {
                $response = $this->adapter->get_forecast($latitude, $longitude, $time, $parameters);
                $response_headers = $this->adapter->response_headers;

                // Check to see if we know how many API calls we've used
                if (array_key_exists('apiCalls', $response_headers) == TRUE) {
                    $this->api_calls = $response_headers['apiCalls'];
                } // End of if "Checking for API call count"

                // Return Forecast
                return new Data\Forecast($response, $response_headers);
            } catch (\Exception $e) {
                return null;
            } // End of try "Getting forecast data"
        } // End of function "get_forecast"


        /**
         * Returns the current API key
         *
         * @return string
         */
        public static function get_api_key() {
            return self::$api_key;
        } // End of function "get_api_key"

    } // End of class "DarkSkyApi"
