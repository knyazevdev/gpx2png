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

    public function __construct($latitude, $longitude, $time = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        if ($time!==null){
            $this->time = is_numeric($time) ? date('Y-m-d H:i:s', $time) : $time;
        }
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