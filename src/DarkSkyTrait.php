<?php

    /**
     *  Dark Sky API
     *
     *  @author Jiminald <code@jiminald.co.uk>
     */

    namespace DarkSkyApi;

    /**
     * Trait DarkSkyTrait
     *
     * Common functions used mainly for Data returned via the Dark Sky API
     *
     * @package DarkSkyApi\DarkSkyApi
     */
    trait DarkSkyTrait {

        /**
         * Magically check for property inside a method, with fallback to data array
         *
         * @param string $key
         *
         * @return mixed
         */
        public function __get($key) {
            // Check for property in this class
            if (property_exists($this, $key) == TRUE) {
                return $this->$key;
            } // End of if "Do we have a property that matches this"

            // Check to see if the key exists in returned data
            if ((property_exists($this, 'data') == TRUE) && (array_key_exists($key, $this->data) == TRUE)) {
                return $this->data[$key];
            } // End of if "Do we have a data ket that matches this"

            // Default, return FALSE
            return FALSE;
        } // End of function "__get"

        /**
         * Magically allow methods and array keys to be called as a function, and returned as an object
         *
         * @param string $function
         * @param array $params
         *
         * @return object
         */
        public function __call($function, $params = []) {
            // Sanitize function name
            $function = str_replace('get', '', $function);
            $function = strtolower($function);

            // Return data
            return (object)$this->$function;
        } // End of function "__call"

    } // End of trait "DarkSkyApi"
