<?php

    /**
     *  Dark Sky API
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi\Adapters;

    use GuzzleHttp\Client;
    use DarkSkyApi\Adapters\AdaptersInterface;
    use DarkSkyApi\DarkSkyApi;

    /**
     * Class GuzzleAdapter
     *
     * Fetches data from the Dark Sky API using Guzzle
     *
     * @package DarkSkyApi\Adapters
     */
    class GuzzleAdapter extends Adapter implements AdaptersInterface {
        use \DarkSkyApi\DarkSkyTrait;

        /**
         * @var Client
         */
        private $guzzleClient;

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
         * @param Client $guzzleClient
         */
        public function __construct(Client $guzzleClient = null) {
            if (NULL === $guzzleClient) {
                $guzzleClient = new Client();
            }
            $this->guzzleClient = $guzzleClient;
        } // End of function "__construct"

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

            $response = $this->guzzleClient->get($this->request_url);

            // Parse Data
            $this->data = json_decode($response->getBody(), TRUE);

            $cacheDirectives = $this->buildCacheDirectives($response);

            $this->response_headers = [
                'cache' => $cacheDirectives,
                'responseTime' => (int)$response->getHeader('x-response-time'),
                'apiCalls' => (int)$response->getHeader('x-forecast-api-calls')
            ];
            return $this->data;
        } // End of function "get_forecast"

        /**
         * Builds the cache directives from response headers.
         *
         * @param $response
         *
         * @return string[]
         */
        protected function buildCacheDirectives($response)
        {
            $cacheControlHeader = null;
            if ($response->hasHeader('cache-control')) {
                $cacheControlHeader = $this->parseHeader($response->getHeader('cache-control'));
                $cacheControlHeader = current($cacheControlHeader);
                $cacheControlHeader = (isset($cacheControlHeader['max-age'])?$cacheControlHeader['max-age']:null);
            }

            $expiresHeader = null;
            if ($response->hasHeader('expires')){
                $expiresHeader = implode(' ',array_column($this->parseHeader($response->getHeader('expires')),0));
            }

            return array_filter([
                'maxAge'=>$cacheControlHeader,
                'expires'=>$expiresHeader,
            ]);
        }

        /**
         * This is taken from the GuzzleHTTP/PSR7 library,
         * see https://github.com/guzzle/psr7 for more info
         *
         * Parse an array of header values containing ";" separated data into an
         * array of associative arrays representing the header key value pair
         * data of the header. When a parameter does not contain a value, but just
         * contains a key, this function will inject a key with a '' string value.
         *
         * @param string|array $header Header to parse into components.
         *
         * @return array Returns the parsed header values.
         */
        protected function parseHeader($header)
        {
            static $trimmed = "\"'  \n\t\r";
            $params = $matches = [];

            foreach ($this->normalizeHeader($header) as $val) {
                $part = [];
                foreach (preg_split('/;(?=([^"]*"[^"]*")*[^"]*$)/', $val) as $kvp) {
                    if (preg_match_all('/<[^>]+>|[^=]+/', $kvp, $matches)) {
                        $m = $matches[0];
                        if (isset($m[1])) {
                            $part[trim($m[0], $trimmed)] = trim($m[1], $trimmed);
                        } else {
                            $part[] = trim($m[0], $trimmed);
                        }
                    }
                }
                if ($part) {
                    $params[] = $part;
                }
            }

            return $params;
        }

        /**
         * This is taken from the GuzzleHTTP/PSR7 library,
         * see https://github.com/guzzle/psr7 for more info
         *
         * Converts an array of header values that may contain comma separated
         * headers into an array of headers with no comma separated values.
         *
         * @param string|array $header Header to normalize.
         *
         * @return array Returns the normalized header field values.
         */
        protected function normalizeHeader($header)
        {
            if (!is_array($header)) {
                return array_map('trim', explode(',', $header));
            }

            $result = [];
            foreach ($header as $value) {
                foreach ((array) $value as $v) {
                    if (strpos($v, ',') === false) {
                        $result[] = $v;
                        continue;
                    }
                    foreach (preg_split('/,(?=([^"]*"[^"]*")*[^"]*$)/', $v) as $vv) {
                        $result[] = trim($vv);
                    }
                }
            }

            return $result;
        }
    }
