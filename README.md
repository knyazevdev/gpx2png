# Gpx2Png

Php class that helps draw track and points on OSM maps.

## Install

Via Composer

``` bash
$ composer require knyazevdev/gpx2png
```

## Basic usage

``` php
require "vendor/autoload.php";
use Gpx2Png\Gpx2Png;

$gpx = new Gpx2Png();
$gpx2png->loadFile($gpxTrackFile);

$res = $gpx2png->generateImage();
$res->saveToFile($target_file);
```

## Credits

- [Alexey Knyazev](https://github.com/knyazevdev)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
