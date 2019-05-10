<?php

    /**
     *  Dark Sky API
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi\Adapters;

    use DarkSkyApi\Adapters\AdaptersInterface;
    use DarkSkyApi\DarkSkyApi;

    /**
     * Class TestAdapter
     *
     * Uses a test file for mocking an API response
     *
     * @package DarkSkyApi\Adapters
     */
    class TestAdapter extends Adapter implements AdaptersInterface {
        use \DarkSkyApi\DarkSkyTrait;

        /**
         * @var request_url
         */
        private $request_url = null;

        /**
         * @var response_headers
         */
        private $response_headers = [];

        /**
         * @var data
         */
        private $data = [];

        /**
         * Returns the response data from the Dark Sky API in the
         * form of an array.
         *
         * @param float $latitude
         * @param float $longitude
         * @param \DateTime $time
         * @param array $parameters
         *
         * @return array
         */
        public function get_forecast($latitude, $longitude, \DateTime $time = null, array $parameters = null) {
            $this->request_url = $this->build_forecast_url($latitude, $longitude, $time, $parameters);

            $response = file_get_contents(__DIR__.'/../../tests/forecast.json');
            $this->data = json_decode($response, TRUE);

            // $cacheDirectives = $this->buildCacheDirectives($response);

            $this->response_headers = [
                'cache' => [
                    'maxAge' => null,
                    'expires' => null
                ],
                'responseTime' => null,
                'apiCalls' => null
            ];

            return $this->data;
        } // End of function "get_forecast"

    } // End of class "TestAdapter"
