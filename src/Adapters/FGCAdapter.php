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
     * Class FGCAdapter
     *
     * Fetches data from the Dark Sky API using file_get_contents
     *
     * @package DarkSkyApi\Adapters
     */
    class FGCAdapter extends Adapter implements AdaptersInterface {
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

            $response = file_get_contents($this->request_url);
            $this->data = json_decode($response, TRUE);

            // $cacheDirectives = $this->buildCacheDirectives($response);

            $this->response_headers = $this->parse_headers($http_response_header);

            return $this->data;
        } // End of function "get_forecast"

        /**
         * Parses the response headers
         *
         * @param array $headers
         *
         * @return array
         */
        private function parse_headers($headers) {
            $response_headers = [
                'cache' => [
                    'maxAge' => null,
                    'expires' => null
                ],
                'responseTime' => null,
                'apiCalls' => null
            ];
            foreach ($headers as $header) {
                switch (true) {
                    case (substr($header, 0, 14) === 'Cache-Control:'):
                        $response_headers['cache']['maxAge'] = trim(substr($header, strrpos($header, '=') + 1));
                        break;
                    case (substr($header, 0, 8) === 'Expires:'):
                        $response_headers['cache']['expires'] = trim(substr($header, 8));
                        break;
                    case (substr($header, 0, 21) === 'X-Forecast-API-Calls:'):
                        $response_headers['apiCalls'] = trim(substr($header, 21));
                        break;
                    case (substr($header, 0, 16) === 'X-Response-Time:'):
                        $response_headers['responseTime'] = (int)trim(substr($header, 16));
                        break;
                    default:
                        break;
                }
            }
            return $response_headers;
        } // End of function "parse_headers"

    } // End of class "FGCAdapter"
