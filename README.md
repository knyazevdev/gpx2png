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

## Extend usage

``` php
require "vendor/autoload.php";
use Gpx2Png\Gpx2Png;
use Gpx2Png\Models\Overlays\DrawParamsPoint;
use Gpx2Png\Models\Point;

// prepare points set from own source 

$points = array();
foreach ($mypoints as $mypoint) {
    $points[] = new Point($mypoint['lat'], $mypoint['lon'], $point['timestamp']);
}

// load points

$gpx2png->loadPoints($points);

// set custom draw params

$gpx2png->drawParams->track->color = "black";
$gpx2png->drawParams->track->opacity = "0.3";
$gpx2png->drawParams->track->startPoint->color = "yellow";
$gpx2png->drawParams->track->distanceLabel->text_size = 20;
$gpx2png->drawParams->track->distanceLabel->text_color = 'red';

// set osm source type

$gpx2png->setMapSourceName("osm_topo");

// set custom tiles cache directory
// default: sys_get_temp_dir().'/tiles'

$gpx2png->mapSource->setTilesDirectory(__DIR__.'/tiles');

// receive result

$res = $gpx2png->generateImage();

// add extra overlays

$extraPoint = $points[mt_rand(0, count($points)-1)];
$drawParamsPoint = new DrawParamsPoint();
$drawParamsPoint->setTemplate(DrawParamsPoint::TPL_LIVE_POINT);

$res->image->drawPoint($extraPoint, $drawParamsPoint);

// save or output file

$res->saveToFile("result.png");
$res->output();

```


## Credits

- [Alexey Knyazev](https://github.com/knyazevdev)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
