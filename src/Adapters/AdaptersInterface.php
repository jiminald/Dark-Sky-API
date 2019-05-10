<?php

    /**
     *  Dark Sky API
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi\Adapters;

    /**
     * Interface AdaptersInterface
     *
     * Ensures all adapters conform to the same
     * structure, for interchanabilitybetween adapters
     *
     * @package DarkSkyApi\Adapters
     */
    interface AdaptersInterface {
        public function get_forecast($latitude, $longitude, \DateTime $time = null, array $parameters = null);
    } // End of Interface "AdaptersInterface"
