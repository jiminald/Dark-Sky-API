<?php

    /**
     *  Dark Sky API
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi\Data;

    use DarkSkyApi\DarkSkyApi;

    /**
     * Class Forecast
     *
     * Holds all of the Forecast data recieved over the Dark Sky API
     *
     * @package DarkSkyApi\Data
     */
    class Forecast {
        use \DarkSkyApi\DarkSkyTrait;

        /**
         * @var data
         */
        private $data = [];

        /**
         * @var headers
         */
        private $headers = [];

        /**
         * Holds the response from the Dark Sky API
         *
         * @param array $data
         * @param array $headers
         *
         * @return self
         */
        public function __construct($data, $headers = []) {
            $this->data = $data;
            $this->headers = $headers;

            return $this;
        } // End of function "__construct"

    } // End of class "Forecast"
