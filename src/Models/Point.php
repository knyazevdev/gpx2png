<?php


namespace Gpx2Png\Models;


use Gpx2Png\Helper\Geo;

class Point
{
    public $latitude;
    public $longitude;
    public $elevation;
    public $time;
    public $distance;
    public $heartrate;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function toCoordinateString()
    {
        return $this->latitude.", ".$this->longitude;
    }

    public function getDistanceToPoint(Point $point)
    {
        return Geo::getDistance($this, $point);
    }

}