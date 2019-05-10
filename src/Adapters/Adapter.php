<?php

    /**
     *  Dark Sky API
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi\Adapters;

    use DarkSkyApi\DarkSkyApi;

    /**
     * Class Adapter
     *
     * Provides common functions shared between all adapters
     *
     * @package DarkSkyApi\Adapters
     */
    abstract class Adapter {

        /**
         * Builds the URL to request from the Dark Sky API
         *
         * @param float $latitude
         * @param float $longitude
         * @param \DateTime|NULL $time
         * @param array|NULL $parameters
         *
         * @return string
         */
        protected function build_forecast_url($latitude, $longitude, \DateTime $time = NULL, array $parameters = NULL) {
            $requestUrl = DarkSkyApi::FORECAST_API_ENDPOINT . DarkSkyApi::get_api_key() . '/' . $latitude . ',' . $longitude;
            if (NULL !== $time) {
                $requestUrl .= ',' . $time->getTimestamp();
            }
            $requestUrl .= '?' . http_build_query($parameters);
            return $requestUrl;
        } // End of function "build_forecast_url"

    } // End of abstract class "Adapter"
