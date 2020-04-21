<?php


namespace Gpx2Png\Models;


class Track
{
    /**
     * @var Point[]
     */
    public $points;

    public function __construct()
    {
        $this->points = [];
    }

    /**
     * @return Bounds
     */
    public function getBounds()
    {
        $bounds = new Bounds();
        if (count($this->points)==1){
            $bounds->setByOnePoint($this->points[0]);
        }else{
            $bounds->setByPointsSet($this->points);
        }

        return $bounds;
    }

    public function getFirstPoint()
    {
        return $this->points[0];
    }

    public function getLastPoint()
    {
        return $this->points[count($this->points)-1];
    }

}