# DARK SKY API

Grab weather forecast information using Dark Sky's API

## You will need

- A [Dark Sky API Key](https://darksky.net/dev)

## Installation
You can install this using Composer
```json
{
    "require": {
        "DarkSkyApi": "dev-master"
    }
}
```

## Adapters

By default, the class uses Guzzle to fetch data from the Dark Sky API servers.
You can change this to use PHP's `file_get_contents`, by adding "FGC" to the construct
```php
<?php
$dark_sky = new \DarkSkyApi\DarkSkyApi('API KEY', 'FGC');
```

### While Developing

The code is shipped with some test data use for automated tests, which is useful to use while developing, saving your API allowance. Change your adapter to "Test", to use the sample data.

## Example

```php
<?php
require_once __DIR__.'/../vendor/autoload.php';

$dark_sky = new \DarkSkyApi\DarkSkyApi('API KEY');
$forecast = $dark_sky->get_forecast('LATITUDE', 'LONGITUDE');

var_dump($forecast->getHourly());
```

## Inspiration

This class is loosely based on https://github.com/jaimz22/Overcast

I found that Dark Sky had changed their API since Overcast was last updated, and I wanted to make it an easier experience in case they changed their format again.
