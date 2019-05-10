<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;


final class DarkSkyApiTest extends TestCase {
    private $adapter = 'Test';
    private $api_key = '3640c93c42472e99b813f210eae36227';
    private $lat = '50.5469';
    private $lon = '-4.9192';

    public function testNewDarkSkyInstance(): void {
        $dark_sky = new \DarkSkyApi\DarkSkyApi($this->api_key, $this->adapter);

        $this->assertInstanceOf(
            \DarkSkyApi\DarkSkyApi::class,
            $dark_sky
        );
    } // End of function "testNewDarkSkyInstance"

    public function testGetForecast(): void {
        $dark_sky = new \DarkSkyApi\DarkSkyApi($this->api_key, $this->adapter);

        $forecast = $dark_sky->get_forecast($this->lat, $this->lon, NULL, ['exclude' => 'minutely', 'extend' => 'hourly', 'units' => 'uk2']);

        // Validate we have a forecast object
        $this->assertInstanceOf(
            \DarkSkyApi\Data\Forecast::class,
            $forecast
        );
    } // End of function "testGetForecast"

    public function testForecastAsClass(): void {
        $dark_sky = new \DarkSkyApi\DarkSkyApi($this->api_key, $this->adapter);

        $forecast = $dark_sky->get_forecast($this->lat, $this->lon, NULL, ['exclude' => 'minutely', 'extend' => 'hourly', 'units' => 'uk2']);

        // Get hourly object
        $this->assertInstanceOf(
            \stdClass::class,
            $forecast->getHourly()
        );

        // Get icon text
        $this->assertEquals(
            'partly-cloudy-night',
            $forecast->getHourly()->icon
        );

        // Get data array from hourly object
        $this->assertIsArray(
            $forecast->getHourly()->data
        );

        // Check values in data array are good
        $this->assertEquals(
            'Partly Cloudy',
            $forecast->getHourly()->data[0]['summary']
        );
        $this->assertEquals(
            '10.34',
            $forecast->getHourly()->data[0]['apparentTemperature']
        );
        $this->assertEquals(
            '50',
            $forecast->getHourly()->data[0]['windBearing']
        );
    } // End of function "testForecastAsClass"

    public function testForecastAsArray(): void {
        $dark_sky = new \DarkSkyApi\DarkSkyApi($this->api_key, $this->adapter);

        $forecast = $dark_sky->get_forecast($this->lat, $this->lon, NULL, ['exclude' => 'minutely', 'extend' => 'hourly', 'units' => 'uk2']);

        // Get hourly object
        $this->assertIsArray(
            $forecast->hourly
        );

        // Get icon text
        $this->assertEquals(
            'partly-cloudy-night',
            $forecast->hourly['icon']
        );

        // Get data array from hourly object
        $this->assertIsArray(
            $forecast->hourly['data']
        );

        // Check values in data array are good
        $this->assertEquals(
            'Partly Cloudy',
            $forecast->hourly['data'][0]['summary']
        );
        $this->assertEquals(
            '10.34',
            $forecast->hourly['data'][0]['apparentTemperature']
        );
        $this->assertEquals(
            '50',
            $forecast->hourly['data'][0]['windBearing']
        );
    } // End of function "testForecastAsArray"


    //
    // public function testCannotBeCreatedFromInvalidEmailAddress(): void
    // {
    //     $this->expectException(InvalidArgumentException::class);
    //
    //     Email::fromString('invalid');
    // }
    //
    // public function testCanBeUsedAsString(): void
    // {
    //     $this->assertEquals(
    //         'user@example.com',
    //         Email::fromString('user@example.com')
    //     );
    // }
}
